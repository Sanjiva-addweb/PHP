<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

if ( ! $post->post_excerpt ) {
	return;
}
$layout = yozi_get_config('product_single_version', 'v1');
?>
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
		
<div class="woocommerce-product-details__short-description-wrapper">
	<div class="woocommerce-product-details__short-description" >
	<div class="single_pricing_head">PRICING</div>
<div class="single_table_wrapper">
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

	$singlep = str_replace('£','',$priceinfo['1-9']); 
				
				$price10 = str_replace('£','',$priceinfo['10-19']);
				$price20 = str_replace('£','',$priceinfo['20-49']);
				$price50 = str_replace('£','',$priceinfo['50-99']);
				$price100 = str_replace('£','',$priceinfo['100-249']);
				$price250 = str_replace('£','',$priceinfo['250 to 499']);
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
	</div>
	
</div>