<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$img = wp_get_attachment_image_src($image,'full');
$bg_color = $bg_color?'style="background-color:'. $bg_color .';"' : "";
if ( !empty($img) && isset($img[0]) ) $style = 'style="background:url('.esc_url_raw($img[0]).') repeat scroll 0 0 / 100% auto"';
?>
<div class="widget widget-action-box clearfix <?php echo esc_attr($el_class.' '.$style_box); ?>" <?php echo (!empty($style)) ? $style:''; ?>>
    <?php if($style_box != 'style2'){ ?>
    	<div class="action-inner">
        	<?php if ($title!=''): ?>
            <h3 class="title">
                <span><?php echo esc_attr( $title ); ?></span>
            </h3>
            <?php endif; ?>
            <?php if ($description!=''): ?>
            <div class="description">
                <?php echo esc_attr( $description ); ?>
            </div>
            <?php endif; ?>
            <?php if ($link!=''): ?>
            <a class="btn btn-theme btn-outline" href="<?php echo esc_url_raw($link ); ?>">
            	<?php if(!empty($text_button)){ ?>
                	<?php echo trim( $text_button ); ?>
                <?php }else{ ?>
                	<?php echo esc_html__('ORDER NOW','yozi') ?>
                <?php } ?>
            </a>
            <?php endif; ?>
        </div>
    <?php }else{ ?>
        <div class="action-v2 clearfix" <?php echo trim($bg_color); ?>>
            <?php 
                $image_box = wp_get_attachment_image_src($image_box,'full');
            ?>
            <?php if(!empty($image_box) && isset($image_box[0])){ ?>
                <div class="box-img col-xs-5">
                    <img src="<?php echo esc_url_raw($image_box[0]); ?>" alt="">
                </div>
            <?php }?>
            <div class="box-left col-xs-7">
                <?php if ($title!=''): ?>
                <h3 class="title">
                    <span><?php echo esc_attr( $title ); ?></span>
                </h3>
                <?php endif; ?>
                <?php if ($description!=''): ?>
                <div class="description">
                    <?php echo esc_attr( $description ); ?>
                </div>
                <?php endif; ?>
                <?php if ($link!=''): ?>
                <a class="btn btn-theme btn-outline" href="<?php echo esc_url_raw($link ); ?>">
                    <?php if(!empty($text_button)){ ?>
                        <?php echo trim( $text_button ); ?>
                    <?php }else{ ?>
                        <?php echo esc_html__('ORDER NOW','yozi') ?>
                    <?php } ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    <?php } ?>
</div>