<?php

if ( !class_exists("Yozi_Woo_Accessories") ) {
	class Yozi_Woo_Accessories {

		public static function init() {
			// accessory
			if ( yozi_get_config('show_product_accessory', true) ) {
				add_action( 'wp_ajax_nopriv_yozi_variable_add_to_cart', array( __CLASS__, 'add_to_cart' ) );
				add_action( 'wp_ajax_yozi_variable_add_to_cart', array( __CLASS__, 'add_to_cart' ) );

				add_action( 'wp_ajax_nopriv_yozi_get_total_price', array( __CLASS__, 'display_total_price' ) );
				add_action( 'wp_ajax_yozi_get_total_price', array( __CLASS__, 'display_total_price' ) );

				// Add
				add_action( 'woocommerce_product_write_panel_tabs', array( __CLASS__, 'add_accessories_field_tab' ) );
				add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'add_accessories_add_fields' ) );

				// Save
				add_action( 'woocommerce_process_product_meta_simple', array( __CLASS__, 'save_accessories' ) );
				add_action( 'woocommerce_process_product_meta_variable', array( __CLASS__, 'save_accessories' ) );
				add_action( 'woocommerce_process_product_meta_grouped', array( __CLASS__, 'save_accessories' ) );
				add_action( 'woocommerce_process_product_meta_external', array( __CLASS__, 'save_accessories' ) );
			}
			// recommend
			add_action( 'woocommerce_product_options_general_product_data',	array( __CLASS__, 'recommend_product_options' ) );
			// save
			add_action( 'woocommerce_process_product_meta_simple', array( __CLASS__, 'save_options' ) );
			add_action( 'woocommerce_process_product_meta_variable', array( __CLASS__, 'save_options' ) );
			add_action( 'woocommerce_process_product_meta_grouped', array( __CLASS__, 'save_options' ) );
			add_action( 'woocommerce_process_product_meta_external', array( __CLASS__, 'save_options' ) );
			
		}
		
		public static function add_accessories_field_tab() {
			?>
			<li class="advanced_options show_if_simple show_if_variable">
				<a href="#apus_product_accessories"> <?php echo esc_html__( 'Accessories', 'yozi' ); ?></a>
			</li>
			<?php
		}

		public static function add_accessories_add_fields() {
			global $post;
			$json_ids = self::get_products_by_ids();
			?>
			<div id="apus_product_accessories" class="panel woocommerce_options_panel">
				<div class="options_group">
					<p class="form-field">
						<label for="product_accessory_ids"><?php esc_html_e( 'Accessories', 'yozi' ); ?></label>
						<select id="product_accessory_ids" class="wc-product-search" multiple="multiple" style="width: 50%;" name="product_accessory_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'yozi' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
							<?php
								foreach ( $json_ids as $product_id => $product_name) {
									echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product_name ) . '</option>';
								}
							?>
						</select>

					</p>
				</div>
			</div>
			<?php
		}

		public static function save_accessories( $post_id ) {
			$accessories = isset( $_POST['product_accessory_ids'] ) ? $_POST['product_accessory_ids'] : array();
			$accessories = array_filter( array_map( 'intval', $accessories ) );
			update_post_meta( $post_id, '_product_accessory_ids', $accessories );
		}

		public static function get_accessories( $product_id ) {
			$product_accessory_ids = get_post_meta( $product_id, '_product_accessory_ids', true );
			if (!empty($product_accessory_ids)) {
				return (array)maybe_unserialize( $product_accessory_ids );
			} else {
				return array();
			}
		}

		public static function get_products_by_ids() {
			global $post;
			$product_ids = self::get_accessories($post->ID);
			$json_ids = array();
			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( is_object( $product ) ) {
					$json_ids[ $product_id ] = wp_kses_post(html_entity_decode($product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' )));
				}
			}
			return $json_ids;
		}

		public static function add_to_cart() {
			$product_id = absint( $_POST['product_id'] );
			$quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
			$variation_id = empty( $_POST['variation_id'] ) ? 0 : $_POST['variation_id'];
			$variation = empty( $_POST['variation'] ) ? 0 : $_POST['variation'];
			$product_status = get_post_status( $product_id );
			
			if ( WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}
				WC_AJAX::get_refreshed_fragments();

			} else {
				$data = array(
					'error' => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
				);
				wp_send_json( $data );
			}
			die();
		}

		public static function display_total_price() {
			check_ajax_referer( 'yozi-ajax-nonce', 'security' );
			$price = empty( $_POST['data'] ) ? 0 : $_POST['data'];
			if ( $price ) {
				$price_html = wc_price( $price );
				echo wp_kses_post( $price_html );
			}
			die();
		}

		public static function recommend_product_options() {
			echo '<div class="options_group">';
				woocommerce_wp_checkbox(
					array(
						'id' => '_apus_recommended',
						'label' => esc_html__( 'Recommended this product', 'yozi' ),
					)
				);
			echo '</div>';
		}

		public static function save_options($post_id) {
			$_apus_recommended = isset( $_POST['_apus_recommended'] ) ? wc_clean( $_POST['_apus_recommended'] ) : '' ;
			update_post_meta( $post_id, '_apus_recommended', $_apus_recommended );
		}
	}
	add_action( 'init', array('Yozi_Woo_Accessories', 'init') );
}