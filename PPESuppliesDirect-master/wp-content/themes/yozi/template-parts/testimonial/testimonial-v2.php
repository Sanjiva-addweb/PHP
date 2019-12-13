<?php
   $job = get_post_meta( get_the_ID(), 'apus_testimonial_job', true );
   $link = get_post_meta( get_the_ID(), 'apus_testimonial_link', true );
?>
<div class="testimonial-default" data-testimonial="content">
  <div class="testimonial-body layout3">
    <div class="description">
      <?php the_excerpt(); ?>      
    </div>
    <div class="clearfix top-inner">
      <div class="image">
        <?php
         if ( has_post_thumbnail() ) {
            the_post_thumbnail('80x80');
          } 
        ?>
      </div>
      <div class="info">
          <?php if (!empty($link)) { ?>
            <h3 class="name-client"><a href="<?php echo esc_url_raw($link); ?>"><?php the_title(); ?></a></h3>
          <?php } else { ?>
            <h3 class="name-client"><?php the_title(); ?></h3>
          <?php } ?>
          <span class="job"><?php echo sprintf(__('%s', 'yozi'), $job); ?></span>
      </div>
    </div>
  </div>
</div>