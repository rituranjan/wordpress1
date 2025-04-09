<?php
namespace Reetech;

class AssetLoader {
    public static function init() {
        add_action('admin_enqueue_scripts', [__CLASS__, 'admin_assets']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'frontend_assets']);
    }

    public static function admin_assets() {
        wp_enqueue_style('datatables', 'https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css');
        wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', ['jquery']);
        wp_enqueue_script('datatables-bootstrap', 'https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js', ['datatables']);
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
        
        wp_enqueue_script(
            'reetech-datatables',
            REETECH_PLUGIN_URL . 'assets/js/datatable.js',
            ['datatables', 'datatables-bootstrap'],
            filemtime(REETECH_PLUGIN_DIR . 'assets/js/datatable.js')
        );
        
        wp_localize_script('reetech-datatables', 'wpApiSettings', [
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ]);
    }

    public static function frontend_assets() {
        wp_enqueue_style(
            'reetech-css-main',
            REETECH_PLUGIN_URL . 'assets/css/module.css',
            [],
            filemtime(REETECH_PLUGIN_DIR . 'assets/css/module.css')
        );
        
        wp_enqueue_script(
            'reetech-js-main',
            REETECH_PLUGIN_URL . 'assets/js/javascript.js',
            ['jquery'],
            filemtime(REETECH_PLUGIN_DIR . 'assets/js/javascript.js'),
            true
        );
        
        wp_localize_script('reetech-js-main', 'reetechData', [
            'pluginUrl' => REETECH_PLUGIN_URL,
        ]);
    }
} 
