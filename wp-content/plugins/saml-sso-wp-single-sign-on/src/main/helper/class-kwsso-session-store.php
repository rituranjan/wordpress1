<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'KWSSO_SessionStore' ) ) {
	/**
	 * KWSSO_SessionStore - 
	 */
	class KWSSO_SessionStore {
		/**
		 * Stores a value with a unique transient key.
		 *
		 * @param string $key Unique identifier for the value to store.
		 * @param mixed  $value Data to store, can be any serializable type.
		 */
		public static function add_store_value( $key, $val ) {
			$transient_key = self::generate_random_key();
			if ( ob_get_contents() ) {
				ob_clean();
			}
			setcookie( 'transient_key', $transient_key, time() + 900, COOKIEPATH, COOKIE_DOMAIN );
			wp_cache_add( 'transient_key', $transient_key );
			set_site_transient( $transient_key . $key, $val, 900 );
		}

		/**
		 * Retrieves a stored value using a transient key.
		 *
		 * @param string $key The unique key for the stored value.
		 * @return mixed Stored data or false if not found.
		 */
		public static function get_store_value( $key ) {
			$transient_key = isset( $_COOKIE['transient_key'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['transient_key'] ) ) : wp_cache_get( 'transient_key' ); //phpcs:ignore -- false positive.
			return get_site_transient( $transient_key . $key );
		}
		/**
		 * Deletes a stored value identified by a key.
		 *
		 * @param string $key The unique key for the stored value to delete.
		 */
		public static function delete_store_value( $key ) {
			$transient_key = isset( $_COOKIE['transient_key'] )
				? sanitize_text_field( wp_unslash( $_COOKIE['transient_key'] ) )
				: wp_cache_get( 'transient_key' );

			if ( ! KWSSO_Utils::is_value_blank( $transient_key ) ) {
				delete_site_transient( $transient_key . $key );
			}
		}
		/**
		 * Sanitizes and retrieves a value from an array by key.
		 *
		 * @param string $key The key to search for in the array.
		 * @param array  $array The array to search in.
		 * @return mixed Sanitized value if found and non-blank; false otherwise.
		 */
		public static function sanitize_check( $key, $array ) {
			if ( ! is_array( $array ) ) {
				return $array;
			}
			$value = array_key_exists( $key, $array ) && ! KWSSO_Utils::is_value_blank( $array[ $key ] ) ? $array[ $key ] : false;
			return is_array( $value ) ? $value : sanitize_text_field( $value );
		}

				/**
				 * Generates a random alphanumeric string of a specified length.
				 *
				 * @return string Randomly generated alphanumeric string.
				 */
		public static function generate_random_key() {
			$length        = wp_rand( 4, 12 );
			$characters    = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$random_string = '';
			for ( $i = 0; $i < $length; $i++ ) {
				$random_string .= $characters[ wp_rand( 0, strlen( $characters ) - 1 ) ];
			}
			return $random_string;
		}
	}
}
