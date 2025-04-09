<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

echo '
<div>
	<div class="kw-main-content" style="margin:4px">
		<div class="kw-section-header">
		<div style="width: 3%;"><img  src="' . esc_url( KEYWOOT_LOGO_WHITE ) . '" class="show_kwsso_icon_form "></div>
  				<h5 class="text-lg font-medium" style="flex: 1 1 0%;">' . esc_html( kwsso_lang_( 'WP Single Sign On - Login With SSO' ) ) . '</h5>    
	        <div class="kw-sso-help-button static mr-kw-4 flex">
				<a id="upgrade-pro-button" target="_blank" href="'.esc_url(KWSSO_CHECKOUT_URL).'" style="cursor:pointer;" class="kw-main-button secondary">Upgrade Pro</a>		
				<button id="" onclick="contactUsOnClick(\'Hi Keywoot Team, I want a free demo of the plugin\')" class="kw-main-button primary mx-kw-4 gradient-button ">
						Get Free Demo
				</button>
			</div>
    </div>

</div>';
