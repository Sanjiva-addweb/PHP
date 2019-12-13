<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<?php if ( have_posts() ) : ?>

    
    
    <!-- sub categories --> 
    <?php
        $term = get_queried_object();
        $name = '';
        if (!empty($term) && isset($term->taxonomy) && $term->taxonomy == 'product_cat') {
            $name = sprintf(__('%s Categories', 'yozi'), $term->name);
        }else{
            $name = sprintf(__('Top Categories', 'yozi'));
        }
        $args = array(
            'before'        => '<div class="widget categories-wrapper">
                <h3 class="widget-title">'.$name.'</h3>
                <div class="row">',
            'after'         => '</div></div>',
            'force_display' => false
        );
        woocommerce_product_subcategories($args);
    ?>

    <!-- Block Products Top -->
    <?php

    $parent_slug = empty( $term->slug ) ? 0 : $term->slug;
    if ( is_shop() || (!empty($term) && isset($term->taxonomy) && $term->taxonomy == 'product_cat') ) {
        $blocks = yozi_get_config( 'product_archive_blocks_top' );
        $blocks = isset($blocks['enabled']) ? $blocks['enabled'] : array();

        yozi_display_products_block_by_category($blocks, $parent_slug);
    }
    ?>
    
    <?php 
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
        add_action( 'woocommerce_before_shop_loop', 'yozi_woocommerce_display_modes',32 );
        add_action( 'woocommerce_before_shop_loop', 'yozi_wc_products_per_page',31 );
        do_action( 'woocommerce_before_shop_loop' ); 
    ?>
    
    <?php woocommerce_product_loop_start(); ?>
        <div class="row">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php wc_get_template_part( 'content', 'product' ); ?>
            <?php endwhile; // end of the loop. ?>
        </div>
    <?php woocommerce_product_loop_end(); ?>
    
    <?php do_action('woocommerce_after_shop_loop'); ?>



    <!-- Block Products Bottom -->
    <?php
    
    if ( is_shop() || (!empty($term) && isset($term->taxonomy) && $term->taxonomy == 'product_cat') ) {
        $blocks = yozi_get_config( 'product_archive_blocks_bottom' );
        $blocks = isset($blocks['enabled']) ? $blocks['enabled'] : array();

        yozi_display_products_block_by_category($blocks, $parent_slug);
        
    }

    if ( yozi_get_config('show_recently_viewed', true) ) {
        wc_get_template( 'content-archive-block-products.php' , array(
            'type' => 'recently_viewed',
            'term' => null,
            'term_name' => '',
            'categories' => array()
        ) );
    }
    ?>

<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
    <?php wc_get_template( 'loop/no-products-found.php' ); ?>
<?php endif;