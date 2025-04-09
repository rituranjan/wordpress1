<?php
/**
 * To use global instance varible for all classes.
 *
 * @package keywoot-saml-sso
 */

namespace KWSSO_CORE\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
trait Instance {
	/** Global object declaration
	 *
	 * @var instance to use global instance varible for all classes.
	 **/
	private static $instance = null;
	/** Function to delcare defination of instance as triats
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
