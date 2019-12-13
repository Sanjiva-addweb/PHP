<?php

// Shop Archive settings
function yozi_woo_redux_config($sections, $sidebars, $columns) {
    $categories = array();
    $attributes = array();
    if ( is_admin() ) {
        $categories = yozi_woocommerce_get_categories(false);

        $attrs = wc_get_attribute_taxonomies();
        if ( $attrs ) {
            foreach ( $attrs as $tax ) {
                $attributes[wc_attribute_taxonomy_name( $tax->attribute_name )] = $tax->attribute_label;
            }
        }
    }
    $attributes = array();
    if ( is_admin() ) {
        $attrs = wc_get_attribute_taxonomies();
        if ( $attrs ) {
            foreach ( $attrs as $tax ) {
                $attributes[wc_attribute_taxonomy_name( $tax->attribute_name )] = $tax->attribute_label;
            }
        }
    }
    $sections[] = array(
        'icon' => 'el el-shopping-cart',
        'title' => esc_html__('Shop Settings', 'yozi'),
        'fields' => array(
            array (
                'id' => 'products_general_total_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('General Setting', 'yozi').'</h3>',
            ),
            array(
                'id' => 'enable_shop_catalog',
                'type' => 'switch',
                'title' => esc_html__('Enable Shop Catalog', 'yozi'),
                'default' => 0
            ),
            array (
                'id' => 'products_brand_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Brands Setting', 'yozi').'</h3>',
            ),
            array(
                'id' => 'product_brand_attribute',
                'type' => 'select',
                'title' => esc_html__( 'Brand Attribute', 'yozi' ),
                'subtitle' => esc_html__( 'Choose a product attribute that will be used as brands', 'yozi' ),
                'desc' => esc_html__( 'When you have choosed a brand attribute, you will be able to add brand image to the attributes', 'yozi' ),
                'options' => $attributes
            ),
            array (
                'id' => 'products_breadcrumb_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Breadcrumbs Setting', 'yozi').'</h3>',
            ),
            array(
                'id' => 'show_product_breadcrumbs',
                'type' => 'switch',
                'title' => esc_html__('Breadcrumbs', 'yozi'),
                'default' => 1
            ),
            array (
                'title' => esc_html__('Breadcrumbs Background Color', 'yozi'),
                'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'yozi').'</em>',
                'id' => 'woo_breadcrumb_color',
                'type' => 'color',
                'transparent' => false,
            ),
            array(
                'id' => 'woo_breadcrumb_image',
                'type' => 'media',
                'title' => esc_html__('Breadcrumbs Background', 'yozi'),
                'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'yozi'),
            ),
        )
    );
    // Archive settings
    $sections[] = array(
        'title' => esc_html__('Product Archives', 'yozi'),
        'fields' => array(
            array (
                'id' => 'products_general_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('General Setting', 'yozi').'</h3>',
            ),
            array(
                'id' => 'show_category_title',
                'type' => 'switch',
                'title' => esc_html__('Show Category Title', 'yozi'),
                'default' => 0
            ),
            array(
                'id' => 'show_category_image',
                'type' => 'switch',
                'title' => esc_html__('Show Category Image', 'yozi'),
                'default' => 0
            ),
            array(
                'id' => 'show_category_description',
                'type' => 'switch',
                'title' => esc_html__('Show Category Description', 'yozi'),
                'default' => 0
            ),
            array(
                'id' => 'product_display_mode',
                'type' => 'select',
                'title' => esc_html__('Products Layout', 'yozi'),
                'subtitle' => esc_html__('Choose a default layout archive product.', 'yozi'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'yozi'),
                    'list' => esc_html__('List', 'yozi')
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'product_list_version',
                'type' => 'select',
                'title' => esc_html__('Product List Version', 'yozi'),
                'options' => array(
                    'list' => esc_html__('Default', 'yozi'),
                    'list-v1' => esc_html__('List V1', 'yozi')
                ),
                'default' => 'list',
                'required' => array('product_display_mode', 'equals', 'list'),
            ),
            array(
                'id' => 'number_products_per_page',
                'type' => 'text',
                'title' => esc_html__('Number of Products Per Page', 'yozi'),
                'default' => 12,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider'
            ),
            array(
                'id' => 'product_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'yozi'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id' => 'show_quickview',
                'type' => 'switch',
                'title' => esc_html__('Show Quick View', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'enable_swap_image',
                'type' => 'switch',
                'title' => esc_html__('Enable Swap Image', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'products_block_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Products Block Setting', 'yozi').'</h3>',
            ),
            array(
                'id'        => 'product_archive_blocks_top',
                'type'      => 'sorter',
                'title'     => esc_html__( 'Products Block Top', 'yozi' ),
                'subtitle'  => esc_html__( 'Please drag and arrange the block', 'yozi' ),
                'options'   => array(
                    'enabled' => array(
                        'deal'            => esc_html__( 'Products Deals', 'yozi' ),
                        'bestseller'   => esc_html__( 'Best Sellers', 'yozi' ),
                        'new'       => esc_html__( 'New Releases', 'yozi' ),
                        'toprated' => esc_html__( 'Top Rated Products', 'yozi' ),
                        'recommended' => esc_html__( 'Recommended Products', 'yozi' )
                        
                    ),
                    'disabled' => array()
                )
            ),
            array(
                'id'        => 'product_archive_blocks_bottom',
                'type'      => 'sorter',
                'title'     => esc_html__( 'Products Block Bottom', 'yozi' ),
                'subtitle'  => esc_html__( 'Please drag and arrange the block', 'yozi' ),
                'options'   => array(
                    'enabled' => array(),
                    'disabled' => array(
                        'deal'            => esc_html__( 'Products Deals', 'yozi' ),
                        'bestseller'   => esc_html__( 'Best Sellers', 'yozi' ),
                        'new'       => esc_html__( 'New Releases', 'yozi' ),
                        'toprated' => esc_html__( 'Top Rated Products', 'yozi' ),
                        'recommended' => esc_html__( 'Recommended Products', 'yozi' )
                    )
                )
            ),
            array(
                'id' => 'show_recently_viewed',
                'type' => 'switch',
                'title' => esc_html__('Show Recently Viewed Products', 'yozi'),
                'default' => 1
            ),
            array (
                'id' => 'products_sidebar_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Sidebar Setting', 'yozi').'</h3>',
            ),
            array(
                'id' => 'product_archive_fullwidth',
                'type' => 'switch',
                'title' => esc_html__('Is Full Width?', 'yozi'),
                'default' => false
            ),
            array(
                'id' => 'product_archive_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Archive Product Layout', 'yozi'),
                'subtitle' => esc_html__('Select the layout you want to apply on your archive product page.', 'yozi'),
                'options' => array(
                    'main' => array(
                        'title' => esc_html__('Main Content', 'yozi'),
                        'alt' => esc_html__('Main Content', 'yozi'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                    ),
                    'left-main' => array(
                        'title' => esc_html__('Left Sidebar - Main Content', 'yozi'),
                        'alt' => esc_html__('Left Sidebar - Main Content', 'yozi'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                    ),
                    'main-right' => array(
                        'title' => esc_html__('Main Content - Right Sidebar', 'yozi'),
                        'alt' => esc_html__('Main Content - Right Sidebar', 'yozi'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                    ),
                ),
                'default' => 'left-main'
            ),
            array(
                'id' => 'product_archive_left_sidebar',
                'type' => 'select',
                'title' => esc_html__('Archive Left Sidebar', 'yozi'),
                'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'yozi'),
                'options' => $sidebars
            ),
            array(
                'id' => 'product_archive_right_sidebar',
                'type' => 'select',
                'title' => esc_html__('Archive Right Sidebar', 'yozi'),
                'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'yozi'),
                'options' => $sidebars
            ),
        )
    );
    
    $sections[] = array(
        'title' => esc_html__('Deals Block Setting', 'yozi'),
        'subsection' => true,
        'fields' => array(
            array(
                'id' => 'show_deal_in_shop',
                'type' => 'switch',
                'title' => esc_html__('Show in Shop Page', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'products_deal_categories',
                'type' => 'select',
                'title' => esc_html__('Display this block in categories', 'yozi'),
                'options' => $categories,
                'multi' => true,
            ),
            array(
                'id' => 'products_deal_title',
                'type' => 'text',
                'title' => esc_html__('Title', 'yozi'),
                'default' => 'Featured Deals in %s products',
                'desc' => esc_html__('%s for category name', 'yozi')
            ),
            array(
                'id' => 'products_deal_number',
                'type' => 'text',
                'title' => esc_html__('Number Products', 'yozi'),
                'default' => 12,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider',
            ),
            array(
                'id' => 'products_deal_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'yozi'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id' => 'products_deal_layout',
                'type' => 'select',
                'title' => esc_html__('Products Layout', 'yozi'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'yozi'),
                    'carousel' => esc_html__('Carousel', 'yozi'),
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'products_deal_rows',
                'type' => 'select',
                'title' => esc_html__('Number Rows', 'yozi'),
                'options' => array(
                    1 => esc_html__('1 Row', 'yozi'),
                    2 => esc_html__('2 Rows', 'yozi'),
                    3 => esc_html__('3 Rows', 'yozi'),
                ),
                'default' => 1,
                'required' => array('products_deal_layout', 'equals', 'carousel'),
            ),
            array(
                'id' => 'products_deal_style',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Product Style', 'yozi'),
                'options' => array(
                    'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                    'inner-list-small' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                ),
                'default' => 'inner'
            ),
            array(
                'id' => 'products_deal_show_view_more',
                'type' => 'switch',
                'title' => esc_html__('Show View More Button', 'yozi'),
                'default' => false
            ),
            array(
                'id' => 'products_deal_view_more',
                'type' => 'text',
                'title' => esc_html__('View More Text', 'yozi'),
                'default' => 'See all product deals in %s',
                'desc' => esc_html__('%s for category name', 'yozi'),
                'required' => array('products_deal_show_view_more', 'equals', true),
            ),
        )
    );
    $sections[] = array(
        'title' => esc_html__('BestSeller Block Setting', 'yozi'),
        'subsection' => true,
        'fields' => array(
            array(
                'id' => 'show_bestseller_in_shop',
                'type' => 'switch',
                'title' => esc_html__('Show in Shop Page', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'products_bestseller_categories',
                'type' => 'select',
                'title' => esc_html__('Display this block in categories', 'yozi'),
                'options' => $categories,
                'multi' => true,
            ),
            array(
                'id' => 'products_bestseller_title',
                'type' => 'text',
                'title' => esc_html__('Title', 'yozi'),
                'default' => 'Best sellers',
                'desc' => esc_html__('%s for category name', 'yozi')
            ),
            array(
                'id' => 'products_bestseller_number',
                'type' => 'text',
                'title' => esc_html__('Number Products', 'yozi'),
                'default' => 12,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider',
            ),
            array(
                'id' => 'products_bestseller_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'yozi'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id' => 'products_bestseller_layout',
                'type' => 'select',
                'title' => esc_html__('Products Layout', 'yozi'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'yozi'),
                    'carousel' => esc_html__('Carousel', 'yozi'),
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'products_bestseller_rows',
                'type' => 'select',
                'title' => esc_html__('Number Rows', 'yozi'),
                'options' => array(
                    1 => esc_html__('1 Row', 'yozi'),
                    2 => esc_html__('2 Rows', 'yozi'),
                    3 => esc_html__('3 Rows', 'yozi'),
                ),
                'default' => 1,
                'required' => array('products_bestseller_layout', 'equals', 'carousel'),
            ),
            array(
                'id' => 'products_bestseller_style',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Product Style', 'yozi'),
                'options' => array(
                    'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                    'inner-list-small' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                ),
                'default' => 'inner'
            ),
            array(
                'id' => 'products_bestseller_show_view_more',
                'type' => 'switch',
                'title' => esc_html__('Show View More Button', 'yozi'),
                'default' => false
            ),
            array(
                'id' => 'products_bestseller_view_more',
                'type' => 'text',
                'title' => esc_html__('View More Text', 'yozi'),
                'default' => 'See all best sellers in %s',
                'desc' => esc_html__('%s for category name', 'yozi'),
                'required' => array('products_bestseller_show_view_more', 'equals', true),
            ),
        )
    );
    $sections[] = array(
        'title' => esc_html__('New Releases Block Setting', 'yozi'),
        'subsection' => true,
        'fields' => array(
            array(
                'id' => 'show_new_in_shop',
                'type' => 'switch',
                'title' => esc_html__('Show in Shop Page', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'products_new_categories',
                'type' => 'select',
                'title' => esc_html__('Display this block in categories', 'yozi'),
                'options' => $categories,
                'multi' => true,
            ),
            array(
                'id' => 'products_new_title',
                'type' => 'text',
                'title' => esc_html__('Title', 'yozi'),
                'default' => 'Hot new releases in %s',
                'desc' => esc_html__('%s for category name', 'yozi')
            ),
            array(
                'id' => 'products_new_number',
                'type' => 'text',
                'title' => esc_html__('Number Products', 'yozi'),
                'default' => 12,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider',
            ),
            array(
                'id' => 'products_new_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'yozi'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id' => 'products_new_layout',
                'type' => 'select',
                'title' => esc_html__('Products Layout', 'yozi'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'yozi'),
                    'carousel' => esc_html__('Carousel', 'yozi'),
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'products_new_rows',
                'type' => 'select',
                'title' => esc_html__('Number Rows', 'yozi'),
                'options' => array(
                    1 => esc_html__('1 Row', 'yozi'),
                    2 => esc_html__('2 Rows', 'yozi'),
                    3 => esc_html__('3 Rows', 'yozi'),
                ),
                'default' => 1,
                'required' => array('products_new_layout', 'equals', 'carousel'),
            ),
            array(
                'id' => 'products_new_style',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Product Style', 'yozi'),
                'options' => array(
                    'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                    'inner-list-small' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                ),
                'default' => 'inner'
            ),
            array(
                'id' => 'products_new_show_view_more',
                'type' => 'switch',
                'title' => esc_html__('Show View More Button', 'yozi'),
                'default' => false
            ),
            array(
                'id' => 'products_new_view_more',
                'type' => 'text',
                'title' => esc_html__('View More Text', 'yozi'),
                'default' => 'See all new releases in %s',
                'desc' => esc_html__('%s for category name', 'yozi'),
                'required' => array('products_new_show_view_more', 'equals', true),
            ),
        )
    );
    $sections[] = array(
        'title' => esc_html__('Top Rated Block Setting', 'yozi'),
        'subsection' => true,
        'fields' => array(
            array(
                'id' => 'show_toprated_in_shop',
                'type' => 'switch',
                'title' => esc_html__('Show in Shop Page', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'products_toprated_categories',
                'type' => 'select',
                'title' => esc_html__('Display this block in categories', 'yozi'),
                'options' => $categories,
                'multi' => true,
            ),
            array(
                'id' => 'products_toprated_title',
                'type' => 'text',
                'title' => esc_html__('Title', 'yozi'),
                'default' => 'Top rated in %s',
                'desc' => esc_html__('%s for category name', 'yozi')
            ),
            array(
                'id' => 'products_toprated_number',
                'type' => 'text',
                'title' => esc_html__('Number Products', 'yozi'),
                'default' => 12,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider',
            ),
            array(
                'id' => 'products_toprated_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'yozi'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id' => 'products_toprated_layout',
                'type' => 'select',
                'title' => esc_html__('Products Layout', 'yozi'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'yozi'),
                    'carousel' => esc_html__('Carousel', 'yozi'),
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'products_toprated_rows',
                'type' => 'select',
                'title' => esc_html__('Number Rows', 'yozi'),
                'options' => array(
                    1 => esc_html__('1 Row', 'yozi'),
                    2 => esc_html__('2 Rows', 'yozi'),
                    3 => esc_html__('3 Rows', 'yozi'),
                ),
                'default' => 1,
                'required' => array('products_toprated_layout', 'equals', 'carousel'),
            ),
            array(
                'id' => 'products_toprated_style',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Product Style', 'yozi'),
                'options' => array(
                    'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                    'inner-list-small' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                ),
                'default' => 'inner'
            ),
            array(
                'id' => 'products_toprated_show_view_more',
                'type' => 'switch',
                'title' => esc_html__('Show View More Button', 'yozi'),
                'default' => false
            ),
            array(
                'id' => 'products_toprated_view_more',
                'type' => 'text',
                'title' => esc_html__('View More Text', 'yozi'),
                'default' => 'See all top rated in %s',
                'desc' => esc_html__('%s for category name', 'yozi'),
                'required' => array('products_toprated_show_view_more', 'equals', true),
            ),
        )
    );
    $sections[] = array(
        'title' => esc_html__('Recommended Block Setting', 'yozi'),
        'subsection' => true,
        'fields' => array(
            array(
                'id' => 'show_recommended_in_shop',
                'type' => 'switch',
                'title' => esc_html__('Show in Shop Page', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'products_recommended_categories',
                'type' => 'select',
                'title' => esc_html__('Display this block in categories', 'yozi'),
                'options' => $categories,
                'multi' => true,
            ),
            array(
                'id' => 'products_recommended_title',
                'type' => 'text',
                'title' => esc_html__('Title', 'yozi'),
                'default' => 'Recommended Products',
                'desc' => esc_html__('%s for category name', 'yozi')
            ),
            array(
                'id' => 'products_recommended_number',
                'type' => 'text',
                'title' => esc_html__('Number Products', 'yozi'),
                'default' => 12,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider',
            ),
            array(
                'id' => 'products_recommended_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'yozi'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id' => 'products_recommended_layout',
                'type' => 'select',
                'title' => esc_html__('Products Layout', 'yozi'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'yozi'),
                    'carousel' => esc_html__('Carousel', 'yozi'),
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'products_recommended_rows',
                'type' => 'select',
                'title' => esc_html__('Number Rows', 'yozi'),
                'options' => array(
                    1 => esc_html__('1 Row', 'yozi'),
                    2 => esc_html__('2 Rows', 'yozi'),
                    3 => esc_html__('3 Rows', 'yozi'),
                ),
                'default' => 1,
                'required' => array('products_recommended_layout', 'equals', 'carousel'),
            ),
            array(
                'id' => 'products_recommended_style',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Product Style', 'yozi'),
                'options' => array(
                    'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                    'inner-list-small' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                ),
                'default' => 'inner'
            ),
            array(
                'id' => 'products_recommended_show_view_more',
                'type' => 'switch',
                'title' => esc_html__('Show View More Button', 'yozi'),
                'default' => false
            ),
            array(
                'id' => 'products_recommended_view_more',
                'type' => 'text',
                'title' => esc_html__('View More Text', 'yozi'),
                'default' => 'See all top rated in %s',
                'desc' => esc_html__('%s for category name', 'yozi'),
                'required' => array('products_recommended_show_view_more', 'equals', true),
            ),
        )
    );
    $sections[] = array(
        'title' => esc_html__('Recently Viewed Block Setting', 'yozi'),
        'subsection' => true,
        'fields' => array(
            array(
                'id' => 'products_recently_viewed_title',
                'type' => 'text',
                'title' => esc_html__('Title', 'yozi'),
                'default' => 'Recently Viewed Products',
            ),
            array(
                'id' => 'products_recently_viewed_number',
                'type' => 'text',
                'title' => esc_html__('Number Products', 'yozi'),
                'default' => 12,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider',
            ),
            array(
                'id' => 'products_recently_viewed_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'yozi'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id' => 'products_recently_viewed_layout',
                'type' => 'select',
                'title' => esc_html__('Products Layout', 'yozi'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'yozi'),
                    'carousel' => esc_html__('Carousel', 'yozi'),
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'products_recently_viewed_rows',
                'type' => 'select',
                'title' => esc_html__('Number Rows', 'yozi'),
                'options' => array(
                    1 => esc_html__('1 Row', 'yozi'),
                    2 => esc_html__('2 Rows', 'yozi'),
                    3 => esc_html__('3 Rows', 'yozi'),
                ),
                'default' => 1,
                'required' => array('products_recently_viewed_layout', 'equals', 'carousel'),
            ),
            array(
                'id' => 'products_recently_viewed_style',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Product Style', 'yozi'),
                'options' => array(
                    'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                    'inner-list-small' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                ),
                'default' => 'inner'
            )
        )
    );
    // Product Page
    $sections[] = array(
        'title' => esc_html__('Single Product', 'yozi'),
        'fields' => array(
            array (
                'id' => 'product_general_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('General Setting', 'yozi').'</h3>',
            ),
            array(
                'id' => 'product_single_version',
                'type' => 'select',
                'title' => esc_html__('Product Layout', 'yozi'),
                'options' => array(
                    'v1' => esc_html__('Layout 1 (3 Columns)', 'yozi'),
                    'v2' => esc_html__('Layout 2 (2 Columns)', 'yozi'),
                    'v3' => esc_html__('Layout 3 (1 Images Column)', 'yozi'),
                ),
                'default' => 'v2',
            ),
            array(
                'id' => 'product_thumbs_position',
                'type' => 'select',
                'title' => esc_html__('Thumbnails Position', 'yozi'),
                'options' => array(
                    'thumbnails-left' => esc_html__('Thumbnails Left', 'yozi'),
                    'thumbnails-right' => esc_html__('Thumbnails Right', 'yozi'),
                    'thumbnails-bottom' => esc_html__('Thumbnails Bottom', 'yozi'),
                ),
                'default' => 'thumbnails-left',
                'required' => array('product_single_version', '=', array('v1', 'v2'))
            ),
            array(
                'id' => 'number_product_thumbs',
                'title' => esc_html__('Number Thumbnails Per Row', 'yozi'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '8',
                'type' => 'slider',
                'required' => array('product_single_version','=',array('v1', 'v2'))
            ),
            array(
                'id' => 'show_product_countdown_timer',
                'type' => 'switch',
                'title' => esc_html__('Show Product CountDown Timer', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_social_share',
                'type' => 'switch',
                'title' => esc_html__('Show Social Share', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'product_content_layout',
                'type' => 'select',
                'title' => esc_html__('Product Content Layout', 'yozi'),
                'options' => array(
                    'tabs' => esc_html__('Tabs', 'yozi'),
                    'accordion' => esc_html__('Accordion', 'yozi'),
                ),
                'default' => 'tabs',
            ),



            array(
                'id' => 'show_product_review_tab',
                'type' => 'switch',
                'title' => esc_html__('Show Product Review Tab', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'hidden_product_additional_information_tab',
                'type' => 'switch',
                'title' => esc_html__('Hidden Product Additional Information Tab', 'yozi'),
                'default' => 1
            ),

            array (
                'id' => 'product_sidebar_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Sidebar Setting', 'yozi').'</h3>',
            ),
            array(
                'id' => 'product_single_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Single Product Sidebar Layout', 'yozi'),
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
                'id' => 'product_single_fullwidth',
                'type' => 'switch',
                'title' => esc_html__('Is Full Width?', 'yozi'),
                'default' => false
            ),
            array(
                'id' => 'product_single_left_sidebar',
                'type' => 'select',
                'title' => esc_html__('Single Product Left Sidebar', 'yozi'),
                'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'yozi'),
                'options' => $sidebars
            ),
            array(
                'id' => 'product_single_right_sidebar',
                'type' => 'select',
                'title' => esc_html__('Single Product Right Sidebar', 'yozi'),
                'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'yozi'),
                'options' => $sidebars
            ),
            array (
                'id' => 'product_block_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3 style="margin: 0;"> '.esc_html__('Product Block Setting', 'yozi').'</h3>',
            ),
            array(
                'id' => 'show_product_accessory',
                'type' => 'switch',
                'title' => esc_html__('Show Product Accessories', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_product_bought_together',
                'type' => 'switch',
                'title' => esc_html__('Show Products Bought together this product', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'number_product_bought',
                'title' => esc_html__('Number of Bought Together products to show', 'yozi'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '20',
                'type' => 'slider',
                'required' => array('show_product_product_bought_together','=',true)
            ),
            array(
                'id' => 'bought_product_columns',
                'type' => 'select',
                'title' => esc_html__('Bought Together Products Columns', 'yozi'),
                'options' => $columns,
                'default' => 4,
                'required' => array('show_product_product_bought_together','=',true)
            ),

            array(
                'id' => 'show_product_product_viewed_together',
                'type' => 'switch',
                'title' => esc_html__('Show Products Viewed together this product', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'number_product_viewed',
                'title' => esc_html__('Number of Viewed Together products to show', 'yozi'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '20',
                'type' => 'slider',
                'required' => array('show_product_product_viewed_together','=',true)
            ),
            array(
                'id' => 'viewed_product_columns',
                'type' => 'select',
                'title' => esc_html__('Viewed Together Products Columns', 'yozi'),
                'options' => $columns,
                'default' => 4,
                'required' => array('show_product_product_viewed_together','=',true)
            ),
            array(
                'id' => 'show_product_releated',
                'type' => 'switch',
                'title' => esc_html__('Show Products Releated', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_upsells',
                'type' => 'switch',
                'title' => esc_html__('Show Products upsells', 'yozi'),
                'default' => 1
            ),
            array(
                'id' => 'number_product_releated',
                'title' => esc_html__('Number of related/upsells products to show', 'yozi'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '20',
                'type' => 'slider'
            ),
            array(
                'id' => 'releated_product_columns',
                'type' => 'select',
                'title' => esc_html__('Releated Products Columns', 'yozi'),
                'options' => $columns,
                'default' => 4
            ),
        )
    );
    
    return $sections;
}
add_filter( 'yozi_redux_framwork_configs', 'yozi_woo_redux_config', 10, 3 );