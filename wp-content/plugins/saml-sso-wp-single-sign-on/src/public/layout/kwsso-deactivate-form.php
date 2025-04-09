<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kwsso_feedback_deactivation_modal() {
	$current_user = wp_get_current_user();
	$user_email   = $current_user->user_email;

	echo '<div id="kwsso-feedback-deactivation-modal" style="display:none" class="kwsso-feedback-modal-overlay">
            <div class="kwsso-feedback-modal">
                <button id="kwsso-feedback-close-modal" class="kwsso-feedback-close">&times;</button> <!-- Close button -->
                <h2>We’re Sorry to See You Go 😔</h2>
                <p>Your feedback helps us improve. Could you take a moment to tell us why you’re deactivating?</p>
                <form id="kwsso-feedback-deactivation-form" method="post" action="">';

	wp_nonce_field( 'kwsso-feedback-deactivation-form' );

	echo '<input type="hidden" name="kwsso_action" value="kwsso-feedback-deactivation-form-action"/>
                <div class="kwsso-feedback-progress-bar">
                    <div class="kwsso-feedback-progress"></div>
                </div>
                <div class="kwsso-feedback-form-step">';
	echo '</div>
                <div class="kwsso_note my-kw-2 text-xs">
                    Our Support Engineer will reach out to you to provide assistance.
                </div>
                <input type="email" class="kw-input w-full my-kw-2" name="feedback_query_email" value="' . esc_attr( $user_email ) . '" placeholder="Enter your Email" required="">
                <textarea class="kw-textarea h-[80px]" name="deactivation_details" placeholder="Please share more details..."></textarea>
                <div class="kwsso-feedback-footer flex mt-kw-6">
                    <button class="kw-main-button primary" type="submit" value="Deactivate and Submit">
                        Deactivate and Submit
                        <svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"></path>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"></path>
                        </svg>
                    </button>
                    <div class="kwsso-feedback-skip-div">
                        <a id="kwsso-feedback-skip-feedback" >Skip</a>
                    </div>
                </div>
            </form>
        </div>
    </div>';
}

add_action( 'admin_footer', 'kwsso_feedback_deactivation_modal' );
