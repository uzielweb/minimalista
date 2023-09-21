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
    document.addEventListener('DOMContentLoaded', function () {
        const backToTopButton = document.getElementById('back-top');
    
        if (backToTopButton) {
            backToTopButton.addEventListener('click', function (event) {
                event.preventDefault();
                scrollToTop();
            });
        }
    
        function scrollToTop() {
            // Scroll to the top smoothly
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    });
});
