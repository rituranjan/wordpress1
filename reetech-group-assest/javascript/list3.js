var Customeroptions = [
    { value: 0, label: 'All Customers' },
    { value: 1, label: 'Customer 1' },
    { value: 2, label: 'Customer 2' },
    // ... rest of customer options ...
];


// Example initialization with column filters
const invoicesTable = new GenericDataTable({
    ajax: {
        url: 'http://localhost/wordpress1/wp-json/reetech-group/v1/invoices-summary'
    },
    filters: [
        {
            id: 'customerFilter',
            type: 'multiselect',
            label: 'Customers',
            options: Customeroptions,
            source: 'http://localhost/wordpress1/wp-json/reetech-group/v1/customers'
        },
        {
            id: 'fromDate',
            type: 'date',
            label: 'From Date'
        },
        {
            id: 'toDate',
            type: 'date',
            label: 'To Date'
        }
    ],
    columns: [
        { 
            data: 'invoice_number',
            title: 'Invoice #',
            type: 'link',
            href: (row) => `invoice.html?id=${row.invoice_number}`,
           // filter: { type: 'string', operators: ['contains', 'starts'] }
        },
        { 
            data: 'customer',
            title: 'Customer',
            type: 'link',
            href: (row) => `customer.html?id=${row.customer_id}`
        },
        { 
            data: 'date', 
            title: 'Date',
            render: (data) => data && data !== '0000-00-00' ? 
                new Date(data).toLocaleDateString() : 'N/A',
           // filter: { type: 'date' }
        },
        { 
            data: 'total', 
            title: 'Total',
            render: $.fn.dataTable.render.number(',', '.', 2, '$'),
            filter: { type: 'number' }
        },
        { 
            data: 'balance', 
            title: 'Balance',
            render: (data, type) => {
                if (type === 'display') {
                    const numericValue = parseFloat(data);
                    const color = numericValue > 0 ? 'danger' : 'success';
                    return `<span class="badge bg-${color}">$${Math.abs(numericValue).toFixed(2)}</span>`;
                }
                return data;
            },
            filter: { type: 'number' }
        },
        { 
            data: 'status', 
            title: 'Status',
            render: (data) => {
                const statusMap = { paid: 'success', pending: 'warning', overdue: 'danger' };
                return `<span class="badge bg-${statusMap[data] || 'secondary'}">${data}</span>`;
            }
        },
        { 
            data: null,
            title: 'Actions',
            type: 'actions',
            orderable: false
        }
    ],
    actions: [
        {
            label: 'Edit',
            url: (row) => `edit.html?id=${row.invoice_number}`,
            icon: 'fas fa-edit'
        },
        {
            label: 'View',
            url: (row) => `view.html?id=${row.invoice_number}`,
            icon: 'fas fa-eye'
        }
    ]
});