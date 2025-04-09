<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-button-settings-page.php';
$sp_base_url  = KWSSO_Utils::kwsso_get_sp_base_url();
kwsso_load_button_settings_page();