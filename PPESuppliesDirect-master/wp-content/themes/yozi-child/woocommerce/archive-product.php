<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_vendor_shop = false;
if (class_exists('WCV_Vendors')) {
    $is_vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
}

get_header();
$sidebar_configs = yozi_get_woocommerce_layout_configs();

?>

	<?php do_action( 'yozi_woo_template_main_before' ); ?>

	<?php
	if ( $is_vendor_shop ) {
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		// remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		// do_action( 'woocommerce_before_main_content' );
	}
	?>

	<section id="main-container" class="main-container <?php echo apply_filters('yozi_woocommerce_content_class', 'container');?>">

		<?php yozi_before_content( $sidebar_configs ); ?>
		<div class="row">
			<?php yozi_display_sidebar_left( $sidebar_configs ); ?>

			<div id="main-content" class="archive-shop col-xs-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
				<?php
					if ( function_exists('yoast_breadcrumb') ) {
				  		yoast_breadcrumb( '<div id="breadcrumbs">','</div>' );
					}
					if( isset( $_GET['removed-product-id'] ) /*&& is_product()*/ ){
						$product = wc_get_product( $_GET['removed-product-id'] );
						$actual_link = strtok("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
						echo '<div class="woocommerce-message" role="alert">“'.$product->get_name().'” removed. <a href="'.$actual_link.'?return_product='.$_GET['removed-product-id'].'&quantity='.$_GET['quantity'].'">Undo?</a></div>';
					}
					// Bulk deals
					if( isset( $_GET['removed-product-ids'] ) ){
						$product_ids = explode( ',', $_GET['removed-product-ids'] );
						$product_qtys = explode( ',', $_GET['quantities'] );
						
						$i = 0;
						$names = array();
						$actual_link = strtok("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
						foreach( $product_ids as $product_id ){
							$product = wc_get_product( $product_id );
							$names[] = $product->get_name() . ' (' . $product_qtys[$i] . ' items)';
							$i++;
						}
						echo '<div class="woocommerce-message" role="alert">“'.implode( '<br />', $names ).'” removed. <a href="'.$actual_link.'?return_products='.$_GET['removed-product-ids'].'&quantities='.$_GET['quantities'].'">Undo?</a></div>';;
					}
				?>
				<div id="primary" class="content-area">
					<div id="content" class="site-content" role="main">

						
						<div id="apus-shop-products-wrapper" class="apus-shop-products-wrapper">
	                        <!-- product content -->
							
							<?php wc_get_template_part( 'content', 'archive-product' ); ?>
							
						</div>
					</div><!-- #content -->
				</div><!-- #primary -->
			</div><!-- #main-content -->
			<?php yozi_display_sidebar_right( $sidebar_configs ); ?>
			
		</div>
	</section>

<?php
get_footer();
