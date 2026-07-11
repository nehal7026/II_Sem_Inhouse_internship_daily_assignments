// Wait until the page is fully loaded
$(document).ready(function () {

    // Bonus: Fade in the entire page
    $(".container").hide().fadeIn(1000);

    // Show/Hide Details Button
    $(".btn-details").click(function () {

        // Toggle the details section
        $(this).closest(".card").find(".details").slideToggle(500);

        // Change button text and color
        if ($(this).text() == "Show Details") {

            $(this).text("Hide Details");

            $(this).css({
                "background": "#ef4444"   // Red
            });

        } else {

            $(this).text("Show Details");

            $(this).css({
                "background": "#06b6d4"   // Blue
            });

        }

    });

});