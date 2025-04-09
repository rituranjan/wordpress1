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
 * Defines constants for Redirection SSO Links tab.
 */
class KwConstants {

	/**
	 * Holds the plugin constants.
	 *
	 * @var array
	 */
	private static $constants = array(
		'CUSTOM_LOGIN_BUTTON'       => 'kwsso_custom_login_text',
		'ADD_SSO_BUTTON'            => 'kwsso_add_sso_button_wp',
		'USE_BUTTON_AS_SHORTCODE'   => 'kwsso_use_button_as_shortcode',
		'ADMIN_NOTICE_FLAG'         => 'kwsso_show_setting_status_notice',
		'ADMIN_NOTICES_MESSAGE'     => 'kwsso_message',
		'TEST_CONFIG_ATTIBUTES'     => 'kwsso_test_config_attrs',
		'SP_BASE_URL'               => 'kwsso_sp_base_url',
		'SP_ENTITY_ID'              => 'kwsso_sp_entity_id',
		'KWSSO_IDP_NAME'            => 'kwsso_saml_identity_name',
		'KWSSO_IDP_KEY'             => 'kwsso_saml_idp_key',
		'LOGIN_BINDING_TYPE'        => 'kwsso_saml_login_binding_type',
		'LOGIN_URL'                 => 'kwsso_saml_login_url',
		'ISSUER'                    => 'kwsso_saml_issuer',
		'X509_CERTIFICATE'          => 'kwsso_saml_x509_certificate',
		'NAMEID_FORMAT'             => 'kwsso_saml_nameid_format',
		'ASSERTION_TIME_VALIDATION' => 'kwsso_assertion_time_validity',
		'ORG_DETAILS'               => 'kwsso_admin_organization_details',
	);

	/**
	 * Retrieves a constant by key.
	 *
	 * @param string $key The key of the constant.
	 * @return string|null The constant value if the key exists, null otherwise.
	 */
	public static function getConstant( $key ) {
		return self::$constants[ $key ] ?? null;
	}
}
