<?php
/*
  Plugin Name: woocommerce color size
  Description: woocommerce add on for color, size and quantity
  Version: 1
  Author: CWS
 */

if( WP_DEBUG && WP_DEBUG_DISPLAY && (defined('DOING_AJAX') && DOING_AJAX) ){
	@ ini_set( 'display_errors', 1 );
}

// Vat
$tax = 'ex';
if( $_COOKIE['vat']  === 'in' ) {
    $tax = 'in';
}elseif( $_COOKIE['vat']  === 'ex' ){
    $tax = 'ex';
}
if( $_GET['vat']  === 'in' ) {
    $tax = 'in';
}elseif( $_GET['vat']  === 'ex' ){
    $tax = 'ex';
}

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
	'2' => '2',
	'4' => '4',
	'6' => '6',
	'8' => '8',
	'10' => '10',
	'12' => '12',
	'14' => '14',
	'16' => '16',
	'18' => '18',
	'20' => '20',
	'22' => '22'	
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
				
				//echo "<pre>"; print_r($variation); echo "</pre>"; 
			}
$size_vars = array_unique($size_vars);
$color_vars = array_unique($color_vars);
	$plugin_url = plugin_dir_url( __FILE__ );
		echo '<link rel="stylesheet" class="test" href="'.$plugin_url . 'style.css" type="text/css"/>';
	?>
	 
<?php $bulkdeal = get_post_meta( $product->get_id(), '_bulkdeal' ); 
	  $bulkprice = get_post_meta( $product->get_id(), '_bulkprice' ); 
	//echo "<pre>"; print_r($dealb);
	if($bulkdeal[0] != '')
	{
		$handle=new WC_Product_Variable($product_id);
        $variations1=$handle->get_children();
        foreach ($variations1 as $value) {
			$single_var=new WC_Product_Variation($value);
		
			$gvarid = $single_var->get_id(); //die;
			$makep =  $bulkprice[0] / $bulkdeal[0];
			
			update_post_meta($gvarid, '_regular_price',round($makep,2) );
		}
		
	}
?>
	<div class="bulkdeal_product" style="display:none">
		<div class="bulkdeal_product_d">
			<h5>Bulk Deal: </h5>
			<input type="text" value="<?php echo $bulkdeal[0]; ?>" name="bulkdeal_count" class="bulkdeal_count" readonly />
		</div>
		<div class="bulkdeal_product_d">
			<h5>Bulk Deal Price: </h5>
			<input type="text" value="<?php echo ( $tax === 'in' ) ? round( $bulkprice[0] * 1.2, 2 ) : $bulkprice[0]; ?>" name="bulkdeal_price" class="bulkdeal_price" readonly />
		</div>
	</div>
	
	<div class="main_varient">
	<?php
		$id = get_the_ID();
		$homeurl = get_home_url();
		$term_ids = get_the_terms( $id, 'product_brand' );
		$term_id = $term_ids[0]->term_id;
		$brand_url = '/brand/' . $term_ids[0]->slug;
		$taxonomy_image_url = get_option('brand_taxonomy_image' . $term_id);
		if( get_post_meta( $id, 'customize_product', true ) ){
			echo '<div class="second_content" style="float: right;"><a class="customize_icon" href="'.$homeurl.'/custom-design/?product_id='.$id.'"><img src="/wp-content/uploads/2019/03/Customise-button.jpg" width="260"></a></div>';
		}
		?>	
	<h3>
		<?php
				echo $product_title; ?>
				</h3>
				<div class="pr_img_code"><div class="pr_code"><span>Product Code: <?php echo $product_sku; ?></span>
		<div class="logo_image"">
					<?php // $key_name = get_post_custom_values($key = 'apus_product_features');  echo $key_name[0]; ?>
					<?php $brandlogometa = get_post_meta($product_id,'_brandlogo',true); 
					//echo $brandlogometa;
					$brandterm = get_the_terms( $product->get_id(), 'product_brand' );
						
					//echo "<pre>"; print_r($getterm);                                                                                                  
							//$_brandlogourl = get_post_meta($product_id,'_brandlogourl',true); 
							$_brandlogourl = site_url().'/shop?product_brand='.$brandterm[0]->slug;
							$brandlogo = get_option('brand_taxonomy_image'.$brandterm[0]->term_id);
							if($brandlogo == '')
							{
								$brandlogometadata = explode('/',$brandlogometa);
								$extractbrandslug = end($brandlogometadata);
								$brandslug = explode('.',$extractbrandslug);
								$getterm = get_term_by('slug', $brandslug[0], 'product_brand');
								//echo "<pre>"; print_r($getterm); 
							}
							$brandlogo = get_option('brand_taxonomy_image'.$getterm->term_id);
							//echo  $brandlogo;
					?>
						<a href="<?php echo $brand_url; ?>" target="_blank"><img width="120px" src="<?php echo $taxonomy_image_url; ?>" alt=""/></a>
						
<!--<p style="margin:5px 0 23px;">Be the first to review this product</p>-->
	
				</div>
</div> 
							
			
<?php global $wpdb;

$getpriceinfo = $wpdb->get_results('select * from wp_pricing_table where product_code="'.$product_sku.'"');
//echo "<pre>"; print_r($getpriceinfo); echo "</pre>"; 
$priceinfo = $getpriceinfo[0]->priceinfo;
$priceinfo = json_decode($priceinfo,true);
// if(empty($priceinfo))
// {  
	// $priceinfo['1-9'] = get_post_meta($id,'_qty1to9_productc_qty',true);
	// $priceinfo['10-19'] = get_post_meta($id,'_qty10to19_productc_qty',true);
	// $priceinfo['20-49'] = get_post_meta($id,'_qty20to49_productc_qty',true);
	// $priceinfo['50-99'] = get_post_meta($id,'_qty50to99_productc_qty',true);
	// $priceinfo['100-249'] = get_post_meta($id,'_qty100to249_productc_qty',true);
	// $priceinfo['250 to 499'] = get_post_meta($id,'_qty250to499_productc_qty',true);
	
// }

//echo "<pre>"; print_r($priceinfo); echo "</pre>"; 
?>
<?php if ( get_post_meta( $product->get_id(), 'choose_template', true ) !== 'template_2' ): ?>
<div class="table_tab500" >
	<div class="tab">
	  <button class="tablinks" onclick="openTab(event, 'Pricing')" id="defaultOpen">BLANK PRICING</button>
	  <button class="tablinks" onclick="openTab(event, 'Embroidery')" id="middle_btn">EMBROIDERY</button>
	  <button class="tablinks" onclick="openTab(event, 'Print')">PRINT</button>
	<div id="Pricing" class="tabcontent">
	<table class="price_table">
	<tbody>
	<tr>
	<td class="table_quantity">1-9</td>
	<td class="table_quantity">10-19</td>
	<td class="table_quantity">20-49</td>
	<td class="table_quantity">50-99</td>
	<td class="table_quantity">100-249</td>
	<td class="table_quantity">250-499</td>
	</tr>
	<tr>
	<?php 
		$singlep = ( $tax === 'in' ) ? 1.2 * get_post_meta( $product_id, '_qty1to9_productc_qty', true ) : get_post_meta( $product_id, '_qty1to9_productc_qty', true ); 		
		$price10 = ( $tax === 'in' ) ? 1.2 * get_post_meta( $product_id, '_qty10to19_productc_qty', true ) : get_post_meta( $product_id, '_qty10to19_productc_qty', true );
		$price20 = ( $tax === 'in' ) ? 1.2 * get_post_meta( $product_id, '_qty20to49_productc_qty', true ) : get_post_meta( $product_id, '_qty20to49_productc_qty', true );
		$price50 = ( $tax === 'in' ) ? 1.2 * get_post_meta( $product_id, '_qty50to99_productc_qty', true ) : get_post_meta( $product_id, '_qty50to99_productc_qty', true );
		$price100 = ( $tax === 'in' ) ? 1.2 * get_post_meta( $product_id, '_qty100to249_productc_qty', true ) : get_post_meta( $product_id, '_qty100to249_productc_qty', true );
		$price250 = ( $tax === 'in' ) ? 1.2 * get_post_meta( $product_id, '_qty250to499_productc_qty', true ) : get_post_meta( $product_id, '_qty250to499_productc_qty', true );
		$addeuro = '£';

	?>
	<td class="table_price"><?php echo $addeuro.round($singlep,2); ?></td>
	<td class="table_price"><?php echo $addeuro.round($price10,2); ?></td>
	<td class="table_price"><?php echo $addeuro.round($price20,2); ?></td>
	<td class="table_price"><?php echo $addeuro.round($price50,2); ?></td>
	<td class="table_price"><?php echo $addeuro.round($price100,2); ?></td>
	<td class="table_price"><?php echo $addeuro.round($price250,2); ?></td>
	</tr>
	<tr>
	<td></td>
	<td class="table_perc"><?php echo round(((($singlep-$price10)/$singlep)*100),2); ?>%</td>
	<td class="table_perc"><?php echo round(((($singlep-$price20)/$singlep)*100),2); ?>%</td>
	<td class="table_perc"><?php echo round(((($singlep-$price50)/$singlep)*100),2); ?>%</td>
	<td class="table_perc"><?php echo round(((($singlep-$price100)/$singlep)*100),2); ?>%</td>
	<td class="table_perc"><?php echo round(((($singlep-$price250)/$singlep)*100),2); ?>%</td>
	</tr>
	</tbody>
	</table> 
	</div>
	<div id="Embroidery" class="tabcontent">
	<table class="price_table">
	<tbody>
	<tr>
	<td class="table_quantity">1-5</td>
	<td class="table_quantity">6-12</td>
	<td class="table_quantity">13-29</td>
	<td class="table_quantity">33-99</td>
	<td class="table_quantity">100-249</td>
	<td class="table_quantity">250-499</td>
	<td class="table_quantity">500+</td>
	</tr>
	<tr>
	<td class="table_price">£4.45</td>
	<td class="table_price">£3.95</td>
	<td class="table_price">£3.55</td>
	<td class="table_price">£2.95</td>
	<td class="table_price">£2.45</td>
	<td class="table_price">£1.67</td>
	<td class="table_price">£1.39</td>
	</tr>
	<tr>
	<td class="table_perc"></td>
	<td class="table_perc">11.24%</td>
	<td class="table_perc">20.22%</td>
	<td class="table_perc">33.71%</td>
	<td class="table_perc">44.94%</td>
	<td class="table_perc">62.47%</td>
	<td class="table_perc">68.76%</td>
	</tr>
	</tbody>
	</table> 
	</div>
	<div id="Print" class="tabcontent">
	<table class="price_table">
	<tbody>
	<tr>
	<td class="table_quantity">1-5</td>
	<td class="table_quantity">6-12</td>
	<td class="table_quantity">13-29</td>
	<td class="table_quantity">33-99</td>
	<td class="table_quantity">100-249</td>
	<td class="table_quantity">250-499</td>
	<td class="table_quantity">500+</td>
	</tr>
	<tr>
	<td class="table_price">£4.45</td>
	<td class="table_price">£3.45</td>
	<td class="table_price">£2.45</td>
	<td class="table_price">£2.25</td>
	<td class="table_price">£1.95</td>
	<td class="table_price">£1.47</td>
	<td class="table_price">£1.28</td>
	</tr>
	<tr>
	<td class="table_perc"></td>
	<td class="table_perc">22.47%</td>
	<td class="table_perc">44.94%</td>
	<td class="table_perc">49.44%</td>
	<td class="table_perc">56.18%</td>
	<td class="table_perc">66.97%</td>
	<td class="table_perc">71.24%</td>
	</tr>
	</tbody>
	</table> 
	</div>	
	</div> 
</div>
<?php elseif( get_post_meta( $product->get_id(), 'choose_template', true ) === 'template_2' ): ?>
	<div class="table_tab500" >
	<div class="tab">
	  <button class="tablinks" onclick="openTab(event, 'Pricing')" id="defaultOpen">BLANK PRICING</button>
	  <button class="tablinks" onclick="openTab(event, 'Embroidery')" id="middle_btn">EMBROIDERY</button>
	  <button class="tablinks" onclick="openTab(event, 'Print')">PRINT</button>
	<div id="Pricing" class="tabcontent">

		<div class="single_heading red">PRICE</div>
			<table class="price_table">
				<tbody>
					<tr>
						<td class="table_quantity">1-9</td>
						<td class="table_quantity">10-19</td>
						<td class="table_quantity">20-49</td>
						<td class="table_quantity">50-99</td>
						<td class="table_quantity">100-249</td>
						<td class="table_quantity">250-499</td>
					</tr>
					<tr>

						<?php
						// Vat
						$tax = 'ex';
						if( $_COOKIE['vat']  === 'in' ) {
						    $tax = 'in';
						}elseif( $_COOKIE['vat']  === 'ex' ){
						    $tax = 'ex';
						}
						if( $_GET['vat']  === 'in' ) {
						    $tax = 'in';
						}elseif( $_GET['vat']  === 'ex' ){
						    $tax = 'ex';
						}
						if( $tax === 'in' ){
							$singlep = get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true ) * 1.2;
							$price10 = get_post_meta( $product->get_id(), '_qty10to19_productc_qty', true ) * 1.2;
							$price20 = get_post_meta( $product->get_id(), '_qty20to49_productc_qty', true ) * 1.2;
							$price50 = get_post_meta( $product->get_id(), '_qty50to99_productc_qty', true ) * 1.2;
							$price100 = get_post_meta( $product->get_id(), '_qty100to249_productc_qty', true ) * 1.2;
							$price250 = get_post_meta( $product->get_id(), '_qty250to499_productc_qty', true ) * 1.2;
						}else{
							$singlep =get_post_meta( $product->get_id(), '_qty1to9_productc_qty', true );
							$price10 = get_post_meta( $product->get_id(), '_qty10to19_productc_qty', true );
							$price20 = get_post_meta( $product->get_id(), '_qty20to49_productc_qty', true );
							$price50 = get_post_meta( $product->get_id(), '_qty50to99_productc_qty', true );
							$price100 = get_post_meta( $product->get_id(), '_qty100to249_productc_qty', true );
							$price250 = get_post_meta( $product->get_id(), '_qty250to499_productc_qty', true );
						}

						?>
						<td class="table_price"><?php echo round( $singlep, 2 ); ?></td>
						<td class="table_price"><?php echo round( $price10, 2 ); ?></td>
						<td class="table_price"><?php echo round( $price20, 2 ); ?></td>
						<td class="table_price"><?php echo round( $price50, 2 ); ?></td>
						<td class="table_price"><?php echo round( $price100, 2 ); ?></td>
						<td class="table_price"><?php echo round( $price250, 2 ); ?></td>
					</tr>
					<tr>
						<td></td>
						<td class="table_perc"><?php echo round(((($singlep-$price10)/$singlep)*100),2); ?>%</td>
						<td class="table_perc"><?php echo round(((($singlep-$price20)/$singlep)*100),2); ?>%</td>
						<td class="table_perc"><?php echo round(((($singlep-$price50)/$singlep)*100),2); ?>%</td>
						<td class="table_perc"><?php echo round(((($singlep-$price100)/$singlep)*100),2); ?>%</td>
						<td class="table_perc"><?php echo round(((($singlep-$price250)/$singlep)*100),2); ?>%</td>
					</tr>
				</tbody>
			</table> 
		</div>
	</div>
</div>
<?php endif; ?>
<script>
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
if(document.getElementById("defaultOpen")) {
	console.log(document.getElementById("defaultOpen"));
	document.getElementById("defaultOpen").click();
}
</script>
<div class="iconic-engraving-field">  
<?php
// $size_order = array( '1-2', '2', '2 Years', '2-3', '3', '3-4', '3-4 Years', '3/4 Years', '3-6', '4', '4-5 Years', '4-6', '4-6 Years', '4-7', '5', '5-6 Years', '5/6 Years', '5-6', '6', '6.5', '6-8', '6-9', '6-11', '7', '7-8', '7/8 Years', '7-8 Years', '7-9', '7-9 years', '7-10', '7-11', '8', '9', '9-10', '9-10 Years', '9/10 Years', '9-11', '11-13 Years', '11/13 Years', '12/14', '16/18', '10', '10T', '10XL', '10.5', '10-12', '11', '12', '12T', '11-12', '11-13', '12-13', '13', '13-14', '14', '14T', '14.5', '14-15', '15', '15.5', '16', '16T', '16.5', '17', '17.5', '18', '18T', '18.5', '19', '19.5', '100', '104', '108', '112', '116', '124', '128', '1112', '132', '136', '140', '145', '150', '155', '160', '165', '170', '175', '180', '185', '190', '195', '20', '20/22', '200', '20T', '21', '22', '22T', '23', '24', '24T', '26', '26T', '28', '28S', '28T', '30', '30S', '30T', '32', '32S', '32T', '33', '34', '34S', '34T', '35', '35/2', '36', '36/3', '36S', '36T', '37', '37/4', '38', '38/5', '38s', '38T', '39', '39-43', '39/6', '40', '40/6', '40S', '40T', '41', '41/7', '42', '42/8', '42S', '42T', '43', '43/9', '44', '44-48', '44/10', '44S', '44T', '45', '45/10.5', '46', '46/11', '46S', '46T', '47', '47/12', '48', '48/13', '48S', '48T', '49', '49/14', '4X5X', '50', '50/15', '50S', '50T', '51', '51/16', '52', '52/17', '52S', '52T', '54', '56', '58', '60', '67', '6T', '6X7X', '72', '76', '8/10', '80', '84', '88', '89', '8T', '92', '96', 'Child', 'KX3', 'XXS/XS', 'XXS', 'XS/S', 'XS', 'Small', 'S', 'S reg', 'S long', 'SM', 'S/R', 'S/M', 'ST', 'S/L', 'M', 'MT', 'Medium', 'M reg', 'M/R', 'M long', 'M/L', 'L', 'L long', 'L reg', 'L/L', 'L/R', 'L/XL', 'Large', 'LT', 'LXL', 'One', 'One size', 'Reg 28 - 48', 'Reg 30 - 44', 'Reg 30 - 46', 'Reg 32 - 46', 'Tall 28 - 44', 'Tall 32 - 42', 'UK28', 'UK30', 'UK32', 'UK33', 'UK34', 'UK36', 'UK37', 'UK38', 'UK38', 'UK40', 'UK40', 'UK41', 'UK41', 'UK42', 'UK42', 'UK44', 'UK44', 'UK46', 'UK46', 'UK47', 'UK48', 'UK48', 'UK50', 'UK52', 'UK54', 'WX3', 'XL', 'XL long', 'XL reg', 'XL/L', 'XL/R', 'XLT', 'XX3X', 'XXL', 'XXL long', 'XXL reg', '3XL/R', '3XLT', '3XL/L', 'XXL/L', 'XXL/R', 'XXLT', 'XXL/3XL', 'XXXL', '3XL', '3XL reg', '3XL long', '4XL', '4XL/5XL', '4XLT', '5XL', '6XL', '7XL', '8XL', '9XL', '54XL' );
//$size_vars_u = array_change_key_case($size_vars, CASE_UPPER);
//$orderarray = array_merge(array_flip($size_order), $size_vars); 
//echo "<pre>"; print_r($orderarray); echo "</pre><br/>";

// $size_order = array_map('strtolower', $size_order);
// $orderarray = array_intersect( $size_order, $size_vars );

//echo "<pre>"; print_r($size_vars); echo "</pre><br/>";
//echo "<pre>"; print_r($orderarray); echo "</pre><br/>";
?>
<div class="size_wrapper">
<div class="single_heading">CHOOSE SIZE AND QUANTITY</div>	
<div class="size_variations">
<table class="size_table">
<tbody>
<tr>
<?php 
foreach($size_vars as $sizeattr_k=>$sizeattr_v){
	//echo "<pre>"; print_r($sizeattr_v); echo "</pre>";
	?>
	<td align="center" class="size_thead"><?php echo (isset($size_attr[$sizeattr_v])) ? $size_attr[$sizeattr_v] : $sizeattr_v; ?></td>
<?php } ?>

</tr>
<tr>

<?php foreach($size_vars as $sizeattr_k=>$sizeattr_v){
					//echo "<pre>"; print_r($sizeattr_v); echo "</pre>";
					
					?>
					<td align="center">
					<div class="qty-increase">+</div>
							<input data-sizename="<?php echo $sizeattr_v; ?>" min="0" class="size_input varient_quantity" value="0" type="number" id="sizeValue"/>
					<div class="qty_decrese" >-</div>
					</td>
					<?php 
					}
					?>	


</td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="colour_wrapper">		
<div class="single_heading">CHOOSE THE COLOUR</div>
<div class="colour_variations">
<table class="colour_table">
<tbody>
<tr align="center">
<?php
foreach($color_vars as $colorvar){
				
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
							$colorcircle .= '<div data-colorid="'.$colorterm->term_id.'" class="circle" style="background:linear-gradient(to right,'.$code['0'].', '.$code['1'].', '.$code['2'].');"><div class="tooltip_text" style="visibility: visible; z-index: 999999; margin-top: 30px;">'.$colorterm->name.'<i></i></div></div>';
							}
							elseif(count($code) == 2){
								$colorcircle .= '<div data-colorid="'.$colorterm->term_id.'" class="circle" style="background:linear-gradient(to right,'.$code['0'].' 50% , '.$code['1'].' 50%);"><div class="tooltip_text" style="visibility: visible; z-index: 999999; margin-top: 30px;">'.$colorterm->name.'<i></i></div></div>';
								
							}
							/* foreach($code  as $colorcode){
								echo "<pre>"; print_r($colorcode);
							} */
						}
						else
						{
							$colorcircle .= '<div data-colorid="'.$colorterm->term_id.'" class="circle" style="background:'.$code.';"><div class="tooltip_text" style="margin-top: 30px;">'.$colorterm->name.'<i></i></div></div>';
						}
					}
					
				}
				else
				{
					$img = end(explode('/',$colorimagemeta[0]));
					$colorimg = explode('&',$img);
					$colorcircle .= '<div data-colorid="'.$colorterm->term_id.'" class="circle" style="background-image: url(\'/wp-content/uploads/colorswatches/'.$colorimg[0].'\');"><div class="tooltip_text" style="margin-top:30px;">'.$colorterm->name.'<i></i></div></div>';
				}
				
				?>
<td align="center"><?php echo $colorcircle; ?></td>
<?php } ?>
</tr>
</tbody>
</table>
</div>
<!-------  old scenerio <ul class="var_input">
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
					//echo "<pre>"; print_r($sizeattr_k); echo "</pre>";
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
								echo '<li class="color_all" style="visibility: hidden"><input class="blank_qty" readonly type="text" value="X"/></li>';
							}
							
						}
					}
					?>
					
				
			<?php echo '</li>'; }  ?>
			
			</ul> --->

</div>	

</div>
<div><a style="font-weight:bold; float:left;">Ordering more than 500 items? Call sales on 0808 109 6099</a></div>			  
<div class="btn-containerdiv" style="width:38%; float:right;">
			<div class="containerdiv1">
				<span class="items_add">0 item(s)</span>
				
			</div>
			<div class="containerdiv2">
				<div class="containerdiv2inner1">
			<span class="bottom woocommerce-Price-amount amount pr_cart_value"><span class="woocommerce-Price-currencySymbol">£</span><span class="intamt">0.00</span></span><br/>
				<?php if( !has_term( 'bulk-deals', 'product_cat', $product->get_id() ) ): ?>
					<span class="items_add1"><span class="woocommerce-Price-currencySymbol">£</span>0 per item</span>
				<?php endif; ?>
				</div>
				<button type="submit" class="single_add_to_cart_button canotadd alt btn btn-theme varient_button btn-block btn-outline wc-variation-selection-needed enabled">ADD TO BASKET <img src="/wp-content/uploads/2019/03/shopping-bag.png" style="width: 21px;" /></button>
				
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
			$first_prices = ( $tax === 'in' ) ? 1.2 * str_replace('£', '' , $first_price) : str_replace('£', '' , $first_price);
			$first_prices2 = ( $tax === 'in' ) ? 1.2 * str_replace('£', '' , $first_price2) : str_replace('£', '' , $first_price2);
			$first_prices3 = ( $tax === 'in' ) ? 1.2 * str_replace('£', '' , $first_price3) : str_replace('£', '' , $first_price3);
			$first_prices4 = ( $tax === 'in' ) ? 1.2 * str_replace('£', '' , $first_price4) : str_replace('£', '' , $first_price4);
			$first_prices5 = ( $tax === 'in' ) ? 1.2 * str_replace('£', '' , $first_price5) : str_replace('£', '' , $first_price5);
			$first_prices6 = ( $tax === 'in' ) ? 1.2 * str_replace('£', '' , $first_price6) : str_replace('£', '' , $first_price6);

			//echo "<pre>"; echo $characters .'<br/>' . $first_prices2 .'<br/>'. $first_prices3 .'<br/>'. $first_prices4 .'<br/>'. $first_prices5 .'<br/>'. $first_prices6 .'<br/>'; echo "</pre>"; die;

		?>
		<input type="hidden" value="<?php echo $product->get_id() ?>" class="product_id"/>
		<input type="hidden" value="<?php echo $first_prices; ?>" class="product_price1"/>
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
				
				$('.colour_table .circle').click(function(){
					$('.colour_table .circle').removeClass('selected');
					$(this).addClass('selected');
				})
			
				var li_box = $('.var_input li.select_size_quality').length;
				var li_width = 100;
				var variationadded = 0;
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
						var current = $(this).val();
						if( $(this).val() == '' )
						{
							var current = 0;
						}
						
						var q = parseInt(current);
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
					variationadded = totalitems;
						totalc = parseFloat(parseFloat(totalc) + parseFloat(oldtotalc)).toFixed(2);
						$('.items_add').html(totalitems+' item(s)');
						var intamt = $('.intamt').html(totalc);
						//var intamt = intamt.toFixed(1);
						//alert(totalitems);
						var peritem = 0;
						if(totalitems > 0){
						 peritem = parseFloat(parseFloat(totalc) / parseInt(totalitems)).toFixed(2);
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
					
				jQuery('.qty-increase, .qty_decrese').click(function() {
					if($(this).hasClass('qty_decrese') && $(this).prev().val() == 0){
						return false;
					}
					var totalc =0;
					var totalitems = 0;

					var q = ($(this).hasClass('qty-increase')) ? 1 : -1;
					$("input.varient_quantity").each(function() {
						var current = $(this).val();
						if($(this).val() == '')
						{
							var current = 0;
						}
						q = parseInt(q) + parseInt(current);

					});
					
					$("input.varient_quantity").each(function() {
						var current = $(this).val();
						if($(this).val() == '')
						{
							var current = 0;
						}
						//var q = parseInt($(this).val());
						
						//var c = $(this).parents('li.color_all').attr('data-cost');
						var c = $('.product_price1').val();
						
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
						
						if($('#tax_change').val() == 'invat')
						{
							
							var addtax = parseFloat(c) * parseFloat(20 / 100);
							
							c = parseFloat(c) + parseFloat(addtax);
						}
						
						if(q != 0 && q != '')
						{
							
							var cal = parseInt(q) * c;
						   // console.log(cal);
							var currenttotalc = parseFloat(totalc) + parseFloat(cal);
							
							totalc = cal;
						    //console.log(totalc);
						   //console.log(q);
						   var current_totalitems = parseInt(q);
						   
						   totalitems = current_totalitems;
						}
						
					    
					});
					variationadded = totalitems;
					
					var bulkdeal_count = $('.bulkdeal_count').val();
					
					if(bulkdeal_count!=''){
						
						var fixedbulkprice = $('.bulkdeal_price').val();
						
						//totalc = parseFloat(totalc).toFixed(1) + parseFloat(oldtotalc);
						
						//totalc = parseFloat(totalc)/parseInt(fixedbulkprice);
						$('.items_add').html(totalitems+' item(s)');
						$('.intamt').html(fixedbulkprice);
						  
							if(totalitems == bulkdeal_count ){
								
								peritem = parseFloat(totalc).toFixed(2) / parseInt(totalitems);
								//$('.intamt').html(fixedbulkprice)/peritem;
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
					   $('.items_add1').html('£'+peritem.toFixed(2)  +' per item');	
					}
					//alert(bulkdeal_count);
					if(bulkdeal_count==''){
						totalc = parseFloat(parseFloat(totalc) + parseFloat(oldtotalc)).toFixed(2);
						
						$('.items_add').html(totalitems+' item(s)');
						$('.intamt').html(totalc);
							//alert("dsfsdfsd");
							$('.bulkdeal_product').css('display','none');
							$('.bulkdeal_product').hide();
							if(totalitems > 0){
								
								peritem = parseFloat(totalc).toFixed(2) / parseInt(totalitems);
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
							//alert(peritem);
							$('.items_add1').html('£'+peritem.toFixed(2)  +' per item');
						}
						
					if(q == 0) $('.items_add1').html('£0 per item');
				});
				$("input.varient_quantity").keyup(function() {
					
					var totalc =0;
					var totalitems = 0;
					var q = 0;
					$("input.varient_quantity").each(function() {
						var current = $(this).val();
						if($(this).val() == '')
						{
							var current = 0;
						}
						q = parseInt(q)+parseInt(current);
					});
					$("input.varient_quantity").each(function() {
						var current = $(this).val();
						if($(this).val() == '')
						{
							current = 0;
						}
						//var q = parseInt($(this).val());
						
						//var c = $(this).parents('li.color_all').attr('data-cost');
						var c = $('.product_price1').val();
						
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
						
						if($('#tax_change').val() == 'invat')
						{
							
							var addtax = parseFloat(c) * parseFloat(20 / 100);
							
							c = parseFloat(c) + parseFloat(addtax);
						}
						
						if(q != 0 && q != '')
						{
							
							var cal = parseInt(q) * c;
						   // console.log(cal);
							var currenttotalc = parseFloat(totalc) + parseFloat(cal);
							
							totalc = cal;
						    //console.log(totalc);
						   //console.log(q);
						   var current_totalitems = parseInt(q);
						   
						   totalitems = current_totalitems;
						}
						
					    
					});
					variationadded = totalitems;
					
					var bulkdeal_count = $('.bulkdeal_count').val();
					
					if(bulkdeal_count!=''){
						
						var fixedbulkprice = $('.bulkdeal_price').val();
						
						//totalc = parseFloat(totalc).toFixed(1) + parseFloat(oldtotalc);
						
						//totalc = parseFloat(totalc)/parseInt(fixedbulkprice);
						$('.items_add').html(totalitems+' item(s)');
						$('.intamt').html(fixedbulkprice);
						  
							if(totalitems == bulkdeal_count ){
								
								peritem = parseFloat(totalc).toFixed(2) / parseInt(totalitems);
								//$('.intamt').html(fixedbulkprice)/peritem;
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
					   $('.items_add1').html('£'+peritem.toFixed(2)  +' per item');	
					}
					//alert(bulkdeal_count);
					if(bulkdeal_count==''){
						totalc = parseFloat(parseFloat(totalc) + parseFloat(oldtotalc)).toFixed(2);
						
						$('.items_add').html(totalitems+' item(s)');
						$('.intamt').html(totalc);
							//alert("dsfsdfsd");
							$('.bulkdeal_product').css('display','none');
							$('.bulkdeal_product').hide();
							if(totalitems > 0){
								
								peritem = parseFloat(totalc).toFixed(2) / parseInt(totalitems);
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
							//alert(peritem);
							$('.items_add1').html('£'+peritem.toFixed(2)  +' per item');
						}
									
						if(q == 0) $('.items_add1').html('£0 per item');
				});
				
				
				var productid = $('.product_id').val();
				$('.single_add_to_cart_button').click(function(){
				
					var varquantity = [];
					var varsizes = [];
					var colorids = [];
					var variantdata = {};
					var size = 0;
					$("input.varient_quantity").each(function() {
						var sizename = $(this).attr('data-sizename');
						if($(this).val() != '' && $(this).val() != 0){ 
						
							varquantity.push(parseInt($(this).val()));
							varsizes.push(sizename);
							size++;
						}
					});
					if($('.circle.selected').length){
					var colorid = $('.circle.selected').attr('data-colorid');
					
						colorids.push(colorid);
					
					}
					//console.log(colorids);
					variantdata['varquantity'] = varquantity;
					variantdata['colors'] = colorids;
					variantdata['sizes'] = varsizes;
					//console.log(variantdata);
					if(isEmpty(colorids))
					{
						alert('Please select color');
						return false;
						
					}
					if(isEmpty(varquantity))
					{
						alert('Please add at least one quantity');
						return false;
						
					}
					
					//alert(variationadded);
					var bulkdeal_count = $('.bulkdeal_count').val();
					if(bulkdeal_count != variationadded && bulkdeal_count != ''){
						alert('Please add '+bulkdeal_count+' quantity');
						return false;
					}
											
						$(this).attr('disabled',true);
						$('.single_add_to_cart_button').append('<div class="loader"></div>');
						$.ajax({
							url : '/wp-admin/admin-ajax.php',
							type : 'post',
							data : {
								'action' : 'addviewcart',
								'variantdata' : variantdata,
								'productid' : productid
							},
							success : function( response ) {
								$('.size_variations input[type="text"]').val(0);
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
		
		foreach($_POST['variantdata']['sizes'] as $key=>$size)
		{
			$sizeterm = get_term_by('name',$size,'pa_size');
			$colorterm = get_term_by('id',$_POST['variantdata']['colors'][0],'pa_color');
			$variation = filterVariations($productid,$sizeterm->slug, $colorterm->slug);
			// echo "<pre>"; print_r($_POST['variantdata']['varquantity'][$key]); echo "</pre>"; 
			$res = WC()->cart->add_to_cart( $productid , $_POST['variantdata']['varquantity'][$key] , $variation[0]->ID );
		}
		foreach($_POST['variantdata']['colors'] as $color)
		{
			$colorterm = get_term_by('id',$color,'pa_color');
		}
		
		//echo "<pre>"; print_r($abc); die;		
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

function filterVariations($productid,$size,$color) {
    $query = new WP_Query( array(
        'post_parent' => $productid,
        'post_status' => 'publish',
        'post_type' => 'product_variation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'   => 'attribute_pa_size',
                'value' => $size,
            ),
            array(
                'key'   => 'attribute_pa_color',
                'value' => $color,
            ),
        ),
    ) );
    $result = array();
    if($query->have_posts()){
        while ($query->have_posts()) {
            $query->next_post();
            $result[] = $query->post;
        }
        wp_reset_postdata();
    }
    wp_reset_query();

    return $result;
}

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
	//update_post_meta( $post_id,'_brandlogo',$product_data['brandlogo']);
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
	  
	/* $filename = str_replace('%2','',$filename);
	 $filename = str_replace(' ','',$filename);
	 $filename = str_replace('%20','',$filename);*/
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
	
	global $wpdb;
	$sku = get_post_meta($post->ID,'_sku',true);
$getpriceinfo = $wpdb->get_results('select * from wp_pricing_table where product_code="'.$sku.'"');
//echo "<pre>"; print_r($getpriceinfo); echo "</pre>"; 
$priceinfo = $getpriceinfo[0]->priceinfo;
$priceinfo = json_decode($priceinfo,true);
	
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
	woocommerce_wp_text_input(
		array(
            'id' => '_bulkprice',
			'class' => 'bulkprice',
            'placeholder' => 'Bulk Deal Price',
            'label' => __('Bulk Deal Price', 'woocommerce'),
            'desc_tip' => 'true'
        )
	);	
	woocommerce_wp_text_input(
		array(
            'id' => '_qty1to9_productc_qty',
			'class' => 'qtyproduct',
            'placeholder' => '1 to 9',
            'label' => __('Product Quantity', 'woocommerce'),
            'desc_tip' => 'true',
			'value' => get_post_meta( $post->ID, '_qty1to9_productc_qty', true ) //str_replace( '£', '', $priceinfo['1-9'] )
        )
	);
	woocommerce_wp_text_input(
		array(
            'id' => '_qty10to19_productc_qty',
			'class' => 'qtyproduct',
            'placeholder' => '10 to 19',
            //'label' => __('1 to 9', 'woocommerce'),
            'desc_tip' => 'true',
			'value' => get_post_meta( $post->ID, '_qty10to19_productc_qty', true ) // str_replace( '£', '', $priceinfo['10-19'] )
        )
	);
	woocommerce_wp_text_input(
		array(
            'id' => '_qty20to49_productc_qty',
			'class' => 'qtyproduct',
            'placeholder' => '20 to 49',
            //'label' => __('1 to 9', 'woocommerce'),
            'desc_tip' => 'true',
			'value' => get_post_meta( $post->ID, '_qty20to49_productc_qty', true ) // str_replace( '£', '', $priceinfo['20-49'] )
        )
	);
	woocommerce_wp_text_input(
		array(
            'id' => '_qty50to99_productc_qty',
			'class' => 'qtyproduct',
            'placeholder' => '50 to 99',
            //'label' => __('1 to 9', 'woocommerce'),
            'desc_tip' => 'true',
			'value' => get_post_meta( $post->ID, '_qty50to99_productc_qty', true ) // str_replace( '£', '', $priceinfo['50-99'] )
        )
	);
	woocommerce_wp_text_input(
		array(
            'id' => '_qty100to249_productc_qty',
			'class' => 'qtyproduct',
            'placeholder' => '100 to 249',
            //'label' => __('1 to 9', 'woocommerce'),
            'desc_tip' => 'true',
			'value' => get_post_meta( $post->ID, '_qty100to249_productc_qty', true ) // str_replace( '£', '', $priceinfo['100-249'] )
        )
	);
	woocommerce_wp_text_input(
		array(
            'id' => '_qty250to499_productc_qty',
			'class' => 'qtyproduct',
            'placeholder' => '250 to 499',
            //'label' => __('1 to 9', 'woocommerce'),
            'desc_tip' => 'true',
			'value' => get_post_meta( $post->ID, '_qty250to499_productc_qty', true ) // str_replace( '£', '', $priceinfo['250 to 499'] )
        )
	);
	/*woocommerce_wp_textarea_input(
        array(
            'id' => '_sizeguide',
			'class' => 'sizeguide',
            'placeholder' => 'Size Guide',
            'label' => __('Add Size Guide Info', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );*/
	
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

add_action('woocommerce_process_product_meta', 'woocommerce_product_bulkprice_save');
function woocommerce_product_bulkprice_save($post_id)
{
	/*$product = wc_get_product( $post_id );
	$meta_values = get_post_meta( get_the_ID() );
	echo "<pre>"; print_r($meta_values); die; */
    $woocommerce_custom_product_text_field = $_POST['_bulkprice'];
    if (!empty($woocommerce_custom_product_text_field))
	{
        update_post_meta($post_id, '_bulkprice', esc_attr($woocommerce_custom_product_text_field));
	}
	else{
		delete_post_meta($post_id,'_bulkprice');
	}
}

add_action('woocommerce_process_product_meta', 'woocommerce_product_qty1to9_save');
function woocommerce_product_qty1to9_save($post_id)
{ 
	global $post;
	global $wpdb;
	
	$sku = get_post_meta($post_id,'_sku',true);
	$getpriceinfo = $wpdb->get_results('select * from wp_pricing_table where product_code="'.$sku.'"');
    //echo "<pre>"; print_r($getpriceinfo); echo "</pre>";

	$arrayprice = array();
	$arrayprice['1-9'] = $_POST['_qty1to9_productc_qty'];
	$arrayprice['10-19'] = $_POST['_qty10to19_productc_qty'];
	$arrayprice['20-49'] = $_POST['_qty20to49_productc_qty'];
	$arrayprice['50-99'] = $_POST['_qty50to99_productc_qty'];
	$arrayprice['100-249'] = $_POST['_qty100to249_productc_qty'];
	$arrayprice['250 to 499'] = $_POST['_qty250to499_productc_qty'];
	$arrayprice['500+'] = 'Call us for a special price';
	
if(empty($getpriceinfo))
{
	$wpdb->insert( 'wp_pricing_table', 
			array( 
				'product_code'     => $sku,
				'priceinfo' => json_encode($arrayprice)
				
			), 
			array( 
				'%s',
				'%s'
			) 
		);
}	
else{
	
	$wpdb->update( 
		'wp_pricing_table', 
		array( 
			'priceinfo' => json_encode($arrayprice)	// integer (number) 
		), 
		array( 'product_code' => $sku ), 
		array( 
			'%s'
		), 
		array( '%s' ) 
	);
	
}


	//echo "<pre>"; print_r($sku); die;
	//echo "<pre>"; print_r($_POST); die;

   // echo "<pre>"; print_r(json_encode($arrayprice)); die;
  
	
}

add_action('woocommerce_process_product_meta', 'woocommerce_product_bulkdeal_save');

function woocommerce_product_bulkdeal_save($post_id)
{
  
    $woocommerce_custom_product_text_field = $_POST['_bulkdeal'];
    if (!empty($woocommerce_custom_product_text_field)) {
        update_post_meta($post_id, '_bulkdeal', esc_attr($woocommerce_custom_product_text_field));
	}
	else {
		delete_post_meta($post_id,'_bulkdeal');
	
	}
	
}

function woocommerce_product_brandlogo_save($post_id)
{
  
    $woocommerce_custom_product_text_field = $_POST['_brandlogo'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_brandlogo', esc_attr($woocommerce_custom_product_text_field));
	
	$woocommerce_custom_product_brandlogourl_field = $_POST['_brandlogourl'];
    if (!empty($woocommerce_custom_product_brandlogourl_field))
        update_post_meta($post_id, '_brandlogourl', esc_attr($woocommerce_custom_product_brandlogourl_field));
	
	$woocommerce_custom_product_sizeguide_field = $_POST['sizeguide_wysiwyg'];
	
	//echo "<pre>"; print_r($_POST); die;
	/*--------16-10-2019------*/
    /*if (!empty($woocommerce_custom_product_sizeguide_field))*/
        update_post_meta($post_id, 'sizeguide', esc_attr($woocommerce_custom_product_sizeguide_field)); 
 
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
		//$dealb = get_post_meta($product->id,'_qty1to9',true);

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
	$meta_name1 = 'double_color1';
	$meta_name2 = 'double_color2';
	
    if ( isset( $_POST[$meta_name] ) ) {
        $meta_value = $_POST[$meta_name];
        update_term_meta($term_id,'colorimage',$meta_value);
    }
	if ( $_POST[$meta_name1] != '' && $_POST[$meta_name2]!= ''  ) {
        $meta_value1 = $_POST[$meta_name1];
		$meta_value2 = $_POST[$meta_name2];
        update_term_meta($term_id,'color',array($meta_value1,$meta_value2));
    }
	
		
}

function pa_color_edit_form_fields ($term_obj) {
    // Read in the order from the options db
    $term_id = $term_obj->term_id;
	//echo "<pre>"; print_r($term_obj);
	$colorimage = get_term_meta( $term_id,'colorimage',true );
	 //echo ($term_id); echo  "check";
	$double_color1 = get_term_meta( $term_id,'color',true );
	
	//$double_color2 = get_term_meta( $term_id,'double_color2',true );
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
	<tr class="form-field">
            <th valign="top" scope="row">
                <label for="double_color"><?php _e('Double Color', ''); ?></label>
            </th>
            <td>
                <input type="text" id="double_color1" name="double_color1" value="<?php  echo $double_color1[0]; ?>"/>
				<input type="text" id="double_color2" name="double_color2" value="<?php echo $double_color1[1]; ?>"/>
				
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
	
	//if($_GET['qq']=1){
		//echo "hello";
	/* function getpricedata(){
		global $wpdb;
		$getpricedta = $wpdb->get_results("SELECT * FROM wp_pricing_table WHERE product_code=AA001 ");
		//echo "<pre>"; print_r($getpricedta);
	}
	getpricedata(); */
	//}
	add_action('wp_ajax_js_addproductstojsonfor_tshirt', 'js_addproductstojsonfor_tshirt' );
	add_action('wp_ajax_nopriv_js_addproductstojsonfor_tshirt', 'js_addproductstojsonfor_tshirt' );
	
function js_addproductstojsonfor_tshirt(){
 	
//echo $_GET['product_id']; die;
ini_set('display_errors',true);

require_once('/home/ppesupplies/public_html/wp-load.php');
$str = file_get_contents('https://ppesuppliesdirect.co.uk/tshirtecommerce/data/products.json');
$productid = $_GET['product_id']; //$_GET['parent'];
//$ab = json_decode($str);
//$bc = $ab->products[0]->design->front[0];
//echo $bc;
$getwcpostmeta =  get_post_meta($productid,'wc_productdata_options');

if($getwcpostmeta[0][0]['_product_id'] != '')
{
   echo "exist"; die;
}
$newproducts = json_decode($str);
$getlastkey =  count($newproducts->products); 
//echo "<pre>"; print_r($newproducts);
 global $woocommerce;
 //$myproduct = new WC_Product( $productid );
 $myproduct =  wc_get_product( $productid ); 
$checkexist = true; 

/*foreach($newproducts->products as $prod)
{ 
//echo "<pre>"; print_r($prod); die;
//echo $myproduct->get_sku(); 
//echo $prod->sku;
	if($myproduct->get_sku() == $prod->sku)
	{ 
		$checkexist = false;	
	}
}*/

if($checkexist) {
$size_vars = array();
$color_vars = array();

//echo $getlastkey;


 // echo "<pre>"; print_r($getwcpostmeta); die;
 $np = new stdClass();
 $np->title = $myproduct->get_name();
 $np->short_description = $myproduct->get_short_description();
 $np->description = $myproduct->get_description();
 $np->size = '';
 $np->image = get_the_post_thumbnail_url( $myproduct->get_id(), 'full' );
 $np->published = ($myproduct->get_status() == 'publish') ? 1 : 0;
 $np->sku = $myproduct->get_sku();
 $np->price = 0;
 $np->print_type = 'DTG';
 $np->min_order = 1;
 $np->max_oder = 1000;
 $np->sale_price = 0; 
 $np->prices = new stdClass();
 $np->prices->min_quantity = array('1');
 $np->prices->max_quantity = array('9999');
 $np->prices->price = array('');
 $np->tax = '';
 $np->dpioutput =300;
 $np->theme = '';
 $np->design = new stdClass();
 
 $colorsarray = array();
 $sizearray = array();
 $colortitlearra = array();

 //$tickets = new WC_Product_Variable( $productid );
 //echo "<pre>"; print_r($pvariations); die;
 $pvariations = $myproduct->get_available_variations();
 //echo "<pre>"; print_r($pvariations); die;
 foreach($pvariations as $variation ){
		 		$colorattr[$variation['attributes']['attribute_pa_color']] = $variation['attributes']['attribute_pa_color'];
				$sizeattr[$variation['attributes']['attribute_pa_size']] = $variation['attributes']['attribute_pa_size'];
			}
		
		foreach($sizeattr as $sizevar)
		{
			$colorterm = get_term_by('slug', $sizevar, 'pa_size');
		}
		foreach($colorattr as $colorvar){

				$colorterm = get_term_by('slug', $colorvar, 'pa_color');
				
				$colorimagemeta = get_term_meta($colorterm->term_id,'colorimage');
				
				if(empty($colorimagemeta) || $colorimagemeta[0] == '')
				{
					$colormeta = get_term_meta($colorterm->term_id,'color');
					
					foreach($colormeta as $code){
						if(is_array($code))
						{
							$ccode = $code['0'].$code['1'];
							
						}
						else
						{ 
							$ccode = $code;
						}
					}
					$ctitle = $colorterm->name;
				}
				else
				{
					$img = end(explode('/',$colorimagemeta[0]));
					$colorimg = explode('&',$img);
					$ccode  = $colorimg[0];
					$ctitle = $colorterm->name;
				}	
			$ccode = str_replace('#','',$ccode);
			$colorsarray[] = $ccode;
			$colortitlearra[] = $ctitle;			
		}
		
		
  $a1 = "{'0':{'id':'area-design'},'1':{'id':'images-1','width':'598px','height':'598px','top':'0px','left':'0px','zIndex':'auto','img':'https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-f15d337c154117592818998215610996285.png','is_product':1,'is_change_color':1}}";
  
  $a2 = "{'0':{'id':'area-design'},'1':{'id':'images-1','width':'598px','height':'598px','top':'0px','left':'0px','zIndex':'auto','img':'https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-c4fac8fb154117592860374177111001160.png','is_product':1,'is_change_color':1}}";
  
  $a3 = "{'0':{'id':'area-design'},'1':{'id':'images-1','width':'598px','height':'598px','top':'0px','left':'0px','zIndex':'auto','img':'https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-e9874147154117592874455294010225786.png','is_product':1,'is_change_color':1}}";
  
  $a4 = "{'0':{'id':'area-design'},'1':{'id':'images-1','width':'598px','height':'598px','top':'0px','left':'0px','zIndex':'auto','img':'https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-e9d36f80154117592860802243510253362.png','is_product':1,'is_change_color':1}}";
  		
		
 $pricearr = array();
 	  $frontarr = array();
	   $backarr = array();
	    $leftarr = array();
		 $rightarr = array();
 
 foreach($colorsarray as $coo){
	 $pricearr[] = '';
	  $frontarr[] = $a1;
	   $backarr[] = $a2;
	    $leftarr[] = $a3;
		 $rightarr[] = $a4;
 }
  $np->design->color_hex = $colorsarray;
  $np->design->color_title = $colortitlearra;
  $np->design->price = $pricearr;
 
  
  $np->design->front = $frontarr;
  $np->design->images_front = "['https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-f15d337c154117592818998215610996285.png']";
  $np->design->back = $backarr;
  $np->design->images_back = "['https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-c4fac8fb154117592860374177111001160.png']";
  $np->design->left = $leftarr;
  $np->design->images_left = "['https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-e9874147154117592874455294010225786.png']";
  $np->design->right =  $rightarr;
  $np->design->images_right = "['https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-e9d36f80154117592860802243510253362.png']";
  
  $np->design->params = new stdClass();
  $np->design->params->front = "{'page':'custom','width':'27.00','height':'30','lockW':true,'lockH':true,'shape':'circlesquare','shapeVal':'9'}";
  
  $np->design->params->back= "{'page':'custom','width':'27','height':'30','lockW':true,'lockH':true,'shape':'circlesquare','shapeVal':'8'}";
  
  $np->design->params->left = "{'page':'custom','width':'10','height':'10','lockW':true,'lockH':true,'shape':'circlesquare','shapeVal':'9'}";
  $np->design->params->right =  "{'page':'custom','width':'10','height':'10','lockW':true,'lockH':true,'shape':'circlesquare','shapeVal':'9'}";
  
  
  $np->design->area = new stdClass();
  $np->design->area->front = "{'width':230,'height':257,'left':'185px','top':'102px','radius':'9px','zIndex':''}";

   $np->design->area->back = "{'width':222,'height':245,'left':'189px','top':'83.5px','radius':'8px','zIndex':''}";
 
   $np->design->area->left = "{'width':98,'height':98,'left':'261px','top':'123.5px','radius':'9px','zIndex':''}";

	$np->design->area->right = "{'width':94,'height':94,'left':'241px','top':'128px','radius':'9px','zIndex':''}";

   
   $np->box_width = 600;
    $np->box_height = 600;
	$preid = $getlastkey - 1;
	 $np->id = $newproducts->products[$preid]->id + 1;
	 
	
	  $args = array(
	'post_type'     => 'product_variation',
	'post_status'   => array( 'private', 'publish' ),
	'numberposts'   => -1,
	'orderby'       => 'menu_order',
	'order'         => 'asc',
	'post_parent'   => $productid // get parent post-ID
 );


$variations = get_posts( $args );

$i = 0;
$variationdata = "";
foreach ( $variations as $variation ) {
	$i++;
	// get variation ID
	$variation_ID = $variation->ID;

	// get variations meta
	$product_variation = new WC_Product_Variation( $variation_ID );
	if(count($variations) == $i){
	 $variationdata .= '"'.$variation_ID.'":"'.$product_variation->regular_price.'"';
	}
	else{
		$variationdata .= '"'.$variation_ID.'":"'.$product_variation->regular_price.'",';
	}
	//echo $product_variation->regular_price;

}
//echo "{".$variationdata."}";

	  $np->prices_variations = "{".$variationdata."}";
	   $np->attributes = new stdClass(); 
	   $np->attributes->name = array('Size');
	   $sizearray = array();
	   foreach($sizeattr as $size) {
		   $sizearray[] = $size;
	   }
	    $np->attributes->titles = array($sizearray);
	
	//echo "<pre>"; print_r($sizeattr); die;

	$setpostmeta = array(array('_product_id'=>''.$np->id.'','_disabled_product_design'=>'','_product_title_img'=>''));
	//$metaser = serialize($setpostmeta);
	//echo $metaser;

	update_post_meta($productid,'wc_productdata_options',$setpostmeta);
	   
	  $newproducts->products[] = $np;
	  
   //echo '<pre>'; print_r($newproducts); die;
   //echo "<pre>"; print_r(json_encode($newproducts)); die;
$fp = fopen('/home/ppesupplies/public_html/tshirtecommerce/data/products.json', 'w');
//echo $fp;
fwrite($fp, json_encode($newproducts));
fclose($fp);
echo $np->id;
 die;
}

} //function end

function add_portwest_product_gallery_images()
{
	global $wpdb;
	$sku = $_GET['sku'];
	//echo "<pre>"; print_r($_POST); die;
$getid = $wpdb->get_results('select * from wp_portwestdata where sku="'.$sku.'"');

$pid = $getid[0]->id;

$jsondata = $wpdb->get_results('select * from wp_portwestdata where id > "'.$pid.'" order by id asc');

//echo "<pre>"; print_r($jsondata); die;
$k = 0;
foreach($jsondata as $data)
{
if($k > 100)
{
 die;
}	
	$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $data->sku ) );


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
	$images = json_decode($data->images);
  
	foreach ($images as $imgs)  
	{
			
		$id = attach_image('/'.$imgs, $data->sku, $product_id,$data->sku);
		if(is_int($id) )
		{
			$ids[] = $id;
		}
		
	}
//echo "<pre>"; print_r($ids); 
   echo $data->sku.',';
	update_post_meta($product_id, '_product_image_gallery', implode(',', $ids));  	
 // echo "<pre>"; print_r($product_id); die;
	
  }
  else
	  
	  {
		  echo "Product not found"; die;
	  }
	 
	  $k++;
  }
}
add_action('wp_ajax_add_portwest_product_gallery_images', 'add_portwest_product_gallery_images' ); // executed when logged in
add_action('wp_ajax_nopriv_add_portwest_product_gallery_images', 'add_portwest_product_gallery_images' ); // executed when logged out


include('sidebar_new.php');
