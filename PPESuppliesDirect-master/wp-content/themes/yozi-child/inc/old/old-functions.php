<?php
//$start_wp_theme_tmp



//wp_tmp


//$end_wp_theme_tmp

?><?php
/**
 * yozi functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Yozi
 * @since Yozi 1.2.0
 */


add_action( 'wp_enqueue_scripts', 'child_wp_enqueue_scripts', 110 );
function child_wp_enqueue_scripts() {
	wp_enqueue_style( 'parent-theme', get_template_directory_uri() .'/style.css' );
	wp_enqueue_style( 'child-theme', get_stylesheet_directory_uri() .'/style.css' );
	wp_enqueue_style( 'bootstrap-child', get_stylesheet_directory_uri() .'/css/bootstrap.css' );
	wp_enqueue_style( 'template-child', get_stylesheet_directory_uri() .'/css/template.css' );
	if( is_product() ){
		global $post;
		$product = wc_get_product( $post->ID );
		if( $product->is_type( 'simple' ) ){
			wp_enqueue_script( 'simple-product', get_stylesheet_directory_uri() .'/assets/js/simple-product.js', ['jquery'], null, true );
		}
	}
	if( is_cart() ){
		wp_enqueue_script( 'cart', get_stylesheet_directory_uri() .'/assets/js/cart.js', ['jquery'], null, true );
		wp_localize_script( 'cart', 'ajax', 
			array(
				'url' => admin_url('admin-ajax.php')
			)
		);
	}
	if( is_archive() && is_woocommerce() ){
		wp_enqueue_script( 'archive', get_stylesheet_directory_uri() .'/assets/js/archive.js', ['jquery'], null, true );
	}
	wp_enqueue_script( 'mini-cart', get_stylesheet_directory_uri() .'/assets/js/miniCart.js', ['jquery'], null, true );
	wp_localize_script( 'mini-cart', 'ajax', 
		array(
			'url' => admin_url('admin-ajax.php')
		)
	);
}

function iconic_find_matching_product_variation( $product, $attributes ) {
 global $product;
    foreach( $attributes as $key => $value ) {
        if( strpos( $key, 'attribute_' ) === 0 ) {
            continue;
        }
 
        unset( $attributes[ $key ] );
        $attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
    }
 
    if( class_exists('WC_Data_Store') ) {
 
        $data_store = WC_Data_Store::load( 'product' );
        return $data_store->find_matching_product_variation( $product, $attributes );
 
    } else {
 
        return $product->get_matching_variation( $attributes );
 
    }
 
}


function apply_var($product_id, $quantity, $variation_id, $variation){
$product_id   = 24;
$quantity     = 1;
$variation_id = 25;
$variation    = array(
	'Color' => 'Blue',
	'Size'  => 'Small',
);

//WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
}
function footer_js() {
global $product;
// $product->is_type( $type ) checks the product type, string/array $type ( 'simple', 'grouped', 'variable', 'external' ), returns boolean
if ( is_product() && $product->is_type( 'variable' ) ) {
?>
<style>
	p.price, div.quantity,label[for=quantity_v]{
		display:none !important;
	}
	div#ivpa-content{
		width: 20%;
		float: left;
		margin-top: 0;
	}
	div#ppom-box-2{
		width: 80%;
		float: left;
		clear: none;
	}
	
</style>

<!--<script>
	jQuery(function(){
		jQuery('.ivpa_active').each(function(){
			jQuery('').remove();
		});
	});
</script>-->
<?php
}
}
add_action( 'wp_footer', 'footer_js', 100 );

function home_vertical_menu($atts){ 
	?>
	<div class="vertical-wrapper">
		<div class="title-vertical bg-theme"><i class="fa fa-bars" aria-hidden="true"></i> <span class="text-title"><?php echo esc_html__('all Departments','yozi') ?></span> <i class="fa fa-angle-down show-down" aria-hidden="true"></i></div>
		<?php
			$args = array(
				'theme_location' => 'vertical-menu',
				'container_class' => 'content-vertical',
				'menu_class' => 'apus-vertical-menu nav navbar-nav',
				'fallback_cb' => '',
				'menu_id' => 'vertical-menu',
				'walker' => new Yozi_Nav_Menu()
			);
			wp_nav_menu($args);
		?>
	</div>
<?php       
 }                           
 add_shortcode( 'vertical_menu_dropdown', 'home_vertical_menu'  );
                  
function action_woocommerce_after_shop_loop_item(  ) { 
    global $product;
	if($product->is_type( 'variable' ))
	{
		/*$variations = $product->get_variation_attributes();
		$sku = get_post_meta($product->id,'_sku',true);
		$colorcircle = '';    
		$availablesizes = '';                         
		foreach($variations as $variationkey=>$variation)       
		{                   
			if($variationkey=='pa_color')
			{
				foreach($variation as $slug)
				{
					//$term = get_term_by('slug', $slug, $variationkey);
					
					$colorterm = get_term_by('slug', $slug, 'pa_color');
					
					$colorimagemeta = get_term_meta($colorterm->term_id,'colorimage');
					
					if(empty($colorimagemeta) || $colorimagemeta[0] == '')
					{
						$colormeta = get_term_meta($colorterm->term_id,'color');
						
						foreach($colormeta as $code){        
							if(is_array($code))
							{
								if(count($code) >2 )
								{
									$colorcircle .= '<div class="circ" style="background:linear-gradient(to right,'.$code['0'].', '.$code['1'].', '.$code['2'].');"></div>';
								}
								elseif(count($code) == 2){
									$colorcircle .= '<div class="circ" style="background:linear-gradient(to right,'.$code['0'].' 50% , '.$code['1'].' 50%);"></div>';
									
								}
								
							}
							else
							{
								$colorcircle .= '<div class="circ" style="background:'.$code.';"></div>';
							}
						}
						
					}
					else
					{
						$img = end(explode('/',$colorimagemeta[0]));
						$colorimg = explode('&',$img);
						$colorcircle .= '<div class="circ" style="background-image: url(\'/wp-content/uploads/colorswatches/'.$colorimg[0].'\');"></div>';
					}
				}
			} 
			elseif($variationkey=='pa_size')
			{
				
				foreach($variation as $slug)
				{
					$term = get_term_by('slug', $slug, $variationkey);
					
					$availablesizes .= $term->name.',';
				}
			}	
		}*/
		
		echo '<input type="hidden" class="product_vid" value="'.$product->id.'" />';
		/*echo '<div class="popoverlay">
			<div class="hoverpopup">
				<h2>Product info ('.$sku.')</h2>
				<div class="colorcontainer">
				<p>Available in '.count($variations['pa_color']).' colours</p>
				'.$colorcircle.'</div>
				<div class="sizecontainer">
				<p>Available in '.count($variations['pa_size']).' sizes</p>
				<div class="avaiblesize">'.$availablesizes.'</div></div>
				<div class="othercontainer"><p>24 hour express dispatch?</p>
				<span>Yes, this is a 24h express item.</span>
				<p>customisations:</p>
					<ul><li>Embroidery</li>
					<li>Logo printing</li>
					<li>Single colour printing</li>
					<li>Double colour printing</li>
		</ul>
				</div>
			</div>
		</div>';*/
	}
	//echo "<pre>"; print_r($variation_variations); echo "</pre>"; 
}; 
         
add_action( 'woocommerce_after_shop_loop_item_title', 'action_woocommerce_after_shop_loop_item', 15, 0 ); 

add_filter( 'manage_edit-product_brand_columns', 'set_custom_edit_product_brand_columns' );
function set_custom_edit_product_brand_columns($columns) {
  
    $columns['is_slider'] = __( 'Use in slider', 'your_text_domain' );

    return $columns;
}
add_filter( 'manage_product_brand_custom_column' , 'custom_product_brand_column', 10, 3 );
function custom_product_brand_column( $content,$column_name,$term_id ) {

    switch ( $column_name ) {             

        case 'is_slider' :
             echo '<input type="checkbox" name="is_slider[]" />';
            break;

    }
}
add_action( 'add_meta_boxes', 'create_custom_sizeguide_box' );
if ( ! function_exists( 'create_custom_sizeguide_box' ) )
{
    function create_custom_sizeguide_box()
    {
        add_meta_box(
            'custom_product_sizeguide_meta_box',
            __( 'Size Guide Information', 'cmb' ),
            'add_custom_sizeguide_content_meta_box',
            'product',
            'normal',
            'default'
        );
    }
}

//  Custom metabox content in admin product pages
if ( ! function_exists( 'add_custom_sizeguide_content_meta_box' ) ){
    function add_custom_sizeguide_content_meta_box( $post ){
        $prefix = '_bhww_'; // global $prefix;

        $productsku = get_post_meta($post->ID, '_sku', true);
		

	 $sizeinfometa = get_post_meta($post->ID,'sizeguide');
	 if(empty($sizeinfometa))
	 {
		 global $wpdb;
		$sizeinfo = $wpdb->get_results('Select * from wp_sizechart where product_code="'.$productsku.'" ');
			$sizeinfo = json_decode($sizeinfo[0]->size_info);

			 $sizedata = "<table class='sizeguide'>";
			 

			if($sizeinfo->other[0]->C != 'Size:'){
				$sizedata .= "<tr>";
			 foreach($sizeinfo->size as $i=>$info)
				{
					if($i>1 && $info !='')
			 		{
						$sizedata .= "<th>".$info."</th>";
					}
					
				}
			 $sizedata .= "</tr>";
			}
		foreach($sizeinfo->other as $otherinfo)
		{
			$sizedata .= "<tr>";
			foreach($otherinfo as $k=>$info)
			{
				if($k != 'A' && $k !='B' && $info != '')
				{
					if($otherinfo->C == 'Size:')
					{
						$sizedata .= "<th>".$info."</th>";
					}
					else
					{
						$sizedata .= "<td>".$info."</td>";
					}
					
				}
			}
			$sizedata .= "</tr>";
		} 
		$sizedata .= "</table>";
		
	}
	 else 
	 {
		 $sizedata = html_entity_decode($sizeinfometa[0]);
	 }

        $args['textarea_rows'] = 6;

        echo '<p>'.__( 'Size Guide info', 'cmb' ).'</p>';
        wp_editor( $sizedata, 'sizeguide_wysiwyg', $args );

        echo '<input type="hidden" name="custom_product_field_nonce" value="' . wp_create_nonce() . '">';


    }
}



//Save the data of the Meta field
add_action( 'save_post', 'save_custom_content_sizeguide_meta_box', 10, 1 );
if ( ! function_exists( 'save_custom_content_sizeguide_meta_box' ) )
{

    function save_custom_content_sizeguide_meta_box( $post_id ) {
        $prefix = '_bhww_'; // global $prefix;

        // We need to verify this with the proper authorization (security stuff).

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'custom_product_field_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'custom_product_field_nonce' ];

        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'product' == $_POST[ 'post_type' ] ){
            if ( ! current_user_can( 'edit_product', $post_id ) )
                return $post_id;
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }
		global $wpdb;
		$productsku = get_post_meta($post_id, '_sku', true);
		
		$sizeinfoid = $wpdb->get_results('Select id from wp_sizechart where product_code="'.$productsku.'" ');
		//echo "<pre>"; print_r($sizeinfoid); die;
		if(!empty($sizeinfoid))
		{
//echo wp_kses_post($_POST[ 'sizeguide_wysiwyg' ]); die;
		//echo "<pre>"; print_r($_POST); die;

			$wpdb->update( 
				'wp_sizechart', 
				array( 
					'sizehtml' => wp_kses_post($_POST[ 'sizeguide_wysiwyg' ])	// string
				), 
				array( 'id' => $sizeinfoid[0]->id ), 
				array( 
					'%s'
				), 
				array( '%d' ) 
			);
		}
		else
		{
			$wpdb->insert( 
				'wp_sizechart', 
				array( 
					'sizehtml' => wp_kses_post($_POST[ 'sizeguide_wysiwyg' ]),
					'product_code' => $productsku
				), 
				array( 
					'%s',
					'%s'
				) 
			);

		}
        // Sanitize user input and update the meta field in the database.
        //update_post_meta( $post_id, $prefix.'sizeguide_wysiwyg', wp_kses_post($_POST[ 'sizeguide_wysiwyg' ]) );
        
    }
}
/*
function addproductstojsonfor_tshirt($pid){
 	
//echo $_GET['product_id']; die;
ini_set('display_errors',true);

//require_once('/home/ppesupplies/public_html/wp-load.php');
$str = file_get_contents('https://ppesuppliesdirect.co.uk/tshirtecommerce/data/products.json');
$productid = $pid; //$_GET['parent'];
//$ab = json_decode($str);
//$bc = $ab->products[0]->design->front[0];
//echo $bc;
//echo "<pre>"; print_r($ab);die;

$newproducts = json_decode($str);
$getlastkey =  count($newproducts->products); 
 global $woocommerce;
 $myproduct = new WC_Product( $productid );
 //$myproduct =  wc_get_product( $productid ); 
$checkexist = true;

foreach($newproducts->products as $prod)
{ 
//echo "<pre>"; print_r($prod); die;
//echo $myproduct->get_sku(); 
//echo $prod->sku;
	if($myproduct->get_sku() == $prod->sku)
	{ 
		$checkexist = false;	
	}
}

if($checkexist) {
$size_vars = array();
$color_vars = array();

//echo $getlastkey;


 // echo "<pre>"; print_r($getwcpostmeta); die;
 $np = new stdClass();
 $np->title = $myproduct->get_name();
 $np->short_description = $myproduct->get_short_description();
 $np->description = $myproduct->get_description();
 $np->size = '';
 $np->image = get_the_post_thumbnail_url( $myproduct->get_id(), 'full' );
 $np->published = ($myproduct->get_status() == 'publish') ? 1 : 0;
 $np->sku = $myproduct->get_sku();
 $np->price = 0;
 $np->print_type = 'DTG';
 $np->min_order = 1;
 $np->max_oder = 1000;
 $np->sale_price = 0; 
 $np->prices = new stdClass();
 $np->prices->min_quantity = array('1');
 $np->prices->max_quantity = array('9999');
 $np->prices->price = array('');
 $np->tax = '';
 $np->dpioutput =300;
 $np->theme = '';
 $np->design = new stdClass();
 
 $colorsarray = array();
 $colortitlearra = array();
 
 $args = array(
	'post_type'     => 'product_variation',
	'post_status'   => array( 'private', 'publish' ),
	'numberposts'   => -1,
	'orderby'       => 'menu_order',
	'order'         => 'asc',
	'post_parent'   => $productid // get parent post-ID
 );


$pvariations = get_posts( $args );
 //$tickets = new WC_Product_Variable( $productid );
 echo "<pre>"; print_r($pvariations); die;
 foreach($pvariations as $variation ){
				
				$colorattr[$variation['attributes']['attribute_pa_color']] = $variation['attributes']['attribute_pa_color'];
			}
		

		foreach($colorattr as $colorvar){

				$colorterm = get_term_by('slug', $colorvar, 'pa_color');
				
				$colorimagemeta = get_term_meta($colorterm->term_id,'colorimage');
				
				if(empty($colorimagemeta) || $colorimagemeta[0] == '')
				{
					$colormeta = get_term_meta($colorterm->term_id,'color');
					
					foreach($colormeta as $code){
						if(is_array($code))
						{
							$ccode = $code['0'].$code['1'];
							
						}
						else
						{
							$ccode = $code;
						}
					}
					$ctitle = $colorterm->name;
				}
				else
				{
					$img = end(explode('/',$colorimagemeta[0]));
					$colorimg = explode('&',$img);
					$ccode  = $colorimg[0];
					$ctitle = $colorterm->name;
				}	
			$ccode = str_replace('#','',$ccode);
			$colorsarray[] = $ccode;
			$colortitlearra[] = $ctitle;			
		}
		
		
  $a1 = "{'0':{'id':'area-design'},'1':{'id':'images-1','width':'598px','height':'598px','top':'0px','left':'0px','zIndex':'auto','img':'https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-f15d337c154117592818998215610996285.png','is_product':1,'is_change_color':1}}";
  
  $a2 = "{'0':{'id':'area-design'},'1':{'id':'images-1','width':'598px','height':'598px','top':'0px','left':'0px','zIndex':'auto','img':'https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-c4fac8fb154117592860374177111001160.png','is_product':1,'is_change_color':1}}";
  
  $a3 = "{'0':{'id':'area-design'},'1':{'id':'images-1','width':'598px','height':'598px','top':'0px','left':'0px','zIndex':'auto','img':'https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-e9874147154117592874455294010225786.png','is_product':1,'is_change_color':1}}";
  
  $a4 = "{'0':{'id':'area-design'},'1':{'id':'images-1','width':'598px','height':'598px','top':'0px','left':'0px','zIndex':'auto','img':'https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-e9d36f80154117592860802243510253362.png','is_product':1,'is_change_color':1}}";
  		
		
 $pricearr = array();
 	  $frontarr = array();
	   $backarr = array();
	    $leftarr = array();
		 $rightarr = array();
 
 foreach($colorsarray as $coo){
	 $pricearr[] = '';
	  $frontarr[] = $a1;
	   $backarr[] = $a2;
	    $leftarr[] = $a3;
		 $rightarr[] = $a4;
 }
  $np->design->color_hex = $colorsarray;
  $np->design->color_title = $colortitlearra;
  $np->design->price = $pricearr;
 
  
  $np->design->front = $frontarr;
  $np->design->images_front = "['https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-f15d337c154117592818998215610996285.png']";
  $np->design->back = $backarr;
  $np->design->images_back = "['https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-c4fac8fb154117592860374177111001160.png']";
  $np->design->left = $leftarr;
  $np->design->images_left = "['https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-e9874147154117592874455294010225786.png']";
  $np->design->right =  $rightarr;
  $np->design->images_right = "['https://ppesuppliesdirect.com/tshirtecommerce//uploaded/tshirt/dg-designer-e9d36f80154117592860802243510253362.png']";
  
  $np->design->params = new stdClass();
  $np->design->params->front = "{'page':'custom','width':'27.00','height':'30','lockW':true,'lockH':true,'shape':'circlesquare','shapeVal':'9'}";
  
  $np->design->params->back= "{'page':'custom','width':'27','height':'30','lockW':true,'lockH':true,'shape':'circlesquare','shapeVal':'8'}";
  
  $np->design->params->left = "{'page':'custom','width':'10','height':'10','lockW':true,'lockH':true,'shape':'circlesquare','shapeVal':'9'}";
  $np->design->params->right =  "{'page':'custom','width':'10','height':'10','lockW':true,'lockH':true,'shape':'circlesquare','shapeVal':'9'}";
  
  
  $np->design->area = new stdClass();
  $np->design->area->front = "{'width':230,'height':257,'left':'185px','top':'102px','radius':'9px','zIndex':''}";

   $np->design->area->back = "{'width':222,'height':245,'left':'189px','top':'83.5px','radius':'8px','zIndex':''}";
 
   $np->design->area->left = "{'width':98,'height':98,'left':'261px','top':'123.5px','radius':'9px','zIndex':''}";

	$np->design->area->right = "{'width':94,'height':94,'left':'241px','top':'128px','radius':'9px','zIndex':''}";

   
   $np->box_width = 600;
    $np->box_height = 600;
	$preid = $getlastkey - 1;
	 $np->id = $newproducts->products[$preid]->id + 1;
	 
	
	 

$i = 0;
$variationdata = "";
foreach ( $variations as $variation ) {
	$i++;
	// get variation ID
	$variation_ID = $variation->ID;

	// get variations meta
	$product_variation = new WC_Product_Variation( $variation_ID );
	if(count($variations) == $i){
	 $variationdata .= '"'.$variation_ID.'":"'.$product_variation->regular_price.'"';
	}
	else{
		$variationdata .= '"'.$variation_ID.'":"'.$product_variation->regular_price.'",';
	}
	//echo $product_variation->regular_price;

}
//echo "{".$variationdata."}";

	  $np->prices_variations = "{".$variationdata."}";
	   $np->attributes = new stdClass(); 
	   
	$getwcpostmeta =  get_post_meta($productid,'wc_productdata_options');

	$setpostmeta = array(array('_product_id'=>''.$np->id.'','_disabled_product_design'=>'','_product_title_img'=>''));
	//$metaser = serialize($setpostmeta);
	//echo $metaser;

	update_post_meta($productid,'wc_productdata_options',$setpostmeta);
	   
	  $newproducts->products[] = $np;
	  
   //echo '<pre>'; print_r($newproducts); die;
   //echo "<pre>"; print_r(json_encode($newproducts)); die;
$fp = fopen('/home/ppesupplies/public_html/tshirtecommerce/data/products.json', 'w');
//echo $fp;
fwrite($fp, json_encode($newproducts));
fclose($fp);
}

} //function end
*/

//
function widget($atts) {
    
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
        'widget_name' => FALSE
    ), $atts));
    
    $widget_name = wp_specialchars($widget_name);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('widget','widget'); 


function get_productin_pop()
{
	$product_id = $_POST['pid'];
	$product =  wc_get_product( $_POST['pid'] ); 
	$variations = $product->get_variation_attributes();
		$sku = get_post_meta($product->id,'_sku',true);
		$currentName = get_the_title($sku);
		$colorcircle = '';    
		$availablesizes = '';                         
		foreach($variations as $variationkey=>$variation)       
		{                   
			if($variationkey=='pa_color')
			{
				foreach($variation as $slug)
				{
					//$term = get_term_by('slug', $slug, $variationkey);
					
					$colorterm = get_term_by('slug', $slug, 'pa_color');
					
					$colorimagemeta = get_term_meta($colorterm->term_id,'colorimage');
					
					if(empty($colorimagemeta) || $colorimagemeta[0] == '')
					{
						$colormeta = get_term_meta($colorterm->term_id,'color');
						
						foreach($colormeta as $code){        
							if(is_array($code))
							{
								if(count($code) >2 )
								{
									$colorcircle .= '<div class="circ" style="background:linear-gradient(to right,'.$code['0'].', '.$code['1'].', '.$code['2'].');"></div>';
								}
								elseif(count($code) == 2){
									$colorcircle .= '<div class="circ" style="background:linear-gradient(to right,'.$code['0'].' 50% , '.$code['1'].' 50%);"></div>';
									
								}
								
							}
							else
							{
								$colorcircle .= '<div class="circ" style="background:'.$code.';"></div>';
							}
						}
						
					}
					else
					{
						$img = end(explode('/',$colorimagemeta[0]));
						$colorimg = explode('&',$img);
						$colorcircle .= '<div class="circ" style="background-image: url(\'/wp-content/uploads/colorswatches/'.$colorimg[0].'\');"></div>';
					}
				}
			} 
			elseif($variationkey=='pa_size')
			{
				
				$args = array(
					'orderby'  => 'ids',
					'order'    => 'ASC',
					'fields' => 'names'
				);
			
				$availablesizes = implode( ', ', wc_get_product_terms( $product_id, 'pa_size', $args ) );
			}	
		}
		
		echo '<div class="popoverlay">
			<div class="hoverpopup">
			<div class="poupdata">
			<h2><p style="font-size:18px">'.$product->get_title().'</p> ('.$sku.')</h2>
				<div class="colorcontainer">
				<p>Product info:</p>
				<p>Available in '.count($variations['pa_color']).' colours</p>
				'.$colorcircle.'</div>
				<div class="sizecontainer">
				<p>Available in '.count($variations['pa_size']).' sizes</p>
				<div class="avaiblesize">'.$availablesizes.'</div></div>
				<p>customisations:</p>
					<ul><li>Embroidery</li>
					<li>Logo printing</li>
					<li>Single colour printing</li>
					<li>Double colour printing</li>
		</ul>
				</div></div>
				</div></div>
			';
	die;	
}
add_action('wp_ajax_get_productin_pop', 'get_productin_pop' );                                                            
add_action('wp_ajax_nopriv_get_productin_pop', 'get_productin_pop' ); // executed when logged out  



function addproductwithvar2() {

set_time_limit(0);
global $wpdb;


$abc = array(
'Amber'=>'Amber',
'Beige'=>'Beige',
'BkBk'=>'Black_Black',
'BkGrey'=>'Black_Grey',
'BkOr'=>'Black_Orange',
'Black'=>'Black', 
'BlackT'=>'Black_Triblend',
'BluBk'=>'blue_black',
'BluBlu'=>'blue_blue',
'Blue'=>'Blue',
'BotleT'=>'bottle_triblend',
'BottleG'=>'Bottle Green',
'Brown'=>'Brown',
'Clear'=>'Clear',
'Graphi'=>'Graphite',
'Green'=>'Green',
'GreenBk'=>'Black_Green',
'Grey'=>'Grey',
'GreyBk'=>'Grey_Black',
'GreyGreen'=>'Grey_Green',
'GreyGrey'=>'Grey_Grey',
'GreyWh'=>'GreyWhite',
'HosBlu'=>'Hospital_Blue',
'Khaki'=>'Khaki',
'Navy'=>'Navy',
'Navy T'=>'Navy Triblend',
'Orange'=>'Orange',
'OrBk'=>'Black_Orange',
'OrBlu'=>'Orange_Blue',
'OrOr'=>'Orange_Orange',
'Pink'=>'Pink',
'PurBlack'=>'Black_Purple',
'PurBlu'=>'Purple_Blue',
'Red'=>'Red',
'RedBk'=>'Red_Black',
'RedBlu'=>'Red_Blue',
'Royal'=>'Royal',
'RoyalT'=>'Royal Triblend',
'Silver'=>'Silver',
'Tan'=>'Tan',
'WhBlu'=>'White_Blue',
'WhGrey'=>'White_Grey',
'WhGrn'=>'Green_White',
'White'=>'White',
'WhRed'=>'White_Red',
'WhWh'=>'White_White',
'YeBk'=>'Yellow_Black',
'YeBlu'=>'Yellow_Blue',
'YeGrey'=>'Yellow_Grey',
'Yellow'=>'Yellow',
'YeOr'=>'Yellow_Orange',
'YeYe'=>'Yellow_Yellow'

);

$start = $_GET['sku'];
$number = $_GET['count'];

$getid = $wpdb->get_results('select * from wp_portwestdata where sku="'.$start.'"');

$pid = $getid[0]->id;

$jsondata = $wpdb->get_results('select * from wp_portwestdata where id > "'.$pid.'" order by id asc');

//echo "<pre>"; print_r($jsondata); die;
/*
foreach($jsondata as $data)
{
	$abc2 = json_decode($data->sizecolor);
	echo $data->sku.',';
	$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $data->sku ) );
  if ( $product_id ) 
  {	 
    //echo $product_id; 
  }
  
  $product = wc_get_product( $product_id );
  $available_variations = $product->get_available_variations();
  if(!empty($available_variations))
  {
	foreach($available_variations as $variation)
	  {
		  echo "<pre>"; print_r($variation['variation_id']); 
		  wp_delete_post($variation['variation_id']);
		  //die;
	  }
  }
  
} */
//echo "<pre>"; print_r($available_variations); die;
$jk = 0;
foreach($jsondata as $data)
{
  
  	$abc2 = json_decode($data->sizecolor);
	$description = json_decode($data->description);
	if($abc2 =='')
	{
	 echo $data->sku.',';
	}
/*	$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $data->sku ) );
  if ( $product_id ) 
  {	 
    echo $product_id; 
  }
  $feature= $description[5].'<ul class="features-text">';
  foreach($description as $key=>$features)
  {
	  if($key >= 6 && $features !='')
	  {
		  $feature .= '<li>'.$features.'</li>';
	  }
	  
  }
   $feature .= '</ul>';
  //echo "<pre>"; print_r($feature); die;
    $my_post = array(
      'ID'           => $product_id,
      'post_content' => $feature
  );

// Update the post into the database
  wp_update_post( $my_post ); 
  wp_set_post_terms($product_id, '1509' ,'product_brand');*/
  
}

die('success'); 
 foreach($jsondata as $data)
{ 
  if($data->onlineprice != '')
  {
  $prices = json_decode($data->onlineprice);
  update_post_meta( $product_id, '_qty1to9_productc_qty',  $prices[1]);
  update_post_meta( $product_id, '_qty10to19_productc_qty',  $prices[2]);
    update_post_meta( $product_id, '_qty20to49_productc_qty',  $prices[3]);
	 update_post_meta( $product_id, '_qty50to99_productc_qty',  $prices[4]);
	  update_post_meta( $product_id, '_qty100to249_productc_qty',  $prices[5]);
	    update_post_meta( $product_id, '_qty250to499_productc_qty',  $prices[6]);
  }
		
 $sizevalues = array();
$colorvalues = array(); 
foreach($abc2 as $varr)
{
	$sizevalues[] = $varr[5];
	$colorvalues[$abc[trim($varr[4])]] = $abc[trim($varr[4])];
 
}
//echo "<pre>"; print_r($sizevalues);
//echo "<pre>"; print_r($colorvalues); die;
$colorarr = array();
foreach($colorvalues as $color)
{
	$colorarr[] = $color;
}

 $product = wc_get_product( $product_id );
  $available_variations = $product->get_available_variations();
  if(!empty($available_variations))
  {
	foreach($available_variations as $variation)
	  {
		  echo "<pre>"; print_r($variation['variation_id']); 
		  wp_delete_post($variation['variation_id']);
		  //die;
	  }
  }

 wp_set_object_terms($product_id, $sizevalues, 'pa_size');
 wp_set_object_terms($product_id, $colorarr, 'pa_color');
 
 
$availableattr = array('size','color');
 $product_attributes_data = array();
    foreach ($availableattr as $attribute) // Loop round each attribute
    {
        $product_attributes_data['pa_'.$attribute] = array( // Set this attributes array to a key to using the prefix 'pa'

            'name'         => 'pa_'.$attribute,
            'value'        => '',
            'is_visible'   => '1',
            'is_variation' => '1',
            'is_taxonomy'  => '1'

        );
    }
	 update_post_meta($product_id, '_product_attributes', $product_attributes_data); 

foreach($abc2 as $varr)
{
  
	// The variation data
	$variation_data =  array(
		'attributes' => array(
			'size'  => $varr[5],
			'color' => $abc[trim($varr[4])],
		),
		'sku'           => '',
		'regular_price' => $varr[3],
		'sale_price'    => $varr[3],
		'stock_qty'     => 10,
	);


	// The function to be run
	create_product_variation( $product_id, $variation_data );

}

	if($jk == $number)
	{
		die;
	}
	$jk++;
 
}

die;
//echo "<pre>"; print_r($jsondata); die;
/*foreach($jsondata as $data)
{

	$abc = json_decode($data->description);
	$abc2 = json_decode($data->sizecolor);
	$product_data['description'] = $abc[5];
	$product_data['name'] = $abc[4];
	$product_data['sku'] = $abc[3];
	$product_data['categories'] = $abc[0];
	$product_data['mainimage'] = $abc2[19];
	if($product_data['description'] == '')
	{
		$product_data['description'] = $product_data['name'];
	}
$post = array( // Set up the basic post data to insert for our product

        'post_author'  => 3,
        'post_content' => $product_data['description'],
		'post_excerpt'   => $product_data['description'],
        'post_status'  => 'publish',
        'post_title'   => $product_data['name'],
        'post_parent'  => '',
        'post_type'    => 'product'
    );

    $post_id = wp_insert_post($post); // Insert the post returning the new post id
//echo "<pre>"; print_r($product_data); die;
    if (!$post_id) // If there is no post id something has gone wrong so don't proceed
    {
        return false;
    }

    update_post_meta($post_id, '_sku', $product_data['sku']); // Set its SKU
    update_post_meta( $post_id,'_visibility','visible'); // Set the product to visible, if not it won't show on the front end
	//update_post_meta( $post_id,'_brandlogo',$product_data['brandlogo']);
	//update_post_meta( $post_id,'_bulkdeal',$product_data['bulkdeal']);
	
    wp_set_object_terms($post_id, $product_data['categories'], 'product_cat'); // Set up its categories
    wp_set_object_terms($post_id, 'variable', 'product_type'); // Set it to a variable product type
	
	//add_brandof_product($post_id);
	
   // insert_product_attributes($post_id, $product_data['available_attributes'], $product_data['variations']); 
	
   // insert_product_variations($post_id, $product_data['variations']); // Insert variations passing the new post id & variations  


			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			$thumb_url = $product_data['mainimage'];
			

			// Download file to temp location
			$tmp = download_url( $thumb_url,300 );
			
			
if(!isset($tmp->errors) || empty($tmp->errors)){
			// Set variables for storage
			// fix file name for query strings
			preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $thumb_url, $matches);
			$file_array['name'] = basename($matches[0]);
			$file_array['tmp_name'] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
			$logtxt .= "Error: download_url error - $tmp\n";
			}else{
			$logtxt .= "download_url: $tmp\n";
			}

			//use media_handle_sideload to upload img:
			$thumbid = media_handle_sideload( $file_array, $post_id, 'gallery desc' );
			// If error storing permanently, unlink
			if ( is_wp_error($thumbid) ) {
			@unlink($file_array['tmp_name']);
			//return $thumbid;
			$logtxt .= "Error: media_handle_sideload error - $thumbid\n";
			}else{
			$logtxt .= "ThumbID: $thumbid\n";
			}

			set_post_thumbnail($post_id, $thumbid);
			
			}
			else
			{
			   echo $abc[3].',';	
			}


$i++; 

} */
echo "<pre>"; print_r($jsondata); die;

}

add_action('wp_ajax_addproductwithvar2', 'addproductwithvar2' ); // executed when logged in
add_action('wp_ajax_nopriv_addproductwithvar2', 'addproductwithvar2' ); // executed when logged out

function create_product_variation( $product_id, $variation_data ){
    // Get the Variable product object (parent)
    $product = wc_get_product($product_id);

    $variation_post = array(
        'post_title'  => $product->get_title(),
        'post_name'   => 'product-'.$product_id.'-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );

    // Creating the product variation
    $variation_id = wp_insert_post( $variation_post );

    // Get an instance of the WC_Product_Variation object
    $variation = new WC_Product_Variation( $variation_id );

    // Iterating through the variations attributes
    foreach ($variation_data['attributes'] as $attribute => $term_name )
    {
        $taxonomy = 'pa_'.$attribute; // The attribute taxonomy

        // If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
        if( ! taxonomy_exists( $taxonomy ) ){
            register_taxonomy(
                $taxonomy,
               'product_variation',
                array(
                    'hierarchical' => false,
                    'label' => ucfirst( $taxonomy ),
                    'query_var' => true,
                    'rewrite' => array( 'slug' => '$taxonomy') // The base slug
                )
            );
        }

        // Check if the Term name exist and if not we create it.
        if( ! term_exists( $term_name, $taxonomy ) )
            wp_insert_term( $term_name, $taxonomy ); // Create the term

        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug; // Get the term slug

        // Get the post Terms names from the parent variable product.
        $post_term_names =  wp_get_post_terms( $product_id, $taxonomy, array('fields' => 'names') );

        // Check if the post term exist and if not we set it in the parent variable product.
        if( ! in_array( $term_name, $post_term_names ) )
            wp_set_post_terms( $product_id, $term_name, $taxonomy, true );

        // Set/save the attribute data in the product variation
        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
    }

    // SKU
    if( ! empty( $variation_data['sku'] ) )
        $variation->set_sku( $variation_data['sku'] );

    // Prices
    if( empty( $variation_data['sale_price'] ) ){
        $variation->set_price( $variation_data['regular_price'] );
    } else {
        $variation->set_price( $variation_data['sale_price'] );
        $variation->set_sale_price( $variation_data['sale_price'] );
    }
    $variation->set_regular_price( $variation_data['regular_price'] );

    // Stock
    if( ! empty($variation_data['stock_qty']) ){
        $variation->set_stock_quantity( $variation_data['stock_qty'] );
        $variation->set_manage_stock(true);
        $variation->set_stock_status('');
    } else {
        $variation->set_manage_stock(false);
    }

    $variation->set_weight(''); // weight (reseting)

    $variation->save(); // Save the data
}

function wh_deleteProduct($id, $force = FALSE)
{
    $product = wc_get_product($id);

    if(empty($product))
        return new WP_Error(999, sprintf(__('No %s is associated with #%d', 'woocommerce'), 'product', $id));

    // If we're forcing, then delete permanently.
    if ($force)
    {
        if ($product->is_type('variable'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->delete(true);
            }
        }
        elseif ($product->is_type('grouped'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->set_parent_id(0);
                $child->save();
            }
        }

        $product->delete(true);
        $result = $product->get_id() > 0 ? false : true;
    }
    else
    {
        $product->delete();
        $result = 'trash' === $product->get_status();
    }

    if (!$result)
    {
        return new WP_Error(999, sprintf(__('This %s cannot be deleted', 'woocommerce'), 'product'));
    }

    // Delete parent product transients.
    if ($parent_id = wp_get_post_parent_id($id))
    {
        wc_delete_product_transients($parent_id);
    }
    return true;
}

function extraskudelete() {
	global $wpdb;
	$livearray = array('2085','2201','2202','2203','2204','2205','2206','2207','2208','2209','2802','2852','2860','2885','A001','A002','A003','A020','A050','A080','A100','A105','A109','A110','A111','A112','A113','A114','A115','A120','A121','A122','A125','A129','A130','A135','A140','A143','A145','A146','A150','A171','A174','A175','A185','A196','A197','A198','A199','A200','A210','A220','A225','A230','A245','A250','A251','A260','A270','A271','A280','A290','A300','A301','A302','A310','A315','A319','A320','A325','A330','A340','A350','A351','A352','A353','A354','A360','A400','A401','A427','A435','A445','A450','A460','A500','A501','A510','A511','A520','A530','A590','A600','A610','A620','A621','A622','A625','A630','A635','A640','A641','A643','A645','A655','A665','A667','A688','A689','A690','A691','A700','A710','A720','A721','A722','A723','A724','A725','A726','A729','A730','A735','A740','A745','A750','A780','A790','A795','A796','A800','A810','A827','A835','A845','A900','A905','A910','A915','A925','A941','AF22','AF50','AF53','AF73','AF82','AF83','AF84','AF91','AM10','AM11','AM12','AM13','AM14','AM15','AM20','AM22','AM23','AM24','AM30','AM31','AP01','AP02','AP30','AP31','AP32','AP50','AP52','AP60','AP62','AP70','AP80','AP81','AP90','AP91','AS10','AS11','AS20','AS21','B010','B013','B023','B024','B028','B029','B120','B121','B123','B130','B131','B133','B140','B151','B153','B175','B185','B195','B209','B210','B212','B300','B302','B307','B309','B310','B900','B901','B903','B904','B905','B907','B908','B909','B910','B912','B916','BIZ1','BIZ2','BIZ4','BIZ5','BIZ6','BIZ7','BP51','BP52','BP90','BT05','BT10','BT20','BZ11','BZ12','BZ30','BZ31','BZ40','C030','C052','C070','C071','C075','C078','C079','C099','C107','C276','C370','C375','C376','C387','C394','C405','C465','C466','C467','C468','C470','C471','C472','C473','C474','C475','C476','C481','C484','C485','C494','C496','C497','C565','C600','C676','C701','C703','C711','C720','C721','C730','C731','C733','C734','C771','C774','C775','C776','C802','C803','C806','C808','C811','C812','C813','C814','C820','C831','C833','C834','C836','C837','C851','C852','C854','C859','C865','C875','C876','C881','C887','C890','CH10','CH11','CH12','CP10','CP21','CR10','CR12','CS10','CS11','CS12','CS20','CS21','CV01','CV02','CV03','CV04','CW10','CW11','CW12','D100','D118','D300','D340','E040','E041','E042','E043','E044','E046','E048','E049','E052','E061','EP01','EP02','EP04','EP06','EP07','EP08','EP16','EP18','EP20','EP21','EP30','F171','F180','F205','F208','F280','F282','F285','F300','F301','F330','F400','F401','F414','F433','F440','F441','F450','F465','F474','F476','F477','F500','F813','FA10','FA11','FA12','FA21','FA22','FA23','FB30','FB31','FB40','FB41','FC01','FC02','FC03','FC04','FC10','FC11','FC12','FC14','FC21','FC41','FC44','FC50','FC52','FC53','FC54','FC55','FC57','FC60','FC61','FC62','FC63','FC64','FC65','FC66','FC67','FC86','FC87','FC88','FC89','FC90','FC94','FC95','FC96','FC97','FD01','FD02','FD09','FD10','FD11','FD15','FD85','FD90','FD95','FF50','FL02','FP02','FP05','FP08','FP10','FP11','FP12','FP13','FP14','FP15','FP18','FP19','FP20','FP21','FP22','FP23','FP26','FP27','FP28','FP29','FP30','FP34','FP35','FP36','FP39','FP40','FP41','FP44','FP45','FP48','FP49','FP50','FP51','FP52','FP62','FP63','FP64','FP65','FP66','FP67','FP68','FP98','FP99','FR01','FR02','FR03','FR06','FR09','FR10','FR11','FR12','FR14','FR18','FR19','FR20','FR21','FR22','FR25','FR26','FR27','FR28','FR30','FR31','FR35','FR36','FR37','FR38','FR41','FR43','FR46','FR47','FR50','FR51','FR52','FR53','FR55','FR56','FR57','FR58','FR59','FR60','FR61','FR62','FR63','FR70','FR71','FR72','FR73','FR74','FR75','FR76','FR77','FR78','FR79','FR80','FR81','FR85','FR89','FR90','FR91','FR92','FR93','FR98','FT12','FT13','FT15','FT25','FT50','FT63','FT64','FW01','FW02','FW03','FW04','FW05','FW06','FW07','FW08','FW09','FW10','FW11','FW12','FW13','FW14','FW15','FW16','FW17','FW18','FW19','FW20','FW21','FW22','FW23','FW24','FW25','FW26','FW28','FW29','FW30','FW31','FW32','FW33','FW34','FW35','FW36','FW37','FW38','FW39','FW40','FW41','FW42','FW43','FW44','FW45','FW46','FW47','FW48','FW49','FW51','FW57','FW58','FW59','FW60','FW61','FW62','FW63','FW64','FW65','FW66','FW67','FW68','FW69','FW71','FW74','FW75','FW80','FW81','FW82','FW83','FW84','FW85','FW86','FW87','FW88','FW89','FW90','FW92','FW93','FW94','FW95','G456','G465','G470','G475','G476','GL10','GL11','GL12','GL13','GL14','GL16','GT10','GT13','GT23','GT27','GT29','GT30','GT33','H440','H441','H442','H443','H444','H445','HA10','HA13','HA14','HB10','HB11','HF50','HV03','HV04','HV05','HV07','HV08','HV09','HV10','HV20','HV21','HV22','HV50','HV55','HV56','ID10','ID11','ID12','ID13','ID20','ID30','IW10','IW30','IW40','IW50','JN12','JN14','JN15','KN09','KN10','KN18','KN20','KN30','KN40','KN90','KN91','KN93','KP05','KP10','KP15','KP20','KP30','KP40','KP44','KP50','KP55','KS10','KS11','KS12','KS13','KS14','KS15','KS18','KS31','KS32','KS40','KS41','KS51','KS54','KS55','KS56','KS60','KS61','KS62','KS63','L440','L470','L474','L476','LJ20','LW12','LW13','LW14','LW15','LW16','LW20','LW30','LW56','LW63','LW70','LW71','LW72','LW97','MT50','MT51','MT52','MV25','MV26','MV27','MV28','MV29','MV35','MV36','MV91','MX28','NX50','P005','P100','P101','P102','P108','P153','P200','P201','P203','P209','P220','P223','P250','P251','P271','P291','P301','P303','P304','P309','P351','P371','P391','P410','P420','P430','P500','P516','P902','P906','P920','P921','P926','P940','P941','P946','P950','P952','P956','P970','P971','P976','PA01','PA02','PA04','PA10','PA30','PA31','PA45','PA46','PA48','PA49','PA50','PA52','PA54','PA55','PA56','PA58','PA60','PA61','PA62','PA63','PA64','PA65','PA66','PA67','PA68','PA69','PA91','PA99','PB10','PB55','PC55','PG54','PJ10','PJ20','PJ50','PJ52','PR32','PS04','PS05','PS11','PS12','PS16','PS21','PS30','PS32','PS33','PS34','PS40','PS41','PS42','PS44','PS46','PS47','PS48','PS50','PS51','PS52','PS53','PS54','PS55','PS58','PS59','PS63','PS90','PS91','PV50','PV54','PV60','PV64','PW11','PW13','PW14','PW15','PW17','PW18','PW20','PW21','PW22','PW23','PW24','PW25','PW26','PW30','PW31','PW32','PW33','PW34','PW340','PW35','PW36','PW37','PW370','PW371','PW38','PW39','PW40','PW41','PW42','PW43','PW45','PW47','PW48','PW50','PW51','PW53','PW54','PW55','PW56','PW57','PW58','PW59','PW60','PW65','PW66','PW68','PW69','PW79','PW80','PW81','PW89','PW90','PW91','PW92','PW93','PW94','PW96','PW97','PW98','PW99','R460','R473','R480','RT19','RT20','RT21','RT22','RT23','RT26','RT27','RT30','RT31','RT32','RT34','RT40','RT42','RT43','RT44','RT45','RT46','RT47','RT48','RT49','RT50','RT51','RT60','RT61','RT62','RT63','S068','S085','S092','S101','S102','S103','S104','S107','S108','S117','S118','S120','S121','S152','S156','S160','S161','S162','S166','S170','S171','S172','S173','S174','S177','S178','S190','S191','S231','S232','S250','S251','S266','S267','S271','S277','S278','S279','S350','S351','S360','S363','S364','S365','S375','S376','S378','S388','S410','S412','S413','S414','S415','S418','S419','S424','S425','S426','S427','S428','S429','S430','S431','S433','S434','S435','S437','S438','S440','S441','S450','S451','S452','S453','S460','S461','S462','S463','S464','S466','S467','S468','S469','S471','S475','S476','S477','S478','S479','S480','S481','S482','S483','S484','S485','S486','S487','S488','S489','S490','S491','S492','S493','S495','S496','S498','S499','S503','S505','S507','S521','S523','S530','S532','S533','S534','S535','S536','S538','S543','S544','S545','S553','S555','S560','S561','S562','S563','S570','S571','S572','S573','S578','S579','S585','S590','S591','S592','S597','S665','S686','S687','S710','S760','S766','S768','S769','S770','S771','S772','S773','S774','S775','S776','S777','S778','S779','S780','S781','S782','S783','S785','S787','S790','S791','S794','S795','S796','S810','S816','S817','S827','S837','S839','S840','S841','S843','S845','S849','S855','S862','S882','S884','S885','S886','S887','S889','S891','S894','S895','S896','S899','S900','S903','S916','S917','S918','S932','S934','S987','S996','S998','S999','SK08','SK10','SK11','SK12','SK13','SK18','SK20','SK33','SM10','SM15','SM20','SM30','SM31','SM33','SM40','SM45','SM50','SM60','SM61','SM63','SM70','SM75','SM80','SM90','SM91','SP01','ST11','ST20','ST30','ST31','ST35','ST36','ST38','ST40','ST41','ST42','ST43','ST44','ST45','ST47','ST50','ST60','ST70','ST80','ST85','SW10','SW20','SW32','SW33','T180','T181','T184','T185','T400','T402','T500','T501','T601','T602','T603','T620','T701','T702','T703','T704','T720','T750','T801','T802','T803','T820','T830','T831','T832','T900','TB02','TB10','TK40','TK41','TK50','TK51','TK52','TK53','TK54','TK83','TX10','TX11','TX12','TX13','TX14','TX15','TX16','TX17','TX18','TX19','TX20','TX22','TX23','TX30','TX32','TX33','TX36','TX37','TX39','TX40','TX45','TX50','TX51','TX52','TX55','TX60','TX61','TX62','TX70','TX71','TX72','VA120','VA198','VA199','VA310','VA350','VA620','VA622','Z456','Z464','Z465','Z524','Z529','Z530','Z531','Z533','Z534','Z541','Z543','Z550','Z551','Z580','Z583','Z586','Z587','Z600','Z601','Z610','Z611','Z612','Z613','Z620','Z622','Z630','Z635','Z636');
	$products = $wpdb->get_results(  "SELECT sku FROM wp_portwestdata"  );
	foreach($products as $gets)
	{
		$skus[] = $gets->sku;	
		}
	//echo "<pre>"; print_r($skus); die;
	$i = 0;
	foreach($skus as $sku)
	{ //if($i == 10){die;}
		if(!in_array($sku,$livearray))
		{
			//echo $sku; die;
		 $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
		  if ( $product_id ) 
		  {	 echo $sku.',';
			wh_deleteProduct($product_id, TRUE);
			//die($sku);
			//echo $product_id; 
		  }
		}
	}
  die('not');
	
}
add_action('wp_ajax_extraskudelete', 'extraskudelete' );                                                            
add_action('wp_ajax_nopriv_extraskudelete', 'extraskudelete' );

function add_sizeguideofportwest(){
	global $wpdb;
	
	$livearray = array('2085','2201','2202','2203','2204','2205','2206','2207','2208','2209','2802','2852','2860','2885','A001','A002','A003','A020','A050','A080','A100','A105','A109','A110','A111','A112','A113','A114','A115','A120','A121','A122','A125','A129','A130','A135','A140','A143','A145','A146','A150','A171','A174','A175','A185','A196','A197','A198','A199','A200','A210','A220','A225','A230','A245','A250','A251','A260','A270','A271','A280','A290','A300','A301','A302','A310','A315','A319','A320','A325','A330','A340','A350','A351','A352','A353','A354','A360','A400','A401','A427','A435','A445','A450','A460','A500','A501','A510','A511','A520','A530','A590','A600','A610','A620','A621','A622','A625','A630','A635','A640','A641','A643','A645','A655','A665','A667','A688','A689','A690','A691','A700','A710','A720','A721','A722','A723','A724','A725','A726','A729','A730','A735','A740','A745','A750','A780','A790','A795','A796','A800','A810','A827','A835','A845','A900','A905','A910','A915','A925','A941','AF22','AF50','AF53','AF73','AF82','AF83','AF84','AF91','AM10','AM11','AM12','AM13','AM14','AM15','AM20','AM22','AM23','AM24','AM30','AM31','AP01','AP02','AP30','AP31','AP32','AP50','AP52','AP60','AP62','AP70','AP80','AP81','AP90','AP91','AS10','AS11','AS20','AS21','B010','B013','B023','B024','B028','B029','B120','B121','B123','B130','B131','B133','B140','B151','B153','B175','B185','B195','B209','B210','B212','B300','B302','B307','B309','B310','B900','B901','B903','B904','B905','B907','B908','B909','B910','B912','B916','BIZ1','BIZ2','BIZ4','BIZ5','BIZ6','BIZ7','BP51','BP52','BP90','BT05','BT10','BT20','BZ11','BZ12','BZ30','BZ31','BZ40','C030','C052','C070','C071','C075','C078','C079','C099','C107','C276','C370','C375','C376','C387','C394','C405','C465','C466','C467','C468','C470','C471','C472','C473','C474','C475','C476','C481','C484','C485','C494','C496','C497','C565','C600','C676','C701','C703','C711','C720','C721','C730','C731','C733','C734','C771','C774','C775','C776','C802','C803','C806','C808','C811','C812','C813','C814','C820','C831','C833','C834','C836','C837','C851','C852','C854','C859','C865','C875','C876','C881','C887','C890','CH10','CH11','CH12','CP10','CP21','CR10','CR12','CS10','CS11','CS12','CS20','CS21','CV01','CV02','CV03','CV04','CW10','CW11','CW12','D100','D118','D300','D340','E040','E041','E042','E043','E044','E046','E048','E049','E052','E061','EP01','EP02','EP04','EP06','EP07','EP08','EP16','EP18','EP20','EP21','EP30','F171','F180','F205','F208','F280','F282','F285','F300','F301','F330','F400','F401','F414','F433','F440','F441','F450','F465','F474','F476','F477','F500','F813','FA10','FA11','FA12','FA21','FA22','FA23','FB30','FB31','FB40','FB41','FC01','FC02','FC03','FC04','FC10','FC11','FC12','FC14','FC21','FC41','FC44','FC50','FC52','FC53','FC54','FC55','FC57','FC60','FC61','FC62','FC63','FC64','FC65','FC66','FC67','FC86','FC87','FC88','FC89','FC90','FC94','FC95','FC96','FC97','FD01','FD02','FD09','FD10','FD11','FD15','FD85','FD90','FD95','FF50','FL02','FP02','FP05','FP08','FP10','FP11','FP12','FP13','FP14','FP15','FP18','FP19','FP20','FP21','FP22','FP23','FP26','FP27','FP28','FP29','FP30','FP34','FP35','FP36','FP39','FP40','FP41','FP44','FP45','FP48','FP49','FP50','FP51','FP52','FP62','FP63','FP64','FP65','FP66','FP67','FP68','FP98','FP99','FR01','FR02','FR03','FR06','FR09','FR10','FR11','FR12','FR14','FR18','FR19','FR20','FR21','FR22','FR25','FR26','FR27','FR28','FR30','FR31','FR35','FR36','FR37','FR38','FR41','FR43','FR46','FR47','FR50','FR51','FR52','FR53','FR55','FR56','FR57','FR58','FR59','FR60','FR61','FR62','FR63','FR70','FR71','FR72','FR73','FR74','FR75','FR76','FR77','FR78','FR79','FR80','FR81','FR85','FR89','FR90','FR91','FR92','FR93','FR98','FT12','FT13','FT15','FT25','FT50','FT63','FT64','FW01','FW02','FW03','FW04','FW05','FW06','FW07','FW08','FW09','FW10','FW11','FW12','FW13','FW14','FW15','FW16','FW17','FW18','FW19','FW20','FW21','FW22','FW23','FW24','FW25','FW26','FW28','FW29','FW30','FW31','FW32','FW33','FW34','FW35','FW36','FW37','FW38','FW39','FW40','FW41','FW42','FW43','FW44','FW45','FW46','FW47','FW48','FW49','FW51','FW57','FW58','FW59','FW60','FW61','FW62','FW63','FW64','FW65','FW66','FW67','FW68','FW69','FW71','FW74','FW75','FW80','FW81','FW82','FW83','FW84','FW85','FW86','FW87','FW88','FW89','FW90','FW92','FW93','FW94','FW95','G456','G465','G470','G475','G476','GL10','GL11','GL12','GL13','GL14','GL16','GT10','GT13','GT23','GT27','GT29','GT30','GT33','H440','H441','H442','H443','H444','H445','HA10','HA13','HA14','HB10','HB11','HF50','HV03','HV04','HV05','HV07','HV08','HV09','HV10','HV20','HV21','HV22','HV50','HV55','HV56','ID10','ID11','ID12','ID13','ID20','ID30','IW10','IW30','IW40','IW50','JN12','JN14','JN15','KN09','KN10','KN18','KN20','KN30','KN40','KN90','KN91','KN93','KP05','KP10','KP15','KP20','KP30','KP40','KP44','KP50','KP55','KS10','KS11','KS12','KS13','KS14','KS15','KS18','KS31','KS32','KS40','KS41','KS51','KS54','KS55','KS56','KS60','KS61','KS62','KS63','L440','L470','L474','L476','LJ20','LW12','LW13','LW14','LW15','LW16','LW20','LW30','LW56','LW63','LW70','LW71','LW72','LW97','MT50','MT51','MT52','MV25','MV26','MV27','MV28','MV29','MV35','MV36','MV91','MX28','NX50','P005','P100','P101','P102','P108','P153','P200','P201','P203','P209','P220','P223','P250','P251','P271','P291','P301','P303','P304','P309','P351','P371','P391','P410','P420','P430','P500','P516','P902','P906','P920','P921','P926','P940','P941','P946','P950','P952','P956','P970','P971','P976','PA01','PA02','PA04','PA10','PA30','PA31','PA45','PA46','PA48','PA49','PA50','PA52','PA54','PA55','PA56','PA58','PA60','PA61','PA62','PA63','PA64','PA65','PA66','PA67','PA68','PA69','PA91','PA99','PB10','PB55','PC55','PG54','PJ10','PJ20','PJ50','PJ52','PR32','PS04','PS05','PS11','PS12','PS16','PS21','PS30','PS32','PS33','PS34','PS40','PS41','PS42','PS44','PS46','PS47','PS48','PS50','PS51','PS52','PS53','PS54','PS55','PS58','PS59','PS63','PS90','PS91','PV50','PV54','PV60','PV64','PW11','PW13','PW14','PW15','PW17','PW18','PW20','PW21','PW22','PW23','PW24','PW25','PW26','PW30','PW31','PW32','PW33','PW34','PW340','PW35','PW36','PW37','PW370','PW371','PW38','PW39','PW40','PW41','PW42','PW43','PW45','PW47','PW48','PW50','PW51','PW53','PW54','PW55','PW56','PW57','PW58','PW59','PW60','PW65','PW66','PW68','PW69','PW79','PW80','PW81','PW89','PW90','PW91','PW92','PW93','PW94','PW96','PW97','PW98','PW99','R460','R473','R480','RT19','RT20','RT21','RT22','RT23','RT26','RT27','RT30','RT31','RT32','RT34','RT40','RT42','RT43','RT44','RT45','RT46','RT47','RT48','RT49','RT50','RT51','RT60','RT61','RT62','RT63','S068','S085','S092','S101','S102','S103','S104','S107','S108','S117','S118','S120','S121','S152','S156','S160','S161','S162','S166','S170','S171','S172','S173','S174','S177','S178','S190','S191','S231','S232','S250','S251','S266','S267','S271','S277','S278','S279','S350','S351','S360','S363','S364','S365','S375','S376','S378','S388','S410','S412','S413','S414','S415','S418','S419','S424','S425','S426','S427','S428','S429','S430','S431','S433','S434','S435','S437','S438','S440','S441','S450','S451','S452','S453','S460','S461','S462','S463','S464','S466','S467','S468','S469','S471','S475','S476','S477','S478','S479','S480','S481','S482','S483','S484','S485','S486','S487','S488','S489','S490','S491','S492','S493','S495','S496','S498','S499','S503','S505','S507','S521','S523','S530','S532','S533','S534','S535','S536','S538','S543','S544','S545','S553','S555','S560','S561','S562','S563','S570','S571','S572','S573','S578','S579','S585','S590','S591','S592','S597','S665','S686','S687','S710','S760','S766','S768','S769','S770','S771','S772','S773','S774','S775','S776','S777','S778','S779','S780','S781','S782','S783','S785','S787','S790','S791','S794','S795','S796','S810','S816','S817','S827','S837','S839','S840','S841','S843','S845','S849','S855','S862','S882','S884','S885','S886','S887','S889','S891','S894','S895','S896','S899','S900','S903','S916','S917','S918','S932','S934','S987','S996','S998','S999','SK08','SK10','SK11','SK12','SK13','SK18','SK20','SK33','SM10','SM15','SM20','SM30','SM31','SM33','SM40','SM45','SM50','SM60','SM61','SM63','SM70','SM75','SM80','SM90','SM91','SP01','ST11','ST20','ST30','ST31','ST35','ST36','ST38','ST40','ST41','ST42','ST43','ST44','ST45','ST47','ST50','ST60','ST70','ST80','ST85','SW10','SW20','SW32','SW33','T180','T181','T184','T185','T400','T402','T500','T501','T601','T602','T603','T620','T701','T702','T703','T704','T720','T750','T801','T802','T803','T820','T830','T831','T832','T900','TB02','TB10','TK40','TK41','TK50','TK51','TK52','TK53','TK54','TK83','TX10','TX11','TX12','TX13','TX14','TX15','TX16','TX17','TX18','TX19','TX20','TX22','TX23','TX30','TX32','TX33','TX36','TX37','TX39','TX40','TX45','TX50','TX51','TX52','TX55','TX60','TX61','TX62','TX70','TX71','TX72','VA120','VA198','VA199','VA310','VA350','VA620','VA622','Z456','Z464','Z465','Z524','Z529','Z530','Z531','Z533','Z534','Z541','Z543','Z550','Z551','Z580','Z583','Z586','Z587','Z600','Z601','Z610','Z611','Z612','Z613','Z620','Z622','Z630','Z635','Z636');
	$i =1;
	$result = $wpdb->get_results('select * from wp_portwestdata');
	foreach($result as $res){
		if(in_array($res->sku,$livearray))
		{ 
			$sizetable = array();
	     $sizedata = json_decode($res->sizecolor);
	     $sizeguide = "<table class='sizeguide'>";
		  $sizeguide .='<th>Size</th>';
		  $sizeguide .='<th>Length</th>';
		  $sizeguide .='<th>Width</th>';
		  $sizeguide .='<th>Height</th>';
		 
			foreach($sizedata as $sized){
				if($sized[5] !='')
				{
					
				$length = $sized[10];
				$width = $sized[11];
				$height = $sized[12];
				
				$sizetable[$sized[5]]['length'] = $length;
				$sizetable[$sized[5]]['width'] = $width;
				$sizetable[$sized[5]]['height'] = $height;
			
				
				}
				else{
					//echo $res->sku.',';
				}
			}
			
			foreach($sizetable as $sizen=>$table)
			{  $sizeguide .= '<tr>';
				$sizeguide .= '<td>'.$sizen.'</td>';
				 $sizeguide .= '<td>'.$table['length'].'</td>';
				 $sizeguide .= '<td>'.$table['width'].'</td>';
				 $sizeguide .= '<td>'.$table['height'].'</td>';
				$sizeguide .= '</tr>';
			}
			$sizeguide .= '</table>';
			  $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $res->sku ) );
		  if ( $product_id ) 
		  { echo $res->sku.',';
	  // echo $sizeguide;
			  update_post_meta($product_id,'sizeguide',esc_attr($sizeguide));
			  //die;
		  }
			
		// echo "<pre>"; print_r($sized); echo "<pre>";
		 
		}
		
	} die;
	echo "<pre>"; print_r($result); die;
}

add_action('wp_ajax_add_sizeguideofportwest', 'add_sizeguideofportwest' );                                                            
add_action('wp_ajax_nopriv_add_sizeguideofportwest', 'add_sizeguideofportwest' );


function set_post_thumbnail_byprodutid()
{
	
	global $wpdb;
	
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');
			
			
	$livearray = array('2085','2201','2202','2203','2204','2205','2206','2207','2208','2209','2802','2852','2860','2885','A001','A002','A003','A020','A050','A080','A100','A105','A109','A110','A111','A112','A113','A114','A115','A120','A121','A122','A125','A129','A130','A135','A140','A143','A145','A146','A150','A171','A174','A175','A185','A196','A197','A198','A199','A200','A210','A220','A225','A230','A245','A250','A251','A260','A270','A271','A280','A290','A300','A301','A302','A310','A315','A319','A320','A325','A330','A340','A350','A351','A352','A353','A354','A360','A400','A401','A427','A435','A445','A450','A460','A500','A501','A510','A511','A520','A530','A590','A600','A610','A620','A621','A622','A625','A630','A635','A640','A641','A643','A645','A655','A665','A667','A688','A689','A690','A691','A700','A710','A720','A721','A722','A723','A724','A725','A726','A729','A730','A735','A740','A745','A750','A780','A790','A795','A796','A800','A810','A827','A835','A845','A900','A905','A910','A915','A925','A941','AF22','AF50','AF53','AF73','AF82','AF83','AF84','AF91','AM10','AM11','AM12','AM13','AM14','AM15','AM20','AM22','AM23','AM24','AM30','AM31','AP01','AP02','AP30','AP31','AP32','AP50','AP52','AP60','AP62','AP70','AP80','AP81','AP90','AP91','AS10','AS11','AS20','AS21','B010','B013','B023','B024','B028','B029','B120','B121','B123','B130','B131','B133','B140','B151','B153','B175','B185','B195','B209','B210','B212','B300','B302','B307','B309','B310','B900','B901','B903','B904','B905','B907','B908','B909','B910','B912','B916','BIZ1','BIZ2','BIZ4','BIZ5','BIZ6','BIZ7','BP51','BP52','BP90','BT05','BT10','BT20','BZ11','BZ12','BZ30','BZ31','BZ40','C030','C052','C070','C071','C075','C078','C079','C099','C107','C276','C370','C375','C376','C387','C394','C405','C465','C466','C467','C468','C470','C471','C472','C473','C474','C475','C476','C481','C484','C485','C494','C496','C497','C565','C600','C676','C701','C703','C711','C720','C721','C730','C731','C733','C734','C771','C774','C775','C776','C802','C803','C806','C808','C811','C812','C813','C814','C820','C831','C833','C834','C836','C837','C851','C852','C854','C859','C865','C875','C876','C881','C887','C890','CH10','CH11','CH12','CP10','CP21','CR10','CR12','CS10','CS11','CS12','CS20','CS21','CV01','CV02','CV03','CV04','CW10','CW11','CW12','D100','D118','D300','D340','E040','E041','E042','E043','E044','E046','E048','E049','E052','E061','EP01','EP02','EP04','EP06','EP07','EP08','EP16','EP18','EP20','EP21','EP30','F171','F180','F205','F208','F280','F282','F285','F300','F301','F330','F400','F401','F414','F433','F440','F441','F450','F465','F474','F476','F477','F500','F813','FA10','FA11','FA12','FA21','FA22','FA23','FB30','FB31','FB40','FB41','FC01','FC02','FC03','FC04','FC10','FC11','FC12','FC14','FC21','FC41','FC44','FC50','FC52','FC53','FC54','FC55','FC57','FC60','FC61','FC62','FC63','FC64','FC65','FC66','FC67','FC86','FC87','FC88','FC89','FC90','FC94','FC95','FC96','FC97','FD01','FD02','FD09','FD10','FD11','FD15','FD85','FD90','FD95','FF50','FL02','FP02','FP05','FP08','FP10','FP11','FP12','FP13','FP14','FP15','FP18','FP19','FP20','FP21','FP22','FP23','FP26','FP27','FP28','FP29','FP30','FP34','FP35','FP36','FP39','FP40','FP41','FP44','FP45','FP48','FP49','FP50','FP51','FP52','FP62','FP63','FP64','FP65','FP66','FP67','FP68','FP98','FP99','FR01','FR02','FR03','FR06','FR09','FR10','FR11','FR12','FR14','FR18','FR19','FR20','FR21','FR22','FR25','FR26','FR27','FR28','FR30','FR31','FR35','FR36','FR37','FR38','FR41','FR43','FR46','FR47','FR50','FR51','FR52','FR53','FR55','FR56','FR57','FR58','FR59','FR60','FR61','FR62','FR63','FR70','FR71','FR72','FR73','FR74','FR75','FR76','FR77','FR78','FR79','FR80','FR81','FR85','FR89','FR90','FR91','FR92','FR93','FR98','FT12','FT13','FT15','FT25','FT50','FT63','FT64','FW01','FW02','FW03','FW04','FW05','FW06','FW07','FW08','FW09','FW10','FW11','FW12','FW13','FW14','FW15','FW16','FW17','FW18','FW19','FW20','FW21','FW22','FW23','FW24','FW25','FW26','FW28','FW29','FW30','FW31','FW32','FW33','FW34','FW35','FW36','FW37','FW38','FW39','FW40','FW41','FW42','FW43','FW44','FW45','FW46','FW47','FW48','FW49','FW51','FW57','FW58','FW59','FW60','FW61','FW62','FW63','FW64','FW65','FW66','FW67','FW68','FW69','FW71','FW74','FW75','FW80','FW81','FW82','FW83','FW84','FW85','FW86','FW87','FW88','FW89','FW90','FW92','FW93','FW94','FW95','G456','G465','G470','G475','G476','GL10','GL11','GL12','GL13','GL14','GL16','GT10','GT13','GT23','GT27','GT29','GT30','GT33','H440','H441','H442','H443','H444','H445','HA10','HA13','HA14','HB10','HB11','HF50','HV03','HV04','HV05','HV07','HV08','HV09','HV10','HV20','HV21','HV22','HV50','HV55','HV56','ID10','ID11','ID12','ID13','ID20','ID30','IW10','IW30','IW40','IW50','JN12','JN14','JN15','KN09','KN10','KN18','KN20','KN30','KN40','KN90','KN91','KN93','KP05','KP10','KP15','KP20','KP30','KP40','KP44','KP50','KP55','KS10','KS11','KS12','KS13','KS14','KS15','KS18','KS31','KS32','KS40','KS41','KS51','KS54','KS55','KS56','KS60','KS61','KS62','KS63','L440','L470','L474','L476','LJ20','LW12','LW13','LW14','LW15','LW16','LW20','LW30','LW56','LW63','LW70','LW71','LW72','LW97','MT50','MT51','MT52','MV25','MV26','MV27','MV28','MV29','MV35','MV36','MV91','MX28','NX50','P005','P100','P101','P102','P108','P153','P200','P201','P203','P209','P220','P223','P250','P251','P271','P291','P301','P303','P304','P309','P351','P371','P391','P410','P420','P430','P500','P516','P902','P906','P920','P921','P926','P940','P941','P946','P950','P952','P956','P970','P971','P976','PA01','PA02','PA04','PA10','PA30','PA31','PA45','PA46','PA48','PA49','PA50','PA52','PA54','PA55','PA56','PA58','PA60','PA61','PA62','PA63','PA64','PA65','PA66','PA67','PA68','PA69','PA91','PA99','PB10','PB55','PC55','PG54','PJ10','PJ20','PJ50','PJ52','PR32','PS04','PS05','PS11','PS12','PS16','PS21','PS30','PS32','PS33','PS34','PS40','PS41','PS42','PS44','PS46','PS47','PS48','PS50','PS51','PS52','PS53','PS54','PS55','PS58','PS59','PS63','PS90','PS91','PV50','PV54','PV60','PV64','PW11','PW13','PW14','PW15','PW17','PW18','PW20','PW21','PW22','PW23','PW24','PW25','PW26','PW30','PW31','PW32','PW33','PW34','PW340','PW35','PW36','PW37','PW370','PW371','PW38','PW39','PW40','PW41','PW42','PW43','PW45','PW47','PW48','PW50','PW51','PW53','PW54','PW55','PW56','PW57','PW58','PW59','PW60','PW65','PW66','PW68','PW69','PW79','PW80','PW81','PW89','PW90','PW91','PW92','PW93','PW94','PW96','PW97','PW98','PW99','R460','R473','R480','RT19','RT20','RT21','RT22','RT23','RT26','RT27','RT30','RT31','RT32','RT34','RT40','RT42','RT43','RT44','RT45','RT46','RT47','RT48','RT49','RT50','RT51','RT60','RT61','RT62','RT63','S068','S085','S092','S101','S102','S103','S104','S107','S108','S117','S118','S120','S121','S152','S156','S160','S161','S162','S166','S170','S171','S172','S173','S174','S177','S178','S190','S191','S231','S232','S250','S251','S266','S267','S271','S277','S278','S279','S350','S351','S360','S363','S364','S365','S375','S376','S378','S388','S410','S412','S413','S414','S415','S418','S419','S424','S425','S426','S427','S428','S429','S430','S431','S433','S434','S435','S437','S438','S440','S441','S450','S451','S452','S453','S460','S461','S462','S463','S464','S466','S467','S468','S469','S471','S475','S476','S477','S478','S479','S480','S481','S482','S483','S484','S485','S486','S487','S488','S489','S490','S491','S492','S493','S495','S496','S498','S499','S503','S505','S507','S521','S523','S530','S532','S533','S534','S535','S536','S538','S543','S544','S545','S553','S555','S560','S561','S562','S563','S570','S571','S572','S573','S578','S579','S585','S590','S591','S592','S597','S665','S686','S687','S710','S760','S766','S768','S769','S770','S771','S772','S773','S774','S775','S776','S777','S778','S779','S780','S781','S782','S783','S785','S787','S790','S791','S794','S795','S796','S810','S816','S817','S827','S837','S839','S840','S841','S843','S845','S849','S855','S862','S882','S884','S885','S886','S887','S889','S891','S894','S895','S896','S899','S900','S903','S916','S917','S918','S932','S934','S987','S996','S998','S999','SK08','SK10','SK11','SK12','SK13','SK18','SK20','SK33','SM10','SM15','SM20','SM30','SM31','SM33','SM40','SM45','SM50','SM60','SM61','SM63','SM70','SM75','SM80','SM90','SM91','SP01','ST11','ST20','ST30','ST31','ST35','ST36','ST38','ST40','ST41','ST42','ST43','ST44','ST45','ST47','ST50','ST60','ST70','ST80','ST85','SW10','SW20','SW32','SW33','T180','T181','T184','T185','T400','T402','T500','T501','T601','T602','T603','T620','T701','T702','T703','T704','T720','T750','T801','T802','T803','T820','T830','T831','T832','T900','TB02','TB10','TK40','TK41','TK50','TK51','TK52','TK53','TK54','TK83','TX10','TX11','TX12','TX13','TX14','TX15','TX16','TX17','TX18','TX19','TX20','TX22','TX23','TX30','TX32','TX33','TX36','TX37','TX39','TX40','TX45','TX50','TX51','TX52','TX55','TX60','TX61','TX62','TX70','TX71','TX72','VA120','VA198','VA199','VA310','VA350','VA620','VA622','Z456','Z464','Z465','Z524','Z529','Z530','Z531','Z533','Z534','Z541','Z543','Z550','Z551','Z580','Z583','Z586','Z587','Z600','Z601','Z610','Z611','Z612','Z613','Z620','Z622','Z630','Z635','Z636');
	$i =1;
	$result = $wpdb->get_results('select * from wp_portwestdata');
	foreach($result as $res){
		if(in_array($res->sku,$livearray))
		{
			$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $res->sku ) );
			  if ( $product_id ) 
			  {	 
					$images = json_decode($res->images); 
						
						$thumb_url = 'https://ppesuppliesdirect.com/'.$images[0];
						

						// Download file to temp location
						$tmp = download_url( $thumb_url,300 );
						
						
					if(!isset($tmp->errors) || empty($tmp->errors)){
							// Set variables for storage
							// fix file name for query strings
							preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $thumb_url, $matches);
							$file_array['name'] = basename($matches[0]);
							$file_array['tmp_name'] = $tmp;

							// If error storing temporarily, unlink
							if ( is_wp_error( $tmp ) ) {
							@unlink($file_array['tmp_name']);
							$file_array['tmp_name'] = '';
							$logtxt .= "Error: download_url error - $tmp\n";
							}else{
							$logtxt .= "download_url: $tmp\n";
							}

							//use media_handle_sideload to upload img:
							$thumbid = media_handle_sideload( $file_array, $product_id, 'Post thumb' );
							// If error storing permanently, unlink
							if ( is_wp_error($thumbid) ) {
							@unlink($file_array['tmp_name']);
							//return $thumbid;
							$logtxt .= "Error: media_handle_sideload error - $thumbid\n";
							}else{
							$logtxt .= "ThumbID: $thumbid\n";
							}

							set_post_thumbnail($product_id, $thumbid);
						
						}
						else
						{
						   echo $post_id.',';	
						}

				
			  }
			
		}
		
	}
	die('finish');
	
}

add_action('wp_ajax_set_post_thumbnail_byprodutid', 'set_post_thumbnail_byprodutid' );                                                            
add_action('wp_ajax_nopriv_set_post_thumbnail_byprodutid', 'set_post_thumbnail_byprodutid' );


function update_size_guide_portwest() {
	//echo "hello";
	global $wpdb;
			
	$livearray = array('2085','2201','2202','2203','2204','2205','2206','2207','2208','2209','2802','2852','2860','2885','A001','A002','A003','A020','A050','A080','A100','A105','A109','A110','A111','A112','A113','A114','A115','A120','A121','A122','A125','A129','A130','A135','A140','A143','A145','A146','A150','A171','A174','A175','A185','A196','A197','A198','A199','A200','A210','A220','A225','A230','A245','A250','A251','A260','A270','A271','A280','A290','A300','A301','A302','A310','A315','A319','A320','A325','A330','A340','A350','A351','A352','A353','A354','A360','A400','A401','A427','A435','A445','A450','A460','A500','A501','A510','A511','A520','A530','A590','A600','A610','A620','A621','A622','A625','A630','A635','A640','A641','A643','A645','A655','A665','A667','A688','A689','A690','A691','A700','A710','A720','A721','A722','A723','A724','A725','A726','A729','A730','A735','A740','A745','A750','A780','A790','A795','A796','A800','A810','A827','A835','A845','A900','A905','A910','A915','A925','A941','AF22','AF50','AF53','AF73','AF82','AF83','AF84','AF91','AM10','AM11','AM12','AM13','AM14','AM15','AM20','AM22','AM23','AM24','AM30','AM31','AP01','AP02','AP30','AP31','AP32','AP50','AP52','AP60','AP62','AP70','AP80','AP81','AP90','AP91','AS10','AS11','AS20','AS21','B010','B013','B023','B024','B028','B029','B120','B121','B123','B130','B131','B133','B140','B151','B153','B175','B185','B195','B209','B210','B212','B300','B302','B307','B309','B310','B900','B901','B903','B904','B905','B907','B908','B909','B910','B912','B916','BIZ1','BIZ2','BIZ4','BIZ5','BIZ6','BIZ7','BP51','BP52','BP90','BT05','BT10','BT20','BZ11','BZ12','BZ30','BZ31','BZ40','C030','C052','C070','C071','C075','C078','C079','C099','C107','C276','C370','C375','C376','C387','C394','C405','C465','C466','C467','C468','C470','C471','C472','C473','C474','C475','C476','C481','C484','C485','C494','C496','C497','C565','C600','C676','C701','C703','C711','C720','C721','C730','C731','C733','C734','C771','C774','C775','C776','C802','C803','C806','C808','C811','C812','C813','C814','C820','C831','C833','C834','C836','C837','C851','C852','C854','C859','C865','C875','C876','C881','C887','C890','CH10','CH11','CH12','CP10','CP21','CR10','CR12','CS10','CS11','CS12','CS20','CS21','CV01','CV02','CV03','CV04','CW10','CW11','CW12','D100','D118','D300','D340','E040','E041','E042','E043','E044','E046','E048','E049','E052','E061','EP01','EP02','EP04','EP06','EP07','EP08','EP16','EP18','EP20','EP21','EP30','F171','F180','F205','F208','F280','F282','F285','F300','F301','F330','F400','F401','F414','F433','F440','F441','F450','F465','F474','F476','F477','F500','F813','FA10','FA11','FA12','FA21','FA22','FA23','FB30','FB31','FB40','FB41','FC01','FC02','FC03','FC04','FC10','FC11','FC12','FC14','FC21','FC41','FC44','FC50','FC52','FC53','FC54','FC55','FC57','FC60','FC61','FC62','FC63','FC64','FC65','FC66','FC67','FC86','FC87','FC88','FC89','FC90','FC94','FC95','FC96','FC97','FD01','FD02','FD09','FD10','FD11','FD15','FD85','FD90','FD95','FF50','FL02','FP02','FP05','FP08','FP10','FP11','FP12','FP13','FP14','FP15','FP18','FP19','FP20','FP21','FP22','FP23','FP26','FP27','FP28','FP29','FP30','FP34','FP35','FP36','FP39','FP40','FP41','FP44','FP45','FP48','FP49','FP50','FP51','FP52','FP62','FP63','FP64','FP65','FP66','FP67','FP68','FP98','FP99','FR01','FR02','FR03','FR06','FR09','FR10','FR11','FR12','FR14','FR18','FR19','FR20','FR21','FR22','FR25','FR26','FR27','FR28','FR30','FR31','FR35','FR36','FR37','FR38','FR41','FR43','FR46','FR47','FR50','FR51','FR52','FR53','FR55','FR56','FR57','FR58','FR59','FR60','FR61','FR62','FR63','FR70','FR71','FR72','FR73','FR74','FR75','FR76','FR77','FR78','FR79','FR80','FR81','FR85','FR89','FR90','FR91','FR92','FR93','FR98','FT12','FT13','FT15','FT25','FT50','FT63','FT64','FW01','FW02','FW03','FW04','FW05','FW06','FW07','FW08','FW09','FW10','FW11','FW12','FW13','FW14','FW15','FW16','FW17','FW18','FW19','FW20','FW21','FW22','FW23','FW24','FW25','FW26','FW28','FW29','FW30','FW31','FW32','FW33','FW34','FW35','FW36','FW37','FW38','FW39','FW40','FW41','FW42','FW43','FW44','FW45','FW46','FW47','FW48','FW49','FW51','FW57','FW58','FW59','FW60','FW61','FW62','FW63','FW64','FW65','FW66','FW67','FW68','FW69','FW71','FW74','FW75','FW80','FW81','FW82','FW83','FW84','FW85','FW86','FW87','FW88','FW89','FW90','FW92','FW93','FW94','FW95','G456','G465','G470','G475','G476','GL10','GL11','GL12','GL13','GL14','GL16','GT10','GT13','GT23','GT27','GT29','GT30','GT33','H440','H441','H442','H443','H444','H445','HA10','HA13','HA14','HB10','HB11','HF50','HV03','HV04','HV05','HV07','HV08','HV09','HV10','HV20','HV21','HV22','HV50','HV55','HV56','ID10','ID11','ID12','ID13','ID20','ID30','IW10','IW30','IW40','IW50','JN12','JN14','JN15','KN09','KN10','KN18','KN20','KN30','KN40','KN90','KN91','KN93','KP05','KP10','KP15','KP20','KP30','KP40','KP44','KP50','KP55','KS10','KS11','KS12','KS13','KS14','KS15','KS18','KS31','KS32','KS40','KS41','KS51','KS54','KS55','KS56','KS60','KS61','KS62','KS63','L440','L470','L474','L476','LJ20','LW12','LW13','LW14','LW15','LW16','LW20','LW30','LW56','LW63','LW70','LW71','LW72','LW97','MT50','MT51','MT52','MV25','MV26','MV27','MV28','MV29','MV35','MV36','MV91','MX28','NX50','P005','P100','P101','P102','P108','P153','P200','P201','P203','P209','P220','P223','P250','P251','P271','P291','P301','P303','P304','P309','P351','P371','P391','P410','P420','P430','P500','P516','P902','P906','P920','P921','P926','P940','P941','P946','P950','P952','P956','P970','P971','P976','PA01','PA02','PA04','PA10','PA30','PA31','PA45','PA46','PA48','PA49','PA50','PA52','PA54','PA55','PA56','PA58','PA60','PA61','PA62','PA63','PA64','PA65','PA66','PA67','PA68','PA69','PA91','PA99','PB10','PB55','PC55','PG54','PJ10','PJ20','PJ50','PJ52','PR32','PS04','PS05','PS11','PS12','PS16','PS21','PS30','PS32','PS33','PS34','PS40','PS41','PS42','PS44','PS46','PS47','PS48','PS50','PS51','PS52','PS53','PS54','PS55','PS58','PS59','PS63','PS90','PS91','PV50','PV54','PV60','PV64','PW11','PW13','PW14','PW15','PW17','PW18','PW20','PW21','PW22','PW23','PW24','PW25','PW26','PW30','PW31','PW32','PW33','PW34','PW340','PW35','PW36','PW37','PW370','PW371','PW38','PW39','PW40','PW41','PW42','PW43','PW45','PW47','PW48','PW50','PW51','PW53','PW54','PW55','PW56','PW57','PW58','PW59','PW60','PW65','PW66','PW68','PW69','PW79','PW80','PW81','PW89','PW90','PW91','PW92','PW93','PW94','PW96','PW97','PW98','PW99','R460','R473','R480','RT19','RT20','RT21','RT22','RT23','RT26','RT27','RT30','RT31','RT32','RT34','RT40','RT42','RT43','RT44','RT45','RT46','RT47','RT48','RT49','RT50','RT51','RT60','RT61','RT62','RT63','S068','S085','S092','S101','S102','S103','S104','S107','S108','S117','S118','S120','S121','S152','S156','S160','S161','S162','S166','S170','S171','S172','S173','S174','S177','S178','S190','S191','S231','S232','S250','S251','S266','S267','S271','S277','S278','S279','S350','S351','S360','S363','S364','S365','S375','S376','S378','S388','S410','S412','S413','S414','S415','S418','S419','S424','S425','S426','S427','S428','S429','S430','S431','S433','S434','S435','S437','S438','S440','S441','S450','S451','S452','S453','S460','S461','S462','S463','S464','S466','S467','S468','S469','S471','S475','S476','S477','S478','S479','S480','S481','S482','S483','S484','S485','S486','S487','S488','S489','S490','S491','S492','S493','S495','S496','S498','S499','S503','S505','S507','S521','S523','S530','S532','S533','S534','S535','S536','S538','S543','S544','S545','S553','S555','S560','S561','S562','S563','S570','S571','S572','S573','S578','S579','S585','S590','S591','S592','S597','S665','S686','S687','S710','S760','S766','S768','S769','S770','S771','S772','S773','S774','S775','S776','S777','S778','S779','S780','S781','S782','S783','S785','S787','S790','S791','S794','S795','S796','S810','S816','S817','S827','S837','S839','S840','S841','S843','S845','S849','S855','S862','S882','S884','S885','S886','S887','S889','S891','S894','S895','S896','S899','S900','S903','S916','S917','S918','S932','S934','S987','S996','S998','S999','SK08','SK10','SK11','SK12','SK13','SK18','SK20','SK33','SM10','SM15','SM20','SM30','SM31','SM33','SM40','SM45','SM50','SM60','SM61','SM63','SM70','SM75','SM80','SM90','SM91','SP01','ST11','ST20','ST30','ST31','ST35','ST36','ST38','ST40','ST41','ST42','ST43','ST44','ST45','ST47','ST50','ST60','ST70','ST80','ST85','SW10','SW20','SW32','SW33','T180','T181','T184','T185','T400','T402','T500','T501','T601','T602','T603','T620','T701','T702','T703','T704','T720','T750','T801','T802','T803','T820','T830','T831','T832','T900','TB02','TB10','TK40','TK41','TK50','TK51','TK52','TK53','TK54','TK83','TX10','TX11','TX12','TX13','TX14','TX15','TX16','TX17','TX18','TX19','TX20','TX22','TX23','TX30','TX32','TX33','TX36','TX37','TX39','TX40','TX45','TX50','TX51','TX52','TX55','TX60','TX61','TX62','TX70','TX71','TX72','VA120','VA198','VA199','VA310','VA350','VA620','VA622','Z456','Z464','Z465','Z524','Z529','Z530','Z531','Z533','Z534','Z541','Z543','Z550','Z551','Z580','Z583','Z586','Z587','Z600','Z601','Z610','Z611','Z612','Z613','Z620','Z622','Z630','Z635','Z636');
	$i =1;
	$result = $wpdb->get_results('select * from wp_portwestdata');
	foreach($result as $res){
		if(in_array($res->sku,$livearray))
		{
			$json_data = json_decode($res->sizecolor);
			foreach($json_data as $json_datas){
				if($json_datas[5]==''){
					$newsizearray[$json_datas[0]][] = $json_datas;
					//echo "<pre>"; print_r($newsizearray); echo "</pre>";
				}
			}
			/*$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $res->sku ) );
			
			  if ( $product_id ) 
			  {	 
				
			  } */
		}
	}
	//echo count($newsizearray); die;
	foreach($newsizearray as $newsize){
			echo "<pre>"; print_r($newsize[0]); echo "</pre>";
$sizeguide = "<table class='sizeguide'>";
		  $sizeguide .='<tr><th>Size</th>';
		  $sizeguide .='<th>Length</th>';
		  $sizeguide .='<th>Width</th>';
		  $sizeguide .='<th>Height</th></tr>';

			$sizeguide .= '<tr>';
				$sizeguide .= '<td>One size</td>';
				 $sizeguide .= '<td>'.$newsize[0][10].'</td>';
				 $sizeguide .= '<td>'.$newsize[0][11].'</td>';
				 $sizeguide .= '<td>'.$newsize[0][12].'</td>';
				$sizeguide .= '</tr>';
			
			$sizeguide .= '</table>';	
$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $newsize[0][0] ) );
			
			  if ( $product_id ) 
			  {	 
					update_post_meta($product_id,'sizeguide',esc_attr($sizeguide));	
			  }
				
			//die;
	}
//	echo "<pre>"; print_r($newsizearray); echo "</pre>"; 
	die;
}
add_action('wp_ajax_update_size_guide_portwest', 'update_size_guide_portwest' );
add_action('wp_ajax_nopriv_update_size_guide_portwest', 'update_size_guide_portwest' );


/**
 * @author: antondrob
 * All custom scripts of the single author
 */

require_once( get_stylesheet_directory() . '/inc/antondrob.php' );

/**
 * @author: antondrob
 * All shortcodes
 */

require_once( get_stylesheet_directory() . '/inc/shortcodes.php' );