// Enhanced Header JavaScript - Minimal and Clean
$(document).ready(function () {

    // Let the original system handle sidebar toggle - don't override icon changes
    // The original main.js will handle the data-toggle="sidebar" functionality

    // Enhanced dropdown functionality
    $('.dropdown').on('show.bs.dropdown', function () {
        $(this).find('.dropdown-menu').addClass('show');
    });

    $('.dropdown').on('hide.bs.dropdown', function () {
        $(this).find('.dropdown-menu').removeClass('show');
    });

    // Smooth dropdown animations
    $('.dropdown-menu').css({'transition': 'all 0.3s ease', 'transform-origin': 'top right'});

    // Add subtle shadow effect on scroll
    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 10) {
            $('.app-header').css('box-shadow', '0 2px 8px rgba(0, 0, 0, 0.15)');
        } else {
            $('.app-header').css('box-shadow', '0 2px 4px rgba(0, 0, 0, 0.1)');
        }
    });

    // Close sidebar when clicking overlay (mobile only)
    $('.app-sidebar__overlay').on('click', function () {
        if ($(window).width() <= 768) {
            $('.app').removeClass('sidenav-toggled');
        }
    });

    // Add keyboard navigation for accessibility
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') { // Close sidebar if open (mobile only)
            if ($('.app').hasClass('sidenav-toggled') && $(window).width() <= 768) {
                $('.app').removeClass('sidenav-toggled');
            }

            // Close dropdowns
            $('.dropdown-menu').removeClass('show');
            $('.dropdown').removeClass('show');
        }
    });

    // Close dropdowns when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
            $('.dropdown').removeClass('show');
        }
    });

    // Handle window resize - ensure sidebar is visible on desktop
    $(window).on('resize', function () {
        if ($(window).width() > 768) { // On desktop, ensure sidebar is visible
            $('.app').removeClass('sidenav-toggled');
        }
    });

});

// Add minimal CSS animations for header only - removed conflicting responsive rules
const headerStyle = document.createElement('style');
headerStyle.textContent = `
    /* Modern Header Layout */
    .app-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        background: linear-gradient(135deg, #00695C 0%, #00594e 100%);
        color: white;
        height: 70px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .header-center {
        flex: 1;
        display: flex;
        justify-content: center;
        max-width: 500px;
        margin: 0 20px;
    }
    
    .header-right {
        display: flex;
        align-items: center;
    }
    
    /* Search Box Styles */
    .search-container {
        width: 100%;
    }
    
    .search-box {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 25px;
        padding: 8px 15px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-icon {
        color: rgba(255, 255, 255, 0.7);
        margin-right: 10px;
        font-size: 16px;
    }
    
    .search-input {
        flex: 1;
        background: transparent;
        border: none;
        color: white;
        font-size: 14px;
        outline: none;
    }
    
    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .search-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .search-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }
    
    /* User Section Styles */
    .user-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .user-info {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    
    .user-name {
        font-weight: 600;
        font-size: 14px;
        color: white;
        line-height: 1.2;
    }
    
    .user-role {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.2;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .user-avatar:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }
    
    /* Hamburger Icon Styles - Only basic styling, responsive behavior handled by CSS files */
    .app-sidebar__toggle {
        background: transparent !important;
        border: none !important;
        color: white !important;
        font-size: 20px;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .app-sidebar__toggle:hover,
    .app-sidebar__toggle:focus,
    .app-sidebar__toggle:active {
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        box-shadow: none !important;
    }
    
    /* Logo Styles */
    .app-header__logo {
        background: transparent !important;
        color: white !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .app-header__logo:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .app-header__logo::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s ease;
    }
    
    .app-header__logo:hover::before {
        left: 100%;
    }
    
    .app-header__logo h2 {
        background: transparent !important;
        color: white !important;
        margin: 0;
        font-weight: 600;
        font-size: 1.4rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    .app-header__logo:hover h2 {
        transform: scale(1.02);
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
    }
    
    /* School Logo Image Styles */
    .school-logo {
        max-height: 50px;
        max-width: 180px;
        object-fit: contain;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin: 0;
        border-radius: 6px;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        position: relative;
        z-index: 1;
    }
    
    .school-logo:hover {
        transform: scale(1.08) rotate(1deg);
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
    }
    
    /* Logo container glow effect */
    .app-header__logo:hover .school-logo {
        animation: logoGlow 2s ease-in-out infinite alternate;
    }
    
    @keyframes logoGlow {
        0% {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2)) drop-shadow(0 0 5px rgba(255, 255, 255, 0.1));
        }
        100% {
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3)) drop-shadow(0 0 10px rgba(255, 255, 255, 0.2));
        }
    }
    
    /* Responsive Logo Design */
    @media (max-width: 768px) {
        .school-logo {
            max-height: 40px;
            max-width: 140px;
        }
        
        .app-header__logo h2 {
            font-size: 1.2rem;
        }
        
        .app-header__logo {
            padding: 6px 8px;
        }
    }
    
    @media (max-width: 480px) {
        .school-logo {
            max-height: 35px;
            max-width: 120px;
        }
        
        .app-header__logo h2 {
            font-size: 1.1rem;
        }
    }
    
    /* Desktop enhancements */
    @media (min-width: 769px) {
        .app-header__logo {
            padding: 10px 16px;
        }
        
        .school-logo {
            max-height: 55px;
            max-width: 200px;
        }
        
        .app-header__logo h2 {
            font-size: 1.5rem;
        }
    }
    
    /* Responsive Design - Only hide search and user info on mobile */
    @media (max-width: 768px) {
        .header-center {
            display: none;
        }
        
        .user-info {
            display: none;
        }
        
        .app-header {
            padding: 0 15px;
        }
    }
`;
document.head.appendChild(headerStyle);
