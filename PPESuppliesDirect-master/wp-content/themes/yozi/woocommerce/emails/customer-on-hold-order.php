<html>
<style>
body{
background-color:#f7f7f7;
}

#main_container{
width:100%;
}
#center_container{
margin-top:50px;
width:800px;
background-color:#fff;
box-shadow: 0px 6px 14px 0px rgba(0,0,0,0.3);
margin-right:auto;
margin-left:auto;
margin-bottom:30px;
}
#order_container{
width:500px;
margin-right:auto;
margin-left:auto;
padding-top:25px;
}
#image_left{

float:left
}
#order_heading{
    float:right;
    margin-top:75px;
	font-size: 24px;
	font-weight: 300;
    font-family: lato;
    padding-top:25px;
}
#date_time{
    font-size: 18px;
    margin:0px !important;
}
#great_news{
    font-size:35px;
	font-weight: 300;
    font-family: lato;
    color: #ca1527;
    margin: auto;
    width: 50%;
}
#order_complete {
    font-size:24px;
	font-weight: 300;
    font-family: lato;
    margin: auto;
    width: 70%;
}
#what_order{
font-family: lato;
margin-top:20px;
font-size:12px;
float:left
}
</style>
<body>
<div id="main_container">

<div id="center_container">

<div id="order_container">
<div id="great_news">ORDER ON HOLD</div>
<div id="order_complete">Some of the products you’ve ordered
are out of stock</div>
<div id="order_heading">
	<?php
	if ( $sent_to_admin ) {
		$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
	} else {
		$before = '';
		$after  = '';
	}
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( 'Order #%s', 'woocommerce' ) . $after . ' <p id="date_time"><time datetime="%s">%s</time></p>', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
	?>
</div>
<div id="image_left">
<img src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/11/Asset-14@1.5x.png"  style="width:300px;height:229px;">
</div>
<div>
<span> <p id="what_order">Thank you for placing your order with PPE Supplies Direct.
It seems like there is a problem with your order, we will get in touch with you shorty.</p></span>
</div>

<?php

/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
?>

</div>

</div>

</div>
</body>
</html>