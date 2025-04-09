<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use KWSSO_CORE\Src\Utility\Tab\KWSSO_PluginTabs;

$kwsso_request_uri        = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
echo '  <div style="display:' . esc_attr( $on_select_idp_page ) . '" id="kwsso_idp_selection_form">
			<div class="kw-idp-list-container mx-kw-4 my-kw-4">
					<div class="w-full flex gap-kw-8 px-kw-4 pt-kw-4 kwsso-shadow-card  ">
						<div >
							<p class="kw-subtitle text-lg font-medium text-blue-600 py-kw-1">
										' . esc_html( kwsso_lang_( 'Select Your Identity Provider (IDP)' ) ) . ':</p> 
							<div class="kwsso_note mt-kw-1 mb-kw-4 py-kw-2">
								<b>Not able to find your Identity Provider? Choose the Custom IDP Option From the Below Cards </b>.                         
							</div> 
						</div>
						<div class="w-full">
									<div class="kw-input-wrapper  my-kw-4">
										<label class="kw-input-label">Search your IDP</label>
										<input class="kw-input py-kw-4 w-full" placeholder="Enter the name of Your Identity Provider" type="text" id="kwsso_idp_list_search_box" >
									</div>
						</div>
					</div>';
					get_idp_list_dropdown();
echo '
			</div>
		</div>';



