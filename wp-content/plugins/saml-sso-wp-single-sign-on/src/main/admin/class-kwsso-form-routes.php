<?php

use KWSSO_CORE\Traits\Instance;
/**
 * The plugin setting class.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class KWSSO_FormRoutes {
	 /**
	 * Stores KWSSO_SettingsService object.
	 *
	 * @var object
	 */
	use Instance;


	public static function get_admin_action_map( $class ) {
		switch ( $class ) {
			case ( 'KWSSO_AdminActions' ):
				return array(
					'kwsso_save_idp_configurations'    => array(
						'method' => 'handle_save_idp_configurations',
						'nonce'  => 'kwsso_save_idp_configurations',
					),
					'kwsso_clear_attributes'           => array(
						'method' => 'kwsso_clear_attributes',
						'nonce'  => 'kwsso_clear_attributes',
					),
					'kwsso_sp_core_config'             => array(
						'method' => 'handle_save_sp_core_config',
						'nonce'  => 'kwsso_sp_core_config',
					),
					'kwsso_upload_or_fetch_metadata'   => array(
						'method' => 'kwsso_upload_or_fetch_metadata',
						'nonce'  => 'kwsso_upload_or_fetch_metadata',
					),
					'kwsso_add_sso_button_action'      => array(
						'method' => 'kwsso_add_sso_button_on_wplogin',
						'nonce'  => 'kwsso_add_sso_button_action',
					),
					'kwsso_enable_disable_idp'         => array(
						'method' => 'kwsso_enable_disable_idp',
						'nonce'  => 'kwsso_enable_disable_idp',
					),
					'kwsso_sp_metadata_dowload'        => array(
						'method' => 'kwsso_handle_metadata_download',
						'nonce'  => 'kwsso_sp_metadata_dowload',
					),
					'kwsso_button_as_shortcode_action' => array(
						'method' => 'kwsso_save_button_as_shortcode',
						'nonce'  => 'kwsso_button_as_shortcode_action',
					),
					'kwsso_confirm_remove_idp_conf'    => array(
						'method' => 'kwsso_confirm_remove_idp_config',
						'nonce'  => 'kwsso_confirm_remove_idp_conf',
					),
					'kwsso_sp_organization_details'    => array(
						'method' => 'kwsso_sp_organization_details',
						'nonce'  => 'kwsso_sp_organization_details',
					),
				);
			case ( 'KWSSO_Activation' ):
				return array(
					'kwsso_contact_us_query'      => array(
						'method' => 'kwsso_send_email_support_query',
						'nonce'  => 'kwsso_contact_us_query',
					),
					'kwsso-feedback-deactivation-form-action' => array(
						'method' => 'kwsso_deactivate_current_plugin',
						'nonce'  => 'kwsso-feedback-deactivation-form',
					),
					'kwsso-email-activation-form' => array(
						'method' => 'kwsso_send_new_activation_mail',
						'nonce'  => 'kwsso-email-activation-form',
					),
					'kwsso-set-first-activation'  => array(
						'method' => 'kwsso_set_first_activation',
						'nonce'  => 'kwsso-set-first-activation',
					),
				);
			default:
				return null;
		}
	}
}
