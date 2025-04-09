/* filepath: c:\xampp\htdocs\wordpress1\wp-content\plugins\reetech-group\templates\view.js */
const account_upgrade_url = '';

$(document).ready(function () {
    // Example: Add functionality for the "Print" button
    $('.btnPrint').on('click', function () {
        window.print();
    });

    // Example: Add functionality for the "More Actions" dropdown
    $('select[name="more_actions"]').on('change', function () {
        if (this.value !== '') {
            $('#more_actions').submit();
        }
    });
});