function initIframeModal(buttonId) {
    $(document).ready(function () {
        // Fully Custom Modal
        $('#' + buttonId).iframeModal({
            modalId: 'customModal',
            title: function (url) {
                return 'Custom: ' + new URL(url, window.location.href).searchParams.get('id');
            },
            size: 'modal-lg',
            header: false,
            footer: false,
            closeButton: true,
            fullHeight: true,
            footerButtons: [
                { text: 'Done', class: 'btn-success', action: 'customAction' },
                { text: 'Close', class: 'btn-danger', action: 'close' },
            ],
            footerButtonActions: {
                customAction: function () {
                    alert('Custom action triggered!');
                },
            },
            beforeClose: function () {
                return confirm('Are you sure you want to close?');
            },
        });
    });
}
