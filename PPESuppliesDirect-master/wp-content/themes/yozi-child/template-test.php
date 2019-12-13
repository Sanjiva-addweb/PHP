<?php
/**
 * Template Name: Test
 */

$str = file_get_contents('https://ppesuppliesdirect.com/tshirtecommerce/data/products.json');
$json = json_decode($str, true); 
echo '<pre>' . print_r($json, true) . '</pre>';