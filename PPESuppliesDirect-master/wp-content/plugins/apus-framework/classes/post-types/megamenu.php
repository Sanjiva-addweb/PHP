<?php
/**
 * Footer manager for apus framework
 *
 * @package    apus-framework
 * @author     Team Apusthemes <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2015-2016 Apus Framework
 */
 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Apus_PostType_Megamenu {

  	public static function init() {
    	add_action( 'init', array( __CLASS__, 'register_post_type' ) );
    	if ( is_admin() ) {
	    	add_action( 'init', array( __CLASS__, 'register_megamenu_vc' ) );
	    }
    	add_filter( 'wp_edit_nav_menu_walker', array( __CLASS__, 'nav_edit_walker'), 10, 2 );
    	add_filter( 'apus_megamenu_item_config_toplevel', array( __CLASS__,'megamenu_item_config_toplevel' ), 10, 2 );
    	add_action( 'apus_megamenu_item_config' , array( __CLASS__, 'add_extra_fields_menu_config' ) );
    	add_filter( 'wp_setup_nav_menu_item', array( __CLASS__, 'custom_nav_item' ) );
    	add_action( 'wp_update_nav_menu_item', array( __CLASS__, 'custom_nav_update' ),10, 3);

    	add_action( 'admin_enqueue_scripts', array( __CLASS__, 'script' ) );
  	}

  	public static function register_post_type() {
	    $labels = array(
			'name'                  => __( 'Apus Megamenu', 'apus-framework' ),
			'singular_name'         => __( 'Megamenu', 'apus-framework' ),
			'add_new'               => __( 'Add New Megamenu', 'apus-framework' ),
			'add_new_item'          => __( 'Add New Megamenu', 'apus-framework' ),
			'edit_item'             => __( 'Edit Megamenu', 'apus-framework' ),
			'new_item'              => __( 'New Megamenu', 'apus-framework' ),
			'all_items'             => __( 'All Megamenus', 'apus-framework' ),
			'view_item'             => __( 'View Megamenu', 'apus-framework' ),
			'search_items'          => __( 'Search Megamenu', 'apus-framework' ),
			'not_found'             => __( 'No Megamenus found', 'apus-framework' ),
			'not_found_in_trash'    => __( 'No Megamenus found in Trash', 'apus-framework' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Apus Megamenu', 'apus-framework' ),
	    );

	    register_post_type( 'apus_megamenu',
	      	array(
		        'labels'            => apply_filters( 'apus_postype_megamenu_labels' , $labels ),
		        'supports'          => array( 'title', 'editor' ),
		        'public'            => true,
		        'has_archive'       => true,
		        'menu_position'     => 51,
		        'menu_icon'         => 'dashicons-admin-home',
	      	)
	    );

  	}

  	public static function script() {
  		wp_enqueue_script( 'apus-upload-image', APUS_FRAMEWORK_URL . 'assets/upload.js', array( 'jquery', 'wp-pointer' ), APUS_FRAMEWORK_VERSION, true );
  	}

  	public static function register_megamenu_vc() {
	    $options = get_option('wpb_js_content_types');
	    if ( is_array($options) && !in_array('apus_megamenu', $options) ) {
	      	$options[] = 'apus_megamenu';
	      	update_option( 'wpb_js_content_types', $options );
	    }
  	}
  	
  	public static function megamenu_item_config_toplevel( $item ) {
	      $item_id = esc_attr( $item->post_name );
	      $posts_array = self::get_sub_megamenus();
	?>
		<p class="field-icon-font description description-wide">   
			<label for="edit-menu-item-icon-font-<?php echo esc_attr($item_id); ?>"><?php _e( 'Icon Font (Awesome):', 'apus-framework' ); ?> <br>
				<input type="text"  name="menu-item-apus_icon_font[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->apus_icon_font); ?>">
			</label>
			<br>
			<span><?php _e('This support display icon from FontAwsome, Please click <a href="//fontawesome.io/" target="_blank"> <b>here</b></a> to see the list.', 'apus-framework');?></span>
		</p>
		<p class="field-icon-image description description-wide">   
			<label for="edit-menu-item-icon-image-<?php echo esc_attr($item_id); ?>"><?php _e( 'Icon Image:', 'apus-framework' ); ?></label>
			<div class="screenshot">
				<?php if ( $item->apus_icon_image ) { ?>
					<img src="<?php echo esc_url($item->apus_icon_image); ?>" alt="<?php echo esc_attr($item->title); ?>"/>
				<?php } ?>
			</div>
			<input type="hidden" class="upload_image" name="menu-item-apus_icon_image[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->apus_icon_image); ?>">
			<div class="upload_image_action">
				<input type="button" class="button add-image" value="Add">
				<input type="button" class="button remove-image" value="Remove">
			</div>
			<span><?php _e('You can use Icon Font or Icon Image', 'apus-framework');?></span>
		</p>

		<p class="field-addclass description description-wide">
			<label for="edit-menu-item-apus_mega_profile-<?php echo esc_attr($item_id); ?>"> 
			  <?php _e( 'Megamenu Profile' ); ?> <br>
			   	<select name="menu-item-apus_mega_profile[<?php echo esc_attr($item_id); ?>]">
				    <option value=""><?php _e( 'Disable', 'apus-framework' ); ?></option>
				    <?php foreach( $posts_array as $_post ){  ?>
				      <option  value="<?php echo esc_attr($_post->post_name);?>" <?php selected( esc_attr($item->apus_mega_profile), $_post->post_name ); ?> ><?php echo esc_html($_post->post_title); ?></option>
				      <?php } ?>
			  	</select>
			</label>

			<a href="<?php echo  esc_url( admin_url( 'edit.php?post_type=apus_megamenu') ); ?>" target="_blank" title="<?php _e( 'Sub Megamenu Management', 'apus-framework' ); ?>"><?php _e( 'Sub Megamenu Management', 'apus-framework' ); ?></a>
			<span><?php _e( 'If enabled megamenu, its submenu will be disabled', 'apus-framework' ); ?></span>
		</p>

		<p class="field-apus_width description description-wide">   
			<label for="edit-menu-item-apus_width-<?php echo esc_attr($item_id); ?>"><?php _e( 'Width:', 'apus-framework' ); ?> <br>
			    <input type="text"  name="menu-item-apus_width[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->apus_width); ?>">
			</label>
		</p>

		<?php 
			$aligns = array(
			    'left' => __('Left', 'apus-framework'),
			    'right' => __('Right', 'apus-framework'),
			    'fullwidth' => __('Fullwidth', 'apus-framework')
			); 
		?> 
		<p class="field-apus_alignment description description-wide">   
			<label for="edit-menu-item-apus_alignment-<?php echo esc_attr($item_id); ?>"><?php _e( 'Alignment:', 'apus-framework' ); ?> <br>
				<select name="menu-item-apus_alignment[<?php echo esc_attr($item_id); ?>]">
					<?php foreach( $aligns as $key => $align ) { ?>
					<option <?php selected( esc_attr($item->apus_alignment), $key ); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($align); ?></option>
					<?php } ?>
				</select>
			</label>
		</p>

	<?php 
	}

	public static function nav_edit_walker($walker, $menu_id) {
        return 'Apus_Megamenu_Config';
    }

    public static function add_extra_fields_menu_config($item, $depth=0) {   
        $item_id = esc_attr( $item->post_name );
    ?>
        <p class="field-addclass description description-wide">
            <label for="edit-menu-item-apus_text_label-<?php echo esc_attr($item_id); ?>">
                <?php  echo __( 'Label', 'apus-framework' ); ?><br />
                <select name="menu-item-apus_text_label[<?php echo esc_attr($item_id); ?>]">
                  <option value="" <?php selected( esc_attr($item->apus_text_label), '' ); ?>><?php _e('None', 'apus-framework'); ?></option>
                  <option value="label_new" <?php selected( esc_attr($item->apus_text_label), 'label_new' ); ?>><?php _e('New', 'apus-framework'); ?></option>
                  <option value="label_hot" <?php selected( esc_attr($item->apus_text_label), 'label_hot' ); ?>><?php _e('Hot', 'apus-framework'); ?></option>
                  <option value="label_featured" <?php selected( esc_attr($item->apus_text_label), 'label_featured' ); ?>><?php _e('Featured', 'apus-framework'); ?></option>
                </select>
            </label>
        </p>
    <?php
    }

    public static function custom_nav_item($menu_item) {
        $fields = array( 'apus_text_label', 'apus_mega_profile', 'apus_alignment', 'apus_width', 'apus_icon_font', 'apus_icon_image' );
        foreach( $fields as $field ){
            $menu_item->{$field} = get_post_meta( $menu_item->ID, $field, true );
        }
       	
        return $menu_item;
    }

    public static function custom_nav_update($menu_id, $menu_item_db_id, $args ) {
    	$post = get_post($menu_item_db_id);
    	if ( is_object($post) ) {
			$fields = array( 'apus_mega_profile', 'apus_text_label', 'apus_alignment', 'apus_width', 'apus_icon_font', 'apus_icon_image' );

			foreach ( $fields as $field ) {
				if (!isset($_POST['menu-item-'.$field]) || !is_array($_POST['menu-item-'.$field]) || !isset($_POST['menu-item-'.$field][$post->post_name])) {
					if ( !is_array($_POST['menu-item-'.$field]) ) {
						$_POST['menu-item-'.$field] = array($post->post_name => "");
					} else {
						$_POST['menu-item-'.$field][$post->post_name] = "";
					}
				}
				$custom_value = $_POST['menu-item-'.$field][$post->post_name];
				update_post_meta( $menu_item_db_id, $field, $custom_value );
			}
  		}
    }

    public static function get_sub_megamenus() {
	   $args = array(
	      'posts_per_page'   => -1,
	      'offset'           => 0,
	      'category'         => '',
	      'category_name'    => '',
	      'orderby'          => 'post_date',
	      'order'            => 'DESC',
	      'include'          => '',
	      'exclude'          => '',
	      'meta_key'         => '',
	      'meta_value'       => '',
	      'post_type'        => 'apus_megamenu',
	      'post_mime_type'   => '',

	      'post_parent'      => '',
	 
	      'suppress_filters' => true 
	    );
	    return get_posts( $args );  
	}
}

Apus_PostType_Megamenu::init();