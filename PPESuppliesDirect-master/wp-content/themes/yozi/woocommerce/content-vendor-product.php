<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<?php if ( have_posts() ) : ?>
    <?php do_action('woocommerce_before_shop_loop'); ?>
    
    <!-- vendor info -->
    
    <?php do_action( 'yozi_before_products'); ?>
    
    <?php woocommerce_product_loop_start(); ?>
        
        <?php while ( have_posts() ) : the_post(); ?>
            <?php wc_get_template_part( 'content', 'product' ); ?>
        <?php endwhile; // end of the loop. ?>
        
    <?php woocommerce_product_loop_end(); ?>

    <?php do_action('woocommerce_after_shop_loop'); ?>

<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
    <?php wc_get_template( 'loop/no-products-found.php' ); ?>
<?php endif;