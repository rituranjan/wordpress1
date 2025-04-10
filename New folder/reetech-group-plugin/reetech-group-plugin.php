 
<?php
/*
Plugin Name: ReetechTEST Group Plugin
Description: A plugin for Reetech Group
Version: 1.0
Author: Your Name
*/

// Register custom API endpoint
add_action('rest_api_init', 'register_hello_world_endpoint');

function register_hello_world_endpoint() {
    register_rest_route(
        'custom-plugin/v1', // Namespace
        '/hello',           // Endpoint path
        array(
            'methods'  => 'GET',
            'callback' => 'hello_world_response',
            'permission_callback' => '__return_true' // Publicly accessible
        )
    );
}

// Callback function to handle the request
function hello_world_response() {
    return new WP_REST_Response(array(
        'message' => 'Hello World!'
    ), 200);
}

//if (!defined('ABSPATH')) exit;

// Define plugin constants
define('REETECH_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('REETECH_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load dependencies
require_once REETECH_PLUGIN_DIR . 'includes/class-asset-loader.php';
require_once REETECH_PLUGIN_DIR . 'includes/class-rest-api.php';
require_once REETECH_PLUGIN_DIR . 'includes/class-shortcode-handler.php';
require_once REETECH_PLUGIN_DIR . 'includes/class-database-manager.php';
require_once REETECH_PLUGIN_DIR . 'includes/class-cache-manager.php';

// Initialize components
add_action('plugins_loaded', function() {
    Reetech\AssetLoader::init();
    Reetech\RestApi::init();
    Reetech\ShortcodeHandler::init();
});