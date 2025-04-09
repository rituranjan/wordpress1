<?php
/**
 * This class contains collections of various static functions used across the plugin.
 *
 * @package keywoot-saml-sso/helper
 */


use KWSSO_CORE\Traits\Instance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This is the main Utility class of the plugin.
 * Lists down all the necessary common utility
 * functions being used in the plugin.
 */
if ( ! class_exists( 'KWSSO_Utils' ) ) {
	/**
	 * KWSSO_Utils class
	 */
	class KWSSO_Utils {


		use Instance;

		/**
	 * Sanitizes a given SSL certificate string.
	 *
	 * This function performs several operations to prepare a raw SSL certificate string for usage.
	 * It trims whitespace from the certificate, removes any carriage return and newline characters,
	 * strips out any dashes, and removes the 'BEGIN CERTIFICATE' and 'END CERTIFICATE' labels.
	 * It also removes any spaces, and then reformats the certificate by chunking it into 64-character
	 * lines, finally re-adding the 'BEGIN CERTIFICATE' and 'END CERTIFICATE' labels appropriately.
	 *
	 * @param string $certificate The raw SSL certificate string to be sanitized.
	 * @return string The sanitized and properly formatted SSL certificate string.
	 */
	public static function kwsso_sanitize_certificate( $certificate ) {
		$certificate = trim( $certificate );
		$certificate = preg_replace( "/[\r\n]+/", '', $certificate );
		$certificate = str_replace( '-', '', $certificate );
		$certificate = str_replace( 'BEGIN CERTIFICATE', '', $certificate );
		$certificate = str_replace( 'END CERTIFICATE', '', $certificate );
		$certificate = str_replace( ' ', '', $certificate );
		$certificate = chunk_split( $certificate, 64, "\r\n" );
		$certificate = "-----BEGIN CERTIFICATE-----\r\n" . $certificate . '-----END CERTIFICATE-----';
		return $certificate;
	}


	/**
	 * Makes a remote call using WordPress HTTP API.
	 *
	 * @param string $url The URL to which the request is to be sent.
	 * @param array  $args Optional. Request arguments.
	 * @param bool   $is_get Optional. Whether the request is a GET request. Default false.
	 *
	 * @return mixed The response body on success, false on failure.
	 */
	public static function keywoot_wp_remote_call( $url, $args = array(), $is_get = false ) {
		if ( ! $is_get ) {
			$response = wp_remote_post( $url, $args );
		} else {
			$response = wp_remote_get( $url, $args );
		}
		if ( ! is_wp_error( $response ) ) {
			return $response['body'];
		} else {
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('NO_INTERNET'), KWSSO_ERROR );
			return false;
		}
	}

		/**
		 * Function to get the Base URL of the site.
		 *
		 * @return string
		 */
		public static function kwsso_get_sp_base_url() {
			$sp_metadata = new KWSSO_SPMetadata();
			return $sp_metadata->get_sp_base_url();
		}

		/**Sanitizing array
		 *
		 * @param array $data data array to be sanitized.
		 * @return string
		 */
		public static function kwsso_sanitize_array( $data ) {
			$sanitized_data = array();
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_data[ $key ] = self::kwsso_sanitize_array( $value );
				} else {
					$sanitized_data[ $key ] = sanitize_text_field( $value );
				}
			}
			return $sanitized_data;
		}

		/**
		 * Checks if a value is blank or undefined.
		 *
		 * @param mixed $value The value to check.
		 * @return bool True if the value is blank, false otherwise.
		 */
		public static function is_value_blank( $value ) {
			return ! isset( $value ) || empty( $value );
		}

		public static function kwsso_get_current_page_url() {
			$http_host = $_SERVER['HTTP_HOST'];
			if ( substr( $http_host, -1 ) == '/' ) {
				$http_host = substr( $http_host, 0, -1 );
			}
			$kwsso_request_uri = $_SERVER['REQUEST_URI'];
			if ( substr( $kwsso_request_uri, 0, 1 ) == '/' ) {
				$kwsso_request_uri = substr( $kwsso_request_uri, 1 );
			}

			$is_https          = ( ! empty( $_SERVER['HTTPS'] ) && strcasecmp( $_SERVER['HTTPS'], 'on' ) == 0 );
			$kwsso_relay_state = 'http' . ( $is_https ? 's' : '' ) . '://' . $http_host . '/' . $kwsso_request_uri;
			return $kwsso_relay_state;
		}

		public static function kwsso_check_if_admin_user( $user ) {
			return ( ! is_null( $user->roles ) && in_array( 'administrator', $user->roles, true ) );
		}

		public static function kwsso_add_link( $title, $link ) {
			$html = '<a href="' . $link . '">' . $title . '</a>';
			return $html;
		}

		/**
		 * Returns TRUE or FALSE depending on if the POLYLANG plugin is active.
		 * This is used to check if the translation should use the polylang
		 * function or the default local translation.
		 *
		 * @return boolean
		 */
		public static function is_polylang_installed() {
			return function_exists( 'pll__' ) && function_exists( 'pll_register_string' );
		}

		public static function check_if_test_configuration( $kwsso_relay_state ) {
			return $kwsso_relay_state === 'kwsso-test-idp-validation';
		}

		/**
		 * Used to display exceptions, if the exception has a non 0 code this function fetches the error code defined by plugin.
		 *
		 * @param Exception $exception Exception object.
		 * @param bool      $is_notice Optional. Determines if the thrown exception should be shown as an admin notice. Default false.
		 * @return void
		 */
		public static function kwsso_throw_exception( $exception, $is_notice = false ) {
			$code       = $exception->getCode();
			$error_code = 'KWSSOERR';
			if ( 0 !== $code ) {
				$formatted_code = str_pad( $code, 3, '0', STR_PAD_LEFT );
				$error_code    .= $formatted_code;
				$error_message  = KwErrorCodes::$error_codes[ $error_code ] ?? null;
				if ( $error_message ) {
					$is_notice ? KWSSO_Display::kwsso_show_exception( $error_message ) : KWSSO_Display::kwsso_die_and_display_error( $error_message );
				}
			}
		}

		public static function is_test_connection_response() {
			$kwsso_relay_data_encoded = ( ! empty( $_POST['RelayState'] ) ) ? sanitize_text_field( wp_unslash( $_POST['RelayState'] ) ) : '';// phpcs:ignore WordPress.Security.NonceVerification.Missing -- external request hence nonce verification not needed.
			// Decode Base64 safely
			$decoded_data = base64_decode( $kwsso_relay_data_encoded, true );
			if ( ! $decoded_data ) {
				return false; // Return false if decoding fails
			}

			$relay_state_data = json_decode( $decoded_data, true );
			if ( ! is_array( $relay_state_data ) || json_last_error() !== JSON_ERROR_NONE ) {
				return false; // Return false if JSON is invalid
			}
			if ( ! isset( $relay_state_data['initiator'] ) || $relay_state_data['initiator'] !== 'keywoot' ) {
				return false; // Return false if the initiator is not 'keywoot'
			}
			$kwsso_relay_state = ( ! empty( $relay_state_data['relay_state'] ) && $relay_state_data['relay_state'] != '/' ) ? $relay_state_data['relay_state'] : '';
			return self::check_if_test_configuration( $kwsso_relay_state );
		}

		/**
		 * Check if the phone number is empty and return error.
		 *
		 * @param string $option_name phone number of the user.
		 */
		public static function kwsso_check_admin_nonce( $action_name ) {
			return ( ! empty( $_POST['kwsso_action'] ) && $_POST['kwsso_action'] === $action_name && check_admin_referer( $action_name ) );
		}

		/**
		 * Validates and updates the X.509 certificate received for the Identity Provider (IDP).
		 *
		 * This function sanitizes and checks the validity of the provided X.509 certificate before
		 * updating the option with the serialized certificate array.
		 *
		 * @param array $kwsso_saml_x509_certificate The array of certificate data to be processed.
		 */
		public static function check_and_update_x509_certifciate( $idp_configuration, $kwsso_saml_x509_certificate ) {

			foreach ( $kwsso_saml_x509_certificate as $key => $value ) {
				if ( empty( $value ) ) {
					unset( $kwsso_saml_x509_certificate[ $key ] );
				} else {
					$kwsso_saml_x509_certificate[ $key ] = self::kwsso_sanitize_certificate( $value );
					if ( ! @openssl_x509_read( $kwsso_saml_x509_certificate[ $key ] ) ) {
						KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('INVALID_CERTIFICATE'), KWSSO_ERROR );
						return false;
					}
				}
			}

			if ( empty( $kwsso_saml_x509_certificate ) ) {
				KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('INVALID_CERTIFICATE'), KWSSO_ERROR );
				return false;
			}
			$idp_configuration->set_x509_certificate( $kwsso_saml_x509_certificate );
			return true;

		}

		public static function kwsso_get_button_styles() {
			$idp_metadata = new KWSSO_IDPConf();
			$options      = array(
				'width'         => '270',
				'height'        => '35',
				'size'          => '55',
				'curve'         => '4',
				'color'         => '#2563EB',
				'theme'         => 'longbutton',
				'text'          => 'Login',
				'font_color'    => '#ffffff',
				'font_size'     => '13',
				'position'      => 'above',
				'identity_name' => $idp_metadata->get_idp_name(),

			);
			$button_text = $options['text'];
			if ( $options['identity_name'] ) {
				$button_text = 'Login with ' . $options['identity_name'];
			}

			$style  = 'width:' . $options['width'] . 'px;';
			$style .= 'height:' . $options['height'] . 'px;';
			$style .= 'background-color:' . $options['color'] . ';';
			$style .= 'border-color:transparent;';
			$style .= 'color:' . $options['font_color'] . ';';
			$style .= 'font-size:' . $options['font_size'] . 'px;';
			$style .= 'cursor:pointer;';
			$style .= 'border-radius:' . $options['curve'] . 'px;';

			$login_button = '<input type="button" name="kwsso_wp_sso_button" value="' . $button_text . '" style="' . $style . '"/>';
			return $login_button;
		}
	}
}
