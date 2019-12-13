<?php
/**
 * Vendor Main Header Template
 *
 * THIS FILE WILL LOAD ON VENDORS STORE URLs (such as yourdomain.com/vendors/bobs-store/)
 *
 * This template can be overridden by copying it to yourtheme/wc-vendors/front/vendor-main-header.php
 *
 * @author		Jamie Madden, WC Vendors
 * @package 	WCVendors/Templates/Emails/HTML
 * @version 	2.0.0
 *
 *
 * Template Variables available
 * $vendor : 			For pulling additional user details from vendor account.  This is an array.
 * $vendor_id  : 		current vendor user id number
 * $shop_name : 		Store/Shop Name (From Vendor Dashboard Shop Settings)
 * $shop_description : Shop Description (completely sanitized) (From Vendor Dashboard Shop Settings)
 * $seller_info : 		Seller Info(From Vendor Dashboard Shop Settings)
 * $vendor_email :		Vendors email address
 * $vendor_login : 	Vendors user_login name
 * $vendor_shop_link : URL to the vendors store
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$twitter_username 	= get_user_meta( $vendor_id , '_wcv_twitter_username', true ); 
$instagram_username = get_user_meta( $vendor_id , '_wcv_instagram_username', true ); 
$facebook_url 		= get_user_meta( $vendor_id , '_wcv_facebook_url', true ); 
$linkedin_url 		= get_user_meta( $vendor_id , '_wcv_linkedin_url', true ); 
$youtube_url 		= get_user_meta( $vendor_id , '_wcv_youtube_url', true ); 
$googleplus_url 	= get_user_meta( $vendor_id , '_wcv_googleplus_url', true ); 
$pinterest_url 		= get_user_meta( $vendor_id , '_wcv_pinterest_url', true ); 
$snapchat_username 	= get_user_meta( $vendor_id , '_wcv_snapchat_username', true ); 

$social_icons = empty( $twitter_username ) && empty( $instagram_username ) && empty( $facebook_url ) && empty( $linkedin_url ) && empty( $youtube_url ) && empty( $googleplus_url ) && empty( $pinterst_url ) && empty( $snapchat_username ) ? false : true; 

$logo_url = get_user_meta( $vendor_id, '_logo_image', true );
$user_info = get_userdata( $vendor_id );
$email = $user_info->data->user_email;

?>
<div class="wcv-header-container">

	<div class="wcv-store-grid wcv-store-header"> 

		<div id="banner-wrap">

			<div id="inner-element">
				<div class="store-info pull-left">
					<div class="media">
						<?php if ( ! empty( $logo_url ) ) : ?>
					  		<div class="media-left media-middle">	  
					  			<div class="store-brand clearfix">
						   			<img class="store-icon" src="<?php echo esc_url($logo_url); ?>" alt="">
						   		</div>
						   	</div>
					   	<?php endif; ?>
					   	<div class="media-body media-middle">
					   		<div class="title-wrapper">
						   		<h3 class="title-store"><?php echo trim($shop_name); ?></h3>

							</div>
					   		<!-- rating -->
					   		<div class="rating-products-wrapper">
								<div class="total-products">
									<span class="total-label"><?php esc_html_e('Total products', 'yozi'); ?></span>
									<?php
										$products = new WP_Query( array('author' => $vendor_id, 'post_type'	=> 'product', 'posts_per_page' => -1) );
									?>
									<span class="total-value"><?php echo trim($products->post_count); ?></span>
								</div>
							</div>
							<!-- contact -->
							<?php if ($email != '')  { ?>
								<div class="store-phone">	  
									<a href="mailto:<?php echo esc_url($email); ?>"><i class="fa fa-envelope-o"></i><?php echo trim($email); ?></a>
								</div>
							<?php } ?>
							
					   	</div>
				   	</div>
				   	<?php if ( $social_icons ) : ?> 				   		   			
					   	<ul class="social-icons"> 
				   			<?php if ( $facebook_url != '') { ?>
				   				<li><a href="<?php echo esc_url($facebook_url); ?>" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
			   				<?php } ?>
				   			<?php if ( $instagram_username != '') { ?>
				   				<li><a href="//instagram.com/<?php echo esc_url($instagram_username); ?>" class="instagram" target="_blank"><i class="fa fa-instagram"></i></a></li>
			   				<?php } ?>
				   			<?php if ( $twitter_username != '') { ?>
				   				<li><a href="//twitter.com/<?php echo esc_url($twitter_username); ?>" class="twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
			   				<?php } ?>
				   			<?php if ( $googleplus_url != '') { ?>
				   				<li><a href="<?php echo esc_url($googleplus_url); ?>" class="googleplus" target="_blank"><i class="fa fa-google-plus"></i></a></li>
			   				<?php } ?>
				   			<?php if ( $pinterest_url != '') { ?>
				   				<li><a href="<?php echo esc_url($pinterest_url); ?>" class="pinterest" target="_blank"><i class="fa fa-pinterest"></i></a></li>
			   				<?php } ?>
				   			<?php if ( $youtube_url != '') { ?>
				   				<li><a href="<?php echo esc_url($youtube_url); ?>" class="youtube" target="_blank"><i class="fa fa-youtube"></i></a></li>
			   				<?php } ?>
				   			<?php if ( $linkedin_url != '') { ?>
				   				<li><a href="<?php echo esc_url($linkedin_url); ?>" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i></a></li>
			   				<?php } ?>
				   			<?php if ( $snapchat_username != '') { ?>
				   				<li><a href="//www.snapchat.com/add/<?php echo esc_url($snapchat_username); ?>" target="_blank"><i class="fa fa-snapchat" aria-hidden="true"></i></a></li>
			   				<?php } ?>
					   	</ul>
					<?php endif; ?>
			   	</div>

			   	<div class="store-aurhor pull-right">
					<div class="store-aurhor-inner">
						<?php echo get_avatar( $vendor_id, 70 ); ?>
						<p class="name-author"><?php echo trim($user_info->first_name .'&nbsp;'. $user_info->last_name); ?></p>
						<a href="mailto:<?php echo trim($user_info->data->user_email); ?>" class="btn-mess"><?php esc_html_e( 'Send a message', 'yozi' ); ?></a>
					</div>
				</div>
				   	

			</div>
		</div>
	</div>
	<div class="tabs">
		<div class="woocommerce-tabs">
			<ul class="tab-product nav nav-tabs">

				<?php if ($shop_description != '') { ?>
					<li class="active"><a data-toggle="tab" href="#tabs-list-about"><?php esc_html_e( 'About Shop', 'yozi' ); ?></a></li>
				<?php } ?>
				<li><a data-toggle="tab" href="#tabs-list-best-deals"><?php esc_html_e( 'Best Deals', 'yozi' ); ?></a></li>

			</ul>
			<div class="tab-content">

				<?php if ($shop_description != '') { ?>
					<div class="tab-pane active in" id="tabs-list-about">
						<?php echo trim($shop_description); ?>
					</div>
				<?php } ?>
				
				<div class="tab-pane" id="tabs-list-best-deals">
					<?php
						wc_get_template( 'tabs-best-deals.php', array( 
							'vendor_id' => $vendor_id,
						), 'wc-vendors/store/' ); 
					?>
				</div>
				
			</div>
		</div>
	</div>
</div>