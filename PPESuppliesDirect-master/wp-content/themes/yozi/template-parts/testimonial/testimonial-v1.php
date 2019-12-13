<?php
   $job = get_post_meta( get_the_ID(), 'apus_testimonial_job', true );
   $link = get_post_meta( get_the_ID(), 'apus_testimonial_link', true );
?>
<div class="testimonial-body">
  <div class="clearfix">
    <div class="image">
       <?php the_post_thumbnail('widget'); ?>
    </div>
    <div class="info">
        <?php if (!empty($link)) { ?>
          <h3 class="name-client"><a href="<?php echo esc_url_raw($link); ?>"><?php the_title(); ?></a></h3>
        <?php } else { ?>
          <h3 class="name-client"><?php the_title(); ?></h3>
        <?php } ?>
        <span class="job text-theme"><?php echo sprintf(__('%s', 'yozi'), $job); ?></span>
    </div>
  </div>
  <div class="description">
      <?php the_excerpt(); ?>      
  </div>
</div>