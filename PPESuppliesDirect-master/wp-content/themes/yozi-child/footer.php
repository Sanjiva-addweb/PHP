<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Yozi
 * @since Yozi 1.0
 */

$footer = apply_filters( 'yozi_get_footer_layout', 'default' );
$show_footer_desktop_mobile = yozi_get_config('show_footer_desktop_mobile', false);
$show_footer_mobile = yozi_get_config('show_footer_mobile', true);
$allbrands = get_terms( array(
    'taxonomy' => 'product_brand',
    'hide_empty' => false,
) );

?>

	<div class="megamenu-overlay"></div>

	</div><!-- .site-content -->



	<footer id="apus-footer" class="apus-footer <?php echo esc_attr(!$show_footer_desktop_mobile ? 'hidden-xs hidden-sm' : ''); ?>" role="contentinfo">
		<div class="footer-inner">
			<?php if ( !empty($footer) ): ?>
				<?php yozi_display_footer_builder($footer); ?>
			<?php else: ?>
				<div class="footer-default">
					<div class="apus-copyright">
						<div class="container">
							<div class="copyright-content clearfix">
								<div class="text-copyright pull-right">
									<?php
										
										$allowed_html_array = array( 'a' => array('href' => array()) );
										echo wp_kses(__('&copy; 2018 - Yozi. All Rights Reserved. <br/> Powered by <a href="//apusthemes.com">ApusThemes</a>', 'yozi'), $allowed_html_array);
									?>

								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</footer><!-- .site-footer -->

	<?php
	if ( yozi_get_config('back_to_top') ) { ?>
		<a href="#" id="back-to-top" class="add-fix-top">
			<i class="fa fa-angle-up" aria-hidden="true"></i>
		</a>
	<?php
	}
	?>
	
	<?php if ( is_active_sidebar( 'popup-newsletter' ) ): ?>
		<?php dynamic_sidebar( 'popup-newsletter' ); ?>
	<?php endif; ?>

	<?php
		if ( $show_footer_mobile ) {
			get_template_part( 'footer-mobile' );
		}
	?>

</div><!-- .site -->

<!-- <style>
	.summary-left.col-sm-8{
    width: 27.66667% !important;
	}
</style> -->
<script>
	/*jQuery(document).ready(function($){
		  $('.dropdown-toggle, .dropdown-menu').mouseover(function(){
			$('.table-visiable-dk').css("border-bottom","none");
			$('.overlay_color').css({"background-color":"rgba(0,0,0,0.5)","display":"block"});
		  });
		  
		  $('.dropdown-toggle, .dropdown-menu').mouseout(function(){
			  $('.overlay_color').css({"background-color":"rgba(0,0,0,0.5)","display":"none"});
		  });
		  
	  });*/
	  
	  jQuery(document).ready(function($){
		  $('.navbar-nav.megamenu li').mouseover(function(){
			  if($(this).find('.dropdown-menu').length){
				$('.table-visiable-dk').css("border-bottom","none");
				$('.megamenu-overlay').css({"background-color":"rgba(0,0,0,0.5)","display":"block"});
			  }
		  });
		  
		  $('.navbar-nav.megamenu li').mouseout(function(){
			  $('.megamenu-overlay').css({"background-color":"rgba(0,0,0,0.5)","display":"none"});
		  });

		  
	  });
	  jQuery( window ).load(function() {
	  	var hes = jQuery("#rev_slider_3_1").height();
	  	jQuery("#upper_sidebar").css("max-height",hes+"px");
   	  });
</script>



<?php wp_footer(); ?>



</body>
</html>