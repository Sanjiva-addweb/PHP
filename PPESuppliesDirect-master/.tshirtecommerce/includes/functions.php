<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('ROOT')) exit('No direct script access allowed');
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
global $wp_query;
class dg{
	
	public function __construct()
	{
		$this->path_data = ROOT .DS. 'data';
		$this->components = ROOT .DS. 'components';

		$this->platform = $this->getPlatform();
	}

	public function getPlatform()
	{
		$platform = 'wordpress';

		$file = ROOT.'/version.json';

		if (file_exists($file)) {
			$json = json_decode(file_get_contents($file), true);
			
			if (isset($json['platforms'])) {
				$platform = $json['platforms'];
			}
		}

		return $platform;
	}
	
	public function url(){
		$pageURL = 'http';
		
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || (isset($_SERVER['HTTP_HTTPSSL']) && $_SERVER['HTTP_HTTPSSL'] == true)) {$pageURL .= "s";}
		
		$pageURL .= "://";
		$pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		
		$url = explode('tshirtecommerce/', $pageURL);
		
		return $url[0];
	}
	
	function openURL($url)
	{
		$data = false;
		if( function_exists('curl_exec') )
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			curl_close($ch);
		}
		
		if( $data == false && function_exists('file_get_contents') )
		{
			$data = file_get_contents($url);
		}
		
		return $data;
	}
	
	// load language
	public function lang($file = 'lang.ini', $cookie = true)
	{
		$file_lang = ROOT .DS. 'data' .DS. 'languages.json';
		$lang = '';
		if (file_exists($file_lang))
		{			
			$languages = json_decode(file_get_contents($file_lang));
			if (count($languages))
			{
				if (!empty($_GET['lang']))
				{
					$lang = $_GET['lang'];
					$file_active = 'language_'.$lang.'.ini';
				}
				elseif (!empty($_POST['lang']))
				{
					$lang = $_POST['lang'];
					$file_active = 'language_'.$lang.'.ini';
				}				
				
				$check = true;
				if (isset($file_active))
				{
					$check_published = true;
					foreach($languages as $language)
					{
						if ($lang == $language->code)
						{
							if (isset($language->published) && $language->published == 0)
							{
								$check_published = false;
							}
							break;
						}
					}
					
					if (file_exists($this->path_data .DS. $file_active) && $check_published == true)
					{
						$file = $file_active;
						$check = false;
					}
				}
				
				if($check == true)
				{
					foreach($languages as $language)
					{
						if (isset($language->default) && $language->default == 1)
						{
							if (file_exists(ROOT .DS. 'data' .DS. $language->file))
							{
								$file = $language->file;
								$lang = $language->code;
							}
						}
					}
				}
			}
		}
		$file = $this->path_data .DS. $file;		
						
		$GLOBALS['lang_active'] = $lang;
		
		if (file_exists($file))
		{
			$data = parse_ini_file($file);
			if ($data === false || $data == null)
			{
				$content 	= file_get_contents($file);
				$data 	= parse_ini_string($content);
			}
		}
		else
		{
			$data = array();
		}
		
		// update text from extra file
		$lang_plus = $this->path_data .DS. 'lang_plus.ini';
		if (file_exists($lang_plus))
		{
			$langs = parse_ini_file($lang_plus);
			if ($langs === false || $langs == null)
			{
				$content 		= file_get_contents($lang_plus);
				$langs 		= parse_ini_string($content);
			}
			
			if (count($langs))
			{
				foreach($langs as $key => $text)
				{
					if (empty($data[$key]))
					{
						$data[$key] = $text;
					}
				}
			}
		}
		
		return $data;
	}
	
	// load themes
	public function theme($name = 'index', $product = array())
	{
		$theme = '';
		if(isset($product->theme) && $product->theme != '')
		{
			$layouts = $this->getLayouts();
			if( $layouts != false && count($layouts) && isset($layouts[$product->theme]) )
			{
				$layout = $layouts[$product->theme];
				$new_file = ROOT .DS. 'themes' .DS. $layout['theme'] .DS. $name. '.php';
				
				if (file_exists($new_file))
				{
					$theme 		= $layout['theme'];
					$settings	= new StdClass();
					$settings->theme = new StdClass();
					$settings->theme->{$theme} = json_decode(json_encode($layout['options']));
					require_once($new_file);
				}
			}
			
		}
		
		if (isset($this->settings) && $theme == '')
		{	
			$settings = $this->settings;
			
			if ( isset($settings->themes) && $settings->themes != '' )
			{
				$new_file = ROOT .DS. 'themes' .DS. $settings->themes .DS. $name. '.php';				
				if (file_exists($new_file))
				{
					$theme = $settings->themes;
					require_once($new_file);
				}
			}
		}
		$this->theme_active = $theme;
	}
	
	public function getLayouts()
	{
		$file 		= ROOT .DS. 'data' .DS. 'layouts.json';
		
		if(!file_exists($file))
		{
			return false;
		}
		
		$content 		= file_get_contents($file);
		if($content !== false)
		{
			$layouts 			= json_decode($content, true);
			return $layouts;
		}
		
		return false;
	}
	
	// load view layout
	public function view($name, $path = '')
	{
		$file 	= $this->components .DS. $name. '.php';
		$file_new 	= $file;
		if ($path != '')
		{
			$file_new = $this->components .DS. $path .DS. $name. '.php';
		}
		
		$theme = '';
		if(isset($this->product->theme) && $this->product->theme != '')
		{
			$layouts = $this->getLayouts();
			if( $layouts != false && count($layouts) && isset($layouts[$this->product->theme]) )
			{
				$layout	 		= $layouts[$this->product->theme];
				$theme 			= $layout['theme'];
				if($path != '')
				{
					$temp 		= ROOT .DS. 'themes' .DS. $layout['theme'] .DS. 'components' .DS. $path .DS. $name. '.php';
					if (file_exists($temp))
					{
						$file_new1 	= $temp;
					}
				}
				if(isset($file_new1))
				{
					$file_new = $file_new1;
				}
				else
				{
					$temp 		= ROOT .DS. 'themes' .DS. $layout['theme'] .DS. 'components' .DS. $name. '.php';
					if (file_exists($temp))
					{
						$file_new = $temp;
					}
				}
			}
			
		}
		
		if ( isset($this->settings) && $theme == '' )
		{
			$settings = $this->settings;
			
			if ( isset($settings->themes) && $settings->themes != '' )
			{
				if($path != '')
				{
					$temp 		= ROOT .DS. 'themes' .DS. $settings->themes .DS. 'components' .DS. $path .DS. $name. '.php';
					if (file_exists($temp))
					{
						$file_new1 	= $temp;
					}
				}
				if(isset($file_new1))
				{
					$file_new = $file_new1;
				}
				else
				{
					$temp = ROOT .DS. 'themes' .DS. $settings->themes .DS. 'components' .DS. $name. '.php';				
					if (file_exists($temp))
					{
						$file_new = $temp;
					}
				}
				
			}
		}
		
		if (file_exists($file_new))
		{
			require_once($file_new);
		}
		elseif(file_exists($file))
		{
			require_once($file);
		}	
	}
	
	// get products
	public function getProducts()
	{
		$file = $this->path_data .DS. 'products.json';		
		if (file_exists($file))
		{
			$data 		= file_get_contents($file);
			$products 	= json_decode($data);			
			return $products->products;
		}
		else
		{
			return array();
		}
	}
	
	// get attribute of product
	public function getAttributes($attribute)
	{
		if (isset($attribute->name) && $attribute->name != '')
		{
			$attrs = new stdClass();
			
			if (is_string($attribute->name))
				$attrs->name 		= json_decode($attribute->name);
			else
				$attrs->name 		= $attribute->name;
			
			if (is_string($attribute->titles))
				$attrs->titles 		= json_decode($attribute->titles);
			else
				$attrs->titles 		= $attribute->titles;
			
			if (is_string($attribute->prices))
				$attrs->prices 		= json_decode($attribute->prices);
			else
				$attrs->prices 		= $attribute->prices;
			
			if (is_string($attribute->type))
				$attrs->type 		= json_decode($attribute->type);
			else
				$attrs->type 		= $attribute->type;

			if(empty($attribute->obj))
			{
				$attribute->obj = array();
			}
			if (is_string($attribute->obj))
				$attrs->obj 		= json_decode($attribute->obj);
			else
				$attrs->obj 		= $attribute->obj;

			if(empty($attribute->required))
			{
				$attribute->required = array();
			}
			if (is_string($attribute->required))
				$attrs->required 		= json_decode($attribute->required);
			else
				$attrs->required 		= $attribute->required;

			if(empty($attribute->value))
			{
				$attribute->value = array();
			}
			if (is_string($attribute->value))
				$attrs->value 		= json_decode($attribute->value);
			else
				$attrs->value 		= $attribute->value;
			
			$html 				= '';
			$setttings 	= $this->getSetting();
			for ($i=0; $i<count($attrs->name); $i++)
			{
				$html 	.= '<div class="form-group product-fields">';
				$html 	.= 		'<label for="fields">'.$attrs->name[$i].'</label>';
				
				$id 	 	= 'attribute['.$i.']';
				$options 	= array(
					'name' => $attrs->name[$i],
					'title' => $attrs->titles[$i],
					'price' => $attrs->prices[$i],
					'type' => $attrs->type[$i],
					'id' => $id,
				);
				$options['required'] 	= 0;
				if(isset($attrs->required[$i]))
				{
					$options['required'] 	= $attrs->required[$i];
				}
				if( isset($attrs->obj[$i]) && $attrs->obj[$i] != '' && $attrs->obj[$i] != 'none' )
				{
					$options['obj'] 		= $attrs->obj[$i];
					$options['value'] 	= $attrs->value[$i];

					$html 	.= $this->field_action($options, $setttings);
				}
				else
				{
					$html 	.= $this->field($options, $setttings);
				}
				
				$html 	.= '</div>';
			}
			return $html;
		}
		else
		{
			return '';
		}
	
	}
	
	function attributePrice($price, $setttings)
	{
		$html = '';
		
		if ($price != '' && $price != '0')
		{
			if ( isset($setttings->currency_symbol) )
				$currency = $setttings->currency_symbol;
			else
				$currency = '$';
			
			if ( strpos($price, '-') !== false)
			{
				$price = str_replace('-', '', $price);
				$add 	= '-';
			}
			else if (strpos($price, '+') !== false)
			{
				$price = str_replace('+', '', $price);
				$add 	= '+';
			}
			else
			{
				$price = $price;
				$add 	= '+';
			}
			
			if (isset($setttings->currency_postion) && $setttings->currency_postion == 'right')
				$html = ' ('.$add.$price.$currency.')';
			else
				$html = ' ('.$add.$currency.$price.')';
		}
		return $html;
	}
	
	function field_action($options, $setttings)
	{
		$name 		= $options['name'];
		$obj 		= $options['obj'];
		$obj_value 	= $options['value'];
		$required 	= $options['required'];
		$title 		= $options['title'];
		$price 		= $options['price'];
		$type 		= $options['type'];
		$id 		= $options['id'];

		$class_required = '';
		if($required == 1)
		{
			$class_required = 'required';
		}

		$action = "design.attribute.init(this, '".$obj."')";
		if($obj == 'image')
		{
			$action = 'onclick="'.$action.'"';
		}
		else
		{
			$action = 'onchange="'.$action.'"';
		}

		$html = '<div class="dg-poduct-fields '.$class_required.'">';
		switch($obj)
		{
			case 'image':
				for ($i=0; $i<count($title); $i++)
				{
					if( isset($obj_value[$i]) &&  $obj_value[$i] != '')
					{
						$html_price = $this->attributePrice($price[$i], $setttings);

						$html .= '<label class="radio-inline attr-img pull-left dg-tooltip" title="'.htmlentities($title[$i]).$html_price.'">';
						$html .= 	'<img '.$action.' src="'.$obj_value[$i].'" class="img-responsive" alt="'.$title[$i].'" width="10">';
						$html .= 	'<input type="radio" name="'.$id.'" value="'.$i.'">';
						$html .= '</label>';
					}
				}
			break;
			
			default:
				$html .= '<select '.$action.' class="form-control input-sm '.$class_required.'" name="'.$id.'">';
				
				for ($i=0; $i<count($title); $i++)
				{
					if ($price[$i] != '0')
						$html_price = $this->attributePrice($price[$i], $setttings);
					else
						$html_price = '';
					
					$html .= '<option data-value="'.$obj_value[$i].'" value="'.$i.'">'.$title[$i].$html_price.'</option>';
				}
				
				$html .= '</select>';
			break;
		}
		$html	.= '</div>';
		
		return $html;
	}

	function field($options, $setttings)
	{
		$name 	= $options['name'];
		$title 	= $options['title'];
		$price 	= $options['price'];
		$type 	= $options['type'];
		$id 		= $options['id'];

		$class_required = '';
		if(isset($options['required']) && $options['required'] == 1)
		{
			$class_required = 'required';
		}

		$html = '<div class="dg-poduct-fields '.$class_required.'" data-type="'.$type.'">';
		switch($type)
		{
			case 'checkbox':
				for ($i=0; $i<count($title); $i++)
				{
					$html .= '<label class="checkbox-inline">';
					$html .= 	'<input type="checkbox" name="'.$id.'['.$i.']" value="'.$i.'"> '.$title[$i];					
					
					$html .= $this->attributePrice($price[$i], $setttings);
					
					$html .= '</label>';
				}
			break;
			
			case 'selectbox':
				$html .= '<select class="form-control input-sm" name="'.$id.'">';
				
				for ($i=0; $i<count((array)$title); $i++)
				{
					if ($price[$i] != '0')
						$html_price = $this->attributePrice($price[$i], $setttings);
					else
						$html_price = '';
					
					$html .= '<option value="'.$i.'">'.$title[$i].$html_price.'</option>';
				}
				
				$html .= '</select>';
			break;
			
			case 'radio':
				for ($i=0; $i<count($title); $i++)
				{
					$html .= '<label class="radio-inline">';
					$html .= 	'<input type="radio" name="'.$id.'" value="'.$i.'"> '.$title[$i];
					$html .= $this->attributePrice($price[$i], $setttings);
					$html .= '</label>';
				}
			break;
			
			case 'textlist':
				$html 		.= '<style>.product-quantity{display:none;}</style><ul class="p-color-sizes list-number col-md-12">';
				for ($i=0; $i<count((array)$title); $i++)
				{
					$html .= '<li>';
					
					if ($price[$i] != '0')
						$html_price = '<small>'.$this->attributePrice($price[$i], $setttings).'</small>';
					else
						$html_price = '';
					
					$html .= 	'<label data-id="'.$title[$i].'">'.$title[$i].$html_price.'</label>';
					$html .= 	'<input type="text" class="form-control input-sm size-number" name="'.$id.'['.$i.']">';					
					$html .= '</li>';
				}
				$html 		.= '</ul>';
			break;
		}
		$html	.= '</div>';
		
		return $html;
	}
	
	public function quantity($min = 1, $name = 'Quantity', $name2 = 'minimum quantity: '){
		$min = (int) $min; // fix default quantity.
		if ($min < 0) $min = 0; // fix default quantity.
		
		$html = '<div class="form-group product-fields product-quantity">';
		$html .= 	'<label>'.$name.'</label>';
		$html .= 	'<input type="text" class="form-control input-sm" value="'.$min.'" data-count="'.$min.'" name="quantity" id="quantity">';
		$html .= '</div>';
		
		$css = '';
		if($min <= 1)
		{
			$css = 'style="display:none;"';
		}
		$html .= '<div class="form-group product-fields" '.$css.'>'
			  . '<span class="help-block"><small>'.$name2.$min.'</small></span>'
			  . '</div>';
		
		return $html;
	}
	
	// get products
	public function getSetting()
	{
		$file = $this->path_data .DS. 'settings.json';		
		if (file_exists($file))
		{
			$data 		= file_get_contents($file);			
			$settings 	= json_decode($data);			
			return $settings;
		}
		else
		{
			return array();
		}
	}
	
	/**
	 * Write File
	 *
	 * Writes data to the file specified in the path.
	 * Creates a new file if non-existent.
	 *
	 * @access	public
	 * @param	string	path to file
	 * @param	string	file data
	 * @return	bool
	 */
	public function WriteFile($path, $data)
	{
		if ( ! $fp = fopen($path, 'w'))
		{
			return FALSE;
		}

		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);

		return TRUE;
	}
	
	public function folder($type = 'uploaded')
	{
		$date 	= new DateTime();
		$year	= $date->format('Y');
		$root 	= $type .DS. $year;
		if (!file_exists(ROOT .DS. $root))
			mkdir(ROOT .DS. $root, 0755);
		
		$month 	= $date->format('m');
		$root 	= $root .DS. $month .DS;
		if (!file_exists(ROOT .DS. $root))
			mkdir(ROOT .DS. $root, 0755);
		
		return $root;
	}
	
	// get all file in foder
	public function getFiles($path, $exten = '.txt')
	{
		if (file_exists($path))
		{
			$files = scandir($path);
			if (count($files) == 0)
				return false;
			
			$list = array();
			for($i=0; $i<count($files); $i++)
			{
				if (strpos($files[$i], $exten) > 0)
				{
					$list[] = $files[$i];
				}
			}
			if (count($list) == 0) return false;
			
			return $list;
		}
		else
		{
			return false;
		}
	}
	
	// qrcode
	public function qrcode($text)
	{	
		include_once ROOT .DS. 'includes' .DS. 'libraries' .DS. 'qrcode.php';
		$qr = new qrcode();
		$qr->setText($text);
		
		$image = $qr->getImage(500);
		
		$root = $this->folder();
		
		$file = 'qrcode-'.strtotime("now") . '.png';
		
		$this->WriteFile(ROOT .DS. $root . $file, $image);
		
		return str_replace('\\', '/', $root .DS. $file);
	}
	
	// categories art
	public function categoriestree($return = true)
	{
		$path = ROOT .DS. 'data' .DS. 'categories_art.json';
		$categories = array();
		if (file_exists($path))
		{
			$str	= file_get_contents($path);
			$categories = json_decode($str);
			if (count($categories) > 0)
			{
				$new = array();
				foreach ($categories as $a){
					if ($a->id == 0) continue;
					$new[$a->parent_id][] = $a;
				}
				if (isset($new[0]))
					$tree = $this->createTree($new, $new[0]);
				else
					$tree = $this->createTree($new, $new);
				
				$categories = $tree;
			}
		}
		$all 				= array();
		$all[0]			= new stdClass();
		$all[0]->id 		= 0;
		$all[0]->title 		= lang('design_all_art', true);
		$all[0]->children 	= array();
		$all[0]->parent_id 	= 0;
			
			
		$categories = array_merge($all, $categories);
		
		if ($return === true)
		{
			return $categories;
			
		}
		else
		{
			echo json_encode($categories);
			exit();
		}
	}
	
	public function createTree(&$list, $parent){
		$tree = array();
		foreach ($parent as $k=>$l){
			if(isset($list[$l->id])){
				$l->children = $this->createTree($list, $list[$l->id]);
				if ( count($l->children) > 0) $l->isFolder = true;	
			}
			$tree[] = $l;
		} 
		return $tree;
	}
	
	// setup cache
	public function cache($folder = 'design')
	{
		require_once ROOT .DS. 'includes' .DS. 'libraries' .DS. 'phpfastcache.php';
		phpFastCache::setup("storage", "files");
		phpFastCache::setup("path", ROOT .DS. 'cache');
		phpFastCache::setup("securityKey", $folder);
		$cache = phpFastCache();
		
		return $cache;
	}
	
	public function saveDesign()
	{
		$results	= array();
		
		$data = json_decode(file_get_contents('php://input'), true);

		if (session_id() === "")
		{
			session_start(); 
		}
		
		if (empty($_SESSION['is_logged']) || (isset($_SESSION['is_logged']) && $_SESSION['is_logged'] === false && $_SESSION['is_logged'] === false && empty($data['is_share'])))
		{
			$results['error'] = 1;
			$results['login'] = 1;
			$results['msg']	= lang('design_save_login');
			echo json_encode($results);
			exit;
		}		
		
		// check user login
		if(isset($_SESSION['is_logged']))
		{
			$is_logged 		= $_SESSION['is_logged'];
		}
		else
		{
			$is_logged 		= array(
				'id' 	=> time(),
				'is_admin' => false
			);
		}
		$user 		= md5($is_logged['id']);
		
		$uploaded 	= $this->folder();
		$path		= ROOT .DS. $uploaded;
		
		if($data['isIE'] == 'true')
		{
			$buffer		= $data['image'];
		}
		else
		{
			$temp 		= explode(';base64,', $data['image']);
			$buffer		= base64_decode($temp[1]);
		}
		
		$design 					= array();
		
		if (isset($data['options']))
		{
			$design['options']		= $data['options'];	
		}
		
		if(empty($data['teams'])) $data['teams'] = '';
		if(empty($data['design_file'])) $data['design_file'] = '';
		if(empty($data['design_key'])) $data['design_key'] = '';

		$design['vectors']		= $data['vectors'];		
		$design['teams']			= $data['teams'];	
		$design['fonts']			= $data['fonts'];
				
		$designer_id			= $data['designer_id'];
		
		// check design and author
		if ($data['design_file'] != '' && $designer_id == $user && $data['design_key'] != '')
		{
			// override file and update
			$temp = explode('/', $data['design_file']);
			$file = $temp[count($temp) - 1];
			if($data['isIE'] == 'true')
			{
				$file		= str_replace('.png', '.svg', $file);
			}
			else
			{
				$file		= str_replace('.svg', '.png', $file);
			}
			
			$path_file			= $path . $file;	
			$file				= str_replace('\\', '/', $uploaded) .'/'. $file;
			$file				= str_replace('//', '/', $file);
			$key				= $data['design_key'];
			$design['design_id'] 	= $key;
		}
		else
		{
			
			$key 		= strtotime("now"). rand();
			if($data['isIE'] == 'true')
			{
				$file 	=  'design-' . $key . '.svg';
			}
			else
			{
				$file 	=  'design-' . $key . '.png';
			}
			
			$path_file	= $path .DS. $file;
			$file		= str_replace('\\', '/', $uploaded) .'/'. $file;
			$file		= str_replace('//', '/', $file);		
			
			$design['design_id'] 		= $key;
		}
		if ( ! $this->WriteFile($path_file, $buffer))
		{
			$results['error'] = 1;
			$results['msg']	= lang('design_msg_save', true);
		}
		else
		{
			if (isset($is_logged['is_admin']) && $is_logged['is_admin'] === true)
			{
				$cache = $this->cache('admin');
			}
			else
			{
				$cache = $this->cache();
			}
			$myDesign = $cache->get($user);
			if ( $myDesign == null )
			{			
				$myDesign = array();
			}
			
			if (isset($data['attribute']))
				$design['attribute']  	= $data['attribute'];
			
			if (isset($data['print_type']))
				$design['print_type']  	= $data['print_type'];
			
			$design['image']			= $file;
			$design['parent_id']		= $data['parent_id'];
			$design['product_id']		= $data['product_id'];
			$design['product_options']  	= $data['product_color'];
			
			$design['cliparts']  		= $data['cliparts'];
			$design['colors']  		= $data['colors'];
			$design['print']  		= $data['print'];
			$design['images']  		= $data['images'];

			if(isset($data['thumbs']) && count($data['thumbs']) > 0)
			{
				$design['thumbs'] 	= array();
				foreach($data['thumbs'] as $view => $str)
				{
					$temp 			= explode(';base64,', $str);
					$buffer			= base64_decode($temp[1]);
					$view_file 		= 'thumbs' .'-'. $view .'-'. $key . '.png';

					$path_file		= $path .DS. $view_file;
					$this->WriteFile($path_file, $buffer);
					$view_file	= str_replace('\\', '/', $uploaded) .'/'. $view_file;
					$view_file	= str_replace('//', '/', $view_file);
					$design['thumbs'][$view]	= $view_file;
				}
			}
			
			// create images of design
			if(isset($design['images']) && count($design['images']) > 0)
			{
				foreach($design['images'] as $view => $str)
				{
					$src 	= '';
					if ($str != '')
					{
						if($data['isIE'] == 'true')
						{
							$buffer		= $str;
							$view_file 	= 'design' .'-'. $view .'-'. $key . '.svg';
						}
						else
						{
							$temp 		= explode(';base64,', $str);
							$buffer		= base64_decode($temp[1]);
							$view_file 	= 'design' .'-'. $view .'-'. $key . '.png';
						}
						$path_file	= $path .DS. $view_file;
						$this->WriteFile($path_file, $buffer);
						$view_file	= str_replace('\\', '/', $uploaded) .'/'. $view_file;
						$view_file	= str_replace('//', '/', $view_file);
						$src		= $view_file;
					}
					$design['images'][$view] = $src;
				}
			}
			else
			{
				$design['images'] = array(
					'front' => ''
				);
			}
			$design['title']  		= $data['title'];
			$design['description']  	= $data['description'];
			
			// save design to cache
			$myDesign[$key]	= array(
				'id' 			=> $key,
				'key' 			=> $key,
				'product_id' 	=> $data['product_id'],
				'parent_id' 	=> $data['parent_id'],
				'product_options' 	=> $data['product_color'],
				'title' 		=> $data['title'],
				'description' 	=> $data['description'],
				'image' 		=> $design['image'],
				'images' 		=> $design['images'],
			);
			if(isset($design['thumbs']))
			{
				$myDesign[$key]['thumbs']	= $design['thumbs'];
			}
			$cache->set($key, $design);
			$cache->set($user, $myDesign);
			
			$results['error'] = 0;
			
			$content = array(
				'user_id'=> $user,
				'design_id'=> $key,
				'design_key'=> $key,
				'designer_id'=> $user,
				'design_file'=> $file,					
			);
			if(isset($design['thumbs']))
			{
				$content['thumbs']	= $design['thumbs'];
			}				
			$results['content'] = $content;	

		}
		
		echo json_encode($results);
		exit;
	}
	
	public function getTax($id)
	{
		$file = ROOT .DS. 'data' .DS. 'taxes.json';
		
		if (file_exists($file))
		{
			$content = file_get_contents($file);
			if ($content === false)
			{
				return false;
			}
			else
			{
				$data = json_decode($content);
				if (count($data))
				{
					foreach($data as $key => $value)
					{
						if ($value->id == $id)
						{
							return $value;
						}
					}
				}
			}
		}
		return false;
	}
	
	// get price of design
	public function prices($data, $add_tax = true)
	{
		// get data post
		$product_id		= $data['product_id'];
		$colors		= $data['colors'];
		$print		= $data['print'];		
		$quantity		= $data['quantity'];

		if($quantity == 0)
		{
			$total 		= new stdClass();
			$total->item 	= 0;
			$total->printing 	= 0;
			$total->clipart 	= 0;
			$total->old 	= 0;
			$total->sale 	= 0;
			
			return $total;
		}	
		
		// get attribute
		if ( isset( $data['attribute'] ) )
		{
			$attribute		= $data['attribute'];
		}
		else
		{
			$attribute		= false;
		}
				
		if ($quantity < 0 ) $quantity = 0;

		if($quantity == 0) {
			echo '{"old":0,"printing":0,"sale":0,"clipart":0,"attribute":0,"item":0}';
			exit;
		}

		$cache 	= $this->cache('products');
		$product 	= $cache->get('product_'.$product_id);
		if($product == null)
		{
			// load product
			$products 		= $this->getProducts();		
			$product 		= false;

			for($i=0; $i < count($products); $i++)
			{
				if ($product_id == $products[$i]->id)
				{
					$product = $products[$i];
					break;
				}
			}

			if ($product !== false){
				$cache->set('product_'.$product_id, $product, 300);
			}
		}
		
		if ($product === false)
		{
			echo json_encode( array('error' => 'Product could not be found') );
			exit;
		}
		else
		{
			
			// load cart
			include_once (ROOT .DS. 'includes' .DS. 'cart.php');
			$cart 		= new dgCart();	
			$post 		= array(
				'colors' 		=> $colors,
				'print' 		=> $print,
				'attribute' 	=> $attribute,
				'quantity' 		=> $quantity,
				'product_id' 	=> $product_id					
			);
			
			// load setting			
			$setting 		= $this->getSetting();	
			$number 		= setValue($setting, 'price_number', 2);
			$price_thousand 	= setValue($setting, 'price_thousand', ',');
			$price_decimal 	= setValue($setting, 'price_decimal', '.');
			
			include_once(ROOT .DS. 'includes' .DS. 'addons.php');					
			$addons 	= new addons();
			$params = array(
				'data' => $data,
				'product' => $product,				
				'setting' => $setting,			
				'post' => $post,			
			);
			
			$addons->view('hooks' .DS. 'product', $params);	
			
					
			$result 		= $cart->totalPrice($product, $post, $setting);
						
			$params = array(
				'data' => $data,
				'product' => $product,				
				'setting' => $setting,			
				'result' => $result,			
				'post' => $post				
			);
			$addons->view('hooks' .DS. 'fields', $params);

			// get art of store
			if(isset($data['artStore']))
			{
				$artStore = $data['artStore'];
			}
			else
			{
				$artStore = array();
			}
			
			/* get cliparts on website */
			$clipartsPrice = array();
			if (isset($data['cliparts']) && count($data['cliparts']) > 0)
			{
				if(count($artStore))
				{
					$cliparts = array();
					foreach($data['cliparts'] as $view => $arts)
					{
						$cliparts[$view]	= array();
						if(count($arts))
						{
							foreach($arts as $art)
							{
								if(!in_array($art, $artStore))
								{
									$cliparts[$view][] = $art;
								}
							}
						}
					}
					$clipartsPrice = $cart->getPriceArt($cliparts);
				}
				else
				{
					$clipartsPrice = $cart->getPriceArt($data['cliparts']);
				}
			}
			
			$result->cliparts = $clipartsPrice;
			$result->quantity = $quantity;
			
			$total	= new stdClass();
			$total->old = (float)$cart->priceFormart($result->price->base) + (float)$cart->priceFormart($result->price->colors) + (float)$cart->priceFormart($result->price->prints);
						
			$print_discount = 0;																						
			if(isset($result->price->print_discount)) $print_discount = (float)$cart->priceFormart($result->price->print_discount);
			
			$total->printing 	= (float)$cart->priceFormart($result->price->prints) - (float)$print_discount;
			$total->sale 		= (float)$cart->priceFormart($result->price->sale) + (float)$cart->priceFormart($result->price->colors) + (float)$cart->priceFormart($total->printing);
			
			$price_clipart = 0;
			if (count($result->cliparts))
			{
				foreach($result->cliparts as $id=>$amount)
				{
					$amount 		= $cart->priceFormart($amount);
					$total->old 	= $total->old + $amount;
					$total->sale 	= $total->sale + $amount;
					$price_clipart 	= $price_clipart + $amount;
				}
			}
			if(!isset($total->clipart))
				$total->clipart = 0;
			$total->clipart 	= $price_clipart;
			
			if (empty($result->price->attribute))				
			{
				$result->price->attribute = 0;
			}
			$total->old 	= ($total->old * $quantity) + $result->price->attribute;
			$total->sale 	= ($total->sale * $quantity) + $result->price->attribute;

			$total->attribute = $result->price->attribute/$quantity;
			
			// get price arts of store
			if(count($artStore))
			{			
				include_once(ROOT .DS. 'includes' .DS. 'store.php');
				$store	= new store($setting);
				$ids	= array();
				foreach($artStore as $art_id)
				{
					if (in_array($art_id, $ids)) continue;
					
					$ids[]		= $art_id;
					$art_price 		= $store->getPrice($art_id);
					$total->old 	= $total->old + $art_price;
					$total->sale 	= $total->sale + $art_price;
					$total->clipart 	= $total->clipart + $art_price;
				}
			}
			
			// check add tax or not
			if ( isset($data['noTax']) && $data['noTax'] == 0)
			{
				$add_tax = false;
			}
			// add tax
			if ($add_tax == true && $this->platform == 'wordpress')
			{
				if (isset($product->tax) && $product->tax > 0)
				{
					// get tax
					$tax = $this->getTax($product->tax);
					$params = array(
						'data' => $data,
						'tax' => $tax
					);
					$addons->view('hooks' .DS. 'taxes', $params);
					if ($tax != false && isset($tax->type))
					{
						if ($tax->type == 't')
						{
							$total->old = $total->old + $tax->value;
							$total->sale = $total->sale + $tax->value;
						}
						else
						{
							$total->old = $total->old + ($tax->value * $total->old)/100;
							$total->sale = $total->sale + ($tax->value * $total->sale)/100;
						}
					}
				}
			}
			
			$params = array(
				'setting' => $setting,
				'data' => $data,
				'quantity' => $quantity,
				'total' => $total,
				'platform' => $this->platform,
			);
			$addons->view('hooks' .DS. 'extends', $params);
			if (property_exists($total, 'number') && $total->number > 0) {
				$number = $total->number;
			}		
			
			if($quantity == 0)
				$avg = 0;
			else
				$avg = $total->sale/$quantity;
			$per_item = 0;
			if( $quantity >= 1 && $quantity <= 9 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty1to9_productc_qty', true );
			if( $quantity >= 10 && $quantity <= 19 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty10to19_productc_qty', true );
			if( $quantity >= 20 && $quantity <= 49 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty20to49_productc_qty', true );
			if( $quantity >= 50 && $quantity <= 99 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty50to99_productc_qty', true );
			if( $quantity >= 100 && $quantity <= 249 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty100to249_productc_qty', true );
			if( $quantity >= 250 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty250to499_productc_qty', true );
			$vat = 1;
			if( $_SESSION['vat'] === 'in' ) $vat = 1.2;
			$per_item = ($per_item + $total->printing + $total->clipart) * $vat;
			$total->item 	= number_format($per_item, $number, $price_decimal, $price_thousand);
			$total->printing 	= number_format($total->printing * $vat, $number, $price_decimal, $price_thousand);
			$total->clipart 	= number_format($total->clipart * $vat, $number, $price_decimal, $price_thousand);
			$total->old 	= number_format( ( ( get_post_meta( $_GET['parent_product_id'], '_qty1to9_productc_qty', true ) + $total->printing + $total->clipart ) * $vat ) * $quantity, $number, $price_decimal, $price_thousand);
			$total->sale 	= number_format($per_item * $quantity, $number, $price_decimal, $price_thousand);
			return $total;
		}	
	}
	
	public function getPrintingType($printing_code)
	{
		$data 			= array();
		$file 			= ROOT .DS. 'data' .DS. 'printings.json';
		if ( file_exists($file) )
		{
			$content 	= file_get_contents($file);			
			if ($content != false && $content != '')
			{
				$printings = json_decode($content);				
				if ( count($printings) )
				{
					foreach ($printings as $printing)
					{
						// check printing type of product
						if ( $printing->printing_code == $printing_code )
						{
							$code_type	= ROOT .DS. 'addons' .DS. 'printings' .DS. $printing->price_type.'.json';
							
							if ( file_exists ($code_type) )
							{
								$data = $printing;
							}
							break;
						}
					}
				}
			}
		}
		return $data;
	}
	
	public function getSVG($post)
	{
		$art_id 		= 0;
		if(isset($post['clipart_id']))
			$art_id	= $post['clipart_id'];

		$type			= $post['file_type'];
		$url			= $post['url'];
		$file_name		= $post['file_name'];

		$colors 		= '[]';
		if(isset($post['colors']))
			$colors		= $post['colors'];
		
		if($art_id > 0)			
			$file 		= $url . 'print/' . $file_name;
		else
			$file 		= $url . '/' . $file_name;
		
		include_once (ROOT .DS. 'includes' .DS. 'libraries' .DS. 'svg.php');
					
		$data = array();
		$size = array();
		
		$size['height'] = 100;
		$size['width'] = 100;
		
		$xml = new svg($file, true);
			
		// get width, heigh of svg file
		$width = $xml->getWidth();
		$height = $xml->getHeight();
		
		// calculated width, height
		if($width > $height){
			$newHeight = $size['height'];
			$newWidth = ($size['height'] / $height) * $width;
		}else{
			$newWidth = $size['width'];
			$newHeight = ($size['width'] / $width) * $height;
		}
		
		// set width, height
		$xml->setWidth($newWidth.'px');
		$xml->setHeight($newHeight.'px');

		$data['content'] 		= $xml->asXML();
		$data['info']['type'] 	= 'svg';				
		$data['info']['colors'] = is_array($colors) ? $colors : json_decode($colors);

		$data['size']['width'] 	= $newWidth . 'px';
		$data['size']['height'] = $newHeight . 'px';
		
		return $data;
	}

	public function getProductchild($product_id, $child_id = 0)
	{
		$products 		= $this->getProducts();
		$product 		= false;
		
		for($i=0; $i < count($products); $i++)
		{
			if ($product_id == $products[$i]->id)
			{
				$product = $products[$i];
				break;
			}
		}
		if($child_id > 0 && $product !== false)
		{
			$file = ROOT .DS. 'data' .DS. 'products_child.json';
			if( file_exists($file) )
			{
				$content 	= file_get_contents($file);
				if( $content !== false && $content != '' )
				{
					$products = json_decode($content);
					if( isset($products->$product_id) && isset($products->$product_id->$child_id) )
					{
						$product_child = $products->$product_id->$child_id;
						foreach ($product_child as $key => $value)
						{
							$product->$key = $value;
						}
					}
				}
			}
		}

		return $product;
	}
	
	// add to cart
	public function addCart($data)
	{
		// get data post
		$product_id		= $data['product_id'];
		$colors			= $data['colors'];
		$print			= $data['print'];		
		$quantity		= $data['quantity'];		
				
		// get attribute
		if ( isset( $data['attribute'] ) )
		{
			$attribute		= $data['attribute'];
		}
		else
		{
			$attribute		= false;
		}
				
		if ($quantity < 1 ) $quantity = 1;
		
		$time = strtotime("now");			
		
		if (isset($data['cliparts']))
		{
			$cliparts = $data['cliparts'];
		}
		else
		{
			$cliparts = false;
		}
		
		$content = array();
		$content['error'] = 1;
		
		// load product
		$child_id = 0;
		if(isset($data['child_id']))
		{
			$child_id 	= $data['child_id'];
		}
		$product = $this->getProductchild($product_id, $child_id);
		
		if ($product === false)
		{
			$content['msg'] = 'Product could not be found';
		}
		else
		{	
			$content['error'] = 0;
			// load cart
			include_once (ROOT .DS. 'includes' .DS. 'cart.php');
			$cart 		= new dgCart();	
			$post 		= array(
				'colors' 		=> $colors,
				'print' 		=> $print,
				'attribute' 	=> $attribute,
				'quantity' 		=> $quantity,
				'product_id' 	=> $product_id					
			);
			
			// load setting			
			$setting 			= $this->getSetting();
			$price_thousand 		= setValue($setting, 'price_thousand', ',');
 			$price_decimal 		= setValue($setting, 'price_decimal', '.');
			
			include_once(ROOT .DS. 'includes' .DS. 'addons.php');					
			$addons 	= new addons();
			$params = array(
				'data' 		=> $data,
				'product' 	=> $product,				
				'setting' 	=> $setting,
				'post'	 	=> $post
			);
			
			$addons->view('hooks' .DS. 'product', $params);	
			
			$result 		= $cart->totalPrice($product, $post, $setting);
						
			$params = array(
				'data' => $data,
				'product' => $product,				
				'setting' => $setting,
				'result' => $result,
				'post' => $post
			);
			$addons->view('hooks' .DS. 'fields', $params);
						
			$result->product	= new stdClass();
			$result->product->name 	= $product->title;
			$result->product->sku 	= $product->sku;
			
			// get art of store
			if(isset($data['artStore']))
			{
				$artStore = $data['artStore'];
			}
			else
			{
				$artStore = array();
			}
			
			// get cliparts
			$clipartsPrice = array();
			if (isset($data['cliparts']) && count($data['cliparts']) > 0)
			{
				if(count($artStore))
				{
					$cliparts = array();
					foreach($data['cliparts'] as $view => $arts)
					{
						$cliparts[$view]	= array();
						if(count($arts))
						{
							foreach($arts as $art)
							{
								if(!in_array($art, $artStore))
								{
									$cliparts[$view][] = $art;
								}
							}
						}
					}
					$clipartsPrice = $cart->getPriceArt($cliparts);
				}
				else
				{
					$clipartsPrice = $cart->getPriceArt($data['cliparts']);
				}
			}					
			$result->cliparts = $clipartsPrice;
			
			if($result->price->colors == '')
				$result->price->colors = 0;
			if($result->price->prints == '')
				$result->price->prints = 0;
				
			$total	= new stdClass();			
			$total->old = $cart->priceFormart($result->price->base) + $cart->priceFormart($result->price->colors) + $cart->priceFormart($result->price->prints);
			
			$print_discount = 0;
			if(isset($result->price->print_discount)) $print_discount = $cart->priceFormart($result->price->print_discount);
			$total->sale = $cart->priceFormart($result->price->sale) + $cart->priceFormart($result->price->colors) + $cart->priceFormart($result->price->prints) - $print_discount;
			
			if (count($result->cliparts))
			{
				foreach($result->cliparts as $id=>$amount)
				{
					$amount 		= $cart->priceFormart($amount);
					$total->old 	= $total->old + $amount;
					$total->sale 	= $total->sale + $amount;				
				}
			}		
			
			if (empty($result->price->attribute))				
			{
				$result->price->attribute = 0;
			}
			$total->old 	= ($total->old * $quantity) + $result->price->attribute;
			$total->sale 	= ($total->sale * $quantity) + $result->price->attribute;
			
			// get price arts of store
			if(count($artStore))
			{			
				include_once(ROOT .DS. 'includes' .DS. 'store.php');
				$store	= new store($setting);
				$ids	= array();
				foreach($artStore as $art_id)
				{
					if (in_array($art_id, $ids)) continue;
					
					$ids[]			= $art_id;
					$art_price 		= $store->getPrice($art_id);
					$total->old 	= $total->old + $art_price;
					$total->sale 	= $total->sale + $art_price;					
				}
			}
			
			$result->total 	= $total;
			
			// get symbol
			if (!isset($setting->currency_symbol))
				$setting->currency_symbol = '$';
			$result->symbol = $setting->currency_symbol;
			
			// save file image design
			$path = $this->folder();
			$design = array();
			$design['images'] = array();
			if(!isset($data['design']['isIE']))
				$data['design']['isIE'] = false;
			if (isset($data['design']['images']['front']))
				$design['images']['front'] 	= $this->createFile($data['design']['images']['front'], $path, 'cart-front-'.$time, $data['design']['isIE']);
					
			if (isset($data['design']['images']['back']))	
				$design['images']['back'] 	= $this->createFile($data['design']['images']['back'], $path, 'cart-back-'.$time, $data['design']['isIE']);
				
			if (isset($data['design']['images']['left']))
				$design['images']['left'] 	= $this->createFile($data['design']['images']['left'], $path, 'cart-left-'.$time, $data['design']['isIE']);
				
			if (isset($data['design']['images']['right']))
				$design['images']['right']	= $this->createFile($data['design']['images']['right'], $path, 'cart-right-'.$time, $data['design']['isIE']);
				
			if (empty($result->options)) $result->options = array();
			
			if (isset($data['teams'])) $teams = $data['teams'];
			else $teams = '';
						
			$params = array(
				'data' => $data,
				'result' => $result,
				'design' => $design,
				'setting' => $setting,
			);
			$addons->view('hooks' .DS. 'cart', $params);
			
			// add cart
			$per_item = 0;
			if( $quantity >= 1 && $quantity <= 9 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty1to9_productc_qty', true );
			if( $quantity >= 10 && $quantity <= 19 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty10to19_productc_qty', true );
			if( $quantity >= 20 && $quantity <= 49 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty20to49_productc_qty', true );
			if( $quantity >= 50 && $quantity <= 99 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty50to99_productc_qty', true );
			if( $quantity >= 100 && $quantity <= 249 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty100to249_productc_qty', true );
			if( $quantity >= 250 ) $per_item = get_post_meta( $_GET['parent_product_id'], '_qty250to499_productc_qty', true );
			$per_item = $per_item + $result->price->prints + $result->price->colors;
			$item 	= array(
				'id'      		=> $result->product->sku,
				'product_id'    => $data['product_id'],
				'qty'     		=> $data['quantity'],
				'teams'     	=> $teams,
				'price'   		=> $per_item * $quantity,
				'prices'   		=> json_encode($result->price),
				'cliparts'   	=> json_encode($result->cliparts),
				'symbol'   		=> $result->symbol,
				'customPrice'   => $result->price->attribute,
				'name'    		=> $result->product->name,
				'time'    		=> $time,
				'options' 		=> $result->options,
			);
			
			$rowid			= md5($result->product->sku . $time);
			$cache			= $this->cache('cart');			
			

			$designs		= array(
				'color' => $data['colors'][key($data['colors'])],
				'print' => $print,
				'cliparts' => $cliparts,
				'images' => $design['images'],
				'vector' => $data['design']['vectors'],
				'fonts' => $data['fonts'],
				'attributes' => $attribute,
				'item' => $item,
				'options' => array('productColors' => isset($result->product_build) ? $result->product_build : array())
			);
			$params = array(
				'rowid' => $rowid,
				'item' => $item,
				'designs' => $designs,
			);
			$addons->view('hooks' .DS. 'cart_design', $params);
			if( isset($data['options']) )
			{
				$designs['options']	= $data['options'];
			}
			$cache->set($rowid, $designs);
		
			$wc_product_id = $_GET['parent_product_id'];
			$wc_product = wc_get_product( $wc_product_id );
			foreach( $wc_product->get_attributes() as $key => $attr ){
				if( $key === 'pa_color' ){
					$attributes = [];
					foreach( $attr->get_terms() as $term ){
						$attributes[] = $term->name;
					}
					break;
				}
			}
			$price_product = $result->total->sale / $quantity;
			$content['product'] = array(
				'rowid'=> $rowid,
				'price'=> $per_item,
				'quantity'=> $quantity,
				'color_hex' => $data['colors'][key($data['colors'])],
				'color_title' => $attributes[key($data['colors'])], //$product->design->color_title[key($data['colors'])],
				'images'=> json_encode($design['images']),
				'teams'=> $teams,
				'options' => $result->options
			);
			if(isset($data['variation_id']))
			{
				$content['product']['variation_id'] = $data['variation_id'];
			}
			$data['test_key'] = 'test_value';
			if(isset($data['variation_attributes']) && $data['variation_attributes'] != '')
			{
				$variation_attributes 	= explode(';', $data['variation_attributes']);
				for($i=0; $i<count($variation_attributes); $i++)
				{
					$attr = explode('|', $variation_attributes[$i]);
					$content['product']['variation'][$attr[0]] = $attr[1];
				}
			}
			if($content['product']['color_title'] == '')
			{
				$content['product']['color_title'] = $content['product']['color_hex'];
			}
		}
		
		return $content;
	}
	
	public function createFile($data, $path, $file, $isIE)
	{
		if($isIE == 'true')
		{
			$buffer     = $data;
			$path_file 	= ROOT .DS. $path .DS. $file .'.svg';
		}
		else
		{
			$temp 		= explode(';base64,', $data);
			$buffer		= base64_decode($temp[1]);
			$path_file 	= ROOT .DS. $path .DS. $file .'.png';
		}
		
		$path_file	= str_replace('/', DS, $path_file);
		
		if ( $this->WriteFile($path_file, $buffer) === false)
		{
			return '';
		}
		else
		{
			if($isIE == 'true')
			{
				return str_replace('\\', '/', $path .DS. $file .'.svg');
			}
			else
			{
				return str_replace('\\', '/', $path .DS. $file .'.png');
			}
		}
	}
	
	public function perpage($width, $height, $proportion)
	{
		$width = $width * $proportion['width'];
		$height = $height * $proportion['height'];
		
		$pagesW = array('0' => 10.5, '1' => 14.8, '2' => 21.0, '3' => 29.7, '4' => 42, '5' => 59.4, '6' => 84.1);
		$pagesH = array('0' => 14.8, '1' => 21, '2' => 29.7, '3' => 42, '4' => 59.4, '5' => 84.1, '6' => 118.9);

		if (($width <= $pagesW[0] && $height <= $pagesH[0]) || ($width <= $pagesH[0] && $height <= $pagesW[0]))
				return 6;
			
		$size = 6;
		for($i=1; $i<=6; $i++)
		{
			if (($width <= $pagesW[$i] && $height<=$pagesH[$i]) || ($width <= $pagesH[$i] && $height <= $pagesW[$i]))
			{
				return 6 - $i;
			}
		}
			
		return 0;
	}
	
	function rgb_to_array($hex)
	{
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3)
	   {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } 
	   else 
	   {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   
	   return $rgb;
	}
	
	/*
	* change color of photo to one color
	*/
	public function photoColor($image, $color)
	{
		$newColor 		= $this->rgb_to_array($color);
		
		$img 			= imagecreatefromstring($image);

		$w 			= imagesx($img);
		$h 			= imagesy($img);

		$rgb = array(255-$newColor[0], 255-$newColor[1], 255-$newColor[2]);
	
		imagefilter($img, IMG_FILTER_NEGATE); 
		imagefilter($img, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]); 
		imagefilter($img, IMG_FILTER_NEGATE); 
		
		imageAlphaBlending($img, true);
		imageSaveAlpha($img, true);
		ob_start();
		imagepng($img);
		$data = ob_get_contents();
		ob_end_clean();
		imagedestroy($img);

		return $data;
	}
	
	public function readFile($file)
	{
		if ( ! file_exists($file))
		{
			return FALSE;
		}

		if (function_exists('file_get_contents'))
		{
			return @file_get_contents($file);
		}

		if ( ! $fp = @fopen($file, FOPEN_READ))
		{
			return FALSE;
		}

		flock($fp, LOCK_SH);

		$data = '';
		if (filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}

	function minify_css($addons)
	{
		$files_css 		= array();
		$files_css[]	= 'assets/css/tshirtecommerce.min.css';
		if(isset($addons->is_mobile) && $addons->is_mobile == true)
		{
			$files_css[] 	= 'assets/css/style-mobile.min.css';
		}
		else
		{
			$files_css[] 	= 'assets/css/style.min.css';
		}
		$files_css = $addons->css($files_css);

		return $files_css;
	}

	function minify_js($addons)
	{
		$files_js 		= array();
		$files_js[]	= 'assets/js/tshirtecommerce.min.js';
		$files_js[]	= 'assets/js/design.js';
		if(isset($addons->is_mobile) && $addons->is_mobile == true)
		{
			$files_js[] 	= 'assets/js/mobile.js';
		}
		$is_admin_editor 	= false;
		if(isset($addons->is_admin))
		{
			$is_admin_editor = true;
		}
		$files_js 			= $addons->js($is_admin_editor, $files_js);

		return $files_js;
	}
}

// get language
function lang($key, $string = false, $js = false)
{
	$lang = $GLOBALS['lang'];	
	
	if ( isset($lang[$key]) )
	{
		$txt = $lang[$key];
	}
	else
	{
		$txt = '';
	}
	if($js === false)
	{
		$txt = str_replace("\\'", "&#39;", $txt);
	}
	
	
	if($string === false)
		echo $txt;
	else
		return $txt;
			
}

function frontend_header($addons)
{
	$dg 	= new dg();
	$url 			= $dg->url().'tshirtecommerce/';
	if(DEVELOPER == 0)
	{
		$exten 			= '';
		if(isset($addons->is_mobile) && $addons->is_mobile == true)
		{
			$exten 		= 'mobile';
		}
		elseif(isset($addons->is_admin) && $addons->is_admin == true)
		{
			$exten 		= 'admin';
		}

		/* Min css file */
		$file_css 		= ROOT .DS. 'assets' .DS. 'css' .DS. 'cache_all_files'.$exten.'.css';
		$url_css 		= 'assets/css/cache_all_files'.$exten.'.css';
		if(file_exists($file_css))
		{
			echo '<link type="text/css" href="'.$url_css.'" rel="stylesheet" media="all"/>';
		}
		else
		{
			$files 	= $dg->minify_css($addons);
			echo '<link type="text/css" href="/tshirtecommerce/assets/css/tshirtecommerce.min.css" class="minify-file" rel="stylesheet"/>';
			if(isset($addons->is_mobile) && $addons->is_mobile == true){
				echo '<link type="text/css" href="/tshirtecommerce/assets/css/style-mobile.min.css" class="minify-file" rel="stylesheet"/>';
			}
			echo $files;

			// for($i=0; $i<count($files); $i++)
			// {
			// 	echo '<link type="text/css" href="'.$url.$files[$i].'" class="minify-file" rel="stylesheet"/>';
			// }
		}

		/* Min js file */
		$file_js 		= ROOT .DS. 'assets' .DS. 'js' .DS. 'cache_all_files'.$exten.'.js';
		$url_js 		= 'assets/js/cache_all_files'.$exten.'.js';
		if(file_exists($file_js))
		{
			echo '<script type="text/javascript" src="'.$url_js.'"></script>';
		}
		else
		{
			$files 	= $dg->minify_js($addons);
			echo '<script type="text/javascript" class="minify-file" src="/tshirtecommerce/assets/js/tshirtecommerce.min.js"></script>';
			echo '<script type="text/javascript" class="minify-file" src="/tshirtecommerce/assets/js/design.js"></script>';
			if(isset($addons->is_mobile) && $addons->is_mobile == true)
			{
				echo '<script type="text/javascript" class="minify-file" src="/tshirtecommerce/assets/js/mobile.js"></script>';
			}
			echo $files;
			
			// for($i=0; $i<count($files); $i++)
			// {
			// 	echo '<script type="text/javascript" class="minify-file" src="'.$url.$files[$i].'"></script>';
			// }
		}
	}
	else
	{
		$files 	= $dg->minify_css($addons);
		for($i=0; $i<count($files); $i++)
		{
			echo '<link type="text/css" href="'.$url.$files[$i].'" rel="stylesheet"/>';
		}

		$files 	= $dg->minify_js($addons);
		for($i=0; $i<count($files); $i++)
		{
			echo '<script type="text/javascript" src="'.$url.$files[$i].'"></script>';
		}
	}
}

// get images
function base_url($url)
{
	return $url;
}

function imageURL($src, $site_url = '')
{
	if ($src == '') return '';
	
	if (strpos($src, 'http') !== false)
		return $src;
	
	$url 		= str_replace('//tshirtecommerce', '/tshirtecommerce', $site_url);
	$temp 		= explode('tshirtecommerce/', $url);
	
	return $temp[0].'tshirtecommerce/'.$src;
}

function setValue($data, $key, $default)
{
	$value = $default;
	if( is_array($data) && isset($data[$key]) )
	{
		$value = $data[$key];
	}
	elseif( is_object($data) && isset($data->$key) )
	{
		$value = $data->$key;
	}
	return $value;
}

function cssShow($data, $key, $default = 1)
{
	if (isset($data->$key))
		$value = $data->$key;
	else
		$value = $default;
	
	if ($value == 1)
		return '';
	else
		return 'style="display:none;"';
}