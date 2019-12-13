jQuery(document).ready(function($){
	$('.widget-woof').on('click', '.woof_color_title', function(){
		$(this).siblings('p.woof_tooltip').find('input').click();
	});

	$('body').on('input', '.input-text.qty.simple', function(e){
		//alert('test');
		e.preventDefault();
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