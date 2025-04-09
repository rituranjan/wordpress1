
class GenericDataTable {
    constructor(config) {
        this.config = {
            container: '#data-table-container',
            tableId: '#main-data-table',
            ajax: { url: '', method: 'GET', headers: {} },
            filters: [],
            columns: [],
            actions: [],
            translations: {
                emptyTable: "No data found",
                processing: "<i class='fa fa-spinner fa-spin'></i> Loading..."
            },
            filterSettings: {
                numberOperators: ['=', '≠', '>', '<', '≥', '≤'],
                stringOperators: ['contains', 'not contains', 'starts', 'ends', '='],
                dateOperators: ['range']
            },
            ...config
        };

        this.init();
    }

    init() {
      //  this.initTheme();
        this.createFilterSection();
        this.initDataTable();
        this.bindEvents();
        //if(this.config.themeSwitcher) this.initThemeSwitcher();
    }

    initTheme() {
        const savedTheme = localStorage.getItem('selectedTheme');
        const defaultTheme = this.config.themes[0].url;
        this.loadTheme(savedTheme || defaultTheme);
    }



    createFilterSection() {
    const hasFilters = this.config.filters.length > 0;
    
    const filterHtml =
     ` ${hasFilters ? `
        <div class="filter-row">
            <div class="row row-cols-1 row-cols-lg-3 g-2 align-items-end">
                ${this.config.filters.map(filter => this.createFilterControl(filter)).join('')}
               
                <div class="col">
                    <button class="btn btn-primary w-100 reset-filters">
                        <i class="fas fa-sync"></i> Reset
                    </button>
                </div>
                            </div>
        </div>` : ''}

        <table id="${this.config.tableId.replace('#', '')}" class="table table-striped" style="width:100%"></table>
    `;

    $(this.config.container).append(filterHtml);
    this.initSelectPickers();
}


    createFilterControl(filter) {
        switch(filter.type) {
            case 'multiselect':
                return `
                    <div class="col">
                        <label class="form-label">${filter.label}</label>
                        <select class="selectpicker form-control ${filter.class || ''}" 
                                id="${filter.id}" multiple 
                                data-live-search="true" 
                                title="${filter.placeholder || 'Select...'}">
                            ${filter.options ? filter.options.map(opt => 
                                `<option value="${opt.value}">${opt.label}</option>`).join('') : ''}
                        </select>
                    </div>`;
            case 'date':
                return `
                    <div class="col">
                        <label class="form-label">${filter.label}</label>
                        <input type="date" class="form-control ${filter.class || ''}" 
                               id="${filter.id}" ${filter.range ? 'data-range' : ''}>
                    </div>`;
            default:
                return '';
        }
    }

    initSelectPickers() {
        $('.selectpicker').selectpicker();
    }

    initDataTable() {
        this.dataTable = $(this.config.tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: this.config.ajax.url,
                type: this.config.ajax.method,
                headers: this.config.ajax.headers,
                data: (d) => this.getRequestData(d)
            },
            columns: this.getColumnsConfig(),
            language: this.config.translations,
            createdRow: (row, data) => this.handleRowCreation(row, data),
        
           initComplete: () => this.initHeaderFilters()
        });
    }

    initHeaderFilters() {
        const self = this;
        if(self.config.columnFiltersShow){
        this.dataTable.columns().every(function(index) {
            const column = this;
            const colConfig = self.config.columns[index];
            const header = $(column.header());
            
            if (colConfig.data && colConfig.filter?.enabled !== false) {
                header.html(`
                    <div class="column-header-content">
                        ${colConfig.title}
                        <div class="column-filter-container">
                            ${self.createColumnFilterInput(colConfig)}
                        </div>
                    </div>
                `);
            }
        });}
    }

    createColumnFilterInput(colConfig) {
        const filterType = this.detectFilterType(colConfig);
        if (filterType === 'none') return '';
        
        const operators = this.getOperatorsForType(filterType);
        let filterHtml = '';
        
        if(operators.length > 1) {
            filterHtml += `
                <select class="form-control filter-operator" data-column="${colConfig.data}">
                    ${operators.map(op => `<option value="${op}">${op}</option>`).join('')}
                </select>
            `;
        }

        switch(filterType) {
            case 'number':
                filterHtml += `<input type="number" class="form-control column-filter" 
                                  data-column="${colConfig.data}" placeholder="Value">`;
                break;
            case 'string':
                filterHtml += `<input type="text" class="form-control column-filter" 
                                  data-column="${colConfig.data}" placeholder="Search...">`;
                break;
            case 'date':
                filterHtml += `
                    <div class="date-range-filter">
                        <input type="date" class="form-control column-filter" 
                               data-column="${colConfig.data}" placeholder="From">
                        <input type="date" class="form-control column-filter" 
                               data-column="${colConfig.data}" placeholder="To">
                    </div>`;
                break;
        }
        
        return filterHtml;
    }

    detectFilterType(colConfig) {
        if (!colConfig.data) return 'none';
        const dataField = colConfig.data.toLowerCase();
        if (dataField.includes('date')) return 'date';
        if (['number', 'total', 'balance', 'price', 'amount'].some(k => dataField.includes(k))) return 'number';
        return 'string';
    }

    getOperatorsForType(type) {
        return this.config.filterSettings[`${type}Operators`] || [];
    }

    getRequestData(d) {
        const filterData = {};
        this.config.filters.forEach(filter => {
            filterData[filter.id] = $(`#${filter.id}`).val();
        });

        const columnFilters = {};
        $(`.column-filter, .filter-operator`).each(function() {
            const column = $(this).data('column');
            const operator = $(`.filter-operator[data-column="${column}"]`).val() || '=';
            const values = $(`.column-filter[data-column="${column}"]`)
                         .map((i, el) => $(el).val()).get();

            if(values.some(v => v)) {
                columnFilters[column] = { operator, values };
            }
        });

        return {
            draw: d.draw,
            start: d.start,
            length: d.length,
            search: d.search.value,
            orderby: d.columns[d.order[0].column].data,
            order: d.order[0].dir,
            ...filterData,
            columnFilters
        };
    }

    getColumnsConfig() {
        return this.config.columns.map(col => ({
            data: col.data,
            title: col.title,
            className: col.className || '',
            orderable: col.orderable !== false,
            render: (data, type, row) => this.renderColumn(col, data, type, row)
        }));
    }

    renderColumn(col, data, type, row) {
        if (typeof col.render === 'function') return col.render(data, type, row);
        if (col.type === 'link') return `<a href="${col.href(row)}" class="${col.class || ''}">${data}</a>`;
        if (col.type === 'badge') {
            const color = typeof col.color === 'function' ? col.color(data) : col.color;
            return `<span class="badge bg-${color}">${data}</span>`;
        }
        if (col.type === 'actions') return this.renderActions(row);
        return data;
    }

    renderActions(row) {
        return `
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                        type="button" data-bs-toggle="dropdown">
                    Actions
                </button>
                <ul class="dropdown-menu">
                    ${this.config.actions.map(action => `
                        <li>
                            <a class="dropdown-item" id="${action.id}" href="${action.url(row)}">
                                ${action.icon ? `<i class="${action.icon}"></i>` : ''}
                                ${action.label}
                            </a>
                        </li>
                    `).join('')}
                </ul>
            </div>`;
    }

    bindEvents() {
        // Global filters
        this.config.filters.forEach(filter => {
            $(`#${filter.id}`).on('change', () => this.dataTable.ajax.reload());
        });

        // Column filters
        $(document).on('change keyup', '.column-filter, .filter-operator', () => {
            this.dataTable.ajax.reload();
        });

        // Reset filters
        $('.reset-filters').click(() => this.resetFilters());
    }

    resetFilters() {
        this.config.filters.forEach(filter => {
            const el = $(`#${filter.id}`);
            if (filter.type === 'multiselect') {
                el.val([]).selectpicker('refresh');
            } else {
                el.val('');
            }
        });

        $(`.column-filter`).val('');
        $(`.filter-operator`).val('=');
        this.dataTable.ajax.reload();
    }

    handleRowCreation(row, data) {
        if (this.config.onRowCreated) {
            this.config.onRowCreated(row, data);
        }
    }
}