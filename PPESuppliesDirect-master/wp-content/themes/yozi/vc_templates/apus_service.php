<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$members = (array) vc_param_group_parse_atts( $members );
if($columns >= 2){
	$columns = ( isset($columns) && !empty($columns) )?'col-sm-6 col-md-'.(12/$columns):'';
} else {
	$columns = ( isset($columns) && !empty($columns) )?'col-sm-'.(12/$columns):'';
}
if ( !empty($members) ):
?>
	<div class="widget widget-service <?php echo esc_attr($el_class.' '.$style); ?>">
		<div class="row">
		    <?php foreach ($members as $item): ?>
		    <?php if ( isset($item['image']) && $item['image'] ) $image_bg = wp_get_attachment_image_src($item['image'],'full'); ?>
		    	<div class="loop-item col-xs-12 <?php echo esc_attr($columns); ?>">
		    		<div class="service-item">
			    		<?php if(isset( $image_bg[0]) && $image_bg[0] ) { ?>
			    			<div class="icon">
								<img class="img" src="<?php echo esc_url_raw($image_bg[0]); ?>" alt="">
							</div>
						<?php } ?>
						<div class="content-left">
							<?php if ( isset($item['title']) && !empty($item['title']) ): ?>
				            	<h3 class="title"><?php echo trim($item['title']); ?></h3>
				            <?php endif; ?>
				            <?php if ( isset($item['des']) && !empty($item['des']) ): ?>
				            	<div class="des">
				        			<?php echo trim($item['des']); ?>
				            	</div>
				         	<?php endif; ?>
			         	</div>
		         	</div>
	         	</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>