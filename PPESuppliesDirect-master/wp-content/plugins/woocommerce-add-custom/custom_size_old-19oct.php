<?php

add_action( 'woocommerce_single_product_summary', 'custom_multiple_add_variation_form', 40 );
function custom_multiple_add_variation_form() {
	
 define('VARIANTPRODUCT', '1');
 global $product; 
 //echo "<pre>"; print_r($product); echo "</pre>";
 $product_id = $product->get_id();
 $product_title = $product->get_title();
 $product_sku = $product->get_sku();
 // echo "<pre>"; print_r($product); echo "</pre>";
 
if($product->is_type( 'variable' )) {
$uri =  explode('/',$_SERVER['REQUEST_URI']);
		
$size_attr = array(
	'xs' => 'XS',
    'small' => 'S',
    'medium' => 'M',
	'large' => 'L',
	's' => 'S',
    'm' => 'M',
	'l' => 'L',
	'xl' => 'XL',
	'xxl' => '2XL',
	'3xl' => '3XL',
	'4xl' => '4XL',
	'5xl' => '5XL',
	'6xl' => '6XL',
	'7xl' => '7XL',
	'14' => '14',
	'12' => '12',
	'10' => '10',
	'8' => '8',
	'6' => '6',
	'4' => '4'
	
);
$sizeterms = get_terms( array(
    'taxonomy' => 'pa_size',
    'hide_empty' => false,
) );
//echo "<pre>"; print_r($sizeterms);
foreach($sizeterms as $sizeterm) 
{
	$size_attr[$sizeterm->slug] = $sizeterm->name;
}
$size_vars = array();
$color_vars = array();

foreach($product->get_available_variations() as $variation ){
				// Variation ID
				foreach($size_attr as $attrk=>$attrv)
				{
					$sizeattr = $variation['attributes']['attribute_pa_size'];
					$colorattr = $variation['attributes']['attribute_pa_color'];
					if($attrk == $sizeattr)
					{
						$size_vars[] = $sizeattr;
						$color_vars[] = $colorattr;
						$variations[$colorattr][$sizeattr] = $variation;
					}

				}
			}
$size_vars = array_unique($size_vars);
$color_vars = array_unique($color_vars);
	$plugin_url = plugin_dir_url( __FILE__ );
	
	echo '<link rel="stylesheet" href="'.$plugin_url . 'style.css" type="text/css"/>';
	?>
	 
<?php $brandterm = get_post_meta( $product->get_id(), '_bulkdeal' ); 
	//echo "<pre>"; print_r($dealb);
?>
	<div class="bulkdeal_product"><h5>Bulk Deal: </h5><input type="text" value="<?php print_r($brandterm[0]); readonly?>" name="bulkdeal_count" class="bulkdeal_count" readonly /></div>
	<div class="main_varient">
	<div> <h3 style="margin-top:-9px;">
		<?php
				echo $product_title; ?>
				</h3></div>
		<div class="left_right_val">
		
		<div class="left_table_val">
		
			<div class="pr_img_code"><div class="pr_code"><span>Product Code: <?php echo $product_sku; ?></span>
<!--<p style="margin:5px 0 23px;">Be the first to review this product</p>-->
				</div>
				
				<div class="logo_image">
					<?php // $key_name = get_post_custom_values($key = 'apus_product_features');  echo $key_name[0]; ?>
					<?php //$brandlogo = get_post_meta($product_id,'_brandlogo',true); 
					
					$brandterm = get_the_terms( $product->get_id(), 'product_brand' );
						
					//echo "<pre>"; print_r($brandterm);                                                                                                  
							//$_brandlogourl = get_post_meta($product_id,'_brandlogourl',true); 
							$_brandlogourl = site_url().'/shop?product_brand='.$brandterm[0]->slug;
							$brandlogo = get_option('brand_taxonomy_image'.$brandterm[0]->term_id);
					?>
						<a href="<?php echo $_brandlogourl; ?>" target="_blank"><img width="auto" src="<?php echo $brandlogo; ?>" alt=""/></a>
				</div>  	
				
			</div>
		<?php global $wpdb;
$getpriceinfo = $wpdb->get_results('select * from wp_pricing_table where product_code="'.$product_sku.'"');
//echo "<pre>"; print_r($getpriceinfo); echo "</pre>"; 
$priceinfo = $getpriceinfo[0]->priceinfo;
$priceinfo = json_decode($priceinfo,true);
//echo "<pre>"; print_r($priceinfo); echo "</pre>"; 
?>
	<div class="table_tab500">
		<div class="Table">
		
			<ul class="qntrow">
				<li>Quantity</li>
				<li>1-9</li>
				<li>10-19</li>
				<li>20-49</li>
				<li>50-99</li>
				<li>100-249</li>
				<li>250-499</li>
				
			</ul>
			<ul class="pricerow">
				<li>Price</li>
				<li><?php echo $priceinfo['1-9']; ?></li>
				<li><?php echo $priceinfo['10-19']; ?></li>
				<li><?php echo $priceinfo['20-49']; ?></li>
				<li><?php echo $priceinfo['50-99']; ?></li>
				<li><?php echo $priceinfo['100-249']; ?></li>
				<li><?php echo $priceinfo['250 to 499']; ?></li>
				
			</ul>
			<?php 
				$singlep = str_replace('£','',$priceinfo['1-9']); 
				
				$price10 = str_replace('£','',$priceinfo['10-19']);
				$price20 = str_replace('£','',$priceinfo['20-49']);
				$price50 = str_replace('£','',$priceinfo['50-99']);
				$price100 = str_replace('£','',$priceinfo['100-249']);
				$price250 = str_replace('£','',$priceinfo['250 to 499']);
				
			?>
			<ul class="saverow">
				<li>Save</li>
				<li></li>
				<li><?php echo round(((($singlep-$price10)/$singlep)*100),2); ?>%</li>
				<li><?php echo round(((($singlep-$price20)/$singlep)*100),2); ?>%</li>
				<li><?php echo round(((($singlep-$price50)/$singlep)*100),2); ?>%</li>
				<li><?php echo round(((($singlep-$price100)/$singlep)*100),2); ?>%</li>
				<li><?php echo round(((($singlep-$price250)/$singlep)*100),2); ?>%</li>
			</ul>
		</div>
		<div class="tab500" style="">Ordering more than 500 items?. give us a call for Quote.</div>

		</div>
	</div>                                                                                                    
	</div>                                                                                                                         
	
	<div class="iconic-engraving-field">                                                                                                                                                                
		<div class="color_container">                                                                                                                                                                                                   
		
		<div class="colors_size">                                                                                                                                                                                                                                                                                                                                       
		<div style="width:16%; font-weight:500;" class="cc_size">Colour</div><div style="width:50%; font-weight:500; margin-bottom: 15px;" class="cc_size">Size & Quantity</div>
		<ul class="variantslist">                   
		
		<li class="select_colorr"><span style="width:215px;">&nbsp;</span>
		<ul class="var_input">
		<?php foreach($size_attr as $sizeattr_k=>$sizeattr_v){
			  if(in_array($sizeattr_k,$size_vars))
			  {
				echo '<li class="select_size_quality">'.$sizeattr_v.'</li>';
			}			
		} ?>
		</ul>
		</li>
		</ul>
		
		</div>
		<div class="colors_size">
		 <ul class="variantslist">
			<?php foreach($color_vars as $colorvar){
				
				$colorcircle = '';
				$colorterm = get_term_by('slug', $colorvar, 'pa_color');
				
				$colorimagemeta = get_term_meta($colorterm->term_id,'colorimage');
				
				if(empty($colorimagemeta) || $colorimagemeta[0] == '')
				{
					$colormeta = get_term_meta($colorterm->term_id,'color');
					
					foreach($colormeta as $code){
						if(is_array($code))
						{
							if(count($code) >2 )
							{
								$colorcircle .= '<div class="circle" style="background:linear-gradient(to right,'.$code['0'].', '.$code['1'].', '.$code['2'].');"></div>';
							}
							elseif(count($code) == 2){
								$colorcircle .= '<div class="circle" style="background:linear-gradient(to right,'.$code['0'].' 50% , '.$code['1'].' 50%);"></div>';
								
							}
							/* foreach($code  as $colorcode){
								echo "<pre>"; print_r($colorcode);
							} */
						}
						else
						{
							$colorcircle .= '<div class="circle" style="background:'.$code.';"></div>';
						}
					}
					
				}
				else
				{
					$img = end(explode('/',$colorimagemeta[0]));
					$colorimg = explode('&',$img);
					$colorcircle .= '<div class="circle" style="background-image: url(\'/wp-content/uploads/colorswatches/'.$colorimg[0].'\');"></div>';
				}
				
				echo '<li class="select_colorr"><div class="colordiv">'.$colorcircle.'<span style="padding-top: 5px;">'.str_replace('_',' ',$colorterm->name).'</span></div>'; ?>
					<ul class="var_input">
					<?php foreach($size_attr as $sizeattr_k=>$sizeattr_v){
						
						if(in_array($sizeattr_k,$size_vars))
						{
							if( isset($variations[$colorvar][$sizeattr_k])){
							
									$variation_id = $variations[$colorvar][$sizeattr_k]['variation_id'];
									$displayprice = $variations[$colorvar][$sizeattr_k]['display_price'];
									
					?>
							<li class="color_all"  data-cost="<?php echo $displayprice; ?>" data-variant-id = "<?php echo $variation_id; ?>" ><input class="varient_quantity" value="0" type="text"/></li>
					<?php 
							
							}
							else
							{
								echo '<li class="color_all"><input class="blank_qty" readonly type="text" value="X"/></li>';
							}
							
						}
					}
					?>
					</ul>
				
			<?php echo '</li>'; }  ?>
		 </ul>
		</div>	
		</div>
		<div class="btn-containerdiv" style="width:38%; float:right;">
			<div class="containerdiv1">
				<span class="items_add">0 item(s)</span>
				
			</div>
			<div class="containerdiv2">
				<div class="containerdiv2inner1">
			<span class="bottom woocommerce-Price-amount amount pr_cart_value"><span class="woocommerce-Price-currencySymbol">£</span><span class="intamt">0.00</span></span><br/>
				<span class="items_add1"><span class="woocommerce-Price-currencySymbol">£</span>0 per item</span>
				</div>
				<button type="submit" class="single_add_to_cart_button canotadd alt btn btn-theme varient_button btn-block btn-outline wc-variation-selection-needed enabled">ADD TO BAG <img src="/wp-content/uploads/2018/07/shopping-bag.png" style="width: 21px;" /></button>
			</div>
		</div>
		<?php 
			
			$characters = $priceinfo;
			$first_price = $characters['1-9'];
			$first_price2 = $characters['10-19'];
			$first_price3 = $characters['20-49'];
			$first_price4 = $characters['50-99'];
			$first_price5 = $characters['100-249'];
			$first_price6 = $characters['250 to 499'];
			$first_prices = str_replace('£', '' , $first_price);
			$first_prices2 = str_replace('£', '' , $first_price2);
			$first_prices3 = str_replace('£', '' , $first_price3);
			$first_prices4 = str_replace('£', '' , $first_price4);
			$first_prices5 = str_replace('£', '' , $first_price5);
			$first_prices6 = str_replace('£', '' , $first_price6);
			//echo "<pre>"; echo $characters .'<br/>' . $first_prices2 .'<br/>'. $first_prices3 .'<br/>'. $first_prices4 .'<br/>'. $first_prices5 .'<br/>'. $first_prices6 .'<br/>'; echo "</pre>"; die;

		?>
		<input type="hidden" value="<?php echo $product->get_id() ?>" class="product_id"/>
		<input type="hidden" value="<?php echo $first_prices2; ?>" class="product_price2"/>
		<input type="hidden" value="<?php echo $first_prices3; ?>" class="product_price3"/>
		<input type="hidden" value="<?php echo $first_prices4; ?>" class="product_price4"/>
		<input type="hidden" value="<?php echo $first_prices5; ?>" class="product_price5"/>
		<input type="hidden" value="<?php echo $first_prices6; ?>" class="product_price6"/>
		
		
	</div>
	</div>
	
		<script type="text/javascript" >
		
		function isEmpty(obj) {
			for(var key in obj) {
				if(obj.hasOwnProperty(key))
					return false;
			}
			return true;
		}
		
		
			jQuery(document).ready(function($) {
				var li_box = $('.var_input li.select_size_quality').length;
				var li_width = 100;
				
				if(li_box>6){
					var li_extra = parseInt(li_box) - parseInt(6);
					var twidth = parseInt(li_extra) * parseInt(li_width);
					twidth = parseInt(700) + parseInt(twidth); 
					$('.colors_size').css('width',twidth+'px');
				}

				//alert($('header').height()); 
				$('.color_container').scroll(function(){
					
					var leftpos = $('.color_container').scrollLeft();
					$('.colordiv').css('left',leftpos+'px');
					$('.cc_size').css({'position':'relative','left':''+leftpos+'px'});
				})
				$('.content-vertical').css('display','block');
				
				$('#tax_change').change(function(){
					
				
					var totalc =0;
					var totalitems = 0;
					$("input.varient_quantity").each(function() {
						
						if(isNaN($(this).val()))
						{
							return false;
						}
						
						var q = parseInt($(this).val());
						var c = $(this).parents('li.color_all').attr('data-cost');
						if($('#tax_change').val() == 'invat')
						{
							
							var addtax = parseFloat(c) * parseFloat(20 / 100);
							
							c = parseFloat(c) + parseFloat(addtax);
						}
						if(q != 0 && q != '')
						{
							
							var cal = parseInt(q) * parseFloat(c);
						   // console.log(cal);
							var currenttotalc = parseFloat(totalc) + parseFloat(cal);
							
							totalc = currenttotalc;
						   // console.log(totalc);
						   
						   var current_totalitems = parseInt(q) + parseInt(totalitems);
						   
						   totalitems = current_totalitems;
						   
						}
						
					    
					});
						totalc = parseFloat(totalc).toFixed(1) + parseFloat(oldtotalc);
						$('.items_add').html(totalitems+' item(s)');
						$('.intamt').html(totalc);
						//alert(totalitems);
						alert("jhjkhjk");
						var peritem = 0;
						if(totalitems > 0){
						 peritem = parseFloat(totalc).toFixed(1) / parseInt(totalitems);
						 $('.single_add_to_cart_button').removeClass('canotadd');
						 $('.single_add_to_cart_button').css('color','white');
						}
						else
						{
							if(!$('.canotadd').length)
							{
								$('.single_add_to_cart_button').addClass('canotadd');
							}
							$('.single_add_to_cart_button').css('color','#d0d0d0');
						}
						$('.items_add1').html('£'+peritem.toFixed(2)  +' per item');
						
					
				})
				//alert($('#vertical-menu').height());
				var  oldtotalc = $('.intamt').html();
				$("input.varient_quantity").keyup(function() {
					
					var totalc =0;
					var totalitems = 0;
					$("input.varient_quantity").each(function() {
						//alert($(this).val());
						
						
						var q = parseInt($(this).val());
						
						if(isNaN(q) || q == '')
						{
							q = 0;
						}
						
						var c = $(this).parents('li.color_all').attr('data-cost');
						
						if($('#tax_change').val() == 'invat')
						{
							
							var addtax = parseFloat(c) * parseFloat(20 / 100);
							
							c = parseFloat(c) + parseFloat(addtax);
						}
						
						if(q >= 10 && q < 20)
						{
							c = $('.product_price2').val();
						}
						else if(q >= 20 && q < 50)
						{
							c = $('.product_price3').val();
						}
						else if(q >= 50 && q < 100)
						{
							c = $('.product_price4').val();
						} 
						else if(q >= 100 && q < 250)
						{
							c = $('.product_price5').val();
						}
						else if(q >= 250)
						{
							c = $('.product_price6').val();
						}
						if(q != 0 && q != '')
						{
							
							var cal = parseInt(q) * parseFloat(c);
						   // console.log(cal);
							var currenttotalc = parseFloat(totalc) + parseFloat(cal);
							
							totalc = currenttotalc;
						   // console.log(totalc);
						   
						   var current_totalitems = parseInt(q) + parseInt(totalitems);
						   
						   totalitems = current_totalitems;
						}
						
						
					});
					if(bulkdeal_count!=''){
						totalc = parseFloat(totalc).toFixed(1) + parseFloat(oldtotalc);
						$('.items_add').html(totalitems+' item(s)');
						$('.intamt').html(totalc)/peritem;
						//alert(totalitems);
						var bulkdeal_count = $('.bulkdeal_count').val();
						//alert (bulkdeal_count);
						if(totalitems>0) {
						var peritem = 0;
						
						var totalitems = 0;
							if(totalitems == bulkdeal_count){
								//alert(bulkdeal_count);
								 peritem = parseFloat(totalc).toFixed(1) / parseInt(totalitems);
								 //alert(peritem);
								 $('.single_add_to_cart_button').removeClass('canotadd');
								 $('.single_add_to_cart_button').css('color','white');
							}
						}
					    }
						if(bulkdeal_count==''){
						totalc = parseFloat(totalc).toFixed(1) + parseFloat(oldtotalc);
						$('.items_add').html(totalitems+' item(s)');
						$('.intamt').html(totalc);
							//alert("dsfsdfsd");
							$('.bulkdeal_product').css('display','none');
							$('.bulkdeal_product').hide();
							if(totalitems > 0){
								
								peritem = parseFloat(totalc).toFixed(1) / parseInt(totalitems);
								//alert(peritem);
								$('.single_add_to_cart_button').removeClass('canotadd');
								$('.single_add_to_cart_button').css('color','white');
							}
							else
							{
								if(!$('.canotadd').length)
								{
									$('.single_add_to_cart_button').addClass('canotadd');
								}
								$('.single_add_to_cart_button').css('color','#d0d0d0');
							}
						}
						
						$('.items_add1').html('£'+peritem.toFixed(2)  +' per item');
						
						
				});
				
				
				
				var productid = $('.product_id').val();
				$('.single_add_to_cart_button').click(function(){
					var varquantity = {};
					$("input.varient_quantity").each(function() {
						var varid = $(this).parents('li').attr('data-variant-id');
						if($(this).val() != '' && $(this).val() != 0){ 
							varquantity[varid] = $(this).val();
						}
					});
					console.log(varquantity);
					if(isEmpty(varquantity))
					{
						alert('Please add at least one quantity');
						return false;
						
					}
					
						
					$(this).attr('disabled',true);
					$('.single_add_to_cart_button').append('<div class="loader"></div>');
				$.ajax({
							url : '/wp-admin/admin-ajax.php',
							type : 'post',
							data : {
								'action' : 'addviewcart',
								'varquantity' : varquantity,
								'productid' : productid
							},
							success : function( response ) {
								$('.single_add_to_cart_button').attr('disabled',false);
								$('.loader').remove();
								$('<p class="addmmsg">Product Succesfully added</p>').insertAfter('.single_add_to_cart_button');
								//console.log(response.fragments);
								//alert(response.fragments['div.widget_shopping_cart_content']);
								
								$('.dropdown-menu.dropdown-menu-right').html(response.fragments['div.widget_shopping_cart_content']);
								$('.total-minicart').addClass('old');
								$(response.fragments['.cart .total-minicart']).insertAfter('.total-minicart');
								$('.total-minicart.old').remove();
								
								$('span.count').addClass('old');
								$(response.fragments['.cart .count']).insertAfter('span.count');
								$('span.count.old').remove();
								$('.single_add_to_cart_button').find('.loader').remove();
								setTimeout(function(){
									$('.addmmsg').remove();
								},3000);
								
							},
							error:function(err)
							{
								$('<p class="addmmsg">Products not added Please Try again.</p>').insertAfter('.single_add_to_cart_button');
								$('.single_add_to_cart_button').attr('disabled',false);
								$('.loader').remove();
								setTimeout(function(){
									$('.addmmsg').remove();
								},2000);
							}
						});
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				
				});
			});
			</script>	

	<?php
}
}
function addviewcart() {
		
		$productid = $_POST['productid'];
		foreach($_POST['varquantity'] as $variationid=>$quantity)
		{
			$res = WC()->cart->add_to_cart( $productid , $quantity , $variationid );
		}
		if($res)
		{
			do_action( 'woocommerce_ajax_added_to_cart', $productid );
			
		}
		if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
			wc_add_to_cart_message( $product_id );
		}
		 WC_AJAX::get_refreshed_fragments();
		
		die;
	}
	
add_action( 'wp_ajax_nopriv_addviewcart', 'addviewcart' );
add_action( 'wp_ajax_addviewcart', 'addviewcart' );



function my_scripts_method() {

}

add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

function addproductwithvar(){
	//echo "<pre>"; print_r($_POST); die;
	
if($_POST['data']['reinsert'] == 'yes' && $_POST['data']['sku'] != ''){
	
	global $wpdb;
 
  $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $_POST['data']['sku'] ) );
  
  $featured_image_id = get_post_meta($product_id,'_thumbnail_id',true);
 
   if(!empty($featured_image_id))
   {
     //wp_delete_post($featured_image_id);
   }
  
  $image_galleries_id = get_post_meta($product_id,'_product_image_gallery',true);
 
  if(!empty($image_galleries_id))
  {
    $image_galleries_array = explode(',',$image_galleries_id);

    foreach($image_galleries_array as $single_image_id)
    {
        wp_delete_post($single_image_id);
    }
  }
  $return = wp_delete_post($product_id);

}	
	

if(get_product_by_sku($_POST['data']['sku']) == null)
{

	$_POST['data']['categories'] = array($_POST['data']['category']);	

	$_POST['data']['available_attributes'] = array('color','size');

	foreach($_POST['data']['colors'] as $colorname=>$colorcode)
	{
		foreach($_POST['data']['sizegrid'] as $size)
		{
			$variations['attributes']['color'] = $colorname;
			$variations['attributes']['size'] = $size;
			$variations['price'] = 11;
			$_POST['data']['variations'][] = $variations;
		}
	}
	
	foreach($_POST['data']['colors'] as $colorname=>$colorcode)
	{
		$term = get_term_by( 'name', $colorname, 'pa_color' ); 
		
		$termid = $term->term_id;
		
		if($termid== ''){
			$insertedterm = wp_insert_term(             
			  $colorname, // the term 
			  'pa_color', // the taxonomy
			  array(
				'description'=> '',
				'parent'=> ''  // get numeric term id
			  )
			);
			//echo "<pre>"; print_r($insertedterm);
		}
		
		$term = get_term_by( 'name', $colorname, 'pa_color' ); 
		
		$termid = $term->term_id;
		$key = get_term_meta( $termid,'color',true ); 
		//echo $key; die;
		if($key == '' && $_POST['data']['colortypes'][$colorname] != 'image')                                  
		{

				update_term_meta($termid,'color',$colorcode);                                                                                        
			
		}
		
		if($_POST['data']['colortypes'][$colorname] == 'image')                                                                                               
			{
				update_term_meta($termid,'color','');                                                                                           
				update_term_meta($termid,'colorimage',$colorcode);                                                                                            
			}                                                   
		
		
	
	}    


	if(!empty($_POST['data']))
	{
		insert_product ($_POST['data']);
		echo "Inserted"; die;
	}
	else
	{
		echo "product not found"; die;
	}
}


echo "failed"; die;
}
add_action('wp_ajax_addproductwithvar', 'addproductwithvar' ); // executed when logged in
add_action('wp_ajax_nopriv_addproductwithvar', 'addproductwithvar' ); // executed when logged out
function get_product_by_sku( $sku ) {
  global $wpdb;

  $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
  
  if ( $product_id ) 

  {	 
	
     echo $product_id; die; 
  }

  return null;
}
function insert_product ($product_data)  
{
    $post = array( // Set up the basic post data to insert for our product

        'post_author'  => 3,
        'post_content' => $product_data['description'],
		'post_excerpt'   => $product_data['description'],
        'post_status'  => 'publish',
        'post_title'   => $product_data['name'],
        'post_parent'  => '',
        'post_type'    => 'product'
    );

    $post_id = wp_insert_post($post); // Insert the post returning the new post id

    if (!$post_id) // If there is no post id something has gone wrong so don't proceed
    {
        return false;
    }

    update_post_meta($post_id, '_sku', $product_data['sku']); // Set its SKU
    update_post_meta( $post_id,'_visibility','visible'); // Set the product to visible, if not it won't show on the front end
	update_post_meta( $post_id,'_brandlogo',$product_data['brandlogo']);
	//update_post_meta( $post_id,'_bulkdeal',$product_data['bulkdeal']);
	
    wp_set_object_terms($post_id, $product_data['categories'], 'product_cat'); // Set up its categories
    wp_set_object_terms($post_id, 'variable', 'product_type'); // Set it to a variable product type
	
	add_brandof_product($post_id);
	
    insert_product_attributes($post_id, $product_data['available_attributes'], $product_data['variations']); 
	
    insert_product_variations($post_id, $product_data['variations']); // Insert variations passing the new post id & variations  


			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			$thumb_url = $product_data['mainimage'];
			
			$thumbe = end(explode('/',$thumb_url));
			$firstpart = str_replace($thumbe,'',$thumb_url);
			$ext = explode('.',$thumbe)[1]; 
			$thumb_url =  $firstpart.'1024.'.$ext;

			// Download file to temp location
			$tmp = download_url( $thumb_url );

			// Set variables for storage
			// fix file name for query strings
			preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $thumb_url, $matches);
			$file_array['name'] = basename($matches[0]);
			$file_array['tmp_name'] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
			$logtxt .= "Error: download_url error - $tmp\n";
			}else{
			$logtxt .= "download_url: $tmp\n";
			}

			//use media_handle_sideload to upload img:
			$thumbid = media_handle_sideload( $file_array, $post_id, 'gallery desc' );
			// If error storing permanently, unlink
			if ( is_wp_error($thumbid) ) {
			@unlink($file_array['tmp_name']);
			//return $thumbid;
			$logtxt .= "Error: media_handle_sideload error - $thumbid\n";
			}else{
			$logtxt .= "ThumbID: $thumbid\n";
			}

			set_post_thumbnail($post_id, $thumbid);
	
	
}

function add_brandof_product($pid){
	
	
	$logo = get_post_meta($pid,'_brandlogo',true);
		
		if($logo != '')
		{
			
			$exp = end(explode('/', $logo));
			
			$logoslug = explode('.',$exp)[0];
			$logoslug = str_replace('%26','-',$logoslug);
			$logoslug = str_replace('%2','-',$logoslug);
			$term = get_term_by('slug', $logoslug, 'product_brand');
			if($term == '' || empty($term))
			{
				
				$logoname = str_replace('-',' ',$logoslug);
				$logoname = str_replace('_',' ',$logoname);
				$logoname = ucfirst($logoname);
				$inserted = wp_insert_term(
				  $logoname, // the term 
				  'product_brand', // the taxonomy
				  array(
					'description'=> '',
					'slug' => $logoslug,
					'parent'=> '',  // get numeric term id
				  )
				); 

				add_option( 'brand_taxonomy_image'.$inserted['term_id'], $logo, '', 'no' );
			}
			//echo "<pre>"; print_r($term); echo "</pre>"; die;
			$term = get_term_by('slug', $logoslug, 'product_brand');
			wp_set_post_terms($pid, $term->term_id ,'product_brand');
		}	
			// add parent categories too 
		$term_ids = [];
		$terms = get_the_terms($pid, "product_cat");
		if ( count( $terms ) > 0 ) {
		foreach( $terms as $item ) {
			$term_ids[] = $item->term_id;
			
			$pcats = get_ancestors( $item->term_id, 'product_cat' ); 
			foreach($pcats as $scat)
			{
				$term_ids[] = $scat;
			
			}
		}
		}
		wp_set_object_terms( $pid, $term_ids, 'product_cat' );
		
		
		//add gender terms 
		
		$productname = get_the_title( $pid );
		if (strpos($productname, 'Kids') !== false) {
			
			$thedata = get_post_meta($pid,'_product_attributes',true);
			wp_set_object_terms( $pid, 'kids', 'pa_gender', true );
					
			$newdata = Array('pa_gender'=>Array(
				   'name'=>'pa_gender',
				   'value'=>'',
				   'is_visible' => '1',
				   'is_taxonomy' => '1'
				 ));
				 
			$thedata = 	 array_merge($thedata,$newdata);

			update_post_meta( $pid,'_product_attributes',$thedata); 
		}
		
		if (strpos($productname, 'Unisex') !== false) {
			
			$thedata = get_post_meta($pid,'_product_attributes',true);
			wp_set_object_terms( $pid, 'unisex', 'pa_gender', true );
					
			$newdata = Array('pa_gender'=>Array(
				   'name'=>'pa_gender',
				   'value'=>'',
				   'is_visible' => '1',
				   'is_taxonomy' => '1'
				 ));
				 
			$thedata = 	 array_merge($thedata,$newdata);

			update_post_meta( $pid,'_product_attributes',$thedata); 
		}
		
		if (strpos($productname, 'lady') !== false || strpos($productname, 'ladies') !== false) {
			
			$thedata = get_post_meta($pid,'_product_attributes',true);
			wp_set_object_terms( $pid, 'female', 'pa_gender', true );
					
			$newdata = Array('pa_gender'=>Array(
				   'name'=>'pa_gender',
				   'value'=>'',
				   'is_visible' => '1',
				   'is_taxonomy' => '1'
				 ));
				 
			$thedata = 	 array_merge($thedata,$newdata);

			update_post_meta( $pid,'_product_attributes',$thedata); 
		}
		
		else{
			
			$thedata = get_post_meta($pid,'_product_attributes',true);
			wp_set_object_terms( $pid, 'male', 'pa_gender', true );
					
			$newdata = Array('pa_gender'=>Array(
				   'name'=>'pa_gender',
				   'value'=>'',
				   'is_visible' => '1',
				   'is_taxonomy' => '1'
				 ));
				 
			$thedata = 	 array_merge($thedata,$newdata);

			update_post_meta( $pid,'_product_attributes',$thedata);
			
		}
				
	
}

function insert_product_attributes ($post_id, $available_attributes, $variations)  
{
    foreach ($available_attributes as $attribute) // Go through each attribute
    {   
        $values = array(); // Set up an array to store the current attributes values.

        foreach ($variations as $variation) // Loop each variation in the  
        {
            $attribute_keys = array_keys($variation['attributes']); // Get the keys for the current variations attributes

            foreach ($attribute_keys as $key) // Loop through each key
            {
                if ($key === $attribute) // If this attributes key is the top level attribute add the value to the $values array
                {
                    $values[] = $variation['attributes'][$key];
                }
            }
        }

        // Essentially we want to end up with something like this for each attribute:
        // $values would contain: array('small', 'medium', 'medium', 'large');

        $values = array_unique($values); // Filter out duplicate values

        // Store the values to the attribute on the new post, for example without variables:
        // wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');
		
        wp_set_object_terms($post_id, $values, 'pa_' . $attribute);  }
		

    $product_attributes_data = array(); // Setup array to hold our product attributes data

    foreach ($available_attributes as $attribute) // Loop round each attribute
    {
        $product_attributes_data['pa_'.$attribute] = array( // Set this attributes array to a key to using the prefix 'pa'

            'name'         => 'pa_'.$attribute,
            'value'        => '',
            'is_visible'   => '1',
            'is_variation' => '1',
            'is_taxonomy'  => '1'

        );
    }

    update_post_meta($post_id, '_product_attributes', $product_attributes_data); // Attach the above array to the new posts meta data key '_product_attributes'
}
function insert_product_variations ($post_id, $variations)  
{
    foreach ($variations as $index => $variation)
    {
        $variation_post = array( // Setup the post data for the variation

            'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,
            'post_name'   => 'product-'.$post_id.'-variation-'.$index,
            'post_status' => 'publish',
            'post_parent' => $post_id,
            'post_type'   => 'product_variation',
            'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index
        );

        $variation_post_id = wp_insert_post($variation_post); // Insert the variation

        foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
        {   
            $attribute_term = get_term_by('name', $value, 'pa_'.$attribute); // We need to insert the slug not the name into the variation post meta

            update_post_meta($variation_post_id, 'attribute_pa_'.$attribute, $attribute_term->slug);
          // Again without variables: update_post_meta(25, 'attribute_pa_size', 'small')
        }

      //  update_post_meta($variation_post_id, '_price', $variation['price']);
        update_post_meta($variation_post_id, '_regular_price', $variation['price']);
		
		
		update_post_meta($variation_post_id, 'stock_qty', 3);
		
    }
}
function wpb_custom_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Single Product Sidebar icons', 'yozi' ),
        'id' => 'single-product-sidebaricons',
        'description' => __( 'Single product sidebar', 'yozi' ),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<span style="display:none;">',
        'after_title' => '</span>'
    ) );
 
}
add_action( 'widgets_init', 'wpb_custom_widgets_init' );
function attach_image ($fileurl, $filealt, $post_id,$sku)  
{
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');
    $filename = basename($fileurl); // Get the filename including extension from the $fileurl e.g. myimage.jpg
$ext =  pathinfo($filename, PATHINFO_EXTENSION);

    $source = '/home/ppesupplies/public_html'.$fileurl; // This is going to be where the image is located, depending on the fileurl you pass in this may not be needed
   if(!is_dir(WP_CONTENT_DIR. '/uploads/productgallery/'.$sku))
   {
	   //echo $source;
	  // echo WP_CONTENT_DIR. '/uploads/productgallery/'.$sku;
		wp_mkdir_p(WP_CONTENT_DIR. '/uploads/productgallery/'.$sku);
	
   }
	  
	 $filename = str_replace('%2','',$filename);
	 $filename = str_replace(' ','',$filename);
	 $filename = str_replace('%20','',$filename);
	 $destination = WP_CONTENT_DIR. '/uploads/productgallery/'. $sku .'/' . $filename; // Specify where we wish to upload the file, generally in the wp uploads directory
     copy($source, $destination); // Copy the file
	 //rename(WP_CONTENT_DIR. '/uploads/productgallery/'. $sku .'/' . $filename,WP_CONTENT_DIR. '/uploads/productgallery/'. $sku .'/' .$sku.'_front_'.time().'.'.$ext);
    $filetype = wp_check_filetype($destination); // Get the mime type of the file

    $attachment = array( // Set up our images post data
        'guid'           => get_option('siteurl') . '/wp-content/uploads/productgallery/'. $sku .'/' . $filename, 
        'post_mime_type' => $filetype['type'],
        'post_title'     => $filename,
        'post_author'    => 1,
        'post_content'   => ''
    );

    $attach_id = wp_insert_attachment( $attachment, $destination, $post_id ); // Attach/upload image to the specified post id, think of this as adding a new post.
if(is_int($attach_id))
{
    $attach_data = wp_generate_attachment_metadata( $attach_id, $destination ); // Generate the necessary attachment data, filesize, height, width etc.

    wp_update_attachment_metadata( $attach_id, $attach_data ); // Add the above meta data data to our new image post

    update_post_meta($attach_id, '_wp_attachment_image_alt', $filealt); // Add the alt text to our new image post

	return $attach_id; // Return the images id to use in the below functions
}
else{
	return null;
}
 
}
function add_product_gallery_images()
{
	global $wpdb;
  $sku = $_POST['sku'];
  $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
  
  if($product_id)
  {
	$image_galleries_id = get_post_meta($product_id,'_product_image_gallery',true);
 
  if(!empty($image_galleries_id))
  {
    $image_galleries_array = explode(',',$image_galleries_id);

    foreach($image_galleries_array as $single_image_id)
    {
        wp_delete_post($single_image_id);
    }
  }
	
	$ids = array();
	$images = $_POST['data'];

	foreach ($images as $image)  
	{
		$id = attach_image('/'.$image['url'], $image['alt'], $product_id,$sku);
		if(is_int($id) )
		{
			$ids[] = $id;
		}
		
	}

	update_post_meta($product_id, '_product_image_gallery', implode(',', $ids));  
	die('added');
  }
  else
	  
	  {
		  echo "Product not found"; die;
	  }
}
add_action('wp_ajax_add_product_gallery_images', 'add_product_gallery_images' ); // executed when logged in
add_action('wp_ajax_nopriv_add_product_gallery_images', 'add_product_gallery_images' ); // executed when logged out

function get_parent_terms($term) {
    if ($term->parent > 0) {
        $term = get_term_by("id", $term->parent, "product_cat");
        if ($term->parent > 0) {
            get_parent_terms($term);
        } else return $term;
    }
    else return $term;
}

function all_cat_classes() {
	
	 // global $wpdb;

  //$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $_GET['sku'] ) );
  
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1
        
    );

    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post();
         $product_id = get_the_ID();
        
		if($product_id >= 72885)
		{
			
		$term_ids = [];
		$terms = get_the_terms($product_id, "product_cat");
		if ( count( $terms ) > 0 ) {
		foreach( $terms as $item ) {
			$term_ids[] = $item->term_id;
			
			$pcats = get_ancestors( $item->term_id, 'product_cat' ); 
			foreach($pcats as $scat)
			{
				$term_ids[] = $scat;
			
			}
		}
		}


		wp_set_object_terms( $product_id, $term_ids, 'product_cat' );
		}
    endwhile;

    wp_reset_query(); 
	
   echo "<pre>"; print_r($term_ids); die;
}
add_action('wp_ajax_all_cat_classes', 'all_cat_classes' ); // executed when logged in
add_action('wp_ajax_nopriv_all_cat_classes', 'all_cat_classes' ); // executed when logged out

function woocommerce_product_brandlogo()
{
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    // Custom Product Text Field
	woocommerce_wp_text_input(
		array(
            'id' => '_bulkdeal',
			'class' => 'bulkdeal',
            'placeholder' => 'Add Bulk Deal',
            'label' => __('Add Bulk Deal', 'woocommerce'),
            'desc_tip' => 'true'
        )
	);
	woocommerce_wp_textarea_input(
        array(
            'id' => '_sizeguide',
			'class' => 'sizeguide',
            'placeholder' => 'Size Guide',
            'label' => __('Add Size Guide Info', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
	
    woocommerce_wp_text_input(
        array(
            'id' => '_brandlogo',
			'class' => 'brandlogo',
            'placeholder' => 'Add Brand Logo',
            'label' => __('Add brand Logo', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
	woocommerce_wp_text_input(
        array(
            'id' => '_brandlogourl',
			'class' => 'brandlogourl',
            'placeholder' => 'Add Logo URL',
            'label' => __('Add Logo URL', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
	$logo = get_post_meta($post->ID, '_brandlogo',true);
	echo '<img width="200" class="logoimage" src="'.$logo.'"/> <input type="button" value="Add Logo" class="set_brand_image" />';
    echo '</div>
	<script>
	jQuery(document).ready(function() {
    var $ = jQuery;
    if ($(".set_brand_image").length > 0) {
        if ( typeof wp !== "undefined" && wp.media && wp.media.editor) {
            $(document).on("click", ".set_brand_image", function(e) {
                e.preventDefault();
				var button = $(this);
                wp.media.editor.send.attachment = function(props, attachment) {
					alert(attachment.url);
                  $(".brandlogo").val(attachment.url);
				  $(".logoimage").attr("src",attachment.url);
                };
                wp.media.editor.open(button);
                return false;
            });
        }
    }
}); </script> ';
 
}

add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_brandlogo');
 
add_action('woocommerce_process_product_meta', 'woocommerce_product_brandlogo_save');

add_action('woocommerce_process_product_meta', 'woocommerce_product_bulkdeal_save');
function woocommerce_product_bulkdeal_save($post_id)
{
  
    $woocommerce_custom_product_text_field = $_POST['_bulkdeal'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_bulkdeal', esc_attr($woocommerce_custom_product_text_field));
}

function woocommerce_product_brandlogo_save($post_id)
{
  
    $woocommerce_custom_product_text_field = $_POST['_brandlogo'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_brandlogo', esc_attr($woocommerce_custom_product_text_field));
	
	$woocommerce_custom_product_brandlogourl_field = $_POST['_brandlogourl'];
    if (!empty($woocommerce_custom_product_brandlogourl_field))
        update_post_meta($post_id, '_brandlogourl', esc_attr($woocommerce_custom_product_brandlogourl_field));
	
	$woocommerce_custom_product_brandlogourl_field = $_POST['_sizeguide'];
    if (!empty($woocommerce_custom_product_brandlogourl_field))
        update_post_meta($post_id, '_sizeguide', esc_attr($woocommerce_custom_product_brandlogourl_field));
 
}
function get_custom_colors(){
$terms = get_terms( array(
    'taxonomy' => 'pa_color',
    'hide_empty' => false,
) );


//https://ppesuppliesdirect.co.uk/wp-admin/admin-ajax.php/?action=get_custom_colors
$file = fopen(WP_CONTENT_DIR."/plugins/woocommerce-add-custom/colours.csv","r");
while($data = fgetcsv($file))
{
	$colors_code[$data[0]] = $data[1];
}
fclose($file);
echo "<pre>"; print_r($colors_code);

//die;

foreach( $terms as $term ) {
   
			
	if(array_key_exists($term->name,$colors_code))
	{
		//update_term_meta($term->term_id, 'color', $colors_code[$term->name]);
		
	}
	  $key = get_term_meta( $term->term_id,'color',true );
    echo "<pre>"; print_r($key); echo "</pre>";
} die;
//echo "<pre>"; print_r($terms); echo "</pre>"; die;
}

add_action('wp_ajax_get_custom_colors', 'get_custom_colors' ); // executed when logged in
add_action('wp_ajax_nopriv_get_custom_colors', 'get_custom_colors' ); // executed when logged out



function add_brands_terms(){ 

global $wpdb;
/**/

$args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1
    );

  $loop = new WP_Query( $args );
  $a = 1;
  set_time_limit(0);
    while ( $loop->have_posts() ) : $loop->the_post();
	global $product;
	//echo $product->id;
		if($product->id >= 86742  ) {
        
		/*$pdesc = $product->get_short_description();
		
        $pdesc  =  explode('grey"',$pdesc );
		$newproducttitle = explode('<p style="margin:5px',$pdesc[1] );
		$ptitle = $newproducttitle[0];
		$ptitle = str_replace(' style="margin-left: 5px;">','',$ptitle);
		
		$ptitle = str_replace('</span></h2>','',$ptitle);
		$ptitle = str_replace('>','',$ptitle);
		$wpdb->update( 
			'wp_posts', 
			array( 
				'post_title' => $ptitle,	// string
			), 
			array( 'ID' => $product->id ), 
			array( 
				'%s'	// value1
			), 
			array( '%d' ) 
		);*/
		
		//echo "<pre>"; print_r($ptitle); echo "</pre>";
		//;
		$terms['p_'.$product->id] = get_the_terms( $product->id, 'product_brand' );
		$logo = get_post_meta($product->id,'_brandlogo',true);
		$dealb = get_post_meta($product->id,'_bulkdeal',true);
		echo $deaalb;
		//echo $logo;
		if($logo != '')
		{
			//echo $product->id;
			$exp = end(explode('/', $logo));
			
			$logoslug = explode('.',$exp)[0];
			$logoslug = str_replace('%26','-',$logoslug);
			$logoslug = str_replace('%2','-',$logoslug);
			$term = get_term_by('slug', $logoslug, 'product_brand');
			if($term == '' || empty($term))
			{
				echo $logoslug;
				$logoname = str_replace('-',' ',$logoslug);
				$logoname = str_replace('_',' ',$logoname);
				$logoname = ucfirst($logoname);
				$inserted = wp_insert_term(
				  $logoname, // the term 
				  'product_brand', // the taxonomy
				  array(
					'description'=> '',
					'slug' => $logoslug,
					'parent'=> '',  // get numeric term id
				  )
				); 

				add_option( 'brand_taxonomy_image'.$inserted['term_id'], $logo, '', 'no' );
			}
			//echo "<pre>"; print_r($term); echo "</pre>"; die;
			$term = get_term_by('slug', $logoslug, 'product_brand');
			wp_set_post_terms($product->id, $term->term_id ,'product_brand');
			//$logos[get_post_meta($product->id,'_brandlogo',true)] = get_post_meta($product->id,'_brandlogo',true);
			 //echo "<pre>"; print_r($term); echo "</pre>"; die;
		}
		}
	   $a++;
    endwhile;

	
    wp_reset_query();	
	//die;
	echo "<pre>"; print_r($terms); echo "</pre>"; die;
	foreach($logos as $logo){
		$exp = explode('/', $logo);
	   $unilogos[end($exp)]	= $logo;
	}
	$c = 0;

	/*foreach($unilogos as $unilogo=>$vval){
		if($c>0)
		{
			echo $logoslug.'</br>';
		$logoslug = explode('.',$unilogo)[0];
		$logoslug = str_replace('%26','-',$logoslug);
		$logoslug = str_replace('%2','-',$logoslug);
		$logoname = str_replace('-',' ',$logoslug);
		$logoname = str_replace('_',' ',$logoname);
		$logoname = ucfirst($logoname);
		//echo $logoname.'='.$logoslug.'<br>';
		
		 $inserted = wp_insert_term(
			  $logoname, // the term 
			  'product_brand', // the taxonomy
			  array(
				'description'=> '',
				'slug' => $logoslug,
				'parent'=> '',  // get numeric term id
			  )
			); 

			//add_option( 'brand_taxonomy_image'.$inserted['term_id'], $vval, '', 'no' );
		}
		$c++;
	} */
	//echo "<pre>"; print_r($unilogos); echo "</pre>"; die; die;
die;	


die("added");
}
add_action('wp_ajax_add_brands_terms', 'add_brands_terms' ); // executed when logged in
add_action('wp_ajax_nopriv_add_brands_terms', 'add_brands_terms' ); // executed when logged out

function add_gender_terms(){ 

global $wpdb;
/**/
$args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1
    );

  $loop = new WP_Query( $args );
  $a = 1;
  set_time_limit(0);
    while ( $loop->have_posts() ) : $loop->the_post();
		global $product;
		//
		if (strpos($product->name, 'Kids') !== false) {
			//echo "<pre>"; print_r($product->name); die;
			$thedata = get_post_meta($product->id,'_product_attributes',true);
			wp_set_object_terms( $product->id, 'kids', 'pa_gender', true );
					
			$newdata = Array('pa_gender'=>Array(
				   'name'=>'pa_gender',
				   'value'=>'',
				   'is_visible' => '1',
				   'is_taxonomy' => '1'
				 ));         
				 
			$thedata = 	 array_merge($thedata,$newdata);

			update_post_meta( $product->id,'_product_attributes',$thedata); 
		}
		
		if (strpos($product->name, 'Unisex') !== false) {
			//echo "<pre>"; print_r($product->name); die;
			$thedata = get_post_meta($product->id,'_product_attributes',true);
			wp_set_object_terms( $product->id, 'unisex', 'pa_gender', true );
					
			$newdata = Array('pa_gender'=>Array(
				   'name'=>'pa_gender',
				   'value'=>'',
				   'is_visible' => '1',
				   'is_taxonomy' => '1'
				 ));                       
				 
			$thedata = 	 array_merge($thedata,$newdata);

			update_post_meta( $product->id,'_product_attributes',$thedata); 
		}                            	
	endwhile;
                       
	                                                                                                                    
    wp_reset_query();
	die;
}

add_action('wp_ajax_add_gender_terms', 'add_gender_terms' ); // executed when logged in
add_action('wp_ajax_nopriv_add_gender_terms', 'add_gender_terms' ); // executed when logged out


function searchandreplace(){ 

global $wpdb;

$allpost = $wpdb->get_results("Select guid From wp_posts where post_type = 'attachment'");
//   https://ppesuppliesdirect.co.uk/wp-content/uploads/
echo count($allpost);
//$wpdb->query($wpdb->prepare("UPDATE wp_posts SET `guid` = REPLACE (`guid`, 'http://ppesuppliesdirect.co.uk/uploads/', 'https://ppesuppliesdirect.co.uk/wp-content/uploads/') WHERE post_type = 'attachment'"));

$allpost = $wpdb->get_results("Select guid From wp_posts where post_type = 'attachment'");
echo "<pre>"; print_r($allpost); die;
}
add_action('wp_ajax_searchandreplace', 'searchandreplace' ); // executed when logged in
add_action('wp_ajax_nopriv_searchandreplace', 'searchandreplace' ); // executed when logged out



add_action('pa_color_edit_form_fields','pa_color_edit_form_fields');
add_action('pa_color_add_form_fields','pa_color_edit_form_fields');
add_action('edited_pa_color', 'pa_color_save_form_fields', 10, 2);
add_action('created_pa_color', 'pa_color_save_form_fields', 10, 2);

function pa_color_save_form_fields($term_id) {
    $meta_name = 'colorimage';
    if ( isset( $_POST[$meta_name] ) ) {
        $meta_value = $_POST[$meta_name];
      
        update_term_meta($term_id,'colorimage',$meta_value);
 
    }
}

function pa_color_edit_form_fields ($term_obj) {
    // Read in the order from the options db
    $term_id = $term_obj->term_id;
	//echo "<pre>"; print_r($term_obj);
	$colorimage = get_term_meta( $term_id,'colorimage',true );
    //$term_metas = get_option("taxonomy_{$term_id}_metas");
    
?>
    <tr class="form-field">
            <th valign="top" scope="row">
                <label for="colorimage"><?php _e('Color Image', ''); ?></label>
            </th>
            <td>
                <input type="text" id="colorimage" name="colorimage" value="<?php echo $colorimage; ?>"/>
            </td>
	</tr>
<?php 
}

function addcolors(){
	
	foreach($_POST['data']['colors'] as $colorname=>$colorcode)
	{
		$term = get_term_by( 'name', $colorname, 'pa_color' ); 
		
		$termid = $term->term_id;
		
		if($termid== ''){
			$insertedterm = wp_insert_term(
			  $colorname, // the term 
			  'pa_color', // the taxonomy
			  array(
				'description'=> '',
				'parent'=> ''  // get numeric term id
			  )
			);
			//echo "<pre>"; print_r($insertedterm);
		}
		$key = get_term_meta( $termid,'color',true ); 
		//echo $key; die;
		if($key == '' && $_POST['data']['colortypes'][$colorname] != 'image')                                  
		{

				update_term_meta($termid,'color',$colorcode);                                                                                        
			
		}
		
		if($_POST['data']['colortypes'][$colorname] == 'image')                                                                                               
			{
				update_term_meta($termid,'color','');                                                                                           
				update_term_meta($termid,'colorimage',$colorcode);                                                                                            
			}
		
		
	
	}
	die;
	
	
}

add_action('wp_ajax_addcolors', 'addcolors' ); // executed when logged in                                                                            
add_action('wp_ajax_nopriv_addcolors', 'addcolors' ); // executed when logged out                                                                             

// Add to admin_init function
add_filter("manage_edit-pa_color_columns", 'add_colorimage_columns');             
 
function add_colorimage_columns($columns) {

	//unset($columns['thumb']);
    $columns['colorimage'] = 'Color Image';
    return $columns;
 
}                 

function manage_custom_column_image($content,$column_name,$term_id){
	
    $termimage = get_term_meta($term_id, 'colorimage');
	
	if(empty($termimage)  || $termimage[0] == '')
	{
		$colormeta = get_term_meta($term_id,'color');
		//echo '<pre>'; print_r($colormeta); echo "</pre>";
		foreach($colormeta as $code)
		{
			if(is_array($code))
			{$colorcircle = '';
				foreach($code  as $colorcode){
					$colorcircle .= '<div style="background-color: '.$colorcode.'; width:45px;height:20px;"></div>';
				}
			}
			else
			{
				$colorcircle = '<div style="background-color: '.$code.';width:45px;height:20px;"></div>';
			}
		}
		
	}
	else
	{
		$img = end(explode('/',$termimage[0]));
		$colorimg = explode('&',$img);
		$colorcircle = '<img src="/wp-content/uploads/colorswatches/'.$colorimg[0].'">';
	}
	
			//echo "<pre>"; print_r($termimage); echo "</pre>";
		switch ($column_name) {
			case 'colorimage':
				//do your stuff here with $term or $term_id
				$content = $colorcircle;
				break;
			default:
				break;
		}
    return $content;
}
add_filter('manage_pa_color_custom_column', 'manage_custom_column_image',70,3);

add_action('admin_head', 'my_custom_cssforcolors');

function my_custom_cssforcolors() {
  echo '<style>
    .colorimage .swatch-preview {
      display:none;
    } 
  </style>';
}

function add_primary_color_terms(){ 

global $wpdb;
/**/

$args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1
    );
	$getc = $wpdb->get_results('select * from wp_color_relations');
//echo "<pre>"; print_r($getc); echo "</pre>";
foreach($getc as $get)
{
   $newcolor[$get->primary_color] = json_decode($get->colors);
}

echo "<pre>"; print_r($newcolor); echo "</pre>";

$terms2 = get_terms( array(
				'taxonomy' => 'pa_colour',
				'hide_empty' => false,
			) );

  $loop = new WP_Query( $args );
  $a = 1;
  set_time_limit(0);
    while ( $loop->have_posts() ) : $loop->the_post();
	global $product;
	
		$terms = get_the_terms($product->id, 'pa_color');
$termscolour = get_the_terms($product->id, 'pa_colour');
echo "<pre>preeee"; print_r($termscolour);
		foreach($terms  as $term)
		{
			$cn = str_replace('_',' ',$term->name);
				foreach($newcolor as $pcolor=>$ncolor)
				{

					if(in_array( $cn ,$ncolor ))
					{ 
						$nterm = get_term_by('name',$pcolor,'pa_colour');
						wp_set_object_terms( $product->id, $nterm->slug, 'pa_colour', true );
					}

				}
			//wp_set_object_terms( $product->id, '', 'pa_colour', true );
		}
		
		
	endwhile;

	wp_reset_query();
	die('success');
	
}
add_action('wp_ajax_add_primary_color_terms', 'add_primary_color_terms' ); // executed when logged in                                                                            
add_action('wp_ajax_nopriv_add_primary_color_terms', 'add_primary_color_terms' ); // executed when logged out  

function get_skus_difference(){ 

global $wpdb;
/**/
$args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1
    );

  $loop = new WP_Query( $args );
  $a = 1;
  set_time_limit(0);
    while ( $loop->have_posts() ) : $loop->the_post();
		global $product;
		//echo "<pre>"; print_r($product->get_sku());
		$productskus[] = $product->get_sku();
	endwhile;
	wp_reset_query();
//echo "<pre>"; print_r($productskus); echo "</pre>";
$all_size = $wpdb->get_results("select product_code from wp_sizechart");
foreach($all_size as $size)
{
		$skusize[]= $size->product_code;
}
//echo "<pre>"; print_r($skusize);

$all_price = $wpdb->get_results("select product_code from wp_pricing_table");
foreach($all_price as $price)
{
		$skuprice[]= $price->product_code;
}

$sizediff = array_diff($productskus, $skusize);
$pricediff = array_diff($productskus, $skuprice);
echo "Size Skus";
foreach($sizediff as $diff)
{
echo "<br>";
echo $diff;

}
echo "<br/><br/><br/>Price Skus";
foreach($pricediff as $diff)
{
echo "<br>";
echo $diff;

}
/*   
$sizediff = array_diff($skusize , $productskus);
$pricediff = array_diff($skuprice, $productskus );
echo "Size Skus";
foreach($sizediff as $diff)
{
echo "<br>";
echo $diff;
}
echo "<br/><br/><br/>Price Skus";
foreach($pricediff as $diff)
{
echo "<br>";
echo $diff;
}*/
//echo "<pre>"; print_r($sizediff);
//echo "<pre>"; print_r($pricediff);

	die;
}

add_action('wp_ajax_get_skus_difference', 'get_skus_difference' );                                                            
add_action('wp_ajax_nopriv_get_skus_difference', 'get_skus_difference' ); // executed when logged out  

	
function get_vari_price_database(){
	global $wpdb;
	//die("dfdfdsf");
	/*$post_v_price = $wpdb->get_results("SELECT priceinfo FROM wp_pricing_table WHERE product_code='".$sku."'");
			$post_v_price = $wpdb->get_results("SELECT priceinfo FROM wp_pricing_table WHERE product_code='RG059'");
			
			//echo "<pre>"; print_r($post_v_price[0]->priceinfo); echo "</pre>";
			$characters = json_decode($post_v_price[0]->priceinfo, true);
			$first_price = $characters['1-9'];
			$first_price2 = $characters['10-19'];
			$first_price3 = $characters['20-49'];
			$first_price4 = $characters['50-99'];
			$first_price5 = $characters['100-249'];
			$first_price6 = $characters['250 to 499'];
			$first_prices = str_replace('£', '' , $first_price);
			$first_prices2 = str_replace('£', '' , $first_price2);
			$first_prices3 = str_replace('£', '' , $first_price3);
			$first_prices4 = str_replace('£', '' , $first_price4);
			$first_prices5 = str_replace('£', '' , $first_price5);
			$first_prices6 = str_replace('£', '' , $first_price6);
			echo "<pre>"; echo $characters .'<br/>' . $first_prices2 .'<br/>'. $first_prices3 .'<br/>'. $first_prices4 .'<br/>'. $first_prices5 .'<br/>'. $first_prices6 .'<br/>'; echo "</pre>"; die;
	
	$args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1
    ); */


  $loop = new WP_Query( $args );
  $a = 1;
  set_time_limit(0);
   /* while ( $loop->have_posts() ) : $loop->the_post();
		global $product;
		$sku = $product->get_sku();
		$id = $product->id;
		//echo $id; die;
		$handle=new WC_Product_Variable($id);
        $variations1=$handle->get_children();
        foreach ($variations1 as $value) {
			$single_variation=new WC_Product_Variation($value);
			$post_id = $single_variation->get_variation_id();
			//delete_post_meta( int $post_id, string $meta_key
			//$as = get_post_meta($post_id,'_regular_price');
			
			//$post_v_price = $wpdb->get_results("SELECT priceinfo FROM wp_pricing_table WHERE product_code='".$sku."'");
			$post_v_price = $wpdb->get_results("SELECT priceinfo FROM wp_pricing_table WHERE product_code='RG059'");
			
			//echo "<pre>"; print_r($post_v_price[0]->priceinfo); echo "</pre>";
			$characters = json_decode($post_v_price[0]->priceinfo, true);
			$first_price = $characters['1-9'];
			$first_prices = str_replace('£', '' , $first_price);
			echo "<pre>"; print_r($first_prices); echo "</pre>";
/*			if($first_prices != '')
			{
				
				update_post_meta($post_id, '_regular_price', $first_prices);
			//}
			
		
		} 
		//die;
	endwhile;	*/
		
	
	}
	
	add_action('wp_ajax_get_vari_price_database', 'get_vari_price_database' );
	add_action('wp_ajax_nopriv_get_vari_price_database', 'get_vari_price_database' );

	/* function woocommerce_bundle_product( $title, '113452' ) {
		echo "hello";
		//return get_post_meta( $product_id, 'wpcf-google-merchant-product-title', true);
	}
	add_filter( 'woocommerce_gpf_title', 'woocommerce_bundle_product', 10, 2 ); */
  
include('sidebar_new.php');