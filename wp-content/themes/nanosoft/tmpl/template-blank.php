<?php

/**
 * Prevent direct access to this file
 */
defined( 'ABSPATH' ) or die();

/**
 * Template Name: Page - Blank
 */
?>
<!DOCTYPE html>
<html <?php language_attributes() ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" name="viewport">
		<link rel="profile" href="//gmpg.org/xfn/11" />

		<?php wp_head() ?>
	</head>
	<body <?php body_class() ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">
		<?php wp_body_open(); ?>
		<?php do_action( 'theme/above_site_wrapper' ) ?>

		<div id="site" class="site">
			<?php while ( have_posts() ): the_post(); ?>
				<?php the_content() ?>
			<?php endwhile ?>
		</div>

		<?php wp_footer() ?>
	</body>
</html>