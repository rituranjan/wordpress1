<?php
/**
 * Defines the Constant class used throughout the plugin.
 *
 * @package keywoot-saml-sso\assets\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Defines constants of the plugin files path required in the plugin.
 */
class KeywootMessage {
	/**
	 * Holds the plugin messages.
	 *
	 * @var array
	 */
	private static $messages = array(
		'INVALID_OPERATION'      => 'Invalid operation. Please try again.',
		'IDP_CONF_SAVED'         => 'Identity provider details fetched and saved successfully.',
		'CURL_DISABLED'          => 'PHP cURL extension is not installed or enabled. Please enable it by editing php.ini (usually located in /etc/ or in the PHP folder on the server). Look for extension=php_curl.dll, remove the semicolon (;) in front of it, and restart the Apache server.',
		'INVALID_FILE_OR_URL'    => 'Please provide a valid metadata file or URL.',
		'ERR_FORM_SUB'           => 'Error submitting the form. Please try again.',
		'QUERY_SUBMITTED'        => 'Thank you for submitting your query. We will reach out to you as soon as possible.',
		'INVALID_CERTIFICATE'    => 'Invalid certificate. Please provide a valid certificate.',
		'IDP_SAVED'              => 'Identity provider details saved successfully.',
		'ATTR_LIST_CLR'          => 'Attribute list cleared successfully.',
		'SSO_BUTTON_ADDED'       => 'SSO button added to the WordPress login form.',
		'SHRTCD_AS_BUTTON_ADDED' => '[KWSSO_SAML_SSO] shortcode can now be used as an SSO button anywhere.',
		'WID_AS_BUTTON_ADDED'    => 'Widget can now be used as an SSO button anywhere.',
		'SETTINGS_UPDATED'       => 'Settings updated successfully.',
		'IDP_ENABLED'            => 'Configuration has been saved.',
		'IDP_REMOVED'            => 'Identity provider configuration has been removed.',
		'INVALID_IDP_NAME'       => 'Please enter a valid Identity Provider name that matches the required format. The name should only contain letters, numbers, and underscores.',
		'NO_INTERNET'            => 'Unable to connect to the internet. Please try again.',
	);

	/**
	 * Retrieves a message by key.
	 *
	 * @param string $key The key of the message.
	 * @return string|null The message string if the key exists, null otherwise.
	 */
	public static function getMessage( $key ) {
		return self::$messages[ $key ] ?? null;
	}
}
