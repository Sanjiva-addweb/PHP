<?php

defined( 'ABSPATH' ) || exit;
/*

*/
add_action( 'wp_enqueue_scripts', 'child_wp_enqueue_scripts', 110 );
function child_wp_enqueue_scripts() {
	/*----------------20-11-2019-------------*/
	/*wp_enqueue_style( 'parent-theme', get_template_directory_uri() .'/style.css' );*/
	wp_enqueue_style( 'child-theme', get_stylesheet_directory_uri() .'/style.css' );
	if( is_front_page() ){

	}
	else {
		/*------------20-11-2019------------------*/
		/*wp_enqueue_style( 'bootstrap-child', get_stylesheet_directory_uri() .'/css/bootstrap.css' );*/
		wp_enqueue_style( 'template-child', get_stylesheet_directory_uri() .'/css/template.css' );
	}
	if( is_product() ){
		global $post;
		$product = wc_get_product( $post->ID );
		if( $product->is_type( 'simple' ) ){
			wp_enqueue_script( 'simple-product', get_stylesheet_directory_uri() .'/assets/js/simple-product.js', ['jquery'], null, true );
		}
		/*wp_enqueue_style( 'ipad-pro', get_stylesheet_directory_uri() .'/assets/css/iPad-pro.css', [], time() );*/
	}
	if( is_cart() ){
		wp_enqueue_script( 'cart', get_stylesheet_directory_uri() .'/assets/js/cart.js', ['jquery'], null, true );
		wp_localize_script( 'cart', 'ajax',
			array(
				'url' => admin_url('admin-ajax.php')
			)
		);
	}
	if( is_archive() && is_woocommerce() ){
		wp_enqueue_script( 'archive', get_stylesheet_directory_uri() .'/assets/js/archive.js', ['jquery'], null, true );
	}
	wp_enqueue_script( 'mini-cart', get_stylesheet_directory_uri() .'/assets/js/miniCart.js', ['jquery'], null, true );
	wp_localize_script( 'mini-cart', 'ajax',
		array(
			'url' => admin_url('admin-ajax.php')
		)
	);
}
/*

*/
