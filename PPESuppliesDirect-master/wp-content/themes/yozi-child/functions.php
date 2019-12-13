<?php
/**
 * yozi functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Yozi
 * @since Yozi 1.2.0
 */
defined( 'ABSPATH' ) || exit;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
/*
Previous version of functions.php can be found in (/inc/old/old-functions.php)
*/

require_once( get_stylesheet_directory() . '/inc/unqueue-scripts/unqueue-scripts.php' );
require_once( get_stylesheet_directory() . '/inc/theme-functions/theme-functions.php' );
require_once( get_stylesheet_directory() . '/inc/ajax/theme-ajax.php' );
require_once( get_stylesheet_directory() . '/inc/woocommerce/woocommerce-functions.php' );
require_once( get_stylesheet_directory() . '/inc/shortcodes/theme-shortcodes.php' );

/**
 * @author: antondrob
 * All custom scripts of the single author
 */
require_once( get_stylesheet_directory() . '/inc/antondrob.php' );

/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function wc_empty_cart_redirect_url() {
	return 'https://ppesuppliesdirect.com';
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// Remove WP Version From Styles	
add_filter('style_loader_src', 'sdt_remove_ver_css_js', 9999);
// Remove WP Version From Scripts
add_filter('script_loader_src', 'sdt_remove_ver_css_js', 9999);
// Function to remove version numbers
function sdt_remove_ver_css_js($src) {
	if (strpos ($src, 'ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
    function wcs_woo_remove_reviews_tab($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}

/*-------------16-10-2019-product-details-tab----------*/
add_filter( 'woocommerce_product_tabs', 'am_ninja_remove_product_tabs', 98 );
function am_ninja_remove_product_tabs( $tabs ) {
    global $product;
    $id = $product->get_id(); // change this to $product->id fro WC less than 2.7
    $my_custom_data = get_post_meta($id, 'sizeguide', true );  
    if(empty($my_custom_data)) {
        unset( $tabs['additional_information'] ); // Remove the additional information tab
        ?>
        <style>
			#tabs-list-additional_information {
    			display: none !important;
			}
			li#tabs-additional_information {
    			display: none !important;
			}
		</style><?php
    }
    return $tabs;
}

add_filter( 'woocommerce_product_tabs', 'am_ninja_remove_product_description_tabs', 98 );
function am_ninja_remove_product_description_tabs( $tabs ) {
    global $product;
    $product_description = get_post($item['product_id'])->post_content; 
    if(empty($product_description)) {
        unset( $tabs['description'] ); // Remove the additional information tab
        ?>
        <style>
			#tabs-list-description {
    			display: none !important;
			}
			li#tabs-description {
    			display: none !important;
			}
		</style><?php
    }
    return $tabs;
}