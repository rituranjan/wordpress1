$(document).ready(function() {
    // Theme URLs (using Bootswatch CDN)
    const themes = {
        'default': 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        'darkly': 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/darkly/bootstrap.min.css',
        'flatly': 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/flatly/bootstrap.min.css',
        'journal': 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/journal/bootstrap.min.css',
        'materia': 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/materia/bootstrap.min.css'
    };

    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        $('#bootstrap-theme').attr('href', themes[savedTheme]);
    }

    // Theme switcher functionality
    $('.theme-option').click(function(e) {
        e.preventDefault();
        const themeName = $(this).data('theme');
        $('#bootstrap-theme').attr('href', themes[themeName]);
        
        // Save preference to localStorage
        localStorage.setItem('theme', themeName);
        
        // Update UI to show selected theme
        $('.theme-option').removeClass('active');
        $(this).addClass('active');
        $('#themeDropdown').text($(this).text());
    });
    
    // Highlight current theme in dropdown
    if (savedTheme) {
        $(`.theme-option[data-theme="${savedTheme}"]`).addClass('active');
        $('#themeDropdown').text($(`.theme-option[data-theme="${savedTheme}"]`).text());
    }
});