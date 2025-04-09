<?php

use KWSSO_CORE\Traits\Instance;
/**
 * The plugin setting class.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class KWSSO_SettingsService {
	 /**
	 * Stores KWSSO_SettingsService object.
	 *
	 * @var object
	 */
	use Instance;

	private $idp_metadata;
	/**
	 * The Constructor for the main class.
	 */
	public function __construct() {
		$this->idp_metadata = new KWSSO_IDPConf();
		$this->initialize_hooks();
	}
	/**
	 * This takes care of initializing all the hooks used by the plugin.
	 *
	 * @return void
	 */
	private function initialize_hooks() {
		add_action( 'wp_authenticate', array( $this, 'handle_kwsso_saml_user_authentication' ) );
		add_action( 'login_footer', array( $this, 'kwsso_add_sso_button_on_wp_login' ) );
		add_shortcode( 'KWSSO_SAML_SSO', array( $this, 'kwsso_use_sso_shortcode' ) );
	}



	/**
	 * Handles user authentication using KW SAML.
	 *
	 * This function checks if the user is logged in. If the user is logged in, it checks
	 * if the redirect URL is an admin URL or the login URL. If not, it safely redirects
	 * the user to the specified redirect URL. If the user is not logged in, it redirects
	 * the user to the site URL.
	 *
	 * @return void
	 */
	public function handle_kwsso_saml_user_authentication() {
		if ( ! is_user_logged_in() ) {
			return;
		}
		$redirect_to  = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : '';
		$is_admin_url = in_array( $redirect_to, array( admin_url(), wp_login_url() ) );
		$redirect_url = $is_admin_url ? site_url() : $redirect_to;
		wp_safe_redirect( $redirect_url );
		exit();
	}


	/**
	 * Function is used to add SSO button on WP login page.
	 *
	 * @return void
	 */
	public function kwsso_add_sso_button_on_wp_login() {
		if ( kwsso_check_if_user_logged_in() ) {
			return;
		}
		if ( get_kwsso_option( KwConstants::getConstant('ADD_SSO_BUTTON') ) == 'true' && $this->idp_metadata->get_is_idp_enabled() == 'true' ) {
			$sp_base_url  = KWSSO_Utils::kwsso_get_sp_base_url();
			$login_button = KWSSO_Utils::kwsso_get_button_styles();
			$redirect_to  = isset( $_GET['redirect_to'] ) ? rawurlencode( $_GET['redirect_to'] ) : '';
			$button_html = sprintf(
				'<div id="kwsso_wp_login_sso_button" style="text-align:center;">
					<div style="padding: 45px 10px 10px 10px; font-size:14px;">
						<b>OR</b>
					</div>
					<a href="%s/?kwsso_action=kwsso_sso_user_login&redirect_to=%s" style="text-decoration:none;">
						%s
					</a>
				</div>',
				esc_url( $sp_base_url ),
				esc_attr( $redirect_to ),
				$login_button
			);
			echo '<script>jQuery(".submit").append(' . json_encode( $button_html ) . ');</script>';
		}
	}

	/**
	 * Function is used to add sso using shortcode.
	 *
	 * @return void
	 */
	public function kwsso_use_sso_shortcode( $attrs ) {
		if ( kwsso_check_if_user_logged_in() ) {
			return '';
		}
		if ( ! kwsso_check_if_sp_configured() ) {
			return 'SP is not configured.';
		}
		$idp_metadata = new KWSSO_IDPConf();
		if ( $idp_metadata->get_is_idp_enabled() != 'true' ) {
			return 'IDP is not Enabled.';
		}
		$sso_params = $this->prepareSSOLoginParameters( $attrs, $idp_metadata );
		return $this->generateSSOLoginHTML( $sso_params );
	}

	/**
	 * Prepare login title with IDP replacement.
	 *
	 * @param string        $idp IDP name.
	 * @param KWSSO_IDPConf $idp_metadata IDP metadata.
	 * @return string Prepared login title.
	 */
	private function prepareLoginTitle( string $idp, KWSSO_IDPConf $idp_metadata ): string {
		$default_title = 'Login with ' . $idp_metadata->get_idp_name();
		$login_title   = get_kwsso_option( KwConstants::getConstant('CUSTOM_LOGIN_BUTTON'), $default_title );
		return str_replace( '##IDP##', $idp, $login_title );
	}

	/**
	 * Prepare SSO login parameters.
	 *
	 * @param array $attrs Shortcode attributes.
	 * @return array SSO login parameters.
	 */
	private function prepareSSOLoginParameters( array $attrs, $idp_metadata ): array {
		$idp         = $attrs['idp'] ?? $idp_metadata->get_idp_name();
		$login_title = $this->prepareLoginTitle( $idp, $idp_metadata );
		$use_button  = get_kwsso_option( KwConstants::getConstant('USE_BUTTON_AS_SHORTCODE') ) === 'true';
		if ( $use_button ) {
			$login_title = KWSSO_Utils::kwsso_get_button_styles();
		}
		return array(
			'sp_base_url' => KWSSO_Utils::kwsso_get_sp_base_url(),
			'idp'         => $idp,
			'login_title' => $login_title,
			'redirect_to' => urlencode( KWSSO_Utils::kwsso_get_current_page_url() ),
			'use_button'  => $use_button,
		);
	}

	/**
	 * Generate SSO login HTML.
	 *
	 * @param array $params SSO login parameters.
	 * @return string SSO login HTML.
	 */
	private function generateSSOLoginHTML( array $params ): string {
		$query_args  = array_filter(
			array(
				'kwsso_action' => 'kwsso_sso_user_login',
				'idp'          => $params['idp'],
				'redirect_to'  => $params['redirect_to'],
			)
		);
		$login_url   = add_query_arg(
			$query_args,
			$params['sp_base_url']
		);
		$style_attrs = $params['use_button'] ? 'style="display:block; text-decoration:none;"' : 'style="display:block;"';
		return sprintf(
			'<a href="%s" %s>%s</a>',
			esc_url( $login_url ),
			$style_attrs,
			$params['login_title']
		);
	}
}


