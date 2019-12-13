<?php
/**
 * Wishlist page template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.12
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php do_action( 'yith_wcwl_before_wishlist_form', $wishlist_meta ); ?>

<form id="yith-wcwl-form" action="<?php echo trim($form_action); ?>" method="post" class="woocommerce">
    <div class="product table-responsive">

    <?php wp_nonce_field( 'yith-wcwl-form', 'yith_wcwl_form_nonce' ) ?>

    <!-- TITLE -->
    <?php
    do_action( 'yith_wcwl_before_wishlist_title' );

    if( ! empty( $page_title ) ) :
    ?>
        <div class="hidden wishlist-title <?php echo ( $is_custom_list ) ? 'wishlist-title-with-form' : ''?>">
            <?php echo apply_filters( 'yith_wcwl_wishlist_title', '<h2>' . $page_title . '</h2>' ); ?>
            <?php if( $is_custom_list ): ?>
                <a class="btn button show-title-form">
                    <?php echo apply_filters( 'yith_wcwl_edit_title_icon', '<i class="fa fa-pencil"></i>' )?>
                    <?php esc_html_e( 'Edit title', 'yozi' ) ?>
                </a>
            <?php endif; ?>
        </div>
        <?php if( $is_custom_list ): ?>
            <div class="hidden-title-form">
                <input type="text" value="<?php echo esc_attr($page_title); ?>" name="wishlist_name"/>
                <button>
                    <?php echo apply_filters( 'yith_wcwl_save_wishlist_title_icon', '<i class="fa fa-check"></i>' )?>
                    <?php esc_html_e( 'Save', 'yozi' )?>
                </button>
                <a class="hide-title-form btn button">
                    <?php echo apply_filters( 'yith_wcwl_cancel_wishlist_title_icon', '<i class="fa fa-remove"></i>' )?>
                    <?php esc_html_e( 'Cancel', 'yozi' )?>
                </a>
            </div>
        <?php endif; ?>
    <?php
    endif;

     do_action( 'yith_wcwl_before_wishlist' ); ?>

    <!-- WISHLIST TABLE -->
	<table class="shop_table cart wishlist_table" data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo esc_attr($wishlist_id); ?>" data-token="<?php echo esc_attr($wishlist_token); ?>">

	    <?php $column_count = 2; ?>
        <thead>
            <tr>
                <th class="product-thumbnail"><?php echo esc_html__('Image','yozi') ?></th>
                <th class="product-name"><?php echo esc_html__('Product Name','yozi') ?></th>
                <th class="product-price"><?php echo esc_html__('Price','yozi') ?></th>
                <th class="product-subtotal"><?php echo esc_html__('Add to Cart','yozi') ?></th>
                <th class="product-remove"><span class="remove"><i class="fa-times fa"></i></span></th>
            </tr>
        </thead>
        <tbody>
        <?php
        if( count( $wishlist_items ) > 0 ) : 
	        $added_items = array();
            foreach( $wishlist_items as $item ) :
                global $product;

	            $item['prod_id'] = yit_wpml_object_id ( $item['prod_id'], 'product', true );

	            if( in_array( $item['prod_id'], $added_items ) ){
		            continue;
	            }

	            $added_items[] = $item['prod_id'];
	            $product = wc_get_product( $item['prod_id'] );
	            $availability = $product->get_availability();
	            $stock_status = $availability['class'];

                if( $product && $product->exists() ) :
	                ?>
                    <tr id="yith-wcwl-row-<?php echo esc_attr($item['prod_id']); ?>" data-row-id="<?php echo esc_attr($item['prod_id']); ?>">

	                    <?php if( $show_cb ) : ?>
		                    <td class="product-checkbox">
			                    <input type="checkbox" value="<?php echo esc_attr( $item['prod_id'] ) ?>" name="add_to_cart[]" <?php echo ( ! $product->is_type( 'simple' ) ) ? 'disabled="disabled"' : '' ?>/>
		                    </td>
	                    <?php endif ?>
                        <td class="hidden-xs hidden-768">
                            <div class="product-thumbnail">
                                <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>">
                                    <?php echo trim($product->get_image()); ?>
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="name-price">
                                <div class="product-name">
                                    <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>
                                    <?php do_action( 'yith_wcwl_table_after_product_name', $item ); ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if( $show_price ) : ?>
                                <span class="price">
                                    <?php echo trim($product->get_price() ? $product->get_price_html() : apply_filters( 'yith_free_text', esc_html__( 'Free!', 'yozi' ) )); ?>
                                </span>
                            <?php endif ?>
                        </td>
	                    <?php if( $show_last_column ): ?>
                        <td class="product-add-to-cart">
	                        <!-- Date added -->
	                        <?php
	                        if( $show_dateadded && isset( $item['dateadded'] ) ):
								echo '<span class="dateadded">' . sprintf(__( 'Added on : %s', 'yozi' ), date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) ) ) . '</span>';
	                        endif;
	                        ?>

	                        <!-- Add to cart button -->
                            <?php if( $show_add_to_cart && isset( $stock_status ) && $stock_status != 'out-of-stock' ): ?>
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            <?php endif ?>

	                        <!-- Change wishlist -->
							<?php if( $available_multi_wishlist && is_user_logged_in() && count( $users_wishlists ) > 1 && $move_to_another_wishlist && $is_user_owner ): ?>
	                        <select class="change-wishlist selectBox">
		                        <option value=""><?php esc_html_e( 'Move', 'yozi' ) ?></option>
		                        <?php
		                        foreach( $users_wishlists as $wl ):
			                        if( $wl['wishlist_token'] == $wishlist_meta['wishlist_token'] ){
				                        continue;
			                        }

		                        ?>
			                        <option value="<?php echo esc_attr( $wl['wishlist_token'] ) ?>">
				                        <?php
				                        $wl_title = ! empty( $wl['wishlist_name'] ) ? esc_html( $wl['wishlist_name'] ) : esc_html( $default_wishlsit_title );
				                        if( $wl['wishlist_privacy'] == 1 ){
					                        $wl_privacy = esc_html__( 'Shared', 'yozi' );
				                        }
				                        elseif( $wl['wishlist_privacy'] == 2 ){
					                        $wl_privacy = esc_html__( 'Private', 'yozi' );
				                        }
				                        else{
					                        $wl_privacy = esc_html__( 'Public', 'yozi' );
				                        }

				                        echo sprintf( '%s - %s', $wl_title, $wl_privacy );
				                        ?>
			                        </option>
		                        <?php
		                        endforeach;
		                        ?>
	                        </select>
	                        <?php endif; ?>

	                        <!-- Remove from wishlist -->
	                        <?php if( $is_user_owner && $repeat_remove_button ): ?>
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove_from_wishlist button" title="<?php esc_html_e( 'Remove this product', 'yozi' ) ?>"><?php esc_html_e( 'Remove', 'yozi' ) ?></a>
                            <?php endif; ?>
                        </td>
	                    <?php endif; ?>

                        <?php if( $is_user_owner ): ?>
                        <td class="product-remove">
                            <div>
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove remove_from_wishlist" title="<?php esc_html_e( 'Remove this product', 'yozi' ) ?>">&times;</a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php
                endif;
            endforeach;
        else: ?>
            <tr>
                <td colspan="<?php echo esc_attr( $column_count ) ?>" class="wishlist-empty"><?php echo apply_filters( 'yith_wcwl_no_product_to_remove_message', esc_html__( 'No products were added to the wishlist', 'yozi' ) ) ?></td>
            </tr>
        <?php
        endif;

        if( ! empty( $page_links ) ) : ?>
            <tr class="pagination-row">
                <td colspan="<?php echo esc_attr( $column_count ) ?>"><?php echo trim($page_links); ?></td>
            </tr>
        <?php endif ?>
        </tbody>
        <?php if( $show_cb || ($is_user_owner && $show_ask_estimate_button && $count > 0) || is_user_logged_in() && $is_user_owner && ! $is_private && $share_enabled ){ ?>
            <tfoot>
            <tr>
    	        <td colspan="<?php echo esc_attr( $column_count ) ?>">
    	            <?php if( $show_cb ) : ?>
    		            <div class="custom-add-to-cart-button-cotaniner">
    		                <a href="<?php echo esc_url( add_query_arg( array( 'wishlist_products_to_add_to_cart' => '', 'wishlist_token' => $wishlist_token ) ) ) ?>" class="button alt" id="custom_add_to_cart"><?php echo apply_filters( 'yith_wcwl_custom_add_to_cart_text', esc_html__( 'Add the selected products to the cart', 'yozi' ) ) ?></a>
    		            </div>
    	            <?php endif; ?>

    	            <?php if ( $is_user_owner && $show_ask_estimate_button && $count > 0 ): ?>
    		            <div class="ask-an-estimate-button-container">
    	                    <a href="<?php echo ( $additional_info || ! is_user_logged_in() ) ? '#ask_an_estimate_popup' : $ask_estimate_url ?>" class="btn button ask-an-estimate-button" <?php echo ( $additional_info ) ? 'data-rel="prettyPhoto[ask_an_estimate]"' : '' ?> >
    	                    <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
    	                    <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_text', esc_html__( 'Ask for an estimate', 'yozi' ) ) ?>
    	                </a>
    		            </div>
    	            <?php endif; ?>

    		        <?php
    		        do_action( 'yith_wcwl_before_wishlist_share' );

    		        if ( is_user_logged_in() && $is_user_owner && ! $is_private && $share_enabled ){
    			        yith_wcwl_get_template( 'share.php', $share_atts );
    		        }

    		        do_action( 'yith_wcwl_after_wishlist_share' );
    		        ?>
    	        </td>
            </tr>
            </tfoot>
        <?php } ?>
    </table>

    <?php wp_nonce_field( 'yith_wcwl_edit_wishlist_action', 'yith_wcwl_edit_wishlist' ); ?>

    <?php if( ! $is_default ): ?>
        <input type="hidden" value="<?php echo trim($wishlist_token); ?>" name="wishlist_id" id="wishlist_id">
    <?php endif; ?>

    <?php do_action( 'yith_wcwl_after_wishlist' ); ?>
    </div>
</form>

<?php do_action( 'yith_wcwl_after_wishlist_form', $wishlist_meta ); ?>

<?php if( $show_ask_estimate_button && ( ! is_user_logged_in() || $additional_info ) ): ?>
	<div id="ask_an_estimate_popup">
		<form action="<?php echo esc_url($ask_estimate_url); ?>" method="post" class="wishlist-ask-an-estimate-popup">
			<?php if( ! is_user_logged_in() ): ?>
				<label for="reply_email"><?php echo apply_filters( 'yith_wcwl_ask_estimate_reply_mail_label', esc_html__( 'Your email', 'yozi' ) ) ?></label>
				<input type="email" value="" name="reply_email" id="reply_email">
			<?php endif; ?>
			<?php if( ! empty( $additional_info_label ) ):?>
				<label for="additional_notes"><?php echo esc_html( $additional_info_label ) ?></label>
			<?php endif; ?>
			<textarea id="additional_notes" name="additional_notes"></textarea>

			<button class="btn button ask-an-estimate-button ask-an-estimate-button-popup" >
				<?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
				<?php echo apply_filters( 'yith_wcwl_ask_an_estimate_text', esc_html__( 'Ask for an estimate', 'yozi' ) ) ?>
			</button>
		</form>
	</div>
<?php endif; ?>