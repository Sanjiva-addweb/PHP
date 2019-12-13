<?php

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
	$img = $product->get_image();
	if( !empty( $atts['src'] ) ) $img = '<img src="' . $atts['src'] . '" />';
	// Price
	$price = $product->get_price();
	// Html temolate
	set_query_var( 'product_data', ['product_id' => $product_id, 'img' => $img, 'price' => $price, 'border' => $atts['border']] );
	$html = get_template_part( 'template-parts/shortcode', 'product' );
	return $html;

}
add_shortcode('product_offer', 'product_offer');