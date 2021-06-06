
require('./cms');
require('./forms');
require('./modules');
require('./dropzone-custom');
require('./slugify');

require('./multilingual');
window.helpers = require('./helpers.js');


jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});


$(document).ready(function () {


    $('.match-height').matchHeight();

    // $('.datatable').DataTable({
    //     "dom": '<"top"fl<"clear">>rt<"bottom"ip<"clear">>'
    // });

    // $('.datepicker').datetimepicker();

    // Save shortcut
    $(document).keydown(function (e) {
        if ((e.metaKey || e.ctrlKey) && e.keyCode == 83) { /*ctrl+s or command+s*/
            $(".btn.save").click();
            e.preventDefault();
            return false;
        }
    });

    /********** MARKDOWN EDITOR **********/

    $('textarea.easymde').each(function () {
        var easymde = new EasyMDE({
            element: this
        });
        easymde.render();
    });

    /********** END MARKDOWN EDITOR **********/

});
