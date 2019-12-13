<?php
/**
 * The Template for displaying the product shipping details
 *
 * Override this template by copying it to yourtheme/wc-vendors/front/shipping
 *
 * @package    WCVendors_Pro
 * @version    1.5.2
 */
?>


<h2 class="title"><?php esc_html_e( 'Shipping Details', 'yozi' ); ?></h2>

<p>
<strong><?php esc_html_e( 'Shipping from ', 'yozi' ); ?>: </strong> <?php echo trim($countries[ strtoupper( $store_country ) ]); ?>
</p>

<?php if ( ! empty( $shipping_flat_rates ) ) :  ?>

	<?php if ( ! empty( $shipping_flat_rates[ 'national' ] ) || ! empty( $shipping_flat_rates[ 'international' ] ) || ( array_key_exists( 'national_free', $shipping_flat_rates ) && $shipping_flat_rates[ 'national_free' ] == 'yes' ) || ( array_key_exists( 'international_free', $shipping_flat_rates ) && $shipping_flat_rates[ 'international_free' ] == 'yes' ) ) :  ?>

	<table>

	<?php if ( $shipping_flat_rates[ 'national_disable' ] !== 'yes' ): ?> 
		<?php if ( strlen( $shipping_flat_rates[ 'national' ] ) >= 0 || strlen( $shipping_flat_rates[ 'national_free' ] ) >= 0 ) : ?>
			<?php $free = ( array_key_exists( 'national_free', $shipping_flat_rates ) && $shipping_flat_rates[ 'national_free' ] == 'yes' ) ? true : false; ?> 
			<?php $price = $free ? esc_html__( 'Free', 'yozi' ) : wc_price( $shipping_flat_rates[ 'national' ] . $product->get_price_suffix() ); ?> 
			<tr>
				<td width="60%"><strong><?php esc_html_e( 'Within ', 'yozi' ); ?><?php echo trim($countries[ strtoupper( $store_country ) ]); ?></strong></td>
				<td width="40%"><?php echo trim($price); ?></td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( $shipping_flat_rates[ 'international_disable' ] !== 'yes' ):  ?>
		<?php if ( strlen( $shipping_flat_rates[ 'international' ] ) > 0 || strlen( $shipping_flat_rates[ 'international_free' ] ) > 0 ) : ?>
			<?php $free = ( array_key_exists( 'international_free', $shipping_flat_rates ) && $shipping_flat_rates[ 'international_free' ] == 'yes' ) ? true : false; ?> 
			<?php $price = $free ? esc_html__( 'Free', 'yozi' ) : wc_price( $shipping_flat_rates[ 'international' ] . $product->get_price_suffix() ); ?> 
			<tr>
				<td width="60%"><strong><?php esc_html_e( 'Outside ', 'yozi' ); ?> <?php echo trim($countries[ strtoupper( $store_country ) ]); ?></strong></td>
				<td width="40%"><?php echo trim($price); ?></td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>
	</table>

	<?php else: ?>

	<h5><?php esc_html_e( 'No shipping rates are available for this product.', 'yozi' ); ?></h5>

	<?php endif; ?>

<?php else: ?>

	<?php if ( ! empty( $shipping_table_rates ) ):  ?>

		<table>

		<thead>
			<tr>
				<th><?php esc_html_e( 'Country', 'yozi' ); ?></th>
				<th><?php esc_html_e( 'State', 'yozi' ); ?></th>
				<th><?php esc_html_e( 'Cost', 'yozi' ); ?></th>
			</tr>
		</thead>
		<?php foreach( $shipping_table_rates as $rate ):  ?>

		<tr>
			<td width="40%"><?php echo ( $rate[ 'country' ] != '' ) ? $countries[ strtoupper( $rate['country'] ) ] : esc_html__( 'Any', 'yozi' ); ?></td>
			<td width="40%"><?php echo ( $rate[ 'state' ] != '' ) ? $rate['state'] : esc_html__( 'Any', 'yozi' ); ?></td>
			<td width="20%"><?php echo wc_price( $rate['fee'] . $product->get_price_suffix() );  ?></td>
		</tr>
		<?php endforeach; ?>

		</table>	

	<?php else: ?>

		<?php if ( ! empty( $shipping_flat_rates ) ):  ?>

			<table>
			<tr>
				<td width="60%"><strong><?php esc_html_e( 'Within ', 'yozi' ); ?><?php echo trim($countries[ strtoupper( $store_country ) ]); ?></strong></td>
				<td width="40%"><?php echo wc_price( $shipping_flat_rates[ 'national' ] . $product->get_price_suffix() );  ?></td>
			</tr>
			<tr>
				<td width="60%"><strong><?php esc_html_e( 'Outside ', 'yozi' ); ?><?php echo trim($countries[ strtoupper( $store_country ) ]); ?></strong></td>
				<td width="40%"><?php echo wc_price( $shipping_flat_rates[ 'international' ] . $product->get_price_suffix() );  ?></td>
			</tr>
			</table>

		<?php else: ?>

		<h5><?php esc_html_e( 'No shipping rates are available for this product.', 'yozi' ); ?></h5>

		<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>

<div class="row">
	<?php if ( $shipping_policy != '' ):  ?>
	<div class="col-xs-12 col-md-6">
		<h3 class="title-small"><?php esc_html_e( 'Shipping Policy', 'yozi' ); ?></h3>
		<p>
		<?php echo trim($shipping_policy); ?>
		</p>
	</div>
	<?php endif; ?>


	<?php if ( $return_policy != '' ):  ?>
	<div class="col-xs-12 col-md-6">
		<h3 class="title-small"><?php esc_html_e( 'Return Policy', 'yozi' ); ?></h3>
		<p>
		<?php echo trim($return_policy); ?>
		</p>
	</div>
	<?php endif; ?>
</div>