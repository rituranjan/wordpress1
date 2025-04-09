<?php
/**
 * This file contains a service for metadata import.
 *
 * @package keywoot-saml-sso/service
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use LightSaml\Model\Context\DeserializationContext;
use LightSaml\Model\Metadata\EntityDescriptor;
use LightSaml\Model\Metadata\IdpSsoDescriptor;
use LightSaml\SamlConstants;
use LightSaml\Model\Metadata\KeyDescriptor;
/**
 * Class to handle metadata import and catch any exceptions that are thrown.
 */
class KwMetadataHandlerService {

	/**
	 * Stores KwMetadataHandlerService object.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Returns KwMetadataHandlerService class object.
	 */
	public static function kwsso_get_instance(): self {
		return self::$instance ??= new self();
	}

	/**
	 * Handles the upload metadata flow and schedules required cron job.
	 *
	 * @return void
	 */
	public function kwsso_handle_upload_or_fetch_metadata(): void {
		$metadata_url = get_value_from_post( 'metadata_url' );
		if ( empty( $_FILES[ 'idp_metadata_file' ] ) && empty( $metadata_url ) ) {
			return;
		}
		$file = ! empty( $_FILES[ 'idp_metadata_file' ][ 'tmp_name' ] ) ? @file_get_contents( $_FILES[ 'idp_metadata_file' ][ 'tmp_name' ] ) : $this->kwsso_fetch_metadata_from_url( $metadata_url );
		if ( $file ) {
			$this->kwsso_save_imported_metadata( $file );
		}
	}
	/**
	 * Fetches metadata content from the provided URL.
	 *
	 * @return string|false Metadata content fetched from URL or false on failure
	 */
	private function kwsso_fetch_metadata_from_url( $metadata_url ) {
		if ( ! kwsso_is_extension_installed( 'curl' ) ) {
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('CURL_DISABLED'), KWSSO_ERROR );
			return '';
		}
		$url      = filter_var( htmlspecialchars( $metadata_url ), FILTER_SANITIZE_URL );
		$response = KWSSO_Utils::keywoot_wp_remote_call( $url, array( 'sslverify' => false ), true );
		if ( ! $response ) {
			return '';
		} else {
			return $response;
		}
	}

	private function get_idp_sso_login_url( IdpSsoDescriptor $idp_sso_descriptor ) {
		$services = $idp_sso_descriptor->getAllSingleSignOnServicesByBinding( SamlConstants::BINDING_SAML2_HTTP_REDIRECT );
		return ! empty( $services ) ? $services[0] : null;
	}

	private function get_signing_key_descriptors( array $key_descriptors ): ?array {
		$signing_key_descriptors = array();

		foreach ( $key_descriptors as $descriptor ) {
			if ( $this->is_signing_key_descriptor( $descriptor ) ) {
				$signing_key_descriptors[] = $descriptor;
			}
		}

		return ! empty( $signing_key_descriptors ) ? $signing_key_descriptors : null;
	}

	private function is_signing_key_descriptor( KeyDescriptor $descriptor ): bool {
		return $descriptor->getUse() === 'signing';
	}


	/**
	 * Writes IDP metadata to db by parsing the XML metadata file.
	 *
	 * @param mixed|string $file XML file containing IDP metadata.
	 * @return void
	 */
	public function kwsso_save_imported_metadata( $xml_content ) {
		if ( ! class_exists( 'DOMDocument' ) ) {
			KWSSO_Display::kwsso_display_admin_notice(  'DOMDocument Not Installed.', KWSSO_ERROR );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Exception from plugin, escaping not needed.
			return;
		}
		libxml_use_internal_errors( true );
		$entity_descriptor = $this->kwsso_parse_xml_content( $xml_content );
		if ( ! $entity_descriptor ) {
			return;
		}
		$errors = $this->get_entity_descriptor_error( $entity_descriptor );
		if ( count( $errors ) ) {
			KWSSO_Display::kwsso_display_admin_notice( 'Metadata validation failed. Issues: ' . implode( ', ', $errors ), KWSSO_ERROR );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Exception from plugin, escaping not needed.
			return;
		}
		$metadata = $this->extract_metadata_from_descriptor( $entity_descriptor );
		if ( ! $metadata ) {
			return;
		}

		$idp_configuration      = new KWSSO_IDPConf();
		$identity_name          = sanitize_text_field( get_value_from_post( 'kwsso_saml_idp_name', $idp_configuration->get_idp_name() ) );
		$kwsso_idp_metadata_url = esc_url_raw( trim( get_value_from_post( 'metadata_url' ) ) );
		$kwsso_idp_key        = sanitize_text_field( get_value_from_post('kwsso_saml_idp_key','custom-idp'  ) );

		$idp_configuration->set_idp_name( $identity_name );
		$idp_configuration->set_login_url( $metadata['login_url'] );
		$idp_configuration->set_login_binding_type( $metadata['binding_type'] );
		$idp_configuration->set_issuer( $metadata['entity_id'] );
		$idp_configuration->set_nameid_format( $metadata['nameid_format'] );
		$idp_configuration->set_request_signed( false );
		$idp_configuration->set_is_idp_enabled( true );
		$idp_configuration->set_assertion_time_validation( true );
		$idp_configuration->set_idp_metadata_url( $kwsso_idp_metadata_url );
		update_kwsso_option( KwConstants::getConstant('KWSSO_IDP_KEY'),$kwsso_idp_key );

		if ( ! KWSSO_Utils::check_and_update_x509_certifciate( $idp_configuration, $metadata['x509_certificate'] ) ) {
			return;
		}
		KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('IDP_CONF_SAVED'), KWSSO_SUCCESS );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Exception from plugin, escaping not needed.

	}

	/**
	 * Extract metadata from entity descriptor
	 *
	 * @param EntityDescriptor $entity_descriptor Entity descriptor
	 * @return array|null
	 */
	private function extract_metadata_from_descriptor( EntityDescriptor $entity_descriptor ): ?array {
		$first_idp_sso_descriptor = $entity_descriptor->getFirstIdpSsoDescriptor();

		if ( ! $first_idp_sso_descriptor ) {
			return null;
		}

		return array(
			'entity_id'         => $entity_descriptor->getEntityID(),
			'login_url'         => $this->get_idp_sso_login_url( $first_idp_sso_descriptor )->getLocation(),
			'x509_certificate'  => $this->extract_x509_certificate( $entity_descriptor ),
			'binding_type'      => ! empty( $this->get_binding_types( $first_idp_sso_descriptor )['HTTP-POST'] ) ? 'HTTP-POST' : 'HTTP-Redirect',
			'nameid_format'     => $this->get_nameid_format( $first_idp_sso_descriptor ),
			'is_request_signed' => ( $first_idp_sso_descriptor->getWantAuthnRequestsSigned() === 'true' ) ? 'checked' : 'unchecked',
		);
	}

	private function extract_x509_certificate( $entity_descriptor ) {
		$x509_certificate = $this->get_signing_key_descriptors( $entity_descriptor->getAllIdpKeyDescriptors() );
		$x509_certificate =
			array_unique(
				array_map(
					function ( $key_descriptor ) {
						return KWSSO_Utils::kwsso_sanitize_certificate( $key_descriptor->getCertificate()->getData() );
					},
					$x509_certificate
				)
			);
		return $x509_certificate;
	}

	private function kwsso_parse_xml_content( string $xml_content ) {
		$deserialization_context = new DeserializationContext();
		if ( ! $deserialization_context->getDocument()->loadXML( $xml_content ) || libxml_get_errors() ) {
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::getMessage('INVALID_FILE_OR_URL'), KWSSO_ERROR );
			return false;
		}

		$entity_descriptor = new EntityDescriptor();
		$entity_descriptor->deserialize( $deserialization_context->getDocument()->firstChild, $deserialization_context );

		return $entity_descriptor;
	}

	private function get_nameid_format( IdpSsoDescriptor $first_idp_sso_descriptor ) {
		$name_id_formats = $first_idp_sso_descriptor->getAllNameIDFormats();

		if ( ! empty( $name_id_formats ) && count( $name_id_formats ) === 1 ) {
			$allowed_name_id_formats = KwNameIdFormatConst::getAllFormats();
			if ( in_array( $name_id_formats[0], $allowed_name_id_formats ) ) {
				return $name_id_formats[0];
			}
		}
		return KwNameIdFormatConst::getFormat('UNSPECIFIED');
	}

	private function get_entity_descriptor_error( $entity_descriptor ) {
		if ( ! $entity_descriptor ) {
			return array( 'Invalid Entity Descriptor' );
		}
		$error_checks = array(
			'EntityID or Issue' => $entity_descriptor->getEntityID(),
			'Login URL'         => $entity_descriptor->getFirstIdpSsoDescriptor(),
			'Certificate'       => $entity_descriptor->getAllIdpKeyDescriptors(),
		);
		$errors = array();
		foreach ( $error_checks as $error_message => $check ) {
			if ( ! $check ) {
				$errors[] = "<strong>{$error_message}</strong>";
			}
		}
		return $errors;
	}


	private function get_binding_types( IdpSsoDescriptor $first_idp_sso_descriptor ): array {
		$binding_types = array();
		foreach ( $first_idp_sso_descriptor->getAllSingleSignOnServices() as $sso_service ) {
			$binding                   = str_replace( 'urn:oasis:names:tc:SAML:2.0:bindings:', '', $sso_service->getBinding() );
			$binding_types[ $binding ] = $binding;
		}
		return $binding_types;
	}
}
