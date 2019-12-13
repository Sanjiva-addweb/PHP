<?php
$title = $nav_menu = $el_class = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$items = (array) vc_param_group_parse_atts( $items );

if ( !empty($items) ):
	?>
	<div class="widget widget-banner-menu">
		<?php if ($title!=''): ?>
	        <h3 class="widget-title text-center">
	            <?php echo esc_attr( $title ); ?>
	        </h3>
	    <?php endif; ?>
		<div class="row">
			<?php 
				foreach ($items as $item): 
			?>
				<div class="item col-xs-12 col-sm-6 col-md-<?php echo esc_attr(12/$columns); ?>">
					<div class="banner-menu-inner">
						<div class="banner-top flex-middle">
							<?php if ( isset($item['image']) && $item['image'] ): 
								$img = wp_get_attachment_image_src($item['image'],'full');
								if ( !empty($img) && !empty($img[0]) ) { 
							?>
								<div class="banner-inner">
									<?php echo trim(yozi_get_attachment_thumbnail($item['image'], 'full')); ?>
								</div>
								<?php } ?>
							<?php endif; ?>
							<div class="right-inner">
							<?php if (isset($item['title']) && trim($item['title'])!='') { ?>
						    	<h3 class="title"><?php echo trim($item['title']); ?></h3>       
						    <?php } ?> 
						    <?php 
								$args = array(
								    'menu' => $item['nav_menu'],
								    'container_class' => '',
								    'menu_class' => 'menu-sub',
								    'fallback_cb' => '',
								    'walker' => new Yozi_Nav_Menu()
								);
								wp_nav_menu($args);
							?>
							</div>
					    </div>
					    
					</div>
			    </div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>