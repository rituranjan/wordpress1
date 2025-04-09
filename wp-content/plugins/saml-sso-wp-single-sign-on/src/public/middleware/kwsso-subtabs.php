<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$active_tab     = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the tab name, doesn't require nonce verification.
if ( isset( $sub_tab_details->sub_tab_details[ $active_tab ] ) ) {
	$sub_tab_list = $sub_tab_details->sub_tab_details[ $active_tab ];
	echo '	<div id="subtab" class="kw-subtabs-container">';

	foreach ( $sub_tab_list as $sub_tabs ) {
		if ( $sub_tabs->navbar_display ) {
			echo '
                    <div class="kw-subtab-item">
                        <span class="kw-subtab-title" 
                            id="' . esc_attr( $sub_tabs->id ) . '">
                            ' . esc_html( $sub_tabs->kwsso_tab_name ) . '
                        </span>
                    </div>';
		}
	}
	echo '</div>';
}
