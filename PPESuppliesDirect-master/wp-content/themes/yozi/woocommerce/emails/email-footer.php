<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<style>
.email_footer , caption, .email_footer td , .email_footer tbody , .email_footer tfoot , .email_footer thead , .email_footer tr , .email_footer th{
		  margin: 0;
          padding: 0;
          outline: 0;
          vertical-align: baseline;
		  font-family: Lato;
		  
}
.email_footer{
	width:780px;
}
#pp_button{
	background-color:#000;
	font-family: Lato;
	color:#fff;
	font-size:13px;
	border-radius: 5px;
	text-align:center;
	width:95%;
	height:19px;
	margin:auto;
	padding-top:3px;
}
#pp_button:hover{
	color:#000;
}
.footer_div{
	padding-right:10px;
	padding-left:10px;
	padding-bottom:10px;
	width:850px;
	background-color:#e7e8e9;
	padding-top:10px;
}
p span 
{
    display: block;
}
#address_txt{
	font-family: Lato;
}

</style>
</div>
<div class="footer_div">
<table class="email_footer">
<tbody>
<tr>
<td style="width:180px;"><a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/11/ppe.png"  style="width:180px;"></a></td>
<td><div id="pp_button"><a style="text-decoration:none; color:#fff;" href="<?php echo get_site_url(); ?>/product-category/ppe/">PPE </a></div></td>
<td><div id="pp_button"><a style="text-decoration:none; color:#fff;" href="<?php echo get_site_url(); ?>/product-category/hi-vis/">HI-VIS</a></div></td>
<td><div id="pp_button"><a style="text-decoration:none; color:#fff;" href="<?php echo get_site_url(); ?>/product-category/workwear/">WORKWEAR</a></div></td>
<td><div id="pp_button"><a style="text-decoration:none; color:#fff;" href="<?php echo get_site_url(); ?>/product-category/footwear/">FOOTWEAR</a></div></td>
</tr>
<tr>
<td rowspan="2"style="width:240px;"><p id="address_txt"> <span>Unit 12, Hill Park Farm, Fairseat Lane</span>
<span>Sevenoaks, Kent, TN15 7PX</span>
<span>0808 109 6099</span>
info@ppesuppliesdirect.com
ppesuppliesdirect.com</p></td>
<td><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/11/free.png"  style="width:122px;"></td>
<td><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/11/30days.png"  style="width:122px;"></td>
<td><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/11/secure.png"  style="width:122px;"></td>
<td><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/11/delivery.png"  style="width:122px;"></td>
</tr>
<tr>
<td colspan="4"><a href="<?php echo get_site_url(); ?>/customisation-techniques/"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/11/footer@1.jpg"  style="width:600px;"></td>
</tr>
</tbody>
</table>
		</div>
	</body>
</html>
