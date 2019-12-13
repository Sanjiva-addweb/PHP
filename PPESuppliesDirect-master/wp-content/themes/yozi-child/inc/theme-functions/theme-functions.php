<?php

defined( 'ABSPATH' ) || exit;
/*

*/
add_action( 'wp_footer', 'footer_js', 100 );
function footer_js() {
global $product;
// $product->is_type( $type ) checks the product type, string/array $type ( 'simple', 'grouped', 'variable', 'external' ), returns boolean
if ( is_product() && $product->is_type( 'variable' ) ) {?>
<style>p.price, div.quantity, label[for=quantity_v]{display:none !important;}div#ivpa-content{width: 20%;float: left;margin-top: 0;}div#ppom-box-2{width: 80%;float: left;clear: none;}</style>
<!--<script>jQuery(function(){jQuery('.ivpa_active').each(function(){jQuery('').remove();});});</script>--><?php
}
}
/*

*/
add_action( 'add_meta_boxes', 'create_custom_sizeguide_box' );
if ( ! function_exists( 'create_custom_sizeguide_box' ) )
{
    function create_custom_sizeguide_box()
    {
        add_meta_box(
            'custom_product_sizeguide_meta_box',
            __( 'Size Guide Information', 'cmb' ),
            'add_custom_sizeguide_content_meta_box',
            'product',
            'normal',
            'default'
        );
    }
}
/*
Custom metabox content in admin product pages
*/
if ( ! function_exists( 'add_custom_sizeguide_content_meta_box' ) ){
    function add_custom_sizeguide_content_meta_box( $post ){
        $prefix = '_bhww_'; // global $prefix;
        $productsku = get_post_meta($post->ID, '_sku', true);
	 $sizeinfometa = get_post_meta($post->ID,'sizeguide');
	 if(empty($sizeinfometa))
	 {
		 global $wpdb;
		$sizeinfo = $wpdb->get_results('Select * from wp_sizechart where product_code="'.$productsku.'" ');
			$sizeinfo = json_decode($sizeinfo[0]->size_info);
			 $sizedata = "<table class='sizeguide'>";
			if($sizeinfo->other[0]->C != 'Size:'){
				$sizedata .= "<tr>";
			 foreach($sizeinfo->size as $i=>$info)
				{
					if($i>1 && $info !='')
			 		{
						$sizedata .= "<th>".$info."</th>";
					}
				}
			 $sizedata .= "</tr>";
			}
		foreach($sizeinfo->other as $otherinfo)
		{
			$sizedata .= "<tr>";
			foreach($otherinfo as $k=>$info)
			{
				if($k != 'A' && $k !='B' && $info != '')
				{
					if($otherinfo->C == 'Size:')
					{
						$sizedata .= "<th>".$info."</th>";
					}
					else
					{
						$sizedata .= "<td>".$info."</td>";
					}
				}
			}
			$sizedata .= "</tr>";
		}
		$sizedata .= "</table>";
	}
	 else
	 {
		 $sizedata = html_entity_decode($sizeinfometa[0]);
	 }
        $args['textarea_rows'] = 6;
        echo '<p>'.__( 'Size Guide info', 'cmb' ).'</p>';
        wp_editor( $sizedata, 'sizeguide_wysiwyg', $args );
        echo '<input type="hidden" name="custom_product_field_nonce" value="' . wp_create_nonce() . '">';
    }
}
/*
Save the data of the Meta field
*/
add_action( 'save_post', 'save_custom_content_sizeguide_meta_box', 10, 1 );
if ( ! function_exists( 'save_custom_content_sizeguide_meta_box' ) )
{
    function save_custom_content_sizeguide_meta_box( $post_id ) {
        $prefix = '_bhww_';
        if ( ! isset( $_POST[ 'custom_product_field_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'custom_product_field_nonce' ];
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        if ( 'product' == $_POST[ 'post_type' ] ){
            if ( ! current_user_can( 'edit_product', $post_id ) )
                return $post_id;
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }
		global $wpdb;
		$productsku = get_post_meta($post_id, '_sku', true);
		$sizeinfoid = $wpdb->get_results('Select id from wp_sizechart where product_code="'.$productsku.'" ');
		if(!empty($sizeinfoid))
		{
			$wpdb->update(
				'wp_sizechart',
				array(
					'sizehtml' => wp_kses_post($_POST[ 'sizeguide_wysiwyg' ])
				),
				array( 'id' => $sizeinfoid[0]->id ),
				array(
					'%s'
				),
				array( '%d' )
			);
		}
		else
		{
			$wpdb->insert(
				'wp_sizechart',
				array(
					'sizehtml' => wp_kses_post($_POST[ 'sizeguide_wysiwyg' ]),
					'product_code' => $productsku
				),
				array(
					'%s',
					'%s'
				)
			);
		}
    }
}
/*

*/
