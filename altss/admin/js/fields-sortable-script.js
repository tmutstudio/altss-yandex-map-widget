

jQuery(document).ready(function($) {
    $('.site-settings-cform-fields-area').sortable({
            stop: function(){
            sort = [];
            i = 1;
            $('.site-settings-cform-fields-area li').each(function(){
                $(this).find("span").text(i);
                sort.push($(this).data('id'));
                i++;
            });
            console.log(sort);
        }
    });
});
