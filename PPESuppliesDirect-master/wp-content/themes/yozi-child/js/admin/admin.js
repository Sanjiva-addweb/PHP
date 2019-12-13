!function($) {
	$( "body" ).on( "click", ".apus-checkbox", function() {
		
			jQuery('.'+this.id).toggle();
		
    });
    $('.apus-wpcolorpicker').each(function(){
    	$(this).wpColorPicker();
    });
}(window.jQuery);
