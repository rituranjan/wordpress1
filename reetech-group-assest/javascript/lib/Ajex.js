/**
 * Enhanced WordPress API Handler with Spinner and Toast Notifications
 * @param {string} method - HTTP method
 * @param {string} endpoint - API endpoint
 * @param {Object} [data={}] - Request data
 * @param {Object} [options={}] - Additional options
 * @param {boolean} [options.showSpinner=true] - Show/hide spinner
 * @param {Object} [options.toastOptions] - Toast configuration
 * @param {function} [successCallback] - Success handler
 * @param {function} [errorCallback] - Error handler
 */
function wpApiRequest(method, endpoint, data = {}, options = {}, successCallback, errorCallback) {
    const defaults = {
        showSpinner: true,
        toastOptions: null
    };
    endpoint='http://localhost/wordpress1/wp-json/reetech-group'+endpoint;
    
    const settings = {...defaults, ...options};
    const $spinner = $('#apiSpinner');
    const toastQueue = [];

    // Show spinner
    if(settings.showSpinner) {
        $spinner.removeClass('d-none').addClass('d-block');
    }

    // Create toast notification
    function showToast(type = 'info', message = '', autoHide = true) {
        const toastId = `toast-${Date.now()}`;
        const iconMap = {
            success: 'check-circle',
            error: 'exclamation-triangle',
            warning: 'exclamation-circle',
            info: 'info-circle',
            loading: 'arrow-repeat'
        };

        const toastHtml = `
            <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${type} text-white">
                    <i class="bi bi-${iconMap[type] || 'info-circle'} me-2"></i>
                    <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;

        $('#apiToastContainer').append(toastHtml);
        const toastEl = document.getElementById(toastId);
        // const toast = new bootstrap.Toast(toastEl, {
        //     autohide: autoHide,
        //     delay: type === 'loading' ? false : 3000
        // });
        const toast = new bootstrap.Toast(toastEl, {
            autohide: autoHide,
            delay:  3000
        });
        
        toast.show();
        return toast;
    }

    // Handle toast queue
    function processToastQueue() {
        if(toastQueue.length > 0) {
            const {type, message} = toastQueue.shift();
            showToast(type, message);
            processToastQueue();
        }
    }

    // Initial toast for long-running requests
    let loadingToast;
    if(settings.toastOptions?.wait) {
        loadingToast = showToast('loading', settings.toastOptions.wait, false);
    }

    // AJAX configuration
    jQuery.ajax({
        url:  endpoint,
        method: method,
        headers: {
            //'X-WP-Nonce': wpApiSettings.nonce,
            'Content-Type': 'application/json'
        },
        data: method === 'GET' ? data : JSON.stringify(data),
        dataType: 'json',
        xhrFields: {withCredentials: true},
        
        beforeSend: function() {
            if(settings.toastOptions?.wait) {
                loadingToast.show();
            }
        },
        
        success: function(response) {
            if(settings.toastOptions?.success) {
                toastQueue.push({
                    type: 'success',
                    message: response.message || settings.toastOptions.success
                });
            }
            
            if(typeof successCallback === 'function') {
                successCallback(response);
            }
        },
        
        error: function(xhr) {

            if(xhr.status === 200) {
                toastQueue.push({
                    type: 'success',
                    message: settings.toastOptions.success
                });
            }else{

            const errorMessage = xhr.responseJSON?.message || 'An error occurred';
            toastQueue.push({
                type: 'error',
                message: errorMessage || settings.toastOptions?.error 
            });
            
            if(typeof errorCallback === 'function') {
                errorCallback(xhr);
            }
            
            if(xhr.status === 401) {
                window.location.href = '/wp-login.php?redirect_to=' + encodeURIComponent(window.location.href);
            }}
        },
        
        complete: function() {
            // Hide spinner
            if(settings.showSpinner) {
                $spinner.removeClass('d-block').addClass('d-none');
            }
            
            // Hide loading toast
            if(loadingToast) {
                loadingToast.hide();
            }
            
            // Process toast queue
            processToastQueue();
            
            // Custom complete toast
            if(settings.toastOptions?.complete) {
                toastQueue.push({
                    type: 'info',
                    message: settings.toastOptions.complete
                });
            }
        }
    });
}


// // Example Usage
// jQuery(document).ready(function($) {
//     // Example GET request with all notifications
//     wpApiRequest(
//         'GET', 
//         'items/v1/all', 
//         {},
//         {
//             showSpinner: true,
//             toastOptions: {
//                 wait: 'Loading items...',
//                 success: 'Items loaded successfully!',
//                 error: 'Failed to load items!',
//                 complete: 'Request completed'
//             }
//         },
//         function(response) {
//             console.log('Items:', response.data);
//         },
//         function(xhr) {
//             console.error('Error:', xhr);
//         }
//     );

//     // Minimal GET request with just spinner
//     wpApiRequest(
//         'GET',
//         'items/v1/all',
//         {},
//         {showSpinner: true}
//     );

//     // Custom notification only
//     wpApiRequest(
//         'POST',
//         'items/v1/create',
//         {item_value: 'New'},
//         {
//             toastOptions: {
//                 success: 'Item created successfully!',
//                 error: 'Creation failed!'
//             }
//         }
//     );
// });

// <!-- Add these elements to your HTML -->
// <div id="apiSpinner" class="d-none position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
//     <div class="spinner-border text-primary" role="status">
//         <span class="visually-hidden">Loading...</span>
//     </div>
// </div>

// <div id="apiToastContainer" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;"></div>