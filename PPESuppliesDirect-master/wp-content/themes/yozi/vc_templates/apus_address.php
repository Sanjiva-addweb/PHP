<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
?>
<div class="widget element-address <?php echo esc_attr($el_class); ?>">
    <?php if ( trim($title)!='' ) { ?>
        <h3 class="title">
            <?php echo trim( $title ); ?>
        </h3>
    <?php } ?>
    <?php if(wpb_js_remove_wpautop( $content, true )){ ?>
        <div class="description">
            <?php echo wpb_js_remove_wpautop( $content, true ); ?>
        </div>
    <?php } ?>
</div>