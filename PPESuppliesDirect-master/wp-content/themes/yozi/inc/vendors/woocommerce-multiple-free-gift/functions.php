<?php

function yozi_woo_free_gift_display() {
    global $product; 

    $post_id = $product->get_id(); 

    $wfg_enabled = get_post_meta( $post_id, '_wfg_single_gift_enabled', true );
    $allowed = get_post_meta( $post_id, '_wfg_single_gift_allowed', true );
    $products = get_post_meta( $post_id, '_wfg_single_gift_products', true );
    ?>
    <?php if( $wfg_enabled && !empty($products) ) : ?>
    <div class="product-free-gift media">
        <div class="gift-content media-body media-middle">
            <h3 class="title"> <span class="icon"><i class="ti-gift"></i></span> <?php esc_html_e( 'Gifts', 'yozi' ); ?></h3>
            <ul class="list-gift">
                <?php foreach( $products as $product_id ) :
                    $_product = wc_get_product( $product_id );
                ?>
                    <li class="product-gift">
                        <i class="fa fa-check"></i>
                        <span class="hightcolor"><?php esc_html_e('Free','yozi'); ?></span> <a href="<?php echo get_permalink( $product_id ); ?>"><?php echo get_the_title($product_id); ?></a> - <span class="hightline"><?php echo trim( $_product->get_price_html() ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>   
        </div>
    </div>
    <?php endif; ?>
<?php }
add_action( 'yozi_after_woocommerce_single_product_summary', 'yozi_woo_free_gift_display', 99 );