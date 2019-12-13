<?php

defined( 'ABSPATH' ) || exit;
/*

*/
add_action('wp_ajax_get_productin_pop', 'get_productin_pop' );
add_action('wp_ajax_nopriv_get_productin_pop', 'get_productin_pop' );
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
/*

*/
add_action('wp_ajax_addproductwithvar2', 'addproductwithvar2' );
add_action('wp_ajax_nopriv_addproductwithvar2', 'addproductwithvar2' );
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
$jk = 0;
foreach($jsondata as $data)
{
  	$abc2 = json_decode($data->sizecolor);
	$description = json_decode($data->description);
	if($abc2 =='')
	{
	 echo $data->sku.',';
	}
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
	  }
  }
 wp_set_object_terms($product_id, $sizevalues, 'pa_size');
 wp_set_object_terms($product_id, $colorarr, 'pa_color');
$availableattr = array('size','color');
 $product_attributes_data = array();
    foreach ($availableattr as $attribute)
    {
        $product_attributes_data['pa_'.$attribute] = array(
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
create_product_variation( $product_id, $variation_data );
}
	if($jk == $number)
	{
		die;
	}
	$jk++;
}
die;
echo "<pre>"; print_r($jsondata); die;
}
/*

*/
add_action('wp_ajax_extraskudelete', 'extraskudelete' );
add_action('wp_ajax_nopriv_extraskudelete', 'extraskudelete' );
function extraskudelete() {
	global $wpdb;
	$livearray = array('2085','2201','2202','2203','2204','2205','2206','2207','2208','2209','2802','2852','2860','2885','A001','A002','A003','A020','A050','A080','A100','A105','A109','A110','A111','A112','A113','A114','A115','A120','A121','A122','A125','A129','A130','A135','A140','A143','A145','A146','A150','A171','A174','A175','A185','A196','A197','A198','A199','A200','A210','A220','A225','A230','A245','A250','A251','A260','A270','A271','A280','A290','A300','A301','A302','A310','A315','A319','A320','A325','A330','A340','A350','A351','A352','A353','A354','A360','A400','A401','A427','A435','A445','A450','A460','A500','A501','A510','A511','A520','A530','A590','A600','A610','A620','A621','A622','A625','A630','A635','A640','A641','A643','A645','A655','A665','A667','A688','A689','A690','A691','A700','A710','A720','A721','A722','A723','A724','A725','A726','A729','A730','A735','A740','A745','A750','A780','A790','A795','A796','A800','A810','A827','A835','A845','A900','A905','A910','A915','A925','A941','AF22','AF50','AF53','AF73','AF82','AF83','AF84','AF91','AM10','AM11','AM12','AM13','AM14','AM15','AM20','AM22','AM23','AM24','AM30','AM31','AP01','AP02','AP30','AP31','AP32','AP50','AP52','AP60','AP62','AP70','AP80','AP81','AP90','AP91','AS10','AS11','AS20','AS21','B010','B013','B023','B024','B028','B029','B120','B121','B123','B130','B131','B133','B140','B151','B153','B175','B185','B195','B209','B210','B212','B300','B302','B307','B309','B310','B900','B901','B903','B904','B905','B907','B908','B909','B910','B912','B916','BIZ1','BIZ2','BIZ4','BIZ5','BIZ6','BIZ7','BP51','BP52','BP90','BT05','BT10','BT20','BZ11','BZ12','BZ30','BZ31','BZ40','C030','C052','C070','C071','C075','C078','C079','C099','C107','C276','C370','C375','C376','C387','C394','C405','C465','C466','C467','C468','C470','C471','C472','C473','C474','C475','C476','C481','C484','C485','C494','C496','C497','C565','C600','C676','C701','C703','C711','C720','C721','C730','C731','C733','C734','C771','C774','C775','C776','C802','C803','C806','C808','C811','C812','C813','C814','C820','C831','C833','C834','C836','C837','C851','C852','C854','C859','C865','C875','C876','C881','C887','C890','CH10','CH11','CH12','CP10','CP21','CR10','CR12','CS10','CS11','CS12','CS20','CS21','CV01','CV02','CV03','CV04','CW10','CW11','CW12','D100','D118','D300','D340','E040','E041','E042','E043','E044','E046','E048','E049','E052','E061','EP01','EP02','EP04','EP06','EP07','EP08','EP16','EP18','EP20','EP21','EP30','F171','F180','F205','F208','F280','F282','F285','F300','F301','F330','F400','F401','F414','F433','F440','F441','F450','F465','F474','F476','F477','F500','F813','FA10','FA11','FA12','FA21','FA22','FA23','FB30','FB31','FB40','FB41','FC01','FC02','FC03','FC04','FC10','FC11','FC12','FC14','FC21','FC41','FC44','FC50','FC52','FC53','FC54','FC55','FC57','FC60','FC61','FC62','FC63','FC64','FC65','FC66','FC67','FC86','FC87','FC88','FC89','FC90','FC94','FC95','FC96','FC97','FD01','FD02','FD09','FD10','FD11','FD15','FD85','FD90','FD95','FF50','FL02','FP02','FP05','FP08','FP10','FP11','FP12','FP13','FP14','FP15','FP18','FP19','FP20','FP21','FP22','FP23','FP26','FP27','FP28','FP29','FP30','FP34','FP35','FP36','FP39','FP40','FP41','FP44','FP45','FP48','FP49','FP50','FP51','FP52','FP62','FP63','FP64','FP65','FP66','FP67','FP68','FP98','FP99','FR01','FR02','FR03','FR06','FR09','FR10','FR11','FR12','FR14','FR18','FR19','FR20','FR21','FR22','FR25','FR26','FR27','FR28','FR30','FR31','FR35','FR36','FR37','FR38','FR41','FR43','FR46','FR47','FR50','FR51','FR52','FR53','FR55','FR56','FR57','FR58','FR59','FR60','FR61','FR62','FR63','FR70','FR71','FR72','FR73','FR74','FR75','FR76','FR77','FR78','FR79','FR80','FR81','FR85','FR89','FR90','FR91','FR92','FR93','FR98','FT12','FT13','FT15','FT25','FT50','FT63','FT64','FW01','FW02','FW03','FW04','FW05','FW06','FW07','FW08','FW09','FW10','FW11','FW12','FW13','FW14','FW15','FW16','FW17','FW18','FW19','FW20','FW21','FW22','FW23','FW24','FW25','FW26','FW28','FW29','FW30','FW31','FW32','FW33','FW34','FW35','FW36','FW37','FW38','FW39','FW40','FW41','FW42','FW43','FW44','FW45','FW46','FW47','FW48','FW49','FW51','FW57','FW58','FW59','FW60','FW61','FW62','FW63','FW64','FW65','FW66','FW67','FW68','FW69','FW71','FW74','FW75','FW80','FW81','FW82','FW83','FW84','FW85','FW86','FW87','FW88','FW89','FW90','FW92','FW93','FW94','FW95','G456','G465','G470','G475','G476','GL10','GL11','GL12','GL13','GL14','GL16','GT10','GT13','GT23','GT27','GT29','GT30','GT33','H440','H441','H442','H443','H444','H445','HA10','HA13','HA14','HB10','HB11','HF50','HV03','HV04','HV05','HV07','HV08','HV09','HV10','HV20','HV21','HV22','HV50','HV55','HV56','ID10','ID11','ID12','ID13','ID20','ID30','IW10','IW30','IW40','IW50','JN12','JN14','JN15','KN09','KN10','KN18','KN20','KN30','KN40','KN90','KN91','KN93','KP05','KP10','KP15','KP20','KP30','KP40','KP44','KP50','KP55','KS10','KS11','KS12','KS13','KS14','KS15','KS18','KS31','KS32','KS40','KS41','KS51','KS54','KS55','KS56','KS60','KS61','KS62','KS63','L440','L470','L474','L476','LJ20','LW12','LW13','LW14','LW15','LW16','LW20','LW30','LW56','LW63','LW70','LW71','LW72','LW97','MT50','MT51','MT52','MV25','MV26','MV27','MV28','MV29','MV35','MV36','MV91','MX28','NX50','P005','P100','P101','P102','P108','P153','P200','P201','P203','P209','P220','P223','P250','P251','P271','P291','P301','P303','P304','P309','P351','P371','P391','P410','P420','P430','P500','P516','P902','P906','P920','P921','P926','P940','P941','P946','P950','P952','P956','P970','P971','P976','PA01','PA02','PA04','PA10','PA30','PA31','PA45','PA46','PA48','PA49','PA50','PA52','PA54','PA55','PA56','PA58','PA60','PA61','PA62','PA63','PA64','PA65','PA66','PA67','PA68','PA69','PA91','PA99','PB10','PB55','PC55','PG54','PJ10','PJ20','PJ50','PJ52','PR32','PS04','PS05','PS11','PS12','PS16','PS21','PS30','PS32','PS33','PS34','PS40','PS41','PS42','PS44','PS46','PS47','PS48','PS50','PS51','PS52','PS53','PS54','PS55','PS58','PS59','PS63','PS90','PS91','PV50','PV54','PV60','PV64','PW11','PW13','PW14','PW15','PW17','PW18','PW20','PW21','PW22','PW23','PW24','PW25','PW26','PW30','PW31','PW32','PW33','PW34','PW340','PW35','PW36','PW37','PW370','PW371','PW38','PW39','PW40','PW41','PW42','PW43','PW45','PW47','PW48','PW50','PW51','PW53','PW54','PW55','PW56','PW57','PW58','PW59','PW60','PW65','PW66','PW68','PW69','PW79','PW80','PW81','PW89','PW90','PW91','PW92','PW93','PW94','PW96','PW97','PW98','PW99','R460','R473','R480','RT19','RT20','RT21','RT22','RT23','RT26','RT27','RT30','RT31','RT32','RT34','RT40','RT42','RT43','RT44','RT45','RT46','RT47','RT48','RT49','RT50','RT51','RT60','RT61','RT62','RT63','S068','S085','S092','S101','S102','S103','S104','S107','S108','S117','S118','S120','S121','S152','S156','S160','S161','S162','S166','S170','S171','S172','S173','S174','S177','S178','S190','S191','S231','S232','S250','S251','S266','S267','S271','S277','S278','S279','S350','S351','S360','S363','S364','S365','S375','S376','S378','S388','S410','S412','S413','S414','S415','S418','S419','S424','S425','S426','S427','S428','S429','S430','S431','S433','S434','S435','S437','S438','S440','S441','S450','S451','S452','S453','S460','S461','S462','S463','S464','S466','S467','S468','S469','S471','S475','S476','S477','S478','S479','S480','S481','S482','S483','S484','S485','S486','S487','S488','S489','S490','S491','S492','S493','S495','S496','S498','S499','S503','S505','S507','S521','S523','S530','S532','S533','S534','S535','S536','S538','S543','S544','S545','S553','S555','S560','S561','S562','S563','S570','S571','S572','S573','S578','S579','S585','S590','S591','S592','S597','S665','S686','S687','S710','S760','S766','S768','S769','S770','S771','S772','S773','S774','S775','S776','S777','S778','S779','S780','S781','S782','S783','S785','S787','S790','S791','S794','S795','S796','S810','S816','S817','S827','S837','S839','S840','S841','S843','S845','S849','S855','S862','S882','S884','S885','S886','S887','S889','S891','S894','S895','S896','S899','S900','S903','S916','S917','S918','S932','S934','S987','S996','S998','S999','SK08','SK10','SK11','SK12','SK13','SK18','SK20','SK33','SM10','SM15','SM20','SM30','SM31','SM33','SM40','SM45','SM50','SM60','SM61','SM63','SM70','SM75','SM80','SM90','SM91','SP01','ST11','ST20','ST30','ST31','ST35','ST36','ST38','ST40','ST41','ST42','ST43','ST44','ST45','ST47','ST50','ST60','ST70','ST80','ST85','SW10','SW20','SW32','SW33','T180','T181','T184','T185','T400','T402','T500','T501','T601','T602','T603','T620','T701','T702','T703','T704','T720','T750','T801','T802','T803','T820','T830','T831','T832','T900','TB02','TB10','TK40','TK41','TK50','TK51','TK52','TK53','TK54','TK83','TX10','TX11','TX12','TX13','TX14','TX15','TX16','TX17','TX18','TX19','TX20','TX22','TX23','TX30','TX32','TX33','TX36','TX37','TX39','TX40','TX45','TX50','TX51','TX52','TX55','TX60','TX61','TX62','TX70','TX71','TX72','VA120','VA198','VA199','VA310','VA350','VA620','VA622','Z456','Z464','Z465','Z524','Z529','Z530','Z531','Z533','Z534','Z541','Z543','Z550','Z551','Z580','Z583','Z586','Z587','Z600','Z601','Z610','Z611','Z612','Z613','Z620','Z622','Z630','Z635','Z636');
	$products = $wpdb->get_results(  "SELECT sku FROM wp_portwestdata"  );
	foreach($products as $gets)
	{
		$skus[] = $gets->sku;
		}
	$i = 0;
	foreach($skus as $sku)
	{
		if(!in_array($sku,$livearray))
		{
		 $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
		  if ( $product_id )
		  {	 echo $sku.',';
			wh_deleteProduct($product_id, TRUE);
		  }
		}
	}
  die('not');
}
/*

*/
add_action('wp_ajax_add_sizeguideofportwest', 'add_sizeguideofportwest' );
add_action('wp_ajax_nopriv_add_sizeguideofportwest', 'add_sizeguideofportwest' );
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
			  update_post_meta($product_id,'sizeguide',esc_attr($sizeguide));
		  }
		}
	} die;
	echo "<pre>"; print_r($result); die;
}
/*

*/
add_action('wp_ajax_update_size_guide_portwest', 'update_size_guide_portwest' );
add_action('wp_ajax_nopriv_update_size_guide_portwest', 'update_size_guide_portwest' );
function update_size_guide_portwest() {
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
				}
			}
		}
	}
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
	}
	die;
}
