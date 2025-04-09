<?php

namespace KWSSO_CORE\Src\Utility\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class KWSSO_SubPage {
	public $kwsso_page_name;
	public $kwsso_menu_name;
	public $kwsso_tab_name;
	public $kwsso_layout;
	public $id;
	public $navbar_display;
	public $css;

	public function __construct( $kwsso_page_name, $kwsso_menu_name, $kwsso_tab_name, $kwsso_layout, $id, $css = '', $navbar_display = true ) {
		$this->kwsso_page_name = $kwsso_page_name;
		$this->kwsso_menu_name = $kwsso_menu_name;
		$this->kwsso_tab_name  = $kwsso_tab_name;
		$this->kwsso_layout    = $kwsso_layout;
		$this->id              = $id;
		$this->navbar_display  = $navbar_display;
		$this->css             = $css;
	}

}
