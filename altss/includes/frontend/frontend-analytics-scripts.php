<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Render Google tag (gtag.js) script code in wp_head
 *
 * @return void
 */
function altss_gtag_script() {
    global $ALTSS_GLOBAL_VARS;

    $G_ID = $ALTSS_GLOBAL_VARS['altss_settings_options']['google_tag_id'] ?? '';
    $is_a_bot = $ALTSS_GLOBAL_VARS['is_allowed_bot'];
    if( $is_a_bot || ! isset( $ALTSS_GLOBAL_VARS['altss_settings_options']['google_tag_enabled'] ) || empty( $G_ID ) ) {
        return;
    }


    $adv_consent = $analytic_consent = 'denied';
    if( altss_cookies_accepted() ) {
        $consent = explode( "|", $_COOKIE['cookie_consent_choice'] );
        $adv_consent = in_array( 'marketing', $consent ) ? 'granted' : 'denied';
        $analytic_consent = in_array( 'analytics', $consent ) ? 'granted' : 'denied';
    }
    if( ! altss_cookies_accepted() || 'denied' === $analytic_consent ) {
        $google_tag_blocking = $ALTSS_GLOBAL_VARS['altss_settings_options']['google_tag_blocking'] ?? 'not_load';
        if( 'not_load' === $google_tag_blocking ) {
            return;
        }
    }


    
    ?>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $G_ID ); ?>" id="altss-gtag-load-script"></script>
<script id="altss-gtag-set-script">
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}

    gtag('consent', 'default', {
        'ad_storage': '<?php echo esc_js( $adv_consent ); ?>',
        'ad_user_data': '<?php echo esc_js( $adv_consent ); ?>',
        'ad_personalization': '<?php echo esc_js( $adv_consent ); ?>',
        'analytics_storage': '<?php echo esc_js( $analytic_consent ); ?>'
    });
    gtag('js', new Date());
    gtag('config', '<?php echo esc_js( $G_ID ); ?>');

</script>
<!-- /Google tag (gtag.js) -->
    <?php

}
add_action( 'wp_head', 'altss_gtag_script' );






function altss_yandex_metrika_script() {
    global $ALTSS_GLOBAL_VARS;

    $yandex_metrika_id = $ALTSS_GLOBAL_VARS['altss_settings_options']['yandex_metrika_id'] ?? '';   
    $is_a_bot = $ALTSS_GLOBAL_VARS['is_allowed_bot'];
    if( $is_a_bot || ! isset( $ALTSS_GLOBAL_VARS['altss_settings_options']['yandex_metrika_enabled'] ) || empty( $yandex_metrika_id ) ) {
        return;
    }
    
    $analytics_allow = false;
    if( altss_cookies_accepted() ) {
        $consent = explode( "|", $_COOKIE['cookie_consent_choice'] );
        $analytics_allow = in_array( 'analytics', $consent );
    }

    if( ! altss_cookies_accepted() || ! $analytics_allow ){
        $yandex_metrika_blocking = $ALTSS_GLOBAL_VARS['altss_settings_options']['yandex_metrika_blocking'] ?? 'not_load';
        if( 'not_load' === $yandex_metrika_blocking ) {
            return;
        }
    }


    $webvisor =  isset( $ALTSS_GLOBAL_VARS['altss_settings_options']['yandex_metrika_webvisor'] ) ? 'true' : 'false';

    ?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" id="yandex-metrika-script" >
(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
m[i].l=1*new Date();
for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,k.id='ym-script',a.parentNode.insertBefore(k,a)})
(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

function initYaCounter() {
    ym(<?php echo intval( $yandex_metrika_id ); ?>, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:<?php echo $webvisor; ?>       
    });
}
<?php echo $analytics_allow ? 'initYaCounter();' : ''; ?>
window['disableYaCounter<?php echo intval( $yandex_metrika_id ); ?>'] = <?php echo esc_attr( $analytics_allow ? 'false' : 'true' ); ?>;
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/<?php echo intval( $yandex_metrika_id ); ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

        <?php 
}
add_action( 'wp_head', 'altss_yandex_metrika_script' );








//////////////////////////// SOME TAG FUNCTION
/**
function altss_some_tag_script() {
    if( ! altss_cookies_accepted() ) {
        return;
    }
    ### script render code
}
add_action( 'wp_head', 'altss_some_tag_script' );
*/
////////////////////////////


