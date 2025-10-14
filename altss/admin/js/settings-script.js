var cpOptions = {
	defaultColor: false,
	change: function(event, ui){ },
	clear: function(){ },
	hide: true,
	palettes: true
};

jQuery(document).ready(function($) {
    $( 'input[name="altss_settings_options[map_display_type]"]' ).click(function(){
        if( 'shortcode' === $(this).val() ){
            $( '.map-shortcode-field' ).show();
            $( '.map-static-image' ).hide();
        }
        else {
            $( '.map-shortcode-field' ).hide();
            $( '.map-static-image' ).show();
        }
    });
    $( '.options-analytics .onoffswitch-checkbox, .options-seo-and-meta-over .onoffswitch-checkbox' ).change(function(){
        var item = $(this).data('item');
        var isChecked = $(this).is(':checked');
        if( isChecked ){
            $( '#' + item + '-area' ).slideDown();
        }
        else {
            $( '#' + item + '-area' ).slideUp();
        }
    });
    $( '.cookie-items-area .onoffswitch-checkbox' ).change(function(){
        var item = $(this).data('item');
        var isChecked = $(this).is(':checked');
        if( isChecked ){
            $( '#' + item + '_ctg_note_text_over' ).slideDown();
        }
        else {
            $( '#' + item + '_ctg_note_text_over' ).slideUp();
        }
    });
    $( '.set-to-default-value' ).click(function(){
        var thisEL = $(this);
        var ddParent = thisEL.parent().parent();
        if(ddParent.find('input').hasClass('iris_color')) {
            ddParent.find('input.iris_color').iris('color', thisEL.data('def'));
        }
        else if(ddParent.find('select').length > 0) {
            ddParent.find('select').find("option[value='"+thisEL.data('def')+"']").prop('selected',true);
        }
        else if(ddParent.find('textarea').length > 0) {
            if('tinymce' === thisEL.data('type')) tinymce.get(ddParent.find('textarea').attr('id')).setContent(ddParent.find('div.section-hint').html());
            else ddParent.find('textarea').val(ddParent.find('div.section-hint').html().trim());
        }
        else {
            ddParent.find('input').val('').attr('placeholder', thisEL.data('def'));
        }
    });
    $( '.restore-to-default-btn' ).click(function(){
        if(confirm(ssData.confirmResetColors)){
            var defVal;
            var btnData = $(this).data('item');
            $('.' + btnData + '-set-item').each((i,el) => {
                $(el).find('dd').each((a,e) => {
                    defVal = $(e).find('.section-hint strong').text();
                    $(e).find('input.iris_color').iris('color', defVal);
                });
            });
        }
    });
    $('.iris_color').wpColorPicker( cpOptions );
});
