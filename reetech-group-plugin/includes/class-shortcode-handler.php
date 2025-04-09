<?php
namespace Reetech;

class ShortcodeHandler {
    private static $template_mapping = [
        'edit' => 'edit.php',
        'view' => 'view.php',
        'home' => 'home.php',
        'list' => 'list.php',
        'new' => 'new.php'
    ];

    public static function init() {
        add_shortcode('reetech_group', [__CLASS__, 'shortcode_handler']);
        add_filter('query_vars', [__CLASS__, 'add_query_vars']);
        add_action('template_redirect', [__CLASS__, 'template_override']);
    }

    public static function shortcode_handler($atts) {
        ob_start();
        $requested_page = get_query_var('home', 'home');
        self::load_template($requested_page);
        return ob_get_clean();
    }

    private static function load_template($page) {
        $template_file = self::$template_mapping[$page] ?? 'home.php';
        $template_path = REETECH_PLUGIN_DIR . "templates/pages/{$template_file}";
        
        if (file_exists($template_path)) {
            get_header();
            include $template_path;
            get_footer();
        } else {
            echo '<p>Template not found: ' . esc_html($template_file) . '</p>';
        }
    }

    public static function add_query_vars($vars) {
        $vars[] = 'home';
        return $vars;
    }

    public static function template_override() {
        if (get_query_var('home')) {
            // Custom template handling
        }
    }
} 
