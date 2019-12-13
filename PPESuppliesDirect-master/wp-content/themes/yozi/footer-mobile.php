<div class="apus-footer-mobile">
	<?php if ( yozi_get_config('show_footer_search') ): ?>
        <div class="footer-search-mobile">
            <?php get_template_part( 'template-parts/productsearchform_footer_mobile' ); ?>
        </div>
    <?php endif; ?>
	<ul>
		<li>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
				<i class="text-theme ti-home"></i>
	            <span><?php esc_html_e('Home', 'yozi'); ?></span>
	        </a>
        </li>

        <?php if ( yozi_get_config('show_footer_search', true) ) { ?>
	    	<li>
	    		<a class="footer-search-btn" href="javascript:void(0)">
	    			<i class="ti-search"></i>
		            <span><?php esc_html_e('Search', 'yozi'); ?></span>
		        </a>
	    	</li>
    	<?php } ?>

    	<?php if ( defined('YOZI_WOOCOMMERCE_ACTIVED') && yozi_get_config('show_footer_cartbtn', true) && !yozi_get_config( 'enable_shop_catalog' )) { ?>
	    	<li>
	    		<a class="footer-mini-cart mini-cart" href="<?php echo wc_get_cart_url(); ?>">
		            <i class="ti-bag"></i>
		            <span class="count"><?php echo sprintf(WC()->cart->cart_contents_count); ?></span>
		            <span><?php esc_html_e('Cart', 'yozi'); ?></span>
		        </a> 
	    	</li>
    	<?php } ?>

    	<?php if ( defined('YOZI_WOOCOMMERCE_ACTIVED') && yozi_get_config('show_footer_myaccount', true) ) { ?>
	    	<li>
	    		<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>">
		            <i class="ti-user"></i>
		            <span><?php esc_html_e('My Account', 'yozi'); ?></span>
		        </a> 
	    	</li>
    	<?php } ?>

    	<?php
    		$morelink_menu = yozi_get_config('morelink_menu');
    		if ( yozi_get_config('show_footer_morelink', true) && $morelink_menu ) { ?>
	    	<li>
	    		<a href="javascript:void(0)" class="more">
		            <i class="ti-more"></i>
		            <span><?php esc_html_e('More', 'yozi'); ?></span>
		        </a>
		        <div class="wrapper-morelink">
			        <?php
	                    $args = array(
	                        'menu' => $morelink_menu,
	                        'menu_class'      => 'footer-morelink list-inline',
	                        'fallback_cb'     => '',
	                        'menu_id'         => 'footer-morelink'
	                    );
	                    wp_nav_menu($args);
	                ?>
	            </div>
	    	</li>
    	<?php } ?>
	</ul>
</div>