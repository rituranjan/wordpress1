<?php

namespace KWSSO_CORE\Src\Utility\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class KWSSO_Page {
	public $kwsso_page_name;
	public $kwsso_menu_slug;
	public $kwsso_menu_name;
	public $kwsso_tab_name;
	public $url;
	public $kwsso_layout;
	public $kwsso_tab_icon;
	public $id;
	public $navbar_display;
	public $css;

	public function __construct( $kwsso_page_name, $kwsso_menu_slug, $kwsso_menu_name, $kwsso_tab_name, $kwsso_tab_icon, $kwsso_request_uri, $kwsso_layout, $id, $css = '', $navbar_display = true ) {
		$this->kwsso_page_name = $kwsso_page_name;
		$this->kwsso_menu_slug = $kwsso_menu_slug;
		$this->kwsso_menu_name = $kwsso_menu_name;
		$this->kwsso_tab_name  = $kwsso_tab_name;
		$this->kwsso_tab_icon  = $kwsso_tab_icon;
		$this->url             = add_query_arg( array( 'page' => $this->kwsso_menu_slug ), $kwsso_request_uri );
		$this->url             = remove_query_arg( array( 'subpage' ), $this->url );
		$this->kwsso_layout    = $kwsso_layout;
		$this->id              = $id;
		$this->navbar_display  = $navbar_display;
		$this->css             = $css;
	}
}

