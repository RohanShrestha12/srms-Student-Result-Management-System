/**
 * Student Enhanced Header JavaScript
 * Provides enhanced functionality for the student dashboard header
 */

(function($) {
    'use strict';

    // Student Header Class
    class StudentHeader {
        constructor() {
            this.init();
        }

        init() {
            this.setupSidebarToggle();
            this.setupSearchFunctionality();
            this.setupDropdownMenus();
            this.setupResponsiveBehavior();
            this.setupAnimations();
            this.setupAccessibility();
            this.setupNotifications();
        }

        // Sidebar Toggle Functionality
        setupSidebarToggle() {
            const $sidebarToggle = $('.app-sidebar__toggle');
            const $sidebar = $('.app-sidebar');
            const $overlay = $('.app-sidebar__overlay');
            const $body = $('body');

            $sidebarToggle.on('click', function(e) {
                e.preventDefault();
                
                if ($body.hasClass('sidebar-open')) {
                    $body.removeClass('sidebar-open');
                    $sidebar.removeClass('sidebar-open');
                    $overlay.fadeOut(300);
                } else {
                    $body.addClass('sidebar-open');
                    $sidebar.addClass('sidebar-open');
                    $overlay.fadeIn(300);
                }
            });

            // Close sidebar when clicking overlay
            $overlay.on('click', function() {
                $body.removeClass('sidebar-open');
                $sidebar.removeClass('sidebar-open');
                $overlay.fadeOut(300);
            });

            // Close sidebar on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $body.hasClass('sidebar-open')) {
                    $body.removeClass('sidebar-open');
                    $sidebar.removeClass('sidebar-open');
                    $overlay.fadeOut(300);
                }
            });
        }

        // Enhanced Search Functionality
        setupSearchFunctionality() {
            const $searchInput = $('.search-input');
            const $searchBtn = $('.search-btn');
            const $searchBox = $('.search-box');

            // Search input focus effects
            $searchInput.on('focus', function() {
                $searchBox.addClass('search-focused');
                $(this).parent().addClass('glow-effect');
            });

            $searchInput.on('blur', function() {
                $searchBox.removeClass('search-focused');
                $(this).parent().removeClass('glow-effect');
            });

            // Search button functionality
            $searchBtn.on('click', function(e) {
                e.preventDefault();
                const query = $searchInput.val().trim();
                if (query) {
                    this.performSearch(query);
                }
            });

            // Enter key search
            $searchInput.on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const query = $(this).val().trim();
                    if (query) {
                        this.performSearch(query);
                    }
                }
            });

            // Auto-suggest functionality
            $searchInput.on('input', function() {
                const query = $(this).val().trim();
                if (query.length >= 2) {
                    this.showSearchSuggestions(query);
                } else {
                    this.hideSearchSuggestions();
                }
            });
        }

        // Perform search
        performSearch(query) {
            // Show loading state
            const $searchBtn = $('.search-btn');
            const originalContent = $searchBtn.html();
            
            $searchBtn.html('<i class="bi bi-arrow-clockwise spin"></i>');
            $searchBtn.prop('disabled', true);

            // Simulate search (replace with actual search logic)
            setTimeout(() => {
                $searchBtn.html(originalContent);
                $searchBtn.prop('disabled', false);
                
                // Show search results or redirect
                this.showSearchResults(query);
            }, 1000);
        }

        // Show search suggestions
        showSearchSuggestions(query) {
            // Implementation for search suggestions
            console.log('Showing suggestions for:', query);
        }

        // Hide search suggestions
        hideSearchSuggestions() {
            // Implementation to hide suggestions
            console.log('Hiding suggestions');
        }

        // Show search results
        showSearchResults(query) {
            // Implementation for showing results
            console.log('Showing results for:', query);
        }

        // Enhanced Dropdown Menus
        setupDropdownMenus() {
            const $dropdowns = $('.dropdown');

            $dropdowns.each(function() {
                const $dropdown = $(this);
                const $menu = $dropdown.find('.dropdown-menu');
                const $toggle = $dropdown.find('[data-bs-toggle="dropdown"]');

                // Custom dropdown behavior
                $toggle.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other dropdowns
                    $dropdowns.not($dropdown).removeClass('show');
                    $dropdowns.not($dropdown).find('.dropdown-menu').removeClass('show');
                    
                    // Toggle current dropdown
                    $dropdown.toggleClass('show');
                    $menu.toggleClass('show');
                });

                // Dropdown item hover effects
                $menu.find('.dropdown-item').on('mouseenter', function() {
                    $(this).addClass('hover-effect');
                }).on('mouseleave', function() {
                    $(this).removeClass('hover-effect');
                });
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $dropdowns.removeClass('show');
                    $dropdowns.find('.dropdown-menu').removeClass('show');
                }
            });
        }

        // Responsive Behavior
        setupResponsiveBehavior() {
            const $header = $('.app-header');
            let lastScrollTop = 0;

            // Header scroll effects
            $(window).on('scroll', function() {
                const scrollTop = $(this).scrollTop();
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down - hide header
                    $header.addClass('header-hidden');
                } else {
                    // Scrolling up - show header
                    $header.removeClass('header-hidden');
                }
                
                lastScrollTop = scrollTop;
            });

            // Mobile menu behavior
            if ($(window).width() <= 768) {
                this.setupMobileMenu();
            }

            // Resize handler
            $(window).on('resize', function() {
                if ($(window).width() <= 768) {
                    this.setupMobileMenu();
                }
            });
        }

        // Mobile menu setup
        setupMobileMenu() {
            const $sidebar = $('.app-sidebar');
            const $body = $('body');

            // Ensure sidebar is closed on mobile
            if ($(window).width() <= 768) {
                $body.removeClass('sidebar-open');
                $sidebar.removeClass('sidebar-open');
            }
        }

        // Animations and Effects
        setupAnimations() {
            // Header entrance animation
            $('.app-header').addClass('animate-in');

            // Logo hover animation
            $('.app-header__logo').on('mouseenter', function() {
                $(this).addClass('logo-hover');
            }).on('mouseleave', function() {
                $(this).removeClass('logo-hover');
            });

            // User avatar pulse effect
            $('.user-avatar').on('mouseenter', function() {
                $(this).addClass('pulse-effect');
            }).on('mouseleave', function() {
                $(this).removeClass('pulse-effect');
            });

            // Search box animations
            $('.search-box').on('mouseenter', function() {
                $(this).addClass('search-hover');
            }).on('mouseleave', function() {
                $(this).removeClass('search-hover');
            });
        }

        // Accessibility Features
        setupAccessibility() {
            // Keyboard navigation
            $('.app-sidebar__toggle').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).click();
                }
            });

            // ARIA labels and roles
            $('.user-avatar').attr('aria-label', 'Open Profile Menu');
            $('.app-sidebar__toggle').attr('aria-label', 'Toggle Sidebar');

            // Focus management
            $('.dropdown-item').on('focus', function() {
                $(this).addClass('focused');
            }).on('blur', function() {
                $(this).removeClass('focused');
            });
        }

        // Notification System
        setupNotifications() {
            // Check for new notifications
            this.checkNotifications();

            // Setup notification polling
            setInterval(() => {
                this.checkNotifications();
            }, 300000); // Check every 5 minutes
        }

        // Check for notifications
        checkNotifications() {
            // Implementation for checking notifications
            // This could make an AJAX call to check for new announcements, results, etc.
            console.log('Checking for notifications...');
        }

        // Utility Methods
        showNotification(message, type = 'info') {
            // Implementation for showing notifications
            console.log(`${type}: ${message}`);
        }

        updateGreeting() {
            const hour = new Date().getHours();
            let greeting = 'Good evening';
            
            if (hour >= 5 && hour <= 11) {
                greeting = 'Good morning';
            } else if (hour >= 12 && hour <= 15) {
                greeting = 'Good afternoon';
            }

            $('.user-greeting').text(greeting);
            $('.user-status').text(`${greeting}! 👋`);
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        // Initialize student header
        const studentHeader = new StudentHeader();

        // Update greeting on page load
        studentHeader.updateGreeting();

        // Global utility functions
        window.StudentHeader = {
            showNotification: studentHeader.showNotification.bind(studentHeader),
            updateGreeting: studentHeader.updateGreeting.bind(studentHeader)
        };
    });

    // Add CSS for animations and effects
    const additionalCSS = `
        <style>
            /* Header animations */
            .app-header.animate-in {
                animation: fadeInDown 0.5s ease-out;
            }

            .app-header.header-hidden {
                transform: translateY(-100%);
                transition: transform 0.3s ease;
            }

            /* Logo hover effect */
            .app-header__logo.logo-hover {
                transform: translateY(-2px);
                text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            /* Pulse effect for user avatar */
            .user-avatar.pulse-effect {
                animation: pulse 0.6s ease-in-out;
            }

            /* Search box hover effect */
            .search-box.search-hover {
                transform: scale(1.02);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            }

            /* Search focused state */
            .search-box.search-focused {
                box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
            }

            /* Glow effect */
            .glow-effect {
                box-shadow: 0 0 20px rgba(102, 126, 234, 0.4);
            }

            /* Dropdown animations */
            .dropdown-menu.show {
                animation: slideDown 0.3s ease-out;
            }

            .dropdown-item.hover-effect {
                transform: translateX(5px);
            }

            .dropdown-item.focused {
                outline: 2px solid rgba(102, 126, 234, 0.5);
                outline-offset: 2px;
            }

            /* Spinning animation for loading */
            .spin {
                animation: spin 1s linear infinite;
            }

            /* Keyframe animations */
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }

            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive sidebar */
            @media (max-width: 768px) {
                .app-sidebar {
                    transform: translateX(-100%);
                    transition: transform 0.3s ease;
                }

                .app-sidebar.sidebar-open {
                    transform: translateX(0);
                }

                .app-sidebar__overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 998;
                    display: none;
                }
            }
        </style>
    `;

    // Inject additional CSS
    $('head').append(additionalCSS);

})(jQuery); 