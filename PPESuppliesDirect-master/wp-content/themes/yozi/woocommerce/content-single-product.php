<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post, $product;

$discounts = get_post_meta( $product->get_id(), '_bulkdiscount_text_info', true );
?>
<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	}
	$layout = yozi_get_config('product_single_version', 'v2');
	if(!empty($discounts)){
        add_action( 'woocommerce_single_product_summary', 'yozi_discounts_before' , 44 );
        add_action( 'woocommerce_single_product_summary', 'yozi_discounts_after' , 46 );
    }
    $thumbs_pos = yozi_get_config('product_thumbs_position', 'thumbnails-left');
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'details-product layout-'.$layout ); ?>>
	<?php if ( $layout == 'v1' ) { ?>
		<div class="row top-content">
			<div class="col-lg-5 col-md-4 col-xs-12">
				<div class="image-mains clearfix <?php echo esc_attr( $thumbs_pos ); ?>">
					<?php
						/**
						 * woocommerce_before_single_product_summary hook
						 *
						 * @hooked woocommerce_show_product_sale_flash - 10
						 * @hooked woocommerce_show_product_images - 20
						 */
						remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10);
						do_action( 'woocommerce_before_single_product_summary' );
					?>
				</div>
			</div>
			<div class="col-lg-7 col-md-8 col-xs-12">
				<div class="information">
					<div class="row flex-top">
						<div class="summary-left <?php echo ($product->is_type( 'grouped' ))?'col-sm-6':'col-sm-8'; ?>">
							<div class="summary entry-summary">
							<?php
								
								add_action( 'yozi_woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
								add_action( 'yozi_woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
								add_action( 'yozi_woocommerce_single_product_summary', 'woocommerce_template_single_meta', 20 );
								add_filter( 'yozi_woocommerce_single_product_summary', 'yozi_woocommerce_share_box', 30 );

								do_action( 'yozi_woocommerce_single_product_summary' );
							?>
							</div>
						</div>
						<div class="summary-right <?php echo ($product->is_type( 'grouped' ))?'col-sm-6':'col-sm-4'; ?>">
							<div class="summary entry-summary">
								<?php
									/**
									 * woocommerce_single_product_summary hook
									 *
									 * @hooked woocommerce_template_single_title - 5
									 * @hooked woocommerce_template_single_rating - 10
									 * @hooked woocommerce_template_single_price - 10
									 * @hooked woocommerce_template_single_excerpt - 20
									 * @hooked woocommerce_template_single_add_to_cart - 30
									 * @hooked woocommerce_template_single_meta - 40
									 * @hooked woocommerce_template_single_sharing - 50
									 */

									remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
									remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
									remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
									remove_action( 'woocommerce_single_product_summary', 'yozi_woocommerce_share_box', 100 );
									remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

									add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 11 );
									do_action( 'woocommerce_single_product_summary' );
								?>
							</div>
						</div><!-- .summary -->
					</div>
					<?php do_action( 'yozi_after_woocommerce_single_product_summary' ); ?>
				</div>
			</div>
		</div>
	<?php } elseif ( $layout == 'v2' ) { ?>
		<div class="row top-content">
			<div class="col-md-7 col-xs-12">
				<div class="image-mains <?php echo esc_attr( $thumbs_pos ); ?>">
					<?php
						/**
						 * woocommerce_before_single_product_summary hook
						 *
						 * @hooked woocommerce_show_product_sale_flash - 10
						 * @hooked woocommerce_show_product_images - 20
						 */
						remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10);
						do_action( 'woocommerce_before_single_product_summary' );
					?>
				</div>
			</div>
			<div class="col-md-5 col-xs-12">
				<div class="information">
					<div class="summary entry-summary ">

						<?php
							/**
							 * woocommerce_single_product_summary hook
							 *
							 * @hooked woocommerce_template_single_title - 5
							 * @hooked woocommerce_template_single_rating - 10
							 * @hooked woocommerce_template_single_price - 10
							 * @hooked woocommerce_template_single_excerpt - 20
							 * @hooked woocommerce_template_single_add_to_cart - 30
							 * @hooked woocommerce_template_single_meta - 40
							 * @hooked woocommerce_template_single_sharing - 50
							 */

							//add_filter( 'woocommerce_single_product_summary', 'yozi_woocommerce_share_box', 30 );
							remove_action('woocommerce_single_product_summary','woocommerce_template_single_rating',10);
							remove_action('woocommerce_single_product_summary','yozi_woocommerce_share_box',100);
							remove_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20);
							remove_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40);

							add_action('woocommerce_single_product_summary','woocommerce_template_single_rating','11');
							add_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt','6');
							do_action( 'woocommerce_single_product_summary' );
						?>
					</div><!-- .summary -->
					
					<?php do_action( 'yozi_after_woocommerce_single_product_summary' ); ?>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<!-- V3 -->
		<div class="top-content product-v-wrapper clearfix">
			<div class="custom-md-7">
				<div class="image-mains">
					<?php
						/**
						 * woocommerce_before_single_product_summary hook
						 *
						 * @hooked woocommerce_show_product_sale_flash - 10
						 * @hooked woocommerce_show_product_images - 20
						 */
						remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10);
						do_action( 'woocommerce_before_single_product_summary' );
					?>
				</div>
			</div>
			<div class="custom-md-5 sticky-this">
				<div class="information">
					<div class="summary entry-summary">

						<?php
							/**
							 * woocommerce_single_product_summary hook
							 *
							 * @hooked woocommerce_template_single_title - 5
							 * @hooked woocommerce_template_single_rating - 10
							 * @hooked woocommerce_template_single_price - 10
							 * @hooked woocommerce_template_single_excerpt - 20
							 * @hooked woocommerce_template_single_add_to_cart - 30
							 * @hooked woocommerce_template_single_meta - 40
							 * @hooked woocommerce_template_single_sharing - 50
							 */

							//add_filter( 'woocommerce_single_product_summary', 'yozi_woocommerce_share_box', 30 );
							remove_action('woocommerce_single_product_summary','woocommerce_template_single_rating',10);
							remove_action('woocommerce_single_product_summary','yozi_woocommerce_share_box',100);
							remove_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20);
							remove_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40);

							add_action('woocommerce_single_product_summary','woocommerce_template_single_rating','11');
							add_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt','6');
							do_action( 'woocommerce_single_product_summary' );
						?>
					</div><!-- .summary -->
					
					<?php do_action( 'yozi_after_woocommerce_single_product_summary' ); ?>
				</div>
			</div>
		</div>
	<?php } ?>

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		add_action('woocommerce_after_single_product_summary', 'yozi_display_viewed_together_product', 12);
		add_action('woocommerce_after_single_product_summary', 'yozi_display_bought_together_product', 13);

		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>