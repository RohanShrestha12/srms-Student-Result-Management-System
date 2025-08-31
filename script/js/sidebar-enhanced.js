// Enhanced Sidebar JavaScript - Works with existing main.js
$(document).ready(function() {
    
    // Wait for main.js to initialize, then enhance the functionality
    setTimeout(function() {
        
        // Simple hover effects for menu items
        $('.app-menu__item').on('mouseenter', function() {
            $(this).css('transform', 'translateX(3px)');
        }).on('mouseleave', function() {
            $(this).css('transform', 'translateX(0)');
        });
        
        // Ensure all dropdowns are hidden by default
        $('.treeview-menu').hide();
        $('.treeview-indicator').css('transform', 'rotate(0deg)');
        
        // Enhance the existing treeview functionality instead of overriding
        $("[data-toggle='treeview']").on('click', function(event) {
            // Let the original handler work first
            setTimeout(function() {
                var $parent = $(event.target).closest('.treeview');
                var $menu = $parent.find('.treeview-menu');
                var $indicator = $parent.find('.treeview-indicator');
                
                // Smooth indicator rotation and menu visibility
                if ($parent.hasClass('is-expanded')) {
                    $indicator.css('transform', 'rotate(90deg)');
                    $menu.show();
                } else {
                    $indicator.css('transform', 'rotate(0deg)');
                    $menu.hide();
                }
            }, 50);
        });
        
        // Simple click effect for non-treeview menu items
        $('.app-menu__item').not('[data-toggle="treeview"]').on('click', function() {
            $('.app-menu__item').removeClass('active');
            $(this).addClass('active');
        });
        
        // Active state management based on current page
        var currentPath = window.location.pathname;
        $('.app-menu__item').each(function() {
            var href = $(this).attr('href');
            if (href && currentPath.includes(href) && href !== 'javascript:void(0);') {
                $(this).addClass('active');
                
                // Expand parent treeview if this is a child item
                var $parentTreeview = $(this).closest('.treeview');
                if ($parentTreeview.length) {
                    var $menu = $parentTreeview.find('.treeview-menu');
                    var $indicator = $parentTreeview.find('.treeview-indicator');
                    
                    $parentTreeview.addClass('is-expanded');
                    $menu.show();
                    $indicator.css('transform', 'rotate(90deg)');
                }
            }
        });
        
        // Simple hover effect for user section
        $('.app-sidebar__user').on('mouseenter', function() {
            $(this).css('transform', 'translateY(-1px)');
        }).on('mouseleave', function() {
            $(this).css('transform', 'translateY(0)');
        });
        
        // Add smooth transitions to existing elements
        $('.treeview-menu').css({
            'transition': 'all 0.3s ease',
            'overflow': 'hidden'
        });
        
        $('.treeview-indicator').css({
            'transition': 'transform 0.3s ease'
        });
        
    }, 100); // Small delay to ensure main.js has initialized
    
});

// Add minimal CSS for enhanced styling without conflicts
const style = document.createElement('style');
style.textContent = `
    .app-menu__item {
        position: relative;
        overflow: hidden;
    }
    
    .treeview-indicator {
        transition: transform 0.3s ease !important;
    }
    
    .treeview-menu {
        transition: all 0.3s ease !important;
        display: none !important;
    }
    
    .treeview.is-expanded .treeview-menu {
        display: block !important;
    }
`;
document.head.appendChild(style); 