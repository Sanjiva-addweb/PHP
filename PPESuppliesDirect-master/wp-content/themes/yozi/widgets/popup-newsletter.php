<div class="popupnewsletter hidden">
  <!-- Modal -->
  <button title="<?php echo esc_html('Close (Esc)', 'yozi'); ?>" type="button" class="mfp-close apus-mfp-close">×</button>
  <div class="row table-visiable-sm">
    <div class="col-sm-6 hidden-xs"><img src="<?php echo esc_url( $image ); ?>"></div>
    <div class="col-sm-6 col-xs-12">
      <div class="popupnewsletter-widget">
        <?php if(!empty($title)){ ?>
            <h3>
                <span><?php echo esc_html( $title ); ?></span>
            </h3>
        <?php } ?>
        
        <?php if(!empty($description)){ ?>
            <p class="description">
                <?php echo trim( $description ); ?>
            </p>
        <?php } ?>      
        <?php mc4wp_show_form(''); ?>
    </div>
    </div>
  </div>
   
</div>