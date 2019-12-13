<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
?>
<div class="widget widget-newletter <?php echo esc_attr($el_class.' '.$style);?>">
	<div class="left-content">
	    <?php if ($title!=''): ?>
	        <h3 class="title">
	            <?php echo ( $title ); ?>
	        </h3>
	    <?php endif; ?>
	    <?php if (!empty($description)) { ?>
			<div class="description">
				<?php echo trim( $description ); ?>
			</div>
		<?php } ?>
	</div>
	<div class="content"> 
		<?php
			if ( function_exists( 'mc4wp_show_form' ) ) {
			  	try {
			  	    $form = mc4wp_get_form(); 
					mc4wp_show_form( $form->ID );
				} catch( Exception $e ) {
				 	esc_html_e( 'Please create a newsletter form from Mailchip plugins', 'yozi' );	
				}
			}
		?>
	</div>
</div>