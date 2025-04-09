// Configuration
const themeConfig = {
    allowedDomains: ['localhost', 'test.localhost'],
    excludedPages: ['/login', '/admin/*', '/special-page.html'],
    themes1: [
        {
            name: "Default",
            url: "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css",
            icon: "bi-sun"
        },
        {
            name: "Darkly",
            url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/darkly/bootstrap.min.css",
            icon: "bi-moon"
        }
    ],
    storageKey: 'globalTheme',
    cookieDomain: '.localhost',

themes: [
    {
        name: "Bootstrap Default",
        url: "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    },
    {
        name: "Darkly",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/darkly/bootstrap.min.css"
    },
    {
        name: "Morph",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/morph/bootstrap.min.css"
    },
    {
        name: "Solar",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/solar/bootstrap.min.css"
    },
    {
        name: "Superhero",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/superhero/bootstrap.min.css"
    },
    {
        name: "Cyborg",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/cyborg/bootstrap.min.css"
    },
    {
        name: "Vapor",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/vapor/bootstrap.min.css"
    },
    {
        name: "Quartz",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/quartz/bootstrap.min.css"
    },
    {
        name: "Sketchy",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/sketchy/bootstrap.min.css"
    },
    {
        name: "Yeti",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/yeti/bootstrap.min.css"
    },
    {
        name: "Pulse",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/pulse/bootstrap.min.css"
    },
    {
        name: "Lux",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/lux/bootstrap.min.css"
    },
    {
        name: "Materia",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/materia/bootstrap.min.css"
    },
    {
        name: "Litera",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/litera/bootstrap.min.css"
    },
    {
        name: "United",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/united/bootstrap.min.css"
    },
    {
        name: "Journal",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/journal/bootstrap.min.css"
    },
    {
        name: "Flatly",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/flatly/bootstrap.min.css"
    },
    {
        name: "Simplex",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/simplex/bootstrap.min.css"
    },
    {
        name: "Minty",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/minty/bootstrap.min.css"
    },
    {
        name: "Porphyro",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/porphyro/bootstrap.min.css"
    },
    {
        name: "Slate",
        url: "https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/slate/bootstrap.min.css"
    }
],
};
class ThemeManager {
    constructor() {
        this.currentUrl = window.location.href;
        this.shouldApplyTheme = this.checkDomain() && !this.isExcludedPage();
        
        if (this.shouldApplyTheme) {
            this.initTheme();
            this.injectSwitcher();
        }
    }

    checkDomain() {
        return themeConfig.allowedDomains.some(domain => 
            window.location.hostname === domain ||
            window.location.hostname.endsWith(`.${domain}`)
        );
    }

    isExcludedPage() {
        const path = window.location.pathname + window.location.search;
        return themeConfig.excludedPages.some(pattern => {
            if (pattern.endsWith('/*')) {
                return path.startsWith(pattern.slice(0, -2));
            }
            return path === pattern;
        });
    }

    initTheme() {
        const savedTheme = this.getStoredTheme();
        this.setTheme(savedTheme || themeConfig.themes[0].url);
    }

    getStoredTheme() {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${themeConfig.storageKey}=`);
        return parts.length === 2 ? parts.pop().split(';').shift() : null;
    }

    setTheme(themeUrl) {
        document.cookie = `${themeConfig.storageKey}=${themeUrl}; ` +
            `domain=${themeConfig.cookieDomain}; path=/; max-age=${365*24*60*60}`;
        
        let link = document.getElementById('theme-stylesheet');
        if (!link) {
            link = document.createElement('link');
            link.id = 'theme-stylesheet';
            link.rel = 'stylesheet';
            document.head.appendChild(link);
        }
        link.href = themeUrl;
    }

    injectSwitcher() {
        const switcherHTML = `
            <div class="theme-switcher" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" 
                            type="button" 
                            data-bs-toggle="dropdown">
                        <i class="bi bi-palette"></i> Theme
                    </button>
                    <ul class="dropdown-menu">
                        ${themeConfig.themes.map(theme => `
                            <li><a class="dropdown-item" href="#" 
                                data-theme="${theme.url}">
                                <i class="bi ${theme.icon}"></i> ${theme.name}
                            </a></li>
                        `).join('')}
                    </ul>
                </div>
            </div>
        `; 
        const container = document.createElement('div');
        container.innerHTML = switcherHTML;
        document.body.appendChild(container);
        
        container.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                this.setTheme(item.dataset.theme);
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ThemeManager();
});
(function() {
    // Check if the window has already loaded
    if (document.readyState === 'complete') {
        initializeThemeManager();
    } else {
        window.addEventListener('load', initializeThemeManager);
    }

    function initializeThemeManager() {
        new ThemeManager();
        document.addEventListener('DOMContentLoaded', () => {
            new ThemeManager();
        });
        // Your script's code here
      //  console.log('Theme Manager loaded successfully!');
    }
})();