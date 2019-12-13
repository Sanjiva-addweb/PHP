<?php
get_header();
$sidebar_configs = yozi_get_blog_layout_configs();

$columns = yozi_get_config('blog_columns', 1);
$bscol = floor( 12 / $columns );
$_count  = 0;
yozi_render_breadcrumbs();
?>
<section id="main-container" class="main-content  <?php echo apply_filters('yozi_blog_content_class', 'container');?> inner">
	<?php yozi_before_content( $sidebar_configs ); ?>
	<div class="row">
		<?php yozi_display_sidebar_left( $sidebar_configs ); ?>

		<div id="main-content" class="col-sm-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
			<main id="main" class="site-main layout-blog" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header hidden">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php
				$layout = yozi_get_config( 'blog_display_mode', 'grid' );
				get_template_part( 'template-posts/layouts/'.$layout );

				// Previous/next page navigation.
				yozi_paging_nav();

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-posts/content', 'none' );

			endif;
			?>

			</main><!-- .site-main -->
		</div><!-- .content-area -->
		<?php yozi_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>
</section>
<?php get_footer(); ?>