<?php
/**
 * KWSSO_CurlCall - A helper class for making HTTP requests.
 *
 * @package keywoot-saml-sso/helper
 */
namespace KWSSO_CORE\Src\Main\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class KWSSO_CurlCall {
	/**
	 * Sends an email notification via the Keywoot API.
	 *
	 * @param string $user_email The recipient's email address.
	 * @param string $message The content of the message to be sent.
	 * @param string $message_type Optional. The type of message. Default is 'Client Query'.
	 * @return bool True if the email was sent successfully, false on failure.
	 */
	public static function kwsso_send_email_notification( $user_email, $query, $type = 'Client Query' ) {
		$current_user = wp_get_current_user();
		$url          = KWSSO_HOST . '/wp-json/keywoot/api/email-notify';
		$fields       = array(
			'first_name'   => ( ! empty( $current_user->user_firstname ) ) ? sanitize_text_field( $current_user->user_firstname ) : '',
			'organization' => isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : null,
			'user_email'   => sanitize_email( $user_email ),
			'query'        => sanitize_textarea_field( $query ),
			'type'         => sanitize_text_field( $type ),
		);
		$response     = self::kwsso_make_http_request( $url, $fields );
		return true;
	}

	/**
	 * Sends an HTTP request to the specified URL with the provided data.
	 *
	 * @param string $url The URL to which the request will be sent.
	 * @param array  $data The request payload (e.g., post body data).
	 * @param array  $headers Optional. HTTP headers for the request.
	 * @param string $method Optional. The HTTP method (e.g., 'POST', 'GET'). Default is 'POST'.
	 * @return string|false The response body on success, or false on failure.
	 */
	public static function kwsso_make_http_request( $url, $body, $headers = array(), $method = 'POST' ) {
		global $wp_version;
		$args     = array(
			'method'      => $method,
			'body'        => $body,
			'timeout'     => '1000',
			'redirection' => '10',
			'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
			'httpversion' => '1.1',
			'blocking'    => true,
			'headers'     => $headers,
			'sslverify'   => defined( 'KWSSO_SSL_VERIFY' ) ? KWSSO_SSL_VERIFY : true,
		);
		$response = wp_remote_request( esc_url_raw( $url ), $args );

		if ( is_wp_error( $response ) ) {
			wp_die( wp_kses( 'Something went wrong: <br/>' . $response->get_error_message(), array( 'br' => array() ) ) );
		}
		return wp_remote_retrieve_body( $response );
	}

}
