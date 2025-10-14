var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

function formbody(actionurl, redirect, mess ){
    mess = mess !== 'undefined' ?  mess : '';
    let formcode = '    <form action="'+actionurl+'" name="review-form" class="reviews-review-form reviews-form" method="post" enctype="multipart/form-data" novalidate="">'+"\n"+
'        <input name="action" type="hidden" value="altss_reviews">'+"\n"+
'        <input name="site-reviews[_referer]" type="hidden" value="'+redirect+'">'+"\n"+
'        <input id="site-reviews-rating" name="site-reviews[rating]" type="hidden" value="--">'+"\n"+
'        <div class="reviews-field reviews-field-rating" data-field="rating">'+"\n"+
'            <label class="reviews-label reviews-label-rating" for="site-reviews-rating"><span>' + wp.i18n.__('Please rate', 'altss') + '</span></label>'+"\n"+
'            <span class="mh-star-rating mh-star-rating--ltr" data-star-rating="">'+"\n"+
'                <span class="mh-star-rating--stars">'+"\n"+
'                    <span data-index="0" data-value="1" class="reviews-star-empty"></span>'+"\n"+
'                    <span data-index="1" data-value="2" class="reviews-star-empty"></span>'+"\n"+
'                    <span data-index="2" data-value="3" class="reviews-star-empty"></span>'+"\n"+
'                    <span data-index="3" data-value="4" class="reviews-star-empty"></span>'+"\n"+
'                    <span data-index="4" data-value="5" class="reviews-star-empty"></span>'+"\n"+
'                </span>'+"\n"+
'            </span>'+"\n"+
'            <div class="reviews-field-error" style="display: none;"></div>'+"\n"+
'        </div>'+"\n"+
'        <div class="reviews-field reviews-field-textarea" data-field="content">'+"\n"+
'            <label class="reviews-label reviews-label-textarea" for="site-reviews-content"><span>' + wp.i18n.__('Your review', 'altss') + '</span></label>'+"\n"+
'            <textarea class="reviews-textarea" id="site-reviews-content" name="site-reviews[content]" placeholder="' + wp.i18n.__('Your review', 'altss') + '" rows="5" required=""></textarea>'+"\n"+
'            <div class="reviews-field-error"></div>'+"\n"+
'        </div>'+"\n";
    if( '' != mess ){
        formcode += '        <div class="reviews-field">'+"\n"+
            mess +
'        </div>'+"\n";
    }
    else{
        formcode += 
'        <div class="reviews-field reviews-field-text" data-field="name">'+"\n"+
'            <label class="reviews-label reviews-label-text" for="site-reviews-name"><span>' + wp.i18n.__('Your name', 'altss') + '</span></label>'+"\n"+
'            <input class="reviews-input reviews-input-text" id="site-reviews-name" name="site-reviews[name]" type="text" placeholder="' + wp.i18n.__('Your name', 'altss') + '" required="" value="">'+"\n"+
'            <div class="reviews-field-error"></div>'+"\n"+
'        </div>'+"\n"+
'        <div class="reviews-field reviews-field-email" data-field="email">'+"\n"+
'            <label class="reviews-label reviews-label-email" for="site-reviews-email"><span>' + wp.i18n.__('Your email', 'altss') + '</span></label>'+"\n"+
'            <input class="reviews-input reviews-input-email" id="site-reviews-email" name="site-reviews[email]" type="email" placeholder="' + wp.i18n.__('Your email', 'altss') + '" required="required" value="">'+"\n"+
'            <div class="reviews-field-error"></div>'+"\n"+
'        </div>'+"\n";
    }

formcode +='        <div class="reviews-field reviews-field-text" data-field="location">'+"\n"+
'            <label class="reviews-label reviews-label-text" for="site-reviews-location"><span>' + wp.i18n.__('Your city', 'altss') + '</span></label>'+"\n"+
'            <input class="reviews-input reviews-input-text" id="site-reviews-location" name="site-reviews[location]" type="text" placeholder="' + wp.i18n.__('Your city', 'altss') + '" required="" value="">'+"\n"+
'            <div class="reviews-field-error"></div>'+"\n"+
'        </div>'+"\n"+ 
'        <div class="reviews-field reviews-field-terms" data-field="terms">'+"\n"+
'                <span class="mh-terms-cb-over">'+"\n"+
'                     <input class="reviews-input-toggle" id="site-reviews-terms" name="site-reviews[terms]" type="checkbox" value="1" required="">'+"\n"+
'                     - <label for="site-reviews-terms-1">' + wp.i18n.__('I consent to the processing of my personal data specified in this form, and this review is based on my experience and expresses my personal opinion.', 'altss') + '</label>'+"\n"+
'                </span>'+"\n"+
'            <div class="reviews-field-error"></div>'+"\n"+
'        </div>'+"\n"+
'       <div class="reviews-form-message"></div>'+"\n"+
'        <div data-field="submit-button">'+"\n"+
'            <button type="button" class="reviews-button button btn btn-primary">'+"\n"+
'                <span class="reviews-button-loading"></span>'+"\n"+
'                <span class="reviews-button-text" data-text="' + wp.i18n.__('Submit a review', 'altss') + '">' + wp.i18n.__('Submit a review', 'altss') + '</span>'+"\n"+
'            </button>'+"\n"+
'        </div>'+"\n"+
'</form>'+"\n";
    return formcode;
}

function mailValidate( val ){
    var pattern = /^[\.a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i;
    if( 0 == val.search( pattern ) ) return true;
    else return false;
}

function validateFields(){
    var substop = false;
    var Fields = ['rating', 'content', 'name', 'email', 'location', 'terms'];
    var rftxt = wp.i18n.__('required field', 'altss');
    var err_email = wp.i18n.__('Invalid email format', 'altss');
    for( i = 0; i < 6; i++ ){
        if( jQuery( 'div' ).is('.reviews-field[data-field="' + Fields[i] + '"]') ){
            thispar = jQuery('.reviews-field[data-field="' + Fields[i] + '"]');
            if( Fields[i] === 'rating' ){
                if( jQuery( '#site-reviews-rating' ).val() != '--' ){
                }
                else{
                    jQuery( '.mh-star-rating--stars span' ).attr( 'class', '' );
                    jQuery( '.mh-star-rating--stars' ).addClass( 'reviews-stars-red' );
                    thispar.children( '.reviews-field-error' ).show().html( rftxt );
                    substop = true;
                }
            }
            else if( Fields[i] === 'email' ){
                if( mailValidate( jQuery( '#site-reviews-email' ).val() ) ){
                    jQuery( '#site-reviews-email' ).removeClass( 'reviews-is-invalid' );
                }
                else{
                    jQuery( '#site-reviews-email' ).addClass( 'reviews-is-invalid' );
                    thispar.children( '.reviews-field-error' ).show().html( err_email );
                    substop = true;
                }
            }
            else if( Fields[i] === 'terms' ){
                if( !jQuery( '#site-reviews-terms' ).is(':checked') ){
                    jQuery( '.mh-terms-cb-over' ).addClass( 'mh-terms-cb-over-error' );
                    thispar.children( '.reviews-field-error' ).show().html( rftxt );
                    substop = true;
                }
                else{
                    jQuery( '.mh-terms-cb-over' ).removeClass( 'mh-terms-cb-over-error' );
                }
            }
            else{
                el = jQuery( '#site-reviews-' + Fields[i] );
                if( el.val().length < 1 ){
                    el.addClass( 'reviews-is-invalid' );
                    thispar.children( '.reviews-field-error' ).show().html( rftxt );
                    substop = true;
                }
                else{
                    el.removeClass( 'reviews-is-invalid' );
                }
            }
        }
        else continue;
    }
    if( !substop ) document.forms['review-form'].submit();
}
function starOut(){
        stsel = jQuery( '#site-reviews-rating' ).val();
        if( stsel === '--' ) stsel = -1;
        jQuery( ".mh-star-rating--stars span" ).each( function( i ){
            if( i < ( stsel ) ){
                jQuery( this ).removeClass( "reviews-star-empty" ).addClass( "mh-active" );
            }
            else{
                jQuery( this ).removeClass( "mh-active" ).addClass( "reviews-star-empty" );
            }
        });
}
jQuery( document ).ready( function($){
        $( '#reviews-form-wrap' ).html( formbody( $( '#reviews-form-wrap' ).data( 'url' ), $( '#reviews-form-wrap' ).data( 'rdr' ), $( '#mess-for-user' ).html() ) );
        var starspan = $( ".mh-star-rating--stars span" );
        starspan.hover( function(){
                $( '.mh-star-rating--stars' ).removeClass( 'reviews-stars-red' );
                $( '.reviews-field-rating' ).children( '.reviews-field-error' ).hide().html( "" );
                var di = $( this ).data( 'index' );
                starspan.removeClass( "mh-active" ).addClass( "reviews-star-empty" );
                starspan.each( function( i ){
                    $( this ).removeClass( "reviews-star-empty" ).addClass( "mh-active" );
                    if( di == i ) return false;
                });
                $( this ).removeClass( "reviews-star-empty" ).addClass( "mh-active" );
	    }, function(){ 	
                starOut();
	  });
          starspan.click(function(){
              $(".reviews-select option").removeAttr("selected");
              $( '#site-reviews-rating' ).val( $( this ).data( "value" ) );
              starOut();
          });
          $( '.reviews-field input[type=text], .reviews-field input[type=email], .reviews-field textarea' ).on( 'input', function(){
              if(isMobile.any()){
                let fld = $( this ).parent( '.reviews-field' ).data( 'field' );
                if( 1 < $( this ).val().length ){
                  $( this ).removeClass( 'reviews-is-invalid' );
                  $( this ).parent( '.reviews-field' ).children( '.reviews-field-error' ).hide().html( "" );
                }
                }
              let fld = $( this ).parent( '.reviews-field' ).data( 'field' );
              if( 1 < $( this ).val().length ){
                $( this ).removeClass( 'reviews-is-invalid' );
                $( this ).parent( '.reviews-field' ).children( '.reviews-field-error' ).hide().html( "" );
              }
          });
          $( '.reviews-field input[type=text], .reviews-field input[type=email], .reviews-field textarea' ).keypress(function(){
              let fld = $( this ).parent( '.reviews-field' ).data( 'field' );
              if( 1 < $( this ).val().length ){
                $( this ).removeClass( 'reviews-is-invalid' );
                $( this ).parent( '.reviews-field' ).children( '.reviews-field-error' ).hide().html( "" );
              }
          });
          $( '#site-reviews-terms' ).click(function(){
              $( '.mh-terms-cb-over' ).removeClass( 'mh-terms-cb-over-error' );
              $( '.reviews-field-terms' ).children( '.reviews-field-error' ).hide().html( "" );
          });
          $( '.btn-primary' ).click(function(){
              validateFields();
          });
});
