<header id="apus-header" class="apus-header header-v5 hidden-sm hidden-xs" role="banner">
        <?php if(is_active_sidebar( 'sidebar-topbar-left' ) || is_active_sidebar( 'sidebar-topbar-right' ) ) {?>
            <div id="apus-topbar" class="apus-topbar apus-topbar-v5 clearfix">
                <div class="container">
                    <?php if ( is_active_sidebar( 'sidebar-topbar-left' ) ) { ?>
                        <div class="pull-left">
                            <div class="topbar-left">
                                <?php dynamic_sidebar( 'sidebar-topbar-left' ); ?>
                            </div>
                        </div>
                    <?php } ?> 
                    <div class="pull-right">
                        <div class="topbar-right">
                            <?php if( !is_user_logged_in() ){ ?>
                                <div class="login-topbar pull-right">
                                    <a class="login" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_html_e('Sign in','yozi'); ?>"><?php esc_html_e('Login in /', 'yozi'); ?></a>
                                    <a class="register" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_html_e('Register','yozi'); ?>"><?php esc_html_e('Register', 'yozi'); ?></a>
                                </div>
                            <?php } else { ?>
                                <?php if ( has_nav_menu( 'top-menu' ) ): ?>
                                    <div class="wrapper-topmenu pull-right">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" aria-expanded="true" role="button" aria-haspopup="true" data-delay="0">
                                                <?php esc_html_e( 'My Account', 'yozi' ); ?><span class="caret"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <?php
                                                    $args = array(
                                                        'theme_location' => 'top-menu',
                                                        'container_class' => 'collapse navbar-collapse',
                                                        'menu_class' => 'nav navbar-nav topmenu-menu',
                                                        'fallback_cb' => '',
                                                        'menu_id' => 'topmenu-menu',
                                                        'walker' => new Yozi_Nav_Menu()
                                                    );
                                                    wp_nav_menu($args);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if ( is_active_sidebar( 'sidebar-topbar-right' ) ) { ?>
                            <div class="topbar-right-inner">
                                <?php dynamic_sidebar( 'sidebar-topbar-right' ); ?>
                            </div>
                            <?php } ?> 
                        </div>
                    </div>
                     
                </div>  
            </div>
        <?php } ?>
        <div class="clearfix">
            <div class="<?php echo (yozi_get_config('keep_header') ? 'main-sticky-header-wrapper' : ''); ?>">
                <div class="<?php echo (yozi_get_config('keep_header') ? 'main-sticky-header' : ''); ?>">
                    <div class="container">
                    <div class="header-middle p-relative">
                        <div class="row">
                            <div class="table-visiable-dk">
                                <div class="col-md-2">
                                    <div class="logo-in-theme">
                                        <?php get_template_part( 'template-parts/logo/logo-yellow' ); ?>
                                    </div>
                                </div>
                                <?php if ( has_nav_menu( 'primary' ) ) : ?>
                                <div class="col-md-6 p-static">
                                    <div class="main-menu">
                                        <nav data-duration="400" class="hidden-xs hidden-sm apus-megamenu slide animate navbar p-static" role="navigation">
                                        <?php   $args = array(
                                                'theme_location' => 'primary',
                                                'container_class' => 'collapse navbar-collapse no-padding',
                                                'menu_class' => 'nav navbar-nav megamenu',
                                                'fallback_cb' => '',
                                                'menu_id' => 'primary-menu',
                                                'walker' => new Yozi_Nav_Menu()
                                            );
                                            wp_nav_menu($args);
                                        ?>
                                        </nav>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="col-md-4">
                                    <?php if ( yozi_get_config('show_searchform') ): ?>
                                        <div class="pull-right">
                                            <?php get_template_part( 'template-parts/productsearch' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="header-right pull-right">
                                        <?php if ( defined('YOZI_WOOCOMMERCE_ACTIVED') && yozi_get_config('show_cartbtn') && !yozi_get_config( 'enable_shop_catalog' ) ): ?>
                                            <div class="pull-right">
                                                <?php get_template_part( 'woocommerce/cart/mini-cart-button-header-left' ); ?>
                                            </div>
                                        <?php endif; ?>
                                        <!-- Wishlist -->
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
                        </div> 
                    </div>
                    </div>
                </div>
            </div>
        </div>
</header>