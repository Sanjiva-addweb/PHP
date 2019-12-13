<?php

if ( !function_exists( 'yozi_autocomplete_search' ) ) {
    function yozi_autocomplete_search() {
        if ( yozi_get_config('enable_autocompleate_search', true) ) {
            wp_enqueue_script( 'typeahead-bundle', get_template_directory_uri() . '/js/typeahead.bundle.min.js', array('jquery'), null, true);
            wp_enqueue_script( 'handlebars', get_template_directory_uri() . '/js/handlebars.min.js', array('typeahead-bundle'), null, true);

            add_action( 'wp_ajax_yozi_autocomplete_search', 'yozi_autocomplete_suggestions' );
            add_action( 'wp_ajax_nopriv_yozi_autocomplete_search', 'yozi_autocomplete_suggestions' );
        }
    }
}
add_action( 'init', 'yozi_autocomplete_search' );

if ( !function_exists( 'yozi_autocomplete_suggestions' ) ) {
    function yozi_autocomplete_suggestions() {
        // Query for suggestions
        check_ajax_referer( 'yozi-ajax-nonce', 'security' );
        $args = array( 's' => $_REQUEST['s'] );
        if ( isset($_REQUEST['post_type']) ) {
            $args['post_type'] = $_REQUEST['post_type'];
        }
        if ( isset($_REQUEST['category']) ) {
            $args['product_cat'] = $_REQUEST['category'];
        }
        if ( !isset($args['post_type']) ) {
            $args['post_type'] = array( 'product' );
        }
        $posts = get_posts( $args );
        $suggestions = array();

        global $post;
        foreach ($posts as $post): setup_postdata($post);
            
            $suggestion = array();
            $suggestion['title'] = esc_html($post->post_title);
            $suggestion['url'] = get_permalink($post);
            if ( has_post_thumbnail( $post->ID ) ) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
                $suggestion['image'] = $image[0];
            } else {
                $suggestion['image'] = '';
            }

            $product = new WC_Product( get_the_ID() );
            $suggestion['price'] = $product->get_price_html();
            $suggestion['id'] = $post->ID;

            $suggestions[] = $suggestion;
        endforeach;
        
        echo json_encode( $suggestions );
     
        exit;
    }
}