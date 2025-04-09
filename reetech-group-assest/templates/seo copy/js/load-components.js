document.addEventListener('DOMContentLoaded', function() {
    // Function to load components with better SEO handling
    function loadComponents() {
        const containers = document.querySelectorAll('[data-component]');
        
        containers.forEach(container => {
            const componentPath = container.getAttribute('data-component');
            
            fetch(componentPath)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    // Special handling for meta tags
                    if (container.id === 'meta-container') {
                        const metaTags = new DOMParser()
                            .parseFromString(html, 'text/html')
                            .querySelectorAll('meta, link[rel="canonical"], link[rel="icon"]');
                        
                        metaTags.forEach(tag => {
                            const existingTag = document.head.querySelector(
                                tag.tagName === 'META' ? 
                                `meta[name="${tag.name}"]` : 
                                `link[rel="${tag.rel}"]`
                            );
                            
                            if (existingTag) {
                                document.head.removeChild(existingTag);
                            }
                            document.head.appendChild(tag.cloneNode(true));
                        });
                    } else {
                        container.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error loading component:', error);
                    // Fallback content can be added here if needed
                });
        });
        const themeScript = document.createElement('script');
    themeScript.src = '/assets/js/theme-switcher.js';
    document.body.appendChild(themeScript);
    }

    // Load all components
    loadComponents();
    
    // For better SEO, you might want to update the page title after loading
    window.addEventListener('componentsLoaded', function() {
        document.title = document.querySelector('meta[property="og:title"]')?.content || document.title;
    });
});