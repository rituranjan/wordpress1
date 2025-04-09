<?php
/**
 * Blank Template Wrapper
 * This displays only our plugin content with no WordPress theme elements
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
    // Load our CSS/JS
    reetech_group_enqueue_assets();
    wp_head(); 
    ?>
</head>
<body>
    <?php 
    // Output our shortcode content
    echo do_shortcode('[reetech_group]'); 
    wp_footer(); 
    ?>
</body>
</html>