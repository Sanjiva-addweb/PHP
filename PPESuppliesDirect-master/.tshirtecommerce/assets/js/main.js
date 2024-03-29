jQuery(function($) {
    jQuery('.product-design .main-product-img').css('background-color', '#'+ jQuery('#product-list-colors span').eq(0).attr('data-color'));
    jQuery(".arrow-mobile").click(function() {
        var e = jQuery(this).attr("data");
        var t = jQuery("#dg-" + e).css("display");
        if (e == "right") {
            if (t == "none") {                
                jQuery("#dg-right").show();
                jQuery(this).addClass('active').css({
                    left: "-28px",
                    right: "auto"
                });
				jQuery(this).parent().css({
					'bottom': 'auto',
					'top': '60px'
				});
                jQuery(".accordion").accordion("refresh")
            } else {              
                jQuery("#dg-right").hide();
                jQuery(this).removeClass('active').css({
                    left: "auto",
                    right: "4px"
                });
				jQuery(this).parent().css({
					'bottom': '60px',
					'top': 'auto'
				});
            }
        }
        if (e == "left") {
            if (t == "none") {
                jQuery(this).children("i").attr("class", "glyphicons chevron-left");
                jQuery("#dg-left").show();
                jQuery(this).css({
                    left: "auto",
                    right: "-32px"
                });
                jQuery(".accordion").accordion("refresh")
            } else {
                jQuery(this).children("i").attr("class", "glyphicons chevron-right");
                jQuery("#dg-left").hide();
                jQuery(this).css({
                    left: "0",
                    right: "auto"
                })
            }
        }
    });
    $('.form-control.input-sm.size-number').eq(0).val('');
    $('.form-control.input-sm.size-number').eq(0).attr('aria-valuenow', 0);
    $('.form-control.input-sm.size-number').eq(0).keyup();
    // $('.form-control.input-sm.size-number').eq(0).change();
});