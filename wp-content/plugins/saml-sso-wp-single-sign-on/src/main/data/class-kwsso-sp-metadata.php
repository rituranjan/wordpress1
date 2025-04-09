<?php

use KWSSO_CORE\Traits\Instance;
use LightSaml\SamlConstants;
use LightSaml\Model\Metadata\EntityDescriptor;
use LightSaml\Model\Metadata\AssertionConsumerService;
use LightSaml\Model\Metadata\SpSsoDescriptor;
use LightSaml\Model\Metadata\SingleLogoutService;
use LightSaml\Model\Metadata\Organization;
use LightSaml\Model\Metadata\ContactPerson;
use LightSaml\Model\Context\SerializationContext;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class KWSSO_SPMetadata
 *
 * This class manages the Service Provider (SP) metadata settings, specifically the base URL and entity ID.
 * It allows getting and setting these options, with values stored in WordPress options for persistence.
 */
class KWSSO_SPMetadata {

	use Instance;

	/**
	 * @var string|null $sp_base_url The base URL of the Service Provider.
	 */
	private $sp_base_url;
	/**
	 * @var string|null $sp_entity_id The entity ID of the Service Provider.
	 */
	private $sp_entity_id;
	/**
	 * @var string $sp_base_url_option Constant for the option key to store the SP base URL.
	 */
	private $orangization_details;
	private $sp_base_url_option;
	/**
	 * @var string $sp_entity_id_option Constant for the option key to store the SP entity ID.
	 */
	private $sp_entity_id_option;
	private $orangization_details_option;

	/**
	 * KWSSO_SPMetadata constructor.
	 *
	 * @param string|null $sp_base_url Optional. The base URL of the SP if provided, otherwise set to the stored option or default.
	 * @param string|null $sp_entity_id Optional. The entity ID of the SP if provided, otherwise set to the stored option or default.
	 */
	public function __construct( $sp_base_url = null, $sp_entity_id = null ) {
		$this->sp_base_url_option          = KwConstants::getConstant( 'SP_BASE_URL' );
		$this->sp_entity_id_option         = KwConstants::getConstant( 'SP_ENTITY_ID' );
		$this->orangization_details_option = KwConstants::getConstant( 'ORG_DETAILS' );
		$this->sp_base_url                 = $this->get_sp_base_url();
		$this->sp_entity_id                = $this->get_sp_entity_id();
		$this->orangization_details        = $this->get_organization_details();
	}
	/**
	 * Sets the base URL for the Service Provider and updates it in WordPress options.
	 *
	 * @param string $sp_base_url The base URL to set for the SP.
	 */
	public function set_sp_base_url( $sp_base_url ) {
		$this->sp_base_url = $sp_base_url;
		update_kwsso_option( $this->sp_base_url_option, $sp_base_url ); // Save in WordPress options
	}
	/**
	 * Gets the base URL of the Service Provider. If not set, retrieves it from WordPress options,
	 * defaulting to the home URL if no option is saved.
	 *
	 * @return string The base URL of the SP.
	 */
	public function get_sp_base_url() {
		if ( ! $this->sp_base_url ) {
			$this->sp_base_url = rtrim( get_kwsso_option( $this->sp_base_url_option, home_url() ), '/' ); // Default to home_url() if option not set
		}
		return $this->sp_base_url;
	}
	/**
	 * Sets the entity ID for the Service Provider and updates it in WordPress options.
	 *
	 * @param string $sp_entity_id The entity ID to set for the SP.
	 */
	public function set_sp_entity_id( $sp_entity_id ) {
		 $this->sp_entity_id = $sp_entity_id;
		update_kwsso_option( $this->sp_entity_id_option, $sp_entity_id ); // Save in WordPress options
	}
	/**
	 * Gets the entity ID of the Service Provider. If not set, retrieves it from WordPress options,
	 * defaulting to the base URL with the plugin path appended if no option is saved.
	 *
	 * @return string The entity ID of the SP.
	 */
	public function get_sp_entity_id() {
		if ( ! $this->sp_entity_id ) {
			$sp_base_url        = $this->get_sp_base_url(); // Ensure SP base URL is available
			$this->sp_entity_id = get_kwsso_option( $this->sp_entity_id_option, $sp_base_url . '/wp-content/plugins/keywoot-saml-sso/' );
		}
		return $this->sp_entity_id;
	}

	public function get_organization_details() {
		if ( ! $this->orangization_details ) {
			$this->orangization_details = get_kwsso_option( $this->orangization_details_option );
		}
		return $this->orangization_details;
	}
	public function set_organization_details( $orangization_details ) {
		 $this->orangization_details = $orangization_details;
		update_kwsso_option( $this->orangization_details_option, $orangization_details ); // Save in WordPress options
	}

	public function kwsso_process_sp_metadata( $download = false, $returnXMLString = false ) {
		$sp_base_url          = $this->get_sp_base_url();
		$sp_entity_id         = $this->get_sp_entity_id();
		$sp_acs_url           = $sp_base_url . '/';
		$organization_details = $this->get_organization_details();
		$orangization_name    = ! empty( $organization_details['organization_name'] ) ? $organization_details['organization_name'] : '';
		$orangization_url     = ! empty( $organization_details['organization_url'] ) ? $organization_details['organization_url'] : '';
		$contact_person_name  = ! empty( $organization_details['contact_person_name'] ) ? $organization_details['contact_person_name'] : '';
		$contact_person_email = ! empty( $organization_details['contact_person_email'] ) ? $organization_details['contact_person_email'] : '';

		if ( ! $returnXMLString ) {

			if ( ob_get_contents() ) {
				ob_clean();
			}

			header( 'Content-Type: text/xml' );
			if ( $download ) {
				header( 'Content-Disposition: attachment; filename="kw-sso-sp-metadata.xml"' );
			}
		}

		$entity_descriptor = new EntityDescriptor();
		$entity_descriptor
			->setEntityID( $sp_entity_id )
			->setValidUntil( '2029-10-22T10:07:10Z' );

		$sp_sso_descriptor = new SpSsoDescriptor();
		$sp_sso_descriptor
			->setWantAssertionsSigned( true )
			->setAuthnRequestsSigned( false );

		$sp_sso_descriptor->addNameIDFormat( SamlConstants::NAME_ID_FORMAT_UNSPECIFIED )
			->addNameIDFormat( SamlConstants::NAME_ID_FORMAT_EMAIL )
			->addNameIDFormat( SamlConstants::NAME_ID_FORMAT_PERSISTENT )
			->addNameIDFormat( SamlConstants::NAME_ID_FORMAT_TRANSIENT );

		$sp_sso_descriptor->addAssertionConsumerService(
			( new AssertionConsumerService() )
				->setBinding( SamlConstants::BINDING_SAML2_HTTP_POST )
				->setLocation( $sp_acs_url )
				->setIndex( 1 )
		);

		$sp_sso_descriptor->addSingleLogoutService(
			( new SingleLogoutService() )
				->setBinding( SamlConstants::BINDING_SAML2_HTTP_REDIRECT )
				->setLocation( $sp_base_url )
		);

		$entity_descriptor->addItem( $sp_sso_descriptor );

		if ( ! empty( $orangization_name ) && ! empty( $orangization_url ) ) {
			$entity_descriptor->addOrganization(
				$org = ( new Organization() )
					->setOrganizationName( $orangization_name )
					->setOrganizationDisplayName( $orangization_name )
					->setOrganizationURL( $orangization_url )
			);
		}
		if ( ! empty( $contact_person_name ) && ! empty( $contact_person_email ) ) {
			$entity_descriptor->addContactPerson(
				$contact = ( new ContactPerson() )
					->setContactType( 'technical' )
					->setGivenName( $contact_person_name )
					->setEmailAddress( $contact_person_email )
			);
		}
		$serialization_context = new SerializationContext();
		$entity_descriptor->serialize( $serialization_context->getDocument(), $serialization_context );
		if ( $returnXMLString ) {
			return $serialization_context->getDocument()->saveXML();
			exit;
		}
		echo $serialization_context->getDocument()->saveXML();
		exit;
	}
}
