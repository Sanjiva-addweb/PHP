<div class="apus-search-form">
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
		<input type="hidden" name="post_type" value="product" class="post_type" />
		<div class="input-group">
			<input type="text" placeholder="<?php echo esc_attr(esc_html__( 'What do you need?', 'yozi' )); ?>" name="s" class="apus-search form-control"/>
			<span class="input-group-btn">
	  			<button type="submit" class="btn btn-theme radius-0"><i class="fa fa-search"></i></button>
			</span>
		</div>
	</form>
</div>