<?php
/**
 * Defines the NameID Constant class used throughout the plugin.
 *
 * @package keywoot-saml-sso\assets\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines constants for NameID Formats in Service Provider Setup tab.
 */
class KwNameIdFormatConst {

	/**
	 * Prefix for the NameID formats.
	 *
	 * @var string
	 */
	private static $prefix = 'urn:oasis:names:tc:SAML:';

	/**
	 * Holds the NameID format constants.
	 *
	 * @var array
	 */
	private static $formats = array(
		'EMAIL'       => '1.1:nameid-format:emailAddress',
		'UNSPECIFIED' => '1.1:nameid-format:unspecified',
		'TRANSIENT'   => '2.0:nameid-format:transient',
		'PERSISTENT'  => '2.0:nameid-format:persistent',
	);

	/**
	 * Retrieves a NameID format by key.
	 *
	 * @param string $key The key of the NameID format.
	 * @return string|null The NameID format with the prefix if the key exists, null otherwise.
	 */
	public static function getFormat( $key ) {
		return isset( self::$formats[ $key ] ) ? self::$prefix . self::$formats[ $key ] : null;
	}

	/**
	 * Retrieves all NameID formats with the prefix.
	 *
	 * @return array The array of all NameID formats with the prefix.
	 */
	public static function getAllFormats() {
		$allFormats = array();
		foreach ( self::$formats as $key => $format ) {
			$allFormats[ $key ] = self::$prefix . $format;
		}
		return $allFormats;
	}
}
