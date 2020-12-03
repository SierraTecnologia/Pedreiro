// require('bootstrap');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');

jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

    alert('oi');
});

