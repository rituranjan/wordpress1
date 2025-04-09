<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$server_uri                     = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : null; // phpcs:ignore -- false positive.
require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-pricing.php';
