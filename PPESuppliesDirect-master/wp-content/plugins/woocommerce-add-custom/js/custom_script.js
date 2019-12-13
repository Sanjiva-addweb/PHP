jQuery(function($){
	
	wcapf_params = {};
	wcapf_params.overlay_bg_color = '#fff';
	wcapf_params.shop_loop_container = '.wcapf-before-products';
	wcapf_params.not_found_container = '.wcapf-before-products';
	wcapf_params.scroll_to_top = 1;
	wcapf_params.scroll_to_top_offset = 100;
	
	wcapfFixPagination = function() {
		var url = window.location.href,
			params = wcapfGetUrlVars(url);

		if (current_page = parseInt(url.replace(/.+\/page\/([0-9]+)+/, "$1"))) {
			if (current_page > 1) {
				url = url.replace(/page\/([0-9]+)/, 'page/1');
			}
		}
		else if(typeof params['paged'] != 'undefined') {
			current_page = parseInt(params['paged']);
			if (current_page > 1) {
				url = url.replace('paged=' + current_page, 'paged=1');
			}
		}

		return url;
	}
	
		// update query string for categories, meta etc..
	wcapfUpdateQueryStringParameter = function(key, value, push_history, url) {
		if (typeof push_history === 'undefined') {
			push_history = true;
		}

		if (typeof url === 'undefined') {
			url = wcapfFixPagination();
		}

		var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i"),
			separator = url.indexOf('?') !== -1 ? "&" : "?",
			url_with_query;
		
		if (url.match(re)) {
			url_with_query = url.replace(re, '$1' + key + "=" + value + '$2');
		}
		else {
			url_with_query = url + separator + key + "=" + value;
		}

		if (push_history === true) {
			return history.pushState({}, '', url_with_query);
		} else {
			return url_with_query;
		}
	}
	
	// URL Parser
	wcapfGetUrlVars = function(url) {
	    var vars = {}, hash;

	    if (typeof url == 'undefined') {
	    	url = window.location.href;
	    } else {
	    	url = url;
	    }

	    var hashes = url.slice(url.indexOf('?') + 1).split('&');
	    for (var i = 0; i < hashes.length; i++) {
	        hash = hashes[i].split('=');
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}
	
	
	// add filter if not exists else remove filter
	wcapfSingleFilter = function(filter_key, filter_val) {
		var params = wcapfGetUrlVars(),
			query;

		if (typeof params[filter_key] !== 'undefined' && params[filter_key] == filter_val) {
			query = wcapfRemoveQueryStringParameter(filter_key);
		} else {
			query = wcapfUpdateQueryStringParameter(filter_key, filter_val, false);
		}

		// update url
		history.pushState({}, '', query);
		
		// filter products
		wcapfFilterProducts();
	}
	
		// remove parameter from url
	wcapfRemoveQueryStringParameter = function(filter_key, url) {
		if (typeof url === 'undefined') {
			url = wcapfFixPagination();
		}

		var params = wcapfGetUrlVars(url),
			count_params = Object.keys(params).length,
			start_position = url.indexOf('?'),
			param_position = url.indexOf(filter_key),
			clean_url,
			clean_query;

		if (count_params > 1) {
			if ((param_position - start_position) > 1) {
				clean_url = url.replace('&' + filter_key + '=' + params[filter_key], '');
			} else {
				clean_url = url.replace(filter_key + '=' + params[filter_key] + '&', '');
			}

			var params = clean_url.split('?');
			clean_query = '?' + params[1];
		} else {
			clean_query = url.replace('?' + filter_key + '=' + params[filter_key], '');
		}

		return clean_query;
	}
	
	
		// scripts to run before updating shop loop
	wcapfBeforeUpdate = function() {
		var overlay_color;
		
		if (wcapf_params.overlay_bg_color.length) {
			overlay_color = wcapf_params.overlay_bg_color;
		} else {
			overlay_color = '#fff';
		}

		var markup = '<div class="wcapf-before-update" style="background-color: ' + overlay_color + '"></div>',
			holder,
			top_scroll_offset = 0;

		if ($(wcapf_params.shop_loop_container.length)) {
			holder = wcapf_params.shop_loop_container;
		} else if ($(wcapf_params.not_found_container).length) {
			holder = wcapf_params.not_found_container;
		}

		if (holder.length) {
			// show loading image
			$(markup).prependTo(holder);
	
			// scroll to top
			if (typeof wcapf_params.scroll_to_top !== 'undefined' && wcapf_params.scroll_to_top == true) {
				var scroll_to_top_offset,
					top_scroll_offset;

				if (typeof wcapf_params.scroll_to_top_offset !== 'undefined' && wcapf_params.scroll_to_top_offset.length) {
					scroll_to_top_offset = parseInt(wcapf_params.scroll_to_top_offset);
				} else {
					scroll_to_top_offset = 100;
				}

				top_scroll_offset = $(holder).offset().top - scroll_to_top_offset;
				
				if (top_scroll_offset < 0) {
					top_scroll_offset = 0;
				}

				$('html, body').animate({scrollTop: top_scroll_offset}, 'slow');		
			}
		}

	}
		// load filtered products
	wcapfFilterProducts = function() { 
		// run before update function: show a loading image and scroll to top
		wcapfBeforeUpdate();

		$.get(window.location.href, function(data) { 
			var $data = jQuery(data),
				shop_loop = $data.find(wcapf_params.shop_loop_container),
				not_found = $data.find(wcapf_params.not_found_container);

			// replace old shop loop with new one
			if (wcapf_params.shop_loop_container == wcapf_params.not_found_container) {
				$(wcapf_params.shop_loop_container).html(shop_loop.html());
				$('.product-block .block-inner .image').each(function(){
					var getsrc = $(this).find('img').attr('data-src');
					$(this).find('img').attr('src',getsrc);
				})
				
			} else {
				if ($(wcapf_params.not_found_container).length) {
					if (shop_loop.length) {
						$(wcapf_params.not_found_container).html(shop_loop.html());
					} else if (not_found.length) {
						$(wcapf_params.not_found_container).html(not_found.html());
					}
				} else if ($(wcapf_params.shop_loop_container).length) {
					if (shop_loop.length) {
						$(wcapf_params.shop_loop_container).html(shop_loop.html());
					} else if (not_found.length) {
						$(wcapf_params.shop_loop_container).html(not_found.html());
					}
				}
			}
			
		});
	}

		
	if($('.apus-shop-products-wrapper').length){

			var vars = {}, hash;
				var url = window.location.href;
				
			var hashes = url.slice(url.indexOf('?') + 1).split('&');
			for (var i = 0; i < hashes.length; i++) {
				hash = hashes[i].split('=');
				vars[hash[0]] = hash[1];
			}
			
			
			 // $("#slider-range").slider({
				// range: true,
				// orientation: "horizontal",
				// min: 1,
				// max: 1000,
				// values: [1, 1000],
				// step: 3,

				// slide: function (event, ui) {
				  // if (ui.values[0] == ui.values[1]) {
					  // return false;
				  // }
				  // //alert(ui.values[0]);
				  // $("#min_price").val(ui.values[0]);	
				  // $("#max_price").val(ui.values[1]);
				  
					// var filter_key_min = 'min_price';
					// var filter_key_max = 'max_price';
					// var filter_val_min = ui.values[0];
					// var filter_val_max = ui.values[1];
					// //if(filter_key)
					// //wcapfSingleFilter(filter_key, filter_val);
					// query = wcapfUpdateQueryStringParameter(filter_key_min, filter_val_min, false);
					// query = wcapfUpdateQueryStringParameter(filter_key_max, filter_val_max, true, query);
					// wcapfFilterProducts();
				// }
			  // });
			
			console.log(vars);
			if ('filter_color' in vars)
			{	
			var colorvars = vars.filter_color;
			colorvars = colorvars.split(',');
			$(colorvars).each(function(i,v){	
				 $("input.filter_color[value='" + v + "']").attr('checked', true);
			});
			}
			if ('filter_size' in vars)
			{
			var sizevars = vars.filter_size;
			sizevars = sizevars.split(',');
			$(sizevars).each(function(i,v){	
				 $("input.filter_size[value='" + v + "']").attr('checked', true);
			});
			}
			if ('filter_gender' in vars)
			{
			var sizevars = vars.filter_gender;
			sizevars = sizevars.split(',');
			$(sizevars).each(function(i,v){	
				 $("input.filter_gender[value='" + v + "']").attr('checked', true);
			});
			}
			if ('min_price' in vars)
			{
			 // $("#slider-range").slider({
				// values: [vars.min_price, vars.max_price]
			  // });
			}
			
			$('input.filter_color').each(function(){
				
				if($(this).val() == vars.filter_color)
				{
					$(this).attr('checked',true);
				}
				//alert(vars.filter_color);
			});
			
		
		$('input.filter_color').change(function(){
			var filter_val  = new Array();
			$('input.filter_color').each(function(){
				
				if($(this).is(':checked'))
				{
					filter_val.push($(this).val());
				}
			});
			
		if( filter_val !== undefined || filter_val.length != 0)
		{
			var filter_key = 'filter_colour';
			
			wcapfSingleFilter(filter_key, filter_val);
		}
		
		});
		//filter by size
		$('input.filter_size').change(function(){
			
			var filter_val  = new Array();
			$('input.filter_size').each(function(){
				
				if($(this).is(':checked'))
				{
					filter_val.push($(this).val());
				}
				
			});
			if( filter_val !== undefined || filter_val.length != 0)
			{
				var filter_key = 'filter_size';
				
				wcapfSingleFilter(filter_key, filter_val);
			}
		});
		//filter by brand
		$('input.filter_brand').change(function(){
			
			var filter_val  = new Array();
			$('input.filter_brand').each(function(){
				
				if($(this).is(':checked'))
				{
					filter_val.push($(this).val());
				}
				
			});
			if( filter_val !== undefined || filter_val.length != 0)
			{
				var filter_key = 'product_brand';
				
				wcapfSingleFilter(filter_key, filter_val);
			}
		});
			
		$('input.filter_gender').change(function(){
			
			var filter_val  = new Array();
			$('input.filter_gender').each(function(){
				
				if($(this).is(':checked'))
				{
					filter_val.push($(this).val());
				}
				
			});
			if( filter_val !== undefined || filter_val.length != 0)
			{
				var filter_key = 'filter_gender';
				
				wcapfSingleFilter(filter_key, filter_val);
			}
		});
			 
			
	}
	if($('.logo-slider').length)
	{
	$('.logo-slider').slick({
						autoplay:true,
						autoplaySpeed:1500,
						arrows:false
	});
	}


	//Quick view qty fix

	$('body').on('click', '.qty-increase', function(){	

		var a =  $(this).parent('td').find('.varient_quantity').val();
		
		if(a == '' || a >= 500)
		{
			a = 0;
		}
		var inc = 1;
		
			var newv = parseInt(a) + parseInt(inc);
			$(this).parent('td').find('.varient_quantity').val(newv);
		
	});


	$('body').on('click', '.qty_decrese', function(){	

		var a = $(this).parent('td').find('.varient_quantity').val();
		
		if(a == '' || a <= '0')
		{
			a =1;
		}
		var inc = 1;
		
			var newv = parseInt(a) - parseInt(inc);
			$(this).parent('td').find('.varient_quantity').val(newv);
		
	});


	//Fix this for single product

	
});


//Add custom extra price for large layer


