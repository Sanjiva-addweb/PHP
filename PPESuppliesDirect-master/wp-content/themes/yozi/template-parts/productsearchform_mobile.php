<?php if ( yozi_get_config('show_searchform') ):
	$class = yozi_get_config('enable_autocompleate_search', true) ? ' apus-autocompleate-input' : '';
?>
	<div class="apus-search-form">
		<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
			<div class="input-group">
				<input type="hidden" name="post_type" value="product" class="post_type" />
				<?php if ( yozi_get_config('enable_autocompleate_search', true) ) echo '<div class="twitter-typeahead">'; ?>
			  		<input type="text" placeholder="<?php echo esc_attr(esc_html__( 'What do you need?', 'yozi' )); ?>" name="s" class="apus-search form-control <?php echo esc_attr($class); ?>"/>
				<?php if ( yozi_get_config('enable_autocompleate_search', true) ) echo '</div>'; ?>
				<span class="input-group-btn">
					<button type="submit" class="btn btn-theme radius-0"><i class="fa fa-search"></i></button>
				</span>
			</div>
		</form>
	</div>
<?php endif; ?>