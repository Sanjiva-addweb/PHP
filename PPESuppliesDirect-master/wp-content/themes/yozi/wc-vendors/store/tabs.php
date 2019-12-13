<?php

$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
$vendor_rating_page = urldecode( get_query_var( 'ratings' ) );
$vendor_id = WCV_Vendors::get_vendor_id( $vendor_shop );
$featured_carousel = get_user_meta( $vendor_id , 'pv_featured_carousel', true );
$store_rates	= get_user_meta( $vendor_id, '_wcv_shipping', true );
$default_shipping_settings = get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' );

$shipping_policy 	= ( empty( $store_rates[ 'shipping_policy' ] ) ) ? $default_shipping_settings[ 'shipping_policy'] : $store_rates[ 'shipping_policy' ];
$return_policy		= ( empty( $store_rates[ 'return_policy' ] ) ) ? $default_shipping_settings[ 'return_policy'] : $store_rates[ 'return_policy' ];

$description = get_user_meta( $vendor_id, 'pv_shop_description', true );

?>

<div class="woocommerce-tabs">
	<ul class="tab-product nav nav-tabs">
		<?php if ($description != '') { ?>
			<li class="active"><a data-toggle="tab" href="#tabs-list-about"><?php esc_html_e( 'About Shop', 'yozi' ); ?></a></li>
		<?php } ?>
		<?php if (!$vendor_rating_page) { ?>
			<li><a data-toggle="tab" href="#tabs-list-review"><?php esc_html_e( 'Review', 'yozi' ); ?></a></li>
		<?php } ?>
		

		<?php if ( !empty($shipping_policy) || !empty($return_policy) ) { ?>
			<li><a data-toggle="tab" href="#tabs-list-shipping"><?php esc_html_e( 'Shipping Policy', 'yozi' ); ?></a></li>
		<?php } ?>
	</ul>
	<div class="tab-content">
		<?php if ($description != '') { ?>
			<div class="tab-pane active in" id="tabs-list-about">
				<?php echo trim($description); ?>
			   	<?php do_action( 'wcv_after_vendor_store_description' ); ?>	
			</div>
		<?php } ?>
		<?php if (!$vendor_rating_page) { ?>
			<div class="tab-pane" id="tabs-list-review">
				<?php
					wc_get_template( 'tabs-review.php', array( 
						'vendor_id' => $vendor_id,
					), 'wc-vendors/store/' ); 
				?>
			</div>
		<?php } ?>

		
		<?php if ( !empty($shipping_policy) || !empty($return_policy) ) { // start of vendor policies  ?>
			<div class="tab-pane" id="tabs-list-shipping">

				<?php if ( $shipping_policy != '' ):  ?>
					<div class="shipping_policy">
						<h3><?php esc_html_e( 'Shipping Policy', 'yozi' ); ?></h3>
						<p><?php echo trim($shipping_policy); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( $return_policy != '' ):  ?>
					<div class="return_policy">
						<h3><?php esc_html_e( 'Return Policy', 'yozi' ); ?></h3>
						<p><?php echo trim($return_policy); ?></p>
					</div>
				<?php endif; ?>

			</div>
		<?php } // end of vendor policies ?>
	</div>
</div>