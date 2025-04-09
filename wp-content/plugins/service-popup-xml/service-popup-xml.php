<?php
/*
Plugin Name: Service Popup with XML Data
Description: Displays a Bootstrap-based pop-up on the homepage with data fetched from an XML file.
Version: 1.0
Author: Your Name
*/

// Enqueue Bootstrap CSS and JS
function service_popup_xml_enqueue_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

    // Enqueue Bootstrap JS and its dependencies
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);

    // Enqueue custom CSS for the popup
    wp_enqueue_style('service-popup-xml-style', plugins_url('style.css', __FILE__));

    // Enqueue custom JS for the popup
    wp_enqueue_script('service-popup-xml-script', plugins_url('script.js', __FILE__), array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'service_popup_xml_enqueue_scripts');

function service_popup_xml_display() {
    // Check if it's the homepage
    if (is_front_page()) {
        // Load the XML file
        $xml = simplexml_load_file(plugin_dir_path(__FILE__) . 'data.xml');

        if ($xml) {
            ?>
            <!-- Bootstrap Modal -->
            <div class="modal fade" id="servicePopup" tabindex="-1" aria-labelledby="servicePopupLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="servicePopupLabel">Reetech Services</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <?php
                                $count = 0;
                                foreach ($xml->service as $service):
                                    $count++;
                                    ?>
                                    <div class="col-md-6 mb-6">
                                        <div class="service-item">
                                            <h4><?php echo esc_html($service->title); ?></h4>
                                            <p><?php echo esc_html($service->description); ?></p>
                                            <ul class="submenu">
                                                <?php foreach ($service->subheadings->subheading as $subheading): ?>
                                                    <li><a href="<?php echo esc_url($subheading->link); ?>"><?php echo esc_html($subheading->name); ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <!-- <a href="<?php echo esc_url($service->redirect_link); ?>" class="btn btn-primary">Know More</a> -->
                                        </div>
                                    </div>
                                    <?php
                                    // Start a new row after every 3 columns
                                    if ($count % 2 === 0) {
                                        echo '</div><div class="row">';
                                    }
                                endforeach;
                                ?>
                            </div>
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
}
add_action('wp_footer', 'service_popup_xml_display');