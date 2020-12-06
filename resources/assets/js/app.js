require('./includes/redactor.min.js');

require('./commons/app');

jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});

