<div class="woocommerce">
    <div id="product-<?php the_ID(); ?>" <?php post_class('product'); ?>>
    	<div id="single-product" class="product-info details-product">
    		<div class="row">
    			<div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="wrapper-img-main">
        				<?php
        					/**
        					 * woocommerce_before_single_product_summary hook
        					 *
        					 * @hooked woocommerce_show_product_sale_flash - 10
        					 * @hooked woocommerce_show_product_images - 20
        					 */
        					//do_action( 'woocommerce_before_single_product_summary' );
        					wc_get_template( 'single-product/product-image-carousel.php' );
        				?>
                    </div>
    			</div>
    			<div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="information quickviewcustom">
						<?php
						global $product;
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
                            //woocommerce_template_single_title();
                            //woocommerce_template_single_price();
                            //woocommerce_template_single_rating();
                            //woocommerce_template_single_excerpt();

                            if ( !yozi_get_config( 'enable_shop_catalog' ) ) {
                                //woocommerce_template_single_add_to_cart();
                            }
                            woocommerce_template_single_meta();
                            if ( yozi_get_config('show_product_social_share') ) {
                                //get_template_part( 'template-parts/sharebox' );
                            }
							
                            ?>
                        <?php
						if( !$product->is_type( 'simple' ) ) do_action( 'woocommerce_single_product_summary' );
						?>
                    </div>
					
    			</div>
    		</div>
    	</div>
    </div>
</div>
<style>

</style>