<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'apus-woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

$layout = yozi_get_config('product_single_version', 'v1');
$thumbs_pos = yozi_get_config('product_thumbs_position', 'thumbnails-left');
$number_product_thumbs = yozi_get_config('number_product_thumbs', 4);
if ( $layout == 'v1' || $layout == 'v2' ) {
?>
	<div class="apus-woocommerce-product-gallery-wrapper">
		<?php
	    $onsale_price = yozi_onsale_price_show();
	    if ($onsale_price && $product->is_type( 'simple' )) {?>
	            <div class="downsale">-<?php echo wc_price($onsale_price); ?></div>
	    <?php } ?>

	    <?php
	      $video = get_post_meta( $post->ID, 'apus_product_review_video', true );

	      if (!empty($video)) {
	        ?>
	        <div class="video">
	          <a href="<?php echo esc_url($video); ?>" class="popup-video">
	            <i class="fa fa-play"></i>
	            <span class="text-theme"><?php echo esc_html__('Watch video', 'yozi'); ?></span>
	          </a>
	        </div>
	        <?php
	      }
	    ?>

		<div class="slick-carousel apus-woocommerce-product-gallery" data-carousel="slick-gallery" data-items="1" data-smallmedium="1" data-extrasmall="1" data-pagination="false" data-nav="false" data-asnavfor=".apus-woocommerce-product-gallery-thumbs" data-slickparent="true">
			<?php
			$attributes = array(
				'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			if ( has_post_thumbnail() ) {
				$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
				$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
				$html .= '</a></div>';
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'yozi' ) );
				$html .= '</div>';
			}

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

			do_action( 'woocommerce_product_thumbnails' );
			?>
		</div>
	</div>
	<div class="wrapper-thumbs">
		<div class="slick-carousel apus-woocommerce-product-gallery-thumbs <?php echo esc_attr($thumbs_pos == 'thumbnails-left' || $thumbs_pos == 'thumbnails-right' ? 'vertical' : ''); ?>" data-carousel="slick-gallery" data-items="<?php echo esc_attr($number_product_thumbs); ?>" data-smallmedium="<?php echo esc_attr($number_product_thumbs); ?>" data-extrasmall="4" data-smallest="4" data-pagination="false" data-nav="true" data-asnavfor=".apus-woocommerce-product-gallery" data-slidestoscroll="1" data-focusonselect="true" <?php echo trim($thumbs_pos == 'thumbnails-left' || $thumbs_pos == 'thumbnails-right' ? 'data-vertical="true"' : ''); ?>>
			<?php
			$attributes = array(
				'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			if ( has_post_thumbnail() ) {
				$html  = '<div class="woocommerce-product-gallery__image"><div class="thumbs-inner">';
				$html .= get_the_post_thumbnail( $post->ID, 'shop_thumbnail', $attributes );
				$html .= '</div></div>';
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder"><div class="thumbs-inner">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'yozi' ) );
				$html .= '</div></div>';
			}

			echo apply_filters( 'yozi_woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

			

			$attachment_ids = $product->get_gallery_image_ids();

			if ( $attachment_ids && has_post_thumbnail() ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
					$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
					
					$html  = '<div class="woocommerce-product-gallery__image"><div class="thumbs-inner">';
					$html .= wp_get_attachment_image( $attachment_id, 'shop_thumbnail', false );
			 		$html .= '</div></div>';

					echo apply_filters( 'yozi_woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
				}
			}

			?>
		</div>
	</div>
<?php } else { ?>
	<div class="apus-woocommerce-product-gallery-wrapper">
		<?php
	    $onsale_price = yozi_onsale_price_show();
	    if ($onsale_price && $product->is_type( 'simple' )) {?>
	            <div class="downsale">-<?php echo wc_price($onsale_price); ?></div>
	    <?php } ?>

	    <?php
	      $video = get_post_meta( $post->ID, 'apus_product_review_video', true );

	      if (!empty($video)) {
	        ?>
	        <div class="video">
	          <a href="<?php echo esc_url($video); ?>" class="popup-video">
	            <i class="fa fa-play"></i>
	            <span class="text-theme"><?php echo esc_html__('Watch video', 'yozi'); ?></span>
	          </a>
	        </div>
	        <?php
	      }
	    ?>
	    
		<div class="apus-woocommerce-product-gallery">
			<?php
			$attributes = array(
				'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			if ( has_post_thumbnail() ) {
				$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
				$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
				$html .= '</a></div>';
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'yozi' ) );
				$html .= '</div>';
			}

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

			$attachment_ids = $product->get_gallery_image_ids();

			if ( $attachment_ids && has_post_thumbnail() ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$full_size_image = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
					$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
					$attributes      = array(
						'title'                   => get_post_field( 'post_title', $attachment_id ),
						'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
						'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2],
					);

					$html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
			 		$html .= '</a></div>';

					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
				}
			}
			?>
		</div>
	</div>
<?php } ?>