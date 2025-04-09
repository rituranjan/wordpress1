<?php
/**
 * Initializes plugin data.
 * Contains defination of common functions.
 *
 * @package keywoot-saml-sso
 */

use KWSSO_CORE\Src\AutoClassLoader;
use KWSSO_CORE\Src\KeywootOnload;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


kwsso_get_autoclassloader();

$plugin_data = array(
	'KWSSO_PLUGIN_VERSION' => '1.4.5',
	'KWSSO_TYPE'           => 'free',
	'KWSSO_SSL_VERIFY'     => false,
	'KWSSO_USE_POLYLANG'   => true,
	'KWSSO_HOST'           => 'https://keywoot.com',
	'KWSSO_SUCCESS'        => 'success',
	'KWSSO_ERROR'          => 'error',
	'KWSSO_CHECKOUT_URL'   => 'https://keywoot.com/plugin/checkout-sso-plugin/',
);

$assets_const = array(
	'KWSSO_CSS_URL'      => 'assets/css/kw-custom-style.min.css',
	'KWSSO_JS_URL'       => 'assets/js/ssosettings.min.js',
	'KWSSO_ICON'         => 'assets/images/kw-icon-png.svg',
	'KEYWOOT_LOGO_WHITE' => 'assets/images/kw-logo-white.png',
	'KWSSO_MAIN_CSS'     => 'assets/css/kw-main.min.css',
);

$constants_files = array(
	'keywoot-sso-constants.php',
	'keywoot-sso-message-strings.php',
	'keywoot-error-codes.php',
	'keywoot-idp-data.php',
	'keywoot-nameid-format.php',
	'keywoot-files-path.php',
);


foreach ( $plugin_data as $constant_name => $constant_value ) {
	define( $constant_name, $constant_value );
}
foreach ( $assets_const as $constant => $url ) {
	define( $constant, KWSSO_PLUGIN_URL . $url . '?version=' . KWSSO_PLUGIN_VERSION );
}
foreach ( $constants_files as $file ) {
	require_once KWSSO_PLUGIN_DIR . 'static' . DIRECTORY_SEPARATOR . $file;
}

KWSSO_FilePath::requireAutoloadFiles();
/**
 * Get The AutoClassLoader
 *
 * @return void
 */
function kwsso_get_autoclassloader() {
	require KWSSO_PLUGIN_DIR . 'src/class-autoclassloader.php';
	$keywoot_class_loader = new AutoClassLoader( 'KWSSO_CORE', realpath( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' ) );
	$keywoot_class_loader->register();
}
KWSSO_AdminActions::instance();
KeywootOnload::instance();
KWSSO_Activation::instance();
KWSSO_SettingsService::instance();
KWSSO_UserRequests::instance();
KWSSO_Utils::instance();
KWSSO_FormRoutes::instance();
KWSSO_SPMetadata::instance();
KWSSO_RelayStateHelper::instance();

