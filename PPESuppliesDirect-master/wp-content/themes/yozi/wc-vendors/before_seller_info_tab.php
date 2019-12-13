
<div class="seller-info-wrapper">
	<?php
	$vendor_id = WCV_Vendors::get_vendor_from_product( get_the_ID() );
	if ( WCV_Vendors::is_vendor( $vendor_id ) ) {
		$store_icon_src = wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), 'pt-vendor-main-logo' );
		$store_icon = '';
		if ( is_array( $store_icon_src ) ) {
			$store_icon = $store_icon_src[0];
		}
		if ( class_exists('WCVendors_Pro') ) {
			$store_url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id );
		} else {
			$store_url = WCV_Vendors::get_vendor_shop_page($vendor_id);
		}
		$store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );

		$twitter_username 	= get_user_meta( $vendor_id , '_wcv_twitter_username', true );
		$instagram_username = get_user_meta( $vendor_id , '_wcv_instagram_username', true );
		$facebook_url = get_user_meta( $vendor_id , '_wcv_facebook_url', true );
		$linkedin_url = get_user_meta( $vendor_id , '_wcv_linkedin_url', true );
		$youtube_url = get_user_meta( $vendor_id , '_wcv_youtube_url', true );
		$googleplus_url = get_user_meta( $vendor_id , '_wcv_googleplus_url', true );
	?>
		<div class="seller-info-top">
			<div class="media">
				<?php if ($store_icon) { ?>
				<div class="media-left">
					<div class="store-brand">
						<a href="<?php echo esc_url($store_url); ?>">
							<img src="<?php echo esc_url($store_icon); ?>" alt="" class="store-icon" />
						</a>
					</div>
				</div>
				<?php } ?>
				<div class="media-body">
					<h3 class="name-store"><a href="<?php echo esc_url($store_url); ?>"><?php echo trim($store_name); ?></a></h3>
					<?php
					$ratings = '';
		  			if ( class_exists('WCVendors_Pro') && !WCVendors_Pro::get_option( 'ratings_management_cap' ) ) {
		  				?>
		  				<div class="seller-info-rating">
			  				<?php
			  				$average_rate = WCVendors_Pro_Ratings_Controller::get_ratings_average( $vendor_id );
			  				$rate_count = WCVendors_Pro_Ratings_Controller::get_ratings_count( $vendor_id );
			  				$url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id ) . 'ratings';
			  				if ( $average_rate !=0 ) {
				  				echo sprintf(_n('Rating: <strong>%s</strong> based on %d rating', 'Rating: %s based on %d ratings', $rate_count, 'yozi'), $average_rate, $rate_count);
				  				echo '<a href="'.esc_url($url).'" class="btn">'.esc_html__('View all ratings', 'yozi').'</a>';
			  				} else {
			  					echo esc_html__("Rating: This Seller still doesn't have any ratings yet.", 'yozi');
			  				}
			  				?>
		  				</div>
		  				<?php
		  			}
		  			?>
		  			<div class="seller-info-social">
		  				<?php if ( $facebook_url != '') { ?>
		  					<li><a href="<?php echo esc_url($facebook_url); ?>" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
	  					<?php } ?>
	  					<?php if ( $twitter_username != '') { ?>
		  					<li><a href="//twitter.com/<?php echo esc_url($twitter_username); ?>" class="twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
	  					<?php } ?>
	  					<?php if ( $instagram_username != '') { ?>
		  					<li><a href="//instagram.com/<?php echo esc_url($instagram_username); ?>" class="instagram" target="_blank"><i class="fa fa-instagram"></i></a></li>
	  					<?php } ?>
	  					<?php if ( $googleplus_url != '') { ?>
		  					<li><a href="<?php echo esc_url($googleplus_url); ?>" class="googleplus" target="_blank"><i class="fa fa-google-plus"></i></a></li>
	  					<?php } ?>
	  					<?php if ( $linkedin_url != '') { ?>
		  					<li><a href="<?php echo esc_url($linkedin_url); ?>" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i></a></li>
	  					<?php } ?>
	  					<?php if ( $youtube_url != '') { ?>
		  					<li><a href="<?php echo esc_url($youtube_url); ?>" class="youtube" target="_blank"><i class="fa fa-youtube"></i></a></li>
	  					<?php } ?>
		  			</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="seller-info-description">