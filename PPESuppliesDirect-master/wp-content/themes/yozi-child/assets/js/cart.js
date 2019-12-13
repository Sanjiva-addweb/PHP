jQuery(document).ready(function($){
	$('div.woocommerce').on('click', '.remove', function(e){
		var clicked = $(this);
		if(clicked.parent().hasClass('bulk-remove')){
			e.preventDefault();
			e.stopPropagation();
			$.ajax({
				url: ajax.url,
				type: 'POST',
				data: {
					action: 'bulk_remove',
					product_id: clicked.parent().attr('data-product-id')
				},
				beforeSend: function( xhr ) {
					
				},
				success: function( data ) {
					$('[name="update_cart"]').prop('disabled', false);
					$('[name="update_cart"]').trigger('click'); 
				}
			});
			return false;
		}
	});
});