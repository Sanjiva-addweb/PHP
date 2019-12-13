<?php

defined( 'ABSPATH' ) || exit;

 function widget($atts) {
     global $wp_widget_factory;
     extract(shortcode_atts(array(
         'widget_name' => FALSE
     ), $atts));
     $widget_name = wp_specialchars($widget_name);
     if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
         $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
         if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
             return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
         else:
             $class = $wp_class;
         endif;
     endif;
     ob_start();
     the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
         'before_widget' => '',
         'after_widget' => '',
         'before_title' => '',
         'after_title' => ''
     ));
     $output = ob_get_contents();
     ob_end_clean();
     return $output;
 }
 add_shortcode('widget','widget');
 

 
 function product_offer( $atts ) {
 	// Shortcode parameters
 	$atts = shortcode_atts( array(
 		'sku' 	 => '',
 		'src' 	 => '',
 		'border' => ''
 	), $atts );
 	// Sku
 	if( empty( $atts['sku'] ) ) return;
 	$product_id = wc_get_product_id_by_sku( $atts['sku'] );
 	// Product image url
 	if( is_null( $product_id ) ) return;
 	$product = wc_get_product( $product_id );
 	if( !$product ) return;
 	$img = $product->get_image();
 	if( !empty( $atts['src'] ) ) $img = '<img src="' . $atts['src'] . '" />';
 	// Price
 	$price = $product->get_price();
 	// Html temolate
 	set_query_var( 'product_data', ['product_id' => $product_id, 'img' => $img, 'price' => $price, 'border' => $atts['border']] );
 	ob_start();
 	get_template_part( 'template-parts/shortcode', 'product' );
 	$html = ob_get_clean();
 	return $html;
 }
add_shortcode('product_offer', 'product_offer');
 