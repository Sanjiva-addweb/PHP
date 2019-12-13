<?php

if ( !function_exists( 'yozi_product_metaboxes' ) ) {
	function yozi_product_metaboxes(array $metaboxes) {
		$prefix = 'apus_product_';
	    $fields = array(
	    	array(
				'name' => esc_html__( 'Review Video', 'yozi' ),
				'id'   => $prefix.'review_video',
				'type' => 'text',
				'description' => esc_html__( 'You can enter a video youtube or vimeo', 'yozi' ),
			),
			array(
				'name' => esc_html__( 'Features', 'yozi' ),
				'id'   => $prefix.'features',
				'type' => 'wysiwyg'
			),
    	);
		
	    $metaboxes[$prefix . 'display_setting'] = array(
			'id'                        => $prefix . 'display_setting',
			'title'                     => esc_html__( 'More Information', 'yozi' ),
			'object_types'              => array( 'product' ),
			'context'                   => 'normal',
			'priority'                  => 'low',
			'show_names'                => true,
			'fields'                    => $fields
		);

	    return $metaboxes;
	}
}
add_filter( 'cmb2_meta_boxes', 'yozi_product_metaboxes' );
