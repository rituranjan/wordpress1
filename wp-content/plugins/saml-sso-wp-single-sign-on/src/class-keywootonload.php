<?php
/** Load Main File KeywootOnload
 *
 * @package keywoot-saml-sso-single-sign-on
 */

namespace KWSSO_CORE\Src;

use KWSSO_CORE\Src\Utility\KWSSO_Menu;
use KWSSO_CORE\Src\Utility\Page\KWSSO_Page;
use KWSSO_CORE\Src\Utility\Tab\KWSSO_PluginTabs;
use KWSSO_CORE\Traits\Instance;
use KWSSO_CORE\Helper\KWSSO_CurlCall;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'KeywootOnload' ) ) {
	final class KeywootOnload {

		use Instance;

		/** Constructor */
		private function __construct() {
			$this->kwsso_load_hooks();
		}

		/**
		 * Initialize all the main hooks needed for the plugin
		 */
		private function kwsso_load_hooks() {
			add_action( 'plugins_loaded', [ $this, 'kwsso_load_textdomain' ] );
			add_action( 'admin_menu', [ $this, 'load_keywoot_menu' ] );
			add_action( 'plugin_action_links_' . KWSSO_PLUGIN_NAME, [ $this, 'kwsso_plugin_action_links' ], 10, 1 );
			$this->kwsso_enqueue();
		}

		/**
		 * Initialize all the enqueue actions for the plugin
		 */
		private function kwsso_enqueue() {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'kwsso_load_jquery' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'load_frontend_script' ], 99 );
			add_action( 'login_enqueue_scripts', [ $this, 'load_frontend_script' ], 99 );
		}

		/**
		 * Enqueue admin-related scripts and styles
		 */
		public function enqueue_admin_scripts() {
			$this->kwsso_enqueue_plugin_settings_css();
			$this->kwsso_enqueue_plugin_settings_script();
		}

		/**
		 * Ensure jQuery is loaded
		 */
		public function kwsso_load_jquery() {
			if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
				wp_enqueue_script( 'jquery' );
			}
		}

		/**
		 * Load Keywoot menu
		 */
		public function load_keywoot_menu() {
			KWSSO_Menu::instance();
		}

		/**
		 * Load the main middleware
		 */
		public function kwsso_load_main_middleware() {
			include KWSSO_PLUGIN_DIR . 'src/public/middleware/kwsso-main-middleware.php';
		}

		/**
		 * Enqueue plugin settings CSS
		 */
		public function kwsso_enqueue_plugin_settings_css() {
			wp_enqueue_style( 'kwsso_admin_settings_style', KWSSO_CSS_URL, [], KWSSO_PLUGIN_VERSION );
			if ( $this->is_kwsso_page() ) {
				wp_enqueue_style( 'kwsso_main_style', KWSSO_MAIN_CSS, [], KWSSO_PLUGIN_VERSION );
			}
		}

		/**
		 * Check if the current page is a Keywoot page
		 */
		private function is_kwsso_page() {
			$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return strpos( $page, 'kwsso' ) === 0 || strpos( $_SERVER['SCRIPT_NAME'], 'plugins.php' ) !== false;
		}

		/**
		 * Enqueue plugin settings script
		 */
		public function kwsso_enqueue_plugin_settings_script() {
			wp_enqueue_script( 'kwsso_admin_settings_script', KWSSO_JS_URL, [ 'jquery' ], KWSSO_PLUGIN_VERSION, false );
			$localized_data = [
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'kwsso_nonce' ), 
				'testConnUrl' => esc_url( kwsso_get_test_url() ),  
			];
			
			wp_localize_script( 'kwsso_admin_settings_script', 'kwssoData', $localized_data );
		}

		/**
		 * Enqueue frontend scripts
		 */
		public function load_frontend_script() {
			wp_enqueue_script( 'jquery' );
		}

		/**
		 * Load plugin textdomain for translations
		 */
		public function kwsso_load_textdomain() {
			load_plugin_textdomain( 'saml-sso-wp-single-sign-on', false, dirname( KWSSO_PLUGIN_NAME ) . '/lang/' );
		}

		/**
		 * Add action links to the plugin
		 */
		public function kwsso_plugin_action_links( $links ) {
			$tab_details = KWSSO_PluginTabs::instance();
			$sp_tab = $tab_details->tab_details['SERVICE_PROVIDER'];
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			if ( is_plugin_active( KWSSO_PLUGIN_NAME ) ) {
				array_unshift( $links, '<a href="' . esc_url( admin_url( 'admin.php?page=' . $sp_tab->kwsso_menu_slug ) ) . '">' . kwsso_lang_( 'Settings' ) . '</a>' );
			}
			return $links;
		}
	}
}
