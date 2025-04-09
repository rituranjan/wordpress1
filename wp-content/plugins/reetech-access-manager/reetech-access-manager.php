<?php
/*
Plugin Name: Reetech Content Access Manager
Description: Custom login, registration, and content permission system
Version: 1.0
Author: Your Name
*/

// 1. ENQUEUE ASSETS
function reetech_enqueue_assets() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    
    // Custom CSS
    wp_enqueue_style('reetech-css', plugins_url('css/reetech.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'reetech_enqueue_assets');

// 2. CUSTOM LOGIN/SIGNUP SHORTCODES
function reetech_login_form($atts) {
    if (is_user_logged_in()) {
        return '<div class="alert alert-info">You are already logged in.</div>';
    }
    
    ob_start();
    ?>
    <div class="reetech-login-form">
        <h3>Login</h3>
        <?php wp_login_form(array(
            'redirect' => home_url(),
            'label_username' => 'Email',
            'remember' => true
        )); ?>
        <p class="text-center mt-3">
            <a href="<?php echo wp_lostpassword_url(); ?>">Forgot password?</a> | 
            <a href="#" data-bs-toggle="modal" data-bs-target="#reetechSignupModal">Create account</a>
        </p>
    </div>
    
    <!-- Signup Modal -->
    <div class="modal fade" id="reetechSignupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php echo reetech_signup_form(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('reetech_login', 'reetech_login_form');

function reetech_signup_form() {
    ob_start();
    ?>
    <form id="reetechSignupForm" method="post">
        <div class="mb-3">
            <label for="reetech_email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="reetech_username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="reetech_password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label for="reetech_role" class="form-label">Account Type</label>
            <select class="form-select" name="role">
                <option value="subscriber">Regular User</option>
                <option value="premium_member">Premium Member</option>
            </select>
        </div>
        <input type="hidden" name="action" value="reetech_signup">
        <?php wp_nonce_field('reetech_signup_nonce', 'reetech_signup_nonce'); ?>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <?php
    return ob_get_clean();
}

// 3. HANDLE SIGNUP
function reetech_handle_signup() {
    if (isset($_POST['action']) && $_POST['action'] === 'reetech_signup') {
        if (!wp_verify_nonce($_POST['reetech_signup_nonce'], 'reetech_signup_nonce')) {
            wp_die('Security check failed');
        }
        
        $email = sanitize_email($_POST['email']);
        $username = sanitize_user($_POST['username']);
        $password = $_POST['password'];
        $role = in_array($_POST['role'], ['subscriber', 'premium_member']) ? $_POST['role'] : 'subscriber';
        
        $user_id = wp_create_user($username, $password, $email);
        
        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role($role);
            
            // Auto-login
            wp_set_auth_cookie($user_id);
            wp_redirect(home_url());
            exit;
        } else {
            echo '<div class="alert alert-danger">' . $user_id->get_error_message() . '</div>';
        }
    }
}
add_action('init', 'reetech_handle_signup');

// 4. CUSTOM POST PERMISSIONS
function reetech_post_permissions($content) {
    if (!is_single() || is_admin()) {
        return $content;
    }
    
    $post_id = get_the_ID();
    $required_role = get_post_meta($post_id, '_reetech_required_role', true);
    
    if (empty($required_role)) {
        return $content;
    }
    
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">Please <a href="' . wp_login_url(get_permalink()) . '">login</a> to view this content.</div>';
    }
    
    $user = wp_get_current_user();
    if (!in_array($required_role, $user->roles)) {
        return '<div class="alert alert-danger">Your account does not have permission to view this content.</div>';
    }
    
    return $content;
}
add_filter('the_content', 'reetech_post_permissions');

// 5. ADD PERMISSION META BOX
function reetech_add_meta_boxes() {
    add_meta_box(
        'reetech_permissions',
        'Content Access',
        'reetech_permissions_meta_box',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'reetech_add_meta_boxes');

function reetech_permissions_meta_box($post) {
    $current_role = get_post_meta($post->ID, '_reetech_required_role', true);
    ?>
    <label for="reetech_required_role">Minimum role required:</label>
    <select name="reetech_required_role" id="reetech_required_role" class="form-select">
        <option value="">Everyone (including guests)</option>
        <option value="subscriber" <?php selected($current_role, 'subscriber'); ?>>Subscribers</option>
        <option value="premium_member" <?php selected($current_role, 'premium_member'); ?>>Premium Members</option>
        <option value="editor" <?php selected($current_role, 'editor'); ?>>Editors</option>
        <option value="administrator" <?php selected($current_role, 'administrator'); ?>>Administrators</option>
    </select>
    <?php
}

function reetech_save_post_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['reetech_required_role'])) {
        update_post_meta($post_id, '_reetech_required_role', sanitize_text_field($_POST['reetech_required_role']));
    }
}
add_action('save_post', 'reetech_save_post_meta');

// 6. ADD CUSTOM USER ROLE
function reetech_add_premium_role() {
    add_role('premium_member', 'Premium Member', array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false,
    ));
}
register_activation_hook(__FILE__, 'reetech_add_premium_role');

// 7. CREATE DEMO PAGES ON ACTIVATION
function reetech_create_demo_pages() {
    $pages = array(
        'Login' => '[reetech_login]',
        'Members Area' => 'This content is for members only.',
        'Premium Content' => 'This is exclusive premium content.'
    );
    
    foreach ($pages as $title => $content) {
        if (!get_page_by_title($title)) {
            $page_id = wp_insert_post(array(
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
            
            // Set permissions for demo pages
            if ($title === 'Members Area') {
                update_post_meta($page_id, '_reetech_required_role', 'subscriber');
            } elseif ($title === 'Premium Content') {
                update_post_meta($page_id, '_reetech_required_role', 'premium_member');
            }
        }
    }
}
register_activation_hook(__FILE__, 'reetech_create_demo_pages');