<?php 
global $product;
$product_id = $product->get_id();
$stock_sold = ( $total_sales = get_post_meta( $product_id, 'total_sales', true ) ) ? round( $total_sales ) : 0;
$stock_available = ( $stock = get_post_meta( $product_id, '_stock', true ) ) ? round( $stock ) : 0;
$total_stock = $stock_sold + $stock_available;
$percentage = ( $stock_available > 0 ? round($stock_sold/$total_stock * 100) : 0 );
?>
<div class="product-block grid grid-deal" data-product-id="<?php echo esc_attr($product_id); ?>">
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
            if ( $product->is_on_sale() ) {?>
                    <span class="price"><?php echo wc_price($product->get_sale_price()); ?></span>
            <?php } ?>
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

        <?php if ( $stock_available > 0 ) { ?>
        <div class="special-progress">
            <div class="progress">
                <span class="progress-bar" style="<?php echo esc_attr('width:' . $percentage . '%'); ?>"></span>
            </div>
            <div class="info_sold clearfix">
                <div class="pull-left">
                    <?php echo esc_html__('Already Sold: ','yozi').'<span class="text-theme">'.$stock_sold.'</span>'; ?>
                </div>
                <div class="pull-right">
                    <?php echo esc_html__('Available: ','yozi').'<span class="text-theme">'.$total_stock.'</span>'; ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php
        $time_sale = get_post_meta( $product_id, '_sale_price_dates_to', true );
        if ( $time_sale ) { ?>
            <div class="time-wrapper">
                <div class="apus-countdown clearfix" data-time="timmer"
                    data-date="<?php echo date('m', $time_sale).'-'.date('d', $time_sale).'-'.date('Y', $time_sale).'-'. date('H', $time_sale) . '-' . date('i', $time_sale) . '-' .  date('s', $time_sale) ; ?>">
                </div>
            </div>
        <?php } ?>
    </div>
</div>