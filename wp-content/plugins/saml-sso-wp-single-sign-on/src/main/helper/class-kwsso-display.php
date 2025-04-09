<?php
/**
 * KWSSO_Display - A helper class for managing admin notices and error handling.
 *
 * @package keywoot-saml-sso/helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'KWSSO_Display' ) ) {
	/**
	 * KWSSO_Display class for managing admin notifications and error messages.
	 */
	class KWSSO_Display {
		/**
		 * Update the admin notice to display a success message.
		 */
		public static function update_success_notice_status() {
			update_kwsso_option( KwConstants::getConstant('ADMIN_NOTICE_FLAG'), 'success' );
		}

		/**
		 * Update the admin notice to display an error message.
		 */
		public static function update_error_notice_status() {
			update_kwsso_option( KwConstants::getConstant('ADMIN_NOTICE_FLAG'), 'error' );
		}

		/**
		 * Display a status notice based on the admin notice flag.
		 *
		 * Retrieves the notice type and message from options, then displays the notice.
		 * Once displayed, it resets the flag to prevent repeated notices.
		 */
		public static function display_status_notice() {
			$kwsso_show_setting_status_notice = get_kwsso_option( KwConstants::getConstant('ADMIN_NOTICE_FLAG') );
			$status_result_string             = get_kwsso_option( KwConstants::getConstant('ADMIN_NOTICES_MESSAGE') );

			if ( $kwsso_show_setting_status_notice === 'success' || $kwsso_show_setting_status_notice === 'error' ) {
				$alert_class = $kwsso_show_setting_status_notice === 'success' ? 'kw-alert-success' : 'kw-alert-error';

				echo '<div id="status_result_notice" class="kw-alert-container ' . esc_attr( $alert_class ) . '">
                        <span>' . wp_kses(
					$status_result_string,
					array(
						'a' => array( 'href' => array() ),
						'i' => array( 'href' => array() ),
						'u' => array( 'href' => array() ),
					)
				) . '</span>
                      </div>';

				update_kwsso_option( KwConstants::getConstant('ADMIN_NOTICE_FLAG'), false );
			}
		}
		/**
		 * Update and display an admin notice with a custom message and status.
		 *
		 * @param string $message The message to display in the admin notice.
		 * @param string $status The status of the notice ('success' or 'error').
		 */
		public static function kwsso_display_admin_notice( $message, $status ) {
			update_kwsso_option( KwConstants::getConstant('ADMIN_NOTICES_MESSAGE'), $message );
			if ( $status == 'success' ) {
				self::update_success_notice_status();
			} else {
				self::update_error_notice_status();
			}
		}

		/**
		 * Displays the error message to admins via admin notice.
		 *
		 * @param array $error_code An array containing the error code details: code, fix, cause and description.
		 * @return void
		 */
		public static function kwsso_show_exception( $error_code ) {
			$message = '<b>[' . esc_attr( $error_code['code'] ) . ']</b> ' . esc_attr( $error_code['cause'] ) . '</br><b>Fix:</b> ' . esc_attr( $error_code['fix'] );
			self::kwsso_display_admin_notice( $message, KWSSO_ERROR );
		}
		/**
		 * Displays the error message to end users along with the provided error code.
		 *
		 * @param array $error_code An array containing the error code details: code, fix, cause and description.
		 * @return void
		 */
		public static function kwsso_die_and_display_error( $error_code ) {
			$output = '
			<div>
				<p>' . esc_html( KwErrorCodes::get_end_user_error() ) . '</p>
				<p ><b>Error Code : </b>[' . esc_html( $error_code ) . ']</p>	
			</div>';
			wp_die( $output );
		}
		public static function kwsso_throw_error( $error_code, $error_message ) {
			if ( KWSSO_Utils::is_test_connection_response() ) {
				kwsso_display_test_error( $error_code, $error_message );
			}
			self::kwsso_die_and_display_error( $error_code );
		}
	}
}
