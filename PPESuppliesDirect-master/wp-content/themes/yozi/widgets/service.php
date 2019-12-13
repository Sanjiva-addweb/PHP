<?php 
?>
<div class="widget widget-service">

    <?php
    foreach ($this->services as $key) {
    ?>

        <div class="loop-item">
            <div class="service-item">
                <?php if ( !empty($instance['service_icon_'.$key]) ) { ?>
                    <div class="icon">
                        <img class="img" src="<?php echo esc_url_raw($instance['service_icon_'.$key]); ?>" alt="">
                    </div>
                <?php } ?>
                <div class="content-left">
                    <?php if ( !empty($instance['service_title_'.$key]) ): ?>
                        <h3 class="title"><?php echo trim($instance['service_title_'.$key]); ?></h3>
                    <?php endif; ?>
                    <?php if ( !empty($instance['service_desc_'.$key]) ): ?>
                        <div class="des">
                            <?php echo trim($instance['service_desc_'.$key]); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    
    <?php
    }
?>
</div>