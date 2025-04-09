<?php
/**
 * User SSO Login Service.
 *
 * @package keywoot-saml-sso/service
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'KWSSO_UserLoginService' ) ) {

	/**
	 * Class KWSSO_UserLoginService
	 *
	 * This class handles the login process for users after sso validation. 
	 */
	class KWSSO_UserLoginService {

		/**
		 * @var string $user_email The email of the user.
		 */
		private $user_email;

		/**
		 * @var string $user_name The username of the user.
		 */
		private $user_name;

		/**
		 * @var string $kwsso_relay_state The relay state parameter for the SSO request.
		 */
		private $kwsso_relay_state;

		/**
		 * @var string $sp_base_url The base URL of the Service Provider.
		 */
		private $sp_base_url;

		/**
		 * @var array $attrs An array holding various SSO attributes.
		 */
		private $saml_attributes;

		public function kwsso_handle_successful_auth( $attrs, $kwsso_relay_state, $sp_base_url ) {
			try {
				$this->initialize_user_attributes( $attrs, $kwsso_relay_state, $sp_base_url );

				if ( KWSSO_Utils::check_if_test_configuration( $kwsso_relay_state ) ) {
					update_kwsso_option( 'kwsso_test', 'Test successful' );
					kwsso_show_test_result( $this->user_email, $attrs );
				} else {
					$this->kwsso_handle_sso_user_login();
				}
			} catch ( Exception $e ) {
				kwsso_exit_with_error( 'An error occurred while processing the SAML Response.' );
			}
		}

		/**
		 * Initialize user attributes from SAML response.
		 */
		private function initialize_user_attributes( $attrs, $kwsso_relay_state, $sp_base_url ) {
			$this->saml_attributes   = $attrs;
			$this->kwsso_relay_state = $kwsso_relay_state;
			$this->sp_base_url       = $sp_base_url;
			$this->user_name         = $this->kwsso_sanitize_username( $attrs['NameID'] );
			$this->user_email        = sanitize_email( $attrs['NameID'] );
		}


		public function kwsso_handle_sso_user_login() {
			$this->check_for_long_username();
			if ( $this->kwsso_check_if_user_exists() ) {
				$this->kwsso_handle_existing_user_login();
			} else {
				$this->kwsso_handle_new_user_creation();
			}
			$this->kwsso_post_login_redirection();
		}

		/**
		 * Checks if the username length exceeds the allowed limit and sanitizes it.
		 *
		 * @param string $user_name Username to be checked and sanitized.
		 */
		private function check_for_long_username() {
			$user_name = $this->kwsso_sanitize_username( $this->user_name );
			if ( strlen( $user_name ) > 60 ) {
				KWSSO_Display::kwsso_die_and_display_error( 'ERR_LONG_USERNAME' );
				exit;
			}

		}

		public function kwsso_sanitize_username( $user_name ) {
			$sanitized_userName = sanitize_user( $user_name, true );
			$user_name          = trim( $sanitized_userName );
			return $user_name;
		}

		private function kwsso_check_if_user_exists() {
			return username_exists( $this->user_name ) || email_exists( $this->user_email );
		}

		/**
		 * Handle existing user login by updating email if necessary, mapping attributes, and setting roles.
		 */
		private function kwsso_handle_existing_user_login() {
			$user = $this->get_existing_user();
			if ( $user && is_email( $this->user_email ) ) {
				wp_update_user(
					array(
						'ID'         => $user->ID,
						'user_email' => $this->user_email,
					)
				);
			}
			$this->kwsso_map_basic_attributes( $user );
			$this->kwsso_update_user_roles( $user );
			update_user_meta( $user->ID, 'kwsso_user_type', 'sso_user' );
			$this->kwsso_set_auth_cookie( $user );
		}


		/**
		 * Retrieve an existing user by username or email.
		 */
		private function get_existing_user() {
			return username_exists( $this->user_name )
				? get_user_by( 'login', $this->user_name )
				: get_user_by( 'email', $this->user_email );
		}


		/**
		 * Set authentication cookies for the user.
		 */
		private function kwsso_set_auth_cookie( $user ) {
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID, true );
		}

		private function kwsso_update_user_roles( $user ) {
			if ( ! KWSSO_Utils::kwsso_check_if_admin_user( $user ) ) {
				$this->kwsso_assign_role_to_user( $user );
			}
		}



		/**
		 * Create a new user.
		 */
		private function kwsso_handle_new_user_creation() {
			$random_password = wp_generate_password( 10, false );
			$user_id         = wp_create_user( $this->user_name ?: $this->user_email, $random_password, $this->user_email );

			if ( is_wp_error( $user_id ) ) {
				KWSSO_Display::kwsso_die_and_display_error( 'ERR_USER_CREATION_FAILED' );
			}

			$user = get_user_by( 'id', $user_id );
			$this->kwsso_map_basic_attributes( $user );
			update_user_meta( $user->ID, 'kwsso_user_type', 'sso_user' );
			$this->kwsso_assign_role_to_user( $user );
			$this->kwsso_set_auth_cookie( $user );
		}

		/**
		 * Assign default role to a user if not an admin.
		 */
		private function kwsso_assign_role_to_user( $user ) {
			$default_role = get_site_option( 'default_role' );
			if ( $default_role && ! KWSSO_Utils::kwsso_check_if_admin_user( $user ) ) {
				wp_update_user(
					array(
						'ID'   => $user->ID,
						'role' => $default_role,
					)
				);
			}
		}

		/**
		 * Map basic SAML attributes to WordPress user meta.
		 */
		private function kwsso_map_basic_attributes( $user ) {
			update_user_meta( $user->ID, 'kwsso_user_attributes', $this->saml_attributes );
		}

		/**
		 * Redirect user after login.
		 */
		public function kwsso_post_login_redirection() {
			$kwsso_redirection_url = ! empty( $this->kwsso_relay_state ) && strpos( $this->kwsso_relay_state, home_url() ) !== false
				? esc_url_raw( $this->kwsso_relay_state )
				: esc_url_raw( $this->sp_base_url );
			wp_safe_redirect( $kwsso_redirection_url );
			exit;
		}
	}
}
