<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-01
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
	$addons 	= $GLOBALS['addons'];
	$product 	= $GLOBALS['product'];
	$setting 	= $GLOBALS['settings'];
	$print_type = 'screen';
	
	if(isset($product)){$print_type = $product->print_type;}
	
	$print_types = array(
		'screen'=> $addons->__('addon_print_type_screen'),
		'DTG'=> $addons->__('addon_print_type_dtg'),
		'sublimation'=> $addons->__('addon_print_type_sublimation'),
		'embroidery'=> $addons->__('addon_print_type_embroidery'),
	);

	$print_types = $addons->printing($print_types);
?>




<div class="modal fade printing-type-modal" tabindex="-1" role="dialog" id="printTypeModal" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Choose the customisation technique
					<!--<?php echo $addons->__('addon_print_type_modal_title'); ?>-->
				</h4>
				<p>Please note this is for preview purposes only and does not go straight to production.<br /> You will receive the
				final version for your approve once the artwork has been evaluated and set up by our graphic designers.<br />
				Also we may suggest a different customization technique if it better suits your needs.</p>
			</div>
			<div class="modal-body row" style="overflow: auto; max-height: 400px;">
				<?php 
					if(count($print_types))
					{
						foreach($print_types as $key=>$val)
						{
							$attr = 'allow_'.$key.'_printing';
							if($key == 'DTG' && isset($product->allow_dtg_printing))
								$attr = 'allow_dtg_printing';
							$attrs = 'printing_type_'.$key;
							if(!isset($product->$attr))
								echo '<div class="col-md-6 col-xs-12 col-zs-12 box_printing" data-print="'.$attr.'" style="display:none;">';
							else
								echo '<div class="col-md-6 col-xs-12 col-zs-12 box_printing" data-print="'.$attr.'" style="display: inline-block; float: left;">';
							
							if(isset($product->print_type) && $product->print_type == $key)
								echo '<a class="amodal col-xs-11 active" href="javascript:void(0)" id="'.$key.'" onclick="changePrintintType(this)" title="'.$val.'">';
							else
								echo '<a class="amodal col-xs-11" href="javascript:void(0)" id="'.$key.'" onclick="changePrintintType(this)" title="'.$val.'">';
							
							if(!isset($setting->$attrs) || $setting->$attrs == '') 
								echo $addons->__('default_printing_type_'.strtolower($key));
							else 
								echo $setting->$attrs;							
								
							echo '</a></div>';
						}
					}
				?>
					
		
				<!-- art work req-->
				<div class="col-md-4 col-xs-6 col-zs-12 artwork-block hidden">
					<div class="artwork-block-body col-xs-11">
						<div class="printing-title">ARTWORK REQUIREMENTS</div>
						<p style="font-size:9px; margin-bottom: 0px;">
							<span>Minimum resolution:</span> 300 dpi<br />
							<span>Vector formats:</span> .ai .pdf .eps<br />
							<span>Bitmap format:</span> .png .psd .jpg .tiff<br />
							<span>Minimum line width:</span> 0.9 mm<br />
							<span>Minimum width for print:</span> 30 cm<br />
							<span>Minimum width for embroidery:</span> 25 cm
						</p>
						<p style="margin-top: 0px;">	
							There may be certain limitations
							depending on the customisation
							type you select. Our expert team
							will work with you to adjust your
							design so it can be produced at
							the best quality
						</p>
					</div>
				</div>


			</div>

		 <p class="modal-bottom-text">Not sure which option to choose? Please give us a call at <span style="color:#D7182A; font-weight: bold;">0808 109 6099</span> and we’ll happily guide you.</p>

		</div>
	</div>
</div>



<style>
/*modal styles*/

.modal-content{
	padding-left: 20px;
	padding-right: 20px;
}

.modal-body{
	padding-top: 5px;
	max-width: 770px;
	margin: auto;
}

.modal-header{
    border-bottom: none;
    font-family: 'Lato', sans-serif;   
    color:#333333;     
    padding-bottom: 0px;
    max-width: 740px;
	margin-left: auto;
	margin-right: auto;
	display: block !important;
	background: #ffffff !important;
}

.modal-title{
    font-size:22px;
    margin-bottom: 5px;
    font-weight: bold;
    font-family: 'Lato',sans-serif;
}

.modal-dialog{
    width:880px;
    
}

.modal-bottom-text{
    padding-bottom:5px;
    margin-top: -15px;
    display: block;
    font-family: 'Lato',sans-serif;
    color:#333333;
    text-align: center;
}

.box_printing{
	float:left !important;
	margin-bottom: 17px;
}

.box_printing img{
	margin-top: 10px;
}


.amodal{
	border-radius: 10px;
	background: #ffffff;
	display: block;
	min-height: 180px;
	border: solid 2px #e2e2e2;
}

.amodal p{
    color:#333333;
    text-decoration: none;
    font-size:11px;
    font-family: 'Lato',sans-serif;
	display: block;
}

.amodal:hover{
	text-decoration: none;
	border: solid 2px #ED1C24;
}

.printing-title{
	font-size: 17px;
	font-family: 'Lato',sans-serif;
	line-height: 1.1;
	margin-bottom: 4px;
}

.amodal .printing-title{
	text-align: center;
	text-transform: uppercase;
	color:#333;
}

.amodal:hover .printing-title{
	color:#C41630;
}

.artwork-block{
	float:left !important;
	margin-bottom: 20px;
}

.artwork-block-body{
	background: #fff;
	border: solid 2px #eeeeee;
	border-radius: 10px;
	display: block;
	padding-top: 8px;
}

.artwork-block p{
	font-family: 'Lato',sans-serif;
	font-size: 11px;
}

.artwork-block p span{
	color:#D7182A;
}

</style>



<?php 
	if(isset($_GET['cart_id'])){ 
		echo "<style> .printing-type-modal, .modal-backdrop {display:none !important;}</style>"; 
	}
?>