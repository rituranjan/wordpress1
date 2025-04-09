<?php
/*
Plugin Name: Service Popup
Description: Displays a Bootstrap-based pop-up on the homepage with a brief overview of services and a "Know More" button.
Version: 1.0
Author: Your Name
*/

// Enqueue Bootstrap CSS and JS
function service_popup_enqueue_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

    // Enqueue Bootstrap JS and its dependencies
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);

    // Enqueue custom CSS for the popup
    wp_enqueue_style('service-popup-style', plugins_url('style.css', __FILE__));

    // Enqueue custom JS for the popup
    wp_enqueue_script('service-popup-script', plugins_url('script.js', __FILE__), array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'service_popup_enqueue_scripts');
function service_popup_display() {
    // Check if it's the homepage
    if (is_front_page()) {
        ?>
        <!-- Bootstrap Modal -->
        <div class="modal fade" id="servicePopup" tabindex="-1" aria-labelledby="servicePopupLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="servicePopupLabel">Our Services</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Here’s a brief overview of our services. We offer a wide range of solutions to meet your needs.</p>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary">Know More</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script to Automatically Show the Modal -->
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#servicePopup').modal('show');
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'service_popup_display');