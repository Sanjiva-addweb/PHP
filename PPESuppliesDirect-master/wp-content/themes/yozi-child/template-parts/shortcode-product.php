<?php

/**
 * Small product cards for home page
 */

$data =  get_query_var( 'product_data' );
$product_id = $data['product_id'];

// Vat logic
$tax = 'ex';
if( isset( $_COOKIE['vat'] ) ) {
    if( $_COOKIE['vat']  === 'in' ) {
        $tax = 'in';
    }elseif( $_COOKIE['vat']  === 'ex' ){
        $tax = 'ex';
    }
}
if( isset( $_GET['vat'] ) ) {
    if( $_GET['vat']  === 'in' ) {
        $tax = 'in';
    }elseif( $_GET['vat']  === 'ex' ){
        $tax = 'ex';
    }
}

if( $tax === 'in' ){
    $price = $data['price'] * 1.2;
}else{
    $price = $data['price'];
}

if( $data['border'] ) $border = $data['border'];
if( is_float( $price ) ){
	$pounds = explode( wc_get_price_decimal_separator(), $price )[0];
	$penny = explode( wc_get_price_decimal_separator(), round( $price, 2 ) )[1];
}
$img = $data['img'];
?>

<div class="widget widget-banner main_deal has-img">	
    <a href="<?php echo get_permalink( $product_id ); ?>">

    	<!--price-->
    	<div class="main_deal-price">
    		<div class="main_deal-price-val">
    			<?php
    				echo get_woocommerce_currency_symbol() . ' ';
    				echo ( is_float( $price ) && isset( $penny ) )  ? $pounds . '<sup class="main_deal-price-sup">.' . $penny . '</sup>'	: $pounds;
                    echo ( !is_float( $price ) )  ? $price : '';
    			?>	
    		</div>				
    	</div>
			
        <div class="image-wrapper image-loaded"<?php if( isset( $border ) ) echo ' style="border:1px solid ' . $border . '"'; ?>><?php echo $img; ?></div>          
     </a>
     <div class="infor"></div>
</div>