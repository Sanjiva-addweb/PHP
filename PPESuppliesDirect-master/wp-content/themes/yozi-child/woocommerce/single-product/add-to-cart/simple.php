<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}
$per_item = $product->get_price();
if( get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true ) ) $per_item = get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true );
if( $_SESSION['vat'] === 'in' ) $per_item = round( $per_item * 1.2, 2 );
echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' );
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		) );

		do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>
		<?php if( $per_item ): ?>
			<div class="containerdiv2inner1">
				<span class="bottom woocommerce-Price-amount amount pr_cart_value"><span class="woocommerce-Price-currencySymbol">£</span><span class="intamt"><?php echo $per_item; ?></span></span><br>
				<span class="items_add1"><span class="woocommerce-Price-currencySymbol">£</span><?php echo $per_item; ?> per item</span>
			</div>
		<?php endif; ?>
		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_product_btn"><?php echo esc_html( $product->single_add_to_cart_text() ); ?>  <img src="/wp-content/uploads/2019/03/shopping-bag.png" style="width: 30px;" /></button>
		<?php
		if( $_SESSION['vat'] === 'in' ){
			$price = ( get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true ) ) ? round( 1.2 * get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true ), 2 ) : '';
			$price2 = ( get_post_meta( $product->get_id(), '_qty10to19_productc_qty', true ) ) ? round( 1.2 * get_post_meta( $product->get_id(), '_qty10to19_productc_qty', true ), 2 ) : '';
			$price3 = ( get_post_meta( $product->get_id(), '_qty20to49_productc_qty', true ) ) ? round( 1.2 * get_post_meta( $product->get_id(), '_qty20to49_productc_qty', true ), 2 ) : '';
			$price4 = ( get_post_meta( $product->get_id(), '_qty50to99_productc_qty', true ) ) ? round( 1.2 * get_post_meta( $product->get_id(), '_qty50to99_productc_qty', true ), 2 ) : '';
			$price5 = ( get_post_meta( $product->get_id(), '_qty100to249_productc_qty', true ) ) ? round( 1.2 * get_post_meta( $product->get_id(), '_qty100to249_productc_qty', true ), 2 ) : '';
			$price6 = ( get_post_meta( $product->get_id(), '_qty250to499_productc_qty', true ) ) ? round( 1.2 * get_post_meta( $product->get_id(), '_qty250to499_productc_qty', true ), 2 ) : '';
		}else{
			$price = ( get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true ) ) ? get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true ) : '';
			$price2 = ( get_post_meta( $product->get_id(), '_qty10to19_productc_qty', true ) ) ? get_post_meta( $product->get_id(), '_qty10to19_productc_qty', true ) : '';
			$price3 = ( get_post_meta( $product->get_id(), '_qty20to49_productc_qty', true ) ) ? get_post_meta( $product->get_id(), '_qty20to49_productc_qty', true ) : '';
			$price4 = ( get_post_meta( $product->get_id(), '_qty50to99_productc_qty', true ) ) ? get_post_meta( $product->get_id(), '_qty50to99_productc_qty', true ) : '';
			$price5 = ( get_post_meta( $product->get_id(), '_qty100to249_productc_qty', true ) ) ? get_post_meta( $product->get_id(), '_qty100to249_productc_qty', true ) : '';
			$price6 = ( get_post_meta( $product->get_id(), '_qty250to499_productc_qty', true ) ) ? get_post_meta( $product->get_id(), '_qty250to499_productc_qty', true ) : '';
		}
		$_qty1to9_productc_qty = get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true );

		?>
		<input type="hidden" value="<?php echo $product->get_id(); ?>" class="product_id" />
		<input type="hidden" value="<?php echo $price; ?>" class="product_price1">
		<input type="hidden" value="<?php echo $price2; ?>" class="product_price2">
		<input type="hidden" value="<?php echo $price3; ?>" class="product_price3">
		<input type="hidden" value="<?php echo $price4; ?>" class="product_price4">
		<input type="hidden" value="<?php echo $price5; ?>" class="product_price5">
		<input type="hidden" value="<?php echo $price6; ?>" class="product_price6">
		<input type="hidden" value="<?php echo ( $_SESSION['vat'] === 'in' ) ? $product->get_price() * 1.2 : $product->get_price(); ?>" class="regular_price">
	</form>


<?php endif; ?>
