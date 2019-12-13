<?php
/**
 * Display the vendor store ratings 
 * 
 * Override this template by copying it to yourtheme/wc-vendors/store
 *
 * @package    WCVendors_Pro
 * @version    1.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$vendor_shop 		= urldecode( get_query_var( 'vendor_shop' ) );
$vendor_id   		= WCV_Vendors::get_vendor_id( $vendor_shop ); 
$vendor_feedback 	= WCVendors_Pro_Ratings_Controller::get_vendor_feedback( $vendor_id );
$vendor_shop_url	= WCV_Vendors::get_vendor_shop_page( $vendor_id ); 

get_header();
?>
	<div class="wrapper-shop ">
		<section id="main-container" class="wrapper-rating">
			<div class="row">
				<div id="main-content" class="archive-shop col-xs-12 ">

					<div id="primary" class="content-area">
						<div class="site-content" role="main">
							<?php do_action( 'woocommerce_before_main_content' ); ?>

							<h1 class="page-title"><?php esc_html_e( 'Customer Ratings', 'yozi' ); ?></h1>

							<?php if ( $vendor_feedback ) { 

								foreach ( $vendor_feedback as $vf ) {

									$customer 		= get_userdata( $vf->customer_id ); 
									$rating 		= $vf->rating; 
									$rating_title 	= $vf->rating_title; 
									$comment 		= $vf->comments;
									$post_date		= date_i18n( get_option( 'date_format' ), strtotime( $vf->postdate ) );  
									$customer_name 	= ucfirst( $customer->display_name ); 
									$product_link	= get_permalink( $vf->product_id );
									$product_title	= get_the_title( $vf->product_id ); 

									// This outputs the star rating 
									$stars = ''; 
									for ($i = 1; $i<=stripslashes( $rating ); $i++) { $stars .= "<i class='fa fa-star'></i>"; } 
									for ($i = stripslashes( $rating ); $i<5; $i++) { $stars .=  "<i class='fa fa-star-o'></i>"; }
									?> 
										<div class="comment-lists">
										<p class='products-comment'><?php esc_html_e( 'Product: ', 'yozi'); ?><a href="<?php echo esc_url($product_link); ?>" target="_blank"><?php echo trim($product_title); ?></a></p>
										<p class="meta"><span><?php esc_html__( 'Posted on', 'yozi'); ?> <?php echo trim($post_date); ?></span> <?php esc_html__( 'by', 'yozi'); echo trim($customer_name); ?></p>
										<div class="rating">
											<?php echo trim($stars); ?>
										</div>
										<h3 class="title-comment"><?php if ( ! empty( $rating_title ) ) { echo trim($rating_title.' :: '); } ?> </h3>
										<p><?php echo trim($comment); ?></p></div>
									
									<?php 
								}

								
							} else {  echo esc_html__( 'No ratings have been submitted for this vendor yet.', 'yozi' ); }  ?>	
							
							<div><a href="<?php echo esc_url($vendor_shop_url); ?>" class="btn btn-danger btn-return"><?php esc_html_e( 'Return to store', 'yozi' ); ?></a></div>

							<?php do_action( 'woocommerce_after_main_content' ); ?>

						</div><!-- #content -->
					</div><!-- #primary -->
				</div><!-- #main-content -->
			</div>
		</section>
	</div>
<?php get_footer(); ?>