<?php
$post_format = get_post_format();
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="top-info">
        <div class="post-layout">
            <div class="categories"><?php yozi_post_categories($post); ?></div>
            <?php if (get_the_title()) { ?>
                <h4 class="entry-title">
                    <?php the_title(); ?>
                </h4>
            <?php } ?>
            <div class="entry-meta">
                <span class="date-post"><?php the_time( get_option('date_format', 'd M, Y') ); ?></span>
                <span class="comments"><?php comments_number( esc_html__('0 Comments', 'yozi'), esc_html__('1 Comment', 'yozi'), esc_html__('% Comments', 'yozi') ); ?></span>
            </div>
        </div>
        <?php if( $post_format == 'link' ) {
                $format = yozi_post_format_link_helper( get_the_content(), get_the_title() );
                $title = $format['title'];
                $link = yozi_get_link_attributes( $title );
                $thumb = yozi_post_thumbnail('', $link);
                echo trim($thumb);
            } else { ?>
            <div class="entry-thumb <?php echo  (!has_post_thumbnail() ? 'no-thumb' : ''); ?>">
                <?php
                    $thumb = yozi_post_thumbnail();
                    echo trim($thumb);
                ?>
            </div>
        <?php } ?>
    </div>
	<div class="entry-content-detail">
    	<div class="single-info info-bottom">
            <div class="entry-description clearfix">
                <?php
                    
                        the_content();
                    
                ?>
            </div><!-- /entry-content -->
    		<?php
    		wp_link_pages( array(
    			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'yozi' ) . '</span>',
    			'after'       => '</div>',
    			'link_before' => '<span>',
    			'link_after'  => '</span>',
    			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'yozi' ) . ' </span>%',
    			'separator'   => '',
    		) );
    		?>
    		<div class="tag-social clearfix">
                <div class="pull-left">
    			 <?php yozi_post_tags(); ?>
                </div>
                <div class="pull-right">
    			 <?php if( yozi_get_config('show_blog_social_share', false) ) {
    					get_template_part( 'template-parts/sharebox' );
    				} ?>
                </div>
    		</div>
    	</div>
    </div>
</article>