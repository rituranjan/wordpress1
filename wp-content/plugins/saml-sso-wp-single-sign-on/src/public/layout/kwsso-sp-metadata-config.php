<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

echo '	<div id="subtab" class="kw-subtabs-container">
                    <div class="kw-subtab-item">
                        <span class="kw-subtab-title" id="kwsso-sp-metadata-details">
                            Service Provider Details
                        </span>
                    </div>
                    <div class="kw-subtab-item">
                        <span class="kw-subtab-title" id="kwsso-sp-metadata-xml">
                        	SP Metadata XML
                        </span>
                    </div>
					<div class="kw-subtab-item">
                        <span class="kw-subtab-title" id="kwsso-sp-core-conf">
                        	SP Core Configuration
                        </span>
                    </div>
		</div>

<div id="kwsso-sp-metadata-details-container" class="kw-subpage-container ' . esc_attr( $kwsso_sp_metadata_details_hidden ) . '">	

	<div class="div-table-body my-kw-8">
		<div class="kw-table">
		         <!-- Header Row -->
            <div class="kw-row font-semibold kw-subtitle kw-header">
                <div class="kw-cell  kw-name">Metadata Parameter</div>
                <div class="kw-cell  kw-name text-white">Value</div>
                <div class="kw-cell kw-name kw-copy-icon"></div>
            </div>';
foreach ( $sp_matadata_details as $sp_matadata_field ) {
	echo '
        <div class="kw-row">
            <div class="kw-cell kw-name">
                <a class="kw-subtitle text-primary text-blue-600">' . $sp_matadata_field['label'] . '</a>
            </div>
            <div class="kw-cell kw-url">
                <span class="kw-link-wrapper" id="' . $sp_matadata_field['id'] . '">' . $sp_matadata_field['value'] . '</span>
            </div>
            <div class="kw-cell kw-copy-icon copy-button">
                <button class="kw-main-button secondary" id="' . $sp_matadata_field['id'] . '_copy" onclick="kwCopyToClipboard(this, \'#' . $sp_matadata_field['id'] . '\', \'#' . $sp_matadata_field['id'] . '_copy\');">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                    Copy
                </button>
                <span class="tooltiptext">Copy To Clipboard</span>
            </div>
        </div>
    ';
};

echo '	<div class="kw-row border-b">
				<div class="kw-cell kw-name"><a class="kw-subtitle text-primary text-blue-600">Download SP Certificate<span style="color:red;">(Premium)</span></a></div>
				<div class="kw-cell kw-url"><input type="button" disabled class="kw-main-button primary"  value="Download" />
				</div>
			<div class="kw-cell kw-copy-icon">
		
			</div>
	</div>
</div>
</div>
</div>
<div id="kwsso-sp-metadata-xml-container" class=" ' . esc_attr( $kwsso_sp_metadata_xml_hidden ) . ' kw-subpage-container ">
    <div class="kwsso-xml-container my-kw-4">
		<h5 class="kw-subtitle text-primary my-kw-2 text-blue-600">Service Provider XML Metadata:</h5>
        <div class="kwsso-xml-code-box" id="kwsso-xml-display">
            <button class="kwsso-xml-btn-download kw-main-button primary" onclick=downloadXMLMetadata(); id="kwsso-xml-download-btn">Download SP Metadata XML</button>
            <pre id="kwsso-xml-content"></pre>
        </div>
				<div class="svg-icon" style=" margin:4%">
  					<svg width="40" height="40" >
  					  <circle cx="20" cy="20" r="18" fill="black" />
   						 <text x="50%" y="50%" text-anchor="middle" alignment-baseline="central" font-family="Arial" font-size="16" fill="white">or</text>
  					</svg>
				</div>
		<div >
		<h5 class="kw-subtitle text-primary my-kw-2 text-blue-600">Service Provider Metadata URL:</h5>
        <div class="kwsso-xml-url-container">
            <input 
                type="text" 
                class="kwsso-xml-url-input" 
                id="kwsso-xml-metadata-url" 
                value="' . esc_url( $metadata_url ) . '" 
                readonly
                title="Metadata URL"
            >
			<span class="kw-copyicon" id="kwsso-xml-copy-btn"  onclick="kwCopyXMLMetadataUrl()" ><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
							<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"/>
							</svg>
							<span hidden id="showcopied">Copied</span>
							</span>
        </div>
		</div>
    </div>

    <div class="kwsso-xml-toast" id="kwsso-xml-toast">URL copied to clipboard!</div>
    <script>
        const xmlContent = `'.$sp_metadata_xml.'`;
        document.getElementById("kwsso-xml-content").textContent = xmlContent;
    </script>
	</div>';
echo '	
<div id="kwsso-sp-core-conf-container" class="kw-subpage-container ' . esc_attr( $kwsso_sp_core_conf_hidden ) . '">
<div class="flex my-kw-6">
		<div class="flex-1">			 
			<div class="flex mt-kw-4 mx-kw-4 pr-kw-4">
				<div class="p-kw-6 kwsso-shadow-card">
					<form border="0" method="post" id="kwsso_update_idp_settings_form" action="">';
		wp_nonce_field('kwsso_sp_core_config');
		echo '		<input type="hidden" name="kwsso_action" value="kwsso_sp_core_config" />
						<div>
							<p class="kw-subtitle text-blue-600">Change Service Provider Base Url and EntityID/Issuer</p>
							<div class="kwsso_note">Changing these details may require reconfiguration on the IdP side.</div>
							<div class="py-kw-4">
								<div class="kw-input-wrapper">
									<label class="kw-input-label">SP Base URL:</label>
									<input type="text" class="kw-input w-full" name="kwsso_sp_base_url" 
										placeholder="Your site base URL" 
										value="' . esc_url($sp_base_url) . '" 
										required />
								</div> 

								<div class="kw-input-wrapper mt-kw-4">
									<label class="kw-input-label">SP EntityID / Issuer:</label>
									<input type="text" class="kw-input w-full" name="kwsso_sp_entity_id" 
										placeholder="Your site base URL" 
										value="' . esc_url($sp_entity_id) . '" 
										required />
								</div>	
								
								<div class="kw-input-wrapper mt-kw-4">
									<input class="kw-main-button primary" type="submit" name="submit" 
										style="width:100px;" 
										value="Update" />
								</div>		
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
			<div class="flex-1">			 
			<div class="flex mt-kw-4 mx-kw-4 pr-kw-4">
				<div class="p-kw-6 kwsso-shadow-card">
					<form border="0" method="post" action="">
		';

		wp_nonce_field('kwsso_sp_organization_details');

		echo '
						<input type="hidden" name="kwsso_action" value="kwsso_sp_organization_details" />
						<div>
							<p class="kw-subtitle text-blue-600">Organization Details</p>
							<div class="kwsso_note">These details are optional, but adding them will incorporate the following information into the service provider\'s metadata</div>
							<div class="py-kw-4">
							<div class="flex gap-kw-2">
								<div class="kw-input-wrapper">
									<label class="kw-input-label">Organization Name</label>
									<input type="text" class="kw-input w-full" name="kwsso_sp_organization_name" 
										placeholder="Name of your Organization" 
										value="' . esc_attr($organization_name) . '" 
										required />
								</div> 
								<div class="kw-input-wrapper">
									<label class="kw-input-label">Contact Person Name</label>
									<input type="text" class="kw-input w-full" name="kwsso_sp_contact_name" 
										placeholder="Enter the name of Contact Person" 
										value="' . esc_attr($contact_person_name) . '" 
										required />
								</div> 
							</div>
								<div class="kw-input-wrapper my-kw-4">
									<label class="kw-input-label">Organization Url</label>
									<input type="url" class="kw-input w-full" name="kwsso_sp_organization_url" 
										placeholder="Enter Url of your Organization" 
										value="' . esc_url($organization_url) . '" 
										required />
								</div>	
								<div class="kw-input-wrapper my-kw-4">
									<label class="kw-input-label">Contact Person Email </label>
									<input type="email" class="kw-input w-full" name="kwsso_sp_contact_email" 
										placeholder="Enter Email of Contact Person" 
										value="' . esc_attr($contact_person_email) . '" 
										required />
								</div>	
								<div class="kw-input-wrapper my-kw-4">
									<input class="kw-main-button primary" type="submit" name="submit" 
										style="width:100px;" 
										value="Update" />
								</div>		
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	</div>';


