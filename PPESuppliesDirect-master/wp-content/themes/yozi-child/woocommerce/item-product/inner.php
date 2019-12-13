<?php 
global $product;
$product_id = $product->get_id();
?>
<div class="product-block grid" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="grid-inner">
        <div class="block-title">
            <?php yozi_woo_display_product_cat( $product_id ); ?>
			<?php
			$gettitle = get_the_title();
			$gettitles = get_the_title(); //preg_replace("/&#8217;/", "'", $gettitle);
			?>
            <h3 class="name stretch">
				<a href="<?php the_permalink(); ?>">
				<?php 
				// htmlspecialchars()
				echo ((strlen(htmlentities($gettitles)) > 30) ? substr($gettitles, 0, 30).' ...' : $gettitles ); 
				//echo ((strlen(htmlentities(get_the_title())) > 15) ? htmlentities(substr(the_title(), 0, 15).' ...') : get_the_title() );
				?>
				</a>
			</h3>
        </div>
        <div class="block-inner">
			<a href="<?php the_permalink(); ?>">
				<figure class="image">
					<?php
					$onsale_price = yozi_onsale_price_show();
					if ($onsale_price && $product->is_type( 'simple' )) {?>
							<div class="downsale">-<?php echo wc_price($onsale_price); ?></div>
					<?php } ?>
					
					<?php
						$image_size = 'shop_catalog2';//isset($image_size) ? $image_size : 'shop_catalog';
						echo ( get_the_post_thumbnail(get_the_ID(), $image_size) ) ? get_the_post_thumbnail(get_the_ID(), $image_size) : '<img src="/wp-content/plugins/woocommerce/assets/images/placeholder.png" alt="'.$product->get_title().'"/>';
						//echo woocommerce_get_product_thumbnail();
					?>
					<?php do_action('yozi_woocommerce_before_shop_loop_item'); ?>
				</figure>
			</a>
            <?php if (yozi_get_config('show_quickview', true)) { ?>
                <div class="quick-view">
                    <a href="#" class="quickview btn btn-dark btn-block radius-3x" data-product_id="<?php echo esc_attr($product_id); ?>" data-toggle="modal" data-target="#apus-quickview-modal">
                        <?php echo esc_html__('Quick View','yozi') ?>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="metas clearfix">
            <?php
                /**
                * woocommerce_after_shop_loop_item_title hook
                *
                * @hooked woocommerce_template_loop_rating - 5
                * @hooked woocommerce_template_loop_price - 10
                */
                remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5);
				//remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price', 10);
                do_action( 'woocommerce_after_shop_loop_item_title');
				
            ?>

            <div class="rating clearfix">
                <?php
				if( get_post_meta( $product_id, '_wc_review_count', true ) > 0 ){
					$rating_html = wc_get_rating_html( $product->get_average_rating() );
                    $count = $product->get_rating_count();
                    if ( $rating_html ) {
                        echo trim( $rating_html );
                        if($count>0 && !empty($count)){
                            echo '<span class="counts">('.$count.')</span>';
                        }
                    }
				}  
                ?>
            </div>
        </div>
    </div>
    <div class="groups-button clearfix">
        <?php if( class_exists( 'YITH_Woocompare_Frontend' ) ) { ?>
            <?php
                $obj = new YITH_Woocompare_Frontend();
                $url = $obj->add_product_url($product_id);
                $compare_class = '';
                if ( isset($_COOKIE['yith_woocompare_list']) ) {
                    $compare_ids = json_decode( $_COOKIE['yith_woocompare_list'] );
                    if ( in_array($product_id, $compare_ids) ) {
                        $compare_class = 'added';
                        $url = $obj->view_table_url($product_id);
                    }
                }
            ?>
            <div class="yith-compare">
                <a title="<?php echo esc_html__('compare','yozi') ?>" href="<?php echo esc_url( $url ); ?>" class="compare <?php echo esc_attr($compare_class); ?>" data-product_id="<?php echo esc_attr($product_id); ?>">
                    <i class="ti-control-shuffle"></i>
                </a>
            </div>
        <?php } ?>
        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
        <?php
            if ( class_exists( 'YITH_WCWL' ) ) {
                echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
            }
        ?>
    </div> 
</div>