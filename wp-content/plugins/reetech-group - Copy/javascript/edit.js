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
});