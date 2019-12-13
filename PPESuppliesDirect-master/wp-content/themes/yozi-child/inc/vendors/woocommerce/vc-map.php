<?php
if ( function_exists('vc_map') && class_exists('WPBakeryShortCode') ) {
	
	if ( !function_exists('yozi_vc_get_product_object')) {
		function yozi_vc_get_product_object($term) {
			$vc_taxonomies_types = vc_taxonomies_types();

			return array(
				'label' => $term->post_title,
				'value' => $term->post_name,
				'group_id' => $term->post_name,
				'group' => isset( $vc_taxonomies_types[ $term->taxonomy ], $vc_taxonomies_types[ $term->taxonomy ]->labels, $vc_taxonomies_types[ $term->taxonomy ]->labels->name ) ? $vc_taxonomies_types[ $term->taxonomy ]->labels->name : esc_html__( 'Taxonomies', 'yozi' ),
			);
		}
	}

	if ( !function_exists('yozi_product_field_search')) {
		function yozi_product_field_search( $search_string ) {
			$data = array();
			$loop = yozi_get_products( array( 'product_type' => 'deals', 'search' => $search_string ) );

			if ( !empty($loop->posts) ) {
				foreach ( $loop->posts as $t ) {
					if ( is_object( $t ) ) {
						$data[] = yozi_vc_get_product_object( $t );
					}
				}
			}
			
			return $data;
		}
	}

	if ( !function_exists('yozi_product_render')) {
		function yozi_product_render( $query ) {
			$args = array(
			  'name'        => $query['value'],
			  'post_type'   => 'product',
			  'post_status' => 'publish',
			  'numberposts' => 1
			);
			$products = get_posts($args);
			if ( ! empty( $query ) && $products ) {
				$product = $products[0];
				$data = array();
				$data['value'] = $product->post_name;
				$data['label'] = $product->post_title;
				return ! empty( $data ) ? $data : false;
			}
			return false;
		}
	}
	add_filter( 'vc_autocomplete_apus_product_deal_product_slugs_callback', 'yozi_product_field_search', 10, 1 );
	add_filter( 'vc_autocomplete_apus_product_deal_product_slugs_render', 'yozi_product_render', 10, 1 );
	
	if ( !function_exists('yozi_vc_get_term_object')) {
		function yozi_vc_get_term_object($term) {
			$vc_taxonomies_types = vc_taxonomies_types();

			return array(
				'label' => $term->name,
				'value' => $term->slug,
				'group_id' => $term->taxonomy,
				'group' => isset( $vc_taxonomies_types[ $term->taxonomy ], $vc_taxonomies_types[ $term->taxonomy ]->labels, $vc_taxonomies_types[ $term->taxonomy ]->labels->name ) ? $vc_taxonomies_types[ $term->taxonomy ]->labels->name : esc_html__( 'Taxonomies', 'yozi' ),
			);
		}
	}

	if ( !function_exists('yozi_category_field_search')) {
		function yozi_category_field_search( $search_string ) {
			$data = array();
			$vc_taxonomies_types = array('product_cat');
			$vc_taxonomies = get_terms( $vc_taxonomies_types, array(
				'hide_empty' => false,
				'search' => $search_string
			) );
			if ( is_array( $vc_taxonomies ) && ! empty( $vc_taxonomies ) ) {
				foreach ( $vc_taxonomies as $t ) {
					if ( is_object( $t ) ) {
						$data[] = yozi_vc_get_term_object( $t );
					}
				}
			}
			return $data;
		}
	}

	if ( !function_exists('yozi_category_render')) {
		function yozi_category_render($query) {  
			$category = get_term_by('slug', $query['value'], 'product_cat');
			if ( ! empty( $query ) && !empty($category)) {
				$data = array();
				$data['value'] = $category->slug;
				$data['label'] = $category->name;
				return ! empty( $data ) ? $data : false;
			}
			return false;
		}
	}

	$bases = array( 'apus_products' );
	foreach( $bases as $base ){   
		add_filter( 'vc_autocomplete_'.$base .'_categories_callback', 'yozi_category_field_search', 10, 1 );
	 	add_filter( 'vc_autocomplete_'.$base .'_categories_render', 'yozi_category_render', 10, 1 );
	}

	function yozi_load_woocommerce_element() {
		$categories = array();
		if ( is_admin() ) {
			$categories = yozi_woocommerce_get_categories();
		}
		$types = array(
			esc_html__('Recent Products', 'yozi' ) => 'recent_product',
			esc_html__('Best Selling', 'yozi' ) => 'best_selling',
			esc_html__('Featured Products', 'yozi' ) => 'featured_product',
			esc_html__('Top Rate', 'yozi' ) => 'top_rate',
			esc_html__('On Sale', 'yozi' ) => 'on_sale',
			esc_html__('Recent Review', 'yozi' ) => 'recent_review'
		);

		vc_map( array(
			'name' => esc_html__( 'Apus Products', 'yozi' ),
			'base' => 'apus_products',
			'icon' => 'icon-wpb-woocommerce',
			'category' => esc_html__( 'Apus Woocommerce', 'yozi' ),
			'description' => esc_html__( 'Display products in frontend', 'yozi' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'yozi' ),
					'param_name' => 'title',
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Get Products By", 'yozi'),
					"param_name" => "type",
					"value" => $types,
				),
				array(
				    'type' => 'autocomplete',
				    'heading' => esc_html__( 'Get Products By Categories', 'yozi' ),
				    'param_name' => 'categories',
				    'settings' => array(
				     	'multiple' => true,
				     	'unique_values' => true
				    ),
			   	),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number Products', 'yozi' ),
					'value' => 10,
					'param_name' => 'number',
					'description' => esc_html__( 'Number products per page to show', 'yozi' ),
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__( 'Layout Type', 'yozi' ),
					"param_name" => "layout_type",
					"value" => array(
						esc_html__( 'Grid', 'yozi' ) => 'grid',
						esc_html__( 'Carousel', 'yozi' ) => 'carousel',
					)
				),
				array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Columns', 'yozi'),
	                "param_name" => 'columns',
	                "value" => array(1,2,3,4,5,6),
	            ),
	            array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Rows', 'yozi'),
	                "param_name" => 'rows',
	                "value" => array(1,2,3,4,5),
	                'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
	            ),
				array(
					"type" => "dropdown",
					"heading" => esc_html__( 'Product Style', 'yozi' ),
					"param_name" => "product_style",
					"value" => array(
						esc_html__( 'Grid Style', 'yozi' ) => 'inner',
						esc_html__( 'List Style', 'yozi' ) => 'inner-list-small',
						esc_html__( 'List Smallest', 'yozi' ) => 'inner-list-smallest',
					)
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Navigation', 'yozi' ),
					'param_name' => 'show_nav',
					'value' => array( esc_html__( 'Yes, to show navigation', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Pagination', 'yozi' ),
					'param_name' => 'show_pagination',
					'value' => array( esc_html__( 'Yes, to show Pagination', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
	            array(
					"type" => "textfield",
					"heading" => esc_html__("Extra class name",'yozi'),
					"param_name" => "el_class",
					"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.",'yozi')
				)
			)
		) );

		vc_map( array(
			'name' => esc_html__( 'Apus Products Tabs', 'yozi' ),
			'base' => 'apus_products_tabs',
			'icon' => 'icon-wpb-woocommerce',
			'category' => esc_html__( 'Apus Woocommerce', 'yozi' ),
			'description' => esc_html__( 'Display products in Tabs', 'yozi' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Widget Title', 'yozi' ),
					'param_name' => 'title',
				),
				array(
					'type' => 'param_group',
					'heading' => esc_html__( 'Product Tabs', 'yozi' ),
					'param_name' => 'tabs',
					'params' => array(
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Tab Title', 'yozi' ),
							'param_name' => 'title',
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__("Get Products By",'yozi'),
							"param_name" => "type",
							"value" => $types,
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__( 'Category', 'yozi' ),
							"param_name" => "category",
							"value" => $categories
						),
					)
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number Products', 'yozi' ),
					'value' => 10,
					'param_name' => 'number',
					'description' => esc_html__( 'Number products per page to show', 'yozi' ),
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__( 'Layout Type', 'yozi' ),
					"param_name" => "layout_type",
					"value" => array(
						esc_html__( 'Grid', 'yozi' ) => 'grid',
						esc_html__( 'Carousel', 'yozi' ) => 'carousel',
					)
				),
				array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Style for Tab', 'yozi'),
	                "param_name" => 'style_tab',
					"value" => array(
						esc_html__( 'Default', 'yozi' ) => '',
						esc_html__( 'Hierarchy', 'yozi' ) => 'hierarchy',
						esc_html__( 'Center', 'yozi' ) => 'style_center',
					)
	            ),
				array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Columns', 'yozi'),
	                "param_name" => 'columns',
	                "value" => array(1,2,3,4,5,6),
	            ),
	            array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Rows', 'yozi'),
	                "param_name" => 'rows',
	                "value" => array(1,2,3,4),
	                'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
	            ),
				array(
					"type" => "dropdown",
					"heading" => esc_html__( 'Product Item Style', 'yozi' ),
					"param_name" => "product_item",
					"value" => array(
						esc_html__( 'Grid Style', 'yozi' ) => 'inner',
						esc_html__( 'List Style', 'yozi' ) => 'list',
					)
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Navigation', 'yozi' ),
					'param_name' => 'show_nav',
					'value' => array( esc_html__( 'Yes, to show navigation', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Pagination', 'yozi' ),
					'param_name' => 'show_pagination',
					'value' => array( esc_html__( 'Yes, to show Pagination', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
	            array(
					"type" => "textfield",
					"heading" => esc_html__("Extra class name",'yozi'),
					"param_name" => "el_class",
					"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.",'yozi')
				)
			)
		) );

		vc_map( array(
			'name' => esc_html__( 'Apus Products Deal', 'yozi' ),
			'base' => 'apus_product_deal',
			'icon' => 'icon-wpb-woocommerce',
			'category' => esc_html__( 'Apus Woocommerce', 'yozi' ),
			'description' => esc_html__( 'Display product deal', 'yozi' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'yozi' ),
					'param_name' => 'title',
				),
				array(
				    'type' => 'autocomplete',
				    'heading' => esc_html__( 'Choose Products', 'yozi' ),
				    'param_name' => 'product_slugs',
				    'settings' => array(
				     	'multiple' => true,
				     	'unique_values' => true
				    ),
			   	),
			   	array(
					"type" => "dropdown",
					"heading" => esc_html__( 'Layout Type', 'yozi' ),
					"param_name" => "layout_type",
					"value" => array(
						esc_html__( 'Grid', 'yozi' ) => 'grid',
						esc_html__( 'Carousel', 'yozi' ) => 'carousel',
					)
				),
				array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Columns', 'yozi'),
	                "param_name" => 'columns',
	                "value" => array(1,2,3,4,5,6),
	            ),
	            array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Rows', 'yozi'),
	                "param_name" => 'rows',
	                "value" => array(1,2,3,4),
	                'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
	            ),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Navigation', 'yozi' ),
					'param_name' => 'show_nav',
					'value' => array( esc_html__( 'Yes, to show navigation', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Pagination', 'yozi' ),
					'param_name' => 'show_pagination',
					'value' => array( esc_html__( 'Yes, to show Pagination', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show 2 Item on Laptop', 'yozi' ),
					'param_name' => 'show_smalldestop',
					'value' => array( esc_html__( 'Yes', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
	            array(
					"type" => "textfield",
					"heading" => esc_html__("Extra class name", 'yozi'),
					"param_name" => "el_class",
					"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.",'yozi')
				)
			)
		) );

		vc_map( array(
			'name' => esc_html__( 'Apus Category Banners ', 'yozi' ),
			'base' => 'apus_category_banner',
			'icon' => 'icon-wpb-woocommerce',
			'category' => esc_html__( 'Apus Woocommerce', 'yozi' ),
			'description' => esc_html__( 'Display category banner', 'yozi' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'yozi' ),
					'param_name' => 'title',
				),
				array(
					'type' => 'param_group',
					'heading' => esc_html__( 'Banner', 'yozi' ),
					'param_name' => 'categoriesbanners',
					'params' => array(
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Title', 'yozi' ),
							'param_name' => 'title',
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__( 'Category', 'yozi' ),
							"param_name" => "category",
							"value" => $categories
						),
						array(
							"type" => "attach_image",
							"heading" => esc_html__("Category Image", 'yozi'),
							"param_name" => "image"
						),
					)
				),
	            array(
					"type" => "dropdown",
					"heading" => esc_html__( 'Layout Type', 'yozi' ),
					"param_name" => "layout_type",
					"value" => array(
						esc_html__( 'Grid', 'yozi' ) => 'grid',
						esc_html__( 'Carousel', 'yozi' ) => 'carousel',
					)
				),
				array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Columns', 'yozi'),
	                "param_name" => 'columns',
	                "value" => array(1,2,3,4,5,6,7,8),
	            ),
	            array(
	                "type" => "dropdown",
	                "heading" => esc_html__('Rows', 'yozi'),
	                "param_name" => 'rows',
	                "value" => array(1,2,3,4),
	                'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
	            ),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Navigation', 'yozi' ),
					'param_name' => 'show_nav',
					'value' => array( esc_html__( 'Yes, to show navigation', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Pagination', 'yozi' ),
					'param_name' => 'show_pagination',
					'value' => array( esc_html__( 'Yes, to show Pagination', 'yozi' ) => 'yes' ),
					'dependency' => array(
						'element' => 'layout_type',
						'value' => array('carousel'),
					),
				),
	            array(
					"type" => "textfield",
					"heading" => esc_html__("Extra class name", 'yozi'),
					"param_name" => "el_class",
					"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
				)
			)
		) );
	}
	add_action( 'vc_after_set_mode', 'yozi_load_woocommerce_element', 99 );

	class WPBakeryShortCode_apus_products extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_products_tabs extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_product_deal extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_category_banner extends WPBakeryShortCode {}
}