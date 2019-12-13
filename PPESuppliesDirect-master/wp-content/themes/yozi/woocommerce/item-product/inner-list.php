<?php 
global $product;
$product_id = $product->get_id();
?>
<div class="product-block product-block-list" data-product-id="<?php echo esc_attr($product_id); ?>">
		<div class="wrapper-image">
			<div class="inner">
			    <figure class="image">
			    	<?php yozi_display_sold_out_loop_woocommerce(); ?>
			        <?php
	                $onsale_price = yozi_onsale_price_show();
	                if ($onsale_price && $product->is_type( 'simple' )) {?>
	                        <div class="downsale">-<?php echo wc_price($onsale_price); ?></div>
	                <?php } ?>
			        <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="product-image">
			            <?php
			                /**
			                * woocommerce_before_shop_loop_item_title hook
			                *
			                * @hooked woocommerce_show_product_loop_sale_flash - 10
			                * @hooked woocommerce_template_loop_product_thumbnail - 10
			                */
			                remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash', 10);
			                do_action( 'woocommerce_before_shop_loop_item_title' );
			            ?>
			        </a>

			        <?php do_action('yozi_woocommerce_before_shop_loop_item'); ?>
			    </figure>
			    <?php if (yozi_get_config('show_quickview', true)) { ?>
	                <div class="quick-view">
	                    <a href="#" class="quickview btn btn-dark btn-block radius-3x" data-product_id="<?php echo esc_attr($product_id); ?>" data-toggle="modal" data-target="#apus-quickview-modal">	
	                       <span><?php esc_html_e('Quick view','yozi'); ?></span>
	                    </a>
	                </div>
	            <?php } ?>


			</div>    
		</div>    
	    <div class="wrapper-info">
	    	<div class="inner">
		    <div class="caption-list">
	        	<div class="cate-wrapper clearfix">
	        		<div class="pull-left">
	        			<?php yozi_woo_display_product_cat( $product_id ); ?>
	        		</div>
	        		<div class="pull-right">
		        		<?php
				            if ( class_exists( 'YITH_WCWL' ) ) {
				                echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
				            }
				        ?>
			        </div>
	        	</div>
    			
	         	<h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	            <div class="rating clearfix">
	                <?php
	                    $rating_html = wc_get_rating_html( $product->get_average_rating() );
	                    $count = $product->get_rating_count();
	                    if ( $rating_html ) {
	                        echo trim( $rating_html );
	                    } else {
	                        echo '<div class="star-rating"></div>';
	                    }
	                    echo '<span class="counts">('.$count.')</span>';
	                ?>
	            </div>
		        
		        <div class="product-excerpt">
		            <?php the_excerpt(); ?>
		        </div>
		    </div>
		    </div>
		</div>  
		<div class="caption-buttons">
			<div class="inner">
	    	<?php
	            /**
	            * woocommerce_after_shop_loop_item_title hook
	            *
	            * @hooked woocommerce_template_loop_rating - 5
	            * @hooked woocommerce_template_loop_price - 10
	            */
	            remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5);
	            do_action( 'woocommerce_after_shop_loop_item_title');
	        ?>

	        <?php
                // Availability
	        	$availability      = $product->get_availability();
                $availability_html = empty( $availability['availability'] ) ? '' : '<div class="avaibility-wrapper">'.esc_html__('Avaibility:', 'yozi').' <span class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</span></div>';
                echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
            ?>

	        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

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
	                <a title="<?php echo esc_html__('compare','yozi') ?>" href="<?php echo esc_url( $url ); ?>" class="compare btn btn-block btn-outline btn-compare <?php echo esc_attr($compare_class); ?>" data-product_id="<?php echo esc_attr($product_id); ?>">
	                    <?php esc_html_e( '+ Add To Compare', 'yozi' ); ?>
	                </a>
	            </div>
	        <?php } ?>
	    	</div>      
	    </div>      
</div>