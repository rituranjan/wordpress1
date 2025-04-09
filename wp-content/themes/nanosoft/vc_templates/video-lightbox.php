<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$atts['url'].= "&width={$atts['width']}&height={$atts['height']}";
?>
<a class="video-lightbox" rel="prettyPhoto" href="<?php echo esc_url( $atts['url'] ) ?>">
    <?php if ( ! empty( $atts['cover'] ) ): ?>
        <?php echo wp_get_attachment_image( $atts['cover'], 'full' ) ?>
    <?php endif ?>

    <?php if ( ! empty( $atts['title'] ) ): ?>
        <span><?php echo esc_html( $atts['title'] ) ?></span>
    <?php endif ?>
</a>
