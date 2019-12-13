<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$args = array('#customer_login', '#customer_register');
$action = isset($_COOKIE['yozi_login_register']) && in_array($_COOKIE['yozi_login_register'], $args) ? $_COOKIE['yozi_login_register'] : '#customer_login';
?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
<style>
.col-md-3.col-sm-3.col-xs-12{
	display:none;
}
table, .table-bordered{
	border:none;
}
#apus-breadscrumb{
    display:none;
}
.title{
	    margin-top: 0px !important;
}
@media (max-width: 768px) {
.col-xs-12{
	    width: 1140px !important;
 }
}

</style>
<div class="main_wrapper">

<div class="user">

	<div id="customer_login" class="register_login_wrapper <?php echo trim($action == '#customer_login' ? 'active' : ''); ?>">
		<div class="log_in_title"><?php esc_html_e( 'RETURNING CUSTOMER?', 'yozi' ); ?></div>
		<form method="post" class="login" role="form">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="form-group form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Username or email address', 'yozi' ); ?> <span class="required">*</span></label>
				<input type="text" class="input-text form-control" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
			</p>
			<p class="form-group form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Password', 'yozi' ); ?> <span class="required">*</span></label>
				<input class="input-text form-control" type="password" name="password" id="password" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="form-group form-row">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<div class="form-group clearfix">
					<span for="rememberme" class="inline pull-left">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php esc_html_e( 'Remember me', 'yozi' ); ?>
					</span>
					<span class="lost_password pull-right">
						<a id="lost_password_txt"href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgotton Your Password?', 'yozi' ); ?></a>
					</span>
				</div>
				<input type="submit" class="btn btn-theme btn-block" name="login" value="<?php esc_html_e( 'LOG IN', 'yozi' ); ?>" />
			</div>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

	

	</div>




	<div id="customer_register" class="content-register register_login_wrapper <?php echo trim($action == '#customer_register' ? 'active' : ''); ?>">

		<h2 class="title"><?php esc_html_e( 'REGISTER', 'yozi' ); ?></h2>
		<form method="post" class="register widget" role="form">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="form-group form-row form-row-wide">
					<label for="reg_username"><?php esc_html_e( 'Username', 'yozi' ); ?> <span class="required">*</span></label>
					<input type="text" class="input-text form-control" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</p>

			<?php endif; ?>

			<p class="form-group form-row form-row-wide">
				<label for="reg_email"><?php esc_html_e( 'Email address', 'yozi' ); ?> <span class="required">*</span></label>
				<input type="email" class="input-text form-control" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="form-group form-row form-row-wide">
					<label for="reg_password"><?php esc_html_e( 'Password', 'yozi' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text form-control" name="password" id="reg_password" />
				</p>

			<?php endif; ?>


			<?php do_action( 'woocommerce_register_form' ); ?>

			<p class="form-group form-row">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<input type="submit" class="btn btn-primary btn-block" name="register" value="<?php esc_html_e( 'Register', 'yozi' ); ?>" />
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

		<div class="create text-center">
			<div class="line-border center">
				<span class="center-line"><?php echo esc_html__('or','yozi') ?></span>
			</div>
			<a class="login-account register-login-action" href="#customer_login"><?php echo esc_html__('LOG IN','yozi'); ?></a>
		</div>

	</div>


</div>

<div class="sign_up_form">

<table class="login_table">
<span id="create_acc_txt">CREATE ACCOUNT</span>
<tbody>
<tr>
<td><img src="https://ppesuppliesdirect.com/wp-content/uploads/2019/05/Tick.jpg"  style="width:30px;"></td>
<td>Manage and track your orders</td>
</tr>
<tr>
<td><img src="https://ppesuppliesdirect.com/wp-content/uploads/2019/05/Tick.jpg"  style="width:30px;"></td>
<td>Save delivery addresses</td>
</tr>
<tr>
<td><img src="https://ppesuppliesdirect.com/wp-content/uploads/2019/05/Tick.jpg"  style="width:30px;"></td>
<td>Download invoices</td>
</tr>
<tr>
<td><img src="https://ppesuppliesdirect.com/wp-content/uploads/2019/05/Tick.jpg"  style="width:30px;"></td>
<td>Download your customised products previews</td>
</tr>
<tr>
<td><img src="https://ppesuppliesdirect.com/wp-content/uploads/2019/05/Tick.jpg"  style="width:30px;"></td>
<td>Recieve exclusive discounts</td>
</tr>
</tbody>
</table>
<div class="register_txt">
			<div class="wrapper_log">
			<div class="line_log"></div>
					<div class="wordwrapper_log">
					<div class="word_log"><?php echo esc_html__('or','yozi') ?></div>                                        
					</div>
					</div>
				<a class="creat-account register-login-action" id="create_account" href="#customer_register"><?php echo esc_html__('REGISTER','yozi'); ?></a>
</div>

</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>