// iframe-modal.js - Enhanced with right-aligned close and full height
(function($) {
    $.fn.iframeModal = function(options) {
        const defaults = {
            modalId: 'genericIframeModal',
            title: 'Modal Title',
            size: 'modal-lg',
            header: false,
            footer: false,
            closeButton: true,
            closeButtonRight: true, // New option for right-aligned close
            fullHeight: true,       // New option for full height
            footerButtons: [
                { text: 'Close', class: 'btn-secondary', action: 'close' }
            ],
            minHeight: 300,
            onShow: null,
            onHide: null
        };

        const settings = $.extend({}, defaults, options);

        // Create modal if it doesn't exist
        if (!$(`#${settings.modalId}`).length) {
            let modalHTML = `
                <div class="modal fade" id="${settings.modalId}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog ${settings.size} ${settings.fullHeight ? 'h-100 m-0' : ''}">
                        <div class="modal-content ${settings.fullHeight ? 'h-100' : ''}">
                            ${settings.header ? `
                            <div class="modal-header ${settings.closeButtonRight ? 'd-flex justify-content-between align-items-center' : ''}">
                                <h5 class="modal-title m-0">${settings.title}</h5>
                                ${settings.closeButton ? 
                                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' : ''}
                            </div>
                            ` : ''}
                            <div class="modal-body p-0 d-flex flex-column">
                             <div align='right' style='margin-right:286px;' class="overlay">
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                                <iframe class="iframe-content w-100 border-0 flex-grow-1" 
                                    style="min-height: ${settings.minHeight}px;"></iframe>
                            </div>
                            ${settings.footer ? `
                            <div class="modal-footer">
                                ${settings.footerButtons.map(btn => `
                                    <button type="button" class="btn ${btn.class}" 
                                        data-action="${btn.action}">${btn.text}</button>
                                `).join('')}
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modalHTML);

            // Event listeners
            $(`#${settings.modalId} .modal-footer button`).on('click', function() {
                const action = $(this).data('action');
                if (action === 'close') {
                    bootstrap.Modal.getInstance($(`#${settings.modalId}`)).hide();
                }
            });
        }

        // Handle click on elements
        this.on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const modalEl = $(`#${settings.modalId}`)[0];
            const modal = new bootstrap.Modal(modalEl);
            const iframe = $(`#${settings.modalId} .iframe-content`);

            // Set iframe source
            iframe.attr('src', url);

            // Adjust modal positioning for full height
            if (settings.fullHeight) {
                $(modalEl).find('.modal-dialog').css({
                    'max-height': '100vh',
                    'margin': '0 auto'
                });
                
                $(modalEl).find('.modal-content').css({
                    'height': '100vh',
                    'border-radius': '0'
                });
            }

            // Show modal
            modal.show();

            // Event handlers
            modalEl.addEventListener('shown.bs.modal', function() {
                if (settings.onShow) settings.onShow(iframe);
            });

            modalEl.addEventListener('hidden.bs.modal', function() {
                iframe.attr('src', 'about:blank');
                if (settings.onHide) settings.onHide();
            });
        });

        return this;
    };
})(jQuery);

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
