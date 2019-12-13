<?php
if ( !function_exists( 'yozi_footer_metaboxes' ) ) {
	function yozi_footer_metaboxes(array $metaboxes) {
		$prefix = 'apus_footer_';
	    $fields = array(
			array(
				'name' => esc_html__( 'Footer Style', 'yozi' ),
				'id'   => $prefix.'style_class',
				'type' => 'select',
				'options' => array(
					'container' => esc_html__('Boxed', 'yozi'),
					'full' => esc_html__('Full', 'yozi'),
				)
			),
			array(
				'name' => esc_html__( 'Footer Background Color', 'yozi' ),
				'id'   => $prefix.'background_class',
				'type' => 'select',
				'options' => array(
					'' => esc_html__('White', 'yozi'),
					'dark' => esc_html__('Dark', 'yozi'),
				)
			),
    	);
		
	    $metaboxes[$prefix . 'display_setting'] = array(
			'id'                        => $prefix . 'display_setting',
			'title'                     => esc_html__( 'Display Settings', 'yozi' ),
			'object_types'              => array( 'apus_footer' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => $fields
		);

	    return $metaboxes;
	}
}
add_filter( 'cmb2_meta_boxes', 'yozi_footer_metaboxes' );