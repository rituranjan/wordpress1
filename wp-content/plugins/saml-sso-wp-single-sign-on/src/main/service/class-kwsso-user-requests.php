<?php
/**
 * User SSO Login Service.
 *
 * @package keywoot-saml-sso/service
 */

use KWSSO_CORE\Traits\Instance;
use Symfony\Component\HttpFoundation\Request;
use LightSaml\Model\Assertion;
use LightSaml\Model\Assertion\Issuer;
use LightSaml\Model\Protocol\NameIDPolicy;
use LightSaml\Binding\BindingFactory;
use LightSaml\Context\Profile\MessageContext;
use LightSaml\SamlConstants;
use LightSaml\Helper;
use LightSaml\Model\Protocol\AuthnRequest;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'KWSSO_UserRequests' ) ) {
	/**
	 * Class KWSSO_UserRequests
	 *
	 * Handles SSO requests and responses for SAML-based single sign-on.
	 */
	class KWSSO_UserRequests {

		use Instance;
		/**
		 * @var KWSSO_IDPConf $idp_configuration Configuration details for the Identity Provider (IdP).
		 */
		private $idp_configuration;

		/**
		 * KWSSO_UserRequests constructor.
		 *
		 * Initializes the Identity Provider configuration and hooks the main wrapper function to the `init` action.
		 */
		public function __construct() {
			$this->idp_configuration = new KWSSO_IDPConf();
			add_action( 'init', array( $this, 'kwsso_main_wrapper' ), 0 );
		}

		/**
		 * Main wrapper function for KWSSO.
		 *
		 * Routes the SAML request handling and handles any exceptions.
		 */
		public function kwsso_main_wrapper() {
			try {
				 $this->keywoot_route();
			} catch ( Exception $ex ) {
				wp_die( $ex->getMessage() );
			}
		}

		/**
		 * Determines and routes the SAML request based on the action.
		 *
		 * Initiates the SSO flow or handles the SAML response depending on the request parameters.
		 */
		public function keywoot_route() {
			if ( $this->idp_configuration->get_is_idp_enabled() == 'true' ) {
				$request = Request::createFromGlobals();
				$this->kwsso_initiate_sso_flow( $request );
				if ( ! empty( sanitize_text_field( wp_unslash( $request->get( 'SAMLResponse' ) ) ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- false positive.
					$this->kwsso_handle_saml_response();
				}
			}

		}

		/**
		 * Initiates the SSO flow if the action is valid.
		 *
		 * Validates the action parameter and initiates SAML login if the conditions are met.
		 *
		 * @param Request $request The HTTP request object containing parameters.
		 */
		public function kwsso_initiate_sso_flow( $request ) {
			$request_option = sanitize_text_field( wp_unslash( $request->get( 'kwsso_action' ) ) );
			if ( empty( $request_option ) || ! in_array( $request_option, array( 'kwsso_sso_user_login', 'kw-test-idp-config' ), true ) ) {
				return;
			}
			$this->kwsso_check_admin_authority( $request_option );
			if ( is_user_logged_in() && $request_option === 'kwsso_sso_user_login' ) {
				$redirect_to_url = sanitize_text_field( wp_unslash( $request->get( 'redirect_to' ) ) );
				if ( ! empty( $redirect_to_url ) ) {
					wp_safe_redirect( htmlspecialchars( $redirect_to_url ) );
					exit();
				}
				return;
			}

			$this->handle_saml_login( $request );
		}

		private function kwsso_check_admin_authority( $request_option ) {
			if ( 'kw-test-idp-config' === $request_option && ( ! is_user_logged_in() || ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) ) ) {
				wp_die( 'You are not authorized to perform this operation. Please contact your administrator.' );
			}
		}

		public function kwsso_handle_saml_response() {
			$kwsso_relay_data_encoded = ( ! empty( $_POST['RelayState'] ) ) ? sanitize_text_field( wp_unslash( $_POST['RelayState'] ) ) : '';// phpcs:ignore WordPress.Security.NonceVerification.Missing -- external request hence nonce verification not needed.
			$decoded_data             = base64_decode( $kwsso_relay_data_encoded, true );
			if ( ! $decoded_data ) {
				return; // Base64 decoding failed
			}

			$relay_state_data = json_decode( $decoded_data, true );
			if ( ! is_array( $relay_state_data ) ) {
				return; // JSON decoding failed
			}
			if ( ! isset( $relay_state_data['initiator'] ) || $relay_state_data['initiator'] != 'keywoot' ) {
				return;
			}
			$kwsso_relay_state = ( ! empty( $relay_state_data['relay_state'] ) && $relay_state_data['relay_state'] != '/' ) ? $relay_state_data['relay_state'] : '';

			$kwsso_saml_response = new KWSSO_ResponseValidator();
			$kwsso_saml_response->kwsso_validate_response();
			$sp_base_url     = KWSSO_Utils::kwsso_get_sp_base_url();
			$attrs           = $kwsso_saml_response->kwsso_get_attributes();
			$name_id_value   = $kwsso_saml_response->kwsso_get_name_id_value();
			$attrs['NameID'] = $name_id_value;
			$user_login      = new KWSSO_UserLoginService();
			$user_login->kwsso_handle_successful_auth( $attrs, $kwsso_relay_state, $sp_base_url );
		}

		/**
		 * Handles the SAML login by creating and sending an authentication request.
		 *
		 * Configures and sends an AuthnRequest to the IdP based on the user's current session and configuration settings.
		 *
		 * @param Request $request The HTTP request object containing parameters.
		 * @throws Exception If any error occurs during the SAML login process.
		 */
		public function handle_saml_login( $request ) {
			try {
				if ( ! kwsso_check_if_sp_configured() ) {
					return;
				}
				$sp_metadata              = new KWSSO_SPMetadata();
				$kwsso_relay_state        = KWSSO_RelayStateHelper::kwsso_determine_relay_state( $request );
				$identity_provider_name   = ! empty( $request->get( 'idp' ) ) ? htmlspecialchars( $request->get( 'idp' ) ) : '';
				$kwsso_saml_login_url     = ! empty( $this->idp_configuration->get_login_url() ) ? $this->idp_configuration->get_login_url() : '';
				$kwsso_saml_login_url     = ! is_array( $kwsso_saml_login_url ) ? esc_url_raw( $kwsso_saml_login_url ) : $kwsso_saml_login_url;
				$sso_binding_type         = $this->idp_configuration->get_login_binding_type();
				$sp_acs_url               = $sp_metadata->get_sp_base_url() . '/';
				$sp_entity_id             = $sp_metadata->get_sp_entity_id();
				$kwsso_saml_nameid_format = $this->idp_configuration->get_nameid_format();
				$kwsso_saml_nameid_format = empty( $kwsso_saml_nameid_format ) ? KwNameIdFormatConst::getFormat( 'UNSPECIFIED' ) : $kwsso_saml_nameid_format;
				$kwsso_authn_request_id   = Helper::generateID();
				KWSSO_SessionStore::add_store_value( 'saml_request_id', $kwsso_authn_request_id );
				$kwsso_relay_state = $this->generate_relay_state_data( $kwsso_relay_state );
				$authn_request     = new AuthnRequest();
				$authn_request
					->setAssertionConsumerServiceURL( $sp_acs_url )
					->setID( $kwsso_authn_request_id )
					->setIssueInstant( new \DateTime() )
					->setDestination( $kwsso_saml_login_url )
					->setIssuer( new Issuer( $sp_entity_id ) )
					->setRelayState( $kwsso_relay_state );

				$authn_request = $this->kwsso_set_nameid_policy( $authn_request );
				$this->kwsso_send_sso_request( $authn_request, $sso_binding_type );
			} catch ( Exception $e ) {
				wp_die( 'An error occurred during SAML login. Please contact support.' . 'SAML login error: ' . $e->getMessage() );
			}
		}
		public function generate_relay_state_data( $relay_state ) {
			$relay_state_data = array(
				'relay_state' => $relay_state,
				'initiator'   => 'keywoot',
			);
			$relay_state      = base64_encode( wp_json_encode( $relay_state_data ) ); // Encode to make it URL-safe
			return $relay_state;
		}

		public function kwsso_set_nameid_policy( $authn_request ) {
			$nameid_policy = new NameIDPolicy();
			$nameid_policy->setAllowCreate( true )->setFormat( SamlConstants::NAME_ID_FORMAT_UNSPECIFIED );
			$authn_request->setNameIDPolicy( $nameid_policy );
			return $authn_request;
		}
		/**
		 * Sends the SAML request using the specified binding type (HTTP-POST or HTTP-Redirect).
		 *
		 * @param \LightSaml\Model\Protocol\AuthnRequest $authn_request The authentication request object to send.
		 * @param string                                 $binding_type The type of binding to use (either 'HTTP-POST' or 'HTTP-Redirect').
		 * @throws Exception If an invalid SAML binding type is specified.
		 */
		private function kwsso_send_sso_request( $authn_request, $binding_type ) {
			$binding_factory = new BindingFactory();
			$message_context = new MessageContext();
			$message_context->setMessage( $authn_request );

			switch ( $binding_type ) {
				case 'HTTP-POST':
					$post_binding  = $binding_factory->create( SamlConstants::BINDING_SAML2_HTTP_POST );
					$http_response = $post_binding->send( $message_context );
					echo $http_response->getContent();
					break;
				case 'HTTP-Redirect':
					$redirect_binding = $binding_factory->create( SamlConstants::BINDING_SAML2_HTTP_REDIRECT );
					$http_response    = $redirect_binding->send( $message_context );
					wp_redirect( esc_url_raw( $http_response->getTargetUrl() ) );
					break;
				default:
					throw new Exception( 'Invalid SAML binding type' );
			}
			exit;
		}
	}

}
