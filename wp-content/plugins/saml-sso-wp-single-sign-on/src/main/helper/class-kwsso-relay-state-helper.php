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


if ( ! class_exists( 'KWSSO_RelayStateHelper' ) ) {
    
	/**
	 * This is the clas us to detemine the relay state of the request.
	 */
	class KWSSO_RelayStateHelper {

        use Instance;

		/**
		 * Determines the relay state URL based on the provided request parameters.
		 *
		 * Checks if a specific relay state is set in the request for configuration testing or redirection.
		 * If no valid relay state is found, it defaults to the referring URL or the Service Provider's base URL.
		 *
		 * @param WP_REST_Request $request The request object containing relay state parameters.
		 * @return string The determined relay state URL.
		 */
		public static function kwsso_determine_relay_state( $request ) {
			$relay_state = ( $request->get( 'kwsso_action' ) === 'kw-test-idp-config' ) ? 'kwsso-test-idp-validation'
			: sanitize_text_field( wp_unslash( $request->get( 'redirect_to' ) ?? wp_get_referer() ?? KWSSO_Utils::kwsso_get_sp_base_url() ) );
			$relay_state = self::kwsso_get_relay_state( $relay_state );
			if ( empty( $relay_state ) ) {
				$relay_state = '/';
			}
			return $relay_state;
		}
		/**
		 * Parses and returns the relay state URL, including path, query, and fragment.
		 *
		 * Extracts the path, query parameters, and fragment identifier from the given relay state URL,
		 * preserving the full URL structure for redirection.
		 *
		 * @param string $relay_state The relay state URL to be processed.
		 * @return string The relay state URL with path, query, and fragment components.
		 */
		public static function kwsso_get_relay_state( $relay_state ) {
			if ( KWSSO_Utils::check_if_test_configuration( $relay_state ) ) {
				return $relay_state;
			}  $parsed_url = wp_parse_url( $relay_state );
			$relay_path    = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '';
			if ( isset( $parsed_url['query'] ) ) {
				$relay_path .= '?' . $parsed_url['query'];
			}
			if ( isset( $parsed_url['fragment'] ) ) {
				$relay_path .= '#' . $parsed_url['fragment'];
			}
			return $relay_path;
		}
	}
}
