<?php 

function fca_ept4_gutenblock_register() {
	
	wp_register_script( 'fca-ept4-template-1', PTP_PLUGIN_URL . '/includes/v4/templates/template-1.js', [], PTP_PLUGIN_VER, true );
	wp_register_script( 'fca-ept4-template-2', PTP_PLUGIN_URL . '/includes/v4/templates/template-2.js', [], PTP_PLUGIN_VER, true );
	wp_register_script( 'fca-ept4-icons-js', PTP_PLUGIN_URL . '/includes/v4/icons.min.js', [], PTP_PLUGIN_VER, true );
	
	$main_script_requires = [
		'fca-ept4-template-1',
		'fca-ept4-template-2',
		'fca-ept4-icons-js',
	];
	
	if( DH_PTP_LICENSE_PACKAGE !== 'Free' ) {
		wp_register_script( 'fca-ept4-template-3', PTP_PLUGIN_URL . '/includes/v4/templates/template-3.js', [], PTP_PLUGIN_VER, true );
		wp_register_script( 'fca-ept4-template-4', PTP_PLUGIN_URL . '/includes/v4/templates/template-4.js', [], PTP_PLUGIN_VER, true );
		wp_register_script( 'fca-ept4-template-5', PTP_PLUGIN_URL . '/includes/v4/templates/template-5.js', [], PTP_PLUGIN_VER, true );
		wp_register_script( 'fca-ept4-template-6', PTP_PLUGIN_URL . '/includes/v4/templates/template-6.js', [], PTP_PLUGIN_VER, true );
		wp_register_script( 'fca-ept4-template-7', PTP_PLUGIN_URL . '/includes/v4/templates/template-7.js', [], PTP_PLUGIN_VER, true );
		wp_register_script( 'fca-ept4-template-8', PTP_PLUGIN_URL . '/includes/v4/templates/template-8.js', [], PTP_PLUGIN_VER, true );
		wp_register_script( 'fca-ept4-template-9', PTP_PLUGIN_URL . '/includes/v4/templates/template-9.js', [], PTP_PLUGIN_VER, true );
		wp_register_script( 'fca-ept4-toolbar-js', PTP_PLUGIN_URL . '/includes/v4/toolbar.min.js', [], PTP_PLUGIN_VER, true );
		
		$main_script_requires = [
			'fca-ept4-template-1',
			'fca-ept4-template-2',
			'fca-ept4-template-3',
			'fca-ept4-template-4',
			'fca-ept4-template-5',
			'fca-ept4-template-6',
			'fca-ept4-template-7',
			'fca-ept4-template-8',
			'fca-ept4-template-9',
			'fca-ept4-toolbar-js',
			'fca-ept4-icons-js',
		];
	}
	
	wp_register_script( 'fca-ept4-table-js', PTP_PLUGIN_URL . '/includes/v4/blocks/table/table.min.js', $main_script_requires, PTP_PLUGIN_VER, true );
	wp_register_script( 'fca-ept4-frontend-js', PTP_PLUGIN_URL . '/includes/v4/blocks/table/table-frontend.min.js', [], PTP_PLUGIN_VER, true );
	wp_register_script( 'fca-ept4-column-js', PTP_PLUGIN_URL . '/includes/v4/blocks/column/column.min.js', [], PTP_PLUGIN_VER, true );
	wp_register_script( 'fca-ept4-list-js', PTP_PLUGIN_URL . '/includes/v4/blocks/list/list.min.js', [], PTP_PLUGIN_VER, true );
	wp_register_script( 'fca-ept4-button-js', PTP_PLUGIN_URL . '/includes/v4/blocks/button/button.min.js', [], PTP_PLUGIN_VER, true );
	wp_register_script( 'fca-ept4-field-js', PTP_PLUGIN_URL . '/includes/v4/blocks/field/field.min.js', [], PTP_PLUGIN_VER, true );
	wp_register_script( 'fca-ept4-list-item-js', PTP_PLUGIN_URL . '/includes/v4/blocks/list-item/list-item.min.js', [], PTP_PLUGIN_VER, true );
	wp_register_style( 'fca-ept4-editor-css', PTP_PLUGIN_URL . '/includes/v4/blocks/table/editor.min.css', [], PTP_PLUGIN_VER );
	wp_register_style( 'fca-ept4-column-css', PTP_PLUGIN_URL . '/includes/v4/blocks/column/column.min.css', [], PTP_PLUGIN_VER );
	wp_register_style( 'fca-ept4-pattern-css', PTP_PLUGIN_URL . '/includes/v4/pattern.min.css', [], PTP_PLUGIN_VER );
	
	register_block_type( PTP_PLUGIN_PATH . '/includes/v4/blocks/table/' );	
	register_block_type( PTP_PLUGIN_PATH . '/includes/v4/blocks/column/' );
	register_block_type( PTP_PLUGIN_PATH . '/includes/v4/blocks/list/' );
	register_block_type( PTP_PLUGIN_PATH . '/includes/v4/blocks/list-item/' );
	register_block_type( PTP_PLUGIN_PATH . '/includes/v4/blocks/button/' );
	register_block_type( PTP_PLUGIN_PATH . '/includes/v4/blocks/field/' );
	
	if( file_exists( PTP_PLUGIN_PATH . 'includes/v4/blocks/toggle-table/toggle-table.js' ) ) {
		wp_register_script( 'fca-ept4-toggle-table-js', PTP_PLUGIN_URL . '/includes/v4/blocks/toggle-table/toggle-table.min.js', [], PTP_PLUGIN_VER, true );
		wp_register_script( 'fca-ept4-toggle-js', PTP_PLUGIN_URL . '/includes/v4/blocks/toggle/toggle.min.js', [], PTP_PLUGIN_VER, true );
		wp_register_style( 'fca-ept4-toggle-css', PTP_PLUGIN_URL . '/includes/v4/blocks/toggle/toggle.min.css', [], PTP_PLUGIN_VER );
		register_block_type( PTP_PLUGIN_PATH . '/includes/v4/blocks/toggle/' );
		register_block_type( PTP_PLUGIN_PATH . '/includes/v4/blocks/toggle-table/' );
	}
	
}
add_action( 'init', 'fca_ept4_gutenblock_register' );

function fca_ept4_block_enqueue() {
	global $post;
	$post_type = get_post_type();
	
	
	if( $post_type === 'wp_block' ) {
		$is_ptp = get_post_meta( $post->ID, '1_dh_ptp_settings', true );
		if( !empty( $is_ptp ) ) {
			wp_enqueue_style( 'fca-ept4-pattern-css' );				
		}
	}
	if ( is_admin() ) {
		$current_screen = get_current_screen();
		if ( !empty( $current_screen ) && $current_screen->is_block_editor() ) {
			wp_enqueue_style( 'fca-ept4-editor-css' );
			wp_localize_script( 'fca-ept4-table-js', 'fcaEpt4EditorData', [ 
				'site_url' => get_site_url(),
				'plugins_url' => PTP_PLUGIN_URL,
				'version' => PTP_PLUGIN_VER,
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'fca_ept4_ajax_nonce' ),
				'edition' => DH_PTP_LICENSE_PACKAGE,
				'woo_active' => ( file_exists( PTP_PLUGIN_PATH . 'includes/v4/woo.php' ) && fca_ept4_is_plugin_active(  'woocommerce/woocommerce.php' ) )
			] );
			wp_localize_script( 'fca-ept4-column-js', 'fcaEpt4ColumnData', [ 
				'allowAddingBlocks' => apply_filters( 'fca_ept_allow_adding_blocks', 'false', $post_type, $current_screen ),
			] );
			wp_localize_script( 'fca-ept4-toolbar-js', 'fcaEpt4ToolbarData', [ 
				'fa_classes' => function_exists( 'fca_ept_get_fa_classes' ) ? fca_ept_get_fa_classes() : false,
			] );
			wp_localize_script( 'fca-ept4-field-js', 'fcaEpt4FieldData', [ 
				'edition' => DH_PTP_LICENSE_PACKAGE
			] );
			wp_localize_script( 'fca-ept4-list-item-js', 'fcaEpt4ListItemData', [ 
				'edition' => DH_PTP_LICENSE_PACKAGE
			] );
		}
	}
	if( function_exists( 'fca_ept_get_fa_classes' ) ) {
		wp_enqueue_style( 'fca-ept-font-awesome' );		
	}	
}
add_action( 'enqueue_block_editor_assets', 'fca_ept4_block_enqueue' );
add_action( 'wp_enqueue_scripts', 'fca_ept4_block_enqueue' );

function fca_ept4_is_plugin_active( $plugin ) {
	return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}

function fca_ept4_do_shortcode( $atts ) {
	
	$id = empty( $atts['id'] ) ? '' : intval( $atts['id'] );
	$content = get_the_content( null, false, $id );
	if( $content ) {
		return do_blocks( $content );		
	}
}
add_shortcode( 'easy-pricing-tables', 'fca_ept4_do_shortcode' );
add_shortcode( 'ept3-block', 'fca_ept4_do_shortcode' ); //V3 SHORTCODE

