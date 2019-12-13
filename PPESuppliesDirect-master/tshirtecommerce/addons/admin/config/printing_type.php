<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-10-11
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
 /*$print_types = array(
	'screen'=> $addons->__('addon_print_type_screen'),
	'DTG'=> $addons->__('addon_print_type_dtg'),
	'sublimation'=> $addons->__('addon_print_type_sublimation'),
	'embroidery'=> $addons->__('addon_print_type_embroidery'),
);*/

$print_types = $addons->printing($print_types);

?>
<script src="<?php echo site_url('assets/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script>
tinymce.init({
	selector: ".text-edittor",
	menubar: false,
	toolbar_items_size: 'small',
	statusbar: false,
	height : 150,
	convert_urls: false,
	setup: function(editor) {
		editor.addButton('mybutton', {
			text: 'My button',
			icon: false,
			onclick: function() {
				editor.insertContent('Main button');
			}
		});
	},
	plugins: [
		"advlist autolink lists link image charmap print preview anchor",
		"searchreplace visualblocks code fullscreen",
		"insertdatetime media table contextmenu paste"
	],
	toolbar: "code | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>
<h4>
	<?php echo $addons->__('addon_print_type_title'); ?>
	<p class="help-block"><?php echo $addons->__('addon_print_type_help'); ?></p>
</h4>
<div class="printing-types panel-group" id="accordion">
	<?php 
		if(count($print_types))
		{
			$i = 1;
			foreach($print_types as $key=>$val)
			{
				if($i == 1)
					$collapse = ' in';
				else
					$collapse = '';
				
				echo '<div class="panel" id="div'.$i.'">';
				echo '<a href="#printing-type-'.$i.'" data-toggle="collapse" data-parent="#accordion"><p>'.$val.'</p></a>';
				echo '<div id="printing-type-'.$i.'" class="col-sm-12 panel-collapse collapse'.$collapse.'">';
				echo '<textarea rows="3" class="col-sm-12 text-edittor" name="setting[printing_type_'.$key.']">';
				
				if(!isset($data['settings']['printing_type_'.$key]) || $data['settings']['printing_type_'.$key]=='')
					echo htmlspecialchars($addons->__('default_printing_type_'.$key));
				else
					echo htmlspecialchars($data['settings']['printing_type_'.$key]);
				
				echo '</textarea></div></div>';
				
				$i++;
			}
			unset($i);
		}
	?>
</div>