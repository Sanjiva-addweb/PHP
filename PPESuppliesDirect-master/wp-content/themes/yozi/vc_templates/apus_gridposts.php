<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( empty($loop) ) return;

$this->getLoop($loop);
$args = $this->loop_args;
$posts_per_page = isset($args['posts_per_page']) ? $args['posts_per_page'] : 4;
if ( get_query_var( 'paged' ) ) {
    $paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
    $paged = get_query_var( 'page' );
} else {
    $paged = 1;
}
$args['paged'] = $paged;
$loop = new WP_Query($args);
set_query_var( 'thumbsize', $thumbsize );
?>

<div class="widget-blogs widget no-margin <?php echo esc_attr($layout_type); ?> <?php echo esc_attr($el_class); ?>">
    <?php if ($title!=''): ?>
        <h3 class="widget-title">
            <?php echo esc_attr( $title ); ?>
        </h3>
    <?php endif; ?>
    <div class="widget-content">
        <?php $columns = $grid_columns; ?>
        <?php $post_item = '_single'; ?>
        <?php if ( $layout_type == 'carousel' ): ?>
            <div class="slick-carousel slick-carousel-top" data-carousel="slick" data-items="<?php echo esc_attr($columns); ?>" data-smallmedium="2" data-extrasmall="1" data-pagination="false" data-nav="true">
                <?php while ( $loop->have_posts() ): $loop->the_post(); global $product; ?>
                    <div class="item">
                        <?php get_template_part( 'template-posts/loop/inner-grid-v2'); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php elseif ( $layout_type == 'grid' ): ?>
            <?php $bcol = 12/$columns; ?>
            <div class="layout-blog style-grid">
                <div class="row">
                    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                        <div class="col-md-<?php echo esc_attr($bcol); ?> col-sm-6 col-xs-12">
                            <?php get_template_part( 'template-posts/loop/inner-grid-v2' ); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php else: ?>
            <ul class="list-no">
                <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                    <li>
                        <?php get_template_part( 'template-posts/loop/inner-list' ); ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php if ( isset($show_pagination) && $show_pagination && $layout_type != 'carousel' ): ?>
        <?php yozi_pagination( $posts_per_page, $loop->found_posts, $loop->max_num_pages ); ?>
    <?php endif ; ?>
</div>
<?php wp_reset_postdata(); ?>