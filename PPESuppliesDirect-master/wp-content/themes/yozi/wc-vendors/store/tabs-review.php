<div id="vendor-ratings" class="tab-pane active">
	<div id="reviews" class="row">
	<?php
		$vendor_ratings = WCVendors_Pro_Ratings_Controller::get_vendor_feedback( $vendor_id );
		$average_rate = WCVendors_Pro_Ratings_Controller::get_ratings_average( $vendor_id );
		$rate_count = WCVendors_Pro_Ratings_Controller::get_ratings_count( $vendor_id );
		$reviews_url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id ) . 'ratings';
		$detail_ratings = yozi_get_vendor_detail_ratings( $vendor_id );
		
	if ( $vendor_ratings ) {
		if ( $average_rate != 0 ) {
			?>
			<div class="vendor-reviews-info col-xs-12 col-sm-3">
				<div class="vendor-reviews-inner">
					<div class="average-value">
						<?php echo sprintf(__( '%s <span>overall</span>', 'yozi' ), $average_rate); ?>
					</div>
					<div class="title-info">
						<?php echo sprintf(_n('Based on %d review', 'Based on %d reviews', $rate_count, 'yozi'), $rate_count); ?>
					</div>
					<div class="detailed-rating">
						<div class="rating-box">
							<div class="detailed-rating-inner">

								<?php for ( $i = 5; $i >= 1; $i -- ) : ?>
									<div class="skill special-progress">
										<div class="star-rating" title="Rated 4 out of 5">
											<span style="width:<?php echo esc_attr($i * 20); ?>%"></span>
										</div>
										<div class="progress">
											<div class="value-percent hidden"><?php echo ( $rate_count && !empty( $detail_ratings[$i] ) ) ? esc_attr(  round( $detail_ratings[$i] / $rate_count * 100, 2 ) . '%' ) : '0%'; ?></div>
											<div class="progress-bar progress-bar-default" style="<?php echo ( $rate_count && !empty( $detail_ratings[$i] ) ) ? esc_attr( 'width: ' . ( $detail_ratings[$i] / $rate_count * 100 ) . '%' ) : 'width: 0%'; ?>">
											</div>
										</div>
										<div class="value"><?php echo empty( $detail_ratings[$i] ) ? '0' : esc_html( $detail_ratings[$i] ); ?></div>
									</div>
								<?php endfor; ?>
							</div>
						</div>
					</div>
					<span><?php esc_html_e('Latest reviews presented here ', 'yozi'); ?></span>
					<p class="view-rating"><a class="btn btn-theme btn-outline" href="<?php echo esc_url($reviews_url); ?>"><?php esc_html_e('View all ratings', 'yozi'); ?></a></p>
				</div>
			</div>
			<?php
		}
		?>
		<div class="vendor-reviews col-xs-12 col-sm-9">
		<?php
			$count = 0;
			foreach ( $vendor_ratings as $vendor_rating ) {
				/* Show only 3 latest reviews */
				if ($count > 2 ) {
					continue;
				}

				$customer = get_userdata( $vendor_rating->customer_id );
				$rating = $vendor_rating->rating;
				$rating_title	= $vendor_rating->rating_title;
				$comment = $vendor_rating->comments;
				$post_date = date_i18n( get_option( 'date_format' ), strtotime( $vendor_rating->postdate ) );
				$customer_name = ucfirst( $customer->display_name );
				$product_link	= get_permalink( $vendor_rating->product_id );
				$product_title = get_the_title( $vendor_rating->product_id );

				// This outputs the star rating
				$stars = '';
				for ($i = 1; $i<=stripslashes( $rating ); $i++) { $stars .= "<i class='fa fa-star'></i>"; }
				for ($i = stripslashes( $rating ); $i<5; $i++) { $stars .=  "<i class='fa fa-star-o'></i>"; }
				?>

				<div class="single-rating">
					<div class="media">
						<div class="media-left">
							<?php echo get_avatar( $vendor_rating->customer_id, 70 ); ?>
						</div>
						<div class="media-body">
							<div class="stars-value"><?php echo trim($stars); ?></div>
							<h4><span class="name"><?php echo esc_attr($customer_name); ?> </span><span><?php esc_html_e( '-', 'yozi'); echo esc_attr($post_date); ?></span></h4>
							<div class="review-container">
								<?php if ( ! empty( $rating_title ) ) { ?>
									<h6><?php echo esc_attr($rating_title); ?></h6>
								<?php } ?>
								<p><?php echo esc_textarea($comment); ?></p>
							</div>
						</div>
					</div>
				</div>

				<?php $count++;
			}
		?>
		</div>
		<?php
	} else { ?>
		<div class="col-xs-12">
			<?PHP esc_html_e( 'No ratings have been submitted for this vendor yet.', 'yozi' ); ?>
		</div>
	<?php } ?>
	</div>
</div>