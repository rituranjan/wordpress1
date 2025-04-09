<?php

/*********************/
/* EPT FOR GUTENBERG */
/*********************/
function fca_ept_render( $attributes ) {
	
	$selectedLayout = empty( $attributes['selectedLayout'] ) ? '' : $attributes['selectedLayout'];
	if( $selectedLayout ) {
		include_once( PTP_PLUGIN_PATH . "assets/blocks/$selectedLayout/fca-ept-$selectedLayout-block.php" );
		$renderLayout = 'fca_ept_render_' . $selectedLayout;
		
		if ( function_exists( $renderLayout ) ) {
			
			if( DH_PTP_LICENSE_PACKAGE !== 'Free' ) {
				wp_enqueue_style( 'fca-ept-font-awesome' );
				
				$fontFamily = empty( $attributes['fontFamily'] ) ? false : trim( $attributes['fontFamily'] );
				
				if( $fontFamily && $fontFamily !== 'sans-serif' ) {
					$font_url = add_query_arg( array(
						"family" => $fontFamily,
						"subset" => "latin" 
					), 'https://fonts.googleapis.com/css' );
					$fontFamily = sanitize_text_field( $fontFamily );
					wp_enqueue_style( "fca-ept-google-font-$fontFamily", $font_url );
				
				}
			}
			
			return call_user_func( $renderLayout, $attributes );
		}
		
	}
	
	return;
}

function fca_ept_gutenberg_post_filter( $bool, $post ) {
	
	if( !empty( $post ) ) {
		$post_type = get_post_type( $post );
		if( $post_type === 'wp_block' ) {			
			$meta = get_post_meta( $post->ID, '1_dh_ptp_settings', true  );
			if( isSet( $meta['ept3'] ) ) {
				return true;
			}
		}
	}
	return $bool;
}
add_filter( 'use_block_editor_for_post', 'fca_ept_gutenberg_post_filter', 9999, 2 );

function fca_ept_register_block() {

	// MAIN
	wp_register_script( 'fca_ept_editor_common_script', PTP_PLUGIN_URL . '/assets/blocks/editor/fca-ept-editor-common.min.js', array( 'jquery', 'wp-blocks', 'wp-element' ), PTP_PLUGIN_VER, true );
	wp_register_script( 'fca_ept_sidebar_script', PTP_PLUGIN_URL . '/assets/blocks/editor/fca-ept-sidebar.min.js', array( 'fca_ept_editor_common_script' ), PTP_PLUGIN_VER, true );
	wp_register_script( 'fca_ept_toolbar_script', PTP_PLUGIN_URL . '/assets/blocks/editor/fca-ept-toolbar.min.js', array( 'fca_ept_editor_common_script' ), PTP_PLUGIN_VER, true );
	wp_register_style( 'fca-ept-editor-style', PTP_PLUGIN_URL . '/assets/blocks/editor/fca-ept-editor.min.css', array(), PTP_PLUGIN_VER );
	wp_register_script( 'fca_ept_editor_script', PTP_PLUGIN_URL . '/assets/blocks/editor/fca-ept-editor.min.js', array( 'fca_ept_sidebar_script', 'fca_ept_toolbar_script' ), PTP_PLUGIN_VER, true );
	
	wp_register_script( 'fca_ept_layout1_script', PTP_PLUGIN_URL . '/assets/blocks/layout1/fca-ept-layout1.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
	wp_register_style( 'fca-ept-layout1-style', PTP_PLUGIN_URL . '/assets/blocks/layout1/fca-ept-layout1.min.css', array(), PTP_PLUGIN_VER );

	wp_register_script( 'fca_ept_layout2_script', PTP_PLUGIN_URL . '/assets/blocks/layout2/fca-ept-layout2.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
	wp_register_style( 'fca-ept-layout2-style', PTP_PLUGIN_URL . '/assets/blocks/layout2/fca-ept-layout2.min.css', array(), PTP_PLUGIN_VER );
	
	if ( DH_PTP_LICENSE_PACKAGE !== 'Free' ) {
		
		wp_register_script( 'fca_ept_layout3_script', PTP_PLUGIN_URL . '/assets/blocks/layout3/fca-ept-layout3.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept-layout3-style', PTP_PLUGIN_URL . '/assets/blocks/layout3/fca-ept-layout3.min.css', array(), PTP_PLUGIN_VER );

		wp_register_script( 'fca_ept_layout4_script', PTP_PLUGIN_URL . '/assets/blocks/layout4/fca-ept-layout4.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept-layout4-style', PTP_PLUGIN_URL . '/assets/blocks/layout4/fca-ept-layout4.min.css', array(), PTP_PLUGIN_VER );

		wp_register_script( 'fca_ept_layout5_script', PTP_PLUGIN_URL . '/assets/blocks/layout5/fca-ept-layout5.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept-layout5-style', PTP_PLUGIN_URL . '/assets/blocks/layout5/fca-ept-layout5.min.css', array(), PTP_PLUGIN_VER );

		wp_register_script( 'fca_ept_layout6_script', PTP_PLUGIN_URL . '/assets/blocks/layout6/fca-ept-layout6.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept-layout6-style', PTP_PLUGIN_URL . '/assets/blocks/layout6/fca-ept-layout6.min.css', array(), PTP_PLUGIN_VER );

		wp_register_script( 'fca_ept_layout7_script', PTP_PLUGIN_URL . '/assets/blocks/layout7/fca-ept-layout7.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept-layout7-style', PTP_PLUGIN_URL . '/assets/blocks/layout7/fca-ept-layout7.min.css', array(), PTP_PLUGIN_VER );

		wp_register_script( 'fca_ept_layout8_script', PTP_PLUGIN_URL . '/assets/blocks/layout8/fca-ept-layout8.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept-layout8-style', PTP_PLUGIN_URL . '/assets/blocks/layout8/fca-ept-layout8.min.css', array(), PTP_PLUGIN_VER );
		
		wp_register_script( 'fca_ept_layout9_script', PTP_PLUGIN_URL . '/assets/blocks/layout9/fca-ept-layout9.min.js', array( 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept-layout9-style', PTP_PLUGIN_URL . '/assets/blocks/layout9/fca-ept-layout9.min.css', array(), PTP_PLUGIN_VER );
		
		// FONTS
		$fonts = array(
			"Roboto:400",
			"Open Sans:400",
			"Lato:400",
			"Oswald:400",
			"Source Sans Pro:400",
			"Montserrat:400",
			"Merriweather:400",
			"Raleway:400",
			"PT Sans:400",
			"Lora:400",
			"Noto Sans:400",
			"Nunito Sans:400",
			"Concert One:400",
			"Prompt:400",
			"Work Sans:400",
		);

		$fonts_collection_url = add_query_arg( array(
			"family" => urlencode( implode( "|", $fonts ) ),
			"subset" => "latin" 
		), 'https://fonts.googleapis.com/css' );
		
		wp_register_style( 'fca-ept-google-fonts', $fonts_collection_url );
		

		// FONTAWESOME
		wp_register_style( 'fca-ept-font-awesome', PTP_PLUGIN_URL . '/assets/pricing-tables/font-awesome/css/font-awesome.min.css', array(), PTP_PLUGIN_VER );
	}
	
	if( !in_array( DH_PTP_LICENSE_PACKAGE, array( 'Free', 'Personal' ) ) ) {
		wp_register_script(	'fca_ept_premium_script', PTP_PLUGIN_URL . '/assets/blocks/editor/fca-ept-premium.min.js', array( 'jquery', 'fca_ept_editor_script' ), PTP_PLUGIN_VER, true );
		wp_register_script(	'fca_ept_toggle', PTP_PLUGIN_URL . '/assets/blocks/toggle/fca-ept-toggle.min.js', array( 'jquery' ), PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept-toggle-style', PTP_PLUGIN_URL . '/assets/blocks/toggle/fca-ept-toggle.min.css', array(), PTP_PLUGIN_VER );
	}
	
	if ( function_exists( 'register_block_type' ) ) {
		register_block_type( 'fatcatapps/easy-pricing-tables', array( 'render_callback' => 'fca_ept_render' ) );
	}

}
add_action( 'init', 'fca_ept_register_block' );

//LISTEN FOR OUR URL PARAMS FOR CUSTOM ACTIONS
function fca_ept_add_block_listener(){
		
	if ( isSet( $_GET['fca_ept_new_block'] ) ){
		$nonce = empty( $_GET['ept_nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_GET['ept_nonce'] ) );
		
		if( wp_verify_nonce( $nonce, 'ept_new' ) == false ){
			wp_die( 'Authorization failed, please try logging in again' );
		}
		$args = array(
			'post_type'      => 'wp_block',
			'meta_key' 		 => '1_dh_ptp_settings',
			'posts_per_page' => '-1'
		);

		$count = count( get_posts ( $args ) ) + 1;
		
		$args = array(
			'post_title'     => 'Pricing Table ' . $count,
			'post_type'      => 'wp_block',
			'post_author'    => get_current_user_id(),
			'post_status'    => 'publish',
			'post_content'   => fca_ept_default_block_content(),
			'meta_input' 	 => array( '1_dh_ptp_settings' => [ 'ept3' => '' ] )
		);

		$post_ID = wp_insert_post( $args );
		wp_redirect( admin_url( "post.php?post={$post_ID}&action=edit" ) );
		exit;
	}		
	
	if ( isSet( $_GET['fca_ept_new_toggle'] ) ){
		$nonce = empty( $_GET['ept_nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_GET['ept_nonce'] ) );
		
		if( wp_verify_nonce( $nonce, 'ept_new' ) == false ){
			wp_die( 'Authorization failed, please try logging in again' );
		}
		$args = array(
			'post_type'      => 'wp_block',
			'meta_key' 		 => '1_dh_ptp_settings',
			'posts_per_page' => '-1'
		);

		$count = count( get_posts ( $args ) ) + 1;
		
		$args = array(
			'post_title'     => 'Pricing Table ' . $count,
			'post_type'      => 'wp_block',
			'post_author'    => get_current_user_id(),
			'post_status'    => 'publish',
			'post_content'   => fca_ept_default_toggle_content(),
			'meta_input' 	 => array( '1_dh_ptp_settings' => [ 'ept3' => '' ] )
		);

		$post_ID = wp_insert_post( $args );
		wp_redirect( admin_url( "post.php?post={$post_ID}&action=edit" ) );
		exit;
	}
	
	if ( isSet( $_GET['fca_ept_new_page'] ) ){
		$nonce = empty( $_GET['ept_nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_GET['ept_nonce'] ) );
		
		if( wp_verify_nonce( $nonce, 'ept_new' ) == false ){
			wp_die( 'Authorization failed, please try logging in again' );
		}
		$args = array(
			'post_type'      => 'page',
			'meta_key' 		 => '1_dh_ptp_settings',
			'posts_per_page' => '-1'
		);

		$count = count( get_posts ( $args ) ) + 1;
		
		$args = array(
			'post_title'     => 'Pricing',
			'post_type'      => 'page',
			'post_author'    => get_current_user_id(),
			'post_status'    => 'publish',
			'post_content'   => fca_ept_default_block_content(),
		);

		$post_ID = wp_insert_post( $args );
		wp_redirect( admin_url( "post.php?post={$post_ID}&action=edit" ) );
		exit;
	}
	
	//USED FOR LEGACY TABLE CLONING
	if ( isSet( $_GET['fca_ept_clone_table'] ) ){
		$postID = empty( $_GET['fca_ept_clone_table'] ) ? '' : intval( $_GET['fca_ept_clone_table'] );
		$nonce = empty( $_GET['ept_nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_GET['ept_nonce'] ) );
		
		if( wp_verify_nonce( $nonce, 'ept_clone' ) && $postID ){
			fca_ept_clone_table( $postID );
		} else {
			wp_die( 'Authorization failed, please try logging in again' );
		}
	}

}
add_action( 'init', 'fca_ept_add_block_listener' );

function fca_ept_default_block_content() {
	return '<!-- wp:easy-pricing-tables/table  -->
	<div style="padding-top:64px;padding-right:16px;padding-bottom:64px;padding-left:16px;gap:9px" class="wp-block-easy-pricing-tables-table ept4-table- layout-0 matchRowHeight"><style></style><style>div.ept4-table- p, div.ept4-table- a, div.ept4-table- li { font-family: sans-serif !important};</style></div>
<!-- /wp:easy-pricing-tables/table -->';
}

function fca_ept_default_toggle_content() {
	return '<!-- wp:easy-pricing-tables/toggle-table -->
<div class="wp-block-easy-pricing-tables-toggle-table"><!-- wp:easy-pricing-tables/toggle -->
<div class="wp-block-easy-pricing-tables-toggle fca-ept-toggle-period-container" style="padding:10px;font-size:18px"><span style="color:#000000">Monthly</span><label class="fca-ept-switch"><input type="checkbox" class="fca-ept-period-toggle"/><span style="background-color:#8ED1FC" class="fca-ept-slider fca-ept-round"></span></label><span style="color:#000000">Yearly</span></div>
<!-- /wp:easy-pricing-tables/toggle -->

<!-- wp:easy-pricing-tables/table {"tableID":"d5c4b268b22f5"} -->
<div style="padding-top:64px;padding-right:16px;padding-bottom:64px;padding-left:16px;gap:9px" class="wp-block-easy-pricing-tables-table ept4-table-d5c4b268b22f5 layout-0 matchRowHeight"><style></style><style>div.ept4-table-d5c4b268b22f5 p, div.ept4-table-d5c4b268b22f5 a, div.ept4-table-d5c4b268b22f5 li { font-family: sans-serif !important};</style></div>
<!-- /wp:easy-pricing-tables/table -->

<!-- wp:easy-pricing-tables/table {"templateID":-1,"tableID":"d0a5395dc7c45"} -->
<div style="padding-top:64px;padding-right:16px;padding-bottom:64px;padding-left:16px;gap:9px" class="wp-block-easy-pricing-tables-table ept4-table-d0a5395dc7c45 layout--1 matchRowHeight"><style></style><style>div.ept4-table-d0a5395dc7c45 p, div.ept4-table-d0a5395dc7c45 a, div.ept4-table-d0a5395dc7c45 li { font-family: sans-serif !important};</style></div>
<!-- /wp:easy-pricing-tables/table --></div>
<!-- /wp:easy-pricing-tables/toggle-table -->';
}

function fca_ept_block_enqueue() {
	// enqueue editor styles
	wp_enqueue_style( 'fca-ept-editor-style' );
	wp_enqueue_style( 'fca-ept-layout1-style' );
	wp_enqueue_style( 'fca-ept-layout2-style' );
	
	if ( DH_PTP_LICENSE_PACKAGE !== 'Free' ) {
		
		wp_enqueue_style( 'fca-ept-font-awesome' );
		wp_enqueue_style( 'fca-ept-google-fonts' );
		
		wp_enqueue_style( 'fca-ept-layout3-style' );
		wp_enqueue_style( 'fca-ept-layout4-style' );
		wp_enqueue_style( 'fca-ept-layout5-style' );
		wp_enqueue_style( 'fca-ept-layout6-style' );
		wp_enqueue_style( 'fca-ept-layout7-style' );
		wp_enqueue_style( 'fca-ept-layout8-style' );
		wp_enqueue_style( 'fca-ept-layout9-style' );
		wp_enqueue_script( 'fca_ept_layout3_script' );
		wp_enqueue_script( 'fca_ept_layout4_script' );
		wp_enqueue_script( 'fca_ept_layout5_script' );
		wp_enqueue_script( 'fca_ept_layout6_script' );
		wp_enqueue_script( 'fca_ept_layout7_script' );
		wp_enqueue_script( 'fca_ept_layout8_script' );
		wp_enqueue_script( 'fca_ept_layout9_script' );
	}
	
	// enqueue layout scripts for editor
	wp_enqueue_script( 'fca_ept_layout1_script' );
	wp_enqueue_script( 'fca_ept_layout2_script' );

	if( !in_array( DH_PTP_LICENSE_PACKAGE, array( 'Free', 'Personal' ) ) ) {
		wp_enqueue_script( 'fca_ept_premium_script' );		
		wp_enqueue_style( 'fca-ept-toggle-style' );
	}
	
	if(  get_post_type() === 'wp_block' ) {
		wp_enqueue_style( 'fca-ept-reusable-block-style' );
	}
	
	wp_localize_script( 'fca_ept_editor_script', 'fcaEptEditorData', array( 
		'edition' => DH_PTP_LICENSE_PACKAGE,
		'directory' => PTP_PLUGIN_URL,
		'woo_integration' => function_exists( 'fca_ept_get_woo_products' ),
		'toggle_integration' => function_exists( 'fca_ept_render_toggle' ),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'edit_url' => admin_url( 'edit.php' ),
		'fa_classes' => function_exists( 'fca_ept_get_fa_classes' ) ? fca_ept_get_fa_classes() : false,
		'debug' => PTP_DEBUG,
		'theme_support' => array(
			'wide' => get_theme_support( 'align-wide' ),
			'block_styles' => get_theme_support( 'wp-block-styles' ),			
		),
		'post_type' => get_post_type(),
	));
	
}
add_action( 'enqueue_block_editor_assets', 'fca_ept_block_enqueue' );

// ADD OUR MENU, MAYBE REMOVE LEGACY WP ADMIN MENU ITEMS
function fca_ept_admin_menu() {
	global $submenu;
	
	add_submenu_page( 'edit.php?post_type=easy-pricing-table', __('All Pricing Tables', 'easy-pricing-tables'), __('All Pricing Tables', 'easy-pricing-tables'), 'manage_options', 'ept3-list', 'fca_ept_render_post_list', 0 );

	add_submenu_page( 'edit.php?post_type=easy-pricing-table', __('Add New', 'easy-pricing-tables'), __('Add New', 'easy-pricing-tables'), 'manage_options', 'ept3-list-new', 'fca_ept_render_add_new', 1 );
	// hide legacy tables submenu if this is a fresh install OR if it's disabled through settings menu
	$show_legacy_tables = get_option( 'dh_ptp_show_legacy_tables' );
	
	if( !$show_legacy_tables ){
		unset($submenu['edit.php?post_type=easy-pricing-table'][2]);


	} 
	//REMOVE ADD NEW - USE THE LEGACY OR NEW POST LIST PAGE TO ADD NEW
	unset($submenu['edit.php?post_type=easy-pricing-table'][3]);

}
add_action( 'admin_menu', 'fca_ept_admin_menu' );

function fca_ept_render_add_new() {
	echo "<script>window.location='" . esc_url( admin_url( 'edit.php' ) ) . "?post_type=easy-pricing-table&page=ept3-list&add_new=1" . "'</script>";
}

function fca_ept_render_post_list(){
	
	$add_new = !empty( $_GET['add_new'] );
	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}
	
	include ( PTP_PLUGIN_PATH . 'includes/post-list-table.php' );
	wp_enqueue_style( 'fca_ept_post_list_css', PTP_PLUGIN_URL . '/assets/blocks/post-list.min.css', array(), PTP_PLUGIN_VER );
	wp_enqueue_script( 'fca_ept_post_list_js', PTP_PLUGIN_URL . '/assets/blocks/post-list.min.js', array(), PTP_PLUGIN_VER, true );
	
	$new_block_link = add_query_arg( array(
		'fca_ept_new_block' => 1,
		'ept_nonce' => wp_create_nonce( 'ept_new' ),
	));
	
	$new_toggle_link = add_query_arg( array(
		'fca_ept_new_toggle' => 1,
		'ept_nonce' => wp_create_nonce( 'ept_new' ),
	));
	
	$new_page_link = add_query_arg( array(
		'fca_ept_new_page' => 1,
		'ept_nonce' => wp_create_nonce( 'ept_new' ),
	));
	
	?>
	<div class="fca-ept-modal" <?php echo $add_new ? 'style="display:block;"' : '' ?> >
		<div class="fca-ept-modal-inner">
			<a href="#" id="fca-ept-modal-close"><?php esc_html_e( 'Close', 'easy-pricing-tables' )?></a>
			<h2><?php esc_html_e( 'Choose an Option', 'easy-pricing-tables' ) ?></h2>
			<table class="fca-ept-modal-list">
				<tr>
					<td>
						<a href="<?php echo esc_url( $new_block_link ) ?>" class="fca-ept-modal-list-group" >
							<span class="dashicons dashicons-shortcode"></span>
							<h3><?php esc_html_e( 'New Shortcode', 'easy-pricing-tables' ) ?></h3>
							<p><?php esc_html_e( 'Create shortcode for use with page editors like Divi or WP Bakery.', 'easy-pricing-tables' ) ?></p>
							<h4><?php esc_html_e( 'Recommended for Divi/WP Bakery/Visual Composer', 'easy-pricing-tables' ) ?></h4>
						</a>				
					</td>					
					<td>
						<a href="<?php echo esc_url( $new_page_link ) ?>" class="fca-ept-modal-list-group" >
							<span class="dashicons dashicons-welcome-add-page"></span>
							<h3><?php esc_html_e( 'New Page with Pricing Table', 'easy-pricing-tables' ) ?></h3>
							<p><?php esc_html_e( 'Create a new blank page containing a pricing table block.', 'easy-pricing-tables' ) ?></p>
							<h4><?php esc_html_e( 'Recommended for Gutenberg', 'easy-pricing-tables' ) ?></h4>
						</a>					
					</td>					
				</tr>
			</table>
			<p class="help" >
				<?php if ( !in_array( DH_PTP_LICENSE_PACKAGE, ['Free', 'Personal' ] ) ) { ?>
					<a href="<?php echo esc_url( $new_toggle_link ) ?>" ><?php esc_html_e( 'New Pricing Table w/ Toggle Shortcode', 'easy-pricing-tables' ) ?></a><br>
				<?php } ?>
				<span class="dashicons dashicons-welcome-learn-more"></span>
				<?php esc_html_e( 'Did you know you can add an Easy Pricing Table block to any post or page?', 'easy-pricing-tables' ) ?> 
				<a href="https://fatcatapps.com/knowledge-base/how-to-add-a-table-to-a-post-shortcode-reusable-block-block/" target="_blank"><?php esc_html_e( 'Learn more', 'easy-pricing-tables' ) ?></a>.
			</p>
		</div>
	</div>
<form method="post">
	<div class="wrap">
		<h2>Easy Pricing Tables <a href="#" id="fca-ept-add-new-button" class="page-title-action">Add New</a></h2>
		
		<?php if ( DH_PTP_LICENSE_PACKAGE === 'Free' ) { ?>
			<p><?php esc_html_e( 'Problems, Suggestions?', 'easy-pricing-tables' ) ?> 
			<a href="https://wordpress.org/support/plugin/easy-pricing-tables" target="_blank"><?php esc_html_e( 'Visit the support forum', 'easy-pricing-tables' ) ?></a> | 
			<a href="https://fatcatapps.com/article-categories/easy-pricing-tables/" target="_blank"><?php esc_html_e( 'Knowledge Base', 'easy-pricing-tables' ) ?></a> | 
			<a href="https://youtu.be/iU3mC8vXKt8" target="_blank"><?php esc_html_e( 'Watch Demo', 'easy-pricing-tables' ) ?></a> |
			<a href="http://fatcatapps.com/easypricingtables/?utm_campaign=ept-ui-sidebar&utm_source=free-plugin&utm_medium=link&utm_content=v3" target="_blank"><?php esc_html_e( 'Get Easy Pricing Tables Premium', 'easy-pricing-tables' ) ?></a>
			</p>
			<div class='fca-ept-twoup'>
				<div>
					<?php
					$listTable = new EPT3_List_Table();
					$listTable->prepare_items();
					$listTable->display();
					?>
				</div>
				<div id='fca-ept-upgrade' >
					<h2><?php esc_html_e( "Wanna get more sales?", 'easy-pricing-tables' ) ?></h2>
					<p><?php esc_html_e( "Upgrade to Premium and Build Better Pricing Tables. You'll Get:", 'easy-pricing-tables' ) ?></p>
					<p><span class='dashicons dashicons-yes'></span>Nine Gorgeous Designs</p>
					<p><span class='dashicons dashicons-yes'></span>Fully Customizable (Colors, Fonts, CSS etc.)</p>
					<p><span class='dashicons dashicons-yes'></span>700+ Icons to Add to Your Tables</p>
					<p><span class='dashicons dashicons-yes'></span>Priority Email Support</p>
					<p><span class='dashicons dashicons-yes'></span>Tooltips</p>
					<p><span class='dashicons dashicons-yes'></span>Font Picker with 12+ fonts</p>
					<p><span class='dashicons dashicons-yes'></span>Pricing Toggles - switch between currencies or monthly/yearly pricing</p>
					<p style='text-align:center;'><a class='button button-primary' href='https://fatcatapps.com/easypricingtables/' target='_blank'>Upgrade Now</a></p>
				
				</div>
			</div>
		<?php } else { ?>
			<p><?php esc_html_e( 'Problems, Suggestions?', 'easy-pricing-tables' ) ?> 
			<a href="https://fatcatapps.com/support/" target="_blank"><?php esc_html_e( 'Get support', 'easy-pricing-tables' ) ?></a> | 
			<a href="https://fatcatapps.com/article-categories/easy-pricing-tables/" target="_blank"><?php esc_html_e( 'Knowledge Base', 'easy-pricing-tables' ) ?></a> | 
			<a href="https://youtu.be/iU3mC8vXKt8" target="_blank"><?php esc_html_e( 'Watch Demo', 'easy-pricing-tables' ) ?></a>
			<?php
			$listTable = new EPT3_List_Table();
			$listTable->prepare_items();
			$listTable->display();
			?>
			</p>
		<?php } ?>
	</div>
</form>
<?php	
}

function fca_ept_get_woo_products_ajax(){

	if( function_exists( 'fca_ept_get_woo_products' ) ){
		wp_send_json_success( fca_ept_get_woo_products() );
	}
}
add_action( 'wp_ajax_fca_ept_get_woo_products_ajax', 'fca_ept_get_woo_products_ajax' );

//USED TO HELP RENDER FINAL TABLES
function fca_ept_get_product_data( $column, $toggle, $prop ){

	// if we have woo integration and the current column is linked to a product
	$wooproduct = function_exists( 'fca_ept_get_woo_products' ) && !empty( $column['wooProductID' . $toggle] ) ? $column['wooProductID' . $toggle] : '';

	switch ( $prop ){

		case 'plan':
			if ( $wooproduct ){
				return $column['useCustomWooTitle' . $toggle] ? $column['useCustomWooTitle' . $toggle] : fca_ept_get_woo_products( $wooproduct )['title'];
			}
			return empty( $column['planText' . $toggle] ) ? '' : $column['planText' . $toggle];
		case 'image':
			if ( $wooproduct ){
				return empty( fca_ept_get_woo_products( $wooproduct )['image'] ) ? '' : fca_ept_get_woo_products( $wooproduct )['image'];
			}
			if ( !empty( $column['planImage'] ) ){
				return $column['planImage'];
			}
			return null;
		case 'price':
			if ( $wooproduct ){
				return fca_ept_get_woo_products( $wooproduct )['price'];
			}
			return empty( $column['priceText' . $toggle] ) ? '' : $column['priceText' . $toggle];
		case 'url':
			if ( $wooproduct ){
				return fca_ept_get_woo_products( $wooproduct )['url'];
			}
			return empty( $column['buttonURL' . $toggle] ) ? '' : do_shortcode( $column['buttonURL' . $toggle] );
			
		default:
			return '';
	}

}

function fca_ept_clone_table( $to_duplicate ) {
			
	$post = get_post( $to_duplicate );	
		
	if (isset( $post ) && $post != null ) {
		
		global $wpdb;
		
		$args = array(
			'post_content'   => $post->post_type === 'wp_block' ? wp_slash( $post->post_content ) : $post->post_content,
			'post_name'      => '',
			'post_status'    => 'publish',
			'post_title'     => $post->post_title . ' copy',
			'post_type'      => $post->post_type,
		);
		$new_post_id = wp_insert_post( $args );

		$post_meta_infos = $wpdb->get_results( $wpdb->prepare("SELECT meta_key, meta_value FROM %i WHERE post_id = %d", $wpdb->postmeta, $to_duplicate ) );
		
		if ( count( $post_meta_infos ) ) {
			
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			
			foreach ($post_meta_infos as $meta_info) {
				
				$meta_key = $meta_info->meta_key;
				if ( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			
			$wpdb->query( $sql_query );
		}
		
		echo "<script>window.location='" . esc_url( admin_url( 'post.php' ) ) . "?post=" . esc_attr( $new_post_id ) . "&action=edit" . "'</script>";
		exit;		
	}

}

function fca_ept_block_admin_notice() {
	$current_page = empty( $_GET['page'] ) ? '' : sanitize_text_field( wp_unslash( $_GET['page'] ) );
	$action = empty( $_GET['action'] ) ? false : sanitize_text_field( wp_unslash( $_GET['action'] ) );
	
	if ( $current_page === 'ept3-list' && $action === 'trash'  ){
		echo '<div id="fca-ept-setup-notice" class="notice notice-success is-dismissible">';
			echo '<p>' . esc_attr__( "Pricing table deleted successfully.", 'easy-pricing-tables' ) . "</p>" ;
		echo '</div>';
	}

}
add_action( 'admin_notices', 'fca_ept_block_admin_notice' );

function fca_ept_match_heights_js( $attributes ) {
	$matchHeights = empty( $attributes['matchHeightsToggle'] ) ? false : true;
	$table_id = empty( $attributes['tableID'] ) ? false : $attributes['tableID'];
	
	if ( $matchHeights && $table_id ) {
		
		$thisTableID = "#fca-ept-table-$table_id";
				
		return "<script id='ept-$table_id-matchheight'>
			var thisTableID = '$thisTableID';
			var imageDivs = document.querySelectorAll( thisTableID + ' .fca-ept-plan-image img' )			
			for( var i = 0; i < imageDivs.length; i++ ) {							
				imageDivs[i].addEventListener('load', function(){
					var imageDivs = document.querySelectorAll( thisTableID + ' .fca-ept-plan-image img' )
					var shortestImageHeight = 2147483647
					for( var i = 0; i < imageDivs.length; i++ ) {							
						imageDivs[i].style.maxHeight = 'none'
						if ( imageDivs[i].offsetHeight > 0 && imageDivs[i].offsetHeight < shortestImageHeight ) {
							shortestImageHeight = imageDivs[i].offsetHeight
						}
					}
					//SET IMAGE DIV CSS
					for( var i = 0; i < imageDivs.length; i++ ) {				
						imageDivs[i].style.maxHeight = shortestImageHeight + 'px'
					}
					
				});
			}
			
			</script>";
	}
	
	return;
}
