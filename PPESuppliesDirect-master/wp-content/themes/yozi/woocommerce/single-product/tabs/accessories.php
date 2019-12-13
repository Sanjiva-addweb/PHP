<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}

$item_style = 'inner';

$pids = Yozi_Woo_Accessories::get_accessories( $product->get_id() );

if ( empty($pids) || sizeof( $pids ) == 0 ) return;

$args = apply_filters( 'woocommerce_accessories_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => -1,
	'orderby' => 'post__in',
	'post__in' => $pids
) );

$products = new WP_Query( $args );
$total_price = 0;
$count = 0;

if ( $products->have_posts() ) : ?>
	
	<div class="yozi-wc-message"></div>
	<div class="accessoriesproducts products clearfix">
		<div class="accessoriesproducts-wrapper">
			<div class="accessories-products-wrapper widget no-margin">
				<h3 class="widget-title"><?php echo esc_html__('Accessories','yozi'); ?></h3>
				<div class="slick-carousel slick-small slick-small-top" data-carousel="slick" data-items="4" data-large="2" data-smallmedium="2" data-extrasmall="1" data-pagination="false" data-nav="true">
					<?php while ( $products->have_posts() ) : $products->the_post(); global $product; ?>
						<div class="accessories-inner">
							<?php wc_get_template( 'item-product/inner-accessories.php' ); ?>
						</div>	
						<?php
							$count++;
							$price_html = $product->get_price_html();
							if ( $price_html ) {
								$display_price = wc_get_price_to_display($product);
							}
							$total_price += $product->get_price();
						?>
					<?php endwhile; ?>
				</div>
			</div>
		
			<div class="check-item">
				<div class="check-all-items-wrapper">
					<input autocomplete="off" type="checkbox" class="check-all-items" id="check-all-items"/><label for="check-all-items"><?php echo esc_html__('Check All Items', 'yozi'); ?></label>
				</div>
				<div class="total-price-wrapper">
					<?php
						$total_products_html = '<span class="product-count">' . $count . '</span>';
						echo sprintf( _n('Price for %s item', 'Price for %s items', $count, 'yozi'), $total_products_html);
						echo '<span class="total-price">' . wc_price( $total_price ) . $product->get_price_suffix() . '</span>';
					?>
				</div>
				<div class="add-all-items-to-cart-wrapper">
					<button type="button" class="button btn btn-theme add-all-items-to-cart"><?php echo esc_html__( 'Add items to cart', 'yozi' ); ?></button>
				</div>
			</div>
		</div>

	</div>
<?php endif;
wp_reset_postdata();
?>