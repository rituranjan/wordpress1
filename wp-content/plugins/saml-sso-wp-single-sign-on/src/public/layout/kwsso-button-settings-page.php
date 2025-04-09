<?php
/**
 * Loads the UI for Button settings tab
 *
 * @param string $button_settings_hidden tab hidden var.
 * @return void
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function kwsso_load_button_settings_page() {
	$settings = array(
		array(
			'form_id'         => 'kwsso_add_sso_button_wp_form',
			'action'          => 'kwsso_add_sso_button_action',
			'input_name'      => 'kwsso_add_sso_button_wp',
			'label_text'      => 'Add a Single Sign on button on the WordPress login page',
			'option_constant' => KwConstants::getConstant('ADD_SSO_BUTTON'),
		),
		array(
			'form_id'         => 'kwsso_use_button_as_shortcode_form',
			'action'          => 'kwsso_button_as_shortcode_action',
			'input_name'      => 'kwsso_use_button_as_shortcode',
			'label_text'      => 'Use Shortcode to Add SSO Button Anywhere <span class="font-semibold text-blue-600">[KWSSO_SAML_SSO]</span>',
			'option_constant' => KwConstants::getConstant('USE_BUTTON_AS_SHORTCODE'),
		),
	);

	echo '<div id="button-settings-container" class="kw-subpage-container">
		<div class="kw-header">
			<p class="kw-head-text flex-1">Button and Customization</p>
		</div>
		<div class="border-b px-kw-8 mt-kw-4">
			<div class="w-full flex my-kw-4">
				<div class="flex-1 my-kw-4 ">
					<h5 class="kw-subtitle text-blue-600">Login Button Settings:</h5>
					<p class="kwsso_note mr-kw-16">
						1. Enable the first option to display an SSO button on the WP login page.<br>
						2. Activate the second option to add an SSO button where the shortcode is placed.
					</p>
				</div>
				<div class="flex-1 mt-kw-4">
					<div class="flex flex-col gap-kw-4 my-kw-4">';

	foreach ( $settings as $setting ) {
		kwsso_render_button_setting_form(
			$setting['form_id'],
			$setting['action'],
			$setting['input_name'],
			$setting['label_text'],
			$setting['option_constant']
		);
	}
	echo '			</div>
				</div>
			</div>
		</div>
	</div>';

	kwsso_customise_sso_button_layout();
}

/**
 * Renders an individual setting form.
 */
function kwsso_render_button_setting_form( $form_id, $action, $input_name, $label_text, $option_constant ) {
	$checked = get_kwsso_option( $option_constant ) == 'true' ? 'checked' : '';
	printf(
		'<div>
			<form id="%1$s" method="post" action="">
				%2$s
				<input type="hidden" name="kwsso_action" value="%3$s"/>
				<p>
					<label class="kw-switch">
						<input type="checkbox" name="%4$s" value="true" %5$s onchange="document.getElementById(\'%1$s\').submit();"/>
						<span class="kw-radio-button-slider"></span>
					</label>
					<span class="px-kw-4 font-semibold">%6$s</span>
				</p>
			</form>
		</div>',
		$form_id,
		wp_nonce_field( $action, '_wpnonce', true, false ),
		$action,
		$input_name,
		$checked,
		$label_text
	);
}


function kwsso_customise_sso_button_layout() {
	$button_html = KWSSO_Utils::kwsso_get_button_styles();
	kwsso_show_premium_feature_notice();
	echo '
    <div class="px-kw-8 my-kw-8">  
        <div class="w-full flex">
            <div class="flex-1">
                <h5 class="kw-subtitle">Customise Login Button Shape</h5>
                <p class="kwsso_note mr-kw-16">
                    This feature enables you to customise the button for SSO according to your own theme.
                </p>
                <h5 class="kw-subtitle mt-kw-4 font-semibold text-blue-600">Button Preview:</h5>
                <div class="py-kw-1">
                   <div ><a >  ' . $button_html . '</a></div>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex-1 flex gap-kw-4 my-kw-8" style="justify-content:space-between">
                    <div class="kw-input-wrapper">
                        <label class="kw-input-label">Custom CSS for SSO Button</label>
                        <textarea class="kw-textarea h-[160px]" disabled></textarea>
                    </div>
                </div>
                <div class="kw-input-wrapper">
                    <input type="button" disabled value="Save Settings" class="kw-main-button primary"/>
                </div>
            </div>
        </div>
    </div>';
}
