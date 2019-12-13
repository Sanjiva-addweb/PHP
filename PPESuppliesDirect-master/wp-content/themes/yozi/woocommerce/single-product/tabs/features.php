<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$features = get_post_meta( $post->ID, 'apus_product_features', true );
?>
<div class="features">
	<?php echo trim($features); ?>
</div>