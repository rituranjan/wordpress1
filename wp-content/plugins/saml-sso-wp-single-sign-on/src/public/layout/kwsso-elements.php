<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use KWSSO_CORE\Src\Utility\Tab\KWSSO_PluginTabs;
/**
 * HTML for Premium feature notice
 *
 * @param bool $show_notice
 * @return void
 */
function kwsso_show_premium_feature_notice() {
		echo '
<div class="p-kw-6 m-kw-2 rounded flex items-center bg-amber-50 justify-between">
  <div class="flex gap-kw-4 items-center">
    <svg width="48" height="48">
      <path d="M34.43 36H13.57a3.33 3.33 0 0 1-3.31-2.89L8 16.43a2 2 0 0 1 3.35-1.73l3.73 3.45A2 2 0 0 0 18 18l4.46-5.26a2 2 0 0 1 3.06 0L30 18a2 2 0 0 0 2.89.17l3.73-3.45A2 2 0 0 1 40 16.43L37.74 33.1a3.33 3.33 0 0 1-3.31 2.9Z"></path>
    </svg>
    <div>
      <p class="font-bold m-kw-0">This is a Premium Feature</p>
      <p class="m-kw-0">Please Upgrade to our premium plan to use the feature or Reach out to us for any question.</p>
    </div>
  </div>
  <div class="flex gap-kw-4">
    <a class="kw-main-button primary " target="_blank" href="'.esc_url(KWSSO_CHECKOUT_URL).'" style="cursor:pointer;">Buy Premium</a>
    <button class="kw-main-button secondary" type="button" style="cursor:pointer;" onClick="contactUsOnClick()">Contact Us</button>
  </div>
</div>';
}


function kwsso_show_test_result( $user_email, $attrs ) {
    ob_end_clean();
    load_test_conf_page_css();
    echo'<div class="test-result-container">';
    check_test_success_or_failure($user_email, $attrs);
    echo'</div>';
    exit;
}

function check_test_success_or_failure($user_email, $attrs )
{
    if ( ! empty( $user_email ) ) {
        update_kwsso_option( KwConstants::getConstant('TEST_CONFIG_ATTIBUTES'), $attrs );
        $idp_configuration = new KWSSO_IDPConf();
        $idp_configuration->set_is_idp_enabled( true );
        displayTestSuccess($user_email, $attrs);
    } else {
        displayTestFailure();
    }
    display_action_buttons();
}


function kwsso_display_user_attributes_test( $user_email, $attrs ) {
	echo '<div class="kw-user-attribute-container">
        <table class="kw-user-attribute-table">';
	if ( ! empty( $attrs ) ) {
		echo '
        <tr>
            <th class="kw-user-attribute-header">Attribute Name</th>
            <th class="kw-user-attribute-header">Attribute Value</th>
        </tr>';
		foreach ( $attrs as $key => $value ) {
			$value = is_array( $value ) ? implode( '<hr/>', $value ) : $value;
			echo "<tr> 
                    <td class='kw-user-attribute-cell'>" . esc_attr( $key ) . "</td>
                    <td class='kw-user-attribute-cell'>" . ($value) . '</td>
                  </tr>';
		}
	} else {
		echo 'No Attributes has been Received from IDP.';
	}
	echo '</table>    
</div>';
}
function displayTestSuccess($user_email, $attrs) {
	echo '
<div class="div-body">      
  <div class="container">
   <div> <svg class="test-success-icon" width="80" height="80" viewBox="0 0 48 48">
            <path d="M44,24c0,11-9,20-20,20S4,35,4,24S13,4,24,4S44,13,44,24z"></path>
            <polyline points="14,24 21,31 36,16"></polyline>
        </svg>
        </div>
        <div>
    <h1>Test Configuration Successful</h1>';
    check_and_display_nameid_attribute($attrs, $user_email);
 echo'   </div>
    <div class="horizontal-line-container">
      <hr class="horizontal-line">
      <span class="or-text"> Attributes Recieved </span>
      <hr class="horizontal-line">
    </div>';
        kwsso_display_user_attributes_test($user_email, $attrs);
  echo'
    <footer>Powered by Keywoot SAML SSO Plugin</footer>
  </div>
</div>';
}
function displayTestFailure() {
	echo '
    <div class="test-failure-container">
        <svg class="test-failure-icon" width="40" height="40" viewBox="0 0 40 40">
            <path d="M20,38.5C9.799,38.5,1.5,30.201,1.5,20S9.799,1.5,20,1.5S38.5,9.799,38.5,20S30.201,38.5,20,38.5z"></path>
            <path d="M20,2c9.925,0,18,8.075,18,18s-8.075,18-18,18S2,29.925,2,20S10.075,2,20,2 M20,1 C9.507,1,1,9.507,1,20s8.507,19,19,19s19-8.507,19-19S30.493,1,20,1L20,1z"></path>
            <path d="M18.5 10H21.5V30H18.5z" transform="rotate(-134.999 20 20)"></path>
            <path d="M18.5 10H21.5V30H18.5z" transform="rotate(-45.001 20 20)"></path>
        </svg>
        <span class="test-failure-text">TEST FAILED</span>
    </div>';
}
function check_and_display_nameid_attribute( $attrs, $user_email ) {
    if ( ! empty( $attrs['NameID'] ) ) {
        if ( strlen( $attrs['NameID'] ) > 60 ) {
            echo '
                <div class="nameid-warning-container">
                    <div class="warning-icon">!</div>
                    <div class="warning-text">
                        <strong>Warning:</strong> 
                        The username exceeds the 60-character limit. Users with this username will not be able to log in. Please use a shorter username.
                    </div>
                </div>
            ';
        }
    }
    echo '    <p class="email">User Email: ' . esc_attr( $user_email ) . '</p>';
}
function display_action_buttons() {
    echo '
        <div class="action-buttons-container">
            <input type="button" class="action-button" value="Close" onClick="self.close();">
        </div>
        
    ';
}

/**
 * Generates an SVG image element with the provided base64-encoded PNG data.
 *
 * This function creates an SVG image element containing a base64-encoded PNG image
 * specified by the provided `$encoded_data`.
 *
 * @param string $encoded_data Base64-encoded PNG image data to embed in the SVG.
 * @return string The generated SVG image element as a string.
 */
function generate_svg_image_element( $path ) {
	$svg_image = '<svg width="50" height="50" version="1.1" viewBox="0 0 50 50"><image width="50" height="50" xlink:href="data:image/png;base64,' . $path . '"/></svg>';
	return $path;
}


/**
 * This function displays test configuration error.
 *
 * @param string $error_code error code.
 *
 * @param string $display_metadata_mismatch The metadata recieved in SMAL response and stored in plugin if the error corresponds to a mismatched metadata.
 *
 * @param string $status_message The status sent by Identity Provider.
 */
function kwsso_display_test_error( $error_code, $status_message = '' ) {

	echo '<div class="kw-error-container">
			<div class="kw-error-header">Error</div>
			<div class="kw-error-body">
				<p><strong>Error Code: </strong>[' . esc_attr( $error_code ) . ']</p>
				<p><strong>Details: </strong>' . esc_attr( KwErrorCodes::get_error_details( $error_code ) ) . '</p>';
	if ( ! empty( $status_message ) ) {
		echo ' <p><strong>Message: </strong>' . esc_attr( $status_message ) . '</p>';
	}

	echo '			<p><b>Encountering configuration challenges or Need Any Help? Reach out to us at support@keywoot.com for help. We’re here to make setup easier!</b></p>
				<div class="kw-error-footer">
					<input class="kw-error-button" type="button" value="Done" onClick="self.close();">
				</div>
			</div>
		  </div>';
	echo '<style>
		.kw-error-container{font-family:Arial,sans-serif;padding:1.5%;max-width:600px;margin:40px auto;background-color:#fff;border:1px solid #e0e0e0;border-radius:10px;box-shadow:0 8px 16px rgba(0,0,0,.08)}.kw-error-header{background-color:#f44336;color:#fff;padding:12px;text-align:center;font-size:22px;font-weight:500;border-top-left-radius:10px;border-top-right-radius:10px;letter-spacing:1px;text-transform:uppercase}.kw-error-body{color:#444;font-size:15px;line-height:1.5;padding:20px}.kw-error-body p{margin-bottom:1em}.kw-error-body strong{color:#333;font-weight:600}.kw-error-footer{text-align:center;padding:15px;border-top:1px solid #e0e0e0}.kw-error-button{padding:10px 20px;background-color:#007bff;color:#fff;font-size:15px;font-weight:600;border:none;border-radius:5px;cursor:pointer;transition:background-color .3s,transform .2s}.kw-error-button:hover{background-color:#0056b3;transform:scale(1.03)}
		</style>';
	exit;
}


function kwsso_display_attrs_list() {
    $idp_attrs = maybe_unserialize( get_kwsso_option( KwConstants::getConstant('TEST_CONFIG_ATTIBUTES') ) );
    if ( ! empty( $idp_attrs ) ) { 
        echo '<button type="button" onclick="kwToggleIDPAttrTable()" class="kw-main-button secondary my-kw-4" name="show_idp_attribute"> 
        Show Attributes Received from IDP
    </button>';
        echo '<div class="kwsso_support_layout p-kw-4 pr-kw-4 kwsso-shadow-card hidden" name="idp_attributes_table">
            <h3 class="kw-subtitle font-semibold text-blue-600">Attributes Received from the Identity Provider:</h3>
            <div class="kwsso-attr-display-table-wrapper">
                <div class="kwsso-attr-display-table">
                    <div class="kwsso-attr-display-table-header">Attribute Name</div>
                    <div class="kwsso-attr-display-table-header">Attribute Value</div>';

        foreach ( $idp_attrs as $key => $value ) {
            $value = is_array( $value ) ? implode( '<hr/>', $value ) : $value;

            echo '<div class="kwsso-attr-display-table-cell">' . esc_attr( $key ) . '</div>';
            echo '<div class="kwsso-attr-display-table-cell">' . $value . '</div>';
        }

        echo '</div>
            </div>
            <br/>
            <form method="post" action="">
                <input type="submit" class="kw-main-button primary" value="Clear Attributes List">';
        wp_nonce_field( 'kwsso_clear_attributes' );
        echo '<input type="hidden" name="kwsso_action" value="kwsso_clear_attributes">
            </form>
        </div>';
    } 
}

function kwsso_activation_modal() {
	if ( get_kwsso_option( 'kwsso_user_first_activation' ) != 'done' ) {
		$current_user = wp_get_current_user();
		$user_email   = $current_user->user_email;
		echo '<div id="kwsso-feedback-deactivation-modal" class="kwsso-feedback-modal-overlay">
            <div class="kwsso-feedback-modal">
			
            <button id="closs_activation_modal" onclick="" class="kwsso-feedback-close">&times;</button>
                <p><b>Complete the Setup</b></p>
				<form id="kwsso-set-first-activation" method="post" action="">';
				wp_nonce_field( 'kwsso-set-first-activation' );
					echo ' <input type="hidden" name="kwsso_action" value="kwsso-set-first-activation"
				</form>
                <form  method="post" action="">';
		wp_nonce_field( 'kwsso-email-activation-form' );
		echo '<input type="hidden" name="kwsso_action" value="kwsso-email-activation-form"/>
                <div class="kwsso-feedback-progress-bar">
                    <div class="kwsso-feedback-progress"></div>
                </div>
                <div class="kwsso-feedback-form-step">';

		echo '</div>
                <div class="kwsso_note my-kw-4 text-xs">
                    <input type="checkbox" class="kw-input w-full my-kw-2" name="allow_usecase_support" checked value="checked">
                    Claim Free Support ! </br>
                    Our Support Person will reach out to you to Provide Assistance
                </div>

                <input type="email" class="kw-input w-full my-kw-2" name="activation_query_email" value="' . esc_attr( $user_email ) . '" placeholder="Enter your Email" required="">
                <textarea name="activation_usecase" class="kw-textarea"
                                style="resize: vertical;width:100%" cols="52" rows="5"
                                onkeyup="kwsso_check_if_query_valid(this)" onblur="kwsso_check_if_query_valid(this)" 
                                onkeypress="kwsso_check_if_query_valid(this)" 
                                placeholder="' . esc_attr( kwsso_lang_( 'Write your usecase here...' ) ) . '"></textarea>
                <div class="kwsso-feedback-footer flex mt-kw-6">
                    <button class="kw-main-button primary" type="submit" value="Deactivate and Submit">
                        Next
                        <svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"></path>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    jQuery("#closs_activation_modal").on("click", function() {
    jQuery("#kwsso-feedback-deactivation-modal").hide();
    document.getElementById(\'kwsso-set-first-activation\').submit()
});
    </script>';
	}
}

/**
 * Generates the IDP list dropdown HTML.
 *
 * This function outputs the HTML for a dropdown list of Identity Providers (IDPs).
 * It iterates through the list of IDPs and uses the show_all_idp_list function
 * to render each IDP in the dropdown.
 */
function get_idp_list_dropdown() {
	$idp_data = KwIDPData::$idp_data;
	$count    = 0;
	echo '
		<div class="kwsso-idp-search-dropdown-container" id="kwsso-idp-search-dropdown-container">
			<div class="kw-input kwsss-idp-search-dropdown-container-content mb-kw-4 idp-card-grid" id="formList">';
                    foreach ( $idp_data as $key => $idp ) {
                        if ( isset( $idp_data[ $key ] ) ) {
                            $count = show_all_idp_list( $idp_data[ $key ], $key, $count, $idp['image_svg'] );
                        }
                    }
	echo ' </div>
	</div>';
}
/**
 * Renders an IDP card in the dropdown list.
 *
 * This function outputs the HTML for a single IDP card within the dropdown list.
 * It constructs a URL for each IDP card and increments the count of rendered cards.
 *
 * @param array  $idp_key   The IDP key data.
 * @param string $idp_title  The name of the premium forms.
 * @param int    $count          The current count of IDP cards.
 * @param string $idp_img        The SVG image path for the IDP.
 *
 * @return int   The updated count of IDP cards.
 */
function show_all_idp_list( $idp, $idp_title, $count, $idp_img ) {
	$tab_details = KWSSO_PluginTabs::instance();
	$count++;
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- html from plugin.
	echo '<div class="idp-card kwsso_idp_seach_box " name="' . esc_attr( $idp['name'] ) . '" id="' . esc_attr( $idp['key'] ) . '"><a class="kwsso_search" data-value="' . esc_attr( $idp['key'] ) . '" ><div class="idp-title">' . esc_attr( $idp_title ) . '</div>' . $idp_img . '</a></div>';
	return $count;
}

function load_test_conf_page_css()
{
    echo '<style>
        .test-result-container {
            font-family: Calibri, sans-serif;
            padding: 40px 3%;
            max-width: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .user-email-display {
            font-size: 14px;
            color: #444;
            margin-bottom: 20px;
        }

        .attributes-received-heading {
            font-weight: bold;
            font-size: 14pt;
        }

        .nameid-warning-container {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: #fff5f5;
            border: 1px solid #f5c2c2;
            color: #d32f2f;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: "Inter", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin-top: 20px;
        }

        .warning-icon {
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background-color: #d32f2f;
            color: #ffffff;
            border-radius: 50%;
            font-size: 18px;
            font-weight: bold;
        }

        .warning-text strong {
            font-size: 16px;
        }

        .user-email-display {
            font-size: 20px;
            color: #444;
            margin-bottom: 20px;
        }

        .attributes-received-heading {
            font-weight: bold;
            font-size: 14pt;
        }

        .action-buttons-container {
            margin: 3%;
            display: block;
            text-align: center;
        }

        .action-button {
            border: none;
            outline: none;
            cursor: pointer;
            width: 250px;
            padding: 13px 22px;
            border-radius: 5px;
            font-size: 15px;
            color: #fff;
            background-color: #3498db;
            transition: background-color 0.3s ease;
        }

        .action-button:hover {
            background-color: #2980b9;
        }

        .action-button:active {
            background-color: #1f618d;
        }

        .kw-user-attribute-container {
            overflow-x: auto;
            max-width: 100%;
            margin-top: 20px;
        }

        .kw-user-attribute-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        .kw-user-attribute-header {
            background-color: #6cb2eb;
            font-weight: bold;
            color: #fff;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .kw-user-attribute-cell {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .test-success-container {
            display: inline-flex;
            align-items: center;
            background-color: #6cb2eb;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            color: #ffffff;
            min-height: 60px;
            width: 100%;
        }

        .test-success-icon {
            fill: #ffffff;
            margin-right: 10px;
        }

        .test-success-icon path {
            fill: #6cb2eb;
        }

        .test-success-icon polyline {
            fill: none;
            stroke: #ffffff;
            stroke-miterlimit: 10;
            stroke-width: 4;
        }

        .test-success-text {
            font-size: 18px;
        }

        .test-failure-container {
            display: inline-flex;
            align-items: center;
            background-color: #ff8383;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            color: #ffffff;
            min-height: 60px;
            width: 100%;
        }

        .test-failure-icon {
            fill: #ffffff;
            margin-right: 10px;
        }

        .test-failure-icon path:first-child {
            fill: #f78f8f;
        }

        .test-failure-icon path:nth-child(2) {
            fill: #c74343;
        }

        .test-failure-icon path:nth-child(3),
        .test-failure-icon path:nth-child(4) {
            fill: #ffffff;
        }

        .test-failure-text {
            font-size: 18px;
        }



            .div-body {
      font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #333;
    }

    /* Container Styles */
    .container {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      width: 90%;
      text-align: center;
      overflow: hidden;
      padding: 20px;
    }

    /* Header Styles */
    .container h1 {
      font-size: 22px;
      margin-bottom: 15px;
      color: #2196f3;
    }

    .email {
      font-size: 16px;
      color: #555;
      margin-bottom: 20px;
    }

    .status {
      font-size: 16px;
      color: #4caf50;
      margin-bottom: 20px;
      font-weight: bold;
    }

    /* Horizontal Line Container */
    .horizontal-line-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .horizontal-line {
      border-top: 1px solid #ddd;
      width: 35%;
    }

    .or-text {
      font-size: 14px;
    font-weight: 700;
      color: #555;
    }

    /* Modern Table Styles */
    .container table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      border-radius: 8px;
      overflow: hidden;
    }

    .container table thead {
      background: #2196f3;
      color: #fff;
    }

    .container table th,
    .container table td {
      padding: 12px;
      text-align: left;
      font-size: 14px;
    }

    .container table th {
      font-weight: 600;
    }

    .container table tbody tr {
      background: #f9f9f9;
      transition: background 0.3s ease;
    }

    .container table tbody tr:nth-child(even) {
      background: #f3f3f3;
    }

    .container table tbody tr:hover {
      background: #e3f2fd;
    }

    .container table tbody td {
      border-bottom: 1px solid #ddd;
    }

    .container table tbody td:last-child {
      color: #555;
    }

    .container footer {
      margin-top: 15px;
      font-size: 12px;
      color: #777;
    }

    /* Responsive Design */
    @media (max-width: 400px) {
      .container {
        padding: 15px;
      }

      .container h1 {
        font-size: 18px;
      }

      .container table th,
      .container table td {
        padding: 8px;
      }

      .horizontal-line-container {
        flex-direction: column;
      }

      .horizontal-line {
        width: 100%;
        margin: 10px 0;
      }
    }
        </style>';
}




