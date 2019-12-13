jQuery(document).ready(function($){
	$('.input-text.qty.simple').val(1);
	$('.input-text.qty.simple').on('input', function(e){
		//alert('test');
		var currentQty = $(this).val();
		if(currentQty <= 9){
			if($('.product_price1').val() != ''){
			let perItem = $('.product_price1').val();
			$('.intamt').text((currentQty * perItem).toFixed(2));
			$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
		}else{
			let perItem = $('.regular_price').val();
			$('.intamt').text((currentQty * perItem).toFixed(2));
			$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
		}
		}else if(currentQty >= 9 && currentQty <= 19){
			if($('.product_price2').val() != ''){
				let perItem = $('.product_price2').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price1').val() != ''){
				let perItem = $('.product_price1').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else{
				let perItem = $('.regular_price').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}
		}else if(currentQty >= 20 && currentQty <= 49){
			if($('.product_price3').val() != ''){
				let perItem = $('.product_price3').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price2').val() != ''){
				let perItem = $('.product_price2').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price1').val() != ''){
				let perItem = $('.product_price1').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else{
				let perItem = $('.regular_price').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}
		}else if(currentQty >= 50 && currentQty <= 99){
			if($('.product_price4').val() != ''){
				let perItem = $('.product_price4').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price3').val() != ''){
				let perItem = $('.product_price3').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price2').val() != ''){
				let perItem = $('.product_price2').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price1').val() != ''){
				let perItem = $('.product_price1').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else{
				let perItem = $('.regular_price').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}
		}else if(currentQty >= 100 && currentQty <= 249){
			if($('.product_price5').val() != ''){
				let perItem = $('.product_price5').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price4').val() != ''){
				let perItem = $('.product_price4').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price3').val() != ''){
				let perItem = $('.product_price3').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price2').val() != ''){
				let perItem = $('.product_price2').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price1').val() != ''){
				let perItem = $('.product_price1').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else{
				let perItem = $('.regular_price').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}
		}else if(currentQty >= 250){
			if($('.product_price6').val() != ''){
				let perItem = $('.product_price6').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price5').val() != ''){
				let perItem = $('.product_price5').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price4').val() != ''){
				let perItem = $('.product_price4').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price3').val() != ''){
				let perItem = $('.product_price3').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price2').val() != ''){
				let perItem = $('.product_price2').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else if($('.product_price1').val() != ''){
				let perItem = $('.product_price1').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}else{
				let perItem = $('.regular_price').val();
				$('.intamt').text((currentQty * perItem).toFixed(2));
				$('.items_add1').html(`<span class="woocommerce-Price-currencySymbol">£</span>${perItem} per item</span>`);
			}
		}
	});
});



