// Document Ready Function
$(document).ready(function() {
    // Initialize components
    initThemeSwitcher();
    initAlerts();
    
    // Other initialization functions can be added here
});

// Theme Switcher Functionality
function initThemeSwitcher() {
    const themes = {
        'default': 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        'darkly': 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/darkly/bootstrap.min.css',
        'flatly': 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/flatly/bootstrap.min.css',
        'journal': 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/journal/bootstrap.min.css',
        'materia': 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/materia/bootstrap.min.css'
    };

    // Create theme switcher UI
    const themeSwitcherHTML = `
        <div class="theme-switcher-container">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="themeDropdown" data-bs-toggle="dropdown">
                Select Theme
            </button>
            <ul class="dropdown-menu" aria-labelledby="themeDropdown">
                <li><a class="dropdown-item theme-option" href="#" data-theme="default">Default Bootstrap</a></li>
                <li><a class="dropdown-item theme-option" href="#" data-theme="darkly">Darkly</a></li>
                <li><a class="dropdown-item theme-option" href="#" data-theme="flatly">Flatly</a></li>
                <li><a class="dropdown-item theme-option" href="#" data-theme="journal">Journal</a></li>
                <li><a class="dropdown-item theme-option" href="#" data-theme="materia">Materia</a></li>
            </ul>
        </div>
    `;

    // Add theme switcher to page
    $('body').prepend(themeSwitcherHTML);

    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        setTheme(savedTheme);
    }

    // Theme selection handler
    $('.theme-option').click(function(e) {
        e.preventDefault();
        const themeName = $(this).data('theme');
        setTheme(themeName);
    });

    function setTheme(themeName) {
        $('#bootstrap-theme').attr('href', themes[themeName]);
        localStorage.setItem('theme', themeName);
    }
}

// Alert Initialization
function initAlerts() {
    // Close button functionality for alerts
    $('.alert .close').click(function() {
        $(this).parent().fadeOut();
    });
    
    // Auto-hide success alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut();
    }, 5000);
}

// Add any other JavaScript functionality here