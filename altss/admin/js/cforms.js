jQuery(document).ready(function($) {
    $(".site-settings-cform-fields-select").click( function(){
        let fArea = $(this).data('area');
        if( cformFields ){
            $(".popup-container").css( 'width', '500px');
            $('#popup-form-wrapper').append(
                    '<div class="popup-cform-fields-form-wrap"><div>' + wp.i18n.__("Select the required fields for the form", "altss") + '</div><form></form></div>'
            );
            for( let key in cformFields ) {
                let chkd = '';
                let dchkd = '';
                if( $('#cform_ul_' + fArea + ' li').is('[data-key=' + key + ']') ) chkd = ' checked';
                if( $('#f' + fArea + '_cb_' + key + ':checked').is('[value=1]') ) dchkd = ' checked';
                $('#popup-form-wrapper form').append(
                        '<label for="cb-' + key + '" class="popup-cform-field-label">'+
                        '<input type="checkbox" id="cb-' + key + '" name="fields[]" value="' + key + '" data="' + dchkd + '"' + chkd + ' /> - ' 
                        + cformFields[key] + '</label>'
                );
            }
            $('#popup-form-wrapper form').append(
                '<div class="popup-cform-field-wrap"><input type="button" id="fset-btn" data-area="' + fArea + '" value="' + wp.i18n.__("Apply", "altss") + '" /></div>'
        );
        }
        else{
            console.log('array truble');
        }

        
        $("#popup_show_bg").show();
    });

    $("body").on( "click", "#fset-btn", function(){
        let area = $(this).data('area');
        let ulId = '#cform_ul_' + area;
        $(ulId).html('');
        var selectedCheckBoxes = $('.popup-cform-fields-form-wrap input[type=checkbox]:checked');
        selectedCheckBoxes.each(function(i, item){
            $(ulId).append(
                    '<li class="form-area-field" data-key="' + item.value + '">' + 
                    '<input type="hidden" name="altss_settings_cforms_options_fields_' + area + '[]" value="' + item.value + '"/>' + 
                    '<div><label>' + cformFields[item.value] + '</label>' +
                    '<input type="checkbox" id="f' + area + '_cb_' + item.value + '" name="altss_settings_cforms_options_reqfields_' + area + '[' + item.value + ']" value="1" title="' + wp.i18n.__('make required', 'altss') + '"' + item.attributes.data.value + ' /></div></li>'
            );
            console.dir(item);
            console.log(item.attributes.data.value);
            console.log(item.value);
        });

        $("#popup_show_bg").hide();
        $('#popup-form-wrapper').html('');
    });


    $(".fs-link").click( function(){
        let id = $(this).data('id');
        let p = $(this).data('p');
        let nonce = $(this).data('nonce');
        $(".popup-container").css( {'width': '100%', 'max-width': '800px', 'min-height': '500px'} );
        $('.popup-container').addClass('loading');
        $("#popup_show_bg").show();

        var data = {
            action: 'view_cfs_record',
            _wpnonce: nonce,
            id: id,
            p: p
        }
        var url = '/wp-admin/admin-ajax.php';
        jQuery.post( url, data, function( response ) {
            if( response){
                $('.popup-container').removeClass('loading');
                $("#popup-form-wrapper").html( response );
            }

        });
    });


    $(".popup-container").on( "click", "#view-cfs-record-actions-delite-span", function(){
        let id = $(this).data('id');
        let p = $(this).data('p');
        let nonce = $(this).data('nonce');

        var url = '/wp-admin/admin-post.php';

        if( confirm( wp.i18n.__("Are you sure you want to delete this entry?", "altss") ) ){
            let form = document.createElement('form');
            let actionid = 'cfs_record_remove';
            form.action = url;
            form.method = 'POST';
            form.innerHTML = '<input name="action" value="' + actionid + '">' +
                            '<input name="_wpnonce" value="' + nonce + '">' +
                            '<input name="id" value="' + id + '">' +
                            '<input name="p" value="' + p + '">';
            document.body.append(form);

            form.submit();
        }
            else return;
    });


    $("body").on( "click", ".popup-close-button", function(){
        $("#popup_show_bg").hide();
        $('#popup-form-wrapper').html('');
    });

    $("#fields-reset").click( function(){
        if( confirm( "Are you sure you want to reset settings?" ) ){
            $('input[type="text"]').each( function( index, item ){
                item.value = $(item).data("dv");
            });
        }
    });

    $('.site-settings-cform-all-items-over').on( 'click', '.site-settings-cform-item-title', function(){
        var fitmst = $(this);
        var tbtn = $(this).children( '.cfitms-toggle' );
        $('#cform-item-' + tbtn.data('key'))
        .slideToggle('slow', function(){
            if( $(this).is(":hidden") ) {
                fitmst.removeClass('fitmst-active');
                fitmst.attr({'title': wp.i18n.__('expand', 'altss')});
                tbtn.removeClass("dashicons-remove").addClass("dashicons-insert");
            }
            else {
                fitmst.addClass('fitmst-active');
                fitmst.attr({'title': wp.i18n.__('collapse', 'altss')});
                tbtn.removeClass("dashicons-insert").addClass("dashicons-remove");
            }
        });
    });
    $(".cfitms-sliddown-button").click(function(){
        $('.site-settings-cform-all-items-over .site-settings-cform-item-wrapp').slideDown("slow", function(){
            $('.site-settings-cform-all-items-over .cfitms-toggle').removeClass("dashicons-insert").addClass("dashicons-remove");
            $('.site-settings-cform-all-items-over .site-settings-cform-item-title').attr({'title':wp.i18n.__('collapse', 'altss')});
            $('.site-settings-cform-all-items-over .site-settings-cform-item-title').addClass('fitmst-active');
        });
    });
    $(".cfitms-slidup-button").click(function(){
        $('.site-settings-cform-all-items-over .site-settings-cform-item-wrapp').slideUp("slow", function(){
            $('.site-settings-cform-all-items-over .cfitms-toggle').removeClass("dashicons-remove").addClass("dashicons-insert");
            $('.site-settings-cform-all-items-over .site-settings-cform-item-title').attr({'title':wp.i18n.__('expand', 'altss')});
            $('.site-settings-cform-all-items-over .site-settings-cform-item-title').removeClass('fitmst-active');
        });
    });


});
 