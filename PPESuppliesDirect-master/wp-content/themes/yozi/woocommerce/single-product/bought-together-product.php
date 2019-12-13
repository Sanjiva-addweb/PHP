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
$rows = 1;
$columns = yozi_get_config('bought_product_columns', 4);
$per_page = yozi_get_config('number_product_bought', 4);

$pids = yozi_get_bought_together_products( array($product->get_id()) );

if ( empty($pids) || sizeof( $pids ) == 0 ) return;

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $per_page,
	'post__in'             => $pids
) );

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

	<div class="boughtproducts products widget">
		<div class="widget-content woocommerce carousel item-grid">
			<h3 class="widget-title"><?php esc_html_e( 'Customers Who Bought This Item Also Bought', 'yozi' ); ?></h3>
			<?php wc_get_template( 'layout-products/carousel.php' , array( 'loop' => $products, 'columns' => $columns, 'rows' => $rows, 'product_item' => $item_style, 'show_pagination' => 1 ) ); ?>
		</div>
	</div>
<?php endif;
wp_reset_postdata();