<?php
/**
 * Comman Service .
 *
 * @package keywoot-saml-sso/service
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use KWSSO_CORE\Traits\Instance;

/**
 * This class handles all the Admin related actions of the user related to the
 */
if ( ! class_exists( 'KWSSO_AdminActions' ) ) {
	/**
	 * KWSSO_AdminActions class
	 */
	class KWSSO_AdminActions {

		use Instance;
		/**
		 * Initializes values
		 */
		private $sp_metadata;

		private $idp_configuration;

		protected function __construct() {
			$this->sp_metadata       = new KWSSO_SPMetadata();
			$this->idp_configuration = new KWSSO_IDPConf();
			$this->initialize_hooks();
			$this->kwsso_sp_metadata_handler();

		}
		/**
		 * Initialize Hooks
		 *
		 * @return void
		 */
		public function initialize_hooks() {
			add_action( 'admin_init', array( $this, 'kwsso_handle_admin_form_actions' ) );
		}


		/**
		 * Handles the download of metadata based on the provided referer check.
		 */
		public function kwsso_handle_metadata_download() {
			$this->sp_metadata->kwsso_process_sp_metadata( true );
		}
		/**
		 * Handles various admin form actions based on the submitted 'kwsso_action' parameter.
		 */
		public function kwsso_handle_admin_form_actions() {
			$action = get_value_from_post('kwsso_action');
			if ( ! $action || $action == null || ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$action_map = KWSSO_FormRoutes::get_admin_action_map( 'KWSSO_AdminActions' );
			if ( ! isset( $action_map[ $action ] ) ) {
				return;
			}
			if ( ! check_admin_referer( $action_map[ $action ]['nonce'] ) ) {
				wp_die( esc_attr( KeywootMessage::getMessage('INVALID_OPERATION') ) );
			}
			$method = $action_map[ $action ]['method'];
			$this->$method();
		}
		/**
		 * Handles the metadata request for Keywoot service provider (SP).
		 * If the 'option' parameter is set to 'kwsso_metadata', it generates SP metadata.
		 */
		public function kwsso_sp_metadata_handler() {
			if ( isset( $_GET['kwsso_action'] ) && 'kwsso-fetch-sp-metadata' === $_GET['kwsso_action'] ) {  //phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the form name, doesn't require nonce verification.
				$this->sp_metadata->kwsso_process_sp_metadata();
			}
		}


		/**
		 * Handles the saving of Identity Provider (IDP) configurations from the admin form.
		 *
		 * This function validates and saves IDP settings including name, URL, binding type, issuer,
		 * x509 certificate, and other related options.
		 */
		private function handle_save_idp_configurations() {
			if ( ! kwsso_is_extension_installed( 'curl' ) ) {
				KWSSO_Display::kwsso_display_admin_notice( 'ERROR:' . KeywootMessage::getMessage('CURL_DISABLED'), KWSSO_ERROR );
				return;
			}
			if ( ! $this->check_fields_validation() ) {
				return;
			}

			$kwsso_saml_identity_name        = sanitize_text_field( get_value_from_post( KwConstants::getConstant('KWSSO_IDP_NAME'), 'Custom' ) );
			$kwsso_saml_login_url            = esc_url_raw( trim( get_value_from_post( KwConstants::getConstant('LOGIN_URL') ) ) );
			$kwsso_saml_login_binding_type   = sanitize_text_field( get_value_from_post( KwConstants::getConstant('LOGIN_BINDING_TYPE'), 'HTTP-Redirect' ) );
			$kwsso_saml_issuer               = esc_url_raw( trim( get_value_from_post( KwConstants::getConstant('ISSUER') ) ) );
			$kwsso_saml_x509_certificate     = get_value_from_post( KwConstants::getConstant('X509_CERTIFICATE') );
			$kwsso_saml_nameid_format        = sanitize_text_field( get_value_from_post( KwConstants::getConstant('NAMEID_FORMAT'), KwNameIdFormatConst::getFormat('UNSPECIFIED') ) );
			$kwsso_assertion_time_validation = sanitize_text_field( get_value_from_post( KwConstants::getConstant('ASSERTION_TIME_VALIDATION'), true ) );
			$kwsso_idp_meatdata_url          = esc_url_raw( trim( get_value_from_post( 'metadata_url' ) ) );
			$kwsso_idp_key        			 = sanitize_text_field( get_value_from_post('kwsso_saml_idp_key','custom-idp'  ) );


			if ( ! KWSSO_Utils::check_and_update_x509_certifciate( $this->idp_configuration, $kwsso_saml_x509_certificate ) ) {
				return;
			}
			$this->idp_configuration->set_idp_name( $kwsso_saml_identity_name );
			$this->idp_configuration->set_login_url( $kwsso_saml_login_url );
			$this->idp_configuration->set_login_binding_type( $kwsso_saml_login_binding_type );
			$this->idp_configuration->set_issuer( $kwsso_saml_issuer );
			$this->idp_configuration->set_nameid_format( $kwsso_saml_nameid_format );
			$this->idp_configuration->set_request_signed( false );
			$this->idp_configuration->set_is_idp_enabled( true );
			$this->idp_configuration->set_assertion_time_validation( $kwsso_assertion_time_validation );
			$this->idp_configuration->set_idp_metadata_url( $kwsso_idp_meatdata_url );
			update_kwsso_option( KwConstants::getConstant('KWSSO_IDP_KEY'),$kwsso_idp_key );
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('IDP_SAVED'), KWSSO_SUCCESS );
		}

		private function kwsso_confirm_remove_idp_config() {
			delete_kwsso_option( 'kwsso_idp_conf' );
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('IDP_REMOVED'), KWSSO_SUCCESS );
		}
		/**
		 * Checks the validation of fields for idp settings.
		 * It checks if essential fields are not empty and if the Identity Provider Name matches a specific format.
		 *
		 * @return bool Returns true if all fields pass validation; otherwise, false.
		 */
		private function check_fields_validation(): bool {
			$idp_name  = get_value_from_post( KwConstants::getConstant('KWSSO_IDP_NAME') );
			$login_url = get_value_from_post( KwConstants::getConstant('LOGIN_URL') );
			$issuer    = get_value_from_post( KwConstants::getConstant('ISSUER') );
			if ( empty( $idp_name ) || empty( $login_url ) || empty( $issuer ) ) {
				KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('REQUIRED_FILEDS'), KWSSO_ERROR );
				return false;
			}
			return true;
		}

		/**
		 * Handles the action to clear specific attributes from the configuration.
		 *
		 * This function removes test configuration attributes based on admin form submission.
		 */
		private function kwsso_clear_attributes() {
			delete_kwsso_option( KwConstants::getConstant('TEST_CONFIG_ATTIBUTES') );
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('ATTR_LIST_CLR'), KWSSO_SUCCESS );
		}
		/**
		 * Handles saving of Service Provider (SP) core configuration settings.
		 *
		 * This function validates and updates SP base URL and entity ID options from the admin form.
		 *
		 * @return void
		 */
		private function handle_save_sp_core_config() {
			if ( ! empty( get_value_from_post( KwConstants::getConstant('SP_BASE_URL') ) ) && ! empty( get_value_from_post( KwConstants::getConstant('SP_ENTITY_ID') ) ) ) {
				$sp_base_url  = htmlspecialchars( get_value_from_post( KwConstants::getConstant('SP_BASE_URL') ) );
				$sp_entity_id = htmlspecialchars( get_value_from_post( KwConstants::getConstant('SP_ENTITY_ID') ) );
				if ( substr( $sp_base_url, -1 ) == '/' ) {
					$sp_base_url = substr( $sp_base_url, 0, -1 );
				}
				$this->sp_metadata->set_sp_base_url( $sp_base_url );
				$this->sp_metadata->set_sp_entity_id( $sp_entity_id );
			}
			KWSSO_Display::kwsso_display_admin_notice( 'SP Settings updated successfully.', KWSSO_SUCCESS );
		}

		private function show_notice_if_sp_not_configured() {
			if ( ! kwsso_check_if_sp_configured() ) {
				KWSSO_Display::kwsso_display_admin_notice( 'You haven\'t completed the IDP setup in the plugin yet. Please complete it before proceeding', KWSSO_ERROR );
				return true;
			}
		}
		/**
		 * Saves the option to use the button as a shortcode.
		 * This function checks the admin nonce, updates the 'Use Button as Shortcode' option based on form submission,
		 */
		private function kwsso_save_button_as_shortcode() {
			if ( $this->show_notice_if_sp_not_configured() ) {
				return;
			}
			$use_button_shortcode = get_value_from_post(  KwConstants::getConstant('USE_BUTTON_AS_SHORTCODE'), false );
			$message              = $use_button_shortcode == 'true' ? KeywootMessage::getMessage('SHRTCD_AS_BUTTON_ADDED') : KeywootMessage::getMessage('SETTINGS_UPDATED');
			update_kwsso_option( KwConstants::getConstant('USE_BUTTON_AS_SHORTCODE'), $use_button_shortcode );
			KWSSO_Display::kwsso_display_admin_notice( $message, KWSSO_SUCCESS );
		}
		/**
		 * Adds the SSO button on the WordPress login page.
		 *
		 * This function checks the admin nonce, updates the 'Add SSO Button on WP Login' option based on form submission,
		 * and displays relevant admin notices.
		 */
		private function kwsso_add_sso_button_on_wplogin() {
			if ( $this->show_notice_if_sp_not_configured() ) {
				return;
			}
				$add_button_on_wp = get_value_from_post( 'kwsso_add_sso_button_wp', false );
				$message          = $add_button_on_wp == 'true' ? KeywootMessage::getMessage('SSO_BUTTON_ADDED') : KeywootMessage::getMessage('SETTINGS_UPDATED');
				update_kwsso_option( KwConstants::getConstant('ADD_SSO_BUTTON'), $add_button_on_wp );
				KWSSO_Display::kwsso_display_admin_notice( $message, KWSSO_SUCCESS );
		}

		private function kwsso_sp_organization_details()
		{
			$organization_details = [
				'organization_name' => sanitize_text_field(get_value_from_post('kwsso_sp_organization_name')),
				'organization_url' => esc_url_raw(get_value_from_post('kwsso_sp_organization_url')),
				'contact_person_name' => sanitize_text_field(get_value_from_post('kwsso_sp_contact_name')),
				'contact_person_email' => sanitize_email(get_value_from_post('kwsso_sp_contact_email')),
			];
		
			$this->sp_metadata->set_organization_details($organization_details);
		
			KWSSO_Display::kwsso_display_admin_notice(KeywootMessage::getMessage('SETTINGS_UPDATED'), KWSSO_SUCCESS);
		}
		
		/**
		 * Adds the SSO button on the WordPress login page.
		 *
		 * This function checks the admin nonce, updates the 'Add SSO Button on WP Login' option based on form submission,
		 * and displays relevant admin notices.
		 */
		private function kwsso_enable_disable_idp() {
			if ( $this->show_notice_if_sp_not_configured() ) {
				return;
			}
			$kwsso_is_idp_enabled = get_value_from_post( 'kwsso_enable_disable_idp', false );// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce already Verified.
			$message              = $kwsso_is_idp_enabled == 'true' ? KeywootMessage::getMessage('IDP_ENABLED') : KeywootMessage::getMessage('IDP_ENABLED');
			$this->idp_configuration->set_is_idp_enabled( $kwsso_is_idp_enabled );
			KWSSO_Display::kwsso_display_admin_notice( $message, KWSSO_SUCCESS );
		}
		/**
		 * Handles the upload or fetching of SSO metadata.
		 * This function checks the admin nonce and processes the upload or fetching of metadata based on form submission.
		 */
		private function kwsso_upload_or_fetch_metadata() {
			if ( empty( get_value_from_post( 'kwsso_saml_idp_name' ) ) ) {
				KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('INVALID_IDP_NAME'), KWSSO_ERROR );
				return;
			}
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once KWSSO_FilePath::getFilePath('WP_ADMIN_FILE');
			}
			$kwsso_metadata_import_service = KwMetadataHandlerService::kwsso_get_instance();
			$kwsso_metadata_import_service->kwsso_handle_upload_or_fetch_metadata();
		}
	}
}

