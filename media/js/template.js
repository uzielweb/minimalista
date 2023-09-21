// jQuery function to add a class to the 'header' element when the page is scrolled
jQuery(document).ready(function ($) {
    $(window).scroll(function () {
        // Check if the user has scrolled more than 1 pixel from the top
        if ($(this).scrollTop() > 1) {
            $('header').addClass("sticky"); // Add the "sticky" class to the 'header' element
        }
        else {
            $('header').removeClass("sticky"); // Remove the "sticky" class from the 'header' element
        }
    });

    // Code for adding an icon to a deeper parent (comment missing)

    // Code to activate dropdown menu on hover for elements with class 'deeper.parent'
    $('.deeper.parent').hover(function () {
        // Show the dropdown menu with a delay and animation
        $(this).children('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
    }, function () {
        // Hide the dropdown menu with a delay and animation
        $(this).children('.dropdown-menu').stop(true, true).delay(200).fadeOut(200);
    });

    // jQuery code that back to top 
    // Hide the button initially
    $("#back-top").hide();

    // Show/hide the button on scroll
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $("#back-top").fadeIn();
        } else {
            $("#back-top").fadeOut();
        }
    });

    // Scroll to top when the button is clicked
    $("#back-top").click(function () {
        $("html, body").animate(
            {
                scrollTop: 0
            },
            500 // Scroll duration in milliseconds
        );
        return false;
    });
});
