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
get_header();
global $post;
$product = wc_get_product( $post->ID );

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

		<div class="col-lg-2 col-md-4 col-xs-12 left-product-sidebar">
		  <div class="thump_p_widget iconswiddgetside" style=""><?php dynamic_sidebar('Single Product Sidebar icons'); ?> </div>
		</div>
		<div class="col-lg-10 col-md-8 col-xs-12">

			<?php
				if ( function_exists('yoast_breadcrumb') ) {
				  		yoast_breadcrumb( '<div id="breadcrumbs" style="margin-left:12px;">','</div>' );
				}
			?>


			<div class="col-lg-4 col-md-6 col-xs-12">
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
			<div class="col-lg-8 col-md-6 col-xs-12 single-product-info-wrapper" style="margin-bottom: 30px;">
				<div class="information">
					<?php
					if( $product->is_type( 'simple' ) ){
						$brand_slug = get_the_terms( $product->get_id(), 'product_brand' )[0]->slug;
						$brand_image = get_option('brand_taxonomy_image'.get_the_terms( $product->get_id(), 'product_brand' )[0]->term_id );
						if( get_post_meta( $product->get_id(), 'customize_product', true ) ){
							echo '<div class="second_content" style="float: right;"><a class="customize_icon" href="'.$homeurl.'/custom-design/?product_id='.$product->get_id().'"><img src="/wp-content/uploads/2019/03/Customise-button.jpg" width="260"></a></div>';
						}
						woocommerce_template_single_title();
						?>
						<?php if( $product->get_sku() ): ?>
							<div class="pr_code"><span>Product Code: <?php echo $product->get_sku(); ?></span>
								<div class="logo_image">
									<a href="<?php echo '/brand/' . $brand_slug; ?>" target="_blank"><img width="120px" src="<?php echo $brand_image; ?>" /></a>
								</div>
							</div>
						<?php endif; ?>
						<div class="table_tab500" >
							<div class="single_heading">PRICE</div>
								<table class="price_table">
									<tbody>
										<tr>
											<td class="table_quantity">1-9</td>
											<td class="table_quantity">10-19</td>
											<td class="table_quantity">20-49</td>
											<td class="table_quantity">50-99</td>
											<td class="table_quantity">100-249</td>
											<td class="table_quantity">250-499</td>
										</tr>
										<tr>
											<?php
											// Vat
											$tax = 'ex';
											if( $_COOKIE['vat']  === 'in' ) {
											    $tax = 'in';
											}elseif( $_COOKIE['vat']  === 'ex' ){
											    $tax = 'ex';
											}
											if( $_GET['vat']  === 'in' ) {
											    $tax = 'in';
											}elseif( $_GET['vat']  === 'ex' ){
											    $tax = 'ex';
											}
											if( $tax === 'in' ){
												$singlep = get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true ) * 1.2;
												$price10 = get_post_meta( $product->get_id(), '_qty10to19_productc_qty', true ) * 1.2;
												$price20 = get_post_meta( $product->get_id(), '_qty20to49_productc_qty', true ) * 1.2;
												$price50 = get_post_meta( $product->get_id(), '_qty50to99_productc_qty', true ) * 1.2;
												$price100 = get_post_meta( $product->get_id(), '_qty100to249_productc_qty', true ) * 1.2;
												$price250 = get_post_meta( $product->get_id(), '_qty250to499_productc_qty', true ) * 1.2;
											}else{
												$singlep =get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true );
												$price10 = get_post_meta( $product->get_id(), '_qty10to19_productc_qty', true );
												$price20 = get_post_meta( $product->get_id(), '_qty20to49_productc_qty', true );
												$price50 = get_post_meta( $product->get_id(), '_qty50to99_productc_qty', true );
												$price100 = get_post_meta( $product->get_id(), '_qty100to249_productc_qty', true );
												$price250 = get_post_meta( $product->get_id(), '_qty250to499_productc_qty', true );
											}
											?>
											<td class="table_price"><?php echo round( $singlep, 2 ); ?></td>
											<td class="table_price"><?php echo round( $price10, 2 ); ?></td>
											<td class="table_price"><?php echo round( $price20, 2 ); ?></td>
											<td class="table_price"><?php echo round( $price50, 2 ); ?></td>
											<td class="table_price"><?php echo round( $price100, 2 ); ?></td>
											<td class="table_price"><?php echo round( $price250, 2 ); ?></td>
										</tr>
										<tr>
											<td></td>
											<td class="table_perc"><?php echo round(((($singlep-$price10)/$singlep)*100),2); ?>%</td>
											<td class="table_perc"><?php echo round(((($singlep-$price20)/$singlep)*100),2); ?>%</td>
											<td class="table_perc"><?php echo round(((($singlep-$price50)/$singlep)*100),2); ?>%</td>
											<td class="table_perc"><?php echo round(((($singlep-$price100)/$singlep)*100),2); ?>%</td>
											<td class="table_perc"><?php echo round(((($singlep-$price250)/$singlep)*100),2); ?>%</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php woocommerce_output_product_data_tabs(); ?>
						<div class="after-tab-block"><a>Ordering more than 500 items? Give us a call for quote.</a></div>
						<?php woocommerce_template_single_add_to_cart();

					}

					if( $product->is_type( 'bundle' ) ){
						do_action( 'woocommerce_single_product_summary' );
						do_action( 'woocommerce_after_single_product_summary' );
						do_action( 'woocommerce_after_single_product' );
					}

					?>
					<div class="row flex-top">

						<div class="summary-left <?php echo ($product->is_type( 'grouped' ))?'col-sm-6':'col-sm-8'; ?>">
							<div class="summary entry-summary">
							<?php

								add_action( 'yozi_woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
								add_action( 'yozi_woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
								add_action( 'yozi_woocommerce_single_product_summary', 'woocommerce_template_single_meta', 20 );
								add_filter( 'yozi_woocommerce_single_product_summary', 'yozi_woocommerce_share_box', 30 );

								//do_action( 'yozi_woocommerce_single_product_summary' );
							?>
							</div>
						</div>
						<?php if( $product->is_type( 'variable' ) ): ?>
							<div class="summary-right <?php echo ($product->is_type( 'grouped' ))?'col-sm-6':'col-sm-8'; ?>">
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
										remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
										remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
										remove_action( 'woocommerce_single_product_summary', 'yozi_woocommerce_share_box', 100 );
										remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

										add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 11 );
										do_action( 'woocommerce_single_product_summary' );
									?>
								</div>
							</div><!-- .summary -->
						<?php endif; ?>
					</div>
					<?php do_action( 'yozi_after_woocommerce_single_product_summary' ); ?>
				</div>
				<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
			</div>

		<?php

		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		if( $product->is_type( 'variable' ) ){
			woocommerce_output_product_data_tabs();
		}
		add_action('woocommerce_after_single_product_summary', 'yozi_display_viewed_together_product', 12);
		add_action('woocommerce_after_single_product_summary', 'yozi_display_bought_together_product', 13);
		//add_action('woocommerce_after_single_product_summary', 'yozi_display_brand_logos_partner', 30);


	?>
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
							remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart', 30);
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
							remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart', 30);
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


	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' );
get_footer();
?>
