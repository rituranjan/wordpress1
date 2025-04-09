<?php
/**
 * File to display sections of Attribute and Role Mapping.
 *
 * @package keywoot-saml-sso\views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Function to display Attribute Mapping sub-tab in Attribute/Role Mapping tab.
 *
 * @return void
 */
function kwsso_attribute_mapping( $attr_mapping_hidden, $show_notice, $disabled ) {
	
	// Retrieve user mapping attributes.
	$kwsso_mapping_username = 'NameID';
	$kwsso_mapping_email    = 'NameID';
	$idp_attrs              = maybe_unserialize( get_kwsso_option( KwConstants::getConstant('TEST_CONFIG_ATTIBUTES') ) );

	echo '<div id="attr-mapping-container" class="kw-subpage-container ' . esc_attr( $attr_mapping_hidden ) . '">
			<div class="kw-header">
				<p class="kw-head-text flex-1">Attributes Mapping</p>
				<input class="kw-main-button primary" ' . esc_attr( $disabled ) . ' value="Save Settings">
			</div>';
	kwsso_show_premium_feature_notice();

	echo '<div class="flex flex-col gap-kw-6 px-kw-4">
			<div class="w-full flex m-kw-4">
				<div  style="width:40%">
		<div class="flex-1 mr-kw-4">
			<div class="w-full p-kw-4 pr-kw-4 kwsso-shadow-card">	
				<div class="mb-kw-2 kw-subtitle font-semibold text-blue-600">
						<h5>Map Required User Details:</h5>
				</div>
				<div class="kwsso_note my-kw-2"> 
				This feature allows you to map the WordPress username and email with the attributes received from the Identity Provider (IDP).				</div>';
			echo '<div class="kw-sso-table-width">';
			echo kwsso_render_required_attribute_field( 'Username', $kwsso_mapping_username, true, $idp_attrs, $disabled );
			echo kwsso_render_required_attribute_field( 'Email', $kwsso_mapping_email, true, $idp_attrs, $disabled );	
			echo'	</div> 
			</div>
		</div>
					
	<div class="mr-kw-8 py-kw-6">';
	kwsso_display_attrs_list();
	echo '</div>
	</div>';

	echo '<div class="flex-1  mr-kw-4">
			<div class="w-full p-kw-4 pr-kw-4 kwsso-shadow-card">	
				<div class="mb-kw-2 kw-subtitle font-semibold text-blue-600">
									<h5>Map Basic User Details:</h5>
				</div>
				<div class="kwsso_note my-kw-2 mr-kw-4"> 
					This feature enables you to map basic user details with the attributes received from the Identity Provider (IDP)				
				</div>';
	echo '<div class="kw-sso-table-width" style="width: 100%;">';

	echo kwsso_render_attribute_field( 'First Name', '', false, $idp_attrs, $disabled );
	echo kwsso_render_attribute_field( 'Last Name', '', false, $idp_attrs, $disabled );
	echo kwsso_render_attribute_field( 'Nickname', '', false, $idp_attrs, $disabled );

	echo '</div> 

		</div>';

	display_custom_attr_mapping_container( $disabled );

	echo '</div></div></div></div>';
}

/**
 * Helper function to render a single attribute field.
 *
 * @param string $label The field label.
 * @param string $value The selected value.
 * @param bool   $required If the field is required.
 * @param array  $idp_attrs The IDP attributes.
 * @param string $disabled Disabled attribute for HTML input.
 * @return string Rendered HTML for the attribute field.
 */
function kwsso_render_attribute_field( $label, $value, $required, $idp_attrs, $disabled ) {
	$required_attr = $required ? 'required' : '';
	$field_html    = '<div class="kw-sso-role-row">
        <div class="kwsso_role_mapping_table">
            <strong>' . esc_html( $label ) . ( $required ? ' <span style="color:red;">*</span>' : '' ) . '</strong>
        </div>
        <div>';

	if ( $idp_attrs ) {
		$field_html .= '<select disabled class="kw-sso-attr-input-width" ' . $required_attr . '>
                            <option value="">--Select an Attribute--</option>';
		foreach ( $idp_attrs as $key => $attr_value ) {
			$selected    = ( $value === $key ) ? 'selected' : '';
			$field_html .= '<option value="' . esc_html( $key ) . '" ' . $selected . '>' . esc_html( $key ) . '</option>';
		}
		$field_html .= '</select>';
	} else {
		$field_html .= '<input type="text" style="width: 400px;" disabled placeholder="Enter attribute name for ' . esc_attr( $label ) . '" class="kw-sso-attr-input-width" value="' . esc_html( $value ) . '" ' . $required_attr . ' />';
	}

	$field_html .= '</div></div>';

	return $field_html;
}

function kwsso_render_required_attribute_field( $label, $value, $required, $idp_attrs, $disabled ) {
	$required_attr = $required ? 'required' : '';
	$field_html    = '<div class="kw-sso-role-row">
        <div class="kwsso_role_mapping_table">
            <strong>' . esc_html( $label ) . ( $required ? ' <span style="color:red;">*</span>' : '' ) . '</strong>
        </div>
        <div>';

	if ( $idp_attrs ) {
		$field_html .= '<select style="width: 250px;" disabled class="" ' . $required_attr . '>
                            <option value="">--Select an Attribute--</option>';
		foreach ( $idp_attrs as $key => $attr_value ) {
			$selected    = ( $value === $key ) ? 'selected' : '';
			$field_html .= '<option value="' . esc_html( $key ) . '" ' . $selected . '>' . esc_html( $key ) . '</option>';
		}
		$field_html .= '</select>';
	} else {
		$field_html .= '<input type="text" style="width: 200px;" disabled placeholder="Enter attribute name for ' . esc_attr( $label ) . '" class="kw-sso-attr-input-width" value="' . esc_html( $value ) . '" ' . $required_attr . ' />';
	}

	$field_html .= '</div></div>';

	return $field_html;
}


function display_custom_attr_mapping_container( $disabled ) {
	echo '<div class="w-full my-kw-8 p-kw-4 pr-kw-4 kwsso-shadow-card">
		<div class="flex justify-between items-center">	
			<div class="mb-kw-2 kw-subtitle font-semibold text-blue-600 mr-4">
				<h5>Custom Attributes Mapping ( Map Custom User Attributes ):</h5>
			</div>
			<div>
				<button type="button" id="kwsso_add_custom_attr_row" class="kw-main-button secondary my-kw-4" value="Add Attribute" >
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none">
						<path d="M12 8V16M16 12H8M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="gray" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Add More
				</button>
			</div>
		</div>
		
		<div class="">
			<!-- Container for the dynamic rows -->
			<div id="kwsso-custom-attr-row-container">
				<div class="custom-attr-row my-kw-4">
					<input type="text"  disabled placeholder="Custom attribute name">
					<select disabled style="width:50%"><option value="">--Select an Attribute--</option></select>
					<button type="button" class="kw-main-button primary kwsso-custom-attr-remove-button" style="float:right;">X</button>
				</div>
				<div class="custom-attr-row my-kw-4">
					<input type="text"  disabled placeholder="Custom attribute name">
					<select disabled style="width:50%"><option value="">--Select an Attribute--</option></select>
					<button type="button" class="kw-main-button primary kwsso-custom-attr-remove-button" style="float:right;">X</button>
				</div>
				<div class="custom-attr-row my-kw-4">
					<input type="text"  disabled placeholder="Custom attribute name">
					<select disabled style="width:50%"><option value="">--Select an Attribute--</option></select>
					<button type="button" class="kw-main-button primary kwsso-custom-attr-remove-button" style="float:right;">X</button>
				</div>
			</div>
			
		</div>
	</div>';

}

/**
 * Function to display role mapping sub-tab in the Attribute/Role Mapping tab.
 *
 * @return void
 */
function kwsso_role_mapping_view( $role_mapping_hidden, $show_notice, $disabled ) {

	$idp_attrs      = maybe_unserialize( get_kwsso_option( KwConstants::getConstant('TEST_CONFIG_ATTIBUTES') ) );
	$toggle_options = array(
		"Restrict updating roles for existing users",
		'Use role mapping settings for admin users as well',
	);

	echo '<div id="role-mapping-container" class="' . esc_html( $role_mapping_hidden ) . ' kw-subpage-container">
    		<div class="kw-header">
				<p class="kw-head-text flex-1">Role Mapping</p>
				<input type="submit" class="kw-main-button primary" disabled value="Save Settings" />
			</div>';
			kwsso_show_premium_feature_notice();
	echo '	<div class="flex flex-col gap-kw-6 px-kw-4">
				<div class="w-full flex m-kw-4">
					<div class="py-kw-4" style="width:40%">
						<p class="kw-subtitle">SSO User Role Mapping:</p>
						<div class="kwsso_note mr-kw-8">
							Role mapping lets you match user groups from your Identity Provider (IdP) to WordPress roles. </br>
							When users sign up automatically, they get assigned a role based on the group they’re in from the IdP. </br>
						</div>			
						<div class="mr-kw-8 py-kw-6">';
							kwsso_display_attrs_list();
				echo '	</div>	
					</div>

					<div class="flex-1 mr-kw-4">
						<div  class="w-full p-kw-4 pr-kw-4 kwsso-shadow-card">
							<div class="my-kw-2 kw-sso-table-width" style="width:100%;border-bottom: none;">
								<div class="mb-kw-2 kw-subtitle font-semibold text-blue-600">
									<h5>Pick the IDP attribute that will determine user roles</h5>
								</div>';

				display_choose_attribute_for_role_mapping_field();
				echo '		</div>
						</div>';

				echo '	<div  class="w-full p-kw-4 pr-kw-4 my-kw-8 kwsso-shadow-card">
							<div class="my-kw-2 kw-sso-table-width" >
								<div class="mt-kw-6 kw-sso-table-width">
									<div class="kw-subtitle font-semibold text-blue-600">
										<h5>User Role Settings</h5>
									</div>';
							echo '  <div >
										<div class=" py-kw-4">
											<div class="kwsso-checkbox-container">
												<div class="kwsso-label-width font-medium">
													Default role of the user
												</div>
												<div class="pl-kw-4">';
												kwsso_show_default_role();
							echo '         		</div>
											</div>
										</div>';

	foreach ( $toggle_options as $option ) {
		echo '
									<div class=" pb-kw-2"> 
										<div class="kwsso-checkbox-container">
											<div class="kwsso-label-width font-medium">
												' . esc_html( $option ) . '
											</div>
											<div class="pl-kw-8 ml-kw-4">
												<label class="kw-switch">
													<input type="checkbox" ' . esc_attr( $disabled ) . ' value="checked" />
													<span class="kw-radio-button-slider"></span>
												</label>
											</div>
										</div>
									</div>';
	};

				echo '				</div>
								</div>
					
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
}
/**
 * Function to display role mapping sub-tab in the Attriute/Role Mapping tab.
 *
 * @return void
 */
function display_choose_attribute_for_role_mapping_field() {
	echo '
    <div class="kwsso_role_mapping_field my-kw-2">
        <div class="font-medium">
            Role Attribute <span style="color:red;">*</span>
        </div>
        <div >';

		echo '<select disabled class="ml-kw-1" style="width: 400px;" >
                <option value="">--Select the IDP attribute to assign roles to Users.--</option>
              </select>
        </div>
    </div>';
}

/**
 * Function to display role mapping sub-tab in the Attriute/Role Mapping tab.
 *
 * @return void
 */
function display_fields_for_each_roles() {
	$wp_roles       = new WP_Roles();
	$roles          = $wp_roles->get_names();
	$max_role_show  = 8;
	$total_roles    = count( $roles );
	$show_view_more = $total_roles > $max_role_show;

	foreach ( $roles as $role_value => $role_name ) {
		$placeholder = 'separated IDP group values separated by Semi-colon(;)';
		echo '<div class="kw-sso-role-row" style="' . ( $show_view_more && $max_role_show-- <= 0 ? 'display:none;' : '' ) . '">
                <div class="kwsso_role_mapping_table font-medium">
                    ' . esc_html( $role_name ) . '
                </div>
                <div >
                    <input type="text" placeholder="' . esc_html( $placeholder ) . '" style="width: 400px;" disabled/>
                </div>
            </div>';
	}
}


/**
 * Funciton to show default role option.
 *
 * @return string HTML for default role.
 */
function kwsso_show_default_role() {
	$roles        = wp_roles()->roles;
	$default_role = get_site_option( 'default_role' );

	echo '<div class="flex" >
				<div>
				<select disabled  style="width:290px;" disabled>';
	foreach ( $roles as $key => $value ) {
		if ( ! empty( $value ) ) {
			$selected = ( $default_role === $key ) ? 'selected' : '';
			echo '<option value="' . esc_html( $key ) . '" ' . esc_html( $selected ) . '>' . esc_html( $value['name'] ) . '</option>';
		}
	}
	echo '   </select></div></div>';
}