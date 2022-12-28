//  jquery add class on scroll

jQuery(document).ready(function ($) {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 1) {
            $('header').addClass("sticky");
        }
        else {
            $('header').removeClass("sticky");
        }
    });
    //
    // add icon to deeeper parent


    // activate dropdown menu on hover for bootstrap


    $('.deeper.parent').hover(function () {
        $(this).children('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
    }, function () {
        $(this).children('.dropdown-menu').stop(true, true).delay(200).fadeOut(200);
    });
});
