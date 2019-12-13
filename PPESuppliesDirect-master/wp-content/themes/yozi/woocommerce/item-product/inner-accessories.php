<?php 
global $product;
$product_id = $product->get_id();
?>

<div class="product-block shop-list-small list-accesories" data-product-id="<?php echo esc_attr($product_id); ?>">
	<div class="content-left">
		<figure class="image">
			<?php yozi_product_image(); ?>
		</figure>
	</div>
	<div class="content-body">
		<!-- categories -->
        <?php yozi_woo_display_product_cat( $product->get_id() ); ?>
        
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
<input autocomplete="off" checked type="checkbox" class="accessory-add-product" data-id="<?php echo esc_attr($product_id); ?>" data-product-type="<?php echo esc_attr($product->get_type()); ?>" data-price="<?php echo esc_attr($product->get_price()); ?>" />