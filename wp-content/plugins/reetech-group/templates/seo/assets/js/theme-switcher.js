document.addEventListener('DOMContentLoaded', function() {
    // Available themes
    const themes = {
        'default': '/assets/css/theme-default.css',
        'dark': '/assets/css/theme-dark.css',
        'corporate': '/assets/css/theme-corporate.css',
        'custom': '/assets/css/theme-custom.css'
    };

    // Get or set theme from localStorage
    function getCurrentTheme() {
        return localStorage.getItem('theme') || 'default';
    }

    function setTheme(themeName) {
        if (!themes[themeName]) return;
        
        localStorage.setItem('theme', themeName);
        document.getElementById('theme-style').href = themes[themeName];
        
        // Update dropdown to show current theme
        document.querySelectorAll('.theme-option').forEach(option => {
            option.classList.toggle('active', option.dataset.theme === themeName);
        });
        
        // Dispatch event for other components
        document.dispatchEvent(new CustomEvent('themeChanged', { detail: themeName }));
    }

    // Initialize theme
    function initTheme() {
        const savedTheme = getCurrentTheme();
        const themeLink = document.createElement('link');
        themeLink.id = 'theme-style';
        themeLink.rel = 'stylesheet';
        themeLink.href = themes[savedTheme];
        document.head.appendChild(themeLink);
        
        setTheme(savedTheme);
    }

    // Event listeners for theme switcher
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('theme-option')) {
            e.preventDefault();
            setTheme(e.target.dataset.theme);
        }
    });

    // Listen for theme changes across tabs/windows
    window.addEventListener('storage', function(e) {
        if (e.key === 'theme') {
            setTheme(e.newValue);
        }
    });

    // Initialize
    initTheme();
});
// Helper function to get cookie value
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

// Modified getCurrentTheme function
function getCurrentTheme() {
    // 1. First try to get from cookie (works across subdomains)
    const cookieTheme = getCookie('theme');
    if (cookieTheme && themes[cookieTheme]) return cookieTheme;
    
    // 2. Fallback to localStorage (single domain)
    const localTheme = localStorage.getItem('theme');
    if (localTheme && themes[localTheme]) return localTheme;
    
    // 3. Default theme
    return 'default';
}

// Modified setTheme function
function setTheme(themeName) {
    if (!themes[themeName]) return;
    
    // Set cookie for all subdomains (replace '.example.com' with your actual domain)
    const domain = window.location.hostname.includes('example.com') 
        ? '.example.com' 
        : window.location.hostname;
    
    document.cookie = `theme=${themeName}; path=/; domain=${domain}; max-age=31536000; SameSite=Lax`;
    
    // Also set localStorage for current domain
    localStorage.setItem('theme', themeName);
    
    // Apply the theme
    document.getElementById('theme-style').href = themes[themeName];
    
    // Update UI
    document.querySelectorAll('.theme-option').forEach(option => {
        option.classList.toggle('active', option.dataset.theme === themeName);
    });
    
    // Notify other components
    document.dispatchEvent(new CustomEvent('themeChanged', { detail: themeName }));
}