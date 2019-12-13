<?php
if ( function_exists('vc_map') && class_exists('WPBakeryShortCode') ) {
	// custom wp
	$attributes = array(
	    'type' => 'dropdown',
	    'heading' => "Style Element",
	    'param_name' => 'style',
	    'value' => array( "one", "two", "three" ),
	    'description' => esc_html__( "New style attribute", "yozi" )
	);
	vc_add_param( 'vc_facebook', $attributes ); // Note: 'vc_message' was used as a base for "Message box" element

	if ( !function_exists('yozi_load_load_theme_element')) {
		function yozi_load_load_theme_element() {
			$columns = array(1,2,3,4,6);
			// Heading Text Block
			vc_map( array(
				'name'        => esc_html__( 'Apus Widget Heading','yozi'),
				'base'        => 'apus_title_heading',
				"class"       => "",
				"category" => esc_html__('Apus Elements', 'yozi'),
				'description' => esc_html__( 'Create title for one Widget', 'yozi' ),
				"params"      => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Widget title', 'yozi' ),
						'param_name' => 'title',
						'description' => esc_html__( 'Enter heading title.', 'yozi' ),
						"admin_label" => true,
					),
					array(
						"type" => "textarea_html",
						'heading' => esc_html__( 'Description', 'yozi' ),
						"param_name" => "content",
						"value" => '',
						'description' => esc_html__( 'Enter description for title.', 'yozi' )
				    ),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Style", 'yozi'),
						"param_name" => "style",
						'value' 	=> array(
							esc_html__('Default Center', 'yozi') => '', 
							esc_html__('White Center', 'yozi') => 'white_center', 
							esc_html__('Normal Center', 'yozi') => 'normal_center', 
						),
						'std' => ''
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'yozi' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'yozi' )
					)
				),
			));
		
			vc_map( array(
				'name'        => esc_html__( 'Apus Address','yozi'),
				'base'        => 'apus_address',
				"class"       => "",
				"category" => esc_html__('Apus Elements', 'yozi'),
				'description' => esc_html__( 'Create title for one Widget', 'yozi' ),
				"params"      => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Widget title', 'yozi' ),
						'param_name' => 'title',
						'description' => esc_html__( 'Enter heading title.', 'yozi' ),
						"admin_label" => true,
					),
					array(
						"type" => "textarea_html",
						'heading' => esc_html__( 'Description', 'yozi' ),
						"param_name" => "content",
						"value" => '',
						'description' => esc_html__( 'Enter description for title.', 'yozi' )
				    ),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'yozi' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'yozi' )
					)
				),
			));

			// calltoaction
			vc_map( array(
				'name'        => esc_html__( 'Apus Widget Call To Action','yozi'),
				'base'        => 'apus_call_action',
				"class"       => "",
				"category" => esc_html__('Apus Elements', 'yozi'),
				'description' => esc_html__( 'Create title for one Widget', 'yozi' ),
				"params"      => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Widget title', 'yozi' ),
						'param_name' => 'title',
						'value'       => esc_html__( 'Title', 'yozi' ),
						'description' => esc_html__( 'Enter heading title.', 'yozi' ),
						"admin_label" => true
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Sub title', 'yozi' ),
						'param_name' => 'subtitle',
						'description' => esc_html__( 'Enter Sub title.', 'yozi' ),
						"admin_label" => true
					),
					array(
						"type" => "textarea_html",
						'heading' => esc_html__( 'Description', 'yozi' ),
						"param_name" => "content",
						"value" => '',
						'description' => esc_html__( 'Enter description for title.', 'yozi' )
				    ),

				    array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Text Button 1', 'yozi' ),
						'param_name' => 'textbutton1',
						'description' => esc_html__( 'Text Button', 'yozi' ),
						"admin_label" => true
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( ' Link Button 1', 'yozi' ),
						'param_name' => 'linkbutton1',
						'description' => esc_html__( 'Link Button 1', 'yozi' ),
						"admin_label" => true
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Button Style", 'yozi'),
						"param_name" => "buttons1",
						'value' 	=> array(
							esc_html__('Default ', 'yozi') => 'btn-default ', 
							esc_html__('Primary ', 'yozi') => 'btn-primary ', 
							esc_html__('Success ', 'yozi') => 'btn-success radius-0 ', 
							esc_html__('Info ', 'yozi') => 'btn-info ', 
							esc_html__('Warning ', 'yozi') => 'btn-warning ', 
							esc_html__('Theme Color ', 'yozi') => 'btn-theme',
							esc_html__('Theme Gradient Color ', 'yozi') => 'btn-theme btn-gradient',
							esc_html__('Second Color ', 'yozi') => 'btn-theme-second',
							esc_html__('Danger ', 'yozi') => 'btn-danger ', 
							esc_html__('Pink ', 'yozi') => 'btn-pink ', 
							esc_html__('White Gradient ', 'yozi') => 'btn-white btn-gradient', 
							esc_html__('Primary Outline', 'yozi') => 'btn-primary btn-outline', 
							esc_html__('White Outline ', 'yozi') => 'btn-white btn-outline ',
							esc_html__('Theme Outline ', 'yozi') => 'btn-theme btn-outline ',
						),
						'std' => ''
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Text Button 2', 'yozi' ),
						'param_name' => 'textbutton2',
						'description' => esc_html__( 'Text Button', 'yozi' ),
						"admin_label" => true
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( ' Link Button 2', 'yozi' ),
						'param_name' => 'linkbutton2',
						'description' => esc_html__( 'Link Button 2', 'yozi' ),
						"admin_label" => true
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Button Style", 'yozi'),
						"param_name" => "buttons2",
						'value' 	=> array(
							esc_html__('Default ', 'yozi') => 'btn-default ', 
							esc_html__('Primary ', 'yozi') => 'btn-primary ', 
							esc_html__('Success ', 'yozi') => 'btn-success radius-0 ', 
							esc_html__('Info ', 'yozi') => 'btn-info ', 
							esc_html__('Warning ', 'yozi') => 'btn-warning ', 
							esc_html__('Theme Color ', 'yozi') => 'btn-theme',
							esc_html__('Second Color ', 'yozi') => 'btn-theme-second',
							esc_html__('Danger ', 'yozi') => 'btn-danger ', 
							esc_html__('Pink ', 'yozi') => 'btn-pink ', 
							esc_html__('White Gradient ', 'yozi') => 'btn-white btn-gradient',
							esc_html__('Primary Outline', 'yozi') => 'btn-primary btn-outline', 
							esc_html__('White Outline ', 'yozi') => 'btn-white btn-outline ',
							esc_html__('Theme Outline ', 'yozi') => 'btn-theme btn-outline ',
						),
						'std' => ''
					),
					
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Style", 'yozi'),
						"param_name" => "style",
						'value' 	=> array(
							esc_html__('Default', 'yozi') => 'default',
							esc_html__('Center', 'yozi') => 'default center',
						),
						'std' => ''
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'yozi' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'yozi' )
					)
				),
			));
			
			// Apus Counter
			vc_map( array(
			    "name" => esc_html__("Apus Counter",'yozi'),
			    "base" => "apus_counter",
			    "class" => "",
			    "description"=> esc_html__('Counting number with your term', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Number", 'yozi'),
						"param_name" => "number",
						"value" => ''
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Color Number", 'yozi'),
						"param_name" => "text_color",
						'value' 	=> '',
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
			// Banner CountDown
			vc_map( array(
				'name'        => esc_html__( 'Apus Banner CountDown','yozi'),
				'base'        => 'apus_banner_countdown',
				"category" => esc_html__('Apus Elements', 'yozi'),
				'description' => esc_html__( 'Show CountDown with banner', 'yozi' ),
				"params"      => array(
					array(
						'type' => 'textarea_html',
						'heading' => esc_html__( 'Widget title', 'yozi' ),
						'param_name' => 'content',
					),
					array(
						"type" => "textarea",
						'heading' => esc_html__( 'Description', 'yozi' ),
						"param_name" => "descript",
						'description' => esc_html__( 'Enter description for title.', 'yozi' )
				    ),
					array(
					    'type' => 'textfield',
					    'heading' => esc_html__( 'Date Expired', 'yozi' ),
					    'param_name' => 'input_datetime'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Button Url', 'yozi' ),
						'param_name' => 'btn_url',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Button Text', 'yozi' ),
						'param_name' => 'btn_text',
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Style", 'yozi'),
						"param_name" => "style_widget",
						'value' 	=> array(
							esc_html__('Light', 'yozi') => 'light',
							esc_html__('Dark', 'yozi') => 'dark',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'yozi' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'yozi' )
					),
				),
			));
			// Banner
			vc_map( array(
				'name'        => esc_html__( 'Apus Banner','yozi'),
				'base'        => 'apus_banner',
				"category" => esc_html__('Apus Elements', 'yozi'),
				'description' => esc_html__( 'Show banner in FrontEnd', 'yozi' ),
				"params"      => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'title', 'yozi' ),
						'param_name' => 'title',
					),
					array(
						"type" => "textarea_html",
						'heading' => esc_html__( 'Content', 'yozi' ),
						"param_name" => "content",
						'description' => esc_html__( 'Enter description for title.', 'yozi' )
				    ),
				    array(
						"type" => "attach_image",
						"heading" => esc_html__("Banner Image", 'yozi'),
						"param_name" => "image"
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Url', 'yozi' ),
						'param_name' => 'url',
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Show Button', 'yozi' ),
						'param_name' => 'show_btn',
						'value' => array( esc_html__( 'Yes, to show Button', 'yozi' ) => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Button Text', 'yozi' ),
						'param_name' => 'btn_text',
						'dependency' => array(
							'element' => 'show_btn',
							'value' => array('yes'),
						),
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Style", 'yozi'),
						"param_name" => "style",
						'value' 	=> array(
							esc_html__('Default', 'yozi') => '', 
							esc_html__('Medium', 'yozi') => ' medium', 
							esc_html__('Banner Big Center', 'yozi') => ' banner-big', 
							esc_html__('Banner Medium Center', 'yozi') => ' banner-medium', 
							esc_html__('Banner Dark', 'yozi') => ' banner-dark', 
						),
						'std' => ''
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'yozi' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'yozi' )
					),
				),
			));
			// Apus Brands
			vc_map( array(
			    "name" => esc_html__("Apus Brands",'yozi'),
			    "base" => "apus_brands",
			    "class" => "",
			    "description"=> esc_html__('Display brands on front end', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Number", 'yozi'),
						"param_name" => "number",
						"value" => ''
					),
				 	array(
						"type" => "dropdown",
						"heading" => esc_html__("Layout Type", 'yozi'),
						"param_name" => "layout_type",
						'value' 	=> array(
							esc_html__('Carousel', 'yozi') => 'carousel', 
							esc_html__('Grid', 'yozi') => 'grid'
						),
						'std' => ''
					),
					array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Columns','yozi'),
		                "param_name" => 'columns',
		                "value" => array(1,2,3,4,5,6,8),
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Style','yozi'),
		                "param_name" => 'style',
		                'value' 	=> array(
							esc_html__('Default ', 'yozi') => '', 
							esc_html__('No Border', 'yozi') => ' no-border',
						),
						'std' => ''
		            ),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
			
			vc_map( array(
			    "name" => esc_html__("Apus Socials link",'yozi'),
			    "base" => "apus_socials_link",
			    "description"=> esc_html__('Show socials link', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textarea",
						"heading" => esc_html__("Description", 'yozi'),
						"param_name" => "description",
						"value" => '',
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Facebook Page URL", 'yozi'),
						"param_name" => "facebook_url",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Twitter Page URL", 'yozi'),
						"param_name" => "twitter_url",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Youtube Page URL", 'yozi'),
						"param_name" => "youtube_url",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Pinterest Page URL", 'yozi'),
						"param_name" => "pinterest_url",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Google Plus Page URL", 'yozi'),
						"param_name" => "google-plus_url",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Instagram Page URL", 'yozi'),
						"param_name" => "instagram_url",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Align", 'yozi'),
						"param_name" => "align",
						'value' 	=> array(
							esc_html__('Left', 'yozi') => 'left', 
							esc_html__('Right', 'yozi') => 'right'
						),
						'std' => ''
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Style", 'yozi'),
						"param_name" => "style",
						'value' 	=> array(
							esc_html__('Style 1', 'yozi') => '', 
							esc_html__('Style 2', 'yozi') => 'style2', 
						),
						'std' => ''
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
			// newsletter
			vc_map( array(
			    "name" => esc_html__("Apus Newsletter",'yozi'),
			    "base" => "apus_newsletter",
			    "class" => "",
			    "description"=> esc_html__('Show newsletter form', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						"type" => "textarea",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						"type" => "textarea",
						"heading" => esc_html__("Description", 'yozi'),
						"param_name" => "description",
						"value" => '',
					),
					array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Layout','yozi'),
		                "param_name" => 'style',
		                'value' 	=> array(
							esc_html__('Style 1', 'yozi') => '', 
							esc_html__('Style 2 (Half)', 'yozi') => 'half', 
							esc_html__('Style 3', 'yozi') => 'style3', 
						),
						'std' => ''
		            ),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
			// Address
			vc_map( array(
			    "name" => esc_html__("Apus Service",'yozi'),
			    "base" => "apus_service",
			    "class" => "",
			    "description"=> esc_html__('Show Service', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						'type' => 'param_group',
						'heading' => esc_html__('Members Settings', 'yozi' ),
						'param_name' => 'members',
						'description' => '',
						'value' => '',
						'params' => array(
							array(
								"type" => "attach_image",
								"heading" => esc_html__("Image for Item", 'yozi'),
								"param_name" => "image",
							),
							array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Title','yozi'),
				                "param_name" => "title",
				            ),
				            array(
				                "type" => "textarea",
				                "class" => "",
				                "heading" => esc_html__('Short Description','yozi'),
				                "param_name" => "des",
				            ),
						),
					),
					array(
		                "type" => "textfield",
		                "class" => "",
		                "heading" => esc_html__('Columns','yozi'),
		                "param_name" => "columns",
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Style','yozi'),
		                "param_name" => 'style',
		                'value' 	=> array(
							esc_html__('Vertical ', 'yozi') => '', 
							esc_html__('Horizontal', 'yozi') => 'horizontal',
							esc_html__('Horizontal white', 'yozi') => 'horizontal st-white',
						),
						'std' => ''
		            ),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
			// Address
			vc_map( array(
			    "name" => esc_html__("Apus Location",'yozi'),
			    "base" => "apus_location",
			    "class" => "",
			    "description"=> esc_html__('Show Service', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						'type' => 'param_group',
						'heading' => esc_html__('Members Settings', 'yozi' ),
						'param_name' => 'members',
						'description' => '',
						'value' => '',
						'params' => array(
							array(
								"type" => "attach_image",
								"heading" => esc_html__("Image Icon for Item", 'yozi'),
								"param_name" => "image",
							),
							array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Font Icon','yozi'),
				                "param_name" => "font_icon",
				            ),
							array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Title','yozi'),
				                "param_name" => "title",
				            ),
				            array(
				                "type" => "textarea",
				                "class" => "",
				                "heading" => esc_html__('Short Description','yozi'),
				                "param_name" => "des",
				            ),
						),
					),
					array(
		                "type" => "textfield",
		                "class" => "",
		                "heading" => esc_html__('Columns','yozi'),
		                "param_name" => "columns",
		            ),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
			// google map
			$map_styles = array( esc_html__('Choose a map style', 'yozi') => '' );
			if ( is_admin() && class_exists('Yozi_Google_Maps_Styles') ) {
				$styles = Yozi_Google_Maps_Styles::styles();
				foreach ($styles as $style) {
					$map_styles[$style['title']] = $style['slug'];
				}
			}
			vc_map( array(
			    "name" => esc_html__("Apus Google Map",'yozi'),
			    "base" => "apus_googlemap",
			    "description" => esc_html__('Diplay Google Map', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
					array(
		                "type" => "textarea",
		                "class" => "",
		                "heading" => esc_html__('Description','yozi'),
		                "param_name" => "des",
		            ),
		            array(
		                'type' => 'googlemap',
		                'heading' => esc_html__( 'Location', 'yozi' ),
		                'param_name' => 'location',
		                'value' => ''
		            ),
		            array(
		                'type' => 'hidden',
		                'heading' => esc_html__( 'Latitude Longitude', 'yozi' ),
		                'param_name' => 'lat_lng',
		                'value' => '21.0173222,105.78405279999993'
		            ),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Map height", 'yozi'),
						"param_name" => "height",
						"value" => '',
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Map Zoom", 'yozi'),
						"param_name" => "zoom",
						"value" => '13',
					),
		            array(
		                'type' => 'dropdown',
		                'heading' => esc_html__( 'Map Type', 'yozi' ),
		                'param_name' => 'type',
		                'value' => array(
		                    esc_html__( 'roadmap', 'yozi' ) 		=> 'ROADMAP',
		                    esc_html__( 'hybrid', 'yozi' ) 	=> 'HYBRID',
		                    esc_html__( 'satellite', 'yozi' ) 	=> 'SATELLITE',
		                    esc_html__( 'terrain', 'yozi' ) 	=> 'TERRAIN',
		                )
		            ),
		            array(
						"type" => "attach_image",
						"heading" => esc_html__("Custom Marker Icon", 'yozi'),
						"param_name" => "marker_icon"
					),
					array(
		                'type' => 'dropdown',
		                'heading' => esc_html__( 'Custom Map Style', 'yozi' ),
		                'param_name' => 'map_style',
		                'value' => $map_styles
		            ),
		            
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
			// Testimonial
			vc_map( array(
	            "name" => esc_html__("Apus Testimonials",'yozi'),
	            "base" => "apus_testimonials",
	            'description'=> esc_html__('Display Testimonials In FrontEnd', 'yozi'),
	            "class" => "",
	            "category" => esc_html__('Apus Elements', 'yozi'),
	            "params" => array(
	              	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
	              	array(
		              	"type" => "textfield",
		              	"heading" => esc_html__("Number", 'yozi'),
		              	"param_name" => "number",
		              	"value" => '4',
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Columns','yozi'),
		                "param_name" => 'columns',
		                "value" => array(1,2,3,4,5,6),
		            ),
		            array(
						"type" => "attach_image",
						"heading" => esc_html__("Image for Widget", 'yozi'),
						"param_name" => "image",
						'dependency' => array(
					       'element' => 'style',
					       'value' => array('style2'),
					    ),
					),
		            array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Layout','yozi'),
		                "param_name" => 'style',
		                'value' 	=> array(
							esc_html__('Default ', 'yozi') => '', 
							esc_html__('White ', 'yozi') => 'white',
							esc_html__('White Style 2', 'yozi') => 'style2',
						),
						'std' => ''
		            ),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
	            )
	        ));
	        // Our Team
			vc_map( array(
	            "name" => esc_html__("Apus Our Team",'yozi'),
	            "base" => "apus_ourteam",
	            'description'=> esc_html__('Display Our Team In FrontEnd', 'yozi'),
	            "class" => "",
	            "category" => esc_html__('Apus Elements', 'yozi'),
	            "params" => array(
	              	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
	              	array(
						'type' => 'param_group',
						'heading' => esc_html__('Members Settings', 'yozi' ),
						'param_name' => 'members',
						'description' => '',
						'value' => '',
						'params' => array(
							array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Name','yozi'),
				                "param_name" => "name",
				            ),
				            array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Short Description','yozi'),
				                "param_name" => "des",
				            ),
							array(
								"type" => "attach_image",
								"heading" => esc_html__("Image", 'yozi'),
								"param_name" => "image"
							),

				            array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Facebook','yozi'),
				                "param_name" => "facebook",
				            ),

				            array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Twitter Link','yozi'),
				                "param_name" => "twitter",
				            ),

				            array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Google plus Link','yozi'),
				                "param_name" => "google",
				            ),

				            array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Linkin Link','yozi'),
				                "param_name" => "linkin",
				            ),

						),
					),
					array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Columns','yozi'),
		                "param_name" => 'columns',
		                "value" => array(1,2,3,4,5,6),
		            ),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
	            )
	        ));

	        // Gallery Images
			vc_map( array(
	            "name" => esc_html__("Apus Gallery",'yozi'),
	            "base" => "apus_gallery",
	            'description'=> esc_html__('Display Gallery In FrontEnd', 'yozi'),
	            "class" => "",
	            "category" => esc_html__('Apus Elements', 'yozi'),
	            "params" => array(
	              	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
					array(
						'type' => 'param_group',
						'heading' => esc_html__('Images', 'yozi'),
						'param_name' => 'images',
						'params' => array(
							array(
								"type" => "attach_image",
								"param_name" => "image",
								'heading'	=> esc_html__('Image', 'yozi')
							),
							array(
				                "type" => "textfield",
				                "heading" => esc_html__('Title','yozi'),
				                "param_name" => "title",
				            ),
				            array(
				                "type" => "textarea",
				                "heading" => esc_html__('Description','yozi'),
				                "param_name" => "description",
				            ),
						),
					),
					array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Columns','yozi'),
		                "param_name" => 'columns',
		                "value" => $columns,
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Layout Type','yozi'),
		                "param_name" => 'layout_type',
		                'value' 	=> array(
							esc_html__('Grid', 'yozi') => 'grid', 
							esc_html__('Mansory 1', 'yozi') => 'mansory',
							esc_html__('Mansory 2', 'yozi') => 'mansory2',
						),
						'std' => 'grid'
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Gutter Elements','yozi'),
		                "param_name" => 'gutter',
		                'value' 	=> array(
							esc_html__('Default ', 'yozi') => '', 
							esc_html__('Gutter 30', 'yozi') => 'gutter30',
						),
						'std' => ''
		            ),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
	            )
	        ));
	        // Gallery Video
			vc_map( array(
	            "name" => esc_html__("Apus Video",'yozi'),
	            "base" => "apus_video",
	            'description'=> esc_html__('Display Video In FrontEnd', 'yozi'),
	            "class" => "",
	            "category" => esc_html__('Apus Elements', 'yozi'),
	            "params" => array(
	              	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
					array(
						"type" => "textarea_html",
						'heading' => esc_html__( 'Description', 'yozi' ),
						"param_name" => "content",
						"value" => '',
						'description' => esc_html__( 'Enter description for title.', 'yozi' )
				    ),
	              	array(
						"type" => "attach_image",
						"heading" => esc_html__("Background Play Image", 'yozi'),
						"param_name" => "image"
					),
					array(
		                "type" => "textfield",
		                "heading" => esc_html__('Youtube Video Link','yozi'),
		                "param_name" => 'video_link'
		            ),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
	            )
	        ));
	        // Features Box
			vc_map( array(
	            "name" => esc_html__("Apus Features Box",'yozi'),
	            "base" => "apus_features_box",
	            'description'=> esc_html__('Display Features In FrontEnd', 'yozi'),
	            "class" => "",
	            "category" => esc_html__('Apus Elements', 'yozi'),
	            "params" => array(
	            	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
					array(
						'type' => 'param_group',
						'heading' => esc_html__('Members Settings', 'yozi' ),
						'param_name' => 'items',
						'description' => '',
						'value' => '',
						'params' => array(
							array(
								"type" => "attach_image",
								"description" => esc_html__("Image for box.", 'yozi'),
								"param_name" => "image",
								"value" => '',
								'heading'	=> esc_html__('Image', 'yozi' )
							),
							array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Title','yozi'),
				                "param_name" => "title",
				            ),
				            array(
				                "type" => "textarea",
				                "class" => "",
				                "heading" => esc_html__('Description','yozi'),
				                "param_name" => "description",
				            ),
							array(
								"type" => "textfield",
								"heading" => esc_html__("Material Design Icon and Awesome Icon", 'yozi'),
								"param_name" => "icon",
								"value" => '',
								'description' => esc_html__( 'This support display icon from Material Design and Awesome Icon, Please click', 'yozi' )
												. '<a href="' . ( is_ssl()  ? 'https' : 'http') . '://zavoloklom.github.io/material-design-iconic-font/icons.html" target="_blank">'
												. esc_html__( 'here to see the list', 'yozi' ) . '</a>'
							),
						),
					),
		           	array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Style Layout','yozi'),
		                "param_name" => 'style',
		                'value' 	=> array(
							esc_html__('Default', 'yozi') => '', 
						),
						'std' => ''
		            ),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
	            )
	        ));

			// information
			vc_map( array(
	            "name" => esc_html__("Apus Action Box",'yozi'),
	            "base" => "apus_action_box",
	            'description'=> esc_html__('Display Features In FrontEnd', 'yozi'),
	            "class" => "",
	            "category" => esc_html__('Apus Elements', 'yozi'),
	            "params" => array(
	            	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
					array(
		                "type" => "textarea",
		                "class" => "",
		                "heading" => esc_html__('Description','yozi'),
		                "param_name" => "description",
		            ),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Button Text Action", 'yozi'),
						"param_name" => "text_button",
						"value" => '',
					),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Button Link Action", 'yozi'),
						"param_name" => "link",
						"value" => '',
					),
					array(
						"type" => "attach_image",
						"description" => esc_html__("Background Image for box.", 'yozi'),
						"param_name" => "image",
						"value" => '',
						'heading'	=> esc_html__('Image', 'yozi' ),
						'dependency' => array(
					       'element' => 'style_box',
					       'value' => array('style1')
					    ),
					),
					array(
						"type" => "attach_image",
						"description" => esc_html__("Image for box.", 'yozi'),
						"param_name" => "image_box",
						"value" => '',
						'heading'	=> esc_html__('Image Box', 'yozi' ),
						'dependency' => array(
					       'element' => 'style_box',
					       'value' => array('style2')
					    ),
					),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__("Background Color", 'yozi'),
						"param_name" => "bg_color",
						'value' 	=> '',
						'dependency' => array(
					       'element' => 'style_box',
					       'value' => array('style2')
					    ),
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Style", 'yozi'),
						"param_name" => "style_box",
						'value' 	=> array(
							esc_html__('Style 1', 'yozi') => 'style1', 
							esc_html__('Style 2', 'yozi') => 'style2', 
						),
						'std' => ''
					),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
	            )
	        ));

			// FAQ
			vc_map( array(
	            "name" => esc_html__("Apus FAQ Box",'yozi'),
	            "base" => "apus_faq_box",
	            'description'=> esc_html__('Display FAQ In FrontEnd', 'yozi'),
	            "class" => "",
	            "category" => esc_html__('Apus Elements', 'yozi'),
	            "params" => array(
	            	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
					array(
						'type' => 'param_group',
						'heading' => esc_html__('Members Settings', 'yozi' ),
						'param_name' => 'items',
						'description' => '',
						'value' => '',
						'params' => array(
							array(
				                "type" => "textfield",
				                "class" => "",
				                "heading" => esc_html__('Title','yozi'),
				                "param_name" => "title",
				            ),
				            array(
				                "type" => "textarea",
				                "class" => "",
				                "heading" => esc_html__('Description','yozi'),
				                "param_name" => "description",
				            ),
						),
					),

		            array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
	            )
	        ));



			$custom_menus = array();
			if ( is_admin() ) {
				$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
				if ( is_array( $menus ) && ! empty( $menus ) ) {
					foreach ( $menus as $single_menu ) {
						if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->slug ) ) {
							$custom_menus[ $single_menu->name ] = $single_menu->slug;
						}
					}
				}
			}
			// Menu
			vc_map( array(
			    "name" => esc_html__("Apus Custom Menu",'yozi'),
			    "base" => "apus_custom_menu",
			    "class" => "",
			    "description"=> esc_html__('Show Custom Menu', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"value" => '',
						"admin_label"	=> true
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Menu', 'yozi' ),
						'param_name' => 'nav_menu',
						'value' => $custom_menus,
						'description' => empty( $custom_menus ) ? esc_html__( 'Custom menus not found. Please visit Appearance > Menus page to create new menu.', 'yozi' ) : esc_html__( 'Select menu to display.', 'yozi' ),
						'admin_label' => true,
						'save_always' => true,
					),
					array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Align','yozi'),
		                "param_name" => 'align',
		                'value' 	=> array(
							esc_html__('Inherit', 'yozi') => '', 
							esc_html__('Left', 'yozi') => 'left', 
							esc_html__('Right', 'yozi') => 'right', 
							esc_html__('Center', 'yozi') => 'center', 
							esc_html__('Center Tag', 'yozi') => 'center_tag', 
						),
						'std' => ''
		            ),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
			// Banner Menu
			vc_map( array(
			    "name" => esc_html__("Apus Banner Menu",'yozi'),
			    "base" => "apus_banner_menu",
			    "class" => "",
			    "description"=> esc_html__('Show Custom Menu', 'yozi'),
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"value" => '',
						"admin_label"	=> true
					),
				    array(
						'type' => 'param_group',
						'heading' => esc_html__('Members Settings', 'yozi' ),
						'param_name' => 'items',
						'description' => '',
						'value' => '',
						'params' => array(
					    	array(
								"type" => "textfield",
								"heading" => esc_html__("Title", 'yozi'),
								"param_name" => "title",
								"value" => '',
								"admin_label"	=> true
							),
							array(
								"type" => "attach_image",
								"description" => esc_html__("Image for Widget", 'yozi'),
								"param_name" => "image",
								"value" => '',
								'heading'	=> esc_html__('Image for Widget', 'yozi' )
							),
							array(
								'type' => 'dropdown',
								'heading' => esc_html__( 'Menu', 'yozi' ),
								'param_name' => 'nav_menu',
								'value' => $custom_menus,
								'description' => empty( $custom_menus ) ? esc_html__( 'Custom menus not found. Please visit Appearance > Menus page to create new menu.', 'yozi' ) : esc_html__( 'Select menu to display.', 'yozi' ),
								'admin_label' => true,
								'save_always' => true,
							),
							array(
								"type" => "textfield",
								"heading" => esc_html__("Extra class name", 'yozi'),
								"param_name" => "el_class",
								"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
							)
						)
				   	),
				   	array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Columns','yozi'),
		                "param_name" => 'columns',
		                "value" => $columns
		            ),
				)
			));

			vc_map( array(
	            "name" => esc_html__("Apus Instagram",'yozi'),
	            "base" => "apus_instagram",
	            'description'=> esc_html__('Display Instagram In FrontEnd', 'yozi'),
	            "class" => "",
	            "category" => esc_html__('Apus Elements', 'yozi'),
	            "params" => array(
	            	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"admin_label" => true,
						"value" => '',
					),
					array(
		              	"type" => "textfield",
		              	"heading" => esc_html__("Instagram Username", 'yozi'),
		              	"param_name" => "username",
		            ),
					array(
		              	"type" => "textfield",
		              	"heading" => esc_html__("Number", 'yozi'),
		              	"param_name" => "number",
		              	'value' => '1',
		            ),
	             	array(
		              	"type" => "textfield",
		              	"heading" => esc_html__("Number Columns", 'yozi'),
		              	"param_name" => "columns",
		              	'value' => '1',
		            ),
		           	array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Layout Type','yozi'),
		                "param_name" => 'layout_type',
		                'value' 	=> array(
							esc_html__('Grid', 'yozi') => 'grid', 
							esc_html__('Carousel', 'yozi') => 'carousel', 
						)
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Photo size','yozi'),
		                "param_name" => 'size',
		                'value' 	=> array(
							esc_html__('Thumbnail', 'yozi') => 'thumbnail', 
							esc_html__('Small', 'yozi') => 'small', 
							esc_html__('Large', 'yozi') => 'large', 
							esc_html__('Original', 'yozi') => 'original', 
						)
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Open links in','yozi'),
		                "param_name" => 'target',
		                'value' 	=> array(
							esc_html__('Current window (_self)', 'yozi') => '_self', 
							esc_html__('New window (_blank)', 'yozi') => '_blank',
						)
		            ),
		            array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
	            )
	        ));

			// vertical menu
			$option_menu  = array(); 
			if ( is_admin() ){
				$menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
			    $option_menu = array( esc_html__('--- Select Menu ---', 'yozi') => '' );
			    foreach ($menus as $menu) {
			    	$option_menu[$menu->name] = $menu->slug;
			    }
			} 
			vc_map( array(
			    "name" => esc_html__("Apus Vertical MegaMenu",'yozi'),
			    "base" => "apus_verticalmenu",
			    "class" => "",
			    "category" => esc_html__('Apus Elements', 'yozi'),
			    "params" => array(
			    	array(
						"type" => "textfield",
						"heading" => esc_html__("Title", 'yozi'),
						"param_name" => "title",
						"value" => 'Vertical Menu',
						"admin_label"	=> true
					),

			    	array(
						"type" => "dropdown",
						"heading" => esc_html__("Menu", 'yozi'),
						"param_name" => "menu",
						"value" => $option_menu,
						"description" => esc_html__("Select menu.", 'yozi')
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Position", 'yozi'),
						"param_name" => "position",
						"value" => array(
							'left'=>'left',
							'right'=>'right'
						),
						'std' => 'left',
						"description" => esc_html__("Postion Menu Vertical.", 'yozi')
					),
					array(
		                "type" => "dropdown",
		                "heading" => esc_html__('Style','yozi'),
		                "param_name" => 'style',
		                'value' 	=> array(
							esc_html__('Styel Default ', 'yozi') => '', 
							esc_html__('Styel Darken ', 'yozi') => 'darken', 
						),
						'std' => ''
		            ),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Extra class name", 'yozi'),
						"param_name" => "el_class",
						"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'yozi')
					)
			   	)
			));
		}
	}
	add_action( 'vc_after_set_mode', 'yozi_load_load_theme_element', 99 );

	class WPBakeryShortCode_apus_title_heading extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_call_action extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_brands extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_socials_link extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_newsletter extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_googlemap extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_testimonials extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_banner_countdown extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_banner extends WPBakeryShortCode {}

	class WPBakeryShortCode_apus_counter extends WPBakeryShortCode {
		public function __construct( $settings ) {
			parent::__construct( $settings );
			$this->load_scripts();
		}

		public function load_scripts() {
			wp_register_script('jquery-counterup', get_template_directory_uri().'/js/jquery.counterup.min.js', array('jquery'), false, true);
		}
	}
	class WPBakeryShortCode_apus_ourteam extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_gallery extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_video extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_features_box extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_faq_box extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_action_box extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_custom_menu extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_instagram extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_service extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_verticalmenu extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_address extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_banner_menu extends WPBakeryShortCode {}
	class WPBakeryShortCode_apus_location extends WPBakeryShortCode {}
}