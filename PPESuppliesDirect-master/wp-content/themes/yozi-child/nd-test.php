<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
die('Go away! Please');

echo 'Start at '.date('G:i:s');
$time = time();


//define('SHORTINIT', true);
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/yozi-child/Excel/PHPExcel.php' );


/* запросы к базе */
echo '<pre>';

$args = [
    'post_type' => 'product',
    'fields' => 'ids',
    'posts_per_page' => -1,
//    'offset'		 => 2000,
];
$query = new WP_Query();
$ids = $query->query($args);

$content = '';
$count = 0;
foreach ($ids as $product_id) {
    $count++;

    $product = wc_get_product($product_id);

    $raw_attributes = $product->get_attributes('edit');
    if (empty($raw_attributes['pa_size']) || empty($raw_attributes['pa_size']->get_slugs())) {
        $sku = $product->get_sku();
        if ($sku) {
            $content .= "$sku\n";
            echo "<b style=\"color:green\">[$count]\t($sku) empty size</b>\n";
        }else
            echo "<b style=\"color:red\">[$count]\t(no sku) empty size</b>\n";
    }
}

/* desc
global $wpdb;
$rows = $wpdb->get_results( "SELECT ID FROM wp_posts WHERE post_type='product' AND post_content=''" );
echo 'count: ' . count($rows) . "\n";
foreach ($rows as $row) {
    $product = wc_get_product($row->ID);
    $sku = $product->get_sku();
    $content .= "$sku\n";
//    $rows = $wpdb->get_results( "SELECT product_code, priceinfo FROM wp_pricing_table" );
}
/* end desc */

$c = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/empty-size.txt', $content);
echo $c ? 'done' : 'nope';

echo "\n";
echo 'End at '.date('G:i:s').' ('.date('i:s',time()-$time).')';
echo "\n";
echo '</pre>';
die();
/* конец запросов */


/* перенос цен из excel файла */
echo '<pre>';
$all_count = 0;
$count_prod = 0;
$count_child = 0;
$skipped = 0;
$empty = 0;

$excel = PHPExcel_IOFactory::load($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/yozi-child/all_prices.xlsx');
$ActiveSheet = $excel->getActiveSheet();

$row=0;
while( $row++<5000 ) {
    $sku = $ActiveSheet->getCellByColumnAndRow(0, $row)->getValue();
    if ( empty($sku) ){
        $empty++;
        continue;
    }

    $id = wc_get_product_id_by_sku($sku);
    if( empty($id) ) {
        $skipped++;
        continue;
    }

    $product = wc_get_product($id);

    $value = round($ActiveSheet->getCellByColumnAndRow(1, $row)->getCalculatedValue(), 2 );
    $t1 = update_post_meta($id, '_qty1to9_productc_qty', $value);
    $value = round($ActiveSheet->getCellByColumnAndRow(2, $row)->getCalculatedValue(), 2 );
    $t2 = update_post_meta($id, '_qty10to19_productc_qty', $value);
    $value = round($ActiveSheet->getCellByColumnAndRow(3, $row)->getCalculatedValue(), 2 );
    $t3 = update_post_meta($id, '_qty20to49_productc_qty', $value);
    $value = round($ActiveSheet->getCellByColumnAndRow(4, $row)->getCalculatedValue(), 2 );
    $t4 = update_post_meta($id, '_qty50to99_productc_qty', $value);
    $value = round($ActiveSheet->getCellByColumnAndRow(5, $row)->getCalculatedValue(), 2 );
    $t5 = update_post_meta($id, '_qty100to249_productc_qty', $value);
    $value = round($ActiveSheet->getCellByColumnAndRow(6, $row)->getCalculatedValue(), 2 );
    $t6 = update_post_meta($id, '_qty250to499_productc_qty', $value);
    $tp = update_post_meta($id, '_price', $value);
    $trp= update_post_meta($id, '_regular_price', $value);
    echo "[$row]\t($id) {".
        ($t1 ?'':'<b style="color:red">t1:fall</b> ').
        ($t2 ?'':'<b style="color:red">t2:fall</b> ').
        ($t3 ?'':'<b style="color:red">t3:fall</b> ').
        ($t4 ?'':'<b style="color:red">t4:fall</b> ').
        ($t5 ?'':'<b style="color:red">t5:fall</b> ').
        ($t6 ?'':'<b style="color:red">t6:fall</b> ').
        ($tp ?'':'<b style="color:red">tp:fall</b> ').
        ($trp?'':'<b style="color:red">trp:fall</b> ')."}\n";

    if ($product->is_type('variable')) {
        $children = $product->get_children();
        foreach ($children as $child) {
            $tcp = update_post_meta($child, '_price', $value);
            $tcrp= update_post_meta($child, '_regular_price', $value);
            echo "-[$row]\t($child) {".
                ($tcp ?'':'<b style="color:red">tcp:fall</b> ').
                ($tcrp?'':'<b style="color:red">tcrp:fall</b> ')."}\n";
            $count_child++;
            $all_count++;
        }
    }
    $count_prod++;
    $all_count++;
}
echo "\n";
echo "all:$all_count\n";
echo "product:$count_prod\n";
echo "children:$count_child\n";
echo "skipped:$skipped\n";
echo "empty:$empty\n";
echo "end row:$row\n";
echo 'End at '.date('G:i:s').' ('.date('i:s',time()-$time).')';
echo "\n";
echo '</pre>';
die();
/* конец блока переноса цен */



/* Перегон в simple */
echo '<pre>';
$args = [
    'post_type' => 'product',
    'fields'    => 'ids',
    'posts_per_page' => 4500,
//    'offset'		 => 3500,
];
$query = new WP_Query();
$ids = $query->query($args);

$i = 0;
foreach ($ids as $id) {
    $i++;
    $product = wc_get_product($id);
    if ($product === false) {
        continue;
    }
    echo "[$i]\t($id) ";
    if ($product->is_type('variable')) {
        $children = $product->get_children();
        $c = count($children);
        echo "{".$c."}";
        if ($c === 1) {
            $d = wp_set_object_terms( $id, 'simple', 'product_type', false );
            echo ($d && !is_wp_error($d)) ? "<b style=\"color:green\"> done</b>\n":"<b style=\"color:red\"> fail</b>\n";
        }else{
            echo "<b style=\"color:orange\"> skipped</b>\n";
        }
    }else{
        echo "<b style=\"color:orange\"> NOT variable</b>\n";
    }
}

echo "\n";
echo "count:$i\n";
echo 'End at '.date('G:i:s').' ('.date('i:s',time()-$time).')';
echo "\n\n";
echo '</pre>';
die();
/* Конец перегона в simple */



/* Перенос цветов */
echo '<pre>';

$excel = PHPExcel_IOFactory::load('Colour Filter.xlsx');
$ActiveSheet = $excel->getActiveSheet();

for ($i = 0, $colors = []; ( $key = $ActiveSheet->getCellByColumnAndRow($i,1)->getValue() ) && $i<20; $i++) {
    $term = get_term_by( 'name', $key, 'pa_colour' );
//    echo "[$i]\t($term->term_id) $key\n";
    for ( $j=2; ( $val = $ActiveSheet->getCellByColumnAndRow($i,$j)->getValue() ) && $j<350; $j++ ) {
//        echo "- [". ($j-1) ."]\t$val\n";
        $colors[$term->term_id][] = mb_strtolower($val);
    }
}
//print_r($colors);

$args = [
    'post_type' => 'product',
    'fields' => 'ids',
    'posts_per_page' => -1,
//    'offset'		 => 2000,
];
$query = new WP_Query();
$ids = $query->query($args);

$cash = unserialize(file_get_contents('nd-cash'));
if (!$cash) $cash=[];

$count = 0;
$err = is_array($cash['err'])? $cash['err']:[];
$skipped = is_array($cash['skipped'])? $cash['skipped']:[];
$done_ids = is_array($cash['done_ids'])? $cash['done_ids']:[];

$try_again = false; // пытаться ли прогнать ошибки повторно?

foreach ($ids as $product_id) {
    $count++;
    if (in_array($product_id, $done_ids)){
        echo "<b style=\"color:green\">[$count]\t($product_id) {-}</b>\n";
        continue;
    }
    if (in_array($product_id, $skipped)) {
        echo "<b style=\"color:orange\">[$count]\t($product_id) {-} skipped</b>\n";
        continue;
    }
    if (in_array($product_id, $err) && !$try_again) {
        echo "<b style=\"color:red\">[$count]\t($product_id) {-} err</b>\n";
        continue;
    }

    $product = wc_get_product($product_id);

//$atr = $product->get_attribute('color');
//$atr = wc_get_product_terms( $product_id, 'color', array( 'fields' => 'names' ) );
    $raw_attributes = $product->get_attributes('edit');
    if (empty($raw_attributes['pa_color']) || empty($raw_attributes['pa_color']->get_slugs())) {
        $skipped[] = $product_id;
        echo "<b style=\"color:orange\">[$count]\t($product_id) {-} skipped</b>\n";
        continue;
    }
    $current_colors = $raw_attributes['pa_color']->get_terms(); //get_slugs();
    $current_colors = array_map(function($term){
        return $term->name;
    }, $current_colors);

//    print_r($current_colors); continue;

    $options = [];
    foreach ($current_colors as $current_color) {
        $current_color = mb_strtolower($current_color);
        foreach ($colors as $key => $array_colors) {
            if (in_array($current_color, $array_colors) || in_array(str_replace('_', ' ', $current_color), $array_colors)) {
                $options[] = $key;
                break;
            }
        }
    }
    $options = array_unique($options);

    $attr = new WC_Product_Attribute;
    $attr->set_id(4);
    $attr->set_name('pa_colour');
    $attr->set_position(2);
    $attr->set_visible(true);
    $attr->set_variation(false);
    $attr->set_options($options);

    $raw_attributes['pa_colour'] = $attr;
    $product->set_attributes($raw_attributes);
    $id = $product->save();

    if ( count($options) ) {
        echo "<b style=\"color:green\">[$count]\t($id) {" . count($options) . "}</b>\n";
        $done_ids[] = $id;
        if ($todel=array_search($id,$err)) unset($err[$todel]);
    } else {
        echo "<b style=\"color:red\">[$count]\t($id) {" . count($options) . "} err</b>\n";
        $err[] = $id;
    }
}
$done_ids = array_unique($done_ids);
$skipped = array_unique($skipped);
$err = array_unique($err);
$cash['done_ids'] = $done_ids;
$cash['skipped'] = $skipped;
$cash['err'] = $err;
file_put_contents('nd-cash', serialize($cash));

echo "\n";
echo "count:$count\n";
echo "\nALL:\ndone:".count($done_ids)."\nerr:".count($err)."\nskipped:".count($skipped)."\n";
echo 'End at '.date('G:i:s').' ('.date('i:s',time()-$time).')';
echo "\n\n";
echo '</pre>';
die();
/* конец переноса цветов */






//$product = wc_get_product( array_shift($ids) );

$i = 0;
$j = 0;

global $wpdb;
$rows = $wpdb->get_results( "SELECT product_code, priceinfo FROM wp_pricing_table" );
echo "rows: " . count($ids) . "\n";
//foreach ($rows as $row) {
foreach ($ids as $id) {
    $i++;
//    $id = wc_get_product_id_by_sku($row->product_code);
    $product = wc_get_product($id);
    if ($product === false) {
        continue;
    }
    if ($product->is_type('variable')) {
        echo "[$i]\t($id)\n";
        $children = $product->get_children();
        foreach ($children as $child) {
            $ss = update_post_meta($child, '_stock_status', 'instock');
            $ms = update_post_meta($child, '_manage_stock', 'no');
            $j++;
            echo "\t[$j]\t($child)\t";
            echo $ss ? 'change' : ' skip ';
            echo '/';
            echo $ms ? 'change' : 'skip';
            echo "\n";
        }
    }
//    echo ( $product->is_type('variable')?'variable':'NOT variable' )."\n";

//    $sku = $product->get_sku();
//    $row = $wpdb->get_results("SELECT product_code, priceinfo FROM wp_pricing_table WHERE product_code='$sku'");
    //$prices = json_decode($row->priceinfo);
    if (false && $prices) { // && is_array($prices)
        foreach ($prices as $key => $value) {
            $value = str_replace('£', '', $value);
            if ($key === '1-9') {
                update_post_meta($id, '_qty1to9_productc_qty', $value);
            } elseif ($key === '10-19') {
                update_post_meta($id, '_qty10to19_productc_qty', $value);
            } elseif ($key === '20-49') {
                update_post_meta($id, '_qty20to49_productc_qty', $value);
            } elseif ($key === '50-99') {
                update_post_meta($id, '_qty50to99_productc_qty', $value);
            } elseif ($key === '100-249') {
                update_post_meta($id, '_qty100to249_productc_qty', $value);
            } elseif ($key === '250 to 499') {
                update_post_meta($id, '_qty250to499_productc_qty', $value);
                update_post_meta($id, '_price', $value);
                update_post_meta($id, '_regular_price', $value);
                if ($product->is_type('variable')) {
                    $children = $product->get_children();
                    foreach ($children as $child) {
                        update_post_meta($child, '_regular_price', $value);
                        update_post_meta($child, '_price', $value);
                        $j++;
                    }
                }
            }
        }
    }
}

echo "\nchild:$j\nparent:$i\nall_parent:".count($ids)."\n";
//echo " rp:$rp \n p:$p \n product:" . count($ids);
echo 'End at '.date('G:i:s');
echo '</pre>';