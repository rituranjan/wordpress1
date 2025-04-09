<?php

namespace KWSSO_CORE\Src\Utility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use KWSSO_CORE\Src\KeywootOnload;
use KWSSO_CORE\Src\Utility\Tab\KWSSO_PluginTabs;
use KWSSO_CORE\Traits\Instance;
use KWSSO_CORE\Constants;

if ( ! class_exists( 'KWSSO_Menu' ) ) {

	final class KWSSO_Menu {

		use Instance;
		private $callback;
		private $kwsso_menu_slug;
		private $menu_logo;
		private $tab_details;

		private function __construct() {
			$this->kwsso_initialize_properties();
			$this->kwsso_add_main_menu();
			$this->kwsso_add_sub_menus();
		}

		private function kwsso_initialize_properties() {
			$this->callback        = array( KeywootOnload::instance(), 'kwsso_load_main_middleware' );
			$this->menu_logo       = KWSSO_ICON;
			$tab_details           = KWSSO_PluginTabs::instance();
			$this->tab_details     = $tab_details->tab_details;
			$this->kwsso_menu_slug = $tab_details->parent_slug;
		}

		private function kwsso_add_main_menu() {
			add_menu_page(
				'WP SAML SSO',
				'WP SAML SSO',
				'manage_options',
				$this->kwsso_menu_slug,
				$this->callback,
				'dashicons-shield',
			);
		}
		private function kwsso_add_sub_menus() {
			foreach ( $this->tab_details as $tab_detail ) {
				add_submenu_page(
					$this->kwsso_menu_slug,
					$tab_detail->kwsso_page_name,
					$tab_detail->kwsso_menu_name,
					'manage_options',
					$tab_detail->kwsso_menu_slug,
					$this->callback
				);
			}
		}
	}
}
