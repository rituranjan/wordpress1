<?php
/**
 * This class loads all the classes.
 *
 * @package keywoot-saml-sso
 */

namespace KWSSO_CORE\Src;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AutoClassLoader' ) ) {
	/**
	 * This class loads all the classes.
	 */
	final class AutoClassLoader {
		/**
		 * Namespace value for the plugin.
		 *
		 * @var string
		 */
		private $kwsso_namespace;

		/**
		 * Include path.
		 *
		 * @var string
		 */
		private $kwsso_folder_path;

		/**
		 * Initilaizes values.
		 *
		 * @param string $kwsso_namespace namespace value.
		 * @param string $kwsso_folder_path directory path.
		 */
		public function __construct( $kwsso_namespace = null, $kwsso_folder_path = null ) {
			$this->kwsso_namespace   = $kwsso_namespace;
			$this->kwsso_folder_path = $kwsso_folder_path;
		}

		/**
		 * Includes all the class.
		 */
		public function register() {
			spl_autoload_register( array( $this, 'kwsso_autoinclude_class_file' ) );
		}

		/**
		 * Unregisters all the class.
		 */
		public function unregister() {
			spl_autoload_unregister( array( $this, 'kwsso_autoinclude_class_file' ) );
		}

		/**
		 * Checks if a class name is a namspace.
		 *
		 * @param string $class_name name of the class.
		 */
		private function check_classname_is_namespace( $kwsso_ns_separator, $class_name ) {
			return substr( $class_name, 0, strlen( $this->kwsso_namespace . $kwsso_ns_separator ) ) === $this->kwsso_namespace . $kwsso_ns_separator;
		}
		/**
		 * Loads all the class.
		 *
		 * @param string $class_name name of the class to include.
		 */
		public function kwsso_autoinclude_class_file( $class_name ) {
			$kwsso_ns_separator = '\\';
			if ( $this->kwsso_namespace !== null && ! $this->check_classname_is_namespace( $kwsso_ns_separator, $class_name ) ) {
				return;
			}

			$segments     = explode( $kwsso_ns_separator, $class_name );
			$class_name   = array_pop( $segments );
			$kwsso_namespace = strtolower( implode( DIRECTORY_SEPARATOR, $segments ) );

			$file_name = 'class-' . strtolower( str_replace( '_', '-', $class_name ) ) . '.php';
			$file_path = str_replace( 'kwsso_core', KWSSO_DIR_NAME, $kwsso_namespace ) . DIRECTORY_SEPARATOR . $file_name;

			$absolute_file_path = ( $this->kwsso_folder_path !== null ) ? $this->kwsso_folder_path . DIRECTORY_SEPARATOR . $file_path : $file_path;

			if ( file_exists( $absolute_file_path ) ) {
				require $absolute_file_path;
			}
		}


	}
}
