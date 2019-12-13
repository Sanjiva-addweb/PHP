<?php
if ( !function_exists ('yozi_custom_styles') ) {
	function yozi_custom_styles() {
		global $post;	
		
		ob_start();	
		?>
		
			<?php
				$font_source = yozi_get_config('font_source');
				$main_font = yozi_get_config('main_font');
				$main_font = isset($main_font['font-family']) ? $main_font['font-family'] : false;
				$main_google_font_face = yozi_get_config('main_google_font_face');
			?>
			<?php if ( ($font_source == "1" && $main_font) || ($font_source == "2" && $main_google_font_face) ): ?>
				h1, h2, h3, h4, h5, h6, .widget-title,.widgettitle
				{
					font-family: 
					<?php if ( $font_source == "2" ) echo '\'' . $main_google_font_face . '\','; ?>
					<?php if ( $font_source == "1" ) echo '\'' . $main_font . '\','; ?> 
					sans-serif;
				}
			<?php endif; ?>
			/* Second Font */
			<?php
				$secondary_font = yozi_get_config('secondary_font');
				$secondary_font = isset($secondary_font['font-family']) ? $secondary_font['font-family'] : false;
				$secondary_google_font_face = yozi_get_config('secondary_google_font_face');
			?>
			<?php if ( ($font_source == "1" && $secondary_font) || ($font_source == "2" && $secondary_google_font_face) ): ?>
				body
				{
					font-family: 
					<?php if ( $font_source == "2" ) echo '\'' . $secondary_google_font_face . '\','; ?>
					<?php if ( $font_source == "1" ) echo '\'' . $secondary_font . '\','; ?> 
					sans-serif;
				}			
			<?php endif; ?>

			
			<?php if ( yozi_get_config('main_color') != "" ) : ?>
				/* seting background main */
				.product-block.grid-deal .progress .progress-bar,
				.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion .vc_active .vc_tta-panel-title::before,
				.detail-post .entry-tags-list a:hover, .detail-post .entry-tags-list a:active,
				.apus-pagination > span:hover, .apus-pagination > span.current, .apus-pagination > a:hover, .apus-pagination > a.current,
				.tabs-v1 .nav-tabs li:focus > a:focus, .tabs-v1 .nav-tabs li:focus > a:hover, .tabs-v1 .nav-tabs li:focus > a, .tabs-v1 .nav-tabs li:hover > a:focus, .tabs-v1 .nav-tabs li:hover > a:hover, .tabs-v1 .nav-tabs li:hover > a, .tabs-v1 .nav-tabs li.active > a:focus, .tabs-v1 .nav-tabs li.active > a:hover, .tabs-v1 .nav-tabs li.active > a,
				.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
				.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
				.widget-social .social a:hover, .widget-social .social a:active, .widget-social .social a:focus,
				.tab-product.nav-tabs > li > a::before,
				.mini-cart .count, .wishlist-icon .count,
				.slick-carousel .slick-dots li.slick-active button,
				.widget .widget-title::before, .widget .widgettitle::before, .widget .widget-heading::before,
				.bg-theme
				{
					background-color: <?php echo esc_html( yozi_get_config('main_color') ) ?> ;
				}
				/* setting color*/
				.woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item .count,
				.shop-list-smallest .name a:hover, .shop-list-smallest .name a:active,
				.detail-post .apus-social-share a:hover, .detail-post .apus-social-share a:active,
				.woocommerce div.product form.cart .group_table .price, .woocommerce div.product form.cart .group_table .price ins,
				.woocommerce div.product .product_title,
				.product-categories li.current-cat-parent > a, .product-categories li.current-cat > a, .product-categories li:hover > a,
				.woocommerce ul.product_list_widget .product-title a,
				.woocommerce ul.product_list_widget .woocommerce-Price-amount,
				.widget_apus_vertical_menu.darken .apus-vertical-menu > li.active > a, .widget_apus_vertical_menu.darken .apus-vertical-menu > li:hover > a,
				.banner-countdown-widget.dark .title strong,
				.nav-tabs.style_center > li.active > a,
				.post-layout .categories a,
				.widget-categorybanner .category-wrapper:hover .title,
				.mini-cart:hover, .mini-cart:active, .wishlist-icon:hover, .wishlist-icon:active,
				.woocommerce div.product p.price, .woocommerce div.product span.price,
				a:hover,a:active,a:focus,
				.btn-link{
					color: <?php echo esc_html( yozi_get_config('main_color') ) ?>;
				}

				/* setting border color*/
				.shop-list-small:hover:not(.shop-list-smallest),
				.wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_active .vc_tta-panel-heading .vc_tta-controls-icon::after, .wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_active .vc_tta-panel-heading .vc_tta-controls-icon::before, .wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-controls-icon::after, .wpb-js-composer .vc_tta-color-grey.vc_tta-style-classic .vc_tta-controls-icon::before,
				.detail-post .entry-tags-list a:hover, .detail-post .entry-tags-list a:active,
				blockquote,
				.apus-pagination > span:hover, .apus-pagination > span.current, .apus-pagination > a:hover, .apus-pagination > a.current,
				.details-product .apus-woocommerce-product-gallery-thumbs .slick-slide:hover .thumbs-inner, .details-product .apus-woocommerce-product-gallery-thumbs .slick-slide:active .thumbs-inner, .details-product .apus-woocommerce-product-gallery-thumbs .slick-slide.slick-current .thumbs-inner,
				body.skin-dark .product-block.grid .groups-button,
				.widget_apus_vertical_menu .apus-vertical-menu > li.active > a, .widget_apus_vertical_menu .apus-vertical-menu > li:hover > a,
				body.skin-dark .product-block.grid:hover,
				body.skin-dark .product-block.grid:hover .metas,
				.banner-countdown-widget .times > div,
				.widget-social .social a:hover, .widget-social .social a:active, .widget-social .social a:focus,
				.product-block.grid-deal,
				.border-theme{
					border-color: <?php echo esc_html( yozi_get_config('main_color') ) ?> !important;
				}
				body.skin-dark .product-block .product-cats a,
				.widget-banner.banner-dark .title strong,
				.text-theme{
					color: <?php echo esc_html( yozi_get_config('main_color') ) ?> !important;
				}
				.widget-banner.banner-dark .image-wrapper{
					outline:5px solid <?php echo esc_html( yozi_get_config('main_color') ) ?>;
				}
			<?php endif; ?>

			<?php if ( yozi_get_config('button_color') != "" ) : ?>
				/* check button color */
				.product-block-list .add-cart a.button,
				body.skin-dark .product-block.grid .yith-wcwl-add-to-wishlist a, body.skin-dark .product-block.grid .compare:before,
				.product-block.grid .yith-wcwl-add-to-wishlist a,
				.product-block.grid .compare:before,
				.btn-theme.btn-outline
				{
					color: <?php echo esc_html( yozi_get_config('button_color') ); ?>;
				}

				/* check second background color */
				body.skin-dark .product-block.grid .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist),
				.product-block.grid .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist),
				body.skin-dark .product-block.grid .compare.added::before,
				.product-block.grid .compare.added::before,
				.woocommerce #respond input#submit,
				.groups-button .add-cart .added_to_cart,
				.woocommerce a.button,
				.add-fix-top,
				.btn-theme
				{
					background-color: <?php echo esc_html( yozi_get_config('button_color') ); ?>;
				}
				/* check second border color */
				body.skin-dark .product-block.grid .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist),
				.product-block.grid .yith-wcwl-add-to-wishlist a:not(.add_to_wishlist),
				body.skin-dark .product-block.grid .compare.added::before,
				.product-block.grid .compare.added::before,
				.product-block-list .add-cart a.button,
				.woocommerce #respond input#submit,
				body.skin-dark .product-block.grid .yith-wcwl-add-to-wishlist a, body.skin-dark .product-block.grid .compare:before,
				.groups-button .add-cart .added_to_cart,
				.btn-outline.btn-theme,
				.btn-theme
				{
					border-color: <?php echo esc_html( yozi_get_config('button_color') ); ?>;
				}

			<?php endif; ?>

			<?php if ( yozi_get_config('button_hover_color') != "" ) : ?>
				.text-theme-second
				{
					color: <?php echo esc_html( yozi_get_config('button_hover_color') ); ?>;
				}
				/* check second background color */
				.product-block-list .add-cart a.button:hover,
				.product-block-list .add-cart a.button:active,
				.product-block-list .add-cart a.button:focus,
				.woocommerce #respond input#submit:hover,
				.woocommerce #respond input#submit:active,
				.woocommerce #respond input#submit:focus,
				.groups-button .add-cart .added_to_cart:hover,
				.groups-button .add-cart .added_to_cart:active,
				.groups-button .add-cart .added_to_cart:focus,
				.woocommerce a.button:hover,
				.woocommerce a.button:active,
				.woocommerce a.button:focus,
				.woocommerce a.button.active,
				.product-block.grid .yith-wcwl-add-to-wishlist a:hover,
				.product-block.grid .compare:hover::before,
				.add-fix-top:focus, .add-fix-top:active, .add-fix-top:hover,
				.btn-theme.btn-outline:hover, .btn-outline.viewmore-products-btn:hover, .btn-theme.btn-outline:active, .btn-outline.viewmore-products-btn:active,
				.btn-theme:hover, .btn-theme:focus, .btn-theme:active, .btn-theme.active, .open > .btn-theme.dropdown-toggle
				{
					background-color: <?php echo esc_html( yozi_get_config('button_hover_color') ); ?>;
				}
				.product-block-list .add-cart a.button:hover,
				.product-block-list .add-cart a.button:active,
				.product-block-list .add-cart a.button:focus,
				.woocommerce #respond input#submit:hover,
				.woocommerce #respond input#submit:focus,
				.woocommerce #respond input#submit:active,
				.groups-button .add-cart .added_to_cart:hover,
				.groups-button .add-cart .added_to_cart:active,
				.groups-button .add-cart .added_to_cart:focus,
				.woocommerce a.button:hover,
				.woocommerce a.button:active,
				.woocommerce a.button:focus,
				.woocommerce a.button.active,
				.product-block.grid .yith-wcwl-add-to-wishlist a:hover,
				.product-block.grid .compare:hover::before,
				.btn-theme.btn-outline:hover, .btn-outline.viewmore-products-btn:hover, .btn-theme.btn-outline:active, .btn-outline.viewmore-products-btn:active,
				.btn-theme:hover, .btn-theme:focus, .btn-theme:active, .btn-theme.active
				{
					border-color: <?php echo esc_html( yozi_get_config('button_hover_color') ); ?>;
				}
				.product-block-list .add-cart a.button:hover,
				.product-block-list .add-cart a.button:active,
				.product-block-list .add-cart a.button:focus,
				.product-block.grid .yith-wcwl-add-to-wishlist a:hover,
				.product-block.grid .compare:hover::before{
					color:#fff;
				}
			<?php endif; ?>

			/***************************************************************/
			/* Top Bar *****************************************************/
			/***************************************************************/
			/* Top Bar Backgound */
			<?php $topbar_bg = yozi_get_config('topbar_bg'); ?>
			<?php if ( !empty($topbar_bg) ) :
				$image = isset($topbar_bg['background-image']) ? str_replace(array('http://', 'https://'), array('//', '//'), $topbar_bg['background-image']) : '';
				$topbar_bg_image = $image && $image != 'none' ? 'url('.esc_url($image).')' : $image;
			?>
				#apus-topbar {
					<?php if ( isset($topbar_bg['background-color']) && $topbar_bg['background-color'] ): ?>
				    background-color: <?php echo esc_html( $topbar_bg['background-color'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($topbar_bg['background-repeat']) && $topbar_bg['background-repeat'] ): ?>
				    background-repeat: <?php echo esc_html( $topbar_bg['background-repeat'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($topbar_bg['background-size']) && $topbar_bg['background-size'] ): ?>
				    background-size: <?php echo esc_html( $topbar_bg['background-size'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($topbar_bg['background-attachment']) && $topbar_bg['background-attachment'] ): ?>
				    background-attachment: <?php echo esc_html( $topbar_bg['background-attachment'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($topbar_bg['background-position']) && $topbar_bg['background-position'] ): ?>
				    background-position: <?php echo esc_html( $topbar_bg['background-position'] ) ?>;
				    <?php endif; ?>
				    <?php if ( $topbar_bg_image ): ?>
				    background-image: <?php echo esc_html( $topbar_bg_image ) ?>;
				    <?php endif; ?>
				}
			<?php endif; ?>
			/* Top Bar Color */
			<?php if ( yozi_get_config('topbar_text_color') != "" ) : ?>
				#apus-topbar {
					color: <?php echo esc_html(yozi_get_config('topbar_text_color')); ?>;
				}
			<?php endif; ?>
			/* Top Bar Link Color */
			<?php if ( yozi_get_config('topbar_link_color') != "" ) : ?>
				#apus-topbar a {
					color: <?php echo esc_html(yozi_get_config('topbar_link_color')); ?>;
				}
			<?php endif; ?>

			<?php if ( yozi_get_config('topbar_link_color_hover') != "" ) : ?>
				#apus-topbar a:hover ,#apus-topbar a:active, #apus-topbar a:focus{
					color: <?php echo esc_html(yozi_get_config('topbar_link_color_hover')); ?>;
				}
			<?php endif; ?>

			/***************************************************************/
			/* Header *****************************************************/
			/***************************************************************/
			/* Header Backgound */
			<?php $header_bg = yozi_get_config('header_bg'); ?>
			<?php if ( !empty($header_bg) ) :
				$image = isset($header_bg['background-image']) ? str_replace(array('http://', 'https://'), array('//', '//'), $header_bg['background-image']) : '';
				$header_bg_image = $image && $image != 'none' ? 'url('.esc_url($image).')' : $image;
			?>	.header_transparent #apus-header .sticky-header .header-inner,
				#apus-header .header-inner,
				#apus-header {
					<?php if ( isset($header_bg['background-color']) && $header_bg['background-color'] ): ?>
				    background-color: <?php echo esc_html( $header_bg['background-color'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($header_bg['background-repeat']) && $header_bg['background-repeat'] ): ?>
				    background-repeat: <?php echo esc_html( $header_bg['background-repeat'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($header_bg['background-size']) && $header_bg['background-size'] ): ?>
				    background-size: <?php echo esc_html( $header_bg['background-size'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($header_bg['background-attachment']) && $header_bg['background-attachment'] ): ?>
				    background-attachment: <?php echo esc_html( $header_bg['background-attachment'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($header_bg['background-position']) && $header_bg['background-position'] ): ?>
				    background-position: <?php echo esc_html( $header_bg['background-position'] ) ?>;
				    <?php endif; ?>
				    <?php if ( $header_bg_image ): ?>
				    background-image: <?php echo esc_html( $header_bg_image ) ?>;
				    <?php endif; ?>
				}
			<?php endif; ?>
			/* Header Color */
			<?php if ( yozi_get_config('header_text_color') != "" ) : ?>
				#apus-header {
					color: <?php echo esc_html(yozi_get_config('header_text_color')); ?>;
				}
			<?php endif; ?>
			/* Header Link Color */
			<?php if ( yozi_get_config('header_link_color') != "" ) : ?>
				#apus-header a {
					color: <?php echo esc_html(yozi_get_config('header_link_color'));?> ;
				}
			<?php endif; ?>
			/* Header Link Color Active */
			<?php if ( yozi_get_config('header_link_color_active') != "" ) : ?>
				#apus-header .active > a,
				#apus-header a:active,
				#apus-header a:hover {
					color: <?php echo esc_html(yozi_get_config('header_link_color_active')); ?>;
				}
			<?php endif; ?>


			/* Menu Link Color */
			<?php if ( yozi_get_config('main_menu_link_color') != "" ) : ?>
				.navbar-nav.megamenu > li > a{
					color: <?php echo esc_html(yozi_get_config('main_menu_link_color'));?> !important;
				}
			<?php endif; ?>
			
			/* Menu Link Color Active */
			<?php if ( yozi_get_config('main_menu_link_color_active') != "" ) : ?>
				.navbar-nav.megamenu > li:hover > a,
				.navbar-nav.megamenu > li.active > a,
				.navbar-nav.megamenu > li > a:hover,
				.navbar-nav.megamenu > li > a:active
				{
					color: <?php echo esc_html(yozi_get_config('main_menu_link_color_active')); ?> !important;
				}
			<?php endif; ?>
			<?php if ( yozi_get_config('main_menu_link_color_active') != "" ) : ?>
				.navbar-nav.megamenu > li.active > a,
				.navbar-nav.megamenu > li:hover > a{
					border-color: <?php echo esc_html(yozi_get_config('main_menu_link_color_active'));?> !important;
				}
			<?php endif; ?>

			/***************************************************************/
			/* Main Content *****************************************************/
			/***************************************************************/
			/*  Backgound */
			<?php $main_content_bg = yozi_get_config('main_content_bg'); ?>
			<?php if ( !empty($main_content_bg) ) :
				$image = isset($main_content_bg['background-image']) ? str_replace(array('http://', 'https://'), array('//', '//'), $main_content_bg['background-image']) : '';
				$main_content_bg_image = $image && $image != 'none' ? 'url('.esc_url($image).')' : $image;
			?>
				#apus-main-content {
					<?php if ( isset($main_content_bg['background-color']) && $main_content_bg['background-color'] ): ?>
				    background-color: <?php echo esc_html( $main_content_bg['background-color'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($main_content_bg['background-repeat']) && $main_content_bg['background-repeat'] ): ?>
				    background-repeat: <?php echo esc_html( $main_content_bg['background-repeat'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($main_content_bg['background-size']) && $main_content_bg['background-size'] ): ?>
				    background-size: <?php echo esc_html( $main_content_bg['background-size'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($main_content_bg['background-attachment']) && $main_content_bg['background-attachment'] ): ?>
				    background-attachment: <?php echo esc_html( $main_content_bg['background-attachment'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($main_content_bg['background-position']) && $main_content_bg['background-position'] ): ?>
				    background-position: <?php echo esc_html( $main_content_bg['background-position'] ) ?>;
				    <?php endif; ?>
				    <?php if ( $main_content_bg_image ): ?>
				    background-image: <?php echo esc_html( $main_content_bg_image ) ?>;
				    <?php endif; ?>
				}
			<?php endif; ?>
			/* main_content Color */
			<?php if ( yozi_get_config('main_content_text_color') != "" ) : ?>
				#apus-main-content {
					color: <?php echo esc_html(yozi_get_config('main_content_text_color')); ?>;
				}
			<?php endif; ?>
			<?php if ( yozi_get_config('main_content_border_color') != "" ) : ?>
				.woocommerce ul.product_list_widget,
				.widget-service,
				.details-product .apus-woocommerce-product-gallery-thumbs .slick-slide .thumbs-inner,
				.tabs-v1 .tab-content > div,
				.woocommerce ul.product_list_widget li,
				.service-item,
				.details-product .apus-woocommerce-product-gallery-wrapper,
				.product-categories {
					border-color: <?php echo esc_html(yozi_get_config('main_content_border_color')); ?>;
				}
			<?php endif; ?>
			/* main_content Link Color */
			<?php if ( yozi_get_config('main_content_link_color') != "" ) : ?>
				#apus-main-content a:not([class]) {
					color: <?php echo esc_html(yozi_get_config('main_content_link_color')); ?>;
				}
			<?php endif; ?>

			/* main_content Link Color Hover*/
			<?php if ( yozi_get_config('main_content_link_color_hover') != "" ) : ?>
				#apus-main-content a:not([class]):hover,#apus-main-content a:not([class]):active, #apus-main-content a:not([class]):focus {
					color: <?php echo esc_html(yozi_get_config('main_content_link_color_hover')); ?>;
				}
			<?php endif; ?>

			/***************************************************************/
			/* Footer *****************************************************/
			/***************************************************************/
			/* Footer Backgound */
			<?php $footer_bg = yozi_get_config('footer_bg'); ?>
			<?php if ( !empty($footer_bg) ) :
				$image = isset($footer_bg['background-image']) ? str_replace(array('http://', 'https://'), array('//', '//'), $footer_bg['background-image']) : '';
				$footer_bg_image = $image && $image != 'none' ? 'url('.esc_url($image).')' : $image;
			?>
				#apus-footer {
					<?php if ( isset($footer_bg['background-color']) && $footer_bg['background-color'] ): ?>
				    background-color: <?php echo esc_html( $footer_bg['background-color'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($footer_bg['background-repeat']) && $footer_bg['background-repeat'] ): ?>
				    background-repeat: <?php echo esc_html( $footer_bg['background-repeat'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($footer_bg['background-size']) && $footer_bg['background-size'] ): ?>
				    background-size: <?php echo esc_html( $footer_bg['background-size'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($footer_bg['background-attachment']) && $footer_bg['background-attachment'] ): ?>
				    background-attachment: <?php echo esc_html( $footer_bg['background-attachment'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($footer_bg['background-position']) && $footer_bg['background-position'] ): ?>
				    background-position: <?php echo esc_html( $footer_bg['background-position'] ) ?>;
				    <?php endif; ?>
				    <?php if ( $footer_bg_image ): ?>
				    background-image: <?php echo esc_html( $footer_bg_image ) ?>;
				    <?php endif; ?>
				}
			<?php endif; ?>
			/* Footer Heading Color*/
			<?php if ( yozi_get_config('footer_heading_color') != "" ) : ?>
				#apus-footer h1, #apus-footer h2, #apus-footer h3, #apus-footer h4, #apus-footer h5, #apus-footer h6 ,#apus-footer .widget-title {
					color: <?php echo esc_html(yozi_get_config('footer_heading_color')); ?> !important;
				}
			<?php endif; ?>
			/* Footer Color */
			<?php if ( yozi_get_config('footer_text_color') != "" ) : ?>
				#apus-footer {
					color: <?php echo esc_html(yozi_get_config('footer_text_color')); ?>;
				}
			<?php endif; ?>
			/* Footer Link Color */
			<?php if ( yozi_get_config('footer_link_color') != "" ) : ?>
				#apus-footer a {
					color: <?php echo esc_html(yozi_get_config('footer_link_color')); ?>;
				}
			<?php endif; ?>

			/* Footer Link Color Hover*/
			<?php if ( yozi_get_config('footer_link_color_hover') != "" ) : ?>
				#apus-footer a:hover {
					color: <?php echo esc_html(yozi_get_config('footer_link_color_hover')); ?>;
				}
			<?php endif; ?>


			/***************************************************************/
			/* Copyright *****************************************************/
			/***************************************************************/
			/* Copyright Backgound */
			<?php $copyright_bg = yozi_get_config('copyright_bg'); ?>
			<?php if ( !empty($copyright_bg) ) :
				$image = isset($copyright_bg['background-image']) ? str_replace(array('http://', 'https://'), array('//', '//'), $copyright_bg['background-image']) : '';
				$copyright_bg_image = $image && $image != 'none' ? 'url('.esc_url($image).')' : $image;
			?>
				.apus-copyright {
					<?php if ( isset($copyright_bg['background-color']) && $copyright_bg['background-color'] ): ?>
				    background-color: <?php echo esc_html( $copyright_bg['background-color'] ) ?> !important;
				    <?php endif; ?>
				    <?php if ( isset($copyright_bg['background-repeat']) && $copyright_bg['background-repeat'] ): ?>
				    background-repeat: <?php echo esc_html( $copyright_bg['background-repeat'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($copyright_bg['background-size']) && $copyright_bg['background-size'] ): ?>
				    background-size: <?php echo esc_html( $copyright_bg['background-size'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($copyright_bg['background-attachment']) && $copyright_bg['background-attachment'] ): ?>
				    background-attachment: <?php echo esc_html( $copyright_bg['background-attachment'] ) ?>;
				    <?php endif; ?>
				    <?php if ( isset($copyright_bg['background-position']) && $copyright_bg['background-position'] ): ?>
				    background-position: <?php echo esc_html( $copyright_bg['background-position'] ) ?>;
				    <?php endif; ?>
				    <?php if ( $copyright_bg_image ): ?>
				    background-image: <?php echo esc_html( $copyright_bg_image ) ?> !important;
				    <?php endif; ?>
				}
			<?php endif; ?>

			/* Footer Color */
			<?php if ( yozi_get_config('copyright_text_color') != "" ) : ?>
				.apus-copyright {
					color: <?php echo esc_html(yozi_get_config('copyright_text_color')); ?>;
				}
			<?php endif; ?>
			/* Footer Link Color */
			<?php if ( yozi_get_config('copyright_link_color') != "" ) : ?>
				.apus-copyright a {
					color: <?php echo esc_html(yozi_get_config('copyright_link_color')); ?>;
				}
			<?php endif; ?>

			/* Footer Link Color Hover*/
			<?php if ( yozi_get_config('copyright_link_color_hover') != "" ) : ?>
				.apus-copyright a:hover {
					color: <?php echo esc_html(yozi_get_config('copyright_link_color_hover')); ?>;
				}
			<?php endif; ?>

			/* Woocommerce Breadcrumbs */
			<?php if ( yozi_get_config('breadcrumbs') == "0" ) : ?>
			.woocommerce .woocommerce-breadcrumb,
			.woocommerce-page .woocommerce-breadcrumb
			{
				display:none;
			}
			<?php endif; ?>



	<?php
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			}
		}
		
		return implode($new_lines);
	}
}
