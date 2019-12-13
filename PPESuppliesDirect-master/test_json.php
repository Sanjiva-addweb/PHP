<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
// $str = file_get_contents('https://ppesuppliesdirect.com/tshirtecommerce/data/products.json');
// $json = json_decode($str, true); 
// echo '<pre>';
// var_dump($json);
// echo '</pre>';

// $wc_product_id = 270924;
// $wc_product = wc_get_product( $wc_product_id );
// foreach( $wc_product->get_attributes() as $key => $attr ){
// 	if( $key === 'pa_size' ){
// 		$attributes = [];
// 		foreach( $attr->get_terms() as $term ){
// 			$attributes[] = $term->name;
// 		}
// 		break;
// 	}
// }
// echo '<pre>';
// var_dump( $attributes );
// echo '</pre>';
$result = get_categories(array(
	'taxonomy' => 'product_brand'
));
echo '<pre>';
var_dump( $result );
echo '</pre>';