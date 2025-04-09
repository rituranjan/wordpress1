<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


use KWSSO_CORE\Src\Utility\Tab\KWSSO_PluginTabs;
use KWSSO_CORE\Src\Utility\Tab\KWSSO_PluginSubTabs;

$kwsso_current_user = wp_get_current_user();
$middleware_path    = KWSSO_PLUGIN_DIR . 'src/public/middleware/';
$layout_path        = KWSSO_PLUGIN_DIR . 'src/public/layout/';
$admin_service      = KWSSO_AdminActions::instance();


$tab_details      = KWSSO_PluginTabs::instance();
$sub_tab_details  = KWSSO_PluginSubTabs::instance();
$sp_metadata      = new KWSSO_SPMetadata();
$idp_metadata     = new KWSSO_IDPConf();
$is_idp_enabled   = $idp_metadata->get_is_idp_enabled();
$x509_certificate = $idp_metadata->get_x509_certificate();

echo '
<div id="kwsso-main-outer-container-div">';
	require_once $middleware_path . 'kwsso-admin-titlebar.php';

echo "  
    <div class='w-full flex'>";
		require_once $middleware_path . 'kwsso-main-navbar.php';

echo '  <div class="flex-1 pl-kw-2 mt-kw-1">';
		KWSSO_Display::display_status_notice();
echo '      <div class="bg-kw-primary-bg rounded-kw-smooth kw-main-content" style="min-height: 472px;">
                <div id="kw-backdrop-container" class="kwsso_box-modal-backdrop dashboard">					
				<div class="flex justify-center items-center h-screen">
					<div class="relative inline-flex">
					<div class="rounded-md h-kw-10 w-kw-10 border-4 border-t-7 border-blue-600 animate-spin absolute"></div>
					</div>
				</div>
			</div>';

				require $middleware_path . 'kwsso-subtabs.php';

echo '          <div>';

if ( isset( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the tab name, doesn't require nonce verification.
	foreach ( $tab_details->tab_details as $kwsso_tabs ) {
		if ( sanitize_text_field( wp_unslash( $_GET['page'] ) ) === $kwsso_tabs->kwsso_menu_slug ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the tab name, doesn't require nonce verification.
			require_once $middleware_path . $kwsso_tabs->kwsso_layout;
		}
	}
}
echo '           </div>
            </div>
        </div>
    </div>
</div>';
kwsso_activation_modal();
require $middleware_path . 'kwsso-contactus.php';

