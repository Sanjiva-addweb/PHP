<?php

$product = $GLOBALS['product'];
$settings = $GLOBALS['settings'];
$addons = $GLOBALS['addons'];

if( isset( $_GET['parent'] ) ){
	$wc_product = wc_get_product( $_GET['parent'] );
	$attributes = $wc_product->get_attributes();
	if( $attributes ){
		$colors = [];
		$sizes = [];
		foreach( $attributes as $key => $attribute ){
			if( $key === 'pa_color' ){
				foreach( $attribute->get_terms() as $attribute_id){
					$colors[] = $attribute_id;
				}
			}elseif ( $key === 'pa_size' ){
                foreach( $attribute->get_terms() as $attribute_term){
                    $sizes[] = $attribute_term;
                }
            }
		}
	}
}

if (isset($settings->show_detail_price) && $settings->show_detail_price == 0)
{
	echo '<style>div.product-price-info{display:none;}</style>';
}
?>
<div class="col-right">
	<span class="arrow-mobile active" data="right">
		<div class="product-color-active list-colors">
			<span class="bg-colors product-color-active-value"></span> 
			<span><?php echo lang('product_color'); ?></span>
		</div>
		<button type="button" class="btn btn-xs btn-primary">
			<i class="fa fa-shopping-cart"></i> <span><?php echo lang('designer_right_buy_now'); ?></span>
		</button>
		<i class="fa fa-times"></i>
	</span>
	<div id="dg-right">
		<!-- product -->
		<div class="align-center" id="right-options">
			<div class="dg-box">
				<div class="accordion">
					<h3><?php echo lang('designer_right_product_options'); ?></h3>
					<div class="product-options contentHolder" id="product-details">
					<?php if ($product != false) { ?>
						<div class="content-y">									
							<?php if (isset($product->design) && $product->design != false) { ?>
							<div class="product-info">
								<div id="e-change-product-color" class="form-group">
									
									<label id="e-label-product-color" for="fields"><?php echo lang('designer_right_choose_product_color'); ?></label>
									<div id="product-list-colors">
									<?php
									$i = 0;
									foreach( $colors as $color ){
										?>
										<span class="bg-colors dg-tooltip <?php if ($i==0) echo 'active'; ?>" onclick="design.products.changeColor(this, <?php echo $i; ?>)" data-color="<?php echo strtoupper( str_replace( '#', '', get_term_meta( $color->term_id, 'color', true )[0] ) ); ?>" data-placement="top" data-original-title="<?php echo $color->name; ?>">
											<a href="javascript:void(0);" style="width:23px; background-color:<?php echo get_term_meta( $color->term_id, 'color', true )[0]; ?>"></a>
										</span>
										<?php
										$i++;
									}
									?>
									</div>
								</div>
								<?php $addons->view('product'); ?>
							</div>
							<?php } ?>
							<form method="POST" id="tool_cart" name="tool_cart" action="">							
							<div class="product-info" id="product-attributes">
								<?php //if (isset($product->attribute)) { ?>
									<?php //echo $product->attribute; ?>
								<div class="form-group product-fields product-quantity">
									<label>Quantity</label>
									<input type="text" class="form-control input-sm" value="1" data-count="1" name="quantity" id="quantity">
								</div>
                                <div class="form-group product-fields">
                                    <label for="fields">Size</label>
                                    <div class="dg-poduct-fields " data-type="textlist">
                                        <style>.product-quantity{display:none}</style>
                                        <ul class="p-color-sizes list-number col-md-12">
                                            <?php
                                            $j = 0;
                                            foreach ($sizes as $size) {
                                            ?>
                                            <li>
                                                <label data-id="<?php echo $size->name ?>"><?php echo $size->name ?></label>
                                                <input type="text" class="form-control input-sm size-number ui-spinner-input" name="attribute[0][<?php echo $j ?>]" aria-valuemin="0" aria-valuenow="1" autocomplete="off" role="spinbutton">
                                            </li>
                                            <?php $j++;} ?>
                                        </ul>
                                    </div>
                                </div>
								<?php //} ?>
								<?php $addons->view('attribute'); ?>
							</div>
							</form>	
						</div>
					<?php } ?>
					</div>
					
					<h3 <?php echo cssShow($settings, 'show_color_used'); ?>><?php echo lang('designer_right_color_used'); ?></h3>
					<div class="color-used" <?php echo cssShow($settings, 'show_color_used'); ?>></div>
					
					<h3 <?php echo cssShow($settings, 'show_screen_size'); ?>><?php echo lang('designer_right_screen_size'); ?></h3>
					<div class="screen-size" <?php echo cssShow($settings, 'show_screen_size'); ?>></div>					
				</div>
				<div class="product-prices">
					<div id="product-price" <?php echo cssShow($settings, 'show_total_price'); ?>>
						<span class="product-price-title"><?php echo lang('designer_right_total'); ?></span>
						<div class="product-price-list">
							<span id="product-price-old">
								<?php echo $settings->currency_symbol; ?><span class="price-old-number"></span>
							</span>
							<span id="product-price-sale">
								<?php echo $settings->currency_symbol; ?><span class="price-sale-number"></span>
							</span>
						</div>
						<span class="price-restart" title="<?php echo lang('designer_get_price'); ?>" onclick="design.ajax.getPrice()"><i class="glyphicons restart"></i></span>
					</div>
					<?php $addons->view('cart'); ?>
					<button <?php echo cssShow($settings, 'show_add_to_cart', 1); ?> type="button" class="btn btn-warning btn-addcart" onclick="design.ajax.addJs(this)"><i class="glyphicons shopping_cart"></i><?php echo lang('designer_right_buy_now'); ?></button>								
				</div>
			</div>
		</div>
	</div>
</div>
