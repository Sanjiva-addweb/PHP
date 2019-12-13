<?php
/**
 * The template part for displaying results in search pages
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Yozi
 * @since Yozi 1.0
 */
?>

<article <?php post_class('post post-layout post-grid-v1'); ?>>
    <?php if (get_the_title()) { ?>
        <h4 class="entry-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h4>
    <?php } ?>
    
    <div class="entry-content no-thumb">
        <div class="entry-meta">
            <span class="date-post"><?php the_time( get_option('date_format', 'd M, Y') ); ?></span>
            <span class="comments"><?php comments_number( esc_html__('0 Comments', 'yozi'), esc_html__('1 Comment', 'yozi'), esc_html__('% Comments', 'yozi') ); ?></span>
        </div>
        <?php if (has_excerpt()) { ?>
            <div class="description"><?php the_excerpt(); ?></div>
        <?php } ?>
        <a class="btn btn-theme" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'yozi'); ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
    </div>
</article>