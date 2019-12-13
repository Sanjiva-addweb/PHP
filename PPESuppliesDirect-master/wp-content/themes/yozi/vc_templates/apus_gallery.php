<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );


$images = (array) vc_param_group_parse_atts( $images );
if ( !empty($images) ):
	$bcol = 12/$columns;
	$_id = yozi_random_key();
?>	
	<div id="widget-gallery-<?php echo esc_attr($_id); ?>" class="widget widget-gallery <?php echo esc_attr($el_class.' '.$layout_type.' '.$gutter);?>">
	    <?php if ($title!=''): ?>
	        <h3 class="widget-title">
	            <span><?php echo esc_attr( $title ); ?></span>
	        </h3>
	    <?php endif; ?>
	    <div class="widget-content">
	    	<?php if ( $layout_type == 'grid' ) { ?>
				<div class="row gutter-default">
					<?php foreach ($images as $image): ?>
						<?php if ( !empty($image['image']) ) { ?>
		    				<div class="col-sm-<?php echo esc_attr($bcol); ?> col-xs-6">
								<?php
									$img_full = wp_get_attachment_image_src($image['image'], 'full');
								?>
								<?php if ( !empty($img_full) && isset($img_full[0]) ): ?>
									<div class="image">
			                    		<?php echo trim(yozi_get_attachment_thumbnail($item['image'], 'thumb')); ?>
				                    	<div class="content-cover">
				                    		<?php if ( !empty($image['title']) ) { ?>
				                    			<h4 class="title"><?php echo esc_html($image['title']); ?></h4>
				                    		<?php } ?>
				                    		<?php if ( !empty($image['description']) ) { ?>
				                    			<div class="description"><?php echo esc_html($image['description']); ?></div>
				                    		<?php } ?>
				                    		<a href="<?php echo esc_url_raw($img_full[0]); ?>" class="popup-image-gallery">
					                    		<i class="fa fa-search" aria-hidden="true"></i>
					                    	</a>
				                    	</div>
			                    	</div>
				                <?php endif; ?>
				        	</div>
			        	<?php } ?>
					<?php endforeach; ?>
				</div>
			<?php } else { ?>

				<?php
				    wp_enqueue_script( 'isotope-pkgd', get_template_directory_uri().'/js/isotope.pkgd.min.js', array( 'jquery' ) );
				    $columns = yozi_get_config('blog_columns', 1);
					$bcol = 3;
				?>
				<div class="isotope-items" data-isotope-duration="400" data-columnwidth=".col-md-<?php echo esc_attr($bcol); ?>">
					<div class="row gutter-default">
				    <?php $i = 0; foreach ($images as $image): ?>
			        	<div class="isotope-item col-xs-6 col-md-<?php echo esc_attr(($i == 0 && $layout_type == 'mansory') ? '6' : $bcol); ?>">
							<?php
								$img_full = wp_get_attachment_image_src($image['image'], 'full');
							?>
							<?php if ( !empty($img_full) && isset($img_full[0]) ): ?>
								<div class="image">
		                    		<?php echo trim(yozi_get_attachment_thumbnail($image['image'], 'full')); ?>
			                    	<div class="content-cover">
			                    		<?php if ( !empty($image['title']) ) { ?>
			                    			<h4 class="title"><?php echo esc_html($image['title']); ?></h4>
			                    		<?php } ?>
			                    		<?php if ( !empty($image['description']) ) { ?>
			                    			<div class="description"><?php echo esc_html($image['description']); ?></div>
			                    		<?php } ?>
			                    		<a href="<?php echo esc_url_raw($img_full[0]); ?>" class="popup-image-gallery">
				                    		<i class="fa fa-search" aria-hidden="true"></i>
				                    	</a>
			                    	</div>
		                    	</div>
			                <?php endif; ?>
			        	</div>
					<?php $i++; endforeach; ?>
					</div>
				</div>

			<?php } ?>
		</div>
	</div>
<?php endif; ?>