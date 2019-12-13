jQuery(document).ready(function($){
	$('.apus-topcart').on('click', '.remove', function(e){

		var clicked = $(this);
		console.log(clicked.attr('data-product-id'));
		e.preventDefault();
		e.stopPropagation();
		$.ajax({
			url: ajax.url,
			type: 'POST',
			data: {
				action: 'bulk_remove',
				product_id: clicked.attr('data-product-id'),
				product_key: clicked.attr('data-product-key'),
				is_cart: clicked.attr('data-is-cart')
			},
			beforeSend: function( xhr ) {
				$('.remove').css({
					'pointer-events': 'none'
				});
				$('.widget_shopping_cart_content').css({
					'opacity': '.7'
				});
			},
			success: function( data ) {
				let result = JSON.parse(data);
				$('.widget_shopping_cart_content').html(result.html);
				$('.mini-cart .count').text(result.count);
				$('.total-minicart').html(result.total);
				$('.remove').css({
					'pointer-events': ''
				});
				$('.widget_shopping_cart_content').css({
					'opacity': ''
				});
				if($('body.woocommerce-cart').length > 0){
					$('[name="update_cart"]').removeAttr('disabled');
					$('[name="update_cart"]').trigger('click'); 
				}else if($('body.woocommerce-checkout').length > 0){
					$(document.body).trigger('update_checkout');
				}
				//console.log(data);
				//window.location.replace(`${window.location.href.split('?')[0]}?removed-product-ids=${result.removed_product_ids}&quantities=${result.quantities}`);
			}
		});
		return false;
	});
});