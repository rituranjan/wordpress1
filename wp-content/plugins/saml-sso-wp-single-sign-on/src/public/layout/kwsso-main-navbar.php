<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

echo '<div  class="kw-sidenav-container kw-main-content">';
foreach ( $tab_details->tab_details as $kwtabs ) {
    if ( $kwtabs->navbar_display ) {
        echo '<a class="kw-sidenav-item ' . ( $active_tab === $kwtabs->kwsso_menu_slug ? 'kw-sidenav-item-active' : '' ) . '" href="' . esc_url( $kwtabs->url ) . '" id="' . esc_attr( $kwtabs->id ) . '">
                <svg viewBox="0 0 24 24" fill="none" class="w-kw-icon h-kw-icon">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="' . esc_attr( $kwtabs->kwsso_tab_icon ) . '" fill="' . ( $active_tab === $kwtabs->kwsso_menu_slug ? 'blue' : '#1E1E1E' ) . '" />
                </svg>' . esc_attr( $kwtabs->kwsso_tab_name ) . '</a>';
    }
}
echo '<div class="flex flex-col gap-kw-2 justify-center pl-kw-1.5"><hr>
        <a class="kw-sidenav-item text-center" onClick="contactUsOnClick(\'Hi Keywoot Team, I Need Support Regarding\');" >' . esc_html( kwsso_lang_( 'Get Support' ) ) . '</a>
        <a class="kw-sidenav-item text-center" href="' . esc_url( $license_url ) . '">' . esc_html( kwsso_lang_( 'Check Pricing' ) ) . '</a>
        <a class="kw-sidenav-item text-center" style="cursor:pointer;" onClick="contactUsOnClick(\'Hi Keywoot Team, I have Custom Requirement\');">' . esc_html( kwsso_lang_( 'Custom Requirement' ) ) . '</a>
    </div></div>';
