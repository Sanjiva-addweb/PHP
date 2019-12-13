<header id="apus-header" class="apus-header header-v1 hidden-sm hidden-xs" role="banner">
    <?php
        $social_links = yozi_get_config('header_social_links_link');
        $social_icons = yozi_get_config('header_social_links_icon');
    ?>
    <?php if(is_active_sidebar( 'sidebar-topbar-left' ) || is_active_sidebar( 'sidebar-topbar-right' ) || !empty($social_links) ) {?>
        <div id="apus-topbar" class="apus-topbar clearfix">
            <div class="wrapper-large">
                <div class="container-fluid">
                    <?php if ( is_active_sidebar( 'sidebar-topbar-left' ) ) { ?>
                        <div class="pull-left">
                            <div class="topbar-left">
                                <?php dynamic_sidebar( 'sidebar-topbar-left' ); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="topbar-right pull-right">
                        <?php
                            if ( !empty($social_links) ) {
                                ?>
                                <ul class="social-top pull-right">
                                    <?php foreach ($social_links as $key => $value) { ?>
                                        <li class="social-item">
                                            <a href="<?php echo esc_url($value); ?>">
                                                <i class="<?php echo esc_attr($social_icons[$key]); ?>"></i>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <?php
                            }
                        ?>
                        <?php if ( is_active_sidebar( 'sidebar-topbar-right' ) ) { ?>
                            <div class="pull-right">
                                <div class="topbar-right-inner">
                                    <?php dynamic_sidebar( 'sidebar-topbar-right' ); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>  
        </div>
    <?php } ?>
    <div class="wrapper-large">
        <div class="<?php echo (yozi_get_config('keep_header') ? 'main-sticky-header-wrapper' : ''); ?>">
            <div class="<?php echo (yozi_get_config('keep_header') ? 'main-sticky-header' : ''); ?>">
                <div class="container-fluid">
                    <div class="header-middle">
                        <div class="row">
                            <div class="table-visiable-dk">
                                <div class="col-md-2">
                                    <div class="logo-in-theme ">
                                        <?php get_template_part( 'template-parts/logo/logo' ); ?>
                                    </div>
                                </div>
                                <?php if ( has_nav_menu( 'primary' ) ) : ?>
								<div class="col-md-7">
									<div class="col-md-12">
										<?php if ( yozi_get_config('show_searchform') ): ?>
											<?php //echo do_shortcode('[yith_woocommerce_ajax_search]'); ?>
											<?php get_template_part( 'template-parts/productsearchform' ); ?>
										<?php endif; ?>
									</div><br/><br/>
									<div class="col-md-12">
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
								</div>
                                <?php endif; ?>
                                <div class="col-md-3">
								<div class="col-md-12">
                                    
                                </div>
								<div class="col-md-12">

									<div class="header-right clearfix">



                                    <a class="header-account-link" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>">My account</a>


                                    <!--vat switcher-->
                                    <?php
                                    $cont = '?';
                                    if( strpos( $_SERVER['REQUEST_URI'], 'custom-design') ){
                                        $parts = parse_url( $_SERVER['REQUEST_URI'] );
                                        parse_str($parts['query'], $query);
                                        $cont = '?product_id=' . $query['product_id'] . '&';
                                    }

                                    $tax = 'ex'; $sep = 'in';
                                    if( isset( $_COOKIE['vat'] ) ) {
                                        if( $_COOKIE['vat']  === 'in' ) {
                                            $tax = 'in';
                                            $sep = 'ex';
                                        }elseif( $_COOKIE['vat']  === 'ex' ){
                                            $tax = 'ex';
                                            $sep = 'in';
                                        }
                                    }
                                    if( isset( $_GET['vat'] ) ) {
                                        if( $_GET['vat']  === 'in' ) {
                                            $tax = 'in';
                                            $sep = 'ex';
                                        }elseif( $_GET['vat']  === 'ex' ){
                                            $tax = 'ex';
                                            $sep = 'in';
                                        }
                                    }

                                    ?>
                                    <div class="vat-switcher">
                                         <span class="text">Ex.VAT</span>
                                         <span class="input">
                                            <input 
                                            onchange="location = this.value;" 
                                            type="checkbox" 
                                            id="switch" 
                                            value="<?php echo explode( '?',  "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" )[0] . $cont . "vat=$sep"; ?>"
                                            <?php if( $tax  === 'in' ) echo ' checked'; ?>
                                            />
                                            <label for="switch">Toggle</label>
                                        </span>    
                                        <span class="text">In.VAT</span>
                                    </div> 
                                    <!--end vat switcher-->

                                    

									<?php if( !is_user_logged_in() ){ ?>
                                        <div class="login-topbar pull-right hidden">
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
                                                       <div class="menu_wrapper"> <?php
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
                                            </div>
                                        <?php endif; ?>
                                    <?php } ?>
										<?php if ( defined('YOZI_WOOCOMMERCE_ACTIVED') && yozi_get_config('show_cartbtn') && !yozi_get_config( 'enable_shop_catalog' ) ): ?>
											<div class="pull-right">
												<?php get_template_part( 'woocommerce/cart/mini-cart-button' ); ?>
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
										<?php
											$tract_order_text = yozi_get_config('header_tract_order');
											if ( !empty($tract_order_text) ) { ?>
											<div class="pull-right">
												<div class="header-order">
													<?php echo trim($tract_order_text); ?>
												</div>
											</div>
										<?php } ?> 
									</div>
									
									<div class="dropdown vat-block" style="float:right;">
										<div class="phone_num">
											<a href="tel:08081096099"><img src="/wp-content/themes/yozi-child/images/phone_num_img.png" style="width:30px;"/>
											<span class="num_num" style="letter-spacing:1px; font-size:15px;"><a class="free_phone_txt">Free phone:</a> <a>0808 109 6099</a></span></a>
											</div>
										<div class="currency_sbox">
										<?php echo do_shortcode('[woocs width=’50%’]'); ?>
										</div>
										<div class="currency_tbox">

                                            <style>
                                                /*.vat-switcher{
                                                    float: left;
                                                }                

                                                .vat-switcher span{
                                                    display: inline-block;
                                                    font-size: 13px;
                                                }
                                                .vat-switcher .text{
                                                    position: relative;
                                                    top: -4px;
                                                    margin-left: 2px;
                                                    margin-right: 2px;
                                                    color: #757575;
                                                }                
                                               .vat-switcher input[type=checkbox]{
                                                    height: 0;
                                                    width: 0;
                                                    visibility: hidden;
                                                }

                                                .vat-switcher label {
                                                    cursor: pointer;
                                                    text-indent: -9999px;
                                                    width: 36px;
                                                    height: 18px;
                                                    background: #cb2229;
                                                    display: block;
                                                    border-radius: 18px;
                                                    position: relative;
                                                }

                                                .vat-switcher label:after {
                                                    content: '';
                                                    position: absolute;
                                                    top: 3px;
                                                    left: 4px;
                                                    width: 12px;
                                                    height: 12px;
                                                    background: #fff;
                                                    border-radius: 28px;
                                                    transition: 0.3s;
                                                }

                                                .vat-switcher input:checked + label {
                                                    background: #cb2229;
                                                }

                                                .vat-switcher input:checked + label:after {
                                                    left: calc(100% - 4px);
                                                    transform: translateX(-100%);
                                                }

                                                .vat-switcher label:active:after {
                                                    width: 30px;
                                                }*/
                                            </style>
										</div>
										
									</div>
									<br/>
                    </div>
								</div>
                            </div>   
                        </div> 
                    </div>
                </div>
               <!---<div class="header-bottom clearfix"> 
                    <?php 
						if ( has_nav_menu( 'vertical-menu' ) ): ?>
                        <div class="col-md-2">
                            <div class="vertical-wrapper">
                                <div class="title-vertical bg-theme"><i class="fa fa-bars" aria-hidden="true"></i> <span class="text-title"><?php echo esc_html__('all Departments','yozi') ?></span> <i class="fa fa-angle-down show-down" aria-hidden="true"></i></div>
                                <?php
                                    $args = array(
                                        'theme_location' => 'vertical-menu',
                                        'container_class' => 'content-vertical',
                                        'menu_class' => 'apus-vertical-menu nav navbar-nav',
                                        'fallback_cb' => '',
                                        'menu_id' => 'vertical-menu',
                                        'walker' => new Yozi_Nav_Menu()
                                    );
                                    wp_nav_menu($args);
                                ?>
                            </div>
                       </div>
                    <?php endif;?>
					
                 </div> --->
            </div>
        </div>
    </div>
</header>
