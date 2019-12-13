<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

if (($handle = fopen('https://ppesuppliesdirect.com/prices.csv', 'r')) !== FALSE) {
	$i = 0; $a = 0;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
		$info = [];
        for ($c=0; $c < $num; $c++) {
			$info[$c] = $data[$c];
        }
		$sku = $info[0];
		$product_id = wc_get_product_id_by_sku( $sku );
		if( $product_id ){
			update_post_meta( $product_id, '_qty1to9_productc_qty', $info[1] );
			update_post_meta( $product_id, '_qty10to19_productc_qty', $info[2] );
			update_post_meta( $product_id, '_qty20to49_productc_qty', $info[3] );
			update_post_meta( $product_id, '_qty50to99_productc_qty', $info[4] );
			update_post_meta( $product_id, '_qty100to249_productc_qty', $info[5] );
			update_post_meta( $product_id, '_qty250to499_productc_qty', $info[6] );
			update_post_meta( $product_id, '_price', $info[6] );
			update_post_meta( $product_id, '_regular_price', $info[6] );
			$a++;
		}dssds
		$i++;
	}
    fclose($handle);
}
var_dump( $a );
var_dump( $i );