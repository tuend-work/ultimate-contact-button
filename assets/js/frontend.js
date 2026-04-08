jQuery(document).ready(function($) {
    // Mobile FAB Toggle
    $('.ucb-mobile-main-btn').on('click', function() {
        $(this).closest('.ucb-mobile-container').toggleClass('active');
        
        // Rotate icon if active
        if ($(this).closest('.ucb-mobile-container').hasClass('active')) {
            $(this).find('span').css('transform', 'rotate(45deg)');
        } else {
            $(this).find('span').css('transform', 'rotate(0deg)');
        }
    });

    // Close on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.ucb-mobile-container').length) {
            $('.ucb-mobile-container').removeClass('active');
            $('.ucb_mobile_main_btn span').css('transform', 'rotate(0deg)');
        }
    });

    // Desktop main button click toggle for mobile touch devices
    $('.ucb-desktop-main-btn').on('click', function(e) {
        if ($(window).width() > 768) {
            // On desktop, hover is usually enough
        }
    });
});
