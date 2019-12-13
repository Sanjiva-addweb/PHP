<?php
global $product;
$time_sale = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );

?>
<div class="special-product">
	<?php if ( $time_sale ) { ?>
		<div class="time">
		    <span><?php echo esc_html__( 'Start for you in: ', 'yozi' ); ?></span>
		    <div class="apus-countdown clearfix" data-time="timmer"
		        data-date="<?php echo date('m', $time_sale).'-'.date('d', $time_sale).'-'.date('Y', $time_sale).'-'. date('H', $time_sale) . '-' . date('i', $time_sale) . '-' .  date('s', $time_sale) ; ?>">
		    </div>
		</div>
	<?php } ?>
</div>