<?php

/**
 * Recalculate cart totals
 */

add_action( 'woocommerce_before_calculate_totals', 'antondrob_recalc_price', 1 );
 
function antondrob_recalc_price( $cart_object ) {

	$items = [];
	$bundle_products = [];
	foreach ( $cart_object->get_cart() as $key => $value ) {

		if( !isset($items[$value['product_id']]) ){
			$items[$value['product_id']] = (int)$value['quantity'];
		}else{
			$items[$value['product_id']]= $items[$value['product_id']] + (int)$value['quantity'];
		}
		if( $value['bundled_items'] ) {
			$bundle_products = array_merge( $bundle_products, $value['bundled_items'] );
		}
		
	}

	foreach ( $cart_object->get_cart() as $key => $value ) {

		// If the product is child bundle - set 0 and continue
		if( in_array( $key, $bundle_products ) ){
			$value['data']->set_price( 0 );
			continue;
		}

		if( has_term( 'bulk-deals', 'product_cat', $value['product_id'] ) ) {
			$value['data']->set_price( get_post_meta( $value['product_id'], '_bulkprice', true) / get_post_meta( $value['product_id'], '_bulkdeal', true)  );
			continue;
		}

		if($items[$value['product_id']] <= 9 && get_post_meta( $value['product_id'], '_qty1to9_productc_qty', true)){
			$value['data']->set_price( get_post_meta( $value['product_id'], '_qty1to9_productc_qty', true) );
		}elseif( $items[$value['product_id']] >= 10 && $items[$value['product_id']] <= 19 && get_post_meta( $value['product_id'], '_qty10to19_productc_qty', true)){
			$value['data']->set_price( get_post_meta( $value['product_id'], '_qty10to19_productc_qty', true) );
		}elseif( $items[$value['product_id']] >= 20 && $items[$value['product_id']] <= 49 && get_post_meta( $value['product_id'], '_qty20to49_productc_qty', true)){
			$value['data']->set_price( get_post_meta( $value['product_id'], '_qty20to49_productc_qty', true) );
		}elseif( $items[$value['product_id']] >= 50 && $items[$value['product_id']] <= 99 && get_post_meta( $value['product_id'], '_qty50to99_productc_qty', true)){
			$value['data']->set_price( get_post_meta( $value['product_id'], '_qty50to99_productc_qty', true) );
		}elseif( $items[$value['product_id']] >= 100 && $items[$value['product_id']] <= 249 && get_post_meta( $value['product_id'], '_qty100to249_productc_qty', true)){
			$value['data']->set_price( get_post_meta( $value['product_id'], '_qty100to249_productc_qty', true) );
		}elseif( $items[$value['product_id']] >= 250 && get_post_meta( $value['product_id'], '_qty250to499_productc_qty', true)){
			$value['data']->set_price( get_post_meta( $value['product_id'], '_qty250to499_productc_qty', true) );
		}
	}
}

/**
 * Change number of products that are displayed per page (shop page)
 */
 
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  $cols = 25;
  return $cols;
}

/**
 * Remove result count
 */

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/**
 * Change text in products_per_page select
 */

add_filter( 'wppp_ppp_text', 'change_products_per_page_text' );
function change_products_per_page_text( $value ){
	return __( 'Show: %s', 'woocommerce-products-per-page' );
}

/**
 * Change sorting text
 */
 
add_filter( 'woocommerce_catalog_orderby', 'antondrob_customize_product_sorting' );
function antondrob_customize_product_sorting( $sorting_options ){
    $sorting_options = array(
        'menu_order' => __( 'Sort alphabetically', 'woocommerce' ),
        'popularity' => __( 'Sort by popularity', 'woocommerce' ),
        'rating'     => __( 'Sort by average rating', 'woocommerce' ),
        'date'       => __( 'Sort by new to old', 'woocommerce' ),
        'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
        'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
    );
    return $sorting_options;
}

/**
 * Save custom product prices on update
 */
 
add_action( 'woocommerce_process_product_meta', 'antondrob_woocommerce_update_product' );
function antondrob_woocommerce_update_product( $product_id ){

	if( !isset( $_POST['_wpnonce'] ) ) return;
	$product = wc_get_product( $product_id );
	if( isset( $_POST['_qty1to9_productc_qty'] ) ) update_post_meta( $product_id, '_qty1to9_productc_qty', $_POST['_qty1to9_productc_qty'] );
	if( isset( $_POST['_qty10to19_productc_qty'] ) ) update_post_meta( $product_id, '_qty10to19_productc_qty', $_POST['_qty10to19_productc_qty'] );
    if( isset( $_POST['_qty20to49_productc_qty'] ) ) update_post_meta( $product_id, '_qty20to49_productc_qty', $_POST['_qty20to49_productc_qty'] );
	if( isset( $_POST['_qty50to99_productc_qty'] ) ) update_post_meta( $product_id, '_qty50to99_productc_qty', $_POST['_qty50to99_productc_qty'] );
	if( isset( $_POST['_qty100to249_productc_qty'] ) ) update_post_meta( $product_id, '_qty100to249_productc_qty', $_POST['_qty100to249_productc_qty'] );
	if( isset( $_POST['_qty250to499_productc_qty'] ) ) update_post_meta( $product_id, '_qty250to499_productc_qty', $_POST['_qty250to499_productc_qty'] );
	if( $product->is_type( 'variable' ) && isset( $_POST['_qty250to499_productc_qty'] ) ){
		$variations = $product->get_children();
		$min_price = get_post_meta( $product_id, '_qty250to499_productc_qty', $_POST['_qty250to499_productc_qty'] );
		if( $variations[0] != $min_price ){
			foreach( $variations as $variation ){
				update_post_meta( $variation, '_regular_price', $min_price );
				update_post_meta( $variation, '_price', $min_price );
			}
		}
	}
	if ( !empty( $_POST['choose_template'] ) ){
		update_post_meta( $product_id, 'choose_template', $_POST['choose_template'] );
	}else{
		delete_post_meta( $product_id, 'choose_template' );
	}
}

/**
 * Bulk remove Ajax handler
 */

if( wp_doing_ajax() ){
	add_action('wp_ajax_bulk_remove', 'bulk_remove_ajax_handler');
	add_action('wp_ajax_nopriv_bulk_remove', 'bulk_remove_ajax_handler');
}
function bulk_remove_ajax_handler(){
	$product_id = $_POST['product_id'];
	$product_key = $_POST['product_key'];
	$is_cart = $_POST['is_cart'];
	$product = wc_get_product( $product_id );
	$variation_ids = array();
	$variation_qty = array();

	if( in_array( 2151, $product->get_category_ids() ) ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if( $product_id == $cart_item['product_id'] ){
				WC()->cart->remove_cart_item( $cart_item_key );
				$variation_ids[] = $cart_item['variation_id'];
				$variation_qty[] = $cart_item['quantity'];
			}
		}
	}else {
		WC()->cart->remove_cart_item( $product_key );
	}
	// if( $product->is_type( 'simple' ) ){
	// 	$return = array(
	// 		'removed_product_ids' => $product_id,
	// 		'quantities'		  => $variation_qty[0]
	// 	);
	// }else{
	// 	$return = array(
	// 		'removed_product_ids' => implode( ',', $variation_ids ),
	// 		'quantities'		  => implode( ',', $variation_qty )
	// 	);
	// }
	ob_start();
	echo wc_get_template( 'cart/mini-cart.php' );
	$html = ob_get_clean();

	echo json_encode( array(
		'count'	  => WC()->cart->get_cart_contents_count(),
		'total'   => wc_price( WC()->cart->get_cart_contents_total() ),
		'html'    => $html
	) );
    die;
}

/**
 * Return removed products to cart
 */

add_action('wp', 'remove_ruturn_products', 1);
function remove_ruturn_products(){
	if( isset( $_GET['custom-remove-item'] ) ){
		$product = wc_get_product( $_GET['product-id'] );
		WC()->cart->remove_cart_item( $_GET['custom-remove-item'] );
		$redirect = strtok(wp_get_referer(), '?') . '?removed-product-id=' . $_GET['product-id'] . '&quantity=' . $_GET['quantity'];
		header( "Location: " . $redirect );
		die;
	}
	if( isset( $_GET['return_product'] ) ){
		WC()->cart->add_to_cart( $_GET['return_product'], $_GET['quantity'] );
		$redirect = strtok("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?'); 
		header( "Location: " . $redirect );
		die;
	}
	if( isset( $_GET['return_products'] ) ){
		$product_ids = explode( ',', $_GET['return_products'] );
		$product_qtys = explode( ',', $_GET['quantities'] );
		$i = 0;
		foreach( $product_ids as $product_id ){
			WC()->cart->add_to_cart( $product_id, $product_qtys[$i] );
			$i++;
		}
		$redirect = strtok("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?'); 
		header( "Location: " . $redirect );
		die;
	}
	
}

/**
 * Add notice when product is removed from mini-cart
 */

add_action( 'woocommerce_before_single_product', 'antondrob_add_notice', 25 );
function antondrob_add_notice(){
	if( isset( $_GET['removed-product-id'] ) /*&& is_product()*/ ){
		$product = wc_get_product( $_GET['removed-product-id'] );
		$actual_link = strtok("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
		echo '<div class="woocommerce-message" role="alert">“'.$product->get_name().'” removed. <a href="'.$actual_link.'?return_product='.$_GET['removed-product-id'].'&quantity='.$_GET['quantity'].'">Undo?</a></div>';
	}
	// Bulk deals
	if( isset( $_GET['removed-product-ids'] ) ){
		$product_ids = explode( ',', $_GET['removed-product-ids'] );
		$product_qtys = explode( ',', $_GET['quantities'] );
		
		$i = 0;
		$names = array();
		$actual_link = strtok("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
		foreach( $product_ids as $product_id ){
			$product = wc_get_product( $product_id );
			$names[] = $product->get_name() . ' (' . $product_qtys[$i] . ' items)';
			$i++;
		}
		echo '<div class="woocommerce-message" role="alert">“'.implode( '<br />', $names ).'” removed. <a href="'.$actual_link.'?return_products='.$_GET['removed-product-ids'].'&quantities='.$_GET['quantities'].'">Undo?</a></div>';;
	}
}

/**
 * Add image/thumbnail size
 */

add_image_size( 'custom_single_product2', 340, 9999, true );
add_image_size( 'shop_catalog2', 300, 0, false );
// if ( function_exists( 'add_image_size' ) ) {
//     add_image_size( 'custom_single_product2', 340, 9999, true );
// }
// add_filter( 'image_size_names_choose', 'new_custom_sizes' );
// function new_custom_sizes( $sizes ) {
//     return array_merge( $sizes, array(
//         'custom_single_product2' => 'Размер 450Хauto',
//     ) );
// }

/**
 * Single product tabs customization
 */

add_filter( 'woocommerce_product_tabs', 'wpb_new_product_tab' );
function wpb_new_product_tab( $tabs ) {
    // Add the new tab
    $tabs['delivery'] = array(
        'title'       => __( 'Delivery & Returns', 'text-domain' ),
        'priority'    => 3,
        'callback'    => 'wpb_delivery_product_tab_content'
    );
    return $tabs;
}
function wpb_delivery_product_tab_content() {
    // The Delivery tab content
    get_template_part( 'template-parts/tabs', 'terms' );
}

add_filter( 'woocommerce_product_tabs', 'wc_change_product_description_tab_title', 100, 1 );
function wc_change_product_description_tab_title( $tabs ) {
  global $post, $product;
	if( $product->is_type( 'variable' ) ){
		if ( isset( $tabs['description']['title'] ) )
			$tabs['description']['title'] = 'Product Information';
		
		if ( isset( $tabs['additional_information']['title'] ) )
			$tabs['additional_information']['title'] = 'Size Guide';
		
		if(is_single() && $tabs['description']['title']==""){
			$tabs['description']['title'] = 'Product Information';
		}
		$tabs['additional_information']['callback'] = 'woo_custom_sizeguide_tab_content';
	}
	if ( $product->is_type( 'simple' ) )
		unset( $tabs['additional_information'] );  	// Remove the additional information tab

	return $tabs;
}

function woo_custom_sizeguide_tab_content() {
	get_template_part( 'template-parts/tabs', 'sizeguide' );
}

add_filter( 'woocommerce_product_tabs', 'wpb_customization_product_tab' );
function wpb_customization_product_tab( $tabs ) {
    // Add the new tab
	global $product;
	if( $product->is_type( 'variable' ) && get_post_meta( $product->get_id(), 'choose_template', true ) !== 'template_2' ){
		$tabs['customization'] = array(
			'title'       => __( 'Customisation Options', 'text-domain' ),
			'priority'    => 5,
			'callback'    => 'wpb_customization_tab_content'
		);
	}
    return $tabs;
}

function wpb_customization_tab_content() {
	get_template_part( 'template-parts/tabs', 'customization' );
}

add_filter( 'woocommerce_product_tabs', 'woo_reorder_tabs', 98 );
function woo_reorder_tabs( $tabs ) {

	$tabs['reviews']['priority'] = 4;			// Reviews first
	$tabs['description']['priority'] = 1;			// Description second
	$tabs['additional_information']['priority'] = 2;	// Additional information third

	return $tabs;
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
// /**
//  * Remove product data tabs
//  */
// add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

// function woo_remove_product_tabs( $tabs ) {
// 	global $product;
// 	if( $product->is_type( 'simple' ) ){
// 		unset( $tabs['additional_information'] );  	// Remove the additional information tab
// 	}
//     return $tabs;
// }

/**
 * Add custom field to set if product is cusomizable
 */

add_action( 'woocommerce_product_options_advanced', 'antondrob_adv_product_options');
function antondrob_adv_product_options(){
 
	echo '<div class="options_group">';
 
	woocommerce_wp_checkbox( array(
		'id'		  => 'customize_product',
		'value'		  => get_post_meta( get_the_ID(), 'customize_product', true ),
		'label'		  => 'Enable customization',
		'desc_tip' 	  => true,
		'description' => 'If checked the customization option will be available in product page.',
	) );
	woocommerce_wp_text_input( array(
		'id'		  => 'customize_product_id',
		'value'		  => get_post_meta( get_the_ID(), 'customize_product_id', true ),
		'label'		  => 'Customization template ID',
		'desc_tip'	  => false,
		'description' => 'All ids can be found <a href="/wp-admin/admin.php?page=online_designer" target="_blank">here</a>.',
	) );
	woocommerce_wp_select( array(
		'id'	  => 'choose_template',
		'label'	  => 'Template',
		'name'    => 'choose_template',
		'value'   => get_post_meta( get_the_ID(), 'choose_template', true ),
		'options' => ['default' => 'Default', 'template_2' => 'Product without customization'],
		'desc_tip' 	  => true,
		'description' => 'Choose which template should be displayed for this specific product.',
	) );
 
	echo '</div>';
 
}

/*
 * New columns
 */

add_filter('manage_product_posts_columns', 'antondrob_price_and_featured_columns');
function antondrob_price_and_featured_columns( $column_array ) {
	$column_array['customize'] = 'Customize';
	$column_array['customize_id'] = 'Temp. ID';
 
	return $column_array;
}
 
/*
 * Populate our new columns with data
 */

add_action('manage_posts_custom_column', 'antondrob_populate_both_columns', 10, 2);
function antondrob_populate_both_columns( $column_name, $id ) {
 
	// if you have to populate more that one columns, use switch()
	switch( $column_name ) :
		case 'customize': {
			if( get_post_meta( $id, 'customize_product', true ) ){
				echo 'On';
			}else{
				echo 'Off';
			}
			break;
		}
		case 'customize_id': {
			if( get_post_meta( $id, 'customize_product_id', true ) ){
				echo get_post_meta( $id, 'customize_product_id', true );
			}else{
				echo '-';
			}
			break;
		}
	endswitch;
 
}

/**
 * Vat switcher logic
 */

add_action( 'woocommerce_init', 'vat_session', 1 );
function vat_session(){
	if ( is_admin() ) return;
	/**
	 * Prices are displayed in different templates:
	 * - /wp-content/plugins/woocommerce-add-customcustom_size.php
	 * - /wp-content/themes/yozi-child/woocommerce/single-product/add-to-cart/simple.php
	 * - /wp-content/themes/yozi-child/woocommerce/loop/price.php
	 */
	if ( class_exists( 'WooCommerce' ) ) {

		if( isset( $_GET['vat'] ) && $_GET['vat'] === 'in' ){
			setcookie('vat', 'in');
		}elseif( isset( $_GET['vat'] ) && $_GET['vat'] === 'ex' ){
			setcookie('vat', 'ex');
		}

		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if( strpos( $actual_link, '/custom-design/?product_id=' ) ) {
			session_start();
			if( $_COOKIE['vat'] === 'in' ){
				$_SESSION['vat'] = 'in';
			}elseif( $_COOKIE['vat'] === 'ex' ){
				$_SESSION['vat'] = 'ex';
			}
			if( $_COOKIE['vat'] === 'in' ){
				$_SESSION['vat'] = 'in';
			}elseif( $_COOKIE['vat'] === 'ex' ){
				$_SESSION['vat'] = 'ex';
			}
		}

	}

}

/**
 * Show free shipping if cart_contents_total >= 99 
 */

add_filter( 'woocommerce_package_rates', 'antondrob_shipping_based_on_price', 10, 2 );
function antondrob_shipping_based_on_price( $rates, $package ) {

    $total = WC()->cart->cart_contents_total;
	//var_dump($available_methods);
	if( $total >= 99 ){
		foreach ( $rates as $key => $rate ) {
			if( $rate->get_method_id() !== 'free_shipping' ){
				unset( $rates[$key] );
			}
		}
	}else{
		foreach ( $rates as $key => $rate ) {
			if( $rate->get_method_id() === 'free_shipping' ){
				unset( $rates[$key] );
			}
		}
	}

    return $rates;

}

/**
 * Add a custom field to product bulk edit special page
 */ 

add_action( 'woocommerce_product_bulk_edit_start', 'custom_field_product_bulk_edit', 10, 0 );
function custom_field_product_bulk_edit() {
    ?>
        <div class="inline-edit-group">
            <label class="alignleft">
                <span class="title"><?php _e('Enable custom.', 'woocommerce'); ?></span>
                <span class="input-text-wrap">
                    <input type="checkbox" name="customize_product" />
                </span>
            </label>
            <label class="alignleft">
                <span class="title"><?php _e('Template ID', 'woocommerce'); ?></span>
                <span class="input-text-wrap">
                    <input type="number" name="customize_product_id" />
                </span>
            </label>
        </div>
    <?php
}

/**
 * Save the custom fields data when submitted for product bulk edit
 */

add_action('woocommerce_product_bulk_edit_save', 'save_custom_field_product_bulk_edit', 10, 1);
function save_custom_field_product_bulk_edit( $product ){
	$product_id = $product->get_id();
	if ( isset( $_REQUEST['customize_product'] ) ){
		update_post_meta( $product_id, 'customize_product', 'yes' );
	}else{
		delete_post_meta( $product_id, 'customize_product' );
	}

	if ( isset( $_REQUEST['customize_product_id'] ) ){
		update_post_meta( $product_id, 'customize_product_id', $_REQUEST['customize_product_id'] );
	}else{
		delete_post_meta( $product_id, 'customize_product_id' );
	}
}

/**
 * Bundled product: children product title in accordion
 */

remove_action( 'woocommerce_bundled_item_details', 'wc_pb_template_bundled_item_details_wrapper_open', 0 );
add_action( 'woocommerce_bundled_item_details', 'antondrob_wc_pb_template_bundled_item_details_wrapper_open', 0 );
function antondrob_wc_pb_template_bundled_item_details_wrapper_open( $bundled_item ) {
	$bundle = $bundled_item->get_bundle();
	$layout = $bundle->get_layout();
	$unique_id = 'mydiv_'.uniqid();
	if ( ! in_array( $layout, array( 'default', 'tabular', 'grid' ) ) ) {
		return;
	}

	if ( 'default' === $layout ) {
		$el = 'div';
	} elseif ( 'tabular' === $layout ) {
		$el = 'tr';
	} elseif ( 'grid' === $layout ) {
		$el = 'li';
	}

	$classes = $bundled_item->get_classes( false );
	$style   = $bundled_item->is_visible() ? '' : ' style="display:none;"';

	if ( 'grid' === $layout && $bundled_item->is_visible() ) {
		// Get class of item in the grid.
		$classes[] = WC_PB()->display->get_grid_layout_class( $bundled_item );
		// Increment counter.
		WC_PB()->display->incr_grid_layout_pos( $bundled_item );
	}
	
	echo '<' . $el . ' class=" panel '.$i.'' . implode( ' ' , $classes ) . '"' . $style . ' >
		<div class="panel-heading">		
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#'.$unique_id.'"	">' . $bundled_item->get_title() . '</a>
                </h4>
        </div>
        <div id="'.$unique_id.'" class="panel-collapse collapse in">
            <div class="panel-body">
	';

}

/**
 * Remove coupon from checkout
 */

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 ); 

/**
 * Payment icons from new line for sagepaydirect
 */

add_filter( 'woocommerce_gateway_title', 'add_html_to_title', 10, 2 );
function add_html_to_title( $title, $id) {
	if( $id === 'sagepaydirect' ) $title =  $title . '<br />';
	return $title;
}

add_filter( 'woocommerce_gateway_icon', 'add_html_after_icon', 10, 2 );
function add_html_after_icon( $icon, $id ) {
	if( $id === 'sagepaydirect' ) $icon =  $icon . '<div class="' . $id . '"></div>';
	if( $id === 'paypal' ) $icon =  '<img src="https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png" alt="PayPal acceptance mark">';
	return $icon;
}

/**
 * Custom single product template for non customizable products
*/

add_filter( 'template_include', 'custom_single_product_template_include', 500, 1 );
function custom_single_product_template_include( $template ) {
	if( is_product() ){
		global $post;
		$product = wc_get_product($post->ID);
	    if ( get_post_meta( $product->get_id(), 'choose_template', true ) === 'template_2' ) {
	        $template = get_stylesheet_directory() . '/woocommerce/content-single-product2.php';
	    }
	}
    return $template;
}

add_filter( 'woocommerce_post_class', 'add_product_class', 10, 2 );
function add_product_class( $classes, $product ) {
	global $product;
	if ( get_post_meta( $product->get_id(), 'choose_template', true ) === 'template_2' ) {
        $classes[] = 'no-customization';
    }
    return $classes;
}

/**
 * Remove Test payment for everyone except admin
 * @author antondrob
 */

add_filter( 'woocommerce_available_payment_gateways', 'leave_test_payment_for_admin' );
  
function leave_test_payment_for_admin( $available_gateways ) {
     
	if ( !is_admin() ) {
		if( !current_user_can( 'manage_options' ) ) {
			unset( $available_gateways['cheque'] );
		}
	}
	return $available_gateways;
	
}