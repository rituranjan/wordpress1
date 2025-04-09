<?php
/**
 * Plugin Name: SAML SSO Login - WP Single Sign On
 * Plugin URI: https://keywoot.com
 * Description: Simplify login with secure SAML Single Sign On (SAML SSO) using any SAML-compliant Identity Provider like Azure, Okta, Keycloak, ADFS, OneLogin. etc
 * Version: 1.4.5
 * Author: Keywoot Software Solutions.
 * Author URI: https://keywoot.com
 * Text Domain: saml-sso-wp-single-sign-on
 * Domain Path: /lang
 * WC requires at least: 3.0.0
 * WC tested up to: 9.6.2
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package keywoot-saml-sso
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'before_woocommerce_init',
	function() {

		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );

		}

	}
);

define( 'KWSSO_PLUGIN_FREE', true );

add_action(
	'plugins_loaded',
	function () {
		if ( defined( 'KWSSO_PLUGIN_PRO' ) ) {
			return;
		}
		kwsso_plugin_init();
	}
);
register_activation_hook( __FILE__, 'kwsso_plugin_activate_actions' );

function kwsso_plugin_activate_actions() {
	set_transient( 'kwsso_activation_redirect', true, 30 );
}
function kwsso_plugin_init() {
	define( 'KWSSO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'KWSSO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	define( 'KWSSO_PLUGIN_NAME', plugin_basename( __FILE__ ) );
	define( 'KWSSO_DIR_NAME', substr( KWSSO_PLUGIN_NAME, 0, strpos( KWSSO_PLUGIN_NAME, '/' ) ) );

	if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
		require_once dirname( __FILE__ ) . '/vendor/autoload.php';
	}

	require_once 'src/kwsso-initializer.php';
}



