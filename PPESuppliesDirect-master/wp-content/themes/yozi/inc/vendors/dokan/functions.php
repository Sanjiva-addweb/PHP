<?php

if ( ! function_exists( 'yozi_dokan_sidebars' ) ) {
	
	function yozi_dokan_sidebars() {
		register_sidebar( array(
			'name' 				=> esc_html__( 'Store Sidebar', 'yozi' ),
			'id' 				=> 'store-sidebar',
			'before_widget'		=> '<aside class="widget %2$s">',
			'after_widget' 		=> '</aside>',
			'before_title' 		=> '<h2 class="widget-title">',
			'after_title' 		=> '</h2>'
		));
	}

}

add_action( 'widgets_init', 'yozi_dokan_sidebars' );


function yozi_dokan_redux_config( $sections, $sidebars, $columns ) {
	// Dokan Store Sidebar
    $dokan_fields = array(
        array(
            'id' => 'dokan_sidebar_layout',
            'type' => 'image_select',
            'compiler' => true,
            'title' => esc_html__('Dokan Store Layout', 'yozi'),
            'subtitle' => esc_html__('Select the layout you want to apply on your Single Product Page.', 'yozi'),
            'options' => array(
                'main' => array(
                    'title' => esc_html__('Main Only', 'yozi'),
                    'alt' => esc_html__('Main Only', 'yozi'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                ),
                'left-main' => array(
                    'title' => esc_html__('Left - Main Sidebar', 'yozi'),
                    'alt' => esc_html__('Left - Main Sidebar', 'yozi'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                ),
                'main-right' => array(
                    'title' => esc_html__('Main - Right Sidebar', 'yozi'),
                    'alt' => esc_html__('Main - Right Sidebar', 'yozi'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                ),
            ),
            'default' => 'left-main'
        ),
        array(
            'id' => 'dokan_sidebar_fullwidth',
            'type' => 'switch',
            'title' => esc_html__('Is Full Width?', 'yozi'),
            'default' => false
        ),
    );

    if ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' ) !== 'off' ) {
    	
    	$dokan_fields[] = array(
            'id' => 'dokan_left_sidebar',
            'type' => 'select',
            'title' => esc_html__('Dokan Store Left Sidebar', 'yozi'),
            'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'yozi'),
            'options' => $sidebars
        );

        $dokan_fields[] = array(
            'id' => 'dokan_right_sidebar',
            'type' => 'select',
            'title' => esc_html__('Dokan Store Right Sidebar', 'yozi'),
            'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'yozi'),
            'options' => $sidebars
        );
    }
    $sections[] = array(
        'title' => esc_html__('Dokan Store Sidebar', 'yozi'),
        'fields' => $dokan_fields
    );

    return $sections;
}
add_filter( 'yozi_redux_framwork_configs', 'yozi_dokan_redux_config', 20, 3 );



// layout class for woo page
if ( !function_exists('yozi_dokan_content_class') ) {
    function yozi_dokan_content_class( $class ) {
        if( yozi_get_config('dokan_sidebar_fullwidth') ) {
            return 'container-fluid';
        }
        return $class;
    }
}
add_filter( 'yozi_dokan_content_class', 'yozi_dokan_content_class' );

// get layout configs
if ( !function_exists('yozi_get_dokan_layout_configs') ) {
    function yozi_get_dokan_layout_configs() {
        
                // lg and md for fullwidth
        if( yozi_get_config('dokan_sidebar_fullwidth') ) {
            $sidebar_width = 'col-lg-2 col-md-3 ';
            $main_width = 'col-lg-10 col-md-9';
        }else{
            $sidebar_width = 'col-lg-3 col-md-3 ';
            $main_width = 'col-lg-9 col-md-9 ';
        }

        $left = yozi_get_config('dokan_left_sidebar');
        $right = yozi_get_config('dokan_right_sidebar');

        switch ( yozi_get_config('dokan_sidebar_layout') ) {
            case 'left-main':
                $configs['left'] = array( 'sidebar' => $left, 'class' => $sidebar_width.' col-sm-12 col-xs-12 hidden-sm hidden-xs'  );
                $configs['main'] = array( 'class' => $main_width.' col-sm-12 col-xs-12' );
                break;
            case 'main-right':
                $configs['right'] = array( 'sidebar' => $right,  'class' => $sidebar_width.' col-sm-12 col-xs-12 hidden-sm hidden-xs' ); 
                $configs['main'] = array( 'class' => $main_width.' col-sm-12 col-xs-12' );
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