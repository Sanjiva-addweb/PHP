<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if (isset($categoriesbanners) && !empty($categoriesbanners)):
	$bcol = 12/$columns;
    $categoriesbanners = (array) vc_param_group_parse_atts( $categoriesbanners );
	?>
	<div class="widget-categorybanner widget">
		<?php if ($title!=''): ?>
	        <h3 class="widget-title">
	            <span><?php echo esc_attr( $title ); ?></span>
	        </h3>
	    <?php endif; ?>
		<div class="categorybanner <?php echo esc_attr($el_class.' '.$layout_type); ?>">
			<?php if ( $layout_type == 'carousel' ) { ?>
				<div class="slick-carousel slick-carousel-top" data-carousel="slick" data-items="<?php echo esc_attr($columns); ?>" <?php echo ($columns>=4)?'data-large="4"':''; ?> data-smallmedium="3" data-extrasmall="2" data-smallest="2" data-pagination="<?php echo esc_attr( $show_pagination ? 'true' : 'false' ); ?>" data-nav="<?php echo esc_attr( $show_nav ? 'true' : 'false' ); ?>">
				    <?php foreach ($categoriesbanners as $item) { ?>
				    	<?php
			    		$category = get_term_by( 'slug', $item['category'], 'product_cat' );
			    		if ( !empty($category) ) {
				    	?>	
				    		<div class="grid-banner-category grid-banner-category-large ">
						        <div class="category-wrapper">
						        	<a href="<?php echo esc_url(get_term_link($category)); ?>">
						                <?php
							                if ( isset($item['image']) && $item['image'] ) {
							                	echo trim(yozi_get_attachment_thumbnail($item['image'], 'full'));
							                }
						                ?>
					                	<h2 class="title">
					                		<?php if ( !empty($item['title']) ) { ?>
				                                <?php echo trim($item['title']); ?>
				                            <?php } else { ?>
				                                <?php echo trim($category->name); ?>
				                            <?php } ?>
				                        </h2>
					                </a>
						        </div>
					        </div>
			    	<?php } ?>
				    <?php } ?>
				</div>
			<?php } elseif ( $layout_type == 'grid' ) { ?>
				<div class="row grid-style-2 grid-banner-category ">
				    <?php $i=0; foreach ($categoriesbanners as $item) { ?>
				    	<?php
			    		$category = get_term_by( 'slug', $item['category'], 'product_cat' );
			    		if ( !empty($category) ) {
				    	?>
					        <div class="col-md-<?php echo esc_attr($bcol); ?>">
						        <div class="category-wrapper">
						        	<a href="<?php echo esc_url(get_term_link($category)); ?>">
						                <?php
							                if ( isset($item['image']) && $item['image'] ) {
							                	echo trim(yozi_get_attachment_thumbnail($item['image'], 'full'));
							                }
						                ?>
					                	<h2 class="title">
					                		<?php if ( !empty($item['title']) ) { ?>
				                                <?php echo trim($item['title']); ?>
				                            <?php } else { ?>
				                                <?php echo trim($category->name); ?>
				                            <?php } ?>
				                        </h2>
						            </a>
						        </div>
					        </div>
			    	<?php $i++; } ?>
				    <?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
endif;