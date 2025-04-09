<?php
/*
Plugin Name: Reetech Group Plugin
Description: A plugin for Reetech Group.
Version: 1.0
Author: Your Name
*/


// Enqueue CSS and JS files
if (!function_exists('reetech_group_enqueue_assets')) {
    function reetech_group_enqueue_assets() {
        wp_enqueue_style('reetech-css-main', plugins_url('css/module.css', __FILE__));
        wp_enqueue_script('reetech-js-main', plugins_url('javascript/javascript.js', __FILE__), array('jquery'), null, true);
        wp_localize_script('reetech-js-main', 'reetechData', array(
            'pluginUrl' => plugins_url('', __FILE__),
        ));
    }
    add_action('wp_enqueue_scripts', 'reetech_group_enqueue_assets');
}

// Allow custom 'page' parameter in URLs
add_filter('query_vars', function($vars) {
    $vars[] = 'home';
    return $vars;
});

// Template override for our pages
add_action('template_redirect', function() {
    if (get_query_var('home')) {
        // Remove all WordPress theme output
        remove_all_actions('wp_head');
        remove_all_actions('wp_footer');
        remove_all_actions('get_header');
        remove_all_actions('get_footer');
        remove_all_actions('get_sidebar');
        
        // Load our custom template
        include plugin_dir_path(__FILE__) . 'templates/blank-wrapper.php';
        exit;
    }
});



// Shortcode to include HTML content
if (!function_exists('reetech_group_shortcode')) {
    function reetech_group_shortcode($atts) {
        // Template mapping
        $template_mapping = array(
            'edit' => 'edit.html',
            'view' => 'view.html',
            'home' => 'home.html',
            'list' => 'list.html',
        );

       
        // Get the requested page
        $requested_page = get_query_var('home', 'home');
        
        // Determine which file to load
        $file_to_load = isset($template_mapping[$requested_page]) 
                      ? $template_mapping[$requested_page] 
                      : 'edit.html';

        // Get file paths
        $template_dir = plugin_dir_path(__FILE__) . 'templates/';
        $file_path = $template_dir . $file_to_load;
        $header_path = $template_dir . 'header.html';
        $footer_path = $template_dir . 'footer.html';

        // Check if main file exists
        if (!file_exists($file_path)) {
            return '<p>Error: Requested page not found.</p>';
        }

        // Get header
        $header = file_exists($header_path) ? file_get_contents($header_path) : '';
        
        // Get content
        $content = file_get_contents($file_path);
        
        // Get footer
        $footer = file_exists($footer_path) ? file_get_contents($footer_path) : '';

        // Combine all parts
        $output = $header . $content ;//. $footer;
        

        // Remove code tags if any
        $output = preg_replace('/<code[^>]*>(.*?)<\/code>/is', '', $output);

        return $output;
    }
    add_shortcode('reetech_group', 'reetech_group_shortcode');
}