<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Yozi
 * @since Yozi 1.0
 */
/*
*Template Name: Page Default
*/

//wp_head();
set_time_limit(120);

?>
<style>

#main-content{
	padding:4rem 5rem;
}
</style>
<section id="main-container" class=" inner">
	<div class="row">
		<div id="main-content" class="main-page ">

				<?php



				/** Function to filter comma **/
				function viv_filter_comma($string){

				$farray = explode(",", str_replace(' ', '', $string));
					foreach ($farray as $k=>$v){

										if ( empty($v)){
											unset($farray[$k]);
										}

					}

					return $farray;
				}

				/** Function to get sizes from items **/
				function viv_get_size_attr( $arritem ){
					$size = "";

					if ( is_numeric( substr( $arritem,-1 ) ) ) {
					//echo "The last digit is number";

					if ( is_numeric( substr( $arritem,-2,1 ) ) ){

						$size = substr($arritem,-2);

					} else{

						$size = substr($arritem,-1);

					}


				} else{

					//echo "The last charcter is string";
					// get last string character

					$last_character = substr($arritem,-1);

					if ( $last_character == 'S' ){

						//get second last character

						$second_last_character = substr($arritem,-2,1);

						if ( $second_last_character == 'X') {

							$third_last_character = substr($arritem,-3,1);

							if ( $third_last_character == 'X') {

								$size = substr($arritem,-3);
							} else {
								$size = substr($arritem,-2);
							}
						} else {

							$size = substr($arritem,-1);
						}

					} // End if S

					//Check if M

					elseif ( $last_character == 'M' ){

						$size = substr($arritem,-1);

					} // If size is medium

					//If size is more than medium i.e large
					elseif ( $last_character == 'L' ){

						// check second last character
						if ( substr($arritem,-2,1) == 'X'){

							//check for third last character
							if ( substr($arritem,-3,1) == '4'){
									$size = substr($arritem,-3);
							} elseif( substr($arritem,-3,1) == '5'){
									$size = substr($arritem,-3);
							}
							 elseif( substr($arritem,-3,1) == '6'){
									$size = substr($arritem,-3);
							}
							 elseif( substr($arritem,-3,1) == '7'){
									$size = substr($arritem,-3);
							}
							 elseif( substr($arritem,-3,1) == '8'){
									$size = substr($arritem,-3);
							}
							elseif( substr($arritem,-3,1) == 'X' ){
								$size = 'e';
								if ( substr($arritem,-4,1) == 'X'){

									if ( substr($arritem,-5,1) == 'X'){

										$size = substr($arritem,-5);


									} else {
										$size = substr($arritem,-4);

									}


								} else {
									$size = substr($arritem,-3);
								}

								//
							}else{
									$size = substr($arritem,-2);
							}


						} else {
							$size = $last_character;
						}

					} // for L size





				}
				 return $size;

				} // end of get_size_attr



		$descjson = wp_remote_get(esc_url_raw(get_stylesheet_directory_uri(). '/inc/wojson/desc.json'));
		$descbody = wp_remote_retrieve_body($descjson);
		$descphp = json_decode($descbody, true);

		$techjson = wp_remote_get(esc_url_raw(get_stylesheet_directory_uri(). '/inc/wojson/tech.json'));
		$techbody = wp_remote_retrieve_body($techjson);
		$techphp = json_decode($techbody, true);
		$itemcsv = $techphp;

		function viv_array_str($v){

			return (string)$v;
		}
		foreach ( $itemcsv as $k ){

		$techlogicdata[] = array_map('viv_array_str', $k);

		}


		//** Script to check if both excel sheet have products with same sku no. or not

		foreach ( $techlogicdata as $itms ){

			$itmstyle[] = $itms['Style'];

		}

		foreach ( $descphp as $descsitem ){

			$desctyle[] = $descsitem['Style'];

		}

		//echo '<pre>' . var_export($itmstyle, true) . '</pre>';
		//echo '<pre>' . var_export($desctyle, true) . '</pre>';



		$jkl = array_intersect( $itmstyle, $desctyle);
		$diffjkl = array_diff( $itmstyle, $desctyle );
		foreach ( $jkl as $jk ){
			foreach ( $descphp as $descsss ){

			if ( in_array( $jk, $descsss ) ){

				$descwithpropermatch[] = $descsss;
 			}

		}
		}

		//echo '<pre>' . var_export($diffjkl, true) . '</pre>';
		//echo '<pre> Count is - ' . count( array_unique( $diffjkl ) ) . '</pre>';
		//echo '<pre>' . var_export( array_unique( $diffjkl ), true) . '</pre>';

		// lets reorder array value
		$oldunorderd = array_unique( $diffjkl );
		$newreorderd = array_values($oldunorderd);


		//echo '<pre>' . var_export( array_slice( $descwithpropermatch, 1, 10 ), true) . '</pre>';

		//echo '<pre>' . var_export( array_slice( array_unique( $diffjkl ),2 ), true) . '</pre>';



			function viv_make_logic_work( $sku, $desccsv, $itemcsv ) {

				function extract_attr_in_array( $sku, $itemcsv){

				foreach ( $itemcsv as $item ){

					if (in_array( $sku, $item )){

						$selectedskus[] = $item;
					}

				}

				foreach ( $selectedskus as $selected ) {

					$skuitem[] = $selected;

				}

				foreach ( $skuitem as $skuforsize ) {

					$newsku[] = array(
						'height' => $skuforsize['Height'],
						'length' => $skuforsize['Length'],
						'width' => $skuforsize['Width'],
						'weight' => $skuforsize['Weight(Kg)'],
						'item' => $skuforsize['Item'],
						'style' => $skuforsize['Style'],
						'size' => viv_get_size_attr($skuforsize['Item']),

					);

				}


				return $newsku;

			}// extract attributes in array from item ;

			function extract_desc_item($sku, $desccsv) {


				foreach ( $desccsv as $descitem ) {

					if (in_array( $sku, $descitem )){

						$filtereddesc[] = $descitem;
					}
				}

				return $filtereddesc;
			}// extract desc items


			$descfunctioned = extract_desc_item($sku, $desccsv);

			$extracted_items = extract_attr_in_array($sku,$itemcsv);

			$finalproduct = $descfunctioned[0];
			$finalproduct['attr'] = $extracted_items;
			$finalproduct['features'] = viv_filter_comma( $descfunctioned[0]['features'] );
			return $finalproduct;


			}//end of main function



			$skugetid = $_GET['sku'];
			//echo $skugetid;
			$xaa = viv_make_logic_work($skugetid ,$descphp,$techlogicdata);


			class viv_producter {


				public $product;

				function __construct( $productarray ){

					$this->product = $productarray;
				}

 				function get_body() {

					$desc = $this->product['Description'];
					$collection = $this->product['Collection'];
					$features = $this->product['features'];

					$body_html = "<p>$desc</p>";
					$body_html .= "<p><strong>Collection:</strong></p>";
					$body_html .= "<p>$collection</p>";
					$body_html .= "<p><strong>Features:</strong></p>";
					$body_html .= "<ul>";
					foreach ( $features as $feature ) {
						$body_html .= "<li>$feature</li>";

					}
					$body_html .= '</ul>';

					return $body_html;
				}

				function get_title(){
					return $this->product['Product'];
				}

				function get_category(){
					return $this->product['ProductType'];
				}


				function get_subcategory(){
					return $this->product['Range'];
				}

				function get_sku(){
					return $this->product['Style'];
				}

				function get_attributes(){
					return $this->product['attr'];
				}


				function get_sizes(){

				}

				function has_size_variation(){

					$attributes = $this->product['attr'];

					foreach ( $attributes as $attribute ) {
						$sizes[] = $attribute['size'];
					}
					return count( $this->product['$sizes'] );


				}

				function get_dimension(){
					$attributes = $this->product['attr'];
					$first = $attributes[0];
					$l = $first['length'];
					$w = $first['width'];
					$h = $first['height'];

					return array('l' => $l, 'w' => $w, 'h'=>$h);

				}

			}



			$firstproduct = new viv_producter($xaa);

		//	echo $firstproduct->get_body();

		//	echo $firstproduct->get_sku();

			//var_dump( $firstproduct->get_dimension() );

			//echo '<pre>' . var_export($firstproduct, true) . '</pre>';




			//echo '<pre>' . var_export($xaa, true) . '</pre>';

		//echo '<pre>' . var_export($techphp, true) . '</pre>';



		//echo '<pre>' . var_export($techlogicdata, true) . '</pre>';


		//echo '<pre>' . var_export($extracted_items, true) . '</pre>';

		function get_category_id_by_name_viv( $name ){

			$category = get_term_by('name', $name, 'product_cat',ARRAY_A);

			return $category['term_id'];

		}


				// Register uploaded images to wordpress database
/*
				  $upload_dir = wp_upload_dir();
				  // Scan images

				  $img_sku = $firstproduct->get_sku();
			  	$file_dir =  $upload_dir['basedir'] . '/products-images/'.$img_sku;

			  	$testdir = scandir( $file_dir );

			  	// scan only img
			  	$imagesinscandir = preg_grep('~\.(jpeg|jpg|png)$~',$testdir);
			  	//echo '<pre>' . var_export($imagesinscandir, true) . '</pre>';

				  //var_dump ($upload_dir);
				  //echo  $upload_dir['basedir'];

				  foreach ( $imagesinscandir as $images ){



				  // get the image file
				  $file = $upload_dir['basedir'] . '/products-images/' .$img_sku.'/' . $images;

				  // check the image mime type
				  $filetype = wp_check_filetype( basename($file), null);

				  // Prepare an array of post data for the attachment.
				  $attachment = array(

					  'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),
					  'post_content' => '',
					  'post_mime_type' => $filetype['type'],
				  );

				  // Insert attachment

					$attach_id = wp_insert_attachment( $attachment, $file );

				  // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				  require_once( ABSPATH . 'wp-admin/includes/image.php' );

				  	//Generate the metadata for the attachment, and update the database record.
				  $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				  wp_update_attachment_metadata( $attach_id, $attach_data );

				  echo "<br/><br/><br/>";

				  $attachimgids[] = $attach_id;
		  } //endforeach for scanned images.
		  var_dump($file_dir);

		  echo "<br/><br/><br/>";
*/
		  	//var_dump($attachimgids);

		//Create a variable product with a color attribute.


	    $product =  new WC_Product_Variable(  );

		$product->set_name($firstproduct->get_title()); //Set product name.

 		$product->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.

 		$product->set_description($firstproduct->get_body()); //Set product description.
		$product->set_sku($firstproduct->get_sku()); //Set SKU
		$product->set_category_ids( array( get_category_id_by_name_viv($firstproduct->get_category()),get_category_id_by_name_viv($firstproduct->get_subcategory())  ) ); //Set the product categories.                   | array $term_ids List of terms IDs.
 		$product->set_regular_price(5.00); //Set the product's regular price.
		$product->set_length($firstproduct->get_dimension()['l']); //Set the product length.
		$product->set_width($firstproduct->get_dimension()['w']); //Set the product width.
		$product->set_height($firstproduct->get_dimension()['h']); //Set the product height.
		$product->set_image_id($attachimgids[0]); //Set main image ID.                                         | int|string $image_id Product image id.
		//$product->set_gallery_image_ids($attachimgids); //Set gallery attachment ids.                       | array $image_ids List of image ids.

 		//$product->set_stock_status('instock'); //Set stock status.                               | string $status 'instock', 'outofstock' and 'onbackorder'
// check if product varition exists

	//	var_dump( $product);

		// = new WC_Product_Variable( $product );
		echo "<br/><br/><br/>";

		//echo '<pre>' . var_export( $single_variation->get_parent_data(), true) . '</pre>';
		//echo '<pre>' . var_export( $variations1, true) . '</pre>';

		//var_dump( $variation1 );






		 //var_dump($attrsizesoptions);

		 //Get attributes of $firstproduct
		 $productatributes = $firstproduct->get_attributes();

		 foreach ( $productatributes as $productatribute ) {

			 $attrsizesoptions[] = $productatribute['size'];



		 }

		 $unique_array_options = array_unique( $attrsizesoptions);

		 foreach ( $productatributes as $productatribute ) {

			 foreach ($unique_array_options as $unique_array_option){

				 if ( in_array( $unique_array_option,$productatribute ) ){

					 $alag[$unique_array_option] = $productatribute['weight'];
				 }
			 }
			$attrsizesoptionswithweight[] = $productatribute['size'];



		}



		 echo '<pre>' . var_export( $alag, true) . '</pre>';
		 echo "<br/><br/><br/>";

		 foreach ( $productatributes as $productatribute ) {

			 foreach ($unique_array_options as $unique_array_option ){

				 if (in_array($unique_array_option, $productatribute)){

					$unique_product_atrributes[] = $productatribute;
				}
			 }

		 }



	     $attribute = new WC_Product_Attribute( 3 );
	     $attribute->set_name('pa_size');
	     $attribute->set_options($unique_array_options);
	     $attribute->set_visible(true);
	     $attribute->set_variation(true);
	     $product->set_attributes(array($attribute));
	     $product->save();

		// var_dump($alag);


		 foreach ( $alag as $size => $weight ) {
			$variation = new WC_Product_Variation();
		 	$variation->set_parent_id($product->get_id());
		 	$variation->set_attributes(array('pa_size' => $size));
		 	$variation->set_regular_price('10');
		 	$variation->set_weight($weight);
		 	$variation->set_status('private');
		 	$variation->save();
		   // Now update some value unrelated to attributes.
		 	$variation = wc_get_product($variation->get_id());
		 	//$variation->set_attributes(array('size' => 'XXL'));
		 	$variation->set_status('publish');
		 	$variation->save();

		}
/*
	 			 $handle=new WC_Product_Variable($product->get_id());
	 			 $variations1=$handle->get_children();

	 			 if ( ! count($variations1) == 0 ){
	 				 foreach ($variations1 as $value) {
	 				$single_variation=new WC_Product_Variation($value);
	 					//echo '<option  value="'.$value.'">'.implode(" / ", $single_variation->get_variation_attributes()).'-'.get_woocommerce_currency_symbol().$single_variation->price.'</option>';

	 					 $p = str_replace(' ', '',$single_variation->name);
	 					echo "<br/><br/><br/>";
	 					//echo $p;
	 					 $v = str_replace(' ', '',$single_variation->get_parent_data()['title']);
	 					echo "<br/><br/><br/>";
	 					$vt = $v . "-".$size;
	 					//echo $v;
	 					echo "<br/><br/><br/>";

	 					if ( $p==$vt){
	 						//echo "Product Variation already there";
	 					}else{
	 						//If same variation already not present
	 						//echo "not matched";

							$variation = new WC_Product_Variation();
							$variation->set_parent_id($product->get_id());
							$variation->set_attributes(array('size' => $size));
							$variation->set_regular_price('10');
							$variation->set_weight($weight);
							$variation->set_status('private');
							$variation->save();
						// Now update some value unrelated to attributes.
							$variation = wc_get_product($variation->get_id());
							//$variation->set_attributes(array('size' => 'XXL'));
							$variation->set_status('publish');
							$variation->save();
	 					}
	 		  }

	 	  } else {

	 		$variation = new WC_Product_Variation();
	 		$variation->set_parent_id($product->get_id());
	 		$variation->set_attributes(array('size' => $size));
	 		$variation->set_regular_price('10');
	 		$variation->set_weight($weight);
	 		$variation->set_status('private');
	 		$variation->save();
	 	  // Now update some value unrelated to attributes.
	 		$variation = wc_get_product($variation->get_id());
	 		//$variation->set_attributes(array('size' => 'XXL'));
	 		$variation->set_status('publish');
	 		$variation->save();
	 	  }

	  } // end for each of productattributes
**/

/* For inserting all categories at once.

	$halfdesc = array_slice($descphp,1200);

	foreach ( $halfdesc as $descer ) {

		$category = get_term_by('name', $descer['ProductType'], 'product_cat',ARRAY_A);
		$category2 = get_term_by('name', $descer['Range'], 'product_cat',ARRAY_A);

		$childcat = $descer['Range'];

		// Check if product parent category exists or not

		echo "<br/><br/><br/>";

		 if ( term_exists($category['name'],'product_cat')){

			 echo $category['name'] . "exists";

			 $term_children = get_term_children( $category['term_id'], 'product_cat' );

			 foreach ($term_children as $child){
				 $allchildcats[] = get_term_by('id', $child, 'product_cat',ARRAY_A);
			 }

			 foreach ( $allchildcats as $chds ){
				 $allchildnames[] = $chds['name'];
			 }

			 if ( in_array( $childcat, $allchildnames ) ){
				 echo "CHild exists - " . $childcat;
			 } else {
				 echo $childcat . " - child cat does not exist";
				 //echo $category2['term_id'];
				 wp_insert_term( $childcat, 'product_cat', array( 'parent' => $category['term_id'] ) );
			 }

		 } else {
			 echo $category['name'] . " - Category does not exist";
			 wp_insert_term( $category['name'], 'product_cat');
		 }



	}




*/








				 ?>

			</main><!-- .site-main -->

		</div><!-- .content-area -->
</section>
<?php //wp_footer(); ?>
