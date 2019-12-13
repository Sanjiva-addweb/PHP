<nav id="navbar-offcanvas" class="navbar hidden-lg hidden-md" role="navigation">
    <ul>
        <?php
            $args = array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'nav navbar-nav',
                'fallback_cb'     => false,
                'walker' => new Yozi_Mobile_Menu(),
                'items_wrap' => '%3$s',
            );
            wp_nav_menu($args);
        ?>

        <?php if( !is_user_logged_in() ){ ?>
            <li>
                <a class="login" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_html_e('Sign in','yozi'); ?>"><?php esc_html_e('Login in', 'yozi'); ?></a>
            </li>
            <li>
                <a class="register" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_html_e('Register','yozi'); ?>"><?php esc_html_e('Register', 'yozi'); ?></a>
            </li>
        <?php } else { ?>
            <?php if ( has_nav_menu( 'top-menu' ) ): ?>
                <li class="text-title"><span><?php echo esc_html__('My Account','yozi') ?></span></li>
                <?php
                    $args = array(
                        'theme_location' => 'top-menu',
                        'container' => false,
                        'menu_class' => 'nav navbar-nav',
                        'fallback_cb'     => false,
                        'walker' => new Yozi_Mobile_Menu(),
                        'items_wrap' => '%3$s',
                    );
                    wp_nav_menu($args);
                ?>
            <?php endif; ?>
        <?php } ?>


        <?php if ( is_active_sidebar( 'sidebar-topbar-left' ) ) { ?>
            <li class="topbar-left-wrapper">
                <div class="topbar-left">
                    <?php dynamic_sidebar( 'sidebar-topbar-left' ); ?>
                </div>
            </li>
        <?php } ?>

        <?php if ( is_active_sidebar( 'sidebar-topbar-right' ) ) { ?>
            <li class="topbar-right-wrapper">
                <?php dynamic_sidebar( 'sidebar-topbar-right' ); ?>
            </li>
        <?php } ?>

        <?php
            $social_links = yozi_get_config('header_social_links_link');
            $social_icons = yozi_get_config('header_social_links_icon');
            if ( !empty($social_links) ) {
                ?>
                <li class="social-top">
                    <?php foreach ($social_links as $key => $value) { ?>
                        <a href="<?php echo esc_url($value); ?>">
                            <i class="<?php echo esc_attr($social_icons[$key]); ?>"></i>
                        </a>
                    <?php } ?>
                </li>
                <?php
            }
        ?>
    </ul>
</nav>