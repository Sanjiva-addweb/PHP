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
$columns = yozi_get_config('viewed_product_columns', 4);

$per_page = yozi_get_config('number_product_viewed', 4);

$pids = yozi_get_products_customer_also_viewed( $product->get_id() );

if ( empty($pids) || sizeof( $pids ) == 0 || !is_array($pids) ) return;

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $per_page,
	'post__in'             => $pids,
	'orderby' => 'ID(ID, explode('.implode(',', $pids).'))'
) );

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

	<div class="viewedproducts products widget">
		<div class="widget-content woocommerce carousel item-grid">
			<h3 class="widget-title"><?php esc_html_e( 'Customers Who Viewed This Item Also Viewed', 'yozi' ); ?></h3>
			<?php wc_get_template( 'layout-products/carousel.php' , array( 'loop' => $products, 'columns' => $columns, 'rows' => $rows, 'product_item' => $item_style , 'show_pagination' => 1) ); ?>
		</div>
	</div>
<?php endif;
wp_reset_postdata();