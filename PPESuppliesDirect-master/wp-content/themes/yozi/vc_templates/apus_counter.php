<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$text_color = $text_color?'style="color:'. $text_color .';"' : "";
wp_enqueue_script( 'jquery-counter', get_template_directory_uri().'/js/jquery.counterup.min.js', array( 'jquery' ) );
wp_enqueue_script( 'waypoints', get_template_directory_uri().'/js/waypoints.min.js', array( 'jquery' ) );

?>
<div class="counters <?php echo esc_attr($el_class); ?>">
	<div class="counter-wrap" <?php echo trim($text_color); ?>>
	   	<span class="counter counterUp"><?php echo (int)$number ?></span>
	</div> 
    <h3 class="title"><?php echo trim($title); ?></h3>
</div>