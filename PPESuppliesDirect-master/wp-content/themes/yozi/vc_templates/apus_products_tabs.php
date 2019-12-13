<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$_id = yozi_random_key();

if (isset($tabs) && !empty($tabs)):
    $tabs = (array) vc_param_group_parse_atts( $tabs );
    $i = 0;
    $style_tab = (!empty($style_tab)) ? $style_tab : 'tab-product';
    $style_item_product = ($product_item == 'inner' || $product_item == 'inner-v2' ) ? ' item-grid':'';
?>

    <div class="widget widget-products-tabs no-margin <?php echo esc_attr($el_class.' widget-tab-'.$style_tab); ?>">
        <?php if ($title!=''): ?>
            <h3 class="widget-title">
                <?php echo esc_attr( $title ); ?>
            </h3>
        <?php endif; ?>
        <div class="widget-content woocommerce <?php echo esc_attr($layout_type.$style_item_product); ?>">
            <ul role="tablist" class="nav nav-tabs <?php echo esc_attr($style_tab); ?>" data-load="ajax">
                <?php foreach ($tabs as $tab) : ?>
                    <li<?php echo ($i == 0 ? ' class="active"' : ''); ?>>
                        <a href="#tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>">
                            <?php if ( !empty($tab['title']) ) { ?>
                                <?php echo trim($tab['title']); ?>
                            <?php } ?>
                        </a>
                    </li>
                <?php $i++; endforeach; ?>
            </ul>
            <div class="widget-inner">
                <div class="tab-content">
                    <?php $i = 0; foreach ($tabs as $tab) : 
                        $encoded_atts = json_encode( $atts );
                        $encoded_tab = json_encode( $tab );
                    ?>
                        <div id="tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>" class="tab-pane <?php echo ($i == 0 ? 'active' : ''); ?>" data-loaded="<?php echo ($i == 0 ? 'true' : 'false'); ?>" data-settings="<?php echo esc_attr($encoded_atts); ?>" data-tab="<?php echo esc_attr($encoded_tab); ?>">

                            <div class="tab-content-products">
                                <?php if ( $i == 0 ): ?>
                                    <?php
                                        $categories = isset($tab['category']) ? array($tab['category']) : array();
                                        $type = isset($tab['type']) ? $tab['type'] : 'recent_product';
                                        $args = array(
                                            'categories' => $categories,
                                            'product_type' => $type,
                                            'post_per_page' => $number,
                                        );
                                        $loop = yozi_get_products( $args );
                                        $max_pages = $loop->max_num_pages;
                                    ?>

                                    <?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array(
                                        'loop' => $loop,
                                        'columns' => $columns,
                                        'product_item' => $product_item,
                                        'show_nav' => $show_nav,
                                        'show_pagination' => $show_pagination,
                                        'rows' => $rows,
                                    ) ); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    <?php $i++; endforeach; ?>
                </div>
            </div>
            
        </div>
    </div>
<?php endif; ?>