<?php
/**
 * Activation file
 *
 * @package keywoot-saml-sso/service
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use KWSSO_CORE\Src\Main\Helper\KWSSO_CurlCall;
use KWSSO_CORE\Traits\Instance;

/**
 * KWSSO_Activation class
 */
class KWSSO_Activation {

	use Instance;
	/**
	 * Initializes values
	 */
	protected function __construct() {
		add_action( 'admin_init', array( $this, 'kwsso_handle_admin_actions' ), 1 );
		add_action( 'admin_init', array( $this, 'kwsso_redirect_after_activation' ) );
		add_action( 'admin_notices', array( $this, 'show_plugin_main_notice' ) );
		add_action( 'wp_ajax_kwsso-feedback_send_deactivation', array( $this, 'kwsso_feedback_send_deactivation' ) );
		add_action( 'wp_ajax_kwsso_dismiss_notice', array( $this, 'kwsso_dismiss_main_admin_notice' ) );
		register_activation_hook( KWSSO_FilePath::getFilePath( 'PLUGIN_MAIN_FILE' ), array( $this, 'kwsso_plugin_activate_actions' ) );
		register_deactivation_hook( KWSSO_FilePath::getFilePath( 'PLUGIN_MAIN_FILE' ), array( $this, 'kwsso_plugin_deactivate' ) );
	}

	/**
	 * This function hooks into the admin_init WordPress hook. This function
	 * checks the form being posted and routes the data to the correct function
	 * for processing. The 'option' value in the form post is checked to make
	 * the diversion.
	 */
	public function kwsso_handle_admin_actions() {
		// Early return if no action or user lacks permissions
		$action = sanitize_text_field( get_value_from_post( 'kwsso_action' ) );

		if ( ! $action || $action == null || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$action_map = KWSSO_FormRoutes::get_admin_action_map( 'KWSSO_Activation' );
		if ( ! isset( $action_map[ $action ] ) ) {
			return;
		}
		if ( ! check_admin_referer( $action_map[ $action ]['nonce'] ) ) {
			wp_die( esc_attr( KeywootMessage::getMessage( 'INVALID_OPERATION' ) ) );
		}
		$method = $action_map[ $action ]['method'];
		$this->$method();
	}

	public function kwsso_deactivate_current_plugin() {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$email  = sanitize_email( get_value_from_post( 'feedback_query_email' ) );
		$reason = sanitize_text_field( get_value_from_post( 'deactivation_details' ) );

		$submitted = KWSSO_CurlCall::kwsso_send_email_notification( $email, $reason, 'Plugin Deactivation' );

		$plugin = KWSSO_PLUGIN_NAME;
		deactivate_plugins( $plugin );
	}
	/**
	 * This function runs on the submission of contact us form.
	 *
	 * @param array $post_data .
	 */
	private function kwsso_send_email_support_query() {
		$email = sanitize_email( get_value_from_post( 'query_email' ) );
		$query = sanitize_text_field( get_value_from_post( 'query' ) );

		if ( ! $email || ! $query || $email == null || $query == null ) {
			KWSSO_Display::kwsso_display_admin_notice( 'Please fill the required Fields', KWSSO_ERROR );
			return;
		}
		$submitted = KWSSO_CurlCall::kwsso_send_email_notification( $email, $query, 'Support Query' );
		if ( $submitted ) {
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage( 'QUERY_SUBMITTED' ), KWSSO_SUCCESS );
			return;
		}
		KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage( 'ERR_FORM_SUB' ), KWSSO_ERROR );

	}

	/**
	 * This function shows the Enterprise plan notificaton on the admin site only at once.
	 * Once you click on the close notice it will not displayed again.
	 * After deactivation of plugin again the notification will get display.
	 **/
	public function show_plugin_main_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$license_page_url = admin_url() . 'admin.php?page=kwsso-premium';
		$query_string     = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : ''; //phpcs:ignore -- false positive.
		$current_url      = admin_url() . 'admin.php?' . $query_string;
		$is_notice_closed = get_kwsso_option( 'kwsso_hide_notice' );
		if ( 'kwsso_hide_notice' !== $is_notice_closed ) {
			if ( $current_url !== $license_page_url ) {
				$main_text        = __( 'We provide Single Sign-On (SSO) integration with over 30 Identity Providers (IDP), including Azure, Azure B2C, Okta, ADFS, Keycloak, OneLogin, Salesforce, Google Workspace (formerly G Suite), Shibboleth, Centrify, Ping, Auth0, and many more.', 'saml-sso-wp-single-sign-on' );
				$note             = __( 'NOTE', 'saml-sso-wp-single-sign-on' );
				$text_for_premium = __( 'Our premium plans include advanced features such as attribute mapping, role mapping, post-login redirection, customizable SSO buttons, and more.', 'saml-sso-wp-single-sign-on' );
				$note_html        = '<div><p><b>' . $main_text . '</p><br><p class="mt-kw-4"><u>' . $note . '</u> ' . $text_for_premium . '</p><br></b></div>';
				echo '
		 		<div id="kw-admin-notice-container" class="kw-admin-notice-container is-dismissible">
					<div class="kw-admin-notice">
						<div class="kw-logo-container">
							<img  style="width: 100%;margin-bottom: -1%;" src="' . esc_url( KEYWOOT_LOGO_WHITE ) . '" alt="Logo">
						</div>
						<div class="kw-notice-content">' . $note_html . '
						<div class=" kw-notice-btn-container">
						<a href=' . esc_url( $license_page_url ) . ' class="kw-notice-btn kw-notice-btn-primary ">Check Out Premium Plans</a>
						<button id="kw-dismiss-main-admin-notice" class="kw-notice-btn kw-notice-btn-secondary">Dismiss</button>
					 	</div>
					 </div>
						
					</div>
				</div>';
			}
		}

	}



	/**
	 * This function we used to update the value on click of hide admin notice.
	 * This is the check for notification on click of close notification.
	 */
	public function kwsso_dismiss_main_admin_notice() {
		if ( current_user_can( 'manage_options' ) ) {
			update_kwsso_option( 'kwsso_hide_notice', 'kwsso_hide_notice' );
		}
	}
	/**
	 * Actions to be performed upon plugin activation.
	 *
	 * This function executes actions required upon activation of the plugin,
	 * such as checking OpenSSL extension, and enabling keeping settings intact.
	 */
	public function kwsso_plugin_activate_actions() {
		$this->kwsso_check_openssl();
		set_transient( 'kwsso_activation_redirect', true, 30 );
	}

	public function kwsso_redirect_after_activation() {
		// Check if the transient is set
		if ( get_transient( 'kwsso_activation_redirect' ) ) {
			delete_transient( 'kwsso_activation_redirect' );
			if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
				wp_safe_redirect( admin_url( 'admin.php?page=kwsso-main-settings' ) );
				exit;
			}
		}
	}

	/**
	 * Checks if the OpenSSL extension is installed and displays error message if not.
	 */
	public function kwsso_check_openssl() {
		if ( ! kwsso_is_extension_installed( 'openssl' ) ) {
			KWSSO_Display::kwsso_die_and_display_error( 'ERR_MISSING_OPENSSL_EXTENSION' );
		}
	}

	private function kwsso_set_first_activation() {
		 update_kwsso_option( 'kwsso_user_first_activation', 'done' );
	}
	/**
	 * Send Email On new Activation
	 *
	 * @return void
	 */
	private function kwsso_send_new_activation_mail() {
		$this->kwsso_set_first_activation();
		$user_email    = sanitize_email( get_value_from_post( 'activation_query_email' ) );
		$usecase       = sanitize_textarea_field( get_value_from_post( 'activation_usecase' ) );
		$allow_support = sanitize_text_field( get_value_from_post( 'allow_usecase_support' ) );
		if ( empty( $user_email ) || ! is_email( $user_email ) ) {
			return;
		}
		if ( $allow_support == 'checked' ) {
			$query     = $usecase . ' | Support Allowed: Yes';
			$submitted = KWSSO_CurlCall::kwsso_send_email_notification( $user_email, $query, 'New Installation' );
		}
	}

	/**
	 * Get runs on deactivation and remove plugin options
	 *
	 * @return void
	 */
	public function kwsso_plugin_deactivate() {
		// code to add
	}
}
