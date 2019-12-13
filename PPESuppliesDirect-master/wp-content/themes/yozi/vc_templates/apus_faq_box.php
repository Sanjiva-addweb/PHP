<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$items = (array) vc_param_group_parse_atts( $items );
if ( !empty($items) ):
$count = 0;
?>
	<div class="widget-faq-box clearfix <?php echo esc_attr($el_class); ?>">
		<?php if ($title!=''): ?>
			<div class="heading">
		        <h3 class="widget-title">
		            <span><?php echo esc_attr( $title ); ?></span>
			    </h3>
		    </div>
	    <?php endif; ?>
		<div class="content">
			<?php foreach ($items as $item): ?>
				<div class="fbox-content clearfix">  
			    	<?php if (isset($item['title']) && trim($item['title'])!='') { ?>
			            <h3 class="ourservice-heading"><?php echo trim($item['title']); ?></h3>
			        <?php } ?>
			         <?php if (isset($item['description']) && trim($item['description'])!='') { ?>
			            <div class="description"><?php echo trim( $item['description'] );?></div>  
			        <?php } ?>
			    </div> 
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>