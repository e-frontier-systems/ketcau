;(function($, window, document, undefined) {
    var updateUpDown = function (sortable) {
        if (sortable instanceof $) {
            sortable = sortable.get(0);
        }
        $('div:not(.ui-sortable-helper)', sortable)
            .removeClass('first')
            .filter('.first').addClass('.first').end()
            .children('input.target-id').val(sortable.id.replace('position_', ''));
        $(sortable)
            .find('input.block-row').each(function (i) {
            $(this).val(i);
        });
    };

    var sortableUpdate = function (e, ui) {
        updateUpDown(this);
        if (ui.sender) {
            updateUpDown(ui.sender[0]);
        }
    };
    window.updateUpDown = updateUpDown;

    $(document).ready(function () {
        var $els = $(window.els.toString());

        $els.each(function () {
            updateUpDown(this);
        });

        $els.sortable({
            items: '> div.block',
            cursor: 'move',
            appendTo: 'body',
            placeholder: 'placeholder',
            connectWith: window.els,
            start: function (e, ui) {
                ui.helper.css("width", ui.item.width());
            },
            stop: function (e, ui) {
                if ($(this).children('.block').length <= 0) {
                    $(this).append($('#target-placeholder').html());
                }
                if (ui.item.parent().children('.block').length > 0) {
                    ui.item.parent().children('.target-placeholder').remove();
                }
            },
            update: sortableUpdate,
        });
    });
})(jQuery, window, document);