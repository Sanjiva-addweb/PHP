<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
// type: deal, bestseller, new, toprated, recommended
if ( !isset($type) || !isset($categories) ) {
	return;
}

switch ($type) {
	case 'deal':
		$product_type = 'on_sale';
		break;
	case 'bestseller':
		$product_type = 'best_selling';
		break;
	case 'new':
		$product_type = 'recent_product';
		break;
	case 'toprated':
		$product_type = 'top_rate';
		break;
	case 'recommended':
		$product_type = 'recommended';
		break;
	case 'recently_viewed':
		$product_type = 'recently_viewed';
		break;
	default:
		$product_type = 'featured_product';
		break;
}

$title = yozi_get_config( 'products_'.$type.'_title' );
$number = yozi_get_config( 'products_'.$type.'_number', 12 );
$columns = yozi_get_config( 'products_'.$type.'_columns', 4 );
$layout_type = yozi_get_config( 'products_'.$type.'_layout', 'grid' );
$rows = yozi_get_config( 'products_'.$type.'_rows', 1 );
$item_style = yozi_get_config( 'products_'.$type.'_style', 'inner' );
$show_view_more = yozi_get_config( 'products_'.$type.'_show_view_more', false );
$view_more = yozi_get_config( 'products_'.$type.'_view_more' );
$vendor_id = 0;
if (class_exists('WCV_Vendors') && $type != 'recently_viewed') {
	$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
	$vendor_rating_page = urldecode( get_query_var( 'ratings' ) );
	$vendor_id = WCV_Vendors::get_vendor_id( $vendor_shop );
}

$args = array(
	'categories' => $categories,
	'product_type' => $product_type,
	'post_per_page' => $number,
	'author' => $vendor_id
);
$loop = yozi_get_products( $args );
if ( is_object($loop) && $loop->have_posts() ) : ?>

	<div class="widget product-top widget-<?php echo esc_attr($type.' widget-'.$layout_type); ?>">
		<?php if ($title) { ?>
			<div class="product-top-title">
				<h3 class="widget-title"><?php echo sprintf($title, $term_name); ?></h3>
				<?php if ($show_view_more) { ?>
					<a href="" class="view-more text-theme"><?php echo sprintf($view_more, $term_name); ?></a>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="widget-content woocommerce <?php echo esc_attr($layout_type.' '.$item_style);?>">
			<div class="<?php echo esc_attr( $layout_type ); ?>-wrapper">
				<?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array( 'loop' => $loop, 'columns' => $columns, 'rows' => $rows , 'product_item' => $item_style , 'show_nav' => 1 ) ); ?>
			</div>
		</div>
	</div>
<?php endif; ?>