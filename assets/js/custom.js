(function($) {

    $(document).ready(function(){
        $('#cat-list').flickity({
            // options
            cellAlign: 'left',
            prevNextButtons: false,
            pageDots: false,
            freeScroll: true,
            contain: true,
            adaptiveHeight: true
        });

        var elm = document.querySelector('#cat-list');
        var ms = new MenuSpy(elm);
    });

    $(window).scroll(function(){
        if ($(window).scrollTop() >= 120) {
            $('header.navigation').addClass('fixed');
        }
        else {
            $('header.navigation').removeClass('fixed');
        }
    });

})(jQuery);


