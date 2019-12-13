<?php

function yozi_woocommerce_setup() {
    global $pagenow;
    if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ) {
        $catalog = array(
            'width'     => '738',   // px
            'height'    => '738',   // px
            'crop'      => 1        // true
        );

        $single = array(
            'width'     => '800',   // px
            'height'    => '800',   // px
            'crop'      => 1        // true
        );

        $thumbnail = array(
            'width'     => '108',    // px
            'height'    => '108',   // px
            'crop'      => 1        // true
        );

        // Image sizes
        update_option( 'shop_catalog_image_size', $catalog );       // Product category thumbs
        update_option( 'shop_single_image_size', $single );         // Single product image
        update_option( 'shop_thumbnail_image_size', $thumbnail );   // Image gallery thumbs
    }
}
add_action( 'init', 'yozi_woocommerce_setup');


if ( !function_exists('yozi_get_products') ) {
    function yozi_get_products( $args = array() ) {
        global $woocommerce, $wp_query;

        $args = wp_parse_args( $args, array(
            'categories' => array(),
            'product_type' => 'recent_product',
            'paged' => 1,
            'post_per_page' => -1,
            'orderby' => '',
            'order' => '',
            'includes' => array(),
            'excludes' => array(),
            'author' => '',
            'search' => '',
        ));
        extract($args);
        
        $query_args = array(
            'post_type' => 'product',
            'posts_per_page' => $post_per_page,
            'post_status' => 'publish',
            'paged' => $paged,
            'orderby'   => $orderby,
            'order' => $order
        );

        if ( isset( $query_args['orderby'] ) ) {
            if ( 'price' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_price',
                    'orderby'   => 'meta_value_num'
                ) );
            }
            if ( 'featured' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_featured',
                    'orderby'   => 'meta_value'
                ) );
            }
            if ( 'sku' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_sku',
                    'orderby'   => 'meta_value'
                ) );
            }
        }

        switch ($product_type) {
            case 'best_selling':
                $query_args['meta_key']='total_sales';
                $query_args['orderby']='meta_value_num';
                $query_args['ignore_sticky_posts']   = 1;
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'featured_product':
                $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['featured'],
                );
                break;
            case 'top_rate':
                add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'recent_product':
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;
            case 'deals':
                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $query_args['meta_query'][] =  array(
                    array(
                        'key'           => '_sale_price_dates_to',
                        'value'         => time(),
                        'compare'       => '>',
                        'type'          => 'numeric'
                    )
                );
                break;     
            case 'on_sale':
                $product_ids_on_sale    = wc_get_product_ids_on_sale();
                $product_ids_on_sale[]  = 0;
                $query_args['post__in'] = $product_ids_on_sale;
                break;
            case 'recent_review':
                if($post_per_page == -1) $_limit = 4;
                else $_limit = $post_per_page;
                global $wpdb;
                $query = "SELECT c.comment_post_ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c
                        WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0
                        ORDER BY c.comment_date ASC";
                $results = $wpdb->get_results($query, OBJECT);
                $_pids = array();
                foreach ($results as $re) {
                    if(!in_array($re->comment_post_ID, $_pids))
                        $_pids[] = $re->comment_post_ID;
                    if(count($_pids) == $_limit)
                        break;
                }

                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $query_args['post__in'] = $_pids;

                break;
            case 'rand':
                $query_args['orderby'] = 'rand';
                break;
            case 'recommended':

                $query_args['meta_query'] = array();
                $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $query_args['meta_query'][] = array(
                    'key' => '_apus_recommended',
                    'value' => 'yes',
                );
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'recently_viewed':
                $viewed_products = ! empty( $_COOKIE['apus_woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['apus_woocommerce_recently_viewed'] ) : array();
                $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

                if ( empty( $viewed_products ) ) {
                    return false;
                }
                $query_args['post__in'] = $viewed_products;
                break;
        }

        if ( !empty($categories) && is_array($categories) ) {
            $query_args['tax_query'][] = array(
                'taxonomy'      => 'product_cat',
                'field'         => 'slug',
                'terms'         => implode(",", $categories ),
                'operator'      => 'IN'
            );
        }

        if (!empty($includes) && is_array($includes)) {
            $query_args['post__in'] = $includes;
        }
        
        if ( !empty($excludes) && is_array($excludes) ) {
            $query_args['post__not_in'] = $excludes;
        }

        if ( !empty($author) ) {
            $query_args['author'] = $author;
        }

        if ( !empty($search) ) {
            $query_args['search'] = "*{$search}*";
        }

        return new WP_Query($query_args);
    }
}

if ( !function_exists('yozi_woocommerce_get_categories') ) {
    function yozi_woocommerce_get_categories() {
        $return = array( esc_html__(' --- Choose a Category --- ', 'yozi') => '' );

        $args = array(
            'type' => 'post',
            'child_of' => 0,
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'hierarchical' => 1,
            'taxonomy' => 'product_cat'
        );

        $categories = get_categories( $args );
        yozi_get_category_childs( $categories, 0, 0, $return );

        return $return;
    }
}

if ( !function_exists('yozi_get_category_childs') ) {
    function yozi_get_category_childs( $categories, $id_parent, $level, &$dropdown ) {
        foreach ( $categories as $key => $category ) {
            if ( $category->category_parent == $id_parent ) {
                $dropdown = array_merge( $dropdown, array( str_repeat( "- ", $level ) . $category->name => $category->slug ) );
                unset($categories[$key]);
                yozi_get_category_childs( $categories, $category->term_id, $level + 1, $dropdown );
            }
        }
    }
}

// get product id who bought also bought
function yozi_get_bought_together_products($pids, $exclude_pids = 1)
{
    $all_products = array();
    $pids_count = count($pids);
    $pid = implode(',',$pids);
    global $wpdb, $table_prefix;
    if ($pids_count > 1 ||  ($pids_count == 1 && !$all_products = wp_cache_get( 'apus_bought_together_'.$pid, 'apus_bought_together' )) ) {
        $subsql = "SELECT oim.order_item_id FROM ".$table_prefix."woocommerce_order_itemmeta oim where oim.meta_key='_product_id' and oim.meta_value in ($pid)";
        $sql = "SELECT oi.order_id from  ".$table_prefix."woocommerce_order_items oi where oi.order_item_id in ($subsql) limit 100";

        $all_orders = $wpdb->get_col($sql);
        if($all_orders){
            $all_orders_str = implode(',',$all_orders);
            $subsql2 = "select oi.order_item_id FROM ".$table_prefix."woocommerce_order_items oi where oi.order_id in ($all_orders_str) and oi.order_item_type='line_item'";
            if($exclude_pids){
                $sub_exsql2 = " and oim.meta_value not in ($pid)";
            }
            $sql2 = "select oim.meta_value as product_id,count(oim.meta_value) as total_count from ".$table_prefix."woocommerce_order_itemmeta oim where oim.meta_key='_product_id' $sub_exsql2 and oim.order_item_id in ($subsql2) group by oim.meta_value order by total_count desc limit 15";
            $all_products = $wpdb->get_col($sql2);
            if ($pids_count==1) {
                wp_cache_add( 'apus_bought_together_'.$pid, $all_products, 'apus_bought_together' );
            }
        }
    }
    return $all_products;
}

// add product viewed
function yozi_track_product_view() {
    if ( ! is_singular( 'product' ) || !yozi_get_config('show_product_product_viewed_together') ) {
        return;
    }

    global $post;

    if ( empty( $_COOKIE['apus_woocommerce_recently_viewed'] ) )
        $viewed_products = array();
    else
        $viewed_products = (array) explode( '|', $_COOKIE['apus_woocommerce_recently_viewed'] );

    if ( ! in_array( $post->ID, $viewed_products ) ) {
        $viewed_products[] = $post->ID;
    }

    if ( sizeof( $viewed_products ) > 15 ) {
        array_shift( $viewed_products );
    }

    // Store for session only
    wc_setcookie( 'apus_woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'yozi_track_product_view', 20 );

function yozi_woocommerce_relation_product_options()
{
    global $post;
    if ( !yozi_get_config('show_product_product_viewed_together') ) {
        return;
    }
    $customer_also_viewed = ! empty( $_COOKIE['apus_woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['apus_woocommerce_recently_viewed'] ) : array();
    if ( ($key = array_search($post->ID, $customer_also_viewed)) !== false ) {
        unset($customer_also_viewed[$key] );
    }

    if(!empty($customer_also_viewed))
    {
        foreach($customer_also_viewed as $viewed)
        {
            $option = 'apus_customer_also_viewed_'.$viewed;
            $option_value = get_option($option);
            
            if(isset($option_value) && !empty($option_value))
            {
                $option_value = explode(',', $option_value);
                if(!in_array($post->ID, $option_value))
                {
                    $option_value[] = $post->ID;
                }
            }
                       
            $option_value = (count($option_value) > 1) ? implode(',', $option_value) : $post->ID;

            update_option($option, $option_value);
        }
    }    
}
add_action('woocommerce_after_single_product', 'yozi_woocommerce_relation_product_options');

function yozi_get_products_customer_also_viewed( $product_id ) {

    $customer_also_viewed = get_option('apus_customer_also_viewed_'.$product_id);  
    if(!empty($customer_also_viewed))        
    {  
        $customer_also_viewed = explode(',',$customer_also_viewed);
        $customer_also_viewed = array_reverse($customer_also_viewed);       
        
        //Skip same product on product page from the list
        if ( ($key = array_search($product_id, $customer_also_viewed)) !== false ) {
            unset($customer_also_viewed[$key] );
        }
        return $customer_also_viewed;
    }
    return false;
}

// hooks
if ( !function_exists('yozi_woocommerce_enqueue_styles') ) {
    function yozi_woocommerce_enqueue_styles() {
        wp_enqueue_style( 'yozi-woocommerce', get_template_directory_uri() .'/css/woocommerce.css' , 'yozi-woocommerce-front' , YOZI_THEME_VERSION, 'all' );
        
        wp_enqueue_script( 'sticky-kit', get_template_directory_uri() . '/js/sticky-kit.js', array( 'jquery' ), '20150330', true );

        wp_register_script( 'yozi-woocommerce', get_template_directory_uri() . '/js/woocommerce.js', array( 'jquery', 'jquery-unveil', 'slick' ), '20150330', true );

        $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : site_url();
        $options = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'enable_search' => (yozi_get_config('enable_autocompleate_search', true) ? '1' : '0'),
            'template' => apply_filters( 'yozi_autocompleate_search_template', '<a href="{{url}}" class="media autocompleate-media"><div class="media-left media-middle"><img src="{{image}}" class="media-object" height="40" width="40"></div><div class="media-body media-middle"><h4>{{{title}}}</h4><p class="price">{{{price}}}</p></div></a>' ),
            'empty_msg' => apply_filters( 'yozi_autocompleate_search_empty_msg', esc_html__( 'Unable to find any products that match the currenty query', 'yozi' ) ),

            'success'       => sprintf( '<div class="woocommerce-message">%s <a class="button btn btn-primary btn-inverse wc-forward" href="%s">%s</a></div>', esc_html__( 'Products was successfully added to your cart.', 'yozi' ), $cart_url, esc_html__( 'View Cart', 'yozi' ) ),
            'empty'         => sprintf( '<div class="woocommerce-error">%s</div>', esc_html__( 'No Products selected.', 'yozi' ) ),
            'nonce' => wp_create_nonce( 'ajax-nonce' ),
            'view_more_text' => esc_html__('View More', 'yozi'),
            'view_less_text' => esc_html__('View Less', 'yozi'),
        );
        wp_localize_script( 'yozi-woocommerce', 'yozi_woo_options', $options );
        wp_enqueue_script( 'yozi-woocommerce' );
        
        wp_enqueue_script( 'wc-add-to-cart-variation' );
    }
}
add_action( 'wp_enqueue_scripts', 'yozi_woocommerce_enqueue_styles', 99 );

// cart
if ( !function_exists('yozi_woocommerce_header_add_to_cart_fragment') ) {
    function yozi_woocommerce_header_add_to_cart_fragment( $fragments ){
        global $woocommerce;
        $fragments['.cart .count'] =  ' <span class="count"> '. $woocommerce->cart->cart_contents_count .' </span> ';
        $fragments['.footer-mini-cart .count'] =  ' <span class="count"> '. $woocommerce->cart->cart_contents_count .' </span> ';
        $fragments['.cart .total-minicart'] = '<div class="total-minicart">'. $woocommerce->cart->get_cart_total(). '</div>';
        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'yozi_woocommerce_header_add_to_cart_fragment' );

// breadcrumb for woocommerce page
if ( !function_exists('yozi_woocommerce_breadcrumb_defaults') ) {
    function yozi_woocommerce_breadcrumb_defaults( $args ) {
        $breadcrumb_img = yozi_get_config('woo_breadcrumb_image');
        $breadcrumb_color = yozi_get_config('woo_breadcrumb_color');
        $style = array();
        $show_breadcrumbs = yozi_get_config('show_product_breadcrumbs');
        if (!is_single()) {
            $show_breadcrumbs = yozi_get_config('show_product_breadcrumbs');
        }
        if ( !$show_breadcrumbs ) {
            $style[] = 'display:none';
        }
        if( $breadcrumb_color  ){
            $style[] = 'background-color:'.$breadcrumb_color;
        }
        if ( isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) ) {
            $style[] = 'background-image:url(\''.esc_url($breadcrumb_img['url']).'\')';
        }
        $estyle = !empty($style)? ' style="'.implode(";", $style).'"':"";

        $full_width = apply_filters('yozi_woocommerce_content_class', 'container');

        $args['wrap_before'] = '<section id="apus-breadscrumb" class="apus-breadscrumb"'.$estyle.'><div class="'.$full_width.'"><div class="wrapper-breads"><div class="breadscrumb-inner"><ol class="apus-woocommerce-breadcrumb breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>';
        $args['wrap_after'] = '</ol></div></div></div></section>';

        return $args;
    }
}
add_filter( 'woocommerce_breadcrumb_defaults', 'yozi_woocommerce_breadcrumb_defaults' );
add_action( 'yozi_woo_template_main_before', 'woocommerce_breadcrumb', 30, 0 );

// display woocommerce modes
if ( !function_exists('yozi_woocommerce_display_modes') ) {
    function yozi_woocommerce_display_modes(){
        global $wp;
        $current_url = yozi_shop_page_link(true);

        $url_grid = add_query_arg( 'display_mode', 'grid', remove_query_arg( 'display_mode', $current_url ) );
        $url_list = add_query_arg( 'display_mode', 'list', remove_query_arg( 'display_mode', $current_url ) );

        $woo_mode = yozi_woocommerce_get_display_mode();

        echo '<div class="display-mode pull-right">';
        echo '<a href="'.  $url_grid  .'" class=" change-view '.($woo_mode == 'grid' ? 'active' : '').'"><i class="ti-layout-grid3"></i></a>';
        echo '<a href="'.  $url_list  .'" class=" change-view '.($woo_mode == 'list' ? 'active' : '').'"><i class="ti-view-list-alt"></i></a>';
        echo '</div>'; 
    }
}

if ( !function_exists('yozi_woocommerce_get_display_mode') ) {
    function yozi_woocommerce_get_display_mode() {
        $woo_mode = yozi_get_config('product_display_mode', 'grid');
        $args = array( 'grid', 'list' );
        if ( isset($_COOKIE['yozi_woo_mode']) && in_array($_COOKIE['yozi_woo_mode'], $args) ) {
            $woo_mode = $_COOKIE['yozi_woo_mode'];
        }
        return $woo_mode;
    }
}

if(!function_exists('yozi_shop_page_link')) {
    function yozi_shop_page_link($keep_query = false ) {
        /*-----15-10-2019----*/
        /*if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url();
        } elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
            $link = get_post_type_archive_link( 'product' );
        } else {
            $link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
        }*/
        global $wp;
        $link = home_url( $wp->request );

        if( $keep_query ) {
            // Keep query string vars intact
            foreach ( $_GET as $key => $val ) {
                if ( 'orderby' === $key || 'submit' === $key ) {
                    continue;
                }
                //$link = add_query_arg( $key, $val, $link );
                $link = add_query_arg( $_GET, $link );

            }
        }
        return $link;
    }
}


if(!function_exists('yozi_filter_before')){
    function yozi_filter_before(){
        echo '<div class="wrapper-fillter"><div class="apus-filter clearfix">';
    }
}
if(!function_exists('yozi_filter_after')){
    function yozi_filter_after(){
        echo '</div></div>';
    }
}
add_action( 'woocommerce_before_shop_loop', 'yozi_filter_before' , 1 );
add_action( 'woocommerce_before_shop_loop', 'yozi_filter_after' , 40 );

// add woo discounts
if(!function_exists('yozi_discounts_before')){
    function yozi_discounts_before(){
        echo '<div class="apus-discounts">';
        echo ' <h3 class="title"> <span class="icon"><i class="ti-hand-point-down"></i></span> '.esc_html__('Bulk Discount', 'yozi').'</h3> ';
    }
}
if(!function_exists('yozi_discounts_after')){
    function yozi_discounts_after(){
        echo '</div>';
    }
}

// set display mode to cookie
if ( !function_exists('yozi_before_woocommerce_init') ) {
    function yozi_before_woocommerce_init() {
        if( isset($_GET['display_mode']) && ($_GET['display_mode']=='list' || $_GET['display_mode']=='grid') ){  
            setcookie( 'yozi_woo_mode', trim($_GET['display_mode']) , time()+3600*24*100,'/' );
            $_COOKIE['yozi_woo_mode'] = trim($_GET['display_mode']);
        }
    }
}
add_action( 'init', 'yozi_before_woocommerce_init' );

// Number of products per page
if ( !function_exists('yozi_woocommerce_shop_per_page') ) {
    function yozi_woocommerce_shop_per_page($number) {
        if(is_admin())
			return;
        if ( isset( $_REQUEST['wppp_ppp'] ) ) :
            $number = intval( $_REQUEST['wppp_ppp'] );
            WC()->session->set( 'products_per_page', intval( $_REQUEST['wppp_ppp'] ) );
        elseif ( isset( $_REQUEST['ppp'] ) ) :
            $number = intval( $_REQUEST['ppp'] );
            WC()->session->set( 'products_per_page', intval( $_REQUEST['ppp'] ) );
        elseif ( WC()->session->__isset( 'products_per_page' ) ) :
            $number = intval( WC()->session->__get( 'products_per_page' ) );
        else :
            $value = yozi_get_config('number_products_per_page', 12);
            $number = intval( $value );
        endif;
        
        return $number;

    }
}
add_filter( 'loop_shop_per_page', 'yozi_woocommerce_shop_per_page', 30 );

// Number of products per row
if ( !function_exists('yozi_woocommerce_shop_columns') ) {
    function yozi_woocommerce_shop_columns($number) {
        $value = yozi_get_config('product_columns');
        if ( in_array( $value, array(1, 2, 3, 4, 5, 6) ) ) {
            $number = $value;
        }
        return $number;
    }
}
add_filter( 'loop_shop_columns', 'yozi_woocommerce_shop_columns' );

// share box
if ( !function_exists('yozi_woocommerce_share_box') ) {
    function yozi_woocommerce_share_box() {
        if ( yozi_get_config('show_product_social_share') ) {
            get_template_part( 'template-parts/sharebox' );
        }
    }
}
add_filter( 'woocommerce_single_product_summary', 'yozi_woocommerce_share_box', 100 );


// quickview
if ( !function_exists('yozi_woocommerce_quickview') ) {
    function yozi_woocommerce_quickview() {
        if ( !empty($_GET['product_id']) ) {
            $args = array(
                'post_type' => 'product',
                'post__in' => array($_GET['product_id'])
            );
            $query = new WP_Query($args);
            if ( $query->have_posts() ) {
                while ($query->have_posts()): $query->the_post(); global $product;
                    $vat = $_SESSION['vat'];
                    wc_get_template_part( 'content', 'product-quickview' );
                endwhile;
            }
            wp_reset_postdata();
        }
        die;
    }
}

if ( yozi_get_global_config('show_quickview') ) {
    add_action( 'wp_ajax_yozi_quickview_product', 'yozi_woocommerce_quickview' );
    add_action( 'wp_ajax_nopriv_yozi_quickview_product', 'yozi_woocommerce_quickview' );
}

// swap effect
if ( !function_exists('yozi_swap_images') ) {
    /*function yozi_swap_images() {
        global $post, $product, $woocommerce;
        
        $output = '';
        $class = "attachment-shop_catalog size-shop_catalog image-no-effect";
        if (has_post_thumbnail()) {
            $swap_image = yozi_get_config('enable_swap_image', true);
            if ( $swap_image ) {
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids && isset($attachment_ids[0])) {
                    $class = "attachment-shop_catalog size-shop_catalog image-hover";
                    $swap_class = "attachment-shop_catalog size-shop_catalog image-effect";
                    $output .= yozi_get_attachment_thumbnail( $attachment_ids[0], 'shop_catalog', false, array('class' => $swap_class), false);
                }
            }
            $output .= yozi_get_attachment_thumbnail( get_post_thumbnail_id(), 'shop_catalog' , false, array('class' => $class), false);
        } else {
            $image_sizes = get_option('shop_catalog_image_size');
            $placeholder_width = $image_sizes['width'];
            $placeholder_height = $image_sizes['height'];

            $output .= '<img src="'.esc_url(wc_placeholder_img_src()).'" alt="'.esc_html__('Placeholder' , 'yozi').'" class="'.$class.'" width="'.$placeholder_width.'" height="'.$placeholder_height.'" />';
        }
        echo trim($output);
    } */
}
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'yozi_swap_images', 10);

if ( !function_exists('yozi_product_image') ) {
    function yozi_product_image($thumb = 'shop_thumbnail') {
        $swap_image = (bool)yozi_get_config('enable_swap_image', true);
        ?>
        <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="product-image">
            <?php yozi_product_get_image($thumb, $swap_image); ?>
        </a>
        <?php
    }
}
// get image
if ( !function_exists('yozi_product_get_image') ) {
    function yozi_product_get_image($thumb = 'shop_thumbnail', $swap = true) {
        global $post, $product, $woocommerce;
        
        $output = '';
        $class = "attachment-$thumb size-$thumb image-no-effect";
        if (has_post_thumbnail()) {
            if ( $swap ) {
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids && isset($attachment_ids[0])) {
                    $class = "attachment-$thumb size-$thumb image-hover";
                    $swap_class = "attachment-$thumb size-$thumb image-effect";
                    $output .= yozi_get_attachment_thumbnail( $attachment_ids[0], $thumb , false, array('class' => $swap_class), false);
                }
            }
            $output .= yozi_get_attachment_thumbnail( get_post_thumbnail_id(), $thumb , false, array('class' => $class), false);
        } else {
            $image_sizes = get_option('shop_catalog_image_size');
            $placeholder_width = $image_sizes['width'];
            $placeholder_height = $image_sizes['height'];

            $output .= '<img src="'.esc_url(wc_placeholder_img_src()).'" alt="'.esc_html__('Placeholder' , 'yozi').'" class="'.$class.'" width="'.$placeholder_width.'" height="'.$placeholder_height.'" />';
        }
        echo trim($output);
    }
}

// layout class for woo page
if ( !function_exists('yozi_woocommerce_content_class') ) {
    function yozi_woocommerce_content_class( $class ) {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        if( yozi_get_config('product_'.$page.'_fullwidth') ) {
            return 'container-fluid';
        }
        return $class;
    }
}
add_filter( 'yozi_woocommerce_content_class', 'yozi_woocommerce_content_class' );

// get layout configs
if ( !function_exists('yozi_get_woocommerce_layout_configs') ) {
    function yozi_get_woocommerce_layout_configs() {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        // lg and md for fullwidth
        if( yozi_get_config('product_'.$page.'_fullwidth') ) {
            $sidebar_width = 'col-lg-2 col-md-12 ';
            $main_width = 'col-lg-10 col-md-12 ';
        }else{
            $sidebar_width = 'col-lg-3 col-md-12 ';
            $main_width = 'col-lg-9 col-md-12 ';
        }
        $left = yozi_get_config('product_'.$page.'_left_sidebar');
        $right = yozi_get_config('product_'.$page.'_right_sidebar');

        switch ( yozi_get_config('product_'.$page.'_layout') ) {
            case 'left-main':
                $configs['left'] = array( 'sidebar' => $left, 'class' => $sidebar_width.'col-sm-12 col-xs-12 '  );
                $configs['main'] = array( 'class' => $main_width.'col-sm-12 col-xs-12' );
                break;
            case 'main-right':
                $configs['right'] = array( 'sidebar' => $right,  'class' => $sidebar_width.'col-sm-12 col-xs-12 ' ); 
                $configs['main'] = array( 'class' => $main_width.'col-sm-12 col-xs-12' );
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                break;
            case 'left-main-right':
                $configs['left'] = array( 'sidebar' => $left,  'class' => 'col-md-3 col-sm-12 col-xs-12'  );
                $configs['right'] = array( 'sidebar' => $right, 'class' => 'col-md-3 col-sm-12 col-xs-12' ); 
                $configs['main'] = array( 'class' => 'col-md-6 col-sm-12 col-xs-12' );
                break;
            default:
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                break;
        }

        return $configs; 
    }
}

if ( !function_exists( 'yozi_product_review_tab' ) ) {
    function yozi_product_review_tab($tabs) {
        global $post;
        if ( !yozi_get_config('show_product_review_tab') && isset($tabs['reviews']) ) {
            unset( $tabs['reviews'] ); 
        }
        if ( get_post_meta( $post->ID, 'apus_product_features', true ) ) {
            $tabs['specifications'] = array(
                'title' => esc_html__('Features', 'yozi'),
                'priority' => 15,
                'callback' => 'yozi_display_features'
            );
        }

        if ( yozi_get_config('show_product_accessory')  ) {
            $pids = Yozi_Woo_Accessories::get_accessories( $post->ID );
            if ( !empty($pids) ) {
                $tabs['accessories'] = array(
                    'title' => esc_html__('Accessories', 'yozi'),
                    'priority' => 13,
                    'callback' => 'yozi_woocommerce_accessories'
                );
            }
        }

        if ( !yozi_get_config('hidden_product_additional_information_tab') && isset($tabs['additional_information']) ) {
            unset( $tabs['additional_information'] ); 
        }

        return $tabs;
    }
}
add_filter( 'woocommerce_product_tabs', 'yozi_product_review_tab', 90 );

function yozi_woocommerce_get_ajax_products() {
    $settings = isset($_POST['settings']) ? $_POST['settings'] : '';
    $tab = isset($_POST['tab']) ? $_POST['tab'] : '';
    
    if ( empty($settings) || empty($tab) ) {
        exit();
    }

    $categories = isset($tab['category']) ? $tab['category'] : '';
    $columns = isset($settings['columns']) ? $settings['columns'] : 4;
    $rows = isset($settings['rows']) ? $settings['rows'] : 1;
    $show_nav = isset($settings['show_nav']) ? $settings['show_nav'] : false;
    $show_pagination = isset($settings['show_pagination']) ? $settings['show_pagination'] : false;
    $number = isset($settings['number']) ? $settings['number'] : 4;
    $product_type = isset($tab['type']) ? $tab['type'] : 'recent_product';

    $layout_type = isset($settings['layout_type']) ? $settings['layout_type'] : 'grid';

    $categories = !empty($categories) ? array($categories) : array();
    $args = array(
        'categories' => $categories,
        'product_type' => $product_type,
        'paged' => 1,
        'post_per_page' => $number,
    );
    $loop = yozi_get_products( $args );
    if ( $loop->have_posts() ) {
        $max_pages = $loop->max_num_pages;
        wc_get_template( 'layout-products/'.$layout_type.'.php' , array(
            'loop' => $loop,
            'columns' => $columns,
            'rows' => $rows,
            'show_nav' => $show_nav,
            'show_pagination' => $show_pagination,
        ) );
    }
    exit();
}
add_action( 'wp_ajax_yozi_ajax_get_products', 'yozi_woocommerce_get_ajax_products' );
add_action( 'wp_ajax_nopriv_yozi_ajax_get_products', 'yozi_woocommerce_get_ajax_products' );


// Wishlist
add_filter( 'yith_wcwl_button_label', 'yozi_woocomerce_icon_wishlist'  );
add_filter( 'yith-wcwl-browse-wishlist-label', 'yozi_woocomerce_icon_wishlist_add' );
function yozi_woocomerce_icon_wishlist( $value='' ){
    return '<i class="ti-heart"></i>'.'<span class="sub-title">'.esc_html__('Add to Wishlist','yozi').'</span>';
}

function yozi_woocomerce_icon_wishlist_add(){
    return '<i class="ti-heart"></i>'.'<span class="sub-title">'.esc_html__('Wishlisted','yozi').'</span>';
}
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


function yozi_get_all_subcategories_levels($parent_id, $parent_slug, &$return = array()) {
    $return[] = $parent_slug;
    $args = array(
        'hierarchical' => true,
        'show_option_none' => '',
        'hide_empty' => true,
        'parent' => $parent_id,
        'taxonomy' => 'product_cat'
    );
    $cats = get_categories($args);
    foreach ($cats as $cat) {
        yozi_get_all_subcategories_levels($cat->term_id, $cat->slug, $return);
    }
    return $return;
}

function yozi_display_products_block_by_category($blocks, $parent_slug) {
    $subcategories = array();
    if ( is_shop() ) {
        $term_name = esc_html__('Shop', 'yozi');
    } else {
        $term = get_term_by( 'slug', $parent_slug, 'product_cat' );
        if ( empty( $term ) || is_wp_error( $term ) ) {
            return;
        }
        $term_name = $term->name;
        // get all sub
        yozi_get_all_subcategories_levels($term->term_id, $parent_slug, $subcategories);
    }
    if ( isset($blocks['placebo']) ) {
        unset($blocks['placebo']);
    }
    foreach ($blocks as $key => $value) {
        $categories = yozi_get_config( 'products_'.$key.'_categories' );
        if ( (is_shop() && yozi_get_config( 'show_'.$key.'_in_shop', true ) ) || (is_array($categories) && in_array($term->slug, $categories)) ) {
            wc_get_template( 'content-archive-block-products.php' , array(
                'type' => $key,
                'term_name' => $term_name,
                'categories' => $subcategories
            ) );
        }
    }
}

function yozi_display_features() {
    get_template_part( 'woocommerce/single-product/tabs/features' );
}

function yozi_woocommerce_accessories() {
    get_template_part( 'woocommerce/single-product/tabs/accessories' );
}

function yozi_display_bought_together_product() {
    if ( yozi_get_config('show_product_product_bought_together') ) {
        get_template_part( 'woocommerce/single-product/bought-together-product' );
    }
}

function yozi_display_viewed_together_product() {
    if ( yozi_get_config('show_product_product_viewed_together') ) {
        get_template_part( 'woocommerce/single-product/viewed-together-product' );
    }
}

function yozi_woocommerce_single_countdown() {
    if ( yozi_get_config('show_product_countdown_timer') ) {
        get_template_part( 'woocommerce/single-product/countdown' );
    }
}
add_action('woocommerce_single_product_summary', 'yozi_woocommerce_single_countdown', 15);


if ( ! function_exists( 'yozi_wc_products_per_page' ) ) {
    function yozi_wc_products_per_page() {
        global $wp_query;

        $action = '';
        $cat                = $wp_query->get_queried_object();
        $return_to_first    = apply_filters( 'yozi_wc_ppp_return_to_first', false );
        $total              = $wp_query->found_posts;
        $per_page           = $wp_query->get( 'posts_per_page' );
        $_per_page          = yozi_get_config('number_products_per_page', 12);

        // Generate per page options
        $products_per_page_options = array();
        while ( $_per_page < $total ) {
            $products_per_page_options[] = $_per_page;
            $_per_page = $_per_page * 2;
        }

        if ( empty( $products_per_page_options ) ) {
            return;
        }

        $products_per_page_options[] = -1;

        $query_string = ! empty( $_GET['QUERY_STRING'] ) ? '?' . add_query_arg( array( 'ppp' => false ), $_GET['QUERY_STRING'] ) : null;

        if ( isset( $cat->term_id ) && isset( $cat->taxonomy ) && $return_to_first ) {
            $action = get_term_link( $cat->term_id, $cat->taxonomy ) . $query_string;
        } elseif ( $return_to_first ) {
            $action = get_permalink( wc_get_page_id( 'shop' ) ) . $query_string;
        }

        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        ?>
        <form method="POST" action="<?php echo esc_url( $action ); ?>" class="form-yozi-ppp">
            <?php
            foreach ( $_GET as $key => $value ) {
                if ( 'ppp' === $key || 'submit' === $key ) {
                    continue;
                }
                if ( is_array( $value ) ) {
                    foreach( $value as $i_value ) {
                        ?>
                        <input type="hidden" name="<?php echo esc_attr( $key ); ?>[]" value="<?php echo esc_attr( $i_value ); ?>" />
                        <?php
                    }
                } else {
                    ?><input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" /><?php
                }
            }
            ?>

            <select name="ppp" onchange="this.form.submit()" class="yozi-wc-wppp-select">
                <?php foreach( $products_per_page_options as $key => $value ) { ?>
                    <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $per_page ); ?>><?php
                        $ppp_text = apply_filters( 'yozi_wc_ppp_text', esc_html__( 'Show: %s', 'yozi' ), $value );
                        esc_html( printf( $ppp_text, $value == -1 ? esc_html__( 'All', 'yozi' ) : $value ) );
                    ?></option>
                <?php } ?>
            </select>
        </form>
        <?php
    }
}

function yozi_woo_after_shop_loop_before() {
    ?>
    <div class="apus-after-loop-shop clearfix">
    <?php
}
function yozi_woo_after_shop_loop_after() {
    ?>
    </div>
    <?php
}
// add_action( 'woocommerce_after_shop_loop', 'yozi_woo_after_shop_loop_before', 1 );
// add_action( 'woocommerce_after_shop_loop', 'yozi_woo_after_shop_loop_after', 99999 );
// add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 30 );
// add_action( 'woocommerce_after_shop_loop', 'yozi_wc_products_per_page', 20 );


function yozi_woo_display_product_cat($product_id) {
    $terms = get_the_terms( $product_id, 'product_cat' );
    if ( !empty($terms) ) { ?>
        <div class="product-cats">
        <?php foreach ( $terms as $term ) {
			if(strlen($term->name) > 25)
			{
				$catname = substr($term->name,0,25).'...';
			}
			else{
				$catname = $term->name;
			}
            echo '<a href="' . get_term_link( $term->term_id ) . '">' . $catname . '</a>';
            break;
        } ?>
        </div>
    <?php
    }
}
if ( !function_exists ('yozi_onsale_price_show') ) {
    function yozi_onsale_price_show() {
        global $product;
        if( $product->is_on_sale() ) {
           // return $product->get_regular_price() - $product->get_sale_price();
        }
        return '';
    }
}

// catalog mode
add_action( 'wp', 'yozi_catalog_mode_init' );
add_action( 'wp', 'yozi_pages_redirect' );


function yozi_catalog_mode_init() {

    if( ! yozi_get_config( 'enable_shop_catalog' ) ) return false;

    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

}

function yozi_pages_redirect() {
    if( ! yozi_get_config( 'enable_shop_catalog' ) ) return false;

    $cart     = is_page( wc_get_page_id( 'cart' ) );
    $checkout = is_page( wc_get_page_id( 'checkout' ) );

    wp_reset_postdata();

    if ( $cart || $checkout ) {
        wp_redirect( home_url() );
        exit;
    }
}
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
    echo 'Delivery & Returns';
             
}

add_filter( 'woocommerce_product_tabs', 'wc_change_product_description_tab_title', 10, 1 );
function wc_change_product_description_tab_title( $tabs ) {
  global $post;
	if ( isset( $tabs['description']['title'] ) )
		$tabs['description']['title'] = 'Product Information';
	
	if ( isset( $tabs['additional_information']['title'] ) )
		$tabs['additional_information']['title'] = 'Size Guide';
	
	if(is_single() && $tabs['description']['title']==""){
		$tabs['description']['title'] = 'Product Information';
	}

	$tabs['additional_information']['callback'] = 'woo_custom_sizeguide_tab_content';
	
	return $tabs;
}

function woo_custom_sizeguide_tab_content()
{
	 global $wpdb;
	 global $product;
	 $product_sku = $product->get_sku();
	 $getsizeinfo = $wpdb->get_results('Select * from wp_sizechart where product_code="'.$product_sku.'" ');
	 $sizeinfo = json_decode($getsizeinfo[0]->size_info);
	// echo $product->id;
	$sizeinfometa = get_post_meta($product->id,'sizeguide');
	 if(empty($sizeinfometa))
	 {
		 echo "<table class='sizeguide'>";
		 
		if($sizeinfo->other[0]->C != 'Size:'){
			echo "<tr>";
		 foreach($sizeinfo->size as $i=>$info)
			{
				if($i>1 && $info !='')
				{
					echo "<th>".$info."</th>";
				}
				
			}
		 echo "</tr>";
		}
		 
		 foreach($sizeinfo->other as $otherinfo)
			{
				echo "<tr>";
				foreach($otherinfo as $k=>$info)
				{
					if($k != 'A' && $k !='B' && $info != '')
					{
						if($otherinfo->C == 'Size:')
						{
							echo "<th>".$info."</th>";
						}
						else
						{
							echo "<td>".$info."</td>";
						}
						
					}
				}
				echo "</tr>";
			}
		
		 echo "</table>";
	 }
	 else 
	 {
		 echo html_entity_decode($sizeinfometa[0]);
	 }
	//echo "<pre>"; print_r($sizeinfo); echo "</pre>"; 
}
// Change the description tab heading to product name
add_filter( 'woocommerce_product_description_heading', 'wc_change_product_description_tab_heading', 10, 1 );
function wc_change_product_description_tab_heading( $title ) {
	global $post;
	return 'FEATURES';
}

add_filter( 'woocommerce_product_tabs', 'woo_reorder_tabs', 98 );
function woo_reorder_tabs( $tabs ) {

	$tabs['reviews']['priority'] = 4;			// Reviews first
	$tabs['description']['priority'] = 1;			// Description second
	$tabs['additional_information']['priority'] = 2;	// Additional information third


	return $tabs;
}
function yozi_display_brand_logos_partner()
{
	echo '<div style="clear:both; background-color:#e8e8e8;" class="vc_row wpb_row vc_row-fluid brandlogos">'.do_shortcode('[gs_logo]').'</div>';
	//do_shortcode('[lsp_slider slider=brand_company_logos]');
} 