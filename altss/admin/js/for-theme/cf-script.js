let fadeOutTimeOut;


jQuery( document ).ready(function($){
    $(".popup-open-button").click( function(){
        $("#popup_show_bg").show();
    });
    
    $("body").on( "click", ".popup-close-button", function(){
        clearTimeout( fadeOutTimeOut );
        $("#popup_show_bg").hide();
     });



 
     // Submitting a form
     $("body").on( "click", ".cform_button", function(){
        var submit_btn = $(this);
        var form_parent = submit_btn.parents('form').parent();
        var cf_id = submit_btn.data('fid');
        $('<input type="hidden" name="action" value="cform_action" />').appendTo('#' + cf_id);
        $('<input type="hidden" name="nonce" value="' + cfajax.nonce + '" />').appendTo('#' + cf_id);
        var s_cform = $( '#' + cf_id );
        if( $('.cf-field-error-txt') ){
            $('.cf-field-error-txt').remove();
            $('input, textarea').removeClass('cf-field-error');
        }
        
        var data = s_cform.serialize();

        submit_btn.val( wp.i18n.__('Sending...', 'altss') ).addClass('btn-color');

        $.post( cfajax.url, data, function( response ) {
            if( response.success){
                form_parent.html('<p class="cform-success-mess">' + response.data + '</p>');
                fadeOutTimeOut = setTimeout( function(){
                    $("#popup_show_bg").fadeOut( 2000 );
                }, 5000 );        
            }
            else {
                if( "mail_error" in response.data ){
                    form_parent.html('<p class="cform-error-mess">' + response.data.mail_error + '</p>');
                    fadeOutTimeOut = setTimeout( function(){
                        $("#popup_show_bg").fadeOut( 2000 );
                    }, 7000 );        
                }
                else{
                    $.each( response.data, function (key, val) {
                        s_cform.find('[name="cfdata[' + key + ']"]').addClass('cf-field-error');
                        s_cform.find('[name="cfdata[' + key + ']"]').closest('p').before('<p class="cf-field-error-txt">' + val + '</p>');
                    });
                    submit_btn.val( wp.i18n.__('Again', 'altss') ).removeClass('btn-color');
                }
            }

        });
     });
     
 
});