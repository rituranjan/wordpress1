// <!-- Dependencies -->
// <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
// <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
// <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

// <script>
function initializeComboAutoComplete(options) {
    const settings = $.extend({
        selector: '.combo-select',
        sourceData: {},
        itemValueKey: 'item_value',
        itemTextKey: 'item_text',
        minChars: 2,
        maxItems: 10
    }, options);

    // Process data and create options
    const items = [];
    Object.keys(settings.sourceData).forEach(key => {
        if (!isNaN(key)) {
            const item = settings.sourceData[key];
            items.push({
                value: item[settings.itemValueKey],
                text: item[settings.itemTextKey]
            });
        }
    });

    // Initialize each select element
    $(settings.selector).each(function() {
        const $originalSelect = $(this);
        const selectName = $originalSelect.attr('name');
        const selectId = $originalSelect.attr('id');
        
        // Create combo container
        const $comboWrapper = $(`
            <div class="combo-wrapper position-relative">
                <input type="text" 
                    class="form-control combo-input" 
                    autocomplete="off"
                    placeholder="Type or select..."
                    role="combobox"
                    aria-haspopup="listbox"
                    aria-expanded="false">
                <div class="combo-dropdown dropdown-menu w-100"></div>
                <select class="combo-select-hidden" 
                    name="${selectName}" 
                    id="${selectId}" 
                    style="display: none"></select>
            </div>
        `).insertAfter($originalSelect);
        
        const $hiddenSelect = $comboWrapper.find('.combo-select-hidden');
        const $input = $comboWrapper.find('.combo-input');
        const $dropdown = $comboWrapper.find('.combo-dropdown');

        // Populate hidden select and dropdown
        items.forEach(item => {
            $hiddenSelect.append(new Option(item.text, item.value));
            $dropdown.append(`
                <a class="dropdown-item" href="#" data-value="${item.value}">
                    ${item.text}
                </a>
            `);
        });

        // Input event handling
        $input.on('input focus', function(e) {
            const searchTerm = $(this).val().toLowerCase();
            const $items = $dropdown.find('.dropdown-item');
            
            $items.each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchTerm));
            });

            $dropdown.show();
            $input.attr('aria-expanded', 'true');
        });

        // Dropdown item selection
        $dropdown.on('click', '.dropdown-item', function(e) {
            e.preventDefault();
            const value = $(this).data('value');
            const text = $(this).text();
            
            $input.val(text)
                  .attr('data-value', value)
                  .trigger('change');
                  
            $hiddenSelect.val(value)
                         .trigger('change');
            
            $dropdown.hide();
            $input.attr('aria-expanded', 'false');
        });

        // Keyboard navigation
        $input.on('keydown', function(e) {
            const $visibleItems = $dropdown.find('.dropdown-item:visible');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                $visibleItems.first().focus();
            } else if (e.key === 'Escape') {
                $dropdown.hide();
            }
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.combo-wrapper').length) {
                $dropdown.hide();
                $input.attr('aria-expanded', 'false');
            }
        });

        // Remove original select
        $originalSelect.remove();
    });
}
{/* </script> */}

{/* <!-- HTML Implementation -->
<select class="combo-select form-select" 
        name="item[]" 
        id="itemSelect0"
        style="display: none">
    <!-- Original options will be generated dynamically -->
</select> */}
{/* 
<script>
// Initialize with your data
$(document).ready(function() {
    const response = { /* your JSON data here */ };
    
//     if (response.success) {
//         initializeComboAutoComplete({
//             selector: '.combo-select',
//             sourceData: response.data,
//             itemValueKey: 'item_value',
//             itemTextKey: 'item_text',
//             minChars: 1,
//             maxItems: 8
//         });
//     }
// });
// </script> */}

//-------------------------------------------




// <div class="select-wrapper">
//     <select class="custom-select form-select" aria-label="Custom select example">
//         <option value="" disabled selected>Select an option</option>
//         <!-- Options will be dynamically populated -->
//     </select>
//     <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
//         <path d="M1 1.5L6 6.5L11 1.5" stroke="#495057" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
//     </svg>
// </div>

class CustomSelect {
    constructor(selector, options = {}) {
        // 1. Check if selector exists
        this.container = document.querySelector(selector);
        if (!this.container) {
            console.error(`Element with selector "${selector}" not found`);
            return;
        }

        // 2. Create component HTML structure
        this.options = options;
        this.createStructure();
        this.initialize();
    }

    createStructure() {
        // Build complete HTML structure
        this.container.innerHTML = `
            <div class="select-wrapper">
                <select class="custom-select form-select" 
                        aria-label="${this.options.ariaLabel || 'Custom select'}">
                    <option value="" disabled selected>${this.options.placeholder || 'Select...'}</option>
                </select>
                <svg class="select-arrow" width="12" height="8" viewBox="0 0 12 8">
                    <path d="M1 1.5L6 6.5L11 1.5" stroke="#495057" 
                          stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        `;

        // Cache elements
        this.select = this.container.querySelector('select');
        this.wrapper = this.container.querySelector('.select-wrapper');
    }

    initialize() {
        // Populate options if data provided
        if (this.options.data) {
            this.populateOptions(this.options.data);
        }

        // Set initial value
        if (this.options.initialValue) {
            this.setValue(this.options.initialValue);
        }

        // Add Bootstrap classes
        this.container.classList.add('mb-3');
        this.select.classList.add('form-select');
    }
    populateOptions(data) {
        // Clear existing options
        this.select.innerHTML = '';
        
        // Add default option
        const defaultOption = new Option(
            this.options.placeholder || 'Select an option',
            '',
            true,
            true
        );
        defaultOption.disabled = true;
        defaultOption.hidden = true;
        this.select.appendChild(defaultOption);

        // Add data options
        data.forEach(item => {
            const option = new Option(
                item.item_text,
                item.item_value
            );
            this.select.appendChild(option);
        });
    }

    getValue() {
        return this.select.value;
    }

    getSelectedText() {
        return this.select.options[this.select.selectedIndex].text;
    }

    setValue(value) {
        this.select.value = value;
    }

    on(event, callback) {
        this.select.addEventListener(event, callback);
    }

    destroy() {
        this.element.parentNode.removeChild(this.element);
    }
}
