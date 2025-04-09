<?php
/**
 * This file validates the response recieved from the IDP
 *
 * @package keywoot-saml-sso/service
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use LightSaml\Model\Assertion\Assertion;
use LightSaml\Model\Protocol\StatusCode;
use Symfony\Component\HttpFoundation\Request;
use LightSaml\Binding\BindingFactory;
use LightSaml\Context\Profile\MessageContext;
use LightSaml\Model\Protocol\Response;
use LightSaml\Model\XmlDSig\SignatureXmlReader;
use LightSaml\Credential\KeyHelper;
use LightSaml\Credential\X509Certificate;
use LightSaml\Model\Context\DeserializationContext;


class KWSSO_ResponseValidator {

	private $response;
	private $assertion;
	private $assertion_type;
	private $name_id_format;
	private $name_id_value;
	private $attributes;
	private $last_error;
	private $session_index;
	private $idp_configuration;
	private $sp_metadata;


	public function __construct() {
		$this->response          = $this->kwsso_get_saml_response();
		$this->idp_configuration = new KWSSO_IDPConf();
		$this->sp_metadata       = new KWSSO_SPMetadata();
	}
	private function kwsso_get_saml_response() {
		$request         = Request::createFromGlobals();
		$message_context = new MessageContext();
		$binding_factory = new BindingFactory();

		$binding = $binding_factory->getBindingByRequest( $request );
		$binding->receive( $request, $message_context );

		return $message_context->asResponse();
	}

	public function kwsso_validate_response() {
		$this->kwsso_validate_saml_response_status_code();
		$this->kwsso_validate_response_issuer();
		$this->kwsso_validate_destination();
		$this->kwsso_validate_issueInstant();
		$this->kwsso_parse_assertion();
		$this->kwsso_validate_assertion();
	}

	private function kwsso_validate_saml_response_status_code() {
		$status      = $this->response->getStatus();
		$status_code = $status->getStatusCode();
		if ( $status_code->getValue() !== 'urn:oasis:names:tc:SAML:2.0:status:Success' ) {
			$status_message = $status->getStatusMessage();
			KWSSO_Display::kwsso_throw_error( 'ERR_AUTHENTICATION_FAILED', 'SAML Response returned an error: ' . ( $status_message ?: 'Unknown error' ) );
		}
	}

	private function kwsso_validate_response_issuer() {
		$issuer            = $this->response->getIssuer()->getValue();
		$kwsso_saml_issuer = $this->idp_configuration->get_issuer();
		if ( $issuer !== $kwsso_saml_issuer ) {
			KWSSO_Display::kwsso_throw_error( 'ERR_ISSUER_MISMATCH', "Issuer Confgired in Plugin :  $kwsso_saml_issuer, Issuer found in saml response: $issuer" );
		}
	}

	private function kwsso_validate_destination() {
		$destination          = $this->response->getDestination();
		$sp_base_url          = $this->sp_metadata->get_sp_base_url();
		$expected_destination = $sp_base_url . '/';
		if ( $destination !== $expected_destination ) {
			KWSSO_Display::kwsso_throw_error( 'ERR_DESTINATION_URL_MISMATCH', "Invalid Destination: $destination , The Detination Should be $expected_destination" );
		}
	}

	private function kwsso_validate_issueInstant() {
		$current_time  = time();
		$issue_instant = $this->response->getIssueInstantTimestamp();
		if ( abs( $current_time - $issue_instant ) > 300 ) {
			KWSSO_Display::kwsso_throw_error( 'ERR_INVALID_TIMESTAMP', 'Outdated SAML response detected: the response timestamp is either outdated or set for a future time.' );
		}
	}


	private function kwsso_parse_assertion(): void {
		if ( $this->response->getFirstEncryptedAssertion() ) {
			KWSSO_Display::kwsso_throw_error( 'ERR_ENCRYPTED_RESPONSE', 'Encrypted Response Detected' );
		}

		if ( $this->response->getFirstAssertion() ) {
			$this->assertion      = $this->response->getFirstAssertion();
			$this->assertion_type = 'encrypted';
			return;
		}
		KWSSO_Display::kwsso_throw_error( 'ERR_ASSERTION_NOT_DETECTED', 'Unable to find saml assertion' );
	}

	private function kwsso_validate_assertion() {

		if ( ! $this->kwsso_validate_signature( $this->assertion, $this->response ) ) {
			KWSSO_Display::kwsso_throw_error( 'ERR_CERTIFICATE_MISMATCH', 'Invalid Signature' );
		}
		$this->validate_assertion_conditions();
		$this->kwsso_validate_saml_response_for_reply_attack( $this->assertion );
		$this->kwsso_validate_inResponseTo();

		if ( ! $name_id_format = $this->assertion->getSubject()->getNameID() ) {
			KWSSO_Display::kwsso_throw_error( 'ERR_MISSING_NAMEID_ATTRIBUTE', 'NameID Not Found' );
		}

		$this->name_id_format = $name_id_format->getFormat();
		$this->name_id_value  = $name_id_format->getValue();
		$this->session_index  = $this->assertion->getFirstAuthnStatement()->getSessionIndex();
		$this->kwsso_retrieve_attributes();

	}

	private function kwsso_retrieve_attributes() {
		$attribute_statement = $this->assertion->getFirstAttributeStatement();
		$this->attributes    = array();

		if ( ! empty( $attribute_statement ) ) {
			foreach ( $attribute_statement->getAllAttributes() as $attribute ) {
				$name                      = $this->get_last_name_part( $attribute->getName() );
				$this->attributes[ $name ] = $this->get_attribute_value( $attribute );
			}
		}
	}

	private function validate_assertion_conditions() {
		$conditions = $this->assertion->getConditions();
		if ( ! $conditions ) {
			KWSSO_Display::kwsso_throw_error( 'ERR_CONDITION_STATEMENT_MISSING', 'Conditions element is missing in the assertion.' );
		}
		$this->validate_assetion_time_validity( $conditions );
		$this->kwsso_validate_audience( $conditions );

	}
	private function validate_assetion_time_validity( $conditions ) {
		$assertion_time_validation = $this->idp_configuration->get_assertion_time_validation();
		if ( empty( $assertion_time_validation ) || $assertion_time_validation ) {
			$current_time = time();

			if ( $conditions->getNotBeforeTimestamp() ) {
				if ( $current_time < $conditions->getNotBeforeTimestamp() ) {
					KWSSO_Display::kwsso_throw_error( 'ERR_CONDITION_TIME_VALIDITY', 'Assertion is not valid yet (NotBefore condition).' );
				}
			}
			if ( $conditions->getNotOnOrAfterTimestamp() ) {
				if ( $current_time >= $conditions->getNotOnOrAfterTimestamp() ) {
					KWSSO_Display::kwsso_throw_error( 'ERR_CONDITION_TIME_VALIDITY', 'Assertion has expired (NotOnOrAfter condition).' );
				}
			}
		}

	}

	private function kwsso_validate_audience( $conditions ) {
		$audienceRestrictions = $conditions->getAllAudienceRestrictions();
		$sp_entity_id         = $this->sp_metadata->get_sp_entity_id();

		foreach ( $audienceRestrictions as $audienceRestriction ) {
			if ( $audienceRestriction->hasAudience( $sp_entity_id ) ) {
				return;
			}
		}
		KWSSO_Display::kwsso_throw_error( 'ERR_AUDIENCE_URI_MISMATCH', 'Invalid Audience URI' );
	}

	private function kwsso_validate_signature( ?Assertion $assertion, ?\LightSaml\Model\Protocol\Response $response ): bool {
		$idp_saved_certificate = $this->idp_configuration->get_x509_certificate();
		// $idp_saved_certificate = maybe_unserialize( $idp_saved_certificate );
		if ( is_array( $idp_saved_certificate ) ) {
			$idp_saved_certificate = reset( $idp_saved_certificate );
		}

		return $this->kwsso_validate_certificate( $assertion, $response, $idp_saved_certificate );
	}
	/**
	 * Helper function to ensure the certificate is in proper PEM format.
	 */
	private function kwsso_format_certificate( $cert ) {
		$cert = trim( $cert );
		// Check if the certificate already contains the header and footer
		if ( strpos( $cert, '-----BEGIN CERTIFICATE-----' ) === false ) {
			$cert = "-----BEGIN CERTIFICATE-----\n" . chunk_split( $cert, 64, "\n" ) . "-----END CERTIFICATE-----\n";
		}

		return $cert;
	}
	private function kwsso_validate_certificate( ?Assertion $assertion, ?\LightSaml\Model\Protocol\Response $response, $cert ): bool {
		try {
			$cert     = $this->kwsso_format_certificate( $cert );
			$x509Cert = new \LightSaml\Credential\X509Certificate();
			$x509Cert->loadPem( $cert );

			$key             = \LightSaml\Credential\KeyHelper::createPublicKey( $x509Cert );
			$signatureReader = $assertion->getSignature() ?? $response->getSignature();

			return $signatureReader->validate( $key );
		} catch ( \Exception $ex ) {
			error_log( $ex->getMessage() );
			return false;
		}
	}

	private function kwsso_validate_name_id_format( ?Assertion $assertion ): bool {
		$sp_name_id_format       = get_kwsso_option( KwConstants::getConstant('NAMEID_FORMAT') );
		$name_id_format_from_idp = $assertion->getSubject()->getNameID()->getFormat();
		return strtolower( $sp_name_id_format ) === strtolower( $name_id_format_from_idp );
	}

	private function kwsso_reduce_attributes( array $attributes ): array {

	}

	// Function to get the last part of the attribute name
	private function get_last_name_part( string $name ): string {
		$name_parts = explode( '/', $name );
		return end( $name_parts );
	}

	// Function to get either a single or multiple attribute values
	private function get_attribute_value( $attribute ) {
		$values = $attribute->getAllAttributeValues();
		return count( $values ) > 1 ? $values : $attribute->getFirstAttributeValue();
	}




	public function kwsso_validate_saml_response_for_reply_attack( Assertion $assertion ) {

		$conditions      = $assertion->getConditions();
		$not_on_or_after = $conditions ? $conditions->getNotOnOrAfterTimestamp() : null;
		$assertion_id    = $assertion->getId();
		$transient_key   = 'saml_response_assertion_id' . $assertion_id;
		$expiry          = $not_on_or_after ? ( $not_on_or_after - time() ) + 300 : 900;  // Default expiry 6000 seconds
		if ( false === get_transient( $transient_key ) ) {
			set_transient( $transient_key, 'assertion_used', $expiry );
		} else {
			KWSSO_Display::kwsso_throw_error( 'ERR_DUPLICATE_RESPONSE', 'Response Already Being Validated Once.' );
		}
	}

	public function kwsso_validate_inResponseTo() {
		$in_response_to  = $this->response->getInResponseTo();
		$saml_request_id = KWSSO_SessionStore::get_store_value( 'saml_request_id' );
		if ( $in_response_to !== $saml_request_id ) {
			KWSSO_Display::kwsso_throw_error( 'ERR_MISMATCHED_REQUEST_ID', 'Invalid Response' );
		}
		KWSSO_SessionStore::delete_store_value( 'saml_request_id' );
	}



	private function kwsso_decrypt_assertion( ?\LightSaml\Model\Assertion\EncryptedElement $assertion ): ?Assertion {
		$credential = new \LightSaml\Credential\X509Credential(
			\LightSaml\Credential\X509Certificate::asString( 'sp_cert' ),
			\LightSaml\Credential\KeyHelper::createPrivateKey( 'key' )
		);

		$decrypt_deserialize_context = new DeserializationContext();
		$reader                      = $assertion;
		try {
			return $reader->decryptMultiAssertion( array( $credential ), $decrypt_deserialize_context );
		} catch ( \Exception $ex ) {
			KWSSO_Display::kwsso_throw_error( 'Invalid decryption certificate' );

			return null;
		}
	}

	public function kwsso_get_name_id_value(): string {
		return $this->name_id_value;
	}

	public function kwsso_get_attributes(): array {
		return $this->attributes;
	}

}
