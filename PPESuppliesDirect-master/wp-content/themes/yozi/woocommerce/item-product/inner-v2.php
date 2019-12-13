<?php 
global $product;
$product_id = $product->get_id();
?>
<div class="product-block grid grid-v2 grid-item-2" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="grid-inner">
        <div class="block-title">
            <?php yozi_woo_display_product_cat( $product_id ); ?>
            <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        </div>
        <div class="block-inner">
            <figure class="image">
                <?php yozi_display_sold_out_loop_woocommerce(); ?>
                
                <?php
                $onsale_price = yozi_onsale_price_show();
                if ($onsale_price && $product->is_type( 'simple' )) {?>
                        <div class="downsale">-<?php echo wc_price($onsale_price); ?></div>
                <?php } ?>
                
                <?php
                    $image_size = isset($image_size) ? $image_size : 'shop_catalog';
                    yozi_product_image($image_size);
                ?>
                <?php do_action('yozi_woocommerce_before_shop_loop_item'); ?>

                <?php
                    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
                    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                    remove_action('woocommerce_before_shop_loop_item_title', 'yozi_swap_images', 10);
                    do_action( 'woocommerce_before_shop_loop_item_title' );
                ?>
                
            </figure>
            <div class="groups-button clearfix">
                <?php if (yozi_get_config('show_quickview', true)) { ?>
                    <div class="view">
                        <a href="#" class="quickview" data-product_id="<?php echo esc_attr($product_id); ?>" data-toggle="modal" data-target="#apus-quickview-modal"><i class="ti-eye"></i></a>
                    </div>
                <?php } ?>
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
                <?php
                    if ( class_exists( 'YITH_WCWL' ) ) {
                        echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
                    }
                ?>
            </div> 
        </div>
        <div class="metas clearfix">
            <div class="inner-meta clearfix">
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
            </div>
        </div>

        <div class="add-cart-bottom">
            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
        </div>

    </div>
</div>