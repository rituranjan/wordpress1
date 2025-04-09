// /* filepath: c:\xampp\htdocs\wordpress1\wp-content\plugins\reetech-group\templates\list.js */
// $(document).ready(function () {
//     // Fully Custom Modal
//     $('#editBtn').iframeModal({
//         modalId: 'customModal',
//         title: function (url) {
//             return 'Custom: ' + new URL(url, window.location.href).searchParams.get('id');
//         },
//         size: 'modal-lg',
//         header: false,
//         footer: false,
//         closeButton: true,
//         fullHeight: true,
//         footerButtons: [
//             { text: 'Done', class: 'btn-success', action: 'customAction' },
//             { text: 'Close', class: 'btn-danger', action: 'close' },
//         ],
//         footerButtonActions: {
//             customAction: function () {
//                 alert('Custom action triggered!');
//             },
//         },
//         beforeClose: function () {
//             return confirm('Are you sure you want to close?');
//         },
//     });
// });


initIframeModal('editBtn');
initIframeModal('viewBtn');
initIframeModal('newBtn');
    $(document).ready(function () {
    $('#confirmDelete').on('hidden.bs.modal', function (e) {
        $('#delete').val('');
    });

    $('.deleteDialog').on('click', function (e) {
        const obj_id = e.currentTarget.dataset.value;
        const obj_status = e.currentTarget.dataset.status;

        $('#delete').val(obj_id);

        if (['Paid', 'Partial'].includes(obj_status)) {
            $('#confirmDelete span.warningPayments').show();
        } else {
            $('#confirmDelete span.warningPayments').hide();
        }

        $('#confirmDelete').modal('show');
    });
});