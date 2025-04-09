<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class KWSSO_IDPConf
 *
 * This class handles the configuration of the Identity Provider (IDP) for the KWSSO plugin.
 * It includes getters and setters for various IDP-related settings such as login URL,
 * issuer, NameID format, and certificates.
 */

class KWSSO_IDPConf {

	/**
	 * @var string|null $idp_name
	 * The name of the Identity Provider (IDP).
	 */
	private $idp_name;

	/**
	 * @var string|null $login_binding_type
	 * Specifies the binding type used for login, such as HTTP-Redirect or HTTP-POST.
	 */
	private $login_binding_type;

	/**
	 * @var string|null $login_url
	 * The URL of the Identity Provider's login endpoint.
	 */
	private $login_url;

	/**
	 * @var string|null $issuer
	 * The unique identifier for the Service Provider (SP), typically a URL.
	 */
	private $issuer;

	/**
	 * @var string|null $nameid_format
	 * Defines the format of the NameID, such as email address or persistent identifier.
	 */
	private $nameid_format;

	/**
	 * @var bool|null $request_signed
	 * Determines whether the SAML authentication request should be signed.
	 */
	private $request_signed;

	/**
	 * @var int|null $assertion_time_validation
	 * The amount of time (in seconds) that a SAML assertion is considered valid.
	 */
	private $assertion_time_validation;

	/**
	 * @var bool|null $is_encoding_enabled
	 * Indicates whether Base64 encoding should be enabled for SAML requests and responses.
	 */
	private $is_encoding_enabled;

	/**
	 * @var bool|null $assertion_signed
	 * Specifies whether the SAML assertion should be signed.
	 */
	private $assertion_signed;

	/**
	 * @var bool|null $saml_response_signed
	 * Specifies whether the SAML response should be signed by the IDP.
	 */
	private $saml_response_signed;

	/**
	 * @var string|null $x509_certificate
	 * The X.509 certificate provided by the Identity Provider for verifying signatures.
	 */
	private $x509_certificate;

	/**
	 * @var bool|null $is_idp_enabled
	 * Determines whether the Identity Provider (IDP) is enabled for authentication.
	 */
	private $is_idp_enabled;
	/**
	 * @var bool|null $is_idp_enabled
	 * Determines whether the Identity Provider (IDP) is enabled for authentication.
	 */
	private $idp_conf_option_key;
	/**
	 * @var bool|null $is_idp_enabled
	 * Determines whether the Identity Provider (IDP) is enabled for authentication.
	 */
	private $idp_metadata_url;


	/**
	 * Constructor for KWSSO_IDPConf.
	 *
	 * Initializes the IDP configuration with provided values or default values (null) if none are provided.
	 */

	/**
	 * Constructor for KWSSO_IDPConf.
	 */
	public function __construct() {
		$this->idp_conf_option_key = 'kwsso_idp_conf';
		$this->kwsso_load_idp_details_options();
	}

	/**
	 * Load values from WordPress options.
	 */
	public function kwsso_load_idp_details_options() {
		$options = get_kwsso_option( $this->idp_conf_option_key, array() );

		$this->idp_name                  = $options['idp_name'] ?? '';
		$this->login_binding_type        = $options['login_binding_type'] ?? '';
		$this->login_url                 = $options['login_url'] ?? '';
		$this->issuer                    = $options['issuer'] ?? '';
		$this->nameid_format             = $options['nameid_format'] ?? KwNameIdFormatConst::getFormat('UNSPECIFIED');
		$this->request_signed            = $options['request_signed'] ?? false;
		$this->assertion_time_validation = $options['assertion_time_validation'] ?? true;
		$this->is_encoding_enabled       = $options['is_encoding_enabled'] ?? false;
		$this->assertion_signed          = $options['assertion_signed'] ?? false;
		$this->saml_response_signed      = $options['saml_response_signed'] ?? false;
		$this->x509_certificate          = $options['x509_certificate'] ?? '';
		$this->is_idp_enabled            = $options['is_idp_enabled'] ?? '';
		$this->idp_metadata_url          = $options['idp_metadata_url'] ?? '';
	}

	/**
	 * Save values to WordPress options.
	 */
	public function save_to_options() {
		$options = array(
			'idp_name'                  => $this->idp_name,
			'login_binding_type'        => $this->login_binding_type,
			'login_url'                 => $this->login_url,
			'issuer'                    => $this->issuer,
			'nameid_format'             => $this->nameid_format,
			'request_signed'            => $this->request_signed,
			'assertion_time_validation' => $this->assertion_time_validation,
			'is_encoding_enabled'       => $this->is_encoding_enabled,
			'assertion_signed'          => $this->assertion_signed,
			'saml_response_signed'      => $this->saml_response_signed,
			'x509_certificate'          => $this->x509_certificate,
			'is_idp_enabled'            => $this->is_idp_enabled,
			'idp_metadata_url'          => $this->idp_metadata_url,
		);

		update_kwsso_option( $this->idp_conf_option_key, $options );
	}
	/**
	 * Get the IDP name.
	 *
	 * @return string|null
	 */
	public function get_idp_name() {
		return $this->idp_name;

	}

	/**
	 * Set the IDP name.
	 *
	 * @param string $idp_name
	 */
	public function set_idp_name( $idp_name ) {
		$this->idp_name = $idp_name;
		$this->save_to_options();
	}

	/**
	 * Get the login binding type.
	 *
	 * @return string|null
	 */
	public function get_login_binding_type() {
		return $this->login_binding_type;

	}

	/**
	 * Set the login binding type.
	 *
	 * @param string $login_binding_type
	 */
	public function set_login_binding_type( $login_binding_type ) {
		$this->login_binding_type = $login_binding_type;
		$this->save_to_options();
	}

	/**
	 * Get the login URL.
	 *
	 * @return string|null
	 */
	public function get_login_url() {
		return $this->login_url;
	}

	/**
	 * Set the login URL.
	 *
	 * @param string $login_url
	 */
	public function set_login_url( $login_url ) {
		$this->login_url = $login_url;
		$this->save_to_options();

	}

	/**
	 * Get the issuer.
	 *
	 * @return string|null
	 */
	public function get_issuer() {
		return $this->issuer;
	}

	/**
	 * Set the issuer.
	 *
	 * @param string $issuer
	 */
	public function set_issuer( $issuer ) {
		$this->issuer = $issuer;
		$this->save_to_options();

	}

	/**
	 * Get the NameID format.
	 *
	 * @return string|null
	 */
	public function get_nameid_format() {
		return $this->nameid_format;
	}

	/**
	 * Set the NameID format.
	 *
	 * @param string $nameid_format
	 */
	public function set_nameid_format( $nameid_format ) {
		$this->nameid_format = $nameid_format;
		$this->save_to_options();

	}

	/**
	 * Get whether the request is signed.
	 *
	 * @return bool|null
	 */
	public function get_request_signed() {
		return $this->request_signed;
	}

	/**
	 * Set whether the request is signed.
	 *
	 * @param bool $request_signed
	 */
	public function set_request_signed( $request_signed ) {
		$this->request_signed = $request_signed;
		$this->save_to_options();
	}

	/**
	 * Get assertion time validation.
	 *
	 * @return int|null
	 */
	public function get_assertion_time_validation() {
		return $this->assertion_time_validation;
	}

	/**
	 * Set assertion time validation.
	 *
	 * @param int $assertion_time_validation
	 */
	public function set_assertion_time_validation( $assertion_time_validation ) {
		$this->assertion_time_validation = $assertion_time_validation;
		$this->save_to_options();
	}

	/**
	 * Get whether encoding is enabled.
	 *
	 * @return bool|null
	 */
	public function get_is_encoding_enabled() {
		return $this->is_encoding_enabled;
	}

	/**
	 * Set whether encoding is enabled.
	 *
	 * @param bool $is_encoding_enabled
	 */
	public function set_is_encoding_enabled( $is_encoding_enabled ) {
		$this->is_encoding_enabled = $is_encoding_enabled;
		$this->save_to_options();
	}

	/**
	 * Get whether the assertion is signed.
	 *
	 * @return bool|null
	 */
	public function get_assertion_signed() {
		return $this->assertion_signed;
	}

	/**
	 * Set whether the assertion is signed.
	 *
	 * @param bool $assertion_signed
	 */
	public function set_assertion_signed( $assertion_signed ) {
		$this->assertion_signed = $assertion_signed;
		$this->save_to_options();
	}

	/**
	 * Get whether the SAML response is signed.
	 *
	 * @return bool|null
	 */
	public function get_saml_response_signed() {
		return $this->saml_response_signed;
	}

	/**
	 * Set whether the SAML response is signed.
	 *
	 * @param bool $saml_response_signed
	 */
	public function set_saml_response_signed( $saml_response_signed ) {
		$this->saml_response_signed = $saml_response_signed;
		$this->save_to_options();
	}

	/**
	 * Get the X.509 certificate.
	 *
	 * @return string|null
	 */
	public function get_x509_certificate() {
		return $this->x509_certificate;
	}

	/**
	 * Set the X.509 certificate.
	 *
	 * @param string $x509_certificate
	 */
	public function set_x509_certificate( $x509_certificate ) {
		$this->x509_certificate = $x509_certificate;
		$this->save_to_options();
	}

	/**
	 * Get whether the IDP is enabled.
	 *
	 * @return bool|null
	 */
	public function get_is_idp_enabled() {
		return $this->is_idp_enabled;
	}

	/**
	 * Set whether the IDP is enabled.
	 *
	 * @param bool $is_idp_enabled
	 */
	public function set_is_idp_enabled( $is_idp_enabled ) {
		$this->is_idp_enabled = $is_idp_enabled;
		$this->save_to_options();
	}

	public function get_idp_metadata_url() {
		return $this->idp_metadata_url;
	}

	public function set_idp_metadata_url( $idp_metadata_url ) {
		$this->idp_metadata_url = $idp_metadata_url;
		$this->save_to_options();
	}
}
