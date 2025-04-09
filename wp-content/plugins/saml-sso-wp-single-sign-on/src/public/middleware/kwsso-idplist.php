<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$subtab               = isset( $_GET['subpage'] ) ? sanitize_text_field( wp_unslash( $_GET['subpage'] ) ) : 'auto-config'; //phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the form name, doesn't require nonce verification.
$auto_config_hidden   = 'auto-config' !== $subtab ? 'hidden' : '';
$manual_config_hidden = 'manual-config' !== $subtab ? 'hidden' : '';
$on_conf_idp_page          = isset( $_GET['ConfIdpPage'] ) ? '' : 'none';//phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the form name, doesn't require nonce verification.
$on_select_idp_page        = kwsso_check_if_sp_configured() || isset( $_GET['ConfIdpPage'] )? 'none' : '';//phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the form name, doesn't require nonce verification.

$show_confiogured_idp_list = ! isset( $_GET['ConfIdpPage'] ) && kwsso_check_if_sp_configured() ? '' : 'none';
require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-idplist.php';

