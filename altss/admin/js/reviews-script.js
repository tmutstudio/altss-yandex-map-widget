
jQuery(document).ready(function($) {
    $( "span.public span" ).click(function(){
        let slink = $(this);
        let act = slink.data('act');
        let id = slink.data('id');
        let nonce = slink.data('nonce');
        let inner = [];
        let classes1 = [];
        let classes2 = [];
       inner['hide'] = ['show', wp.i18n.__( 'Publish a review', 'altss' ), wp.i18n.__( 'hidden', 'altss' )];
        inner['show'] = ['hide', wp.i18n.__( 'Hide review', 'altss' ), wp.i18n.__( 'published', 'altss' )];
        classes1['show'] = 'color-green';
        classes1['hide'] = 'color-brown';
        classes2['show'] = 'color-brown';
        classes2['hide'] = 'color-green';
        $.ajax(
            {
                url: wp_admin_url + 'admin-ajax.php',
                type: 'POST',
                data: {
                        action: 'review-public',
                        _wpnonce: nonce,
                        act: act,
                        id: id,
	                },
                error: function(jqXHR, exception)
                {
                    $('#ajax_message').fadeIn(400).html("<span style=\"color:red\">" + wp.i18n.__( 'Error!', 'altss' ) + "</span>").fadeOut(2000);
                    console.log(jqXHR.status);
                },
                success: function()
                {
                    console.log(act+':'+slink.html());
                    slink.data('act', inner[act][0]);
                    slink.html(inner[act][1]).removeClass(classes1[act]).addClass(classes2[act]);
                    $('#review-status-'+id).html(inner[act][2]).removeClass(classes2[act]).addClass(classes1[act]);
                    if( 'hide' === act ){
                        $('#optional-upload-'+id).addClass('nopublic-bg');
                    }
                    else{
                        $('#optional-upload-'+id).removeClass('nopublic-bg');
                    }
                    $('#ajax_message').fadeIn(400).html("<span style=\"color:green\">" + wp.i18n.__( 'Information updated!', 'altss' ) + "</span>").fadeOut(2000);
                }
            });

        
        
    });
    var shorttext = [];
    $( ".morespan" ).click(function(){
        let morespan = $(this);
        let txtover = morespan.parent('.review-row-title-text');
        let txtdiv = morespan.parent('.review-row-title-text').children('.textdiv');
        let id = txtover.data('id');
        let fulltext = txtover.data('ftext');
        let more = morespan.data('more');

        if( 1 == more ){
            txtover.hide();
            shorttext[id] = txtdiv.html();
            txtdiv.html(fulltext);
            morespan.html( wp.i18n.__( 'collapse text', 'altss' ) );
            morespan.data('more', 0);
            txtover.slideDown('slow');
        }
        else{
            txtover.slideUp(500, "linear", function(){
                txtdiv.html(shorttext[id]);
                txtover.show();
            });
            
            morespan.data('more', 1);
            morespan.html(' . . . ' + wp.i18n.__( 'expand text', 'altss' ) );
        }
    });
    $( ".trash span, .restore span, .delete span" ).click(function(){
        let form = document.createElement('form');
        let act = $(this).data('act');
        let id = $(this).data('id');
        let nonce = $(this).data('nonce');
        form.action = wp_admin_url + "admin-post.php";
        form.method = 'POST';

        form.innerHTML = '<input name="action" value="review_trash_restore">' +
                            '<input name="id" value="' + id + '">' +
                            '<input name="act" value="' + act + '">' +
                            '<input name="_wpnonce" value="' + nonce + '">' +
                            '<input name="url" value="' + altss_current_url + '">';
        document.body.append(form);
        if( 'trash' === act || 'restore' === act ) {
            form.submit();
        }
        else if( 'delete' === act ) {
            if( confirm( "Are you sure you want to delete this review?" ) ) form.submit();
        } 
        
    });
});
