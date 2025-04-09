<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$sp_base_url  = $sp_metadata->get_sp_base_url();
$sp_entity_id = $sp_metadata->get_sp_entity_id();

$kwsso_saml_nameid_format = ! empty( $idp_metadata->get_nameid_format() ) ? $idp_metadata->get_nameid_format() : KwNameIdFormatConst::getFormat('EMAIL');
$kwsso_request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; // phpcs:ignore -- false positive.

$service_provider_query = add_query_arg( array( 'tab' => 'save' ), $kwsso_request_uri );
$metadata_url           = $sp_base_url . '/?kwsso_action=kwsso-fetch-sp-metadata';

$upload_metadata_query = add_query_arg(
	array(
		'tab'    => 'save',
		'action' => 'upload_metadata',
	),
	$kwsso_request_uri
);

$sp_matadata_details = array(
	array(
		'label' => 'SP EntityID | Audience URI | Issuer',
		'id'    => 'sp_entity_id',
		'value' => esc_html( $sp_entity_id ),
	),
	array(
		'label' => 'Recipient URL | Destination URL | SLO URL',
		'id'    => 'sp_recipient_url',
		'value' => esc_url( $sp_base_url ) . '/',
	),
	array(
		'label' => 'Assertion Consumer Service URL (ACS URL)',
		'id'    => 'sp_acs_url',
		'value' => esc_url( $sp_base_url ) . '/',
	),
	array(
		'label' => 'NameID format',
		'id'    => 'sp_nameid_format',
		'value' => esc_html( $kwsso_saml_nameid_format ),
	),

);
$subtab              = isset( $_GET['subpage'] ) ? sanitize_text_field( wp_unslash( $_GET['subpage'] ) ) : 'kwsso-sp-metadata-details'; //phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the form name, doesn't require nonce verification.

$kwsso_sp_metadata_details_hidden = 'kwsso-sp-metadata-details' !== $subtab ? 'hidden' : '';
$kwsso_sp_metadata_xml_hidden     = 'kwsso-sp-metadata-xml' !== $subtab ? 'hidden' : '';
$kwsso_sp_core_conf_hidden        = 'kwsso-sp-core-conf' !== $subtab ? 'hidden' : '';

$sp_metadata_xml=$sp_metadata->kwsso_process_sp_metadata(false,true);
$dom = new DOMDocument('1.0', 'UTF-8');
libxml_use_internal_errors(true);
$dom->loadXML($sp_metadata_xml);
$dom->formatOutput = true;
$sp_metadata_xml= $dom->saveXML();

$organization_details = $sp_metadata->get_organization_details();
$organization_name = !empty($organization_details['organization_name']) ? $organization_details['organization_name'] : '';
$organization_url = !empty($organization_details['organization_url']) ? $organization_details['organization_url'] : '';
$contact_person_name = !empty($organization_details['contact_person_name']) ? $organization_details['contact_person_name'] : '';
$contact_person_email = !empty($organization_details['contact_person_email']) ? $organization_details['contact_person_email'] : '';
require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-sp-metadata-config.php';
