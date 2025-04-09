<?php
$values     = [];
$taxonomies = [
    'nproject-category',
    'nproject-tag'
];

foreach ( $taxonomies as $taxonomy ) {
    $terms = get_terms( $taxonomy );
    $values[ $taxonomy ] = [];

    if ( is_array( $terms ) ) {
        foreach ( $terms as $term ) {
            $values[ $taxonomy ][] = [
                'label' => $term->name,
                'value' => $term->term_id
            ];
        }
    }
}

return array(
    'name'     => __( 'LineThemes: Project Carousel', 'nanosoft' ),
    'base'     => 'project-carousel',
    'category' => 'LineThemes',
    'params'   => array(
        // General tab
        array(
            'type'        => 'textfield',
            'heading'     => __( 'Widget Title', 'nanosoft' ),
            'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', 'nanosoft' ),
            'param_name'  => 'widget_title'
        ),

        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Categories', 'nanosoft' ),
            'description' => __( 'If you want to narrow output, enter category names here. Note: Only listed categories will be included.', 'nanosoft' ),
            'param_name'  => 'categories',
            'settings'    => array(
                'multiple'       => true,
                'sortable'       => true,
                'min_length'     => 1,
                'no_hide'        => true,
                'unique_values'  => true,
                'display_inline' => true,
                'values'         => $values['nproject-category']
            )
        ),

        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Tags', 'nanosoft' ),
            'description' => __( 'If you want to narrow output, enter tag names here. Note: Only listed tags will be included.', 'nanosoft' ),
            'param_name'  => 'tags',
            'settings'    => array(
                'multiple'       => true,
                'sortable'       => true,
                'min_length'     => 1,
                'no_hide'        => true,
                'unique_values'  => true,
                'display_inline' => true,
                'values'         => $values['nproject-tag']
            )
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Limit', 'nanosoft' ),
            'description' => __( 'The number of posts will be shown', 'nanosoft' ),
            'param_name'  => 'limit',
            'value'       => 9
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Offset', 'nanosoft' ),
            'description' => __( 'The number of posts to pass over', 'nanosoft' ),
            'param_name'  => 'offset',
            'value'       => 0
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Thumbnail Size', 'nanosoft' ),
            'description' => __( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nanosoft' ),
            'param_name'  => 'thumbnail_size'
        ),

        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Show Post Date?', 'nanosoft' ),
            'description' => __( 'Select "Yes" to display post date in the carousel', 'nanosoft' ),
            'param_name'  => 'show_date',
            'std'         => 'no',
            'value'       => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Date format', 'nanosoft' ),
            'description' => __( 'Enter custom date format that will be shown', 'nanosoft' ),
            'param_name'  => 'date_format',
            'dependency'  => array(
                'element' => 'show_date',
                'value'   => 'yes'
            ),
            'value'       => ''
        ),

        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Show Post Excerpt?', 'nanosoft' ),
            'description' => __( 'Select "Yes" to display post excerpt in the carousel', 'nanosoft' ),
            'param_name'  => 'show_excerpt',
            'std'         => 'no',
            'value'       => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Auto Post Excerpt?', 'nanosoft' ),
            'description' => __( 'Select "Yes" to display automatic generate post excerpt', 'nanosoft' ),
            'param_name'  => 'auto_excerpt',
            'dependency'  => array(
                'element' => 'show_excerpt',
                'value'   => 'yes'
            ),
            'std'         => 'no',
            'value'       => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Post excerpt length', 'nanosoft' ),
            'description' => __( 'Enter the custom length of post excerpt that will be generated', 'nanosoft' ),
            'param_name'  => 'excerpt_length',
            'dependency'  => array(
                'element' => 'auto_excerpt',
                'value'   => 'yes'
            ),
            'value'       => 200
        ),

        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Read more', 'nanosoft' ),
            'description' => __( 'Select "YES" to show the read more link', 'nanosoft' ),
            'param_name' => 'readmore',
            'std'        => 'yes',
            'value'      => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Read more text', 'nanosoft' ),
            'description' => __( 'Custom text for the read more link', 'nanosoft' ),
            'param_name'  => 'readmore_text',
            'dependency'  => array(
                'element' => 'readmore',
                'value'   => 'yes'
            ),
            'value'       => __( 'Read More', 'nanosoft' )
        ),

        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Order By', 'nanosoft' ),
            'description' => __( 'Select how to sort retrieved posts.', 'nanosoft' ),
            'param_name'  => 'order',
            'std'         => 'date',
            'value'       => array(
                __( 'Date', 'nanosoft' )          => 'date',
                __( 'ID', 'nanosoft' )            => 'ID',
                __( 'Author', 'nanosoft' )        => 'author',
                __( 'Title', 'nanosoft' )         => 'title',
                __( 'Modified', 'nanosoft' )      => 'modified',
                __( 'Random', 'nanosoft' )        => 'rand',
                __( 'Comment count', 'nanosoft' ) => 'comment_count',
                __( 'Menu order', 'nanosoft' )    => 'menu_order'
            )
        ),

        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Order Direction', 'nanosoft' ),
            'description' => __( 'Designates the ascending or descending order.', 'nanosoft' ),
            'param_name'  => 'direction',
            'std'         => 'DESC',
            'value'       => array(
                __( 'Ascending', 'nanosoft' )          => 'ASC',
                __( 'Descending', 'nanosoft' )            => 'DESC'
            )
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Extra Class', 'nanosoft' ),
            'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nanosoft' ),
            'param_name'  => 'class'
        ),

        // Carousel Options
        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Show Items', 'nanosoft' ),
            'description' => __( 'The maximum amount of items displayed at a time', 'nanosoft' ),
            'param_name'  => 'items',
            'group'       => __( 'Carousel Options', 'nanosoft' ),
            'value'       => array_combine( range( 1, 6 ), range( 1, 6 ) ),
            'std'         => 4
        ),

        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Autoplay?', 'nanosoft' ),
            'param_name' => 'autoplay',
            'group'      => __( 'Carousel Options', 'nanosoft' ),
            'std'        => 'yes',
            'value'      => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Stop On Hover?', 'nanosoft' ),
            'description' => __( 'Rewind speed in milliseconds', 'nanosoft' ),
            'param_name'  => 'hover_stop',
            'group'       => __( 'Carousel Options', 'nanosoft' ),
            'std'         => 'yes',
            'value'       => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'        => 'checkbox',
            'heading'     => __( 'Slider Controls', 'nanosoft' ),
            'param_name'  => 'controls',
            'group'       => __( 'Carousel Options', 'nanosoft' ),
            'std'         => 'navigation,rewind-navigation,pagination,pagination-numbers',
            'value'       => array(
                __( 'Navigation', 'nanosoft' )         => 'navigation',
                __( 'Rewind Navigation', 'nanosoft' )  => 'rewind-navigation',
                __( 'Pagination', 'nanosoft' )         => 'pagination',
                __( 'Pagination Numbers', 'nanosoft' ) => 'pagination-numbers'
            )
        ),

        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Scroll Per Page?', 'nanosoft' ),
            'param_name' => 'scroll_page',
            'group'       => __( 'Carousel Options', 'nanosoft' ),
            'std'        => 'yes',
            'value'      => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Allow Mouse Drag?', 'nanosoft' ),
            'param_name' => 'mouse_drag',
            'group'      => __( 'Carousel Options', 'nanosoft' ),
            'std'        => 'yes',
            'value'      => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Allow Touch Drag?', 'nanosoft' ),
            'param_name' => 'touch_drag',
            'group'      => __( 'Carousel Options', 'nanosoft' ),
            'std'        => 'yes',
            'value'      => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        // Speed
        array(
            'type'        => 'textfield',
            'heading'     => __( 'Autoplay Speed', 'nanosoft' ),
            'description' => __( 'Autoplay speed in milliseconds', 'nanosoft' ),
            'param_name'  => 'autoplay_speed',
            'group'       => __( 'Speed', 'nanosoft' ),
            'value'       => 5000
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Slide Speed', 'nanosoft' ),
            'description' => __( 'Slide speed in milliseconds', 'nanosoft' ),
            'param_name'  => 'slide_speed',
            'group' => __( 'Speed', 'nanosoft' ),
            'value'       => 200
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Pagination Speed', 'nanosoft' ),
            'description' => __( 'Pagination speed in milliseconds', 'nanosoft' ),
            'param_name'  => 'pagination_speed',
            'group' => __( 'Speed', 'nanosoft' ),
            'value'       => 200
        ),

        array(
            'type'        => 'textfield',
            'heading'     => __( 'Rewind Speed', 'nanosoft' ),
            'description' => __( 'Rewind speed in milliseconds', 'nanosoft' ),
            'param_name'  => 'rewind_speed',
            'group' => __( 'Speed', 'nanosoft' ),
            'value'       => 200
        ),

        // Responsive
        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Enable Responsive?', 'nanosoft' ),
            'param_name' => 'responsive',
            'group'      => __( 'Responsive', 'nanosoft' ),
            'std'        => 'yes',
            'value'      => array(
                __( 'Yes', 'nanosoft' ) => 'yes',
                __( 'No', 'nanosoft' ) => 'no'
            )
        ),

        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Items On Tablet', 'nanosoft' ),
            'description' => __( 'The maximum amount of items displayed at a time on tablet device', 'nanosoft' ),
            'param_name'  => 'tablet_items',
            'group'       => __( 'Responsive', 'nanosoft' ),
            'value'       => array_combine( range( 1, 6 ), range( 1, 6 ) ),
            'std'         => 2
        ),

        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Items On Mobile', 'nanosoft' ),
            'description' => __( 'The maximum amount of items displayed at a time on mobile device', 'nanosoft' ),
            'param_name'  => 'mobile_items',
            'group'       => __( 'Responsive', 'nanosoft' ),
            'value'       => array_combine( range( 1, 6 ), range( 1, 6 ) ),
            'std'         => 1
        ),

        array(
            'type' => 'css_editor',
            'param_name' => 'css',
            'group' => __( 'Design Options', 'nanosoft' )
        )
    )
);
