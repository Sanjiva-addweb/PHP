<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// Vat
$tax = 'ex';
if( isset( $_COOKIE['vat'] ) ) {
	if( $_COOKIE['vat']  === 'in' ) {
	    $tax = 'in';
	}elseif( $_COOKIE['vat']  === 'ex' ){
	    $tax = 'ex';
	}
}
if( isset( $_GET['vat'] ) ) {
	if( $_GET['vat']  === 'in' ) {
	    $tax = 'in';
	}elseif( $_GET['vat']  === 'ex' ){
	    $tax = 'ex';
	}
}
global $product;
$product_id = $product->get_id();

if( get_post_meta( $product_id, '_price', true ) ){
	$price = ( $tax === 'in' ) ? wc_price( get_post_meta( $product_id, '_price', true ) * 1.2 ) : wc_price( get_post_meta( $product_id, '_price', true ) );
}
if( get_post_meta( $product_id, '_qty250to499_productc_qty', true ) ){
	$price = __('From') . ' ';
	$price .= ( $tax === 'in' ) ? wc_price( get_post_meta( $product_id, '_qty250to499_productc_qty', true ) * 1.2 ) : wc_price( get_post_meta( $product_id, '_qty250to499_productc_qty', true ) );
}
if( get_post_meta( $product_id, '_bulkprice', true ) ){
	$price = ( $tax === 'in' ) ? wc_price( get_post_meta( $product_id, '_bulkprice', true ) * 1.2 ) : wc_price( get_post_meta( $product_id, '_bulkprice', true ) );

}

echo $price;