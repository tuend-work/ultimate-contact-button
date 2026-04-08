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
            $('.ucb-mobile-main-btn span').css('transform', 'rotate(0deg)');
        }
    });

    // Icon Rotation for Main Button (Desktop & Mobile)
    function cycleIcons() {
        $('.ucb-desktop-container, .ucb-mobile-container').each(function() {
            var $container = $(this);
            var $mainBtn = $container.find('.ucb-mobile-main-btn, .ucb-desktop-main-btn');
            var $mainIcon = $mainBtn.find('span');
            var $subIcons = $container.find('.ucb-mobile-sub-btn .ucb-icon, .ucb-sub-btn .ucb-icon');
            
            // Don't cycle if the menu is open (rotated)
            if ($container.hasClass('active')) return;
            
            if ($subIcons.length === 0) return;
            
            var currentIndex = $container.data('icon-index') !== undefined ? $container.data('icon-index') : -1;
            var nextIndex = (currentIndex + 1) % $subIcons.length;
            
            var nextIconHtml = $subIcons.eq(nextIndex).html();
            
            $mainIcon.css('opacity', '0');
            
            setTimeout(function() {
                $mainIcon.html(nextIconHtml).css('opacity', '1');
            }, 500);
            
            $container.data('icon-index', nextIndex);
        });
    }

    // Start cycling every 2 seconds
    setInterval(cycleIcons, 2000);
});
