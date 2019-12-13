<?php

defined( 'ABSPATH' ) || exit;
/*

*/
function iconic_find_matching_product_variation( $product, $attributes ) {
 global $product;
    foreach( $attributes as $key => $value ) {
        if( strpos( $key, 'attribute_' ) === 0 ) {
            continue;
        }
        unset( $attributes[ $key ] );
        $attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
    }
    if( class_exists('WC_Data_Store') ) {
        $data_store = WC_Data_Store::load( 'product' );
        return $data_store->find_matching_product_variation( $product, $attributes );
    } else {
        return $product->get_matching_variation( $attributes );
    }
}
/*

*/
function apply_var($product_id, $quantity, $variation_id, $variation){
$product_id   = 24;
$quantity     = 1;
$variation_id = 25;
$variation    = array(
	'Color' => 'Blue',
	'Size'  => 'Small',
);
}
/*

*/
function action_woocommerce_after_shop_loop_item(  ) {
    global $product;
	if($product->is_type( 'variable' ))
	{
		echo '<input type="hidden" class="product_vid" value="'.$product->id.'" />';
	}
};
add_action( 'woocommerce_after_shop_loop_item_title', 'action_woocommerce_after_shop_loop_item', 15, 0 );
/*

*/
add_filter( 'manage_edit-product_brand_columns', 'set_custom_edit_product_brand_columns' );
function set_custom_edit_product_brand_columns($columns) {
    $columns['is_slider'] = __( 'Use in slider', 'your_text_domain' );
    return $columns;
}
/*

*/
add_filter( 'manage_product_brand_custom_column' , 'custom_product_brand_column', 10, 3 );
function custom_product_brand_column( $content,$column_name,$term_id ) {
    switch ( $column_name ) {
        case 'is_slider' :
             echo '<input type="checkbox" name="is_slider[]" />';
            break;
    }
}
/*

*/
function create_product_variation( $product_id, $variation_data ){
    $product = wc_get_product($product_id);
    $variation_post = array(
        'post_title'  => $product->get_title(),
        'post_name'   => 'product-'.$product_id.'-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );
    $variation_id = wp_insert_post( $variation_post );
    $variation = new WC_Product_Variation( $variation_id );
    foreach ($variation_data['attributes'] as $attribute => $term_name )
    {
        $taxonomy = 'pa_'.$attribute;
        if( ! taxonomy_exists( $taxonomy ) ){
            register_taxonomy(
                $taxonomy,
               'product_variation',
                array(
                    'hierarchical' => false,
                    'label' => ucfirst( $taxonomy ),
                    'query_var' => true,
                    'rewrite' => array( 'slug' => '$taxonomy')
                )
            );
        }
        if( ! term_exists( $term_name, $taxonomy ) )
            wp_insert_term( $term_name, $taxonomy );
        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug;
        $post_term_names =  wp_get_post_terms( $product_id, $taxonomy, array('fields' => 'names') );
        if( ! in_array( $term_name, $post_term_names ) )
            wp_set_post_terms( $product_id, $term_name, $taxonomy, true );
        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
    }
    if( ! empty( $variation_data['sku'] ) )
        $variation->set_sku( $variation_data['sku'] );
    if( empty( $variation_data['sale_price'] ) ){
        $variation->set_price( $variation_data['regular_price'] );
    } else {
        $variation->set_price( $variation_data['sale_price'] );
        $variation->set_sale_price( $variation_data['sale_price'] );
    }
    $variation->set_regular_price( $variation_data['regular_price'] );
    if( ! empty($variation_data['stock_qty']) ){
        $variation->set_stock_quantity( $variation_data['stock_qty'] );
        $variation->set_manage_stock(true);
        $variation->set_stock_status('');
    } else {
        $variation->set_manage_stock(false);
    }
    $variation->set_weight('');
    $variation->save();
}
/*

*/
function wh_deleteProduct($id, $force = FALSE)
{
    $product = wc_get_product($id);
    if(empty($product))
        return new WP_Error(999, sprintf(__('No %s is associated with #%d', 'woocommerce'), 'product', $id));
    if ($force)
    {
        if ($product->is_type('variable'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->delete(true);
            }
        }
        elseif ($product->is_type('grouped'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->set_parent_id(0);
                $child->save();
            }
        }
        $product->delete(true);
        $result = $product->get_id() > 0 ? false : true;
    }
    else
    {
        $product->delete();
        $result = 'trash' === $product->get_status();
    }
    if (!$result)
    {
        return new WP_Error(999, sprintf(__('This %s cannot be deleted', 'woocommerce'), 'product'));
    }
    if ($parent_id = wp_get_post_parent_id($id))
    {
        wc_delete_product_transients($parent_id);
    }
    return true;
}
/*

*/
