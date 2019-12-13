<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;
	
$woo_display = yozi_woocommerce_get_display_mode();
if ( $woo_display == 'list' ) { 	
	$classes[] = 'list-products col-xs-12';
	$product_list_version = yozi_get_config('product_list_version', 'list');
?>
	<div <?php wc_product_class( $classes ); ?>>
	 	<?php wc_get_template_part( 'item-product/inner', $product_list_version ); ?>
	</div>
<?php
} else {

	// Store loop count we're currently on
	if ( empty( $woocommerce_loop['loop'] ) ) {
		$woocommerce_loop['loop'] = 0;
	}
	// Store column count for displaying the grid
	if ( empty( $woocommerce_loop['columns'] ) ) {
		$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
	}

	// Ensure visibility
	if ( ! $product || ! $product->is_visible() ) {
		return;
	}

	$columns = 12/$woocommerce_loop['columns'];
	if($woocommerce_loop['columns'] == 5){
		$columns = 'cl-5 col-md-3';
	}
	if($woocommerce_loop['columns'] >=4 ){
		$classes[] = 'col-md-'.$columns.' col-sm-4 col-xs-6 ';
	}else{
		$classes[] = 'col-md-'.$columns.' col-sm-6 col-xs-6 ';
	}
	?>
	<div <?php wc_product_class( $classes ); ?>>
		<?php wc_get_template_part( 'item-product/inner' ); ?>
	</div>
<?php } ?>