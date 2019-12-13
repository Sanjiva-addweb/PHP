<?php
/**
 * The Template for displaying all single posts.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$store_user = get_userdata( get_query_var( 'author' ) );
$store_info = dokan_get_store_info( $store_user->ID );


get_header();
$sidebar_configs = yozi_get_dokan_layout_configs();

?>

<?php do_action( 'yozi_woo_template_main_before' ); ?>

<section id="main-container" class="main-container <?php echo apply_filters('yozi_dokan_content_class', 'container');?>">
    
    <div class="row">
        <?php
        $main_class = '';
        if ( isset($sidebar_configs['left']) ) {
            $main_class = 'pull-right';
        }
        ?>

        <div id="main-content" class="archive-shop col-xs-12 <?php echo esc_attr($sidebar_configs['main']['class']. ' '. $main_class); ?>">
            <div id="dokan-primary" class="dokan-single-store">
                <div id="dokan-content" class="store-page-wrap woocommerce site-content" role="main">
            
                    <?php dokan_get_template_part( 'store-header' ); ?>

                    <div id="store-toc-wrapper">
                        <div id="store-toc">
                            <?php
                            if( isset( $store_info['store_tnc'] ) ):
                            ?>
                                <h2 class="headline"><?php esc_html_e( 'Terms And Conditions', 'yozi' ); ?></h2>
                                <div>
                                    <?php
                                    echo nl2br($store_info['store_tnc']);
                                    ?>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div><!-- #store-toc -->
                    </div><!-- #store-toc-wrap -->
                </div><!-- #content -->
            </div><!-- #primary -->
        </div><!-- #main-content -->

        <?php if ( isset($sidebar_configs['left']) ) : ?>
            <div class="dokan-store-sidebar <?php echo esc_attr($sidebar_configs['left']['class']) ;?>">
                
                <?php if ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' ) == 'off' ) { ?>
                    <div id="dokan-secondary" class="dokan-clearfix dokan-store-sidebar" role="complementary" style="margin-right:3%;">
                        <div class="dokan-widget-area widget-collapse">
                            <?php
                            if ( ! dynamic_sidebar( 'sidebar-store' ) ) {

                                $args = array(
                                    'before_widget' => '<aside class="widget">',
                                    'after_widget'  => '</aside>',
                                    'before_title'  => '<h3 class="widget-title">',
                                    'after_title'   => '</h3>',
                                );

                                if ( class_exists( 'Dokan_Store_Location' ) ) {
                                    the_widget( 'Dokan_Store_Category_Menu', array( 'title' => esc_html__( 'Store Category', 'yozi' ) ), $args );
                                    if( dokan_get_option( 'store_map', 'dokan_general', 'on' ) == 'on' ) {
                                        the_widget( 'Dokan_Store_Location', array( 'title' => esc_html__( 'Store Location', 'yozi' ) ), $args );
                                    }
                                    if( dokan_get_option( 'contact_seller', 'dokan_general', 'on' ) == 'on' ) {
                                        the_widget( 'Dokan_Store_Contact_Form', array( 'title' => esc_html__( 'Contact Vendor', 'yozi' ) ), $args );
                                    }
                                }

                            }
                            ?>

                            <?php do_action( 'dokan_sidebar_store_after', $store_user, $store_info ); ?>
                        </div>
                    </div><!-- #secondary .widget-area -->
                <?php
                } else {
                    if ( is_active_sidebar( $sidebar_configs['left']['sidebar'] ) ) {
                        dynamic_sidebar( $sidebar_configs['left']['sidebar'] );
                    }
                }
                ?>

            </div>
        <?php endif; ?>

        <?php if ( isset($sidebar_configs['right']) ) : ?>
            <div class="dokan-store-sidebar <?php echo esc_attr($sidebar_configs['right']['class']) ;?>">
                

                <?php if ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' ) == 'off' ) { ?>
                    <div id="dokan-secondary" class="dokan-clearfix dokan-store-sidebar" role="complementary" style="margin-right:3%;">
                        <div class="dokan-widget-area widget-collapse">
                            <?php
                            if ( ! dynamic_sidebar( 'sidebar-store' ) ) {

                                $args = array(
                                    'before_widget' => '<aside class="widget">',
                                    'after_widget'  => '</aside>',
                                    'before_title'  => '<h3 class="widget-title">',
                                    'after_title'   => '</h3>',
                                );

                                if ( class_exists( 'Dokan_Store_Location' ) ) {
                                    the_widget( 'Dokan_Store_Category_Menu', array( 'title' => esc_html__( 'Store Category', 'yozi' ) ), $args );
                                    if( dokan_get_option( 'store_map', 'dokan_general', 'on' ) == 'on' ) {
                                        the_widget( 'Dokan_Store_Location', array( 'title' => esc_html__( 'Store Location', 'yozi' ) ), $args );
                                    }
                                    if( dokan_get_option( 'contact_seller', 'dokan_general', 'on' ) == 'on' ) {
                                        the_widget( 'Dokan_Store_Contact_Form', array( 'title' => esc_html__( 'Contact Vendor', 'yozi' ) ), $args );
                                    }
                                }

                            }
                            ?>

                            <?php do_action( 'dokan_sidebar_store_after', $store_user, $store_info ); ?>
                        </div>
                    </div><!-- #secondary .widget-area -->
                <?php
                } else {
                    if ( is_active_sidebar( $sidebar_configs['right']['sidebar'] ) ) {
                        dynamic_sidebar( $sidebar_configs['right']['sidebar'] );
                    }
                }
                ?>

            </div>
        <?php endif; ?>
        
    </div>
</section>
<?php

get_footer();