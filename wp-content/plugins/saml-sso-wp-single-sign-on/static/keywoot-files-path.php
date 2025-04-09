<?php
/**
 * Defines the KWSSO_FilePath class for managing plugin file paths.
 *
 * @package keywoot-saml-sso\assets\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Include necessary base constant file

/**
 * Class KWSSO_FilePath
 *
 * Manages plugin file paths in two categories: autoload and non-autoload.
 */
class KWSSO_FilePath {

	/**
	 * File paths to be required automatically on load.
	 *
	 * @var array
	 */
	private static $autoloadFilePaths = array(
		'SETTINGS_HELPER'             => KWSSO_PLUGIN_DIR . 'src/main/helper/kwsso-functions.php',
		'KWSSO_UTILITY'               => KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwsso-utility.php',
		'KWSSO_SESSION_STORE'         => KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwsso-session-store.php',
		'KWSSO_RELAY_STATE_HELPER'    => KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwsso-relay-state-helper.php',
		'SAML_SERVICE'                => KWSSO_PLUGIN_DIR . 'src/main/service/class-kwsso-saml-service.php',
		'RESPONSE_VALIDATOR'          => KWSSO_PLUGIN_DIR . 'src/main/service/class-kwsso-response-validator.php',
		'METADATA_IMPORT_HANDLER'     => KWSSO_PLUGIN_DIR . 'src/main/service/class-kwsso-metadata-handler-service.php',
		'KWSSO_USER_REQUESTS_HANDLER' => KWSSO_PLUGIN_DIR . 'src/main/service/class-kwsso-user-requests.php',
		'KWSSO_USER_LOGIN_HANDLER'    => KWSSO_PLUGIN_DIR . 'src/main/service/class-kwsso-user-login-service.php',
		'KW_SP_METADATA'              => KWSSO_PLUGIN_DIR . 'src/main/data/class-kwsso-sp-metadata.php',
		'KWSSO_IDP_CONF'              => KWSSO_PLUGIN_DIR . 'src/main/data/class-kwsso-idp-configuration.php',
		'SSO_SERVICE'                 => KWSSO_PLUGIN_DIR . 'src/main/admin/class-kwssosettings.php',
		'KWSSO_ADMIN_ACTION'          => KWSSO_PLUGIN_DIR . 'src/main/admin/class-kwsso-admin-actions.php',
		'KWSSO_FORM_ROUTES'           => KWSSO_PLUGIN_DIR . 'src/main/admin/class-kwsso-form-routes.php',
		'KWSSO_ACTIVATION'            => KWSSO_PLUGIN_DIR . 'src/main/admin/class-kwsso-activation.php',
		'KEYWOOT_DISPLAY'             => KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwsso-display.php',
		'KWSSO_ELEMENTS'              => KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-elements.php',
		'KWSSO_DEACTIVATE_FORM'       => KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-deactivate-form.php',
		'KWSSO_ONLOAD'                => KWSSO_PLUGIN_DIR . 'src/class-keywootonload.php',
	);

	/**
	 * File paths that are not required automatically and can be loaded on demand.
	 *
	 * @var array
	 */
	private static $nonAutoloadFilePaths = array(
		'PLUGIN_MAIN_FILE' => KWSSO_PLUGIN_DIR . 'keywoot-saml-sso.php',
		'WP_ADMIN_FILE'    => ABSPATH . 'wp-admin/assets/file.php',
	);

	/**
	 * Get the file path associated with the given key from autoload or non-autoload paths.
	 *
	 * @param string $key The key of the file path to retrieve.
	 * @return string|null The file path if found, or null if the key does not exist.
	 */
	public static function getFilePath( $key ) {
		if ( isset( self::$autoloadFilePaths[ $key ] ) ) {
			return self::$autoloadFilePaths[ $key ];
		}

		return self::$nonAutoloadFilePaths[ $key ] ?? null;
	}

	/**
	 * Get all autoload file paths.
	 *
	 * @return array An associative array of autoload file paths.
	 */
	public static function getAutoloadFilePaths() {
		return self::$autoloadFilePaths;
	}

	/**
	 * Get all non-autoload file paths.
	 *
	 * @return array An associative array of non-autoload file paths.
	 */
	public static function getNonAutoloadFilePaths() {
		return self::$nonAutoloadFilePaths;
	}

	/**
	 * Automatically require all files in the autoload paths.
	 */
	public static function requireAutoloadFiles() {
		foreach ( self::$autoloadFilePaths as $path ) {
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}
}
