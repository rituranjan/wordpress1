<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function kwsso_link_and_shortcode_view( $idp_metadata ) {
	$sp_base_url = KWSSO_Utils::kwsso_get_sp_base_url();
	$rows        = array(
		array(
			'name'    => 'Shortcode for SSO',
			'id'      => 'kwsso_configure_sso_shortcode',
			'content' => '[KWSSO_SAML_SSO]',
		),
		array(
			'name'    => 'HTML Code for Login',
			'id'      => 'kwsso_configure_sso_login_url',
			'content' => '&lt;a href="' . esc_url( $sp_base_url ) . '/?kwsso_action=kwsso_sso_user_login"&gt;' . esc_attr( $idp_metadata->get_idp_name() ) . '&lt;/a&gt',
		),
	);

	echo '
    <div class="kw-subpage-container">
        <div class="kw-header">
            <p class="kw-head-text flex-1">Links And Shortcodes</p>
        </div>
        <div class="border-b flex flex-col gap-kw-6 pb-kw-4 px-kw-4 ">         
            <div class="w-full"> 
                <h5 class="kw-subtitle  m-kw-4"></h5>
                <div class="div-table-body">
                    <div class="kw-table">';

	foreach ( $rows as $row ) {
		echo '
            <div class="kw-row">
                <div class="kw-cell kw-link-name"><a class="kw-subtitle text-primary text-blue-600">' . $row['name'] . '</a></div>
                <div class="kw-cell kw-url"><span class="kw-link-wrapper" id="' . $row['id'] . '">' . $row['content'] . '</span></div>
                <div class="kw-cell kw-copy-icon copy-button">
                    <button class="kw-main-button secondary" id="' . $row['id'] . '_copy" onclick="kwCopyToClipboard(this, \'#' . $row['id'] . '\', \'#' . $row['id'] . '_copy\');">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"></path>
                        </svg>
                        Copy
                    </button>
                    <span class="tooltiptext">Copy To Clipboard</span>
                </div>
            </div>';
	}

	echo '
                    </div>
                </div>
            </div>
        </div>
    </div>';
}

function kwsso_custom_sso_redirection_view() {
	$sp_base_url = KWSSO_Utils::kwsso_get_sp_base_url();
	 echo '<div id="redirection-settings-container" class=" kw-subpage-container">
	 <div class="kw-header">
			<p class="kw-head-text flex-1">Redirection Settings</p>
		</div>	';
	kwsso_show_premium_feature_notice();
	echo '
	<div class="border-b flex flex-col gap-kw-6 px-kw-4">
		<div class="w-full flex m-kw-4">
			<div class="flex-1 my-kw-6">
				<h5 class="kw-subtitle">After SSO and SLO Redirection URLs</h5>
		<div class="kwsso_note mr-kw-8">
					After completing SSO or Logging out, users will be redirected to these URLs. If no URL is specified, they will return to the page where the SLO or SSO process began.
				</div>
			</div>
			<div class="flex-1 px-kw-8 my-kw-6">
				<div class="my-kw-4 py-kw-2">
				<label class="kw-switch">
				<input disabled type="checkbox" >			
				<span class="kw-radio-button-slider"></span>
			</label>
			<span class="px-kw-4"><b>Use Custom URLs for Redirection</b></span>
				</div>
				<div class="kw-input-wrapper my-kw-4">
					<label class="kw-input-label">Redirection Url After SSO</label>
					<input class="kw-input" type="url" style="width:100%;" placeholder="Enter after sso redirection URL" value=""/>
				</div>
				<div class="kw-input-wrapper my-kw-4">
					<label class="kw-input-label">Redirection Url After Logout</label>
					<input type="url" class="kw-input" style="width:100%;" placeholder="Enter after Logout redirection URL" value=""/>
				</div>
				<input type="submit" class="kw-main-button primary" value="Update" disabled/>
			</div>
		</div>
	</div>';
		kwsso_auto_redirection_from_login_layout();
		kwsso_auto_redirection_from_site_layout();
	echo ' </div>';

}


function kwsso_auto_redirection_from_site_layout() {
	echo '
	<div class=" px-kw-4">
			<div class="w-full flex m-kw-4">
				<div class="flex-1">
					<h5 class="kw-subtitle">Site Access to only Logged in Users</h5>
				<div class="kwsso_note mr-kw-16">These options ensure that only logged-in users can access your site. <br> Enable the first option to auto-redirect users to the IdP for login.<br> Enable the second option to auto-redirect users to the WP Admin page for login.</div>
					<div class="flex flex-col my-kw-6">       
					<h5 class="kw-subtitle">Force authentication with your IdP on each login attempt</h5>
					<div class="kwsso_note mr-kw-16">Turning this option will require users to re-enter their credentials at your IdP for every login attempt, even if they are already logged in to the IdP. Depending on your Identity Provider, additional configuration may be needed to enforce this behavior.</div>
				</div>
					</div>
				<div class="flex-1">   
				<div class="flex flex-col gap-kw-8 py-kw-4"> 
					<div>
							<label class="kw-switch">
								<input type="checkbox" disabled >
									<span class="kw-radio-button-slider"></span>
							</label>
						<span class="px-kw-4"><b>Automatically Redirect users to the IDP if user not logged in.</b></span>
					</div>
				</div>
				<div class="flex flex-col gap-kw-8 mb-kw-12"> 
				<div>
					<label class="kw-switch">
						<input type="checkbox" disabled >
						<span class="kw-radio-button-slider"></span>
					</label>
					<span class="px-kw-4"><b>Automatically Redirect to WP Login page if user not logged in</b></span>
				</div>
				</div>
				
				<div class="flex flex-col gap-kw-2 my-kw-8 py-kw-4">       
				<div >
				<div >
						<label class="kw-switch">
						<input type="checkbox" disabled>
							<span class="kw-radio-button-slider"></span>
						</label>
						<span class="px-kw-4"><b>Enable Force authentication</b></span>
				</div>
			</div>
							
			</div>     
			</div>
		</div>
	</div>';
}




function kwsso_auto_redirection_from_login_layout() {
	echo '
    <div class=" px-kw-4 ">
        <div class="w-full flex m-kw-4 ">
            <div class="flex-1">
                <h5 class="kw-subtitle">Auto Redirect to IDP From WordPress Login Page</h5>
                <p class="kwsso_note mr-kw-16">
                    Enable this option to redirect users visiting the WP login page to your configured IdP for authentication.
                </p>
            </div>
            <div class="flex-1">
                <div class="flex flex-col gap-kw-4 my-kw-4">
                    <div>
                        <label class="kw-switch">
                            <input type="checkbox" disabled >
	                            <span class="kw-radio-button-slider"></span>
                        </label>
                        <span class="px-kw-4"><b>Redirect to IdP from WordPress Login Page</b></span>
                    </div>
                </div>     
            </div>
        </div>
    </div>
	';
}




