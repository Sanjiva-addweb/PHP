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
?>
<div class="col-right view-modal-box">
	<div class="row tool-back">
		<div class="col-md-12">
			<div class="pull-right tool-back-done">
				<a href="#" class="btn btn-sm btn-none" onclick="e_display('.col-right', 'hide')">
					<i class="glyph-icon flaticon-checked"></i>
				</a>
			</div>
			<center>
				<strong><?php echo lang('designer_right_product_options'); ?></strong>
			</center>
			<div class="pull-left tool-back-close">
				<a href="#" class="btn btn-sm btn-none" onclick="e_display('.col-right', 'hide')">
					<i class="glyph-icon text-danger flaticon-14 flaticon-remove"></i>
				</a>
			</div>
		</div>
	</div>
	<div id="dg-right">
		<!-- product -->
		<div class="align-center" id="right-options">
			<div class="dg-box">
				<!-- product info -->
				<div class="product-options" id="product-details">
				<?php if ($product != false) { ?>
					<div class="col-md-12">
						<?php if (isset($product->design) && $product->design != false) { ?>
						<div class="product-info">
							<div id="e-change-product-color" class="form-group product-fields" <?php if(count($product->design->color_hex) < 2) echo 'style="display: none;"'; ?>>
								<a id="e-button-product-color-mobile" class="btn btn-default btn-xs" <?php if(count($product->design->color_hex) < 7) echo 'style="display: none;"'; ?> role="button" data-toggle="collapse" aria-expanded="false" href="#product-list-colors" aria-expanded="false" aria-controls="product-list-colors"><span class="pull-left"><?php echo lang('designer_right_choose_product_color'); ?></span><span class="pull-left ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span></a>

								<label id="e-label-product-color-mobile" for="fields" <?php if(count($product->design->color_hex) > 6) echo 'style="display: none;"'; ?>><?php echo lang('designer_right_choose_product_color'); ?></label>

								<div class="product-list-colors-mobile collapse<?php if(count($product->design->color_hex) < 7) echo ' in'; ?>" id="product-list-colors">
									

									<?php
									$i = 0;
									foreach( $colors as $color ){
										?>
										<span class="bg-colors dg-tooltip <?php if ($i==0) echo 'active'; ?>" onclick="design.products.changeColor(this, <?php echo $i; ?>)" data-color="<?php echo strtoupper( str_replace( '#', '', get_term_meta( $color->term_id, 'color', true )[0] ) ); ?>" data-placement="top" data-original-title="<?php echo $color->name; ?>" style="width:25px; background-color:<?php echo get_term_meta( $color->term_id, 'color', true )[0]; ?>">
											<a href="javascript:void(0);"></a>
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
				
				<hr />
				<div class="text-left col-md-12 print-options" <?php if(cssShow($settings, 'show_color_used') != '' && cssShow($settings, 'show_screen_size') != '') echo 'style="display: none;"'; ?>>	
					<label <?php echo cssShow($settings, 'show_color_used'); ?>><?php echo lang('designer_right_color_used'); ?></label>
					<div class="color-used" <?php echo cssShow($settings, 'show_color_used'); ?>></div>
					<hr />	
					<label <?php echo cssShow($settings, 'show_screen_size'); ?>><?php echo lang('designer_right_screen_size'); ?></label>
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
					<button <?php echo cssShow($settings, 'show_add_to_cart', 1); ?> type="button" class="btn btn-warning btn-addcart" onclick="design.ajax.addJs(this)"><i class="glyph-icon flaticon-shopping-cart-3"></i> <span><?php echo lang('designer_right_buy_now'); ?></span></button>								
				</div>
			</div>
		</div>
	</div>
</div>

<!-- layers -->
<div class="view-modal-box view-modal-layers">
	<div class="row tool-back">
		<div class="col-md-12">
			<div class="pull-right tool-back-done">
				<a href="#" class="btn btn-sm btn-none" onclick="e_display('.view-modal-layers', 'hide')">
					<i class="glyph-icon flaticon-checked"></i>
				</a>
			</div>
			<center>
				<strong><?php echo lang('designer_menu_login_layers'); ?></strong>
			</center>
			<div class="pull-left tool-back-close">
				<a href="#" class="btn btn-sm btn-none" onclick="e_display('.view-modal-layers', 'hide')">
					<i class="glyph-icon text-danger flaticon-14 flaticon-remove"></i>
				</a>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div id="dg-layers">
			<ul id="layers"></ul>
		</div>
	</div>
</div>