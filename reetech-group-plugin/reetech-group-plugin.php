 
<?php
/*
Plugin Name: Reetech Group Plugin
Description: A plugin for Reetech Group
Version: 1.0
Author: Your Name
*/



if (!defined('ABSPATH')) exit;

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