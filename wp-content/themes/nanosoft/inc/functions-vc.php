<?php
if ( ! function_exists( 'vc_add_param' ) ) {
    return;
}

add_action( 'vc_before_init', function () {
	vc_add_param( 'vc_row', [
		'type' => 'dropdown',
		'heading' => esc_html__( 'Canvas Effect', 'nanosoft' ),
		'param_name' => 'canvas_effect',
		'value' => [
			esc_html__( 'No', 'nanosoft' ) => 'no',
			esc_html__( 'Yes', 'nanosoft' ) => 'yes'
		]
	] );
    vc_add_param( 'vc_row', [
        'type' => 'dropdown',
        'heading' => esc_html__( 'Background Position', 'nanosoft' ),
        'param_name' => 'bg_position',
        'group' => esc_html__( 'Design Options', 'nanosoft' ),
        'value' => [
            esc_html__( 'Default', 'nanosoft' ) => '',
            esc_html__( 'Left Top', 'nanosoft' ) => 'left top',
            esc_html__( 'Left Center', 'nanosoft' ) => 'left center',
            esc_html__( 'Left Bottom', 'nanosoft' ) => 'left bottom',
            esc_html__( 'Right Top', 'nanosoft' ) => 'right top',
            esc_html__( 'Right Center', 'nanosoft' ) => 'right center',
            esc_html__( 'Right Bottom', 'nanosoft' ) => 'right bottom',
            esc_html__( 'Center Top', 'nanosoft' ) => 'center top',
            esc_html__( 'Center Center', 'nanosoft' ) => 'center center',
            esc_html__( 'Center Bottom', 'nanosoft' ) => 'center bottom',
            esc_html__( 'Custom', 'nanosoft' ) => 'custom'
        ]
    ] );
    vc_add_param( 'vc_row', [
        'type' => 'textfield',
        'heading' => esc_html__( 'Custom Background Position', 'nanosoft' ),
        'param_name' => 'custom_position',
        'group' => esc_html__( 'Design Options', 'nanosoft' ),
        'std' => '',
        'dependency' => array(
            'element' => 'bg_position',
            'value'   => 'custom',
        ),
    ] );
    
    vc_add_param( 'vc_row', [
        'type' => 'dropdown',
        'heading' => esc_html__( 'Advanced Background Size', 'nanosoft' ),
        'param_name' => 'bg_size',
        'group' => esc_html__( 'Design Options', 'nanosoft' ),
        'value' => [
            esc_html__( 'Default', 'nanosoft' ) => '',
            esc_html__( '100% Width', 'nanosoft' ) => '100% auto',
            esc_html__( '100% Height', 'nanosoft' ) => 'auto 100%',
            esc_html__( 'Stretch', 'nanosoft' ) => '100% 100%',
            esc_html__( 'Custom', 'nanosoft' ) => 'custom'
        ]
    ] );
    vc_add_param( 'vc_row', [
        'type' => 'textfield',
        'heading' => esc_html__( 'Custom Background Size', 'nanosoft' ),
        'param_name' => 'custom_size',
        'group' => esc_html__( 'Design Options', 'nanosoft' ),
        'std' => '',
        'dependency' => array(
            'element' => 'bg_size',
            'value'   => 'custom',
        ),
    ] );

    vc_add_param( 'vc_section', [
        'type' => 'dropdown',
        'heading' => esc_html__( 'Background Position', 'nanosoft' ),
        'param_name' => 'bg_position',
        'group' => esc_html__( 'Design Options', 'nanosoft' ),
        'value' => [
            esc_html__( 'Default', 'nanosoft' ) => '',
            esc_html__( 'Left Top', 'nanosoft' ) => 'left top',
            esc_html__( 'Left Center', 'nanosoft' ) => 'left center',
            esc_html__( 'Left Bottom', 'nanosoft' ) => 'left bottom',
            esc_html__( 'Right Top', 'nanosoft' ) => 'right top',
            esc_html__( 'Right Center', 'nanosoft' ) => 'right center',
            esc_html__( 'Right Bottom', 'nanosoft' ) => 'right bottom',
            esc_html__( 'Center Top', 'nanosoft' ) => 'center top',
            esc_html__( 'Center Center', 'nanosoft' ) => 'center center',
            esc_html__( 'Center Bottom', 'nanosoft' ) => 'center bottom',
            esc_html__( 'Custom', 'nanosoft' ) => 'custom'
        ]
    ] );
    vc_add_param( 'vc_section', [
        'type' => 'textfield',
        'heading' => esc_html__( 'Custom Background Position', 'nanosoft' ),
        'param_name' => 'custom_position',
        'group' => esc_html__( 'Design Options', 'nanosoft' ),
        'std' => '',
        'dependency' => array(
            'element' => 'bg_position',
            'value'   => 'custom',
        ),
    ] );
    vc_add_param( 'vc_section', [
        'type' => 'dropdown',
        'heading' => esc_html__( 'Advanced Background Size', 'nanosoft' ),
        'param_name' => 'bg_size',
        'group' => esc_html__( 'Design Options', 'nanosoft' ),
        'value' => [
            esc_html__( 'Default', 'nanosoft' ) => '',
            esc_html__( '100% Width', 'nanosoft' ) => '100% auto',
            esc_html__( '100% Height', 'nanosoft' ) => 'auto 100%',
            esc_html__( 'Stretch', 'nanosoft' ) => '100% 100%',
            esc_html__( 'Custom', 'nanosoft' ) => 'custom'
        ]
    ] );
    vc_add_param( 'vc_section', [
        'type' => 'textfield',
        'heading' => esc_html__( 'Custom Background Size', 'nanosoft' ),
        'param_name' => 'custom_size',
        'group' => esc_html__( 'Design Options', 'nanosoft' ),
        'std' => '',
        'dependency' => array(
            'element' => 'bg_size',
            'value'   => 'custom',
        ),
    ] );
} );