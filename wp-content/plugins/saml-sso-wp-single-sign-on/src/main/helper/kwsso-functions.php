<?php


/**
 * Check if a PHP extension is loaded.
 *
 * @param string $extension_name The name of the PHP extension.
 * @return int Returns 1 if the extension is loaded, 0 otherwise.
 */
function kwsso_is_extension_installed( $extension_name ) {
	return extension_loaded( $extension_name ) ? 1 : 0;
}

/**
 * Generate the URL for the attribute mapping tab.
 *
 * This function appends the 'tab=attribute_role' query parameter to the current request URI.
 *
 * @return string The URL with the 'tab=attribute_role' parameter added.
 */
function kwsso_get_attribute_mapping_url() {
	return add_query_arg( array( 'tab' => 'attribute_role' ), $_SERVER['REQUEST_URI'] );
}


/**
 * Returns the URL for testing single sign-on (SSO) configuration.
 *
 * @param bool $new_cert Whether to include the 'newcert=true' parameter in the URL.
 * @return string The URL for testing SSO configuration.
 */
function kwsso_get_test_url() {
	$url = home_url() . '/?kwsso_action=kw-test-idp-config';
	return $url;
}

function kwsso_check_if_sp_configured( $html_element = false ) {
	$idp_configuration               = new KWSSO_IDPConf();
		$kwsso_saml_login_url        = $idp_configuration->get_login_url();
		$kwsso_saml_x509_certificate = $idp_configuration->get_x509_certificate();
		$kwsso_saml_x509_certificate = is_array( $kwsso_saml_x509_certificate ) ? $kwsso_saml_x509_certificate : array( 0 => $kwsso_saml_x509_certificate );
	if ( ! empty( $kwsso_saml_login_url ) && ! empty( $kwsso_saml_x509_certificate ) ) {
		return $html_element ? '' : 1;
	}
	return $html_element ? 'disabled title="Disabled. Configure your Service Provider"' : 0;
}

/**
 * Helper function to exit script with an error message.
 *
 * @param string $message
 */
function kwsso_exit_with_error( $message ) {
	echo sprintf( '%s', esc_attr( $message ) );
	exit;
}

/**
 * Retrieves a value from the $_POST superglobal based on a given key.
 *
 * @param string $key The key to retrieve from $_POST.
 * @return mixed|null The value from $_POST if it exists, otherwise null.
 */
function get_value_from_post( $key, $default = '' ) {
	return isset( $_POST[ $key ] ) ? $_POST[ $key ] : $default;// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verifing nonce in the fucntion where this helper fuction is called.
}

/**
 * Returns admin post url.
 */
function admin_post_url() {
	return admin_url( 'admin-post.php' ); }

/**
 * Returns wp ajax url.
 */
function wp_ajax_url() {
	return admin_url( 'admin-ajax.php' ); }

/**
 * Used for transalating the string
 *
 * @param string $string - option name to be deleted.
 */
function kwsso_lang_( $string ) {

	$string = preg_replace( '/\s+/S', ' ', $string );
	return $string;
}


/**
 * Deletes the option set in the wp_option table.
 *
 * @param string $string - option name to be deleted.
 * @param string $prefix - prefix of the option.
 */
function delete_kwsso_option( $string, $prefix = null ) {
	// $string = ( null === $prefix ? 'wpkw_' : $prefix ) . $string;
	delete_site_option( $string );
}
/**
 * Retrieved the value of the option in the wp_option table.
 *
 * @param string $string - option name to be deleted.
 * @param string $prefix - prefix of the option.
 */
function get_kwsso_option( $string, $default = false, $prefix = null ) {
	// $string = ( null === $prefix ? 'wpkw_' : $prefix ) . $string;
	return get_site_option( $string, $default );
}

/**
 * Updates the option set in the wp_option table.
 *
 * @param string $string - option name to be deleted.
 * @param string $value - value of the option.
 * @param string $prefix - prefix of the option.
 */
function update_kwsso_option( $string, $value, $prefix = null ) {
	// $string = ( null === $prefix ? 'wpkw_' : $prefix ) . $string;
	update_site_option( $string, $value );
}
	/**
	 * Chceks if a user is logged in or not, additional checks for guest login.
	 *
	 * @return bool true if user is logged in, false if not logged in.
	 */
function kwsso_check_if_user_logged_in() {
	if ( is_user_logged_in() ) {
		return true;
	}
	return false;
}



