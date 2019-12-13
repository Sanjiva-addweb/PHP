<?php
function get_allfilter_terms($termname)
{
		$category = get_queried_object();
		$args = array(
		'post_type'             => 'product',
		'post_status'           => 'publish',
		's' => $_GET['s'],
		'posts_per_page'        => 3000
	);
	if($category->term_id){
			$args['tax_query'] = array(
			array(
				'taxonomy'      => $category->taxonomy,
				'field' 		=> 'term_id', //This is optional, as it defaults to 'term_id'
				'terms'         => $category->term_id,
				'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
			)
		);
	}
	$products = new WP_Query($args);
	$attributes = array();
	foreach($products->posts as $productdata)
	{
		if($termname == 'size')
		{
			 $post_atts = get_the_terms($productdata->ID, 'pa_size');
				if ($post_atts) {
					foreach ($post_atts as $x) {
						if(!in_array($x, $attributes)){
							$attributes[]=$x;
						}
					}
				}
		}
		if($termname == 'color')
		{
			$post_atts = get_the_terms($productdata->ID, 'pa_color');
			if ($post_atts) {
				foreach ($post_atts as $x) {
					if(!in_array($x, $attributes)){
						$attributes[]=$x;
					}
				}
			}
		}
		if($termname == 'brand')
		{
			$post_atts = get_the_terms($productdata->ID, 'product_brand');
			if ($post_atts) {
				foreach ($post_atts as $x) {
					if(!in_array($x, $attributes)){
						$attributes[]=$x;
					}
				}
			}
		}
	}
	return $attributes;
}
function brand_name() {?>
		<dl id="narrow-by-list" class="sln-nav">
            <dt class="filter-title-brand odd">
                        <span>Brand</span>
			</dt>
			<dd class="filter-items-brand odd">
			<?php
				$terms = get_allfilter_terms('brand');
				if ( $terms ) {
					echo '<ol class="sln-type-checkbox">';
						foreach ( $terms as $term ) {
							echo '<li class="swatch-link">';
									echo '<input type="checkbox" class="filter_brand" value="'.$term->slug.'" /><a href="javascript:void(0);" class="' . $term->slug . '">';
										echo $term->name;
									echo '<span class="count-item"></span></a>';
							echo '</li>';
					}
					echo '</ol>';
				}
			?>
            </dd>
			</dl>
			<?php }
			add_shortcode( 'brand_nm', 'brand_name' );
			function gender_name() {
			?>
			<dl id="narrow-by-list" class="sln-nav">
            <dt class="filter-title-gender even">
                        <span>Gender</span>
            </dt>
            <dd class="filter-items-gender even">
                <ol class="sln-type-checkbox">
				<?php
				$terms = get_terms( array(
					'taxonomy' => 'pa_gender',
					'hide_empty' => false,
				) );
				foreach($terms as $term) {
			?>
                    <li class="swatch-link">
                        <input type="checkbox" class="filter_gender" value="<?php echo $term->slug ?>" /><a href="javascript:void(0);" class="<?php echo $term->slug ?>">
							<span class="count-item"><?php  echo $term->name; ?></span></a>
                    </li>
			<?php } ?>
                </ol>
            </dd>
			</dl>
			<?php }
			add_shortcode( 'gender_nm', 'gender_name' );
			function price_name() { ?>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" type="text/css" media="all" />
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript"></script>
			<dl id="narrow-by-list" class="sln-nav">
            <dt class="filter-title-price odd">
                        <span>Price</span>
            </dt>
            <dd class="filter-items-price odd">
				<div id="slider-range" class="price-filter-range" name="rangeInput"></div>
					<div style="margin:30px auto">
					  <input type="hidden"  id="min_price" value="" class="price_val_mm" />
					  <input type="hidden"  id="max_price" value="" class="price_val_mm" />
					</div>
            </dd>
			</dl>
			<?php }
			add_shortcode( 'price_nm', 'price_name' );
			function brand_size() {
				$terms = get_allfilter_terms('size');
			?>
			<dl id="narrow-by-list" class="sln-nav">
            <dt class="filter-title-size even">
                        <span>Size</span>
            </dt>
            <dd class="filter-items-size even">
                <ol class="sln-type-checkbox">
				<?php
				$sizeitr = 1;
				foreach( $terms as $term ) {
						$key = $term->name;
				?>
				<?php if($sizeitr == 20 ){ echo "<div class='showall hide'>"; }?>
                    <li class="swatch-link">
                        <input type="checkbox" class="filter_size" value="<?php echo $term->slug; ?>"/><a href="javascript:void(0);"><?php  echo $key; ?></a>
                    </li>

				<?php if($sizeitr == count($terms) && count($terms) >= 20 ){ echo '</div>'; } ?>
				<?php $sizeitr++; } ?>
				 <label onclick="viewall(this);" class="more" style="">More <i class="fa fa-sort-desc" aria-hidden="true"></i></label>
                <label onclick="lessall(this);" class="less" style="display: none;">Less <i class="fa fa-sort-asc" aria-hidden="true"></i></label>
                </ol>
            </dd>
			</dl>
			<?php }
			add_shortcode( 'brand_sz', 'brand_size' );
			function color_name() {
			$terms = get_terms( array(
				'taxonomy' => 'pa_colour',
				'hide_empty' => false,
			) );
			?>
			<dl id="narrow-by-list" class="sln-nav">
            <dt class="filter-title-color last odd">
                        <span>Colour</span>
            </dt>
            <dd class="filter-items-color last odd">
                <ul class="sln-type-checkbox">
				<?php
				$coloritr = 1;
					foreach( $terms as $term ) {
						$colormeta = get_term_meta($term->term_id,'color',true);
						$colorcircle = '<div class="circ" style="background-color: '.$colormeta.';"></div>';
						?>
                    <li class="swatch-link">
                        <input type="checkbox" class="filter_color" value="<?php echo $term->slug; ?>"/><a href="JavaScript:Void(0);">
						<?php echo str_replace('_',' ',$term->name); ?></a>
                    <span class="color-group">
					  <?php echo $colorcircle; ?>
                    </span>
                    </li>
					<?php
					} ?>
				</ul>
            </dd>
			</dl>

			<script type="text/javascript">
				viewall = function(e) {
				  jQuery(e).parents('ol').find('div.showall').removeClass( "hide" );
				  jQuery(e).hide();
				  jQuery(e).parent().find('label.less').show();
				 }
				 lessall = function(e) {
				  jQuery(e).parent().find('div.showall').addClass( "hide" );
				  jQuery(e).parent().find('label.more').show();
				  jQuery(e).hide();
				 }
			</script>
			<?php }
			add_shortcode( 'color_nm', 'color_name' );
function wpdocs_theme_name_scriptss() {
	$plugin_url = plugin_dir_url( __FILE__ )."newsidebarcss.css";
    wp_enqueue_style( 'newsidebarcss.css', $plugin_url );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scriptss' );
add_shortcode('newsibarshop','wpdocs_theme_name_scriptss');
function wppv_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
		wp_enqueue_script(
			'custom-script',
			$plugin_url.'js/custom_script.js',
			array( 'jquery' )
		);
}
add_action( 'wp_enqueue_scripts', 'wppv_load_plugin_css' );
function custom_advanced_search_query($query)
{
if($query->is_search()) {
    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
		$query->set('post_type',array('product', 'product_variation'));
        $query->set('meta_query', array(
		 array(
                'key' => '_price',
                'value' => $_GET['min_price'],
                'compare' => '>='
            ),
            array(
                'key' => '_price',
                'value' => $_GET['max_price'],
                'compare' => '<='
            )
		 )
		);
    }
    return $query;
}
}
add_action('pre_get_posts', 'custom_advanced_search_query', 1000);
?>
