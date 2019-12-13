<?php $thumbsize = !isset($thumbsize) ? yozi_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;?>
<article <?php post_class('post post-layout post-grid-v1'); ?>>
    <div class="categories"><?php yozi_post_categories($post); ?></div>
    <?php if (get_the_title()) { ?>
        <h4 class="entry-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h4>
    <?php } ?>
    <?php
        $thumb = yozi_display_post_thumb($thumbsize);
        echo trim($thumb);
    ?>
    <div class="entry-content <?php echo !empty($thumb) ? '' : 'no-thumb'; ?>">
        <div class="entry-meta">
            <span class="date-post"><?php the_time( get_option('date_format', 'd M, Y') ); ?></span>
            <span class="comments"><?php comments_number( esc_html__('0 Comments', 'yozi'), esc_html__('1 Comment', 'yozi'), esc_html__('% Comments', 'yozi') ); ?></span>
        </div>
        <?php if (! has_excerpt()) { ?>
            <div class="description"><?php echo yozi_substring( get_the_content(), 40, '...' ); ?></div>
        <?php } else { ?>
            <div class="description"><?php echo yozi_substring( get_the_excerpt(), 40, '...' ); ?></div>
        <?php } ?>
        <a class="btn btn-theme" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'yozi'); ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
    </div>
</article>