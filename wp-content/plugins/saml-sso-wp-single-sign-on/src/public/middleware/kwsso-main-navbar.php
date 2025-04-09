<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$server_uri     = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : null; // phpcs:ignore -- false positive.
$kwsso_request_uri = remove_query_arg( array( 'page', 'subpage' ), $server_uri );
$active_tab     = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the tab name, doesn't require nonce verification.
$license_url    = add_query_arg( array( 'page' => $tab_details->tab_details['PRICING']->kwsso_menu_slug ), $kwsso_request_uri );

require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-main-navbar.php';

