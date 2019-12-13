<?php global $product; ?>
<div class="product-block shop-list-small shop-list-normal clearfix">
	<div class="content-left">
		<figure class="image">
			<?php yozi_product_image(); ?>
		</figure>
	</div>
	<div class="content-body">
		<h3 class="name">
			<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"><?php echo trim( $product->get_title() ); ?></a>
		</h3>
		<div class="rating clearfix">
			<?php
				$rating_html = wc_get_rating_html( $product->get_average_rating() );
            	if ( $rating_html ) {
            		echo trim( $rating_html );
            	} else {
            		echo '<div class="star-rating"></div>';
            	}
        	?>
	    </div>
		<span class="price"><?php echo trim($product->get_price_html()); ?></span>
	</div>
</div>