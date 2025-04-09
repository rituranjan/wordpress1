<?php
/**
 * Deletes options saved in the plugin.
 *
 * @package keywoot-saml-sso
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$option_array = array(
	'kwsso_custom_login_text',
	'kwsso_add_sso_button_wp',
	'kwsso_use_button_as_shortcode',
	'kwsso_show_setting_status_notice',
	'kwsso_message',
	'kwsso_test',
	'kwsso_test_config_attrs',
	'kwsso_sp_base_url',
	'kwsso_sp_entity_id',
	'kwsso_user_first_activation',
);
foreach ( $option_array as $option ) {
	delete_site_option( $option );
}
