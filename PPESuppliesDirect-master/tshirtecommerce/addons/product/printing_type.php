<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-10-11 / update 2015-11-01
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

	$addons 	= $GLOBALS['addons'];
	$product 	= $GLOBALS['product'];
	$print_type = 'screen';
	
	$print_types = array(
		'screen'=> $addons->__('addon_print_type_screen'),
		'DTG'=> $addons->__('addon_print_type_dtg'),
		'sublimation'=> $addons->__('addon_print_type_sublimation'),
		'embroidery'=> $addons->__('addon_print_type_embroidery'),
	);

	$print_types = $addons->printing($print_types);
	
	if(count($print_types))
	{
		foreach($print_types as $key=>$val)
		{
			if($product->print_type == $key)
				$print_type = $val;
		}
	}
?>

<div id="printing-type" class="form-group product-fields" 
	<?php 
		if(isset($product->allow_change_printing_type) && $product->allow_change_printing_type != ''){ echo "style='display: block;'";}
		else { echo "style='display: none;'";}
	?>>
	<label for="fields"><?php echo $addons->__('addon_print_type_title') ?></label><br/>
	<span id='spanType'><?php echo $print_type; ?></span>
	
	<a href="javascript:void(0)" title="Edit" onclick="jQuery('.printing-type-modal').modal('show');">
		<i class="fa fa-pencil"></i>
	</a>
</div>
