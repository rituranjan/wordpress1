<?php

function fca_ept_admin_review_notice() {
	
	$action = empty( $_GET['fca_ept_review_notice'] ) ? false : sanitize_text_field( wp_unslash( $_GET['fca_ept_review_notice'] ) );
	
	if( $action ) {
		
		$nonce = empty( $_GET['fca_ept_nonce'] ) ? false : sanitize_text_field( wp_unslash( $_GET['fca_ept_nonce'] ) );
		$nonceVerified = wp_verify_nonce( $nonce, 'fca_ept_leave_review' );
		if( $nonceVerified == false ) {
			wp_die( "Unauthorized. Please try logging in again." );
		}
		
		update_option( 'dh_ptp_show_review_notice', false );
		if( $action == 'review' ) {
			echo "<script>document.location='https://wordpress.org/support/plugin/easy-pricing-tables/reviews/'</script>";
		}
				
		if( $action == 'later' ) {
			//MAYBE MAKE SURE ITS NOT ALREADY SET
			if( wp_next_scheduled( 'fca_ept_schedule_review_notice' ) == false ) {
				wp_schedule_single_event( time() + 30 * DAY_IN_SECONDS, 'fca_ept_schedule_review_notice' );
			}
		}
		
		if( $action == 'dismiss' ) {
			//DO NOTHING
		}		
	}	
	
	$show_review_option = get_option( 'dh_ptp_show_review_notice', null );
	if ( $show_review_option === null ) {
	
		//MAYBE MAKE SURE ITS NOT ALREADY SET
		if( wp_next_scheduled( 'fca_ept_schedule_review_notice' ) == false ) {
			wp_schedule_single_event( time() + 30 * DAY_IN_SECONDS, 'fca_ept_schedule_review_notice' );
		}
		add_option( 'dh_ptp_show_review_notice', false );
	}
	
	if( $show_review_option ) {

		$nonce = wp_create_nonce( 'fca_ept_leave_review' );
		$review_url = add_query_arg( array( 'fca_ept_review_notice' => 'review', 'fca_ept_nonce' => $nonce ) );
		$postpone_url = add_query_arg( array( 'fca_ept_review_notice' => 'later', 'fca_ept_nonce' => $nonce ) );
		$forever_dismiss_url = add_query_arg( array( 'fca_ept_review_notice' => 'dismiss', 'fca_ept_nonce' => $nonce ) );

		echo '<div id="fca-ept-review-notice" class="notice notice-success is-dismissible" style="padding-bottom: 8px; padding-top: 8px;">';
		
			echo '<p>' . esc_html__( "Hi! You've been using Easy Pricing Tables Free for a while now, so who better to ask for a review than you? Would you please mind leaving us one? It really helps us a lot!", 'easy-pricing-tables' ) . '</p>';
			
			echo "<a href='" . esc_url( $review_url ) . "' class='button button-primary' style='margin-top: 2px;'>" . esc_html__( 'Leave review', 'easy-pricing-tables' ) . "</a> ";
			echo "<a style='position: relative; top: 10px; left: 7px;' href='" . esc_url( $postpone_url ) . "' >" . esc_html__( 'Maybe later', 'easy-pricing-tables' ) . "</a> ";
			echo "<a style='position: relative; top: 10px; left: 16px;' href='" . esc_url( $forever_dismiss_url ) . "' >" . esc_html__( 'No thank you', 'easy-pricing-tables' ) . "</a> ";
			echo '<br style="clear:both">';
			
		echo '</div>';
	
	}

}
add_action( 'admin_notices', 'fca_ept_admin_review_notice' );

function fca_ept_enable_review_notice(){
	update_option( 'dh_ptp_show_review_notice', true );
	wp_clear_scheduled_hook( 'fca_ept_schedule_review_notice' );
}
add_action ( 'fca_ept_schedule_review_notice', 'fca_ept_enable_review_notice' );

//DEACTIVATION SURVEY
function fca_ept_admin_deactivation_survey( $hook ) {
	if ( $hook === 'plugins.php' ) {
		
		ob_start(); ?>		
			<div id="fca-deactivate" style="position: fixed; left: 232px; top: 191px; border: 1px solid #979797; background-color: white; z-index: 9999; padding: 12px; max-width: 669px;">
				<h3 style="font-size: 14px; border-bottom: 1px solid #979797; padding-bottom: 8px; margin-top: 0;"><?php esc_html_e( 'Sorry to see you go', 'easy-pricing-tables' ) ?></h3>
				<p><?php esc_html_e( 'Hi, this is David, the creator of Easy Pricing Tables. Thanks so much for giving my plugin a try. I’m sorry that you didn’t love it.', 'easy-pricing-tables' ) ?>
				</p>
				<p><?php esc_html_e( 'I have a quick question that I hope you’ll answer to help us make Easy Pricing Tables better: what made you deactivate?', 'easy-pricing-tables' ) ?>
				</p>
				<p><?php esc_html_e( 'You can leave me a message below. I’d really appreciate it.', 'easy-pricing-tables' ) ?>
				</p>
				
				<p><textarea style='width: 100%;' id='fca-ept-deactivate-textarea' placeholder='<?php esc_attr_e( 'What made you deactivate?', 'easy-pricing-tables' ) ?>'></textarea></p>
				
				<div style='float: right;' id='fca-deactivate-nav'>
					<button style='margin-right: 5px;' type='button' class='button button-secondary' id='fca-ept-deactivate-skip'><?php esc_html_e( 'Skip', 'easy-pricing-tables' ) ?></button>
					<button type='button' class='button button-primary' id='fca-ept-deactivate-send'><?php esc_html_e( 'Send Feedback', 'easy-pricing-tables' ) ?></button>
				</div>			
			</div>		
		<?php
			
		$html = ob_get_clean();
		
		$data = array(
			'html' => $html,
			'nonce' => wp_create_nonce( 'fca_ept_uninstall_nonce' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
		
		wp_enqueue_script( 'fca_ept_deactivation_js', PTP_PLUGIN_URL . '/includes/notices/deactivation.js', false, PTP_PLUGIN_VER, true );
		wp_localize_script( 'fca_ept_deactivation_js', "fca_ptp", $data );
	}
	
	
}	
add_action( 'admin_enqueue_scripts', 'fca_ept_admin_deactivation_survey' );

//UNINSTALL ENDPOINT
function fca_ptp_uninstall_ajax() {
	
	$msg = empty( $_REQUEST['msg'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['msg'] ) );
	$nonce = empty( $_REQUEST['nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
	$nonceVerified = wp_verify_nonce( $nonce, 'fca_ept_uninstall_nonce') == 1;

	if ( $nonceVerified && !empty( $msg ) ) {
		
		$url =  "https://api.fatcatapps.com/api/feedback.php";
				
		$body = array(
			'product' => 'pricingtables',
			'msg' => $msg,		
		);
		
		$args = array(
			'timeout'     => 15,
			'redirection' => 15,
			'body' => wp_json_encode( $body ),	
			'blocking'    => true,
			'sslverify'   => false
		); 		
		
		$return = wp_remote_post( $url, $args );
		
		wp_send_json_success( $msg );

	}
	wp_send_json_error( $msg );

}

add_action( 'wp_ajax_fca_ptp_uninstall', 'fca_ptp_uninstall_ajax' );

function dh_ptp_upgrade_to_premium_menu()
{
    $page_hook = add_submenu_page('edit.php?post_type=easy-pricing-table', __('Upgrade to Premium', 'easy-pricing-tables'), __('Upgrade to Premium', 'easy-pricing-tables'), 'manage_options', 'easy-pricing-tables-upgrade', 'dh_ptp_upgrade_to_premium');
    add_action('load-' . $page_hook , 'dh_ptp_upgrade_ob_start');
}
add_action('admin_menu', 'dh_ptp_upgrade_to_premium_menu');

function dh_ptp_upgrade_ob_start() {
    ob_start();
}

function dh_ptp_upgrade_to_premium()
{
    wp_redirect('https://fatcatapps.com/easypricingtables/?utm_campaign=wp%2Bsubmenu&utm_source=Easy%2BPricing%2BTables%2BFree&utm_medium=plugin&utm_content=v1', 301);
    exit();
}

function dh_ptp_upgrade_to_premium_menu_js()
{
    ?>
    <script type="text/javascript">
    	jQuery(document).ready(function ($) {
            $('a[href="edit.php?post_type=easy-pricing-table&page=easy-pricing-tables-upgrade"]').on( 'click', function () {
        		$(this).attr('target', '_blank')
            })
        })
    </script>
    <style>
        a[href="edit.php?post_type=easy-pricing-table&page=easy-pricing-tables-upgrade"] {
            color: #6bbc5b !important;
        }
        a[href="edit.php?post_type=easy-pricing-table&page=easy-pricing-tables-upgrade"]:hover {
            color: #7ad368 !important;
        }
    </style>
    <?php 
}
add_action( 'admin_footer', 'dh_ptp_upgrade_to_premium_menu_js');
