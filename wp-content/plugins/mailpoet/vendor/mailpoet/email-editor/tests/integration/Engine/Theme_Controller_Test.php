<?php
declare(strict_types = 1);
namespace MailPoet\EmailEditor\Engine;
if (!defined('ABSPATH')) exit;
class Theme_Controller_Test extends \MailPoetTest {
 private Theme_Controller $theme_controller;
 public function _before() {
 parent::_before();
 $this->theme_controller = $this->di_container->get( Theme_Controller::class );
 }
 public function testItGeneratesCssStylesForRenderer() {
 $css = $this->theme_controller->get_stylesheet_for_rendering();
 // Font families.
 verify( $css )->stringContainsString( '.has-arial-font-family' );
 verify( $css )->stringContainsString( '.has-comic-sans-ms-font-family' );
 verify( $css )->stringContainsString( '.has-courier-new-font-family' );
 verify( $css )->stringContainsString( '.has-georgia-font-family' );
 verify( $css )->stringContainsString( '.has-lucida-font-family' );
 verify( $css )->stringContainsString( '.has-tahoma-font-family' );
 verify( $css )->stringContainsString( '.has-times-new-roman-font-family' );
 verify( $css )->stringContainsString( '.has-trebuchet-ms-font-family' );
 verify( $css )->stringContainsString( '.has-verdana-font-family' );
 verify( $css )->stringContainsString( '.has-arvo-font-family' );
 verify( $css )->stringContainsString( '.has-lato-font-family' );
 verify( $css )->stringContainsString( '.has-merriweather-font-family' );
 verify( $css )->stringContainsString( '.has-merriweather-sans-font-family' );
 verify( $css )->stringContainsString( '.has-noticia-text-font-family' );
 verify( $css )->stringContainsString( '.has-open-sans-font-family' );
 verify( $css )->stringContainsString( '.has-playfair-display-font-family' );
 verify( $css )->stringContainsString( '.has-roboto-font-family' );
 verify( $css )->stringContainsString( '.has-source-sans-pro-font-family' );
 verify( $css )->stringContainsString( '.has-oswald-font-family' );
 verify( $css )->stringContainsString( '.has-raleway-font-family' );
 verify( $css )->stringContainsString( '.has-permanent-marker-font-family' );
 verify( $css )->stringContainsString( '.has-pacifico-font-family' );
 verify( $css )->stringContainsString( '.has-small-font-size' );
 verify( $css )->stringContainsString( '.has-medium-font-size' );
 verify( $css )->stringContainsString( '.has-large-font-size' );
 verify( $css )->stringContainsString( '.has-x-large-font-size' );
 // Font sizes.
 verify( $css )->stringContainsString( '.has-small-font-size' );
 verify( $css )->stringContainsString( '.has-medium-font-size' );
 verify( $css )->stringContainsString( '.has-large-font-size' );
 verify( $css )->stringContainsString( '.has-x-large-font-size' );
 // Colors.
 verify( $css )->stringContainsString( '.has-black-color' );
 verify( $css )->stringContainsString( '.has-black-background-color' );
 verify( $css )->stringContainsString( '.has-black-border-color' );
 verify( $css )->stringContainsString( '.has-black-color' );
 verify( $css )->stringContainsString( '.has-black-background-color' );
 verify( $css )->stringContainsString( '.has-black-border-color' );
 $this->checkCorrectThemeConfiguration();
 if ( wp_get_theme()->get( 'Name' ) === 'Twenty Twenty-One' ) {
 verify( $css )->stringContainsString( '.has-yellow-background-color' );
 verify( $css )->stringContainsString( '.has-yellow-color' );
 verify( $css )->stringContainsString( '.has-yellow-border-color' );
 }
 }
 public function testItCanTranslateFontSizeSlug() {
 verify( $this->theme_controller->translate_slug_to_font_size( 'small' ) )->equals( '13px' );
 verify( $this->theme_controller->translate_slug_to_font_size( 'medium' ) )->equals( '16px' );
 verify( $this->theme_controller->translate_slug_to_font_size( 'large' ) )->equals( '28px' );
 verify( $this->theme_controller->translate_slug_to_font_size( 'x-large' ) )->equals( '42px' );
 verify( $this->theme_controller->translate_slug_to_font_size( 'unknown' ) )->equals( 'unknown' );
 }
 public function testItCanTranslateColorSlug() {
 verify( $this->theme_controller->translate_slug_to_color( 'black' ) )->equals( '#000000' );
 verify( $this->theme_controller->translate_slug_to_color( 'white' ) )->equals( '#ffffff' );
 verify( $this->theme_controller->translate_slug_to_color( 'cyan-bluish-gray' ) )->equals( '#abb8c3' );
 verify( $this->theme_controller->translate_slug_to_color( 'pale-pink' ) )->equals( '#f78da7' );
 $this->checkCorrectThemeConfiguration();
 if ( wp_get_theme()->get( 'Name' ) === 'Twenty Twenty-One' ) {
 verify( $this->theme_controller->translate_slug_to_color( 'yellow' ) )->equals( '#eeeadd' );
 }
 }
 public function testItLoadsColorPaletteFromSiteTheme() {
 $this->checkCorrectThemeConfiguration();
 $settings = $this->theme_controller->get_settings();
 if ( wp_get_theme()->get( 'Name' ) === 'Twenty Twenty-One' ) {
 verify( $settings['color']['palette']['theme'] )->notEmpty();
 }
 }
 public function testItReturnsCorrectPresetVariablesMap() {
 $variable_map = $this->theme_controller->get_variables_values_map();
 verify( $variable_map['--wp--preset--color--black'] )->equals( '#000000' );
 verify( $variable_map['--wp--preset--spacing--20'] )->equals( '20px' );
 }
 private function checkCorrectThemeConfiguration() {
 $expected_themes = array( 'Twenty Twenty-One' );
 if ( ! in_array( wp_get_theme()->get( 'Name' ), $expected_themes, true ) ) {
 $this->fail( 'Test depends on using Twenty Twenty-One or Twenty Nineteen theme. If you changed the theme, please update the test.' );
 }
 }
}
