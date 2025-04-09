<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require_once 'kwsso-setup-idp-config.php';

echo '<div class="kwsso_full-layout w-full" id="idpform" style="display:' . esc_attr( $on_conf_idp_page ) . '" >
    <div id="subtab" class="kw-subtabs-container">
        <div class="kw-subtab-item kw-subtab-item">
            <span class="kw-subtab-title" id="auto-config">
                Auto Configuration
            </span>
        </div>
        <div class="kw-subtab-item">
            <span class="kw-subtab-title" id="manual-config">
                Manual Configuration
            </span>
        </div>

        <button 
        id="go_back_to_idp_list" 
        class="kw-main-button secondary"  style="margin-left:auto">
       
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
<path d="M18 2V6M18 6V10M18 6H14M18 6H22M4 10H8C9.10457 10 10 9.10457 10 8V4C10 2.89543 9.10457 2 8 2H4C2.89543 2 2 2.89543 2 4V8C2 9.10457 2.89543 10 4 10ZM10 18C10 20.2091 8.20914 22 6 22C3.79086 22 2 20.2091 2 18C2 15.7909 3.79086 14 6 14C8.20914 14 10 15.7909 10 18ZM16 22H20C21.1046 22 22 21.1046 22 20V16C22 14.8954 21.1046 14 20 14H16C14.8954 14 14 14.8954 14 16V20C14 21.1046 14.8954 22 16 22Z" stroke="#28303F" stroke-width="2" stroke-linecap="round"/>
</svg>

        ' . esc_html( kwsso_lang_( 'Choose IDP' ) ) . '
    </button>
        <button type="button" class="kw-main-button primary" onclick="kwBeginTestConnection();"';
if ( ! kwsso_check_if_sp_configured() || ! $x509_certificate || $is_idp_enabled != 'true' ) {
	echo ' disabled';
}
echo '              value="Test configuration" >
        Test Configuration
    </button>
        <button 
        id="go_back_to_configured_idp_button" 
        class="kw-main-button secondary" style="margin-right: 3%">
        ' . esc_html( kwsso_lang_( 'Go Back' ) ) . '
    </button>
    </div>
           <div style="width:100%">';
echo '     
        <div class="my-kw-4">	
';

kwsso_idp_configuration_view( $auto_config_hidden, $manual_config_hidden,  $idp_metadata );

echo '
        </div>
    </div>';
require KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-configured-idp-list.php';
require KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-idplistgrid.php';


