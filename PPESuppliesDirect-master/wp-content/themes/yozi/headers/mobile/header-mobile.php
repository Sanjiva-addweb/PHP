<div id="apus-header-mobile" class="header-mobile hidden-lg hidden-md clearfix">    
    <div class="container">
        <div class="row">
            <div class="table-visiable-dk">
                <?php if ( !has_nav_menu( 'vertical-menu' ) ): ?>
                    <div class="col-xs-3">
                        <div class="box-left">
                            <a href="#navbar-offcanvas" class="btn btn-showmenu"><i class="fa fa-bars"></i></a>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="text-center col-xs-<?php echo ( has_nav_menu( 'vertical-menu' ) ) ? '8' : '6'; ?>">
                    <?php
                        $logo = yozi_get_config('media-mobile-logo');
                    ?>
                    <?php if( isset($logo['url']) && !empty($logo['url']) ): ?>
                        <div class="logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
                                <img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="logo logo-theme">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
                                <img src="<?php echo esc_url_raw( get_template_directory_uri().'/images/logo.png'); ?>" alt="<?php bloginfo( 'name' ); ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-xs-3">
                    <?php if ( defined('YOZI_WOOCOMMERCE_ACTIVED') && yozi_get_config('show_cartbtn') && !yozi_get_config( 'enable_shop_catalog' ) ): ?>
                        <div class="box-right pull-right">
                            <!-- Setting -->
                            <div class="top-cart">
                                <?php get_template_part( 'woocommerce/cart/mini-cart-button' ); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( class_exists( 'YITH_WCWL' ) ):
                        $wishlist_url = YITH_WCWL()->get_wishlist_url();
                    ?>
                        <div class="pull-right">
                            <a class="wishlist-icon" href="<?php echo esc_url($wishlist_url);?>" title="<?php esc_html_e( 'View Your Wishlist', 'yozi' ); ?>"><i class="ti-heart"></i>
                                <?php if ( function_exists('yith_wcwl_count_products') ) { ?>
                                    <span class="count"><?php echo yith_wcwl_count_products(); ?></span>
                                <?php } ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
        <?php if ( yozi_get_config('show_searchform') ): ?>
            <div class="clearfix search-mobile">
                <?php get_template_part( 'template-parts/productsearchform_mobile' ); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="bottom-mobile clearfix">
                <?php if ( has_nav_menu( 'vertical-menu' ) ): ?>
                    <div class="col-xs-3">
                        <div class="box-left">
                            <a href="#navbar-offcanvas" class="btn btn-showmenu"><i class="fa fa-bars"></i></a>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-xs-9">
                    <?php if ( has_nav_menu( 'vertical-menu' ) ): ?>
                        <h4 class="text-title text-right mobile-vertical-menu-title"><span><?php echo esc_html__('All Departments','yozi') ?></span><i aria-hidden="true" class="fa fa-angle-down"></i></h4>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ( has_nav_menu( 'vertical-menu' ) ): ?>
    <div class="mobile-vertical-menu hidden-lg hidden-md" style="display: none;">
        
        <nav class="navbar navbar-offcanvas navbar-static" role="navigation">
            <?php
                $args = array(
                    'theme_location' => 'vertical-menu',
                    'container_class' => 'navbar-collapse navbar-offcanvas-collapse',
                    'menu_class' => 'nav navbar-nav',
                    'fallback_cb' => '',
                    'menu_id' => 'vertical-mobile-menu',
                    'walker' => new Yozi_Mobile_Vertical_Menu()
                );
                wp_nav_menu($args);
            ?>
        </nav>
    </div>
<?php endif; ?>