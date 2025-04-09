<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-general-settings-view.php';
require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-button-settings-page.php';

$disabled = ! file_exists( KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwgeneralsettings.php' ) ? 'disabled' : '';
$is_prem  = ! file_exists( KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwgeneralsettings.php' ) ? true : '';


KWSSO_Utils::kwsso_get_sp_base_url();
kwsso_link_and_shortcode_view( $idp_metadata );
