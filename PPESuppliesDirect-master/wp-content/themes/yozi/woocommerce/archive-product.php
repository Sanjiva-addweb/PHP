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
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		do_action( 'woocommerce_before_main_content' );
	}
	?>

	<section id="main-container" class="main-container <?php echo apply_filters('yozi_woocommerce_content_class', 'container');?>">
		<?php yozi_before_content( $sidebar_configs ); ?>
		<div class="row">
			<?php yozi_display_sidebar_left( $sidebar_configs ); ?>

			<div id="main-content" class="archive-shop col-xs-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">

				<div id="primary" class="content-area">
					<div id="content" class="site-content" role="main">

						<!-- category description -->
						<?php if ( is_product_category() ) { ?>
							<?php if ( yozi_get_config('show_category_title') ) { ?>
								<h1 class="category-title"><?php woocommerce_page_title(); ?></h1>
							<?php } ?>
							<?php
								if ( yozi_get_config('show_category_image') ) {
									$term = get_queried_object();
									$thumbnail_id  			= get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true  );
									if ( $thumbnail_id ) {
										$image = wp_get_attachment_image_src( $thumbnail_id, 'full'  );
										if ( $image ) {
											?>
											<img src="<?php echo esc_url($image[0]); ?>">
											<?php
										}
									}
								}
							?>
							<?php
							if ( yozi_get_config('show_category_description') ) {
								the_archive_description( '<div class="taxonomy-description">', '</div>' );
							}
							?>
						<?php } ?>

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
