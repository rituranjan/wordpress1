<?php
/*
Template Name: Blank Template
Description: A custom template that shows only the page content with no header, footer, or sidebar.
*/

// Remove all actions for header, footer, and sidebar
remove_all_actions('wp_head');
remove_all_actions('wp_footer');
remove_all_actions('get_sidebar');

// Load only the page content
get_header(); // This is intentionally left empty to avoid loading the default header
?>

<div id="blank-page-content">
    <?php
    // Start the loop
    while (have_posts()) : the_post();
        the_content(); // Output the page content (including the shortcode)
    endwhile;
    ?>
</div>

<?php
get_footer(); // This is intentionally left empty to avoid loading the default footer
?>