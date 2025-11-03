(function ($) {
    'use strict';

    $(document).on('input', '#acl_listing_price', function () {
        var value = parseFloat($(this).val());
        if (!isNaN(value) && window.alpSettings && window.alpSettings.currencySymbol) {
            $(this).attr('title', window.alpSettings.currencySymbol + value.toFixed(2));
        }
    });
})(jQuery);
