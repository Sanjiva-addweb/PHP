<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( !empty($product_slugs) ) {
    $product_slugs = explode(',', $product_slugs);
    
	global $woocommerce;
    $product_item = (!empty($product_item)) ? $product_item : 'inner-deal';
    $products = ($product_item == 'inner-deal2') ? 'inner-deal2' : ' show-text p-bottom ';
	$args = array( 'post_name__in' => $product_slugs, 'post_status' => 'publish', 'post_type' => 'product' );
	$args['meta_query'] = array();
    $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
    $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
    $args['meta_query'][] =  array(
        array(
            'key'           => '_sale_price_dates_to',
            'value'         => time(),
            'compare'       => '>',
            'type'          => 'numeric'
        )
    );
	$loop = new WP_Query( $args );
	if ( $loop->have_posts() ) {
		?>
		<div class="widget widget-products-deal <?php echo esc_attr($el_class.' '.$product_item); ?>">
            <?php if ($title!=''): ?>
                <h3 class="widget-title">
                    <?php echo esc_attr( $title ); ?>
                </h3>
            <?php endif; ?>
        	<div class="widget-content woocommerce <?php echo esc_attr($layout_type); ?>">
        		<?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array(
                    'loop' => $loop,
                    'show_smalldestop' => $show_smalldestop,
                    'columns' => $columns,
                    'product_item' => $product_item,
                    'show_nav' => $show_nav,
                    'show_pagination' => $show_pagination,
                    'rows' => $rows,
                    'products' => $products,
                ) ); ?>
        	</div>
    	</div>
		<?php
	}
}