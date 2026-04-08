jQuery(document).ready(function($) {
    // 1. FAB Main Contact Button Toggle
    $(document).on('click', '.ucb-main-trigger', function() {
        var $container = $(this).closest('.ucb-main-container');
        $container.toggleClass('active');
        
        // Rotate trigger icon if active
        if ($container.hasClass('active')) {
            $(this).find('span').css('transform', 'rotate(45deg)');
        } else {
            $(this).find('span').css('transform', 'rotate(0deg)');
        }
    });

    // Close FAB if clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.ucb-main-container').length) {
            $('.ucb-main-container').removeClass('active');
            $('.ucb-main-trigger span').css('transform', 'rotate(0deg)');
        }
    });

    // 2. Icon Rotation for Main Contact Button (FAB)
    function cycleFabIcons() {
        $('.ucb-main-container').each(function() {
            var $container = $(this);
            var $trigger = $container.find('.ucb-main-trigger');
            var $mainIcon = $trigger.find('span');
            var $subIcons = $container.find('.ucb-sub-btn .ucb-icon');
            
            // Logic: Don't cycle icon while menu is open
            if ($container.hasClass('active')) return;
            if ($subIcons.length === 0) return;
            
            var currentIndex = $container.data('icon-index') !== undefined ? $container.data('icon-index') : -1;
            var nextIndex = (currentIndex + 1) % $subIcons.length;
            
            var nextIconHtml = $subIcons.eq(nextIndex).html();
            
            // Smooth transition
            $mainIcon.css('opacity', '0');
            setTimeout(function() {
                $mainIcon.html(nextIconHtml).css('opacity', '1');
            }, 500);
            
            $container.data('icon-index', nextIndex);
        });
    }

    // Set rotation interval (2.5s for dynamic feel)
    setInterval(cycleFabIcons, 2500);
});
