<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="sagepay"><h2><?php esc_html_e( 'SagePay', 'woocommerce_sagepayform' ); ?>
				<?php echo wc_help_tip( __( 'This section shows any information about SagePay.', 'woocommerce_sagepayform' ) ); ?>
			</h2></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $debug_data as $data ) :
			$mark = ( isset( $data['success'] ) && true == $data['success'] ) ? 'yes' : 'error';
			$mark_icon = 'yes' === $mark ? 'yes' : 'no-alt';
		?>
		<tr>
			<td data-export-label="<?php echo esc_attr( $data['name'] ) ?>"><?php echo esc_html( $data['name'] ) ?>:</td>
			<td class="help"><?php echo wc_help_tip( $data['tip'] ); ?></td>
			<td>
				<mark class="<?php echo esc_html( $mark ) ?>">
					<span class="dashicons dashicons-<?php echo esc_html( $mark_icon )?>"></span> <?php echo wp_kses_data( $data['note'] ); ?>
				</mark>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>