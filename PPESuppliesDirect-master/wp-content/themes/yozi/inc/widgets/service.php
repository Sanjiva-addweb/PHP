<?php

class Yozi_Widget_Service extends Apus_Widget {
    public $services;
    public function __construct() {
        $this->services = array(1,2,3,4);
        parent::__construct(
            'apus_service',
            esc_html__('Apus Service Widget', 'yozi'),
            array( 'description' => esc_html__( 'Show Service', 'yozi' ), )
        );
        $this->widgetName = 'service';
        add_action('admin_enqueue_scripts', array($this, 'scripts'));
    }
    
    public function scripts() {
        wp_enqueue_script( 'apus-upload-image', APUS_FRAMEWORK_URL . 'assets/upload.js', array( 'jquery', 'wp-pointer' ), APUS_FRAMEWORK_VERSION, true );
    }

    public function getTemplate() {
        $this->template = 'service.php';
    }

    public function widget( $args, $instance ) {
        $this->display($args, $instance);
    }
    
    public function form( $instance ) {
        $defaults = array(
            'service_icon_1' => '',
            'service_title_1' => '',
            'service_desc_1' => '',

            'service_icon_2' => '',
            'service_title_2' => '',
            'service_desc_2' => '',

            'service_icon_3' => '',
            'service_title_3' => '',
            'service_desc_3' => '',

            'service_icon_4' => '',
            'service_title_4' => '',
            'service_desc_4' => '',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        // Widget admin form

        
        
        ?>
        <div class="services-wrapper">

            <?php foreach ($this->services as $key) { ?>
                <div class="service-item">
                    <h3><?php echo esc_attr('Service Item ').$key; ?></h3>
                    <label><strong><?php esc_html_e( 'Icon:', 'yozi' ); ?></strong></label>
                    
                    <input class="widefat upload_image" name="<?php echo esc_attr($this->get_field_name( 'service_icon_'.$key )); ?>" type="hidden" value="<?php echo esc_attr($instance['service_icon_'.$key]); ?>" />
                    
                    <div class="screenshot">
                        <?php if ( !empty($instance['service_icon_'.$key]) ) { ?>
                            <img src="<?php echo esc_url($instance['service_icon_'.$key]); ?>" style="max-width:100%" alt=""/>
                        <?php } ?>
                    </div>
                    <div class="upload_image_action">
                        <input type="button" class="button add-image" value="<?php esc_html_e('Add', 'yozi');?>">
                        <input type="button" class="button remove-image" value="<?php esc_html_e('Remove', 'yozi');?>">
                    </div>
                    <p>
                        <label><strong><?php esc_html_e('Title:', 'yozi');?></strong></label>
                        <input class="widefat" name="<?php echo esc_attr($this->get_field_name( 'service_title_'.$key )); ?>" type="text" value="<?php echo esc_attr( $instance['service_title_'.$key] ); ?>" />
                    </p>
                    <p>
                        <label><strong><?php esc_html_e('Short Description:', 'yozi');?></strong></label>
                        <textarea name="<?php echo esc_attr($this->get_field_name( 'service_desc_'.$key )); ?>" class="widefat"><?php echo esc_attr( $instance['service_desc_'.$key] ) ; ?></textarea>
                    </p>
                </div>
            <?php } ?>
        </div>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        foreach ($this->services as $key) {
             $instance['service_icon_'.$key] = !empty( $new_instance['service_icon_'.$key] ) ? $new_instance['service_icon_'.$key] : '';
             $instance['service_title_'.$key] = !empty( $new_instance['service_title_'.$key] ) ? $new_instance['service_title_'.$key] : '';
             $instance['service_desc_'.$key] = !empty( $new_instance['service_desc_'.$key] ) ? $new_instance['service_desc_'.$key] : '';
        }
       


        return $instance;
    }
}

register_widget( 'Yozi_Widget_Service' );