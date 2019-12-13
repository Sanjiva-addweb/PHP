<?php 
/**
 * The Template for displaying a vendor in the vendor list shortcode 
 *
 * Override this template by copying it to yourtheme/wc-vendors/front
 *
 * @package    WCVendors_Pro
 * @version    1.5.2
 */
$image_id = get_user_meta( $vendor_id, '_wcv_store_icon_id', true );
$store_icon = '';
if ($image_id) {
	$store_icon_src = wp_get_attachment_image_src( $image_id, array( 180, 180 ) );
	// see if the array is valid
	if ( is_array( $store_icon_src ) ) { 
		$store_icon 	= '<img src="'. $store_icon_src[0].'" alt="" class="store-icon" />'; 
	}
}
$phone				= ( array_key_exists( '_wcv_store_phone', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_phone' ]  : '';
// Migrate to store address array 
$address1 			= ( array_key_exists( '_wcv_store_address1', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_address1' ] : ''; 
$address2 			= ( array_key_exists( '_wcv_store_address2', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_address2' ] : '';
$city	 			= ( array_key_exists( '_wcv_store_city', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_city' ]  : '';
$state	 			= ( array_key_exists( '_wcv_store_state', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_state' ] : '';
$phone				= ( array_key_exists( '_wcv_store_phone', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_phone' ]  : '';
$store_postcode		= ( array_key_exists( '_wcv_store_postcode', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_postcode' ]  : '';

$address 			= ( $address1 != '') ? $address1 .', ' . $city .', '. $state .', '. $store_postcode : ''; 

$user = get_userdata($vendor_id);
$registered_date = $user->data->user_registered;
$email = $user->data->user_email;
?>
<div class="col-sm-6">
	<div class="wcv-pro-vendorlist"> 
		<div class="row"> 
			<?php if ($store_icon) { ?>
				<div class="col-md-4 col-xs-12">
					<a class="avatar" href="<?php echo esc_url($shop_link); ?>">
						<?php echo trim($store_icon); ?>
					</a>
				</div>	
			<?php } ?>
			<div class="col-md-8 col-xs-12">
				<div class="store-info wcv-shop-details"> 
					<h4 class="name-store"><a href="<?php echo esc_url($shop_link); ?>"><?php echo trim($shop_name); ?></a></h4>
					<div class="metas">
						<div class="listing-meta">
							<?php echo WCVendors_Pro_Ratings_Controller::ratings_link( $vendor_id, true ); ?>
						</div>
						<div class="products">
							<span class="total-label"><?php esc_html_e('Product:', 'yozi'); ?></span>
							<?php
								$products = new WP_Query( array('author' => $vendor_id, 'post_type'	=> 'product') );
							?>
							<span class="total-value"><?php echo trim($products->post_count); ?></span>
						</div>
						<div class="registered-date">
							<?php echo date( 'M Y', strtotime($registered_date) ); ?>
						</div>
					</div>
					<?php if ( $address != '' ) { ?>
					<div class="store-address">
						<!---------------------20-11-2019-------------->
						<!-- <a href="//maps.google.com/maps?&q=<?php //echo esc_url($address); ?>"><address><i class="fa fa-map-marker"></i><?php //echo trim($address); ?></address></a> -->
					</div>
					<?php } ?>
					<?php if ($phone != '')  { ?>
						<div class="store-phone">	  
							<a href="tel:<?php echo esc_url($phone); ?>"><i class="fa fa-phone"></i><?php echo trim($phone); ?></a>
						</div>
					<?php } ?>
					<?php if ($email != '')  { ?>
						<div class="store-phone">	  
							<a href="mailto:<?php echo esc_url($email); ?>"><i class="fa fa-envelope-o"></i><?php echo trim($email); ?></a>
						</div>
					<?php } ?>
				</div>
			</div>
		</div><!-- close wcv-store-grid -->

	</div>
</div>