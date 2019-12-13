<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
$i = 0;
if ( ! empty( $tabs ) ) :
	$layout = yozi_get_config('product_content_layout', 'tabs');
	if ( $layout == 'accordion' ) {
	?>
		<div class="panel-group" id="woocommerce-accordion" role="tablist" aria-multiselectable="true">
			<?php $i = 0; ?>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="panel panel-default">
				    <div id="heading-<?php echo esc_attr( $key ); ?>" class="panel-heading" role="tab">
				      	<h4 class="panel-title">
					        <a class="<?php echo ($i !== 0 ? 'collapsed' : ''); ?>" role="button" data-toggle="collapse" data-parent="#woocommerce-accordion" href="#collapse-<?php echo esc_attr( $key ); ?>" aria-expanded="true" aria-controls="collapse-<?php echo esc_attr( $key ); ?>">
					          	<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
					        </a>
				      	</h4>
				    </div>
				    <div id="collapse-<?php echo esc_attr( $key ); ?>" class="panel-collapse collapse <?php echo ($i == 0 ? 'in' : ''); ?>" role="tabpanel" aria-labelledby="heading-<?php echo esc_attr( $key ); ?>">
				      	<div class="panel-body">
				      		<?php call_user_func( $tab['callback'], $key, $tab ); ?>
				      	</div>
				    </div>
			  	</div>
		  	<?php $i++; endforeach; ?>
		</div>
	<?php } else { ?>
		<div class="col-lg-12 col-md-8 col-xs-12 woocommerce-tabs tabs-v1">
			<div class="tap-top">
				<ul class="tabs-list nav nav-tabs">
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<li<?php echo ($i == 0 ? ' class="active"' : '');?> id="tabs-<?php echo esc_attr( $key ); ?>">
							<a data-toggle="tab" href="#tabs-list-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
						</li>
					<?php $i++; endforeach; ?>
				</ul>
			</div>
			<div class="tab-content">
			<?php $i = 0; ?>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="tab-pane<?php echo ($i == 0 ? ' active in' : ''); ?>" id="tabs-list-<?php echo esc_attr( $key ); ?>">
					<?php call_user_func( $tab['callback'], $key, $tab ); ?>
				</div>
			<?php $i++; endforeach; ?>
			</div>
		</div>
	<?php } ?>
<?php endif; ?>