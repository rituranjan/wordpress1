<?php


function my_custom_styles() {
	 // Enqueue CSS files
	wp_enqueue_style('animate', get_template_directory_uri() . '/assets/css/animate.min.css');
    wp_enqueue_style('animated-icons', get_template_directory_uri() . '/assets/css/animated-icons.css');
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
    wp_enqueue_style('components', get_template_directory_uri() . '/assets/css/components.css');
    wp_enqueue_style('custom-animation', get_template_directory_uri() . '/assets/css/custom-animation.css');
    wp_enqueue_style('fontawesome-all', get_template_directory_uri() . '/assets/css/fontawesome-all.min.css');
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css');
    wp_enqueue_style('nice-select', get_template_directory_uri() . '/assets/css/nice-select.css');
    wp_enqueue_style('slick', get_template_directory_uri() . '/assets/css/slick.css');
    wp_enqueue_style('slicknav', get_template_directory_uri() . '/assets/css/slicknav.css');
    wp_enqueue_style('style.map', get_template_directory_uri() . '/assets/css/style.map');
    wp_enqueue_style('themify-icons', get_template_directory_uri() . '/assets/css/themify-icons.css');
    wp_enqueue_style('top-section', get_template_directory_uri() . '/assets/css/top-section.css');
    wp_enqueue_style('customStyle', get_template_directory_uri() . '/assets/css/customStyle.css');
    wp_enqueue_style('owl.carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
    wp_enqueue_style('owl.carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css');
}

function my_custom_scripts() {	
    // Enqueue JavaScript files
    wp_enqueue_script('jquery-1.12.4.min', get_template_directory_uri() . '/assets/js/jquery-1.12.4.min.js', array('jquery'), null, false);
	wp_enqueue_script('modernizr-3.5.0.min', get_template_directory_uri() . '/assets/js/modernizr-3.5.0.min.js', array('jquery'), null, false);
    wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), null, true);
    wp_enqueue_script('contact-js', get_template_directory_uri() . '/assets/js/contact.js', array('jquery'), null, true);
    wp_enqueue_script('ajaxchimp-js', get_template_directory_uri() . '/assets/js/jquery.ajaxchimp.min.js', array('jquery'), null, true);
    wp_enqueue_script('counterup-js', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js', array('jquery'), null, true);
    wp_enqueue_script('form-js', get_template_directory_uri() . '/assets/js/jquery.form.js', array('jquery'), null, true);
    wp_enqueue_script('magnific-popup-js', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.js', array('jquery'), null, true);
    wp_enqueue_script('nice-select-js', get_template_directory_uri() . '/assets/js/jquery.nice-select.min.js', array('jquery'), null, true);
	wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), null, false);
    wp_enqueue_script('slicknav-js', get_template_directory_uri() . '/assets/js/jquery.slicknav.min.js', array('jquery'), null, true);
    wp_enqueue_script('validate-js', get_template_directory_uri() . '/assets/js/jquery.validate.min.js', array('jquery'), null, true);
    wp_enqueue_script('mail-script-js', get_template_directory_uri() . '/assets/js/mail-script.js', array('jquery'), null, true);
    wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array('jquery'), null, true);
    wp_enqueue_script('plugins-js', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), null, true);
    wp_enqueue_script('popper-js', get_template_directory_uri() . '/assets/js/popper.min.js', array('jquery'), null, true);
    wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/js/slick.min.js', array('jquery'), null, false);
    wp_enqueue_script('waypoints-js', get_template_directory_uri() . '/assets/js/waypoints.min.js', array('jquery'), null, true);
    wp_enqueue_script('wow-js', get_template_directory_uri() . '/assets/js/wow.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'my_custom_scripts');
add_action('wp_enqueue_scripts', 'my_custom_styles');

add_action('wp_ajax_send_newsletter_email', 'send_newsletter_email');
add_action('wp_ajax_nopriv_send_newsletter_email', 'send_newsletter_email');

function send_newsletter_email() {
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $subscriber_email = sanitize_email($_POST['email']);
		
        // Validate the email format
        if (!is_email($subscriber_email)) {
            echo 'Invalid email address.';
            wp_die();
        }
		
		$subject = 'Reetech Newsletter Subscription';
        $recipient_email = 'testerucodice@gmail.com';
        $recipient_message = "<html><head><title>New Subscriber</title><style>body{font-family:Arial,sans-serif;color:#333;line-height:1.6}.header{background-color:#f4f4f4;padding:10px;text-align:center}.content{padding:20px}.footer{font-size:.8em;text-align:center;color:#888}</style></head><body><div class='header'><h1>🎉 New Subscription Alert! 🎉</h1></div><div class='content'><p>Hi there!</p><p>A new user has subscribed to our newsletter.</p><p><strong>Email: </strong>$subscriber_email</p></div><div class='footer'><p>&copy; " . date('Y') . " Rudra Enterprise LLC. 2024. All rights reserved.</p></div></body></html>";
		
		$subscriber_message = "<html><head><title>New Subscriber</title><style>body{font-family:Arial,sans-serif;color:#333;line-height:1.6}.header{background-color:#f4f4f4;padding:10px;text-align:center}.content{padding:20px}.footer{font-size:.8em;text-align:center;color:#888}</style></head><body><div class='header'><h1>🎉 New Subscription Alert! 🎉</h1></div><div class='content'><p>Hi there!</p><p>We are excited that you have subscribed to our newsletter.</p><p>Thank you for being a part of our community! We look forward to sharing our latest updates and exclusive content with you.</p><p>Best regards,<br>Reetech Team</p></div><div class='footer'><p>&copy; " . date('Y') . " Rudra Enterprise LLC. 2024. All rights reserved.</p></div></body></html>";
        
		// Set the sender name and email
		$headers = array(
			'From: Reetech <info@reetechusa.com>',
			'Content-Type: text/html; charset=UTF-8'
		);
		
		$subscriber_send = wp_mail($subscriber_email, $subject, $subscriber_message, $headers);
		$recipient_send = wp_mail($recipient_email, $subject, $recipient_message, $headers);
		if ($subscriber_send && $recipient_send) {
            echo 'Subscribed successfully!';
        } else {
//             echo 'Email sending failed.';
        }

    } else {
        echo 'Email address is required.';
    }

    wp_die(); // This is required to terminate immediately and return a proper response
}

?>