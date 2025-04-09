<?php
return array(
    'name'     => esc_html__('LineThemes: Video Lightbox', 'nanosoft'),
    'base'     => 'video-lightbox',
    'category' => 'LineThemes',
    'params'   => array(
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__( 'Video URL', 'nanosoft' ),
            'description' => esc_html__( 'We are accepted both Youtube and Video URL', 'nanosoft' ),
            'param_name' => 'url',
            'std'        => ''
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__( 'Title', 'nanosoft' ),
            'param_name' => 'title',
            'std'        => ''
        ),

        array(
            'type'        => 'attach_image',
            'param_name'  => 'cover',
            'heading'     => esc_html__( 'Cover Image', 'nanosoft' ),
            'description' => esc_html__( 'Select the image you want to show as the video cover.', 'nanosoft' )
        ),

        array(
            'type'       => 'textfield',
            'heading'    => esc_html__( 'Width', 'nanosoft' ),
            'param_name' => 'width',
            'std'        => 640
        ),

        array(
            'type'       => 'textfield',
            'heading'    => esc_html__( 'Height', 'nanosoft' ),
            'param_name' => 'height',
            'std'        => 480
        ),
    )
);
