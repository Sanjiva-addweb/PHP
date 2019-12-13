<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$nav_menu = ( $menu !='' ) ? wp_get_nav_menu_object( $menu ) : false;
if(!$nav_menu) return false;
$position_class = ($position=='left') ? 'menu-left' : 'menu-right';
$args = array(
    'menu' => $nav_menu,
    'container_class' => 'content-vertical '.$position_class,
    'menu_class' => 'apus-vertical-menu nav navbar-nav',
    'fallback_cb' => '',
    'menu_id' => 'vertical-menu',
    'walker' => new Yozi_Nav_Menu()
);
?>

<aside class="widget <?php echo esc_attr( $el_class ) ; ?> widget_apus_vertical_menu <?php echo ($style != '') ? esc_attr($style) :''; ?>">
    <?php if ($title!=''): ?>
        <h3 class="widget-title"><?php echo trim($title); ?></h3>
    <?php endif; ?>
    <div class="widget-content clearfix">
        <?php wp_nav_menu($args); ?>
    </div>
</aside>