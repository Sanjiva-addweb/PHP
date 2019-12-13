<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Yozi
 * @since Yozi 1.0
 */

get_header();
$sidebar_configs = yozi_get_blog_layout_configs();

$columns = yozi_get_config('blog_columns', 1);
$bscol = floor( 12 / $columns );
$_count  = 0;

yozi_render_breadcrumbs();
?>
<section id="main-container" class="main-content  <?php echo apply_filters('yozi_blog_content_class', 'container');?> inner">
	<div class="row">

		<div id="main-content" class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
			<main id="main" class="site-main layout-blog" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header hidden">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					?>
					<div class="col-sm-<?php echo esc_attr($bscol); ?>">
						<?php get_template_part( 'content', 'search' ); ?>
					</div>
					<?php
					$_count++;
				// End the loop.
				endwhile;

				// Previous/next page navigation.
				yozi_paging_nav();

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-posts/content', 'none' );

			endif;
			?>

			</main><!-- .site-main -->
		</div><!-- .content-area -->
		<div class="col-sm-3 col-xs-12 sidebar">
        	<?php if ( is_active_sidebar( 'sidebar-default' ) ): ?>
	   			<?php dynamic_sidebar('sidebar-default'); ?>
	   		<?php endif; ?>
           	
        </div>
		
	</div>
</section>
<?php get_footer(); ?>
