<?php
/**
 * yozi functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Yozi
 * @since Yozi 2.0.4
 */

define( 'YOZI_THEME_VERSION', '2.0.4' );
define('YOZI_DEMO_MODE', false);

if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

if ( ! function_exists( 'yozi_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Yozi 1.0
 */
function yozi_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on yozi, use a find and replace
	 * to change 'yozi' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'yozi', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 750, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'yozi' ),
		'top-menu' => esc_html__( 'Top Menu', 'yozi' ),
		'vertical-menu' => esc_html__( 'Vertical Menu', 'yozi' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	add_theme_support( "woocommerce" );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = yozi_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'yozi_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	add_editor_style( array( 'css/template.css' ) );

	yozi_get_load_plugins();
}
endif; // yozi_setup
add_action( 'after_setup_theme', 'yozi_setup' );

/**
 * Load Google Front
 */
function yozi_get_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
    * supported by Montserrat, translate this to 'off'. Do not translate
    * into your own language.
    */
    $rubik = _x( 'on', 'Rubik font: on or off', 'yozi' );
    $satisfy = _x( 'on', 'Satisfy font: on or off', 'yozi' );

    if ( 'off' !== $rubik || 'off' !== $satisfy  ) {
        $font_families = array();
        if ( 'off' !== $rubik ) {
            $font_families[] = 'Rubik:300,400,500,700,900';
        }
		if ( 'off' !== $satisfy ) {
            $font_families[] = 'Satisfy';
        }
 		$font_google_code = yozi_get_config('font_google_code');
 		$font_source = yozi_get_config('font_source');
 		if ( $font_source == 2 && !empty($font_google_code) ) {
 			$font_families[] = $font_google_code;
 		}
        $query_args = array(
            'family' => ( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 		
        $fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
    }
 
    return esc_url_raw( $fonts_url );
}

function yozi_fonts_url() {
	wp_enqueue_style( 'yozi-theme-fonts', yozi_get_fonts_url(), array(), null );
}
add_action('wp_enqueue_scripts', 'yozi_fonts_url');

/**
 * Enqueue scripts and styles.
 *
 * @since Yozi 1.0
 */
function yozi_scripts() {
	// Load our main stylesheet.

	//load font awesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.5.0' );

	// load font themify icon
	wp_enqueue_style( 'font-themify', get_template_directory_uri() . '/css/themify-icons.css', array(), '1.0.0' );
	
	wp_enqueue_style( 'ionicons', get_template_directory_uri() . '/css/ionicons.css', array(), '2.0.0' );

	// load animate version 3.5.0
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), '3.5.0' );

	// load bootstrap style
	if( is_rtl() ){
		wp_enqueue_style( 'bootstrap-rtl', get_template_directory_uri() . '/css/bootstrap-rtl.css', array(), '3.2.0' );
	} else {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.2.0' );
	}
	
	wp_enqueue_style( 'yozi-template', get_template_directory_uri() . '/css/template.css', array(), '3.2' );
	$footer_style = yozi_print_style_footer();
	if ( !empty($footer_style) ) {
		wp_add_inline_style( 'yozi-template', $footer_style );
	}
	$custom_style = yozi_custom_styles();
	if ( !empty($custom_style) ) {
		wp_add_inline_style( 'yozi-template', $custom_style );
	}
	/*-------------------20-11-2019------------------*/
	/*wp_enqueue_style( 'yozi-style', get_template_directory_uri() . '/style.css', array(), '3.2' );*/

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '20150330', true );

	wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '1.8.0', true );
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '1.8.0' );
	
	wp_register_script( 'countdown', get_template_directory_uri() . '/js/countdown.js', array( 'jquery' ), '20150315', true );
	wp_localize_script( 'countdown', 'yozi_countdown_opts', array(
		'days' => esc_html__('Days', 'yozi'),
		'hours' => esc_html__('Hours', 'yozi'),
		'mins' => esc_html__('Mins', 'yozi'),
		'secs' => esc_html__('Secs', 'yozi'),
	));
	wp_enqueue_script( 'countdown' );

	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/js/magnific/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/js/magnific/magnific-popup.css', array(), '1.1.0' );

	wp_enqueue_script( 'jquery-unveil', get_template_directory_uri() . '/js/jquery.unveil.js', array( 'jquery' ), '1.1.0', true );
	
	wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.jquery.min.js', array( 'jquery' ), '0.6.12', true );
	wp_enqueue_style( 'perfect-scrollbar', get_template_directory_uri() . '/css/perfect-scrollbar.css', array(), '0.6.12' );
	
	wp_enqueue_script( 'jquery-mmenu', get_template_directory_uri() . '/js/mmenu/jquery.mmenu.js', array( 'jquery' ), '0.6.12', true );
	wp_enqueue_style( 'jquery-mmenu', get_template_directory_uri() . '/js/mmenu/jquery.mmenu.css', array(), '0.6.12' );

	wp_register_script( 'yozi-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );
	wp_localize_script( 'yozi-script', 'yozi_ajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'ajax_nonce' => wp_create_nonce( "yozi-ajax-nonce" ),
		'next' => esc_html__('Next', 'yozi'),
		'previous' => esc_html__('Previous', 'yozi'),
	));
	wp_enqueue_script( 'yozi-script' );
	
	wp_add_inline_script( 'yozi-script', "(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);" );
}
add_action( 'wp_enqueue_scripts', 'yozi_scripts', 100 );

/**
 * Display descriptions in main navigation.
 *
 * @since Yozi 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function yozi_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'yozi_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Yozi 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function yozi_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'yozi_search_form_modify' );

/**
 * Function get opt_name
 *
 */
function yozi_get_opt_name() {
	return 'yozi_theme_options';
}
add_filter( 'apus_framework_get_opt_name', 'yozi_get_opt_name' );


function yozi_register_demo_mode() {
	if ( defined('YOZI_DEMO_MODE') && YOZI_DEMO_MODE ) {
		return true;
	}
	return false;
}
add_filter( 'apus_framework_register_demo_mode', 'yozi_register_demo_mode' );

function yozi_get_demo_preset() {
	$preset = '';
    if ( defined('YOZI_DEMO_MODE') && YOZI_DEMO_MODE ) {
        if ( isset($_GET['_preset']) && $_GET['_preset'] ) {
            $presets = get_option( 'apus_framework_presets' );
            if ( is_array($presets) && isset($presets[$_GET['_preset']]) ) {
                $preset = $_GET['_preset'];
            }
        } else {
            $preset = get_option( 'apus_framework_preset_default' );
        }
    }
    return $preset;
}

function yozi_register_post_types($post_types) {
	foreach ($post_types as $key => $post_type) {
		if ( $post_type == 'brand' ) {
			unset($post_types[$key]);
		}
	}
	return $post_types;
}
add_filter( 'apus_framework_register_post_types', 'yozi_register_post_types' );

function yozi_get_config($name, $default = '') {
	global $apus_options;
    if ( isset($apus_options[$name]) ) {
        return $apus_options[$name];
    }
    return $default;
}

function yozi_get_global_config($name, $default = '') {
	$options = get_option( 'yozi_theme_options', array() );
	if ( isset($options[$name]) ) {
        return $options[$name];
    }
    return $default;
}

function yozi_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Default', 'yozi' ),
		'id'            => 'sidebar-default',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'yozi' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Topbar Left', 'yozi' ),
		'id'            => 'sidebar-topbar-left',
		'description'   => esc_html__( 'Add widgets here to appear in your Topbar.', 'yozi' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Topbar Right', 'yozi' ),
		'id'            => 'sidebar-topbar-right',
		'description'   => esc_html__( 'Add widgets here to appear in your Topbar.', 'yozi' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Blog sidebar', 'yozi' ),
		'id'            => 'blog-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'yozi' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name' 				=> esc_html__( 'Shop Sidebar', 'yozi' ),
		'id' 				=> 'shop-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'yozi' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	));
	
	register_sidebar( array(
		'name' 				=> esc_html__( 'Popup Newsletter', 'yozi' ),
		'id' 				=> 'popup-newsletter',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'yozi' ),
		'before_widget' => '<aside class="%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	));
}
add_action( 'widgets_init', 'yozi_widgets_init' );

function yozi_get_load_plugins() {

	$plugins[] = array(
		'name'                     => esc_html__( 'Apus Framework For Themes', 'yozi' ),
        'slug'                     => 'apus-framework',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-framework.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'WPBakery Visual Composer', 'yozi' ),
	    'slug'                     => 'js_composer',
	    'required'                 => true,
	    'source'				   => get_template_directory() . '/inc/plugins/js_composer.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Revolution Slider', 'yozi' ),
        'slug'                     => 'revslider',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/revslider.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Cmb2', 'yozi' ),
	    'slug'                     => 'cmb2',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'MailChimp for WordPress', 'yozi' ),
	    'slug'                     => 'mailchimp-for-wp',
	    'required'                 =>  true
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Contact Form 7', 'yozi' ),
	    'slug'                     => 'contact-form-7',
	    'required'                 => true,
	);

	// woocommerce plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'Woocommerce', 'yozi' ),
	    'slug'                     => 'woocommerce',
	    'required'                 => true,
	);

	$plugins[] =(array(
		'name'                     => esc_html__( 'WooCommerce Variation Swatches', 'yozi' ),
	    'slug'                     => 'variation-swatches-for-woocommerce',
	    'required'                 =>  false
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'YITH WooCommerce Compare', 'yozi' ),
	    'slug'                     => 'yith-woocommerce-compare',
	    'required'                 =>  false
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'YITH WooCommerce Wishlist', 'yozi' ),
	    'slug'                     => 'yith-woocommerce-wishlist',
	    'required'                 =>  false
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'WooCommerce Bulk Discount', 'yozi' ),
	    'slug'                     => 'woocommerce-bulk-discount',
	    'required'                 =>  false
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'WooCommerce Multiple Free Gift', 'yozi' ),
	    'slug'                     => 'woocommerce-multiple-free-gift',
	    'required'                 =>  false
	));

	tgmpa( $plugins );
}

require get_template_directory() . '/inc/plugins/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/functions-helper.php';
require get_template_directory() . '/inc/functions-frontend.php';

/**
 * Implement the Custom Header feature.
 *
 */
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/classes/megamenu.php';
require get_template_directory() . '/inc/classes/mobilemenu.php';
require get_template_directory() . '/inc/classes/mobileverticalmenu.php';

/**
 * Custom template tags for this theme.
 *
 */
require get_template_directory() . '/inc/template-tags.php';

if ( defined( 'APUS_FRAMEWORK_REDUX_ACTIVED' ) ) {
	require get_template_directory() . '/inc/vendors/redux-framework/redux-config.php';
	define( 'YOZI_REDUX_FRAMEWORK_ACTIVED', true );
}
if( yozi_is_cmb2_activated() ) {
	require get_template_directory() . '/inc/vendors/cmb2/page.php';
	require get_template_directory() . '/inc/vendors/cmb2/footer.php';
	require get_template_directory() . '/inc/vendors/cmb2/product.php';
	define( 'YOZI_CMB2_ACTIVED', true );
}
if( yozi_is_vc_activated() ) {
	require get_template_directory() . '/inc/vendors/visualcomposer/functions.php';
	require get_template_directory() . '/inc/vendors/visualcomposer/google-maps-styles.php';
	require get_template_directory() . '/inc/vendors/visualcomposer/vc-map-posts.php';
	require get_template_directory() . '/inc/vendors/visualcomposer/vc-map-theme.php';
	define( 'YOZI_VISUALCOMPOSER_ACTIVED', true );
}
if( yozi_is_woocommerce_activated() ) {
	require get_template_directory() . '/inc/vendors/woocommerce/functions.php';
	require get_template_directory() . '/inc/vendors/woocommerce/functions-search.php';
	require get_template_directory() . '/inc/vendors/woocommerce/vc-map.php';
	require get_template_directory() . '/inc/vendors/woocommerce/functions-brand.php';
	require get_template_directory() . '/inc/vendors/woocommerce/functions-accessories.php';
	require get_template_directory() . '/inc/vendors/woocommerce/functions-redux-configs.php';
	define( 'YOZI_WOOCOMMERCE_ACTIVED', true );
}
if( yozi_is_apus_framework_activated() ) {
	require get_template_directory() . '/inc/widgets/contact-info.php';
	require get_template_directory() . '/inc/widgets/custom_menu.php';
	require get_template_directory() . '/inc/widgets/popup_newsletter.php';
	require get_template_directory() . '/inc/widgets/recent_comment.php';
	require get_template_directory() . '/inc/widgets/recent_post.php';
	require get_template_directory() . '/inc/widgets/search.php';
	require get_template_directory() . '/inc/widgets/single_image.php';
	require get_template_directory() . '/inc/widgets/socials.php';
	require get_template_directory() . '/inc/widgets/service.php';
	define( 'YOZI_FRAMEWORK_ACTIVED', true );
}
if( yozi_is_free_gift_activated() ) {
	require get_template_directory() . '/inc/vendors/woocommerce-multiple-free-gift/functions.php';
}
if( yozi_is_dokan_activated() ) {
	require get_template_directory() . '/inc/vendors/dokan/functions.php';
}
if( yozi_is_wcvendors_activated() ) {
	require get_template_directory() . '/inc/vendors/wc-vendors/functions.php';
	define( 'YOZI_WC_VENDORS_ACTIVED', true );
}

/**
 * Customizer additions.
 *
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Styles
 *
 */
require get_template_directory() . '/inc/custom-styles.php';




