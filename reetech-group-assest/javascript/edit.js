/* filepath: c:\xampp\htdocs\wordpress1\wp-content\plugins\reetech-group\templates\edit.js */
function showFeedback(status) {
    var msg;

    if (status == 'success') {
        msg = 'Your default note was successfully updated!';
    } else {
        msg = 'There was an error updating your default message. Please try again later or contact support.';
    }

    $('#modalForm').hide();
    $('#modalFeedback').find('.feedbackMessage').text(msg);
    $('#modalFeedback').show();
}

$(function () {
    $('#defaultNote').on('show.bs.modal', function (e) {
        $('#modalForm').find('.btn-spinner').prop('disabled', false).removeClass('btn-spinner');
        $('#modalForm').show();
        $('#modalFeedback').hide();
    });

    var request;

    $('#btnSaveDefaultNote').click(function () {
        $(this).prop('disabled', true).addClass('btn-spinner');
        setTimeout(function () {
            $(this).prop('disabled', false).removeClass('btn-spinner');
        }, 6000);

        var notes = $('#default_note').val();
        var newdata = "type=invoices&notes=" + encodeURIComponent(notes);

        if (document.getElementById('copy_to_current_document').checked) {
            document.getElementById('doc_notes').value = notes;
        }

        if (request) {
            request.abort();
        }

        request = $.ajax({
            url: "/app/invoices/defaultnotes",
            type: "post",
            data: newdata,
        });

        request.done(function (response, textStatus, jqXHR) {
            showFeedback('success');
        });

        request.fail(function (jqXHR, textStatus, errorThrown) {
            showFeedback('error');
        });

        $('#default_note').each(growTextarea);
    });

    function collectInvoiceData() {
        // Basic invoice information
        const invoiceData = {
            from: {
                name: $('#from_name').val(),
                address: $('#from_address').val()
            },
            to: {
                id: $('#to_name').val(),
                name: $('#to_name option:selected').text(),
                address: $('#to_address').val()
            },
            logo: $('#customization_logoFilename').val(),
            heading: $('#doc_heading').val(),
            invoice_number: $('#doc_number').val(),
            po_number: $('#po_number').val(),
            invoice_date: $('#dateStart').val(),
            due_date: $('#dateEnd').val(),
            items: [],
            notes: $('#doc_notes').val(),
            subtotal: parseFloat($('#sum_subtotal').val().replace('$', '') || 0),
            total: parseFloat($('#sum_total').val().replace('$', '') || 0),
            amount_paid: parseFloat($('#amount_paid').val().replace('$', '') || 0),
            balance_due: parseFloat($('#balance_due').val().replace(/[^0-9.-]+/g, '') || 0)
        };
    
        // Collect line items
        $('#dataTable tr.line').each(function() {
            const row = $(this);
            const item = {
                type: row.find('select[name="item[]"]').val(),
                description: row.find('textarea[name="description[]"]').val(),
                unit_price: parseFloat(row.find('input[name="unit_price[]"]').val() || 0),
                quantity: parseFloat(row.find('input[name="qty[]"]').val() || 0),
                tax_rate: row.find('.jtaxTotal').text().trim(),
                tax_amount: parseFloat(row.find('input[name="tax_total[]"]').val() || 0),
                amount: parseFloat(row.find('input[name="total[]"]').val() || 0),
                expense_id: row.find('input[name="expense_id[]"]').val()
            };
            
            invoiceData.items.push(item);
        });
    
        return invoiceData;
    }

    function submitInvoiceToWordPress() {
        // Collect the data
        const invoiceData = collectInvoiceData();
        
        // Prepare the AJAX request
        $.ajax({
            url: '/wp-json/your-plugin/v1/invoices', // Replace with your actual endpoint
            type: 'POST',
            data: JSON.stringify(invoiceData),
            contentType: 'application/json',
            beforeSend: function(xhr) {
                // Add WordPress nonce for security if needed
                xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            },
            success: function(response) {
                console.log('Invoice submitted successfully:', response);
                alert('Invoice saved successfully!');
            },
            error: function(xhr, status, error) {
                console.error('Error submitting invoice:', error);
                alert('Error saving invoice: ' + error);
            }
        });
    }

});