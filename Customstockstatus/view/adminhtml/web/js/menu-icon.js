define(['jquery'], function($) {
    return function(target) {
        $(document).on('rendered', function() {
            const item = $('[data-ui-id="menu-santi-customstockstatus-config"] > .item-content');
            if (item.length && !item.find('.superman-icon').length) {
                item.prepend('<span class="superman-icon" style="font-size:16px; font-weight:bold; color:#d32f2f; margin-right:5px;">S</span>');
            }
        });
        return target;
    };
});