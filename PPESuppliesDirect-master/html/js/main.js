jQuery(document).ready(function ($, e) {
    if($('.home-img-slider')){
        $('.home-img-slider').slick({
            arrows: false,
            dots: false,
            variableWidth: false
        });
    }


    $('.tabs-buttons button').on('click', function (e) {
        $(this).parent('.tabs-buttons').find('button').removeClass('active-tab-btn');
        $(this).addClass('active-tab-btn');

        $(this).parents('.tabs-section').find('.tab').removeClass('active-tab');
        var tabId = '#'+$(this).data('tab');
        $(tabId).addClass('active-tab').find('.tab-slider').slick('slickGoTo','0');
    });

});
