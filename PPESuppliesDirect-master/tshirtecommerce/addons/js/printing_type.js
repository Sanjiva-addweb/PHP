/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-06 / update: 2015-11-26
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */


function changePrintintType(oObject){

    print_type 		= oObject.id;
    var strTypeText = oObject.getAttribute('title');
	
    jQuery('.printing-type-modal').modal('hide');
	
    var span 		= document.getElementById('spanType');
    span.innerText 	= span.textContent = strTypeText;
	
    design.ajax.getPrice();

    // Remove active class
    jQuery('#printTypeModal .amodal').removeClass('active');

    jQuery(oObject).addClass('active');

    return;
}
jQuery(document).on('form.addtocart.design', function(event, datas){
    datas.print_type = print_type;
}); 

jQuery(document).on("change.product.design", function(event, product){
	if (typeof event.namespace == 'undefined' || event.namespace != 'design.product') return; // 2015-11-01
	
	if(  typeof product != 'undefined' && typeof product.allow_change_printing_type != 'undefined')
	{
		var el = jQuery("#dg-right .product-info #printing-type");
        el.css('display', 'block');
		
		if(typeof product.print_type == 'undefined')
			product.print_type = print_type;
		
		// setting modal
		var check = false;
		jQuery('#printTypeModal .box_printing').each(function()
		{
			var e = jQuery(this);
			
			var printing = e.attr('data-print');
			if(typeof product[printing] != 'undefined')
			{
				e.css('display', 'inline-block');
				if((typeof product['allow_'+product.print_type+'_printing'] == 'undefined' || 'allow_'+product.print_type+'_printing' == printing) && check == false)
				{
					var evt = e.children('.amodal');
					var txtPrinting = evt.attr('title');
					
					var span 		= document.getElementById('spanType');
					span.innerText 	= span.textContent = txtPrinting;
					
					design.ajax.getPrice();
					jQuery('#printTypeModal .amodal').removeClass('active');
					evt.addClass('active');
					product.print_type = evt.attr('id');
					check = true;
				}
			}else
			{
				e.css('display', 'none');
			}
		});
		
		// display modal
		jQuery('.printing-type-modal').modal('show');
	}
	else
	{
		var el = jQuery("#dg-right .product-info #printing-type");
		if(el.length == 1) el.css('display', 'none');
	}
    
});

jQuery(document).ready(function($){
	setTimeout(function(){
		if(typeof allow_change_printing_type != 'undefined' && allow_change_printing_type == 1)
		{
			jQuery('.printing-type-modal').modal('show');
		}
	}, 1000);
	jQuery(window).resize(function(){
		var h 	= jQuery(this).height();
		h 		= h - 150;
		jQuery('#printTypeModal .modal-body').css('max-height', h+'px');
	});
	$('button.close').on('click', function(){
        $('.modal-backdrop.fade.in').remove();
    });
});