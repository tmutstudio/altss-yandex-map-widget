<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Checking if a bot from the allowed list has visited the site.
 *
 * @return bool
 */
function altss_is_allowed_bot() {
    $bot_list = include ALTSITESET_INCLUDES_DIR . '/data-vars/whitelist-of-bots.php';

    $user_agent = strtolower( $_SERVER['HTTP_USER_AGENT'] ); 

    foreach ( $bot_list as $bot ) {
        if ( strpos($user_agent, $bot) !== false ) {
            return true;
        }
    }
    return false;
}

add_action('init', 'altss_global_vars', 1);
function altss_global_vars(){
	global $ALTSS_GLOBAL_VARS;

    $ALTSS_GLOBAL_VARS['altss_settings_options'] = get_option( 'altss_settings_options' );
    $ALTSS_GLOBAL_VARS['cookie_banner_settings'] = get_option( 'altss_settings_options_cookie_banner_settings' );
    $ALTSS_GLOBAL_VARS['cookie_banner_data'] = include ALTSITESET_INCLUDES_DIR . '/data-vars/cookie-banner-data.php';
    $ALTSS_GLOBAL_VARS['is_allowed_bot'] = altss_is_allowed_bot();
}


/**
 * Get one setting from altss_settings_options.
 * 
 * @param string $set            Option string key.
 * @return value or empty string
 */
function altss_get_settings_set( $set ){
    global $ALTSS_GLOBAL_VARS;
    $settings =  $ALTSS_GLOBAL_VARS['altss_settings_options'];
    return $settings[$set] ?? '';
}


/**
 * Check if cookies are accepted.
 *
 * @return bool
 */
function altss_cookies_accepted() {
    return isset( $_COOKIE['cookie_consent_choice'] ) && str_contains( $_COOKIE['cookie_consent_choice'], 'tech|' );
}



altss_is_allowed_bot();

function altss_cform_generator( $id, $button_selector = '#popup-open-button', $sc_container_selector = null ){
    global $ALTSS_GLOBAL_VARS;
    include ALTSITESET_INCLUDES_DIR.'/data-vars/cform-fields.php';
    $id = intval( $id );
    $formTitle = "altss_settings_cforms_options_title_{$id}";
    $formTitleShow = "altss_settings_cforms_options_titleshow_{$id}";
    $formDesc = "altss_settings_cforms_options_desc_{$id}";
    $formDescShow = "altss_settings_cforms_options_descshow_{$id}";
    $formFields = "altss_settings_cforms_options_fields_{$id}";
    $formReqFields = "altss_settings_cforms_options_reqfields_{$id}";
    $formFirstEmail = "altss_settings_cforms_options_firstemail_{$id}";
    $formSecondEmail = "altss_settings_cforms_options_secondemail_{$id}";
    $formSubmitBtnText = "altss_settings_cforms_options_submitbtntext_{$id}";

    $$formTitle = get_option($formTitle);
    $$formTitleShow = get_option($formTitleShow);
    $$formDesc = get_option($formDesc);
    $$formDescShow = get_option($formDescShow);
    $$formFields = get_option($formFields);
    $$formReqFields = get_option($formReqFields);
    $$formFirstEmail = get_option($formFirstEmail);
    $$formSecondEmail = get_option($formSecondEmail);
    $$formSubmitBtnText = get_option($formSubmitBtnText);

    $altss_settings_options = $ALTSS_GLOBAL_VARS['altss_settings_options'];

    $allowed_link_html = array(
        'a' => array(
            'href'  => true,
            'target' => true,
        ),
     );
    $privacy_policy_page_id = $altss_settings_options['privacy_policy_page'] ?? ( get_option( 'altss_settings_cforms_privacy_policy_page' ) ?? 0 ); //For compatibility
    /* translators: %s: search url */
    $form_accept_text = sprintf( wp_kses( __( "I consent to the processing of my personal data in accordance with the <a href=\"%s\" target=\"_blank\">privacy policy</a> of this website.", "altss" ), $allowed_link_html ), esc_url( get_page_link( $privacy_policy_page_id ) ) );
    $popup_container_form_wrapper = get_option( 'altss_settings_cforms_container_id' );
    $container_selector = $popup_container_form_wrapper ? '#' . $popup_container_form_wrapper : '#popup-container-form-wrapper';

    $js_content = "
    ";

    $js_fields_list =[];

    $return = '';

    if( is_array( $$formFields ) ){
        foreach ( $$formFields as $val ) {
            $fld_ds = $FORM_FIELDS[$val];
            $fieldsSettings = get_option("altss_settings_cforms_options_field_{$val}");
            $placeholder = isset( $fieldsSettings['placeholder'] ) && ! empty( $fieldsSettings['placeholder'] ) ? esc_attr( $fieldsSettings['placeholder'] )  : $fld_ds['placeholder'];
            if( 'textarea' != $fld_ds['type'] ){
                $itype = "type=\"{$fld_ds['type']}\"";
                $js_fields_list[] = "cfLt + 'p' + cfGt + cfLt + cfInp + '{$itype}' + ' name=\"cfdata[{$val}]\" placeholder=\"{$placeholder}\"' + cfGt + cfLt + '/p' + cfGt +\n";
            }
            else{
                $js_fields_list[] = "cfLt + 'p' + cfGt + cfLt + 'textarea' + ' name=\"cfdata[{$val}]\" placeholder=\"{$placeholder}\"' + cfGt + cfLt + '/textarea' + cfGt + cfLt + '/p' + cfGt +\n";
            }
            
        }
    }
    $js_fields_list[] = "cfLt + 'p' + cfGt + cfLt + 'label class=\"checkbox-label\"' + cfGt + cfLt + cfInp + 'type=\"checkbox\"' + ' name=\"cfdata[accept]\" value=\"1\"' + cfGt + cfLt + 'span' + cfGt + ' - {$form_accept_text}' + cfLt + '/span' + cfGt + cfLt + '/label' + cfGt + cfLt + '/p' + cfGt +\n";


    $form_title = $$formTitleShow ? "cfLt + 'p class=\"cform-title\"' + cfGt + '{$$formTitle}' + cfLt + '/p' + cfGt + " : "";
    $form_desc = $$formDescShow ? "cfLt + 'div class=\"cform-desc\"' + cfGt + '{$$formDesc}' + cfLt + '/div' + cfGt + " : "";

    $js_content .= "

    var formContent_{$id} = {$form_title}{$form_desc}cfLt + 'form id=\"cform_{$id}\" action=\"\" method=\"POST\"' + cfGt +
    " . implode( "", $js_fields_list ) . "
    cfLt + cfInp + 'type=\"hidden\" name=\"cform\" value=\"{$id}\"' + cfGt +
    cfLt + 'p class=\"button-p\"' + cfGt + cfLt + cfInp + 'type=\"button\" data-fid=\"cform_{$id}\" name=\"submit\" data-val=\"{$$formSubmitBtnText}\"' + 
    'value=\"{$$formSubmitBtnText}\" class=\"cform_button\" id=\"cform_button_{$id}\"' + cfGt + cfLt + '/p' + cfGt +
    cfLt + '/form' + cfGt;
    ";
    if( null != $sc_container_selector ){
        $container_selector = $sc_container_selector;
        $js_content .= "
cform_container = document.querySelector('{$container_selector}')
cform_container.innerHTML = formContent_{$id};
        ";
    }
    else {
        $js_content .= "
document.addEventListener('DOMContentLoaded', function(){
    jQuery('{$button_selector}').on('click', function(){
        cform_container = document.querySelector('{$container_selector}')
        cform_container.innerHTML = formContent_{$id};
        jQuery('#popup_show_bg').show();
    });
});
        ";       
    }


    add_action( 'wp_footer', function()  use ( $js_content, $id ) {
        ?>
<script id="s_cform_script_<?php echo esc_attr( $id ); ?>">
    <?php echo  $js_content; ?>
</script>
        <?php
        } );

}

function altss_cforms_generate( $data ) {
    $items = [];
    foreach( $data as $v ){
        if( array_key_exists( intval( $v[0] ), $items ) ){
            $items[intval( $v[0] )] .= ", " . $v[1];
        }
        else {
            $items[intval( $v[0] )] = $v[1];
        }
    }

    foreach ($items as $key => $value) {
        altss_cform_generator( $key, $value );
    }

}



add_action( 'wp_head', function()   {
    ?>
<script id="s_cform_script_vars">
    var cfLt = '<';
    var cfGt = '>'
    var cfInp = 'input ';
</script>
    <?php
    } );

add_shortcode( 'ass_cform_button', 'altss_cformbtn_shortcode' );
function altss_cformbtn_shortcode( $atts ){
    $atts = shortcode_atts( array(
		'title' => esc_html__( "form button", "altss" ),
		'class' => 'cform-button-over',
		'id' => 'cform-scode-btn',
		'cfid' => 0
	), $atts );
    altss_cform_generator( $atts['cfid'], "#{$atts['id']}" );
    return "<div id=\"{$atts['id']}\" class=\"{$atts['class']}\"><button>{$atts['title']}</button></div>";
}

add_shortcode( 'ass_cform', 'altss_cform_shortcode' );
function altss_cform_shortcode( $atts ){
    $atts = shortcode_atts( array(
		'id' => 'cform-scode-container',
		'btnid' => 'cform-btn',
		'cfid' => 0
	), $atts );
    altss_cform_generator( $atts['cfid'], "#{$atts['btnid']}", "#{$atts['id']}" );
    return "<div class=\"scode-form-container\" id=\"{$atts['id']}\"></div>";
}

add_shortcode( 'ass_cookie_consent', 'altss_cookie_consent_button' );
function altss_cookie_consent_button( $atts ){
    $atts = shortcode_atts( array(
		'title' => __( 'Change cookie consent', 'altss' ),
	), $atts );
    return '<div class="cookie-banner-buttons" style="justify-content: flex-start;">
        <button class="cookie-banner-decline-button" data-set="show-banner">' . esc_html( $atts['title'] ) . '</button>
    </div>';

}



add_shortcode( 'ass_footer_section', 'altss_footer_section_insert' );
function altss_footer_section_insert( $atts ){
    global $ALTSS_GLOBAL_VARS;
    $settings_options = $ALTSS_GLOBAL_VARS['altss_settings_options'];
    altss_cforms_generate([
        [ $settings_options['footer_form_id'], "#footer-form-button" ]
    ]);
    ob_start();
    altss_the_footer_section();
    $return = ob_get_contents();
    ob_end_clean();
    return $return;
}

if( is_dir( get_theme_root() . "/" . get_stylesheet() . "/assets" ) ){
    add_action( 'wp_enqueue_scripts', function(){
        global $ALTSS_GLOBAL_VARS;
        $settings_options = $ALTSS_GLOBAL_VARS['altss_settings_options'];

        $css_theme_dir = get_theme_root() . "/" . get_stylesheet()  . '/assets/css/';
        $js_theme_dir = get_theme_root() . "/" . get_stylesheet()  . '/assets/js/';
        $css_theme_dir_uri = get_template_directory_uri() . '/assets/css/';
        $js_theme_dir_uri = get_template_directory_uri() . '/assets/js/';
        $__Version = wp_get_theme()->get( 'Version' );

        if( is_file( $css_theme_dir . "cf-style.css" ) ){
            wp_enqueue_style( 'cform-style', $css_theme_dir_uri . 'cf-style.css', array(), $__Version );
        }
        if( is_file( $css_theme_dir . "reviews-style.css" ) ){
            wp_enqueue_style( 'reviews-style', $css_theme_dir_uri . 'reviews-style.css', array(), $__Version );
        }
        if( is_file( $css_theme_dir . "owl-carousel-style.css" ) ){
            wp_enqueue_style( 'owl-carousel-style', $css_theme_dir_uri . 'owl-carousel-style.css', array(), $__Version );
        }
        if( is_file( $css_theme_dir . "footer-section.css" ) && isset( $settings_options['enable_footer_section'] ) ){
            wp_enqueue_style( 'footer-section-style', $css_theme_dir_uri . 'footer-section.css', array(), $__Version );
            if( ! empty( $settings_options['footer_section_styles'] ) ) {
                wp_add_inline_style( 'footer-section-style', '
        .footer-section { ' .
                    ( $settings_options['footer_section_styles']['color'] ? 'color: ' . $settings_options['footer_section_styles']['color'] . '; ' : '' ) . 
                    ( $settings_options['footer_section_styles']['bgcolor'] ? 'background-color: ' . $settings_options['footer_section_styles']['bgcolor'] . '; ' : '' ) . 
                ' }
        .footer-section a:visited,
        .footer-section a:focus-visible,
        .footer-section a:focus,
        .footer-section a:active,
        .footer-section a { ' .
                    ( $settings_options['footer_section_styles']['link_color'] ? 'color: ' . $settings_options['footer_section_styles']['link_color'] . '; ' : '' ) . 
                ' }
        @media(hover: hover) and (pointer: fine) {
            .footer-section a:hover { ' .
                    ( $settings_options['footer_section_styles']['link_hov_color'] ? 'color: ' . $settings_options['footer_section_styles']['link_hov_color'] . '; ' : '' ) . 
                ' }
        }'
            
            );
            }
        }
        if( is_file( $css_theme_dir . "cookie-banner.css" ) ){
            wp_enqueue_style( 'cookie-banner-style', $css_theme_dir_uri . 'cookie-banner.css', array(), $__Version );
        }

        wp_enqueue_script( 'jquery-form' );

        if( is_file( $js_theme_dir . "cf-script.js" ) ){
            wp_enqueue_script(
                'cform-script',
                $js_theme_dir_uri . 'cf-script.js',
                array( 'jquery' ),
                $__Version,
                true
            );
            wp_set_script_translations( 'cform-script', 'altss', ALTSITESET_LANG_DIR . '/js' );
        }
        if( is_file( $js_theme_dir . "reviews-form.js" ) ){
            wp_enqueue_script(
                'reviews-form-script',
                $js_theme_dir_uri . 'reviews-form.js',
                array( 'jquery' ),
                $__Version,
                true
            );
            wp_set_script_translations( 'reviews-form-script', 'altss', ALTSITESET_LANG_DIR . '/js' );
        }
        if( is_file( $js_theme_dir . "cookie-banner.js" ) ){
            wp_enqueue_script(
                'cookie-banner-script',
                $js_theme_dir_uri . 'cookie-banner.js',
                array( 'jquery' ),
                $__Version,
                true
            );
            wp_set_script_translations( 'cookie-banner-script', 'altss', ALTSITESET_LANG_DIR . '/js' );
        }

    } );

    add_action( 'wp_footer', function() {
        $popup_container_form_wrapper = get_option( 'altss_settings_cforms_container_id' );
        ?>
<div class="popup-show-bg" id="popup_show_bg">
    <div class="popup-container">
        <div class="popup-container-wrapper">
            <div class="popup__close">
                <button type="button" class="popup-close-button" aria-label="<?php esc_html_e( "Close dialog", "altss"); ?>">
                    <svg role="presentation" class="popup__close-icon" width="28px" height="28px" viewBox="0 0 23 23"
                        version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g stroke="none" stroke-width="1" fill="" fill-rule="evenodd">
                            <rect
                                transform="translate(11.313708, 11.313708) rotate(-45.000000) translate(-11.313708, -11.313708)"
                                x="10.3137085" y="-3.6862915" width="2" height="30"></rect>
                            <rect
                                transform="translate(11.313708, 11.313708) rotate(-315.000000) translate(-11.313708, -11.313708)"
                                x="10.3137085" y="-3.6862915" width="2" height="30"></rect>
                        </g>
                    </svg>
                </button>
            </div>
            <div id="<?php echo esc_attr( ( $popup_container_form_wrapper ? $popup_container_form_wrapper : 'popup-container-form-wrapper' ) ); ?>">
            </div>
        </div>
    </div>
</div>        <?php
    } , 99999 );


} 




add_action( 'wp_enqueue_scripts', 'altss_localize_scripts' );
function altss_localize_scripts(){
    global $ALTSS_GLOBAL_VARS;
    $site_domain = preg_replace( '#^\w+://#', '', site_url() );

	wp_localize_script( 'cform-script', 'cfajax',
		array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce( 'cform-nonce' )
		)
	);

    $cb_settings = $ALTSS_GLOBAL_VARS['cookie_banner_settings'];
    $cb_dev_values = $ALTSS_GLOBAL_VARS['cookie_banner_data']['default_values'];

    $banner_position = ! empty( $cb_settings['banner_position'] ) ? $cb_settings['banner_position'] : $cb_dev_values['banner_position'];
    $banner_border_radius = ! empty( $cb_settings['banner_border_radius'] ) ? $cb_settings['banner_border_radius'] : $cb_dev_values['banner_border_radius'];
    $banner_delay_time = ! empty( $cb_settings['banner_delay_time'] ) ? $cb_settings['banner_delay_time'] : $cb_dev_values['banner_delay_time'];
    $cookie_consent_days = ! empty( $cb_settings['cookie_consent_days'] ) ? $cb_settings['cookie_consent_days'] : $cb_dev_values['cookie_consent_days'];

    $wordpress_cookie_prefs = $ALTSS_GLOBAL_VARS['cookie_banner_data']['wordpress_cookie_prefs'];

    $yandex_metrika_id = $ALTSS_GLOBAL_VARS['altss_settings_options']['yandex_metrika_id'] ?? '';

	wp_localize_script( 'cookie-banner-script', 'cbsData',
		array(
			'siteDomain' => $site_domain,
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce( 'cform-nonce' ),
			'bannerPosition' => esc_attr( $banner_position ),
			'bannerBorderRadius' => esc_attr( $banner_border_radius ),
			'bannerDelayTime' => intval( $banner_delay_time ) * 1000,
			'cookieConsentDays' => esc_attr($cookie_consent_days ),
			'wpCookiePrefs' => array_map( 'esc_attr', $wordpress_cookie_prefs ),
			'yaMetrikaID' => intval( $yandex_metrika_id ),
		)
	);

}

function altss_insertable_text_block( $num = 1, $class = '' ){
    $num = intval( $num );
    $txt = wp_unslash( get_option( "altss_settings_options_embedded_text_{$num}" ) );
    ?>
    <div class="<?php echo esc_attr( $class ); ?>">
        <?php echo wp_kses( $txt, 'post' ); ?>
    </div>
    <?php
}




add_shortcode( 'reviews_page', 'altss_reviews_page_shortcode' );

function altss_reviews_page_shortcode( $atts ){
    global $wpdb;
    $page = (int) get_query_var( 'paged' );
    $p = 0 != $page ? $page : 1;
	$return = "";
    $altss_reviews_session = get_transient( 'altss_reviews_session' );

    $allowed_link_html = array(
        'a' => array(
            'href'  => true,
            'blank' => true,
        ),
     ); 

    $return .=  "<div class=\"reviews-content-over\">\n";
    if( isset( $altss_reviews_session['sendtime'] ) && ( $altss_reviews_session['sendtime'] > ( time() - 30 ) ) ){
        unset( $altss_reviews_session['sendtime'] );
        set_transient( 'altss_reviews_session', $altss_reviews_session);
        $return .=  '
        <div class="mh-review-send-mess">
            ' . esc_html__( "Thank you! Your review has been added and sent for review.", "altss" ) . '
        </div>
        <p style="text-align: center;">
            <a href="/reviews/">' . esc_html__( "back to reviews", "altss" ) . '</a>
        </p>
        ';
    }
    else {
        $reviews_per_page = 10;
        $reviews_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}altss_reviews WHERE review_status='1'" );
        if( $reviews_per_page < $reviews_count ){
            $all_pages = ceil( $reviews_count / $reviews_per_page );
            $n = $all_pages + 1;
            $links = [];
            for( $i=1; $i<$n; $i++ ){
                if( $i != $p ) $links[] = '<a href="/reviews/page/' . $i . '/">' . esc_html__( "page", "altss" ) . ' <strong>' . $i . '</strong></a>';
                else $links[] = '<span class="">' . esc_html__( "page", "altss" ) . ' <strong>' . $i . '</strong></span>';
            }
            $liksstr = implode( " | ", $links );
            $pagination_links = '<div class="mh-reviews-pagination-over">' . $liksstr . '</div>';
        }
        else{
            $pagination_links = '<div class="mh-reviews-pagination-over"></div>';
        }
        $return .=  $pagination_links;
        $limit = (int) $reviews_per_page;
        $p = ( 1 != $p ) ? ( ( $p - 1 ) * ( $limit ) ) : 0;
        $reviews = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}altss_reviews WHERE review_status='1' ORDER BY review_create_date DESC LIMIT %d, %d", $p, $limit ) );
        $timezone = wp_timezone_string();
        foreach($reviews as $v) {
            $stars = '<span class="mh-review-stars-over">';
            for($i = 0; $i < $v->review_rating; $i++) {
                $stars .= '<span class="mh-review-star-item"></span>';
            }
            $stars .= '</span>';
    
            $return .=  '
            <div class="mh-review-item-over">
                <div class="mh-review-item-rating">' . $stars . '</div>
                <div class="mh-review-item-author"><strong>' . $v->review_author_name . '</strong> ' . esc_html__( "from", "altss" ) . ' ' . $v->review_author_location . '</div>
                <div class="mh-review-item-date">' . mysql2date( esc_html__( "Y-m-d H:i", "altss" ), $v->review_create_date ) . '</div>
                <div class="mh-review-item-text">' . wp_unslash(wpautop($v->review_text)) . '</div>
                <div class="mh-review-item-response">' . $v->review_response_text . '</div>
            </div>
            ';
        }
    
        $return .=  '
        <div class="reviews reviews-default">
            <div id="reviews-form-wrap" class="reviews-form-wrap" data-page="" data-url="' .admin_url('admin-post.php') . '" data-rdr="' . site_url( '/reviews/' ) . '"></div>
        </div>
        ';
        if ( is_user_logged_in() ){
            $user_id = get_current_user_id();
            $user_nick = get_user_meta( $user_id, 'nickname', true );
            $user_name = get_user_meta( $user_id, 'first_name', true );
            $return .=  '
            
        <div id="mess-for-user" style="display: none;">
            <p>
                ' . esc_html__( "You are logged in under the username:", "altss" ) . ' <span class="reviews-nick-span">' . $user_nick . '</span>
                ';
                if ( '' != $user_name ){
                    $return .=  '
                    ' . esc_html__( "with name:", "altss" ) . ' <span class="reviews-nick-span">' . $user_name . '</span>
                    ';
                }
            $return .=  "
            </p>
            <p>\n";
                /* translators: %s: search url */
            $return .=  '            ' . sprintf( wp_kses( __( "Your review will be left on behalf of this user. If you want to leave a review with other data (name, e-mail), you need to <a href='%s'>log out</a>", "altss" ), $allowed_link_html ), esc_url( wp_logout_url( '/reviews/' ) ) ) . '
            </p>
        </div>
        ';
                }
                else{
                    $return .=  '
                    <div id="mess-for-user" style="display: none;"></div>
                    ';
                }
        
        
        $return .=  $pagination_links;
    
    }




        $return .=  "</div>\n";

    return $return;
}

add_filter('redirect_canonical', 'altss_reviews_page_disable_redirect_canonical');
function altss_reviews_page_disable_redirect_canonical( $redirect_url ){
	if( is_paged() ) {
        $redirect_url = false;
    }

	return $redirect_url;
}




function altss_homepage_reviews_slider(){
    global $wpdb, $sections_fields;
    $slider_settings = $sections_fields[6]['slider'];
    $limit = intval( $slider_settings['all_items'] );
    $reviews = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}reviews WHERE review_status='1' ORDER BY review_create_date DESC Limit 0, %d", $limit ) );
    $innerdata = [];
    $r_i = 0;
    $r_count = count( $reviews );
    $all_count = ceil( $r_count / $slider_settings['items'] );
    for( $jj = 0; $jj < $all_count; $jj++ ){
        $innerdata_item = "<div class=\"home-reviews-item-wrapper\">\n";
        for( $i = 0; $i < $slider_settings['items']; $i++ ){
            if( $r_i == $r_count ) break;
            $text = wp_trim_words( wp_unslash( wpautop( $reviews[$r_i]->review_text ) ), 100 );
            $innerdata_item .= '
            <div class="section-reviews-item">
                <div class="section-reviews-item-rating">' . altss_rewiew_rating_stars( $reviews[$r_i]->review_rating, false ) . '</div>
                <div class="section-reviews-item-text">' . $text . '</div>
                <div class="section-reviews-item-name"><span class="">' . $reviews[$r_i]->review_author_name . '</span></div>
            </div>
            ';
            $r_i++;
        }
        $innerdata_item .= "</div>\n";
        $innerdata[] = $innerdata_item;
    }
    $n = count( $innerdata );
    ?>
                    <div class="home-reviews-wrapper">
                        <div id="home-reviews-owl-carousel" class="owl-carousel owl-theme">
                        <?php for( $i = 0; $i < $n; $i++ ):?>
                                    <div><?php echo esc_html( $innerdata[$i] ); ?></div>
                        <?php endfor;?>
                        </div>
                    </div>
   <?php 

    $js_str = 'autoplayHoverPause:true';
    foreach( $slider_settings as $k => $v ){
        if( 'all_items' === $k || 'items' === $k ) continue;
        if( 'slide_time' === $k ){
            $v = $v * 1000;
            $set = 'autoplayTimeout';
        }
        else $set = $k;
        $js_str .= ', ' . $set . ':' . $v;
    }

    $jsslidersinit = "jQuery(document).ready(function($) {
             $('#home-reviews-owl-carousel').owlCarousel({items:1, " . $js_str . "});
    });";
    wp_add_inline_script( 'owl-carousel-script', $jsslidersinit );
}

function altss_rewiew_rating_stars( $n=0, $e = true ){
    $res = '<span class="mh-review-stars-over">';
    for( $i=0; $i<$n; $i++ ){
        $res .= '<span class="mh-review-star-item"></span>';
    }
    $res .= '</span>';
    if( $e ) echo $res;
    else return $res;
}


function altss_header_option_field( $slug ){
    global $ALTSS_GLOBAL_VARS;
    $altss_settings_options = $ALTSS_GLOBAL_VARS['altss_settings_options'];
    if( isset( $altss_settings_options[$slug] ) ){
        if( ! empty( $altss_settings_options[$slug] ) ){
            echo esc_html( $altss_settings_options[$slug] );
        }
    }
}

function altss_the_contact_section_map(){
    global $ALTSS_GLOBAL_VARS;
    $altss_settings_options = $ALTSS_GLOBAL_VARS['altss_settings_options'];
    if( isset( $altss_settings_options['map_display_type'] ) ){
        if( 'shortcode' === $altss_settings_options['map_display_type'] ){
            echo apply_shortcodes( wp_kses( $altss_settings_options['map_shortcode'], 'post' ) );
        }
        elseif( 'static_image' === $altss_settings_options['map_display_type'] ){
            if( '' != $altss_settings_options['map_static_image_link'] ){
            ?>
        <a href="<?php echo esc_url( $altss_settings_options['map_static_image_link'] ); ?>" target="_blank" title="<?php echo esc_attr( $altss_settings_options['map_static_image_link_title'] ); ?>">
            <img src="<?php echo esc_url( $altss_settings_options['map_static_image'] ); ?>" alt="<?php echo esc_attr( $altss_settings_options['contacts']['contacts_location'] ); ?>" />
        </a>
            <?php
            }
            else{
            ?>
            <img src="<?php echo esc_url( $altss_settings_options['map_static_image'] ); ?>" alt="<?php echo esc_attr( $altss_settings_options['contacts']['contacts_location'] ); ?>" title="<?php echo esc_attr( $altss_settings_options['contacts']['contacts_location'] ); ?>" />
            <?php
            }
        }
    }
}

function altss_sanitize_text( $text ){
    $text = sanitize_text_field( $text );
    $text = preg_replace( "/\[br\]/", "<br>", $text );
    return $text;
}


function altss_sanitize_textarea( $text ){
    $text = wp_kses( $text, "post" );
    $text = wpautop( $text );
    return $text;
}


add_filter( 'pre_handle_404', function( $template ) {
    global $wp_query, $ALTSS_GLOBAL_VARS;
    $settings_options = $ALTSS_GLOBAL_VARS['altss_settings_options'];
	if( is_page( 'reviews' ) && isset( $settings_options['disable_reviews'] ) ){
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
		return 'stop';
	}
	return false;
}, 10, 2 );
