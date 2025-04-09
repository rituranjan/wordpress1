<?php

	/**
	 * Displays UI for service provider setup tab.
	 *
	 * @return void
	 */
function kwsso_idp_configuration_view( $auto_config_hidden, $manual_config_hidden, $idp_conf_details ) {
	$kwsso_saml_identity_name       = $idp_conf_details->get_idp_name();
	$kwsso_saml_login_binding_type  = $idp_conf_details->get_login_binding_type();
	$kwsso_saml_login_url           = ! empty( $idp_conf_details->get_login_url() ) ? $idp_conf_details->get_login_url() : '';
	$kwsso_saml_login_url           = ! is_array( $kwsso_saml_login_url ) ? esc_url_raw( $kwsso_saml_login_url ) : $kwsso_saml_login_url;
	$kwsso_saml_issuer              = $idp_conf_details->get_issuer();
	$kwsso_saml_x509_certificate    = $idp_conf_details->get_x509_certificate();
	$kwsso_saml_x509_certificate    = ! is_array( $kwsso_saml_x509_certificate ) ? maybe_unserialize(  $kwsso_saml_x509_certificate  ) : $kwsso_saml_x509_certificate;
	$kwsso_saml_x509_certificate    = is_array( $kwsso_saml_x509_certificate ) ? $kwsso_saml_x509_certificate : array( 0 => $kwsso_saml_x509_certificate );
	$kwsso_saml_request_signed      = 'unchecked';
	$kwsso_saml_nameid_format       = $idp_conf_details->get_nameid_format();
	$saml_assertion_time_validation = $idp_conf_details->get_assertion_time_validation() ? 'checked' : 'unchecked';

	$kwsso_saml_idp_key = get_kwsso_option( KwConstants::getConstant( 'KWSSO_IDP_KEY' ) ) ? get_kwsso_option( KwConstants::getConstant( 'KWSSO_IDP_KEY' ) ) : 'custom-idp';
	$sync_interval      = 'never';
	$sync_url           = $idp_conf_details->get_idp_metadata_url();
	$sync_selected      = ! empty( $sync_interval ) ? 'checked' : '';
	$hidden             = empty( $sync_interval ) ? 'hidden' : '';

	echo '	<form id="auto-config-container" method="post" action="" class="kw-subpage-container ' . esc_attr( $auto_config_hidden ) . '" enctype="multipart/form-data" >
				<div class=" flex flex-col gap-kw-6 px-kw-4">
					<div class="w-full flex m-kw-4">
						<div  style="width:40%">
							<h5 class="kw-subtitle"> ' . __( 'Fetch or Upload Identity provider metadata', 'saml-sso-wp-single-sign-on' ) . '</h5>
							<div class="kwsso_note mr-kw-8" >
								<p class="kw-subtitle-secondary">' . __( 'You can configure your IDP here in three ways.', 'saml-sso-wp-single-sign-on' ) . '</p>
								<ol>
									<li>' . __( 'You can Directly fetch the configurtaion using the Metadata Url.', 'saml-sso-wp-single-sign-on' ) . '</li>
									<li>' . __( 'You can Upload the the metadata file', 'saml-sso-wp-single-sign-on' ) . '</li>
									<li>' . __( 'You can click on "Configure it Manually" button to configure it manually', 'saml-sso-wp-single-sign-on' ) . '</li>
								</ol>  									
							</div>	
						</div>

					<div class="flex-1">
						<div  class="w-[95%] py-kw-4 pr-kw-4">
						<input class="w-full kw-input hidden" type="text" name="kwsso_saml_idp_key" required  style="width: 95%;" value="' . esc_attr( $kwsso_saml_idp_key ) . '" />

							<div class="hidden kw-input-wrapper">
								<label class="kw-input-label">' . __( 'IDP Name', 'saml-sso-wp-single-sign-on' ) . '</label>
								<input class="w-full kw-input" type="text" name="kwsso_saml_idp_name" required placeholder="Identity Provider name like ADFS, SimpleSAML" style="width: 95%;" value="' . esc_attr( $kwsso_saml_identity_name ) . '" />
							</div>';
						wp_nonce_field( 'kwsso_upload_or_fetch_metadata' );
					echo ' <input type="hidden" name="kwsso_action" value="kwsso_upload_or_fetch_metadata" />
							<input type="hidden" name="action" value="fetch_metadata" />
							<input class="w-full kw-input hidden" type="text" name="kwsso_saml_idp_key" required  style="width: 95%;" value="' . esc_attr( $kwsso_saml_idp_key ) . '" />

							<div class="kw-input-wrapper">
								<label class="kw-input-label">' . __( 'Enter IDP MetaData URL', 'saml-sso-wp-single-sign-on' ) . '</label>
								<input class="w-full kw-input" type="url" name="metadata_url"  placeholder="' . __( 'Enter metadata URL of your IdP.', 'saml-sso-wp-single-sign-on' ) . '" style="width:95%" value="' . esc_url( $sync_url ) . '" />
							</div>
							
						<div class="kw-input-wrapper mt-kw-6">
								<button class="kw-main-button primary" type="submit"  value="Fetch & Save Metadata" />
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" ><path d="M3 11H4C8.97056 11 13 15.0294 13 20V21M3 7H4C11.1797 7 17 12.8203 17 20V21M3 3H4C13.3888 3 21 10.6112 21 20V21M8 18.5C8 19.8807 6.88071 21 5.5 21C4.11929 21 3 19.8807 3 18.5C3 17.1193 4.11929 16 5.5 16C6.88071 16 8 17.1193 8 18.5Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									Fetch & Save Metadata
									<svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none" >
										<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
										<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
									</svg>
								</button>
							</div>

							<div class="svg-icon">
								<svg width="40" height="40" >
									<circle cx="20" cy="20" r="18" fill="black" />
									<text x="50%" y="50%" text-anchor="middle" alignment-baseline="central" font-family="Arial" font-size="16" fill="white">or</text>
								</svg>
							</div>
							<input type="hidden" name="kwsso_action" value="kwsso_upload_or_fetch_metadata" />
							<input type="hidden" name="action" value="upload_metadata" />
							<div class="kw-input-wrapper mt-kw-6">
								<input class="w-full kw-input" id="upload_idp_metadata_actual" hidden type="file"  name="idp_metadata_file" />
							</div>
							<div class="flex items-center kw-select-container py-kw-2 px-kw-4 h-[56px] pr-kw-1" style="width: 95%;">
								<div class="flex-grow">
									<h6 id="choosen_file_id" class="m-kw-0">No file Choosen</h6>
								</div>
								<button type="button" id="upload_idp_metadata" name="kwsso_request_type" class="kw-main-button secondary"> 
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" ><path d="M9 13L10.5858 11.4142C11.3668 10.6332 12.6332 10.6332 13.4142 11.4142L15 13M12 11V16M22 10V17C22 19.2091 20.2091 21 18 21H6C3.79086 21 2 19.2091 2 17V7C2 4.79086 3.79086 3 6 3H8.66667C9.53215 3 10.3743 3.28071 11.0667 3.8L12.9333 5.2C13.6257 5.71929 14.4679 6 15.3333 6H18C20.2091 6 22 7.79086 22 10Z" stroke="#28303F" stroke-width="2" stroke-linecap="round"/></svg>
									Choose File 
								</button>
							</div>
							<div class="kw-input-wrapper mt-kw-6">
								<button class="kw-main-button primary" type="submit"  class="" value="Upload Metadata File" />
									<svg width="19" height="19" viewBox="0 0 24 24" fill="none" >
										<path d="M9 6L12 3M12 3L15 6M12 3L12 15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M7.5 9L7 9C4.79086 9 3 10.7909 3 13L3 17C3 19.2091 4.79086 21 7 21L17 21C19.2091 21 21 19.2091 21 17L21 13C21 10.7909 19.2091 9 17 9L16.5 9" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg> Upload Metadata File
									<svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none" >
									<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
									<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
									</svg>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>';

	echo '<div>
		<div id="manual-config-container" class="kw-subpage-container ' . esc_attr( $manual_config_hidden ) . '" >
			<h5 class="kw-subtitle my-kw-6 px-kw-6">Configure Identity Provider Manually:</h5>
	<div class="flex">
		<div class="flex-1">			 
			<div class="flex mt-kw-4 ml-kw-4">
				<div class="p-kw-6 kwsso-shadow-card">
				<form method="post" action="">';
		wp_nonce_field( 'kwsso_save_idp_configurations' );
		echo '<input type="hidden" name="kwsso_action" value="kwsso_save_idp_configurations" />
						<div>
							<p class="kw-subtitle text-blue-600">Manual IDP Configuration:</p>
							<input class="w-full kw-input hidden" type="text" name="kwsso_saml_idp_key" required  style="width: 95%;" value="' . esc_attr( $kwsso_saml_idp_key ) . '" />
							<div class="py-kw-4">
								<div class="kw-input-wrapper my-kw-6">
									<label class="kw-input-label">Identity Provider Name:</label>
									<input class="w-full kw-input " type="text" required name="kwsso_saml_identity_name" placeholder="Identity Provider Name" value="' . esc_attr( $kwsso_saml_identity_name ) . '" required />
								</div> 

								<div class="kw-input-wrapper my-kw-6">
									<label class="kw-input-label">IDP EntityID / Issuer:</label>
									<input class="w-full kw-input" type="text" name="kwsso_saml_issuer" placeholder="Identity Provider Entity ID or Issuer"  value="' . esc_url( $kwsso_saml_issuer ) . '" required  />
								</div>	

								<div class="kw-input-wrapper my-kw-6">
									<label class="kw-input-label">SAML Login URL:</label>
									<input class="w-full kw-input" type="url" name="kwsso_saml_login_url" placeholder="Single Sign On Service URL of your IdP" value="' . esc_url( $kwsso_saml_login_url ) . '" required  />
								</div>	';
	if ( empty( $kwsso_saml_x509_certificate ) ) {

			echo '		<div class="kw-input-wrapper my-kw-6">
                     		   <label class="kw-input-label">X.509 Certificate</label>
								<textarea class="kw-textarea" rows="6" cols="5" name="kwsso_saml_x509_certificate[0]" 
								placeholder="Enter X.509 Certificate in Correct format:
										-----BEGIN CERTIFICATE-----
										#############################
										-----END CERTIFICATE-----
								"></textarea>
                  		  </div>';
	} else {
		foreach ( $kwsso_saml_x509_certificate as $key => $value ) {
			echo '
									<div class="kw-input-wrapper my-kw-6">
										<label class="kw-input-label">X.509 Certificate</label>
										<textarea  class="kw-textarea" style="font-size: 12px;" rows="6" cols="5" name="kwsso_saml_x509_certificate[' . esc_attr( $key ) . ']"
										placeholder="Enter X.509 Certificate in Correct format:
										-----BEGIN CERTIFICATE-----
										#############################
										-----END CERTIFICATE-----
								">' . esc_attr( $value ) . '</textarea>
									</div>';
		}
	}

			echo '
								<div class="kw-input-wrapper hidden my-kw-6">
								<label class="kw-input-label">IDP Metadata URL(Optional):</label>
									<input class="w-full kw-input" type="text" name="metadata_url" placeholder="Enter the metadata URl"  value="' . esc_url( $sync_url ) . '"   />
								</div>
								
								<div class="flex items-center my-kw-6">
									<h5  style="width:300px" class="kw-subtitle text-primary ">Choose SSO BInding Type:</h5>
									<select name="kwsso_saml_login_binding_type" id="kwsso_saml_login_binding_type">
										<option value="HTTP-Redirect"' .
										( ( $kwsso_saml_login_binding_type == 'HTTP-Redirect' || empty( $kwsso_saml_login_binding_type ) ) ? ' selected="selected"' : '' ) .
										'>HTTP-Redirect Binding</option>
										<option value="HTTP-POST"' .
										( $kwsso_saml_login_binding_type == 'HTTP-POST' ? ' selected="selected"' : '' ) .
										'>HTTP-POST Binding</option>
									</select>								
								</div>
								<div class="flex items-center  my-kw-4">
									<h5 style="width:300px" class="kw-subtitle text-primary ">Assertion Time Validity :</h5>
										<label class="kw-switch">
											<input type="checkbox" name="kwsso_assertion_time_validity"  value="Yes" ' . esc_attr( $saml_assertion_time_validation ) . ' />
											<span class="kw-radio-button-slider"></span>
										</label>
								</div>
										<div class="flex my-kw-8">
												<button class="kw-main-button primary" name="submit" type="submit" />
													Save Configurations
													<svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none" >
														<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
														<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
													</svg>
												</button>
												<input type="button" class="kw-main-button secondary mx-kw-4" name="test" onclick="kwBeginTestConnection();"';
	if ( ! kwsso_check_if_sp_configured() || ! $kwsso_saml_x509_certificate ) {
		echo 'disabled';
	}
		echo ' value="Test configuration" />
											</div>	
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="flex-1">			 
			<div class="flex mt-kw-4 mx-kw-8 pr-kw-4">
				<div class="p-kw-6 kwsso-shadow-card">
						<div>
							<p class="kw-subtitle text-blue-600">Advance Settings:</p>
							<div class="p-kw-2 my-kw-2 rounded flex items-center bg-amber-50 justify-between">
								<div class="flex gap-kw-2 items-center">
									<svg width="48" height="48">
									<path d="M34.43 36H13.57a3.33 3.33 0 0 1-3.31-2.89L8 16.43a2 2 0 0 1 3.35-1.73l3.73 3.45A2 2 0 0 0 18 18l4.46-5.26a2 2 0 0 1 3.06 0L30 18a2 2 0 0 0 2.89.17l3.73-3.45A2 2 0 0 1 40 16.43L37.74 33.1a3.33 3.33 0 0 1-3.31 2.9Z"></path>
									</svg>
									<div>
									<p class="font-bold m-kw-0"> This is a Premium Feature</p>
									</div>
								</div>
								<div class="flex gap-kw-2">
									<a class="kw-main-button primary " target="_blank" href="' . esc_url( KWSSO_CHECKOUT_URL ) . '" style="cursor:pointer;">Upgrade</a>
									<button class="kw-main-button secondary" type="button" style="cursor:pointer;" onClick="contactUsOnClick()">Help</button>
								</div>
							</div>
							
							<div class="py-kw-4">
								<div class="flex items-center">
									<h5  style="width:300px" class="kw-subtitle text-primary ">Auto Sync Metadata:</h5>
									<div class="kw-input-wrapper ">
										<select disabled style="margin-right:10px">
											<option >Never</option>"
										</select>
									</div>
								</div>
								

								<div class="flex items-center my-kw-4">
									<h5  style="width:300px" class="kw-subtitle text-primary ">Choose NameID format:</h5>
									<div class="kw-input-wrapper ">				<select style="margin-top:1%;width:95%;" disabled name="kwsso_saml_nameid_format" >
											<option value="' . KwNameIdFormatConst::getFormat( 'UNSPECIFIED' ) . '">' . KwNameIdFormatConst::getFormat( 'UNSPECIFIED' ) . '</option>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- html from plugin.
						echo '			</select></div>
								</div>	
				
								<div class="flex items-center  my-kw-4">
									<h5 style="width:300px" class="kw-subtitle text-primary ">Enable Signed SSO Requests:	</h5>
										<label class="kw-switch">
											<input type="checkbox" disabled name="kwsso_saml_request_signed"  value="Yes" ' . esc_attr( $kwsso_saml_request_signed ) . ' />
											<span class="kw-radio-button-slider"></span>
										</label>
								</div>
								<div class="flex items-center  my-kw-4">
									<h5 style="width:300px" class="kw-subtitle text-primary ">Enable Single Logout:	</h5>
										<label class="kw-switch">
											<input type="checkbox" disabled name="kwsso_saml_request_signed"  value="Yes" ' . esc_attr( $kwsso_saml_request_signed ) . ' />
											<span class="kw-radio-button-slider"></span>
										</label>
								</div>
								<div class="kw-input-wrapper my-kw-8">
									<input class="kw-main-button primary"disabled type="submit" name="submit" value="Save Settings" />
								</div>		
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
				</div>
			</div>

	<div>
	</div>';
}

