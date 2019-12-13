<?php 
/**
 * Vendor List Template
 *
 * This template can be overridden by copying it to yourtheme/wc-vendors/front/vendors-list.php
 *
 * @author		Jamie Madden, WC Vendors
 * @package 	WCVendors/Templates/Emails/HTML
 * @version 	2.0.0
 * 
 *	Template Variables available
 *  $shop_name : pv_shop_name
 *  $shop_description : pv_shop_description (completely sanitized)
 *  $shop_link : the vendor shop link
 *  $vendor_id  : current vendor id for customization

 */


$logo_url = get_user_meta( $vendor_id, '_logo_image', true );
if ( $logo_url ) {
	$store_icon = '<img src="'. esc_url($logo_url) .'" alt="" class="store-icon" />';
}

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