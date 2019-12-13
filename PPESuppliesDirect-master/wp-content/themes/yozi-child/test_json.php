<?php

$str = file_get_contents('https://ppesuppliesdirect.com/tshirtecommerce/data/products.json');
$json = json_decode($str, true); 
echo '<pre>' . var_dump($json, true) . '</pre>';