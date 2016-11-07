(function ($) {
    jQuery(".faqwd_sotable_ul").sortable({
        update: function () {
            var order = [];
            $('#sortable').children().each(function (i) {
                order.push($(this).attr('id'));
            });

            jQuery.post(
                    ajaxurl, {
                        'action': 'save_categories_order',
                        'cats': order.join(','),
                        'page': page,
                        security: faqwd_admin.ajaxnonce
                    },
                    function (response) {
                        var result = jQuery.parseJSON(response);
                        if (jQuery('#faqwd_msg').length > 0) {
                            jQuery('#faqwd_msg').remove();
                        }
                        if (result.status == 'ok') {
                            $('#sortable').before($('<div id="faqwd_msg" class="faqwd-success">' + result.msg + '</div>')).delay(2000).queue(function (n) {
                                $('#faqwd_msg').fadeOut('slow').remove();
                                n();
                            });
                        } else {
                            $('#sortable').before($('<div id="faqwd_msg" class="faqwd-error">' + result.msg + '</div>'));
                        }


                    }
            );
        }
    });

    $(document).ready(function () {
        var search = window.location.search;
        var arr = search.split('post_type=');
        arr = arr[1];
        if (typeof arr != 'undefined' && arr.split('&')[0] == "faq_wd") {
            $('#the-list').sortable({
                'items': 'tr',
                'axis': 'y',
                'update': function (e, ui) {
                    var page = 1;
                    var url = window.location.search;
                    url = url.split('paged=');
                    if (url[1] != undefined) {
                        url = url[1].split('&');
                        page = url[0];
                    }

                    var order = [];
                    $('#the-list').children().each(function (i) {
                        var id = $(this).attr('id');
                        id = id.split('-');
                        order.push(id[1]);
                    });
                    $.post(ajaxurl, {
                        action: 'faqwd_sotable',
                        order: order.join(','),
                        page: page,
                        security: faqwd_admin.ajaxnonce
                    });
                }
            });

            $('table.tags #the-list').sortable({
                'items': 'tr',
                'axis': 'y',
                'update': function (e, ui) {
                    var order = [];
                    $('#the-list').children().each(function (i) {
                        var id = $(this).attr('id');
                        id = id.split('-');
                        order.push(id[1]);
                    });
                    var page = 1;
                    var url = window.location.search;
                    url = url.split('paged=');
                    if (url[1] != undefined) {
                        url = url[1].split('&');
                        page = url[0];
                    }
                    $.post(ajaxurl, {
                        action: 'faqwd_category_sotable',
                        order: order.join(','),
                        page: page,
                        security: faqwd_admin.ajaxnonce
                    });
                }
            });
        }

    });

    $('.uninstall_button').click(function () {
        if (confirm("You Are About To Uninstall FAQ WD From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.")) {
            $("#uninstall_form").submit();
        } else {

        }

    });

    var taxonomy = 'faq_category';
    $('#' + taxonomy + 'checklist li :checkbox, #' + taxonomy + 'checklist-pop :checkbox').on('click', function () {
        if ($(this).closest("#faq_category-pop").length == 0) {
            var t = $(this), c = t.is(':checked'), cat_id = t.attr('id').split('-'), id = cat_id[2];
        } else {
            var t = $(this), c = t.is(':checked'), cat_id = t.attr('id').split('-'), id = cat_id[3];
        }
        $('#in-' + taxonomy + '-' + id + ', #in-popular-' + taxonomy + '-' + id).prop('checked', c);
    });

    $('.faq_wd_delete_lang').submit(function () {
        return confirm('Delete Translation ?');
    });
}(jQuery));

 