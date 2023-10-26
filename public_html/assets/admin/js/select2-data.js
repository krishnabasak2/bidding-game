/* Select2 Init*/
// "use strict";
// $(".select2").select2();
// $("#input_tags").select2({
//     tags: true,
//     tokenSeparators: [',', ' ']
// });



(function ($) {
    'use strict';

    if ($(".js-example-basic-single").length) {
        $(".js-example-basic-single").select2();
    }
    if ($(".js-example-basic-multiple").length) {
        $(".js-example-basic-multiple").select2();
    }
})(jQuery);