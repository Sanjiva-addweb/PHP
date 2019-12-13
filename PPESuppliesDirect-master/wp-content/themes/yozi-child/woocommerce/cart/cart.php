<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!-- header -->
<div class="apus-checkout-header">
	
	<div class="apus-checkout-step">
		<ul class="clearfix">
			<li class="active">
				<div class="inner">
				<?php printf(__( '<span class="step">%s</span>', 'yozi' ), '' ); ?>
				<span class="inner-step">
					<?php echo esc_html__('Shopping Cart','yozi'); ?>
				</span>
				</div>
			</li>
			<li>
				<div class="inner">
				<?php printf(__( '<span class="step">%s</span>', 'yozi' ), '' ); ?>
				<span class="inner-step">
					<?php echo esc_html__('Checkout','yozi'); ?>
				</span>
				</div>
			</li>
			<li>
				<div class="inner">
				<?php printf(__( '<span class="step">%s</span>', 'yozi' ), '' ); ?>
				<span class="inner-step">
					<?php echo esc_html__('Order Completed','yozi'); ?>
				</span>
				</div>
			</li>
		</ul>
	</div>
</div>
<?php

add_action( 'woocommerce_theme_cart_collaterals', 'woocommerce_cart_totals' );
wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr class="cart_headr_txt">
					<th class="product-thumbnail"><?php esc_html_e( 'Image', 'yozi' ); ?></th>
					<th class="product-name"><?php esc_html_e( 'Product Name', 'yozi' ); ?></th>
					<th class="product-price"><?php esc_html_e( 'Price', 'yozi' ); ?></th>
					<th class="product-quantity"><?php esc_html_e( 'Quantity', 'yozi' ); ?></th>
					<th class="product-subtotal"><?php esc_html_e( 'Total', 'yozi' ); ?></th>
					<th class="product-remove"><span class="remove"><i class="fa-times fa"></i></span></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				// Bulk and bundle product customization
				$bulk_products = [];
				$bundle_products = [];
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$price 		= get_post_meta( $product_id, '_bulkprice', true ) / get_post_meta( $product_id, '_bulkdeal', true );
					$name		= $_product->get_attributes();
					if( $_product->is_type( 'variable' ) ){
						$name_qty	= $_product->get_variation_attributes();
					}
					if( has_term( 'bulk-deals', 'product_cat', $product_id ) ){
						$bulk_products[$product_id]['qty'] += $cart_item['quantity'];
						$bulk_products[$product_id]['price'] += $price * $cart_item['quantity'];
						$bulk_products[$product_id]['name'][] = $name;
						$bulk_products[$product_id]['hidden'] = false;
						$bulk_products[$product_id]['qtys'][] = $cart_item['quantity'];
						$bulk_products[$product_id]['name_qty'][] = $name_qty;
					}
					if( $cart_item['bundled_items'] ) {
						$bundle_products = array_merge( $bundle_products, $cart_item['bundled_items'] );
					}
				}

				// echo '<pre>';
				// var_dump( WC()->cart->get_cart() );
				// echo '</pre>';

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

					if( in_array( $cart_item_key, $bundle_products ) ) continue;

					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?><?php echo ($bulk_products[$product_id]['hidden']) ? ' bulk-item-hidden' : ''; ?>">

							
							<td class="product-thumbnail">
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo trim($thumbnail);
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
									}
								?>
							</td>

							<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'yozi' ); ?>">
								<?php
									if ( ! $product_permalink ) {
										if( !$bulk_products[$product_id]['hidden'] && has_term( 'bulk-deals', 'product_cat', $product_id ) ){
											echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
										}else{
											echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
										}
									} else {
										if( !$bulk_products[$product_id]['hidden'] && has_term( 'bulk-deals', 'product_cat', $product_id ) ){
											echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );
										}else{
											echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
										}
									}

									// Meta data
									echo wc_get_formatted_cart_item_data( $cart_item );

									// Backorder notification
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'yozi' ) . '</p>';
									}
									
									if( has_term( 'bulk-deals', 'product_cat', $product_id ) && !$bulk_products[$product_id]['hidden'] ){
										echo ( has_term( 'bulk-deals', 'product_cat', $product_id ) ) ? '<br><b>Variations:</b>' : '';
										$finish = array();
										foreach( $bulk_products[$product_id]['name'] as $variations ){
											$var_arr = array();
											foreach( $variations as $key => $variation ){
												$array = get_term_by( 'slug', $variation, $key );
												$var_arr[] =  $array->name;
											}
											$finish['name'][] = $var_arr;
										}
										
										foreach( $bulk_products[$product_id]['qtys'] as $quantity ){
											$finish['qtys'][] = $quantity;
										}
										
										$i = 0;
										$new_array = array();
										foreach($finish['name'] as $variation){
											$new_array[implode(', ', $variation)] += $finish['qtys'][$i];
											$i++;											
										}
										
										foreach($new_array as $key => $value){
											echo '<br />' . $key . ' <b>x</b> ' . $value;											
										}
									}
									
									
								?>
							</td>

							<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'yozi' ); ?>">
								<?php
								if( !has_term( 'bulk-deals', 'product_cat', $product_id ) ){
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
									//echo wc_price( wc_get_price_excluding_tax( $_product, array( 'qty' => 1 ) ) );
								}
								?>
							</td>

							<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'yozi' ); ?>">
								<?php
								if( !$bulk_products[$product_id]['hidden'] && has_term( 'bulk-deals', 'product_cat', $product_id ) ){
									echo $bulk_products[$product_id]['qty'];
								}else{
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value'   => $_product->get_max_purchase_quantity(),
											'min_value'   => '0',
										), $_product, false );
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								}
								?>
							</td>

							<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'yozi' ); ?>">
								<?php
									if( !$bulk_products[$product_id]['hidden'] && has_term( 'bulk-deals', 'product_cat', $product_id ) ){
										echo wc_price( $bulk_products[$product_id]['price'] );
									}else{
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
										//echo wc_price( wc_get_price_excluding_tax( $_product, array( 'qty' => $cart_item['quantity'] ) ) );
									}
								?>
							</td>
							<td class="product-remove<?php echo ( !$bulk_products[$product_id]['hidden'] && has_term( 'bulk-deals', 'product_cat', $product_id ) ) ? ' bulk-remove' : ''; ?>" data-product-id="<?php echo $product_id; ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"> <i class="fa-times fa"></i></a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										__( 'Remove this item', 'yozi' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									), $cart_item_key );
								?>
							</td>
						</tr>
						<?php
					}
					if( array_key_exists( $product_id, $bulk_products ) ){
						$bulk_products[$product_id]['hidden'] = true;
					}
				}
				?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<tr>
					<td colspan="6" class="actions">

						<?php if ( wc_coupons_enabled() ) { ?>
							<div class="coupon">
								<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'yozi' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Discount Code', 'yozi' ); ?>" /> <input type="submit" class="cupon_btn_new" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'yozi' ); ?>" />
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php } ?>

						<input type="submit" class="button" name="update_cart" style="color:#fff;" value="<?php esc_attr_e( 'Update cart', 'yozi' ); ?>" />

						<?php do_action( 'woocommerce_cart_actions' ); ?>

						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</td>
				</tr>
				<tr>
				<td colspan="6">
					<div class="continue_shopping">
					<div class="continue_shopping_btn"><div class="arrow_left_btn"></div><a class="continue_txt" href="https://ppesuppliesdirect.com">CONTINUE SHOPPING</a></div>
					<img src="https://ppesuppliesdirect.com/wp-content/uploads/2019/03/all_icons_v2.png" alt="card icons" class="cart_card_icons">
					</div>
					</td>
				</tr>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>
	<div class="cart_total_new">
		<div class="col-md-12 col-xs-12" style="padding-right:0px;">
			<div class="cart-collaterals">
				<?php do_action( 'woocommerce_theme_cart_collaterals' ); ?>
			</div>
		</div>
		<?php do_action( 'woocommerce_after_cart' ); ?>
<div class="cart-collaterals widget related">
	<?php
		/**
		 * woocommerce_cart_collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		remove_action('woocommerce_cart_collaterals','woocommerce_cart_totals',10);
	 	do_action( 'woocommerce_cart_collaterals' );
	?>
</div>
	</div>
