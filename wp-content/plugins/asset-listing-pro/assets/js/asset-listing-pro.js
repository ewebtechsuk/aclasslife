(function ($) {
    'use strict';

    function toggleAssetTypeFields($form) {
        var $select = $form.find('#acl_asset_type');
        if (!$select.length) {
            return;
        }

        var selected = $select.find('option:selected').data('asset-type') || '';
        selected = selected.toString().toLowerCase();

        $form.find('.alp-asset-fields').each(function () {
            var $group = $(this);
            var isActive = selected && $group.data('asset-type') === selected;

            $group.toggleClass('is-active', isActive);

            $group.find('input, select, textarea').each(function () {
                var $input = $(this);
                var shouldRequire = $input.data('required') === 1 || $input.data('required') === '1';

                $input.prop('disabled', !isActive);

                if (shouldRequire) {
                    $input.prop('required', !!isActive);
                }
            });
        });
    }

    function initAssetTypeForms() {
        $('.alp-submission-form').each(function () {
            var $form = $(this);

            $form.addClass('alp-has-js');
            toggleAssetTypeFields($form);

            $form.on('change', '#acl_asset_type', function () {
                toggleAssetTypeFields($form);
            });
        });
    }

    function initPriceTooltip() {
        $(document).on('input', '#acl_listing_price', function () {
            var value = parseFloat($(this).val());
            if (!isNaN(value) && window.alpSettings && window.alpSettings.currencySymbol) {
                $(this).attr('title', window.alpSettings.currencySymbol + value.toFixed(2));
            }
        });
    }

    $(document).ready(function () {
        initAssetTypeForms();
        initPriceTooltip();
    });
})(jQuery);
