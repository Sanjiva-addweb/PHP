<?php 
/*
 * The template for displaying the vendor sold by on the shop loop
 *
 * Override this template by copying it to yourtheme/wc-vendors/front/
 *
 * @package    WCVendors
 * @version    2.0.0
 * 		
 * Template Variables available 
 *  
 * $vendor_id  : current vendor id for customization 
 * $sold_by_label : sold by label 
 * $sold_by : sold by 
 *
 *
 */


if ( class_exists('WCVendors_Pro') ) {
	$store_icon = '';
	$image_id = get_user_meta( $vendor_id, '_wcv_store_icon_id', true );
	if ($image_id) {
		$store_icon_src 	= wp_get_attachment_image_src( $image_id, array( 150, 150 ) );
		if ( is_array( $store_icon_src ) ) { 
			$store_icon 	= '<img src="'. $store_icon_src[0].'" alt="" class="store-icon" />'; 
		}
	}
} else {
	$logo_url = get_user_meta( $vendor_id, '_logo_image', true );
	if ( $logo_url ) { 
		$store_icon 	= '<img src="'. $logo_url.'" alt="" class="store-icon" />'; 
	}
}
?>

<div class="wcvendors_sold_by_in_loop">
	<?php if ( ! empty( $store_icon ) ) : ?>
		<a href="<?php echo esc_url(WCV_Vendors::get_vendor_shop_page( $vendor_id )); ?>" title="<?php echo esc_attr($sold_by_label .' '.WCV_Vendors::get_vendor_sold_by( $vendor_id )); ?>" data-toggle="tooltip">
			<?php echo trim($store_icon); ?>
		</a>
	<?php endif; ?>	
</div>