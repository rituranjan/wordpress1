<?php
/**
 * Load admin view for Contact Us pop up.
 *
 * @package keywoot-saml-sso/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
echo '
<div class="w-[296px] kwsso-contact-us-container duration-300"> 
    <div id="kwsso_contact_us" class="flex gap-kw-4 relative justify-end">
        <input id="contact-us-toggle" type="checkbox" class="peer sr-only"/>
  <span onClick="contactUsOnClick(\'\')" class="kwsso_contact_us_box">
    <span class="kw-head-text text-white leading-normal" style="font-size:14px;">Need Help? <br>Contact Us</span>
    <span onClick="contactUsOnClick(\'\')">
    <svg width="50" height="50" viewBox="0 0 102 103" fill="none" class="cursor-pointer">
      <g id="d4c51d1a6d24c668e01e2eb6a39325d7">
        <rect width="102" height="103" rx="51" fill="url(#b69bc691e4b17a460c917ded85c3988c)"></rect>
        <g id="0df790d6c3b93208dd73e487cf02eedc">
          <path id="e161bdf1e94ee39e424acc659f19e97c" fill-rule="evenodd" clip-rule="evenodd" d="M32 51.2336C32 37.5574 36.7619 33 51.0476 33C65.3333 33 70.0952 37.5574 70.0952 51.2336C70.0952 64.9078 65.3333 69.4672 51.0476 69.4672C36.7619 69.4672 32 64.9078 32 51.2336Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
          <path id="c79e8f13aac8a6b146b9542a01c31ddc" d="M69.0957 44.2959C69.0957 44.2959 56.6508 55.7959 51.5957 55.7959C46.5406 55.7959 34.0957 44.2959 34.0957 44.2959" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
        </g>
      </g>
    </svg>
</span>
</span>
        
        <div class="kwsso_contactus_popup_container" style="display:none;">
        <div id="kw-contact-form" class="kwsso_contactus_container hidden animate-fade-in-up">
            <div class="kw-header bg-gray-50">
                <h5 class="kw-head-text flex-1">Need Help? Drop Your Query</h5>
                  <div onclick="kwsso_contactus_goback()">
                    <label class="kw-icon-button">
                      <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
                        <g id="a8e87dce2cfc3c0d3b0cee61b2290011">
                          <path id="4988f6043ba0a8c6d0d29ca41557a1d8" d="M8.99033 1.00293L1.00366 8.9896" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path id="7c0fb53a248addedc5d06bb436da0b4d" d="M9 9L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                      </svg>
                    </label>
                  </div>
            </div>
            <form name="f" method="post" action="" class="flex flex-col gap-kw-3 px-kw-6 py-kw-4">';
			wp_nonce_field( 'kwsso_contact_us_query' );
			echo '
                <p class="kwsso_note "> Simply send us your query, and we\'ll reach out to you at our earliest convenience. </br> </br> <b> NOTE: </b>Please make sure the entered email is correct so that our team can contact you back</p> 

                <input type="hidden" name="kwsso_action" value="kwsso_contact_us_query">
                   
                        <input type="email" class="kw-input" id="query_email" name="query_email" value="' . esc_attr( $user_email ) . '"
                            placeholder="' . esc_attr( kwsso_lang_( 'Enter your Email' ) ) . '" required />
                            <textarea id="contactQuery" name="query" class="kw-textarea h-[160px]"
                                style="resize: vertical;width:100%" cols="52" rows="10"
                                onkeyup="kwsso_check_if_query_valid(this)" onblur="kwsso_check_if_query_valid(this)" 
                                onkeypress="kwsso_check_if_query_valid(this)" 
                                placeholder="' . esc_attr( kwsso_lang_( 'Write your query here...' ) ) . '"></textarea>
              
                                <input type="submit" name="send_query" id="send_query" value="' . esc_attr( kwsso_lang_( 'Submit Query' ) ) . '" 
                                class="kw-main-button primary " />
                  ------------------------    OR   -------------------------
                <a href="mailto:support@keywoot.com" class="kw-main-button secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="-ml-kw-4">
                      <g id="2500ca1d51c4344f9af74fabf3c0a9a0">
                        <path id="31503c81468ad79fcb98b748e7e5efa0" fill-rule="evenodd" clip-rule="evenodd" d="M3.99132 6.93334C4.26951 6.30829 4.6229 5.80071 5.0541 5.38795C6.31992 4.17628 8.44047 3.60205 11.976 3.60205C15.5114 3.60205 17.632 4.17628 18.8978 5.38795C19.4016 5.87022 19.7992 6.48196 20.0948 7.25926C19.9264 7.3907 19.7143 7.55478 19.4676 7.74256C18.8124 8.24133 17.9165 8.9049 16.95 9.56716C15.9808 10.2313 14.9544 10.8846 14.036 11.3689C13.5767 11.6111 13.157 11.8043 12.7938 11.9354C12.4205 12.0701 12.1542 12.1217 11.987 12.1217C11.8223 12.1217 11.5662 12.0712 11.2114 11.9384C10.8655 11.809 10.4685 11.6177 10.0353 11.3769C9.16899 10.8951 8.21009 10.2444 7.30741 9.58136C6.40761 8.92048 5.57801 8.25809 4.97248 7.76007C4.67006 7.51135 4.42435 7.3043 4.25468 7.15981C4.16987 7.08758 4.1041 7.03103 4.05981 6.99277L4.00979 6.94943L3.99744 6.93868L3.99453 6.93613L3.9939 6.93559L3.99383 6.93552L3.99382 6.93552L3.99379 6.93549C3.99297 6.93477 3.99214 6.93406 3.99132 6.93334ZM3.50334 8.48906C3.30109 9.44768 3.20215 10.595 3.20215 11.9688C3.20215 15.3397 3.79796 17.3469 5.05412 18.5494C6.31996 19.7612 8.44053 20.3356 11.976 20.3356C15.5114 20.3356 17.632 19.7612 18.8978 18.5494C20.154 17.3469 20.7498 15.3397 20.7498 11.9688C20.7498 10.7543 20.6724 9.71687 20.5148 8.8303C20.4699 8.86467 20.4236 8.89995 20.3762 8.93609C19.7083 9.44451 18.7912 10.1239 17.7979 10.8045C16.8074 11.4833 15.7272 12.1729 14.7356 12.6958C14.2399 12.9572 13.7537 13.1837 13.303 13.3463C12.8624 13.5053 12.4068 13.6217 11.987 13.6217C11.5651 13.6217 11.1159 13.5043 10.6857 13.3433C10.2466 13.179 9.7789 12.9506 9.30636 12.6878C8.36136 12.1624 7.34518 11.4702 6.41947 10.7903C5.49086 10.1083 4.63875 9.42774 4.01966 8.91858C3.82261 8.75651 3.64883 8.61153 3.50334 8.48906ZM21.75 7.49863C22.1045 8.76599 22.2498 10.2556 22.2498 11.9688C22.2498 15.4351 21.6551 17.9863 19.9351 19.6329C18.2247 21.2702 15.5834 21.8356 11.976 21.8356C8.36853 21.8356 5.72719 21.2702 4.01684 19.6329C2.29681 17.9863 1.70215 15.4351 1.70215 11.9688C1.70215 8.50214 2.2968 5.95086 4.01687 4.30437C5.72724 2.66717 8.36859 2.10205 11.976 2.10205C15.5833 2.10205 18.2247 2.66717 19.935 4.30437C20.7104 5.04653 21.257 5.97252 21.6237 7.08332C21.7084 7.20986 21.7499 7.35463 21.75 7.49863Z" fill="black"></path>
                      </g>
                    </svg>
                    <span>Email us at support@keywoot.com <span>
                </a>
            </form> 
          </div>    
        </div>
    </div>
</div>    
';
