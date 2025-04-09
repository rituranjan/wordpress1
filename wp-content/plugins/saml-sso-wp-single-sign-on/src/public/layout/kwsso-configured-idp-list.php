<?php

echo ' <div id="configured-idp-container" style="display:' . esc_attr( $show_confiogured_idp_list ) . '"class="kw-subpage-container">
	<div class="kw-header">
	<p class="kw-head-text flex-1">Configured Identity Provider(IDP)</p>
  
</div>
<div class="border-b flex flex-col gap-kw-6 pb-kw-4 px-kw-4 ">         
	<div class="w-full"> 

<div class="div-table-body">
<div class="kw-table">
<div class="bg-card ">
 	<div class="kw-row">
    	<div class="kw-cell  flex kw-link-name"><a class="kw-subtitle mx-kw-8 text-primary">Icon</a></div>
		<div class="kw-cell flex kw-link-name"> <a class="kw-subtitle text-primary "> Identity Provider</a></div>
		<div class="kw-cell kw-link-name "><a class="kw-subtitle text-primary">Enable/Disable</a></div>
		<div class="kw-cell kw-main-button-wrapper-name flex justify-center"><a class="kw-subtitle text-primary">Actions</a>
		</div>
  </div>
</div>';
show_configured_idp_row( $idp_metadata);
echo '    </div>
</div>
          </div>



    </div>

<div id="kwdeleteIDPModal" aria-hidden="true" tabindex="-1" style="display:none" class="kw-popup-modal">
    <div class="kw-popup-modal-wrapper ">
        <div class="relative p-kw-4 text-center bg-primary-bg rounded-smooth shadow sm:p-kw-5">
            <button type="button"  name="hide_remove_idp_conf_alert" class="text-secondary-txt absolute rounded-full top-kw-2.5 right-kw-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-smooth text-caption p-kw-1.5 ml-auto inline-flex items-center data-modal-toggle="deleteModal">
                <svg aria-hidden="true" class="w-kw-icon h-kw-icon" fill="currentColor" viewBox="0 0 20 20" ><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <svg class="text-kw-inactive-ic w-kw-11 h-kw-11 mb-kw-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" ><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <p class="mb-kw-4 font-semibold text-kw-secondary-txt">Are you sure you want to remove the confguration for this IDP?</p>
            <div class="flex justify-center items-center space-x-kw-4">
                <button data-modal-toggle="deleteModal" name="hide_remove_idp_conf_alert" type="button" class="kw-main-button secondary">
                    No, cancel
                </button>
				<form id="kwsso_confirm_remove_idp_conf" method="post" action="">';
						wp_nonce_field( 'kwsso_confirm_remove_idp_conf' );
						echo '<input type="hidden" name="kwsso_action" value="kwsso_confirm_remove_idp_conf"/>
					    <button type="submit" class="kw-main-button primary">
                    		Yes, I\'m sure
                		</button>	
				</form>
            </div>
        </div>
    </div>
</div>
 </div>';

function findIdpNameByKey( $idp_data, $searchKey ) {
	foreach ( $idp_data as $idp ) {
		if ( isset( $idp['key'] ) && $idp['key'] === $searchKey ) {
			return $idp['name'];
		}
	}
	return null; // Return null if the key is not found
}

function show_configured_idp_row( $idp_metadata ) {
	$is_idp_congigured = kwsso_check_if_sp_configured();
	// $is_idp_congigured=false;
	if ( $is_idp_congigured ) {
		$idp_data = KwIDPData::$idp_data;

		// var_dump(array_key_exists( $idp_metadata->get_idp_name(), (array) $idp_data ));exit;
		
		$idp_key  = array_key_exists( $idp_metadata->get_idp_name(), (array) $idp_data ) ? $idp_data[ $idp_metadata->get_idp_name() ] : $idp_data[ findIdpNameByKey( $idp_data, get_kwsso_option( KwConstants::getConstant('KWSSO_IDP_KEY') ) ) ];
		$idp_img  = $idp_key['image_svg'];

		echo ' 	<div class="kw-row">
    	<div class="kw-cell flex kw-link-name "><div class="mx-kw-8">' . $idp_img . '</div></div>
		    	<div class="kw-cell flex kw-link-name"> <a class="kw-subtitle text-primary text-blue-600">' . $idp_key['name'] . '</a></div>

		<div class="kw-cell kw-link-name">
		                     <form id="kwsso_enable_disable_idp_form" method="post" action="">';
						wp_nonce_field( 'kwsso_enable_disable_idp' );
						echo '<input type="hidden" name="kwsso_action" value="kwsso_enable_disable_idp"/>
                            <p>
                                <label class="kw-switch">
                                    <input type="checkbox" name="kwsso_enable_disable_idp"  value="true"';
									checked( $idp_metadata->get_is_idp_enabled() == 'true' );
									echo ' onchange="document.getElementById(\'kwsso_enable_disable_idp_form\').submit();"/>
                                    <span class="kw-radio-button-slider"></span>
                                </label>
                            </p>
                        </form></div>
		<div class="kw-cell kw-main-button-wrapper-name flex justify-center">
			<button class="kw-main-button primary " id="kw-edit-configuration-button" >
Edit Configuration
			</button>
					<button class="kw-main-button secondary mx-kw-4" onclick="kwBeginTestConnection();" id="kw-edit-configuration-button" ';
		if ( ! kwsso_check_if_sp_configured() || ! $idp_metadata->get_x509_certificate() || $idp_metadata->get_is_idp_enabled() != 'true' ) {
			echo ' disabled';
		};
		echo ' >
Test		</button>
		
			<button class="kw-main-button secondary" id="kw-remove-idp-configuration">
<svg width="20" height="20" viewBox="0 0 20 22" fill="none" >
<path d="M3 7V17C3 19.2091 4.79086 21 7 21H13C15.2091 21 17 19.2091 17 17V7M12 10V16M8 10L8 16M14 4L12.5937 1.8906C12.2228 1.3342 11.5983 1 10.9296 1H9.07037C8.40166 1 7.7772 1.3342 7.40627 1.8906L6 4M14 4H6M14 4H19M6 4H1" stroke="#28303F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
			</button>
		</div>
	</div>';} else {
		echo '	<div class="flex flex-col items-center p-kw-8 ">
		<div class="border-dashed border-kw-primary-br border-2 rounded-kw-smooth w-full max-w-xl p-kw-8 px-kw-16 flex flex-col items-center shadow-sm bg-kw-primary-bg">
		  <button class="kw-main-button primary mb-kw-2" id="add-new-idp-button">
			Add Identity Provider <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
				  <path d="M12 8V16M16 12H8M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				  </svg>
		  </button>
		  <p class="text-kw-secondary-txt kw-mt-lg text-center">
	  You don\'t have an IDP (Identity Provider) configured. Please configure one by clicking the button above.    </p>
		</div>
	  </div>';
		}

}
