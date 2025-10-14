var consentCookieName = 'cookie_consent_choice';

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function cbSetCookie($, btnSet) {
    var name = consentCookieName;
    var value = 'decline';
    if('decline' !== btnSet) {
        value = 'tech';
        
        $('input[name^="cookie_banner_customize["]').each(function(i, e){
            if('accept_all' === btnSet) $(e).prop( 'checked', true );
            if($(e).is( ':checked' )) value = value + '|' + $(e).val();
        });
        console.log($('input[name^="cookie_banner_customize["]'));
    }
    var days = cbsData.cookieConsentDays;
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/;";
}


function setCookieItems($) {
    var consentState = getCookie(consentCookieName);
    $('input[name^="cookie_banner_customize["]').each(function(i, e){
        if('string' === typeof consentState) $(e).prop( 'checked', consentState.includes('|' + $(e).val())); 
    });    
}



function deleteCookie(name, domain) {
    var DomainEQ = domain ? ' Domain=' + domain + ';' : '';
    document.cookie = name + '=; Path=/;' + DomainEQ + ' Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function deleteAllUnnecessaryCookies() {
    var cookies = document.cookie.split(';');
    var essentialCookies = cbsData.wpCookiePrefs;
    cookies.forEach(cookie => {
        var [name] = cookie.trim().split('=');
        var isEssentialCookie = essentialCookies.some(prefix => name.startsWith(prefix));
        if (!isEssentialCookie) {
            deleteCookie(name);
            deleteCookie(name, cbsData.siteDomain);
        }
    });
}

function setConsentGtag(set) {
    if('function' !== typeof(gtag)) return;
    gtag('consent', 'update', {
        'ad_storage': set,
        'ad_user_data': set,
        'ad_personalization': set,
        'analytics_storage': set
    });
}

function setYandexMetrika(set) {
    var st = { decline: true, accept: false }
    window['disableYaCounter' + cbsData.yaMetrikaID] = st[set];
}

function showCookieBanner($, position) {
        $('#cookie-banner').css({'display': 'flex', 'opacity': 0});
        if(!['top-full', 'bottom-full'].includes(cbsData.bannerPosition)){
            var cbWidth = $('#cookie-banner').outerWidth();
            var cbHeight = $('#cookie-banner').outerHeight();
            var animateProps = {}
            switch (position) {
                case 'top-left':
                    $('#cookie-banner').css({'top': '-'+cbHeight+'px', 'left': '-'+cbWidth+'px'});
                    animateProps = {'top': 0, 'left': 0}
                    break;
            
                case 'top-center':
                    $('#cookie-banner').css({'top': '-'+cbHeight+'px'});
                    animateProps = {'top': 0}
                    break;
            
                case 'top-right':
                    $('#cookie-banner').css({'top': '-'+cbHeight+'px', 'right': '-'+cbWidth+'px'});
                    animateProps = {'top': 0, 'right': 0}
                    break;
            
                case 'middle-left':
                    $('#cookie-banner').css({'left': '-'+cbWidth+'px'});
                    animateProps = {'left': 0}
                    break;
            
                case 'center':
                    $('#cookie-banner').css({'left': '-'+cbWidth+'px', 'top': '-'+cbHeight+'px'});
                    animateProps = {'top': '50%', 'left': '50%'}
                    break;
            
                case 'middle-right':
                    $('#cookie-banner').css({'right': '-'+cbWidth+'px'});
                    animateProps = {'right': 0}
                    break;
            
                case 'bottom-left':
                    $('#cookie-banner').css({'bottom': '-'+cbHeight+'px', 'left': '-'+cbWidth+'px'});
                    animateProps = {'bottom': 0, 'left': 0}
                    break;
            
                case 'bottom-center':
                    $('#cookie-banner').css({'bottom': '-'+cbHeight+'px'});
                    animateProps = {'bottom': 0}
                    break;

                case 'bottom-right':
                    $('#cookie-banner').css({'bottom': '-'+cbHeight+'px', 'right': '-'+cbWidth+'px'});
                    animateProps = {'bottom': 0, 'right': 0}
                    break;
            }

            $('#cookie-banner').css({'opacity': 1});
            $('#cookie-banner').animate(animateProps, 300, "linear", function(){
                $('#cookie-banner-back-layer').fadeIn(200);
            });
        }
        else {
            $('#cookie-banner').animate({'opacity': 1}, 300, "linear", function(){
                $('#cookie-banner-back-layer').fadeIn(200);
            });
        }
}

function hideCookieBanner($) {
    $('#cookie-banner').fadeOut( 500, "linear", function(){
        $('#cookie-banner-back-layer').fadeOut(200);
        $('.cookie-banner .popup__close').hide();
    });
}

function showCustomizeWin($) {
    setCookieItems($);
    $('#cookie-banner').hide();
    $('#cookie-banner-start-content').hide();
    $('#cookie-banner-customize-content').show();
    var docWidth = window.innerWidth;
    var docHeight = window.innerHeight;
    $('#cookie-banner').css({
        'display': 'flex',
        'opacity': 0,
        'max-width': '680px',
        'border-radius': docWidth < 600 ? 0 : cbsData.bannerBorderRadius + 'px',
        'top': '50%',
        'left': '50%',
        'bottom': 'unset',
        'transform': 'translate(-50%, -50%)',
    });
    if( $('#cookie-banner').outerHeight() > docHeight ) {
        $('#cookie-banner').css({
            'position': 'absolute',
            'top': 0,
            'transform': 'translate(-50%, 0)',
        });
        $('html, body').animate({
            scrollTop: $('#cookie-banner').offset().top - 70
        }, 800);
    }
    animateProps = {'opacity': 1}
    $('#cookie-banner').animate(animateProps, 300, "linear", function(){
        $('#cookie-banner-back-layer').fadeIn(200);
    });
}


function hideCustomizeWin($, back) {
    $('#cookie-banner-start-content').show();
    $('#cookie-banner-customize-content').hide();
    $('#cookie-banner').attr('style', 'daisplay: none;');
    if( back ) showCookieBanner($, cbsData.bannerPosition);
    else hideCookieBanner($);
}


jQuery(document).ready(function ($) {

    var cookieConsentState = getCookie(consentCookieName);

    if(! cookieConsentState) {
        setTimeout(()=>{
            showCookieBanner($, cbsData.bannerPosition);
        }, cbsData.bannerDelayTime);
    }

    $('.cookie-banner-buttons button, .cookie-banner-back-button-over button').click(function(){
        var btnSet = $(this).data('set');
        switch (btnSet) {
            case 'decline':
                deleteAllUnnecessaryCookies();
                setConsentGtag('denied');
                setYandexMetrika('decline');                
                break;
        
            case 'accept_all':
            case 'accept_selected':
                //setConsentGtag('granted');
                //setYandexMetrika('accept');
                break;
        
            case 'customize':
                showCustomizeWin($);
                return;
        
            case 'back':
                hideCustomizeWin($, true)
                return;
        
            case 'show-banner':
                $('.cookie-banner .popup__close').show();
                showCookieBanner($, cbsData.bannerPosition);
                return;
        }
        cbSetCookie($, btnSet);
        setTimeout(()=>{
            hideCustomizeWin($);
        }, 500);
    });
    
    $('.cookie-banner .popup__close').click(function(){
        hideCustomizeWin($);
    });
});