<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Frontend Cookie Banner Class.
 */
final class Altss_Cookie_Banner {
    private static $site_settings = [];
    private static $default_texts = [];
    private static $default_values = [];
    private static $cookie_banner_settings = [];
    private static $cookie_select_list = [];

	public static function init(){
		add_action( 'init', [ __CLASS__, 'hooks' ], 30 );
	}

	public static function hooks(){
        global $ALTSS_GLOBAL_VARS;
        SELF::$site_settings = $ALTSS_GLOBAL_VARS['altss_settings_options'];
        SELF::$default_texts = $ALTSS_GLOBAL_VARS['cookie_banner_data']['default_texts'];
        SELF::$default_values = $ALTSS_GLOBAL_VARS['cookie_banner_data']['default_values'];
        SELF::$cookie_select_list = $ALTSS_GLOBAL_VARS['cookie_banner_data']['cookie_select_list'];
        SELF::$cookie_banner_settings = $ALTSS_GLOBAL_VARS['cookie_banner_settings'];
        if( ! $ALTSS_GLOBAL_VARS['is_allowed_bot'] ) {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'render_banner_styles' ], 30 );
        add_action( 'wp_footer', [ __CLASS__, 'render_banner_html' ], 30 );
        }
	}

	public static function render_banner_html(){
        $settings = SELF::$cookie_banner_settings;
        $def_txts = SELF::$default_texts;
        $select_list = SELF::$cookie_select_list;
        $title = ! empty( $settings['banner_title_text'] ) ? $settings['banner_title_text'] : $def_txts['banner_title_text'];
        $customize_title = ! empty( $settings['customize_title_text'] ) ? $settings['customize_title_text'] : $def_txts['customize_title_text'];
        $customize_intro_text = ! empty( $settings['customize_intro_text'] ) ? $settings['customize_intro_text'] : $def_txts['customize_intro_text'];
        $text = ! empty( $settings['banner_text'] ) ? $settings['banner_text'] : $def_txts['banner_text'];
        $accept_all_text = ! empty( $settings['accept_all_btn_txt'] ) ? $settings['accept_all_btn_txt'] : $def_txts['accept_all_btn_txt'];
        $accept_selected_text = ! empty( $settings['accept_selected_btn_txt'] ) ? $settings['accept_selected_btn_txt'] : $def_txts['accept_selected_btn_txt'];
        $decline_text = ! empty( $settings['decline_btn_txt'] ) ? $settings['decline_btn_txt'] : $def_txts['decline_btn_txt'];
        $back_text = ! empty( $settings['back_btn_txt'] ) ? $settings['back_btn_txt'] : $def_txts['back_btn_txt'];
        $customize_text = ! empty( $settings['customize_btn_txt'] ) ? $settings['customize_btn_txt'] : $def_txts['customize_btn_txt'];
        $privacy_policy_page_id = SELF::$site_settings['privacy_policy_page'] ?? 0;
        $cookie_policy_page_id = SELF::$site_settings['cookie_policy'] ?? 0;
        ?>
        <div id="cookie-banner-back-layer" class="cookie-banner-back-layer"></div>
        <div id="cookie-banner" class="cookie-banner">
            <div style="position: relative;">
                <div class="popup__close" style="display: none;">
                    <button type="button" class="popup-close-button" aria-label="<?php esc_html_e( "Close dialog", "avtooligarh"); ?>">
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
            </div>
            <div id="cookie-banner-start-content">
                <div class="cookie-banner-text-over">
                    <h3 class="cookie-banner-title"><?php echo esc_html( $title ); ?></h3>
                    <div class="cookie-banner-text"><?php echo wp_kses_post( $text ); ?></div>
                    <div class="cookie-banner-policy-link">
                        <p style="margin: 4px 0;">
                            <a href="<?php echo esc_url( get_page_link( $privacy_policy_page_id ) ); ?>" target="_blank">
                            <?php echo esc_html_e( 'privacy policy', 'altss' ); ?>
                            </a>
                        </p>
                        <p style="margin: 4px 0;">
                            <a href="<?php echo esc_url( get_page_link( $cookie_policy_page_id ) ); ?>" target="_blank">
                            <?php echo esc_html_e( 'coockie policy', 'altss' ); ?>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="cookie-banner-buttons">
                    <button class="cookie-banner-decline-button" data-set="decline"><?php echo esc_html( $decline_text ); ?></button>
                    <button class="cookie-banner-customize-button" data-set="customize"><?php echo esc_html( $customize_text ); ?></button>
                    <button class="cookie-banner-accept-button" data-set="accept_all"><?php echo esc_html( $accept_all_text ); ?></button>
                </div>
            </div>
            <div id="cookie-banner-customize-content" style="display: none;">
                <h3 class="cookie-banner-title"><?php echo esc_html( $customize_title ); ?></h3>
                <div class="cookie-banner-customize-intro-text"><?php echo wp_kses_post( $customize_intro_text ); ?></div>
                <div class="cookie-banner-customize-items-area">
                    <?php foreach( $select_list as $key => $val ) { 
                        if( 'tech' !== $key && empty( $settings['items'][$key] ) ) continue; ?>
                        <div class="cookie-banner-customize-item">
                            <div class="cb-onoffswitch-over">
                                <div class="cb-onoffswitch-left">
                                    <?php if( 'tech' !== $key ){ ?>
                                    <input type="checkbox" id="cookie_banner_customize_<?php echo esc_attr( $key ); ?>" name="cookie_banner_customize[<?php echo esc_attr( $key ); ?>]" class="cb-onoffswitch-checkbox" value="<?php echo esc_attr( $key ); ?>" />
                                    <label class="cb-onoffswitch-label" for="cookie_banner_customize_<?php echo esc_attr( $key ); ?>"></label>
                                    <?php }
                                    else { ?>
                                    <label class="cb-onoffswitch-required-label" for="cookie_banner_customize_<?php echo esc_attr( $key ); ?>"></label>
                                    <?php } ?>
                                </div>
                                <label class="cb-onoffswitch-label-text" for="cookie_banner_customize_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $val ); ?></label>
                            </div>
                            <div class="cookie-banner-customize-item-desc">
                                <?php echo wp_kses_post( $def_txts[$key . '_ctg_note_text'] ); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="cookie-banner-back-buttons">
                    <div class="cookie-banner-back-button-over">
                        <button class="cookie-banner-back-button" data-set="back"><?php echo esc_html( $back_text ); ?></button>
                    </div>
                    <div class="cookie-banner-buttons">
                        <button class="cookie-banner-accept-button" data-set="accept_selected"><?php echo esc_html( $accept_selected_text ); ?></button>
                        <button class="cookie-banner-accept-button" data-set="accept_all"><?php echo esc_html( $accept_all_text ); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <?php
	}
	public static function render_banner_styles(){
		if( is_admin() ){
			return;
		}
        $cb_settings = SELF::$cookie_banner_settings;
        $def_vals = SELF::$default_values;
        $banner_position = !empty( $cb_settings['banner_position'] ) ? $cb_settings['banner_position'] : $def_vals['banner_position'];
        $position = '';
        $banner_border = '';
        $banner_width = !empty( $cb_settings['banner_width'] ) ? $cb_settings['banner_width'] : $def_vals['banner_width'];
        $max_width = in_array( $banner_position, [ 'top-full', 'bottom-full' ] ) ? 'none' : $banner_width . 'px';
        $banner_border_radius = !empty( $cb_settings['banner_border_radius'] ) ? $cb_settings['banner_border_radius'] : $def_vals['banner_border_radius'];
        $banner_v_padding = !empty( $cb_settings['banner_v_padding'] ) ? $cb_settings['banner_v_padding'] : $def_vals['banner_v_padding'];
        $banner_h_padding = !empty( $cb_settings['banner_h_padding'] ) ? $cb_settings['banner_h_padding'] : $def_vals['banner_h_padding'];
        $banner_title_color = !empty( $cb_settings['banner_title_color'] ) ? $cb_settings['banner_title_color'] : $def_vals['banner_title_color'];
        $banner_txt_color = !empty( $cb_settings['banner_txt_color'] ) ? $cb_settings['banner_txt_color'] : $def_vals['banner_txt_color'];
        $btn_border_width = !empty( $cb_settings['btn_border_width'] ) ? $cb_settings['btn_border_width'] : $def_vals['btn_border_width'];
        $btn_border_radius = !empty( $cb_settings['btn_border_radius'] ) ? $cb_settings['btn_border_radius'] : $def_vals['btn_border_radius'];
        $accept_all_btn_bgcolor = !empty( $cb_settings['accept_all_btn_bgcolor'] ) ? $cb_settings['accept_all_btn_bgcolor'] : $def_vals['default_btn_bgcolor'];
        $accept_all_btn_txt_color = !empty( $cb_settings['accept_all_btn_txt_color'] ) ? $cb_settings['accept_all_btn_txt_color'] : $def_vals['default_btn_txt_color'];
        $accept_all_btn_border_color = !empty( $cb_settings['accept_all_btn_border_color'] ) ? $cb_settings['accept_all_btn_border_color'] : $def_vals['default_btn_border_color'];
        $accept_all_btn_hov_bgcolor = !empty( $cb_settings['accept_all_btn_hov_bgcolor'] ) ? $cb_settings['accept_all_btn_hov_bgcolor'] : $def_vals['default_btn_hov_bgcolor'];
        $accept_all_btn_hov_txt_color = !empty( $cb_settings['accept_all_btn_hov_txt_color'] ) ? $cb_settings['accept_all_btn_hov_txt_color'] : $def_vals['default_btn_hov_txt_color'];
        $accept_all_btn_hov_border_color = !empty( $cb_settings['accept_all_btn_hov_border_color'] ) ? $cb_settings['accept_all_btn_hov_border_color'] : $def_vals['default_btn_hov_border_color'];
        $decline_btn_bgcolor = !empty( $cb_settings['decline_btn_bgcolor'] ) ? $cb_settings['decline_btn_bgcolor'] : $def_vals['default_btn_bgcolor'];
        $decline_btn_txt_color = !empty( $cb_settings['decline_btn_txt_color'] ) ? $cb_settings['decline_btn_txt_color'] : $def_vals['default_btn_txt_color'];
        $decline_btn_border_color = !empty( $cb_settings['decline_btn_border_color'] ) ? $cb_settings['decline_btn_border_color'] : $def_vals['default_btn_border_color'];
        $decline_btn_hov_bgcolor = !empty( $cb_settings['decline_btn_hov_bgcolor'] ) ? $cb_settings['decline_btn_hov_bgcolor'] : $def_vals['default_btn_hov_bgcolor'];
        $decline_btn_hov_txt_color = !empty( $cb_settings['decline_btn_hov_txt_color'] ) ? $cb_settings['decline_btn_hov_txt_color'] : $def_vals['default_btn_hov_txt_color'];
        $decline_btn_hov_border_color = !empty( $cb_settings['decline_btn_hov_border_color'] ) ? $cb_settings['decline_btn_hov_border_color'] : $def_vals['default_btn_hov_border_color'];
        $back_btn_bgcolor = !empty( $cb_settings['back_btn_bgcolor'] ) ? $cb_settings['back_btn_bgcolor'] : $def_vals['default_btn_bgcolor'];
        $back_btn_txt_color = !empty( $cb_settings['back_btn_txt_color'] ) ? $cb_settings['back_btn_txt_color'] : $def_vals['default_btn_txt_color'];
        $back_btn_border_color = !empty( $cb_settings['back_btn_border_color'] ) ? $cb_settings['back_btn_border_color'] : $def_vals['default_btn_border_color'];
        $back_btn_hov_bgcolor = !empty( $cb_settings['back_btn_hov_bgcolor'] ) ? $cb_settings['back_btn_hov_bgcolor'] : $def_vals['default_btn_hov_bgcolor'];
        $back_btn_hov_txt_color = !empty( $cb_settings['back_btn_hov_txt_color'] ) ? $cb_settings['back_btn_hov_txt_color'] : $def_vals['default_btn_hov_txt_color'];
        $back_btn_hov_border_color = !empty( $cb_settings['back_btn_hov_border_color'] ) ? $cb_settings['back_btn_hov_border_color'] : $def_vals['default_btn_hov_border_color'];
        $customize_btn_bgcolor = !empty( $cb_settings['customize_btn_bgcolor'] ) ? $cb_settings['customize_btn_bgcolor'] : $def_vals['default_btn_bgcolor'];
        $customize_btn_txt_color = !empty( $cb_settings['customize_btn_txt_color'] ) ? $cb_settings['customize_btn_txt_color'] : $def_vals['default_btn_txt_color'];
        $customize_btn_border_color = !empty( $cb_settings['customize_btn_border_color'] ) ? $cb_settings['customize_btn_border_color'] : $def_vals['default_btn_border_color'];
        $customize_btn_hov_bgcolor = !empty( $cb_settings['customize_btn_hov_bgcolor'] ) ? $cb_settings['customize_btn_hov_bgcolor'] : $def_vals['default_btn_hov_bgcolor'];
        $customize_btn_hov_txt_color = !empty( $cb_settings['customize_btn_hov_txt_color'] ) ? $cb_settings['customize_btn_hov_txt_color'] : $def_vals['default_btn_hov_txt_color'];
        $customize_btn_hov_border_color = !empty( $cb_settings['customize_btn_hov_border_color'] ) ? $cb_settings['customize_btn_hov_border_color'] : $def_vals['default_btn_hov_border_color'];
        $active_switch_color = !empty( $cb_settings['active_switch_color'] ) ? $cb_settings['active_switch_color'] : $def_vals['active_switch_color'];
        $active_switch_border_color = !empty( $cb_settings['active_switch_border_color'] ) ? $cb_settings['active_switch_border_color'] : $def_vals['active_switch_border_color'];

        switch ( $banner_position ) {
            case 'top-full':
                $position = 'top: 0; left: 0;';
                break;
            case 'bottom-full':
                $position = 'bottom: 0; left: 0;';
                break;
            case 'top-left':
                $position = 'top: 0; left: 0;';
                $banner_border = "border-bottom-right-radius: {$banner_border_radius}px;";
                break;
            case 'top-center':
                $position = 'top: 0; left: 50%; transform: translateX(-50%);';
                $banner_border = "border-bottom-left-radius: {$banner_border_radius}px; border-bottom-right-radius: {$banner_border_radius}px;";
                break;
            case 'top-right':
                $position = 'top: 0; right: 0;';
                $banner_border = "border-bottom-left-radius: {$banner_border_radius}px;";
                break;
            case 'middle-left':
                $position = 'top: 50%; left: 0; transform: translateY(-50%);';
                $banner_border = "border-top-right-radius: {$banner_border_radius}px; border-bottom-right-radius: {$banner_border_radius}px;";
                break;
            case 'center':
                $position = 'top: 50%; left: 50%; transform: translate(-50%, -50%);';
                $banner_border = "border-radius: {$banner_border_radius}px;";
                break;
            case 'middle-right':
                $position = 'top: 50%; right: 0; transform: translateY(-50%);';
                $banner_border = "border-top-left-radius: {$banner_border_radius}px; border-bottom-left-radius: {$banner_border_radius}px;";
                break;
            case 'bottom-left':
                $position = 'bottom: 0; left: 0;';
                $banner_border = "border-top-right-radius: {$banner_border_radius}px;";
                break;
            case 'bottom-center':
                $position = 'bottom: 0; left: 50%; transform: translateX(-50%);';
                $banner_border = "border-top-left-radius: {$banner_border_radius}px; border-top-right-radius: {$banner_border_radius}px;";
                break;
            case 'bottom-right':
                $position = 'bottom: 0; right: 0;';
                $banner_border = "border-top-left-radius: {$banner_border_radius}px;";
                break;
        }


        $banner_bgcolor = $cb_settings['banner_bgcolor'] ?? $def_vals['banner_bgcolor'];

		$styles = "
			.cookie-banner-back-layer { display: none; background-color: #000; opacity: .3; position: fixed; top: 0; width: 100vw;; height: 100vh; z-index: 99990; }
			.cookie-banner { 
                display: none; flex-direction: column; row-gap: 10px;
                background-color: " .  esc_attr( $banner_bgcolor ) . ";
                position: fixed; " .  esc_attr( $position ) . "
                width: 100%; max-width: " .  esc_attr( $max_width ) . "; min-height: 100px; padding: " .  esc_attr( $banner_v_padding ) . "px " .  esc_attr( $banner_h_padding ) . "px;
                " .  esc_attr( $banner_border ) . "
                z-index: 99998;
            }
			.cookie-banner-text-over { display: flex; flex-direction: column; } 
			.cookie-banner-title { margin: 0 0 10px 0; text-align: center; color: " .  esc_attr( $banner_title_color ) . "; } 
			.cookie-banner-text { text-align: justify; color: " .  esc_attr( $banner_txt_color ) . "; } 
			.cookie-banner-policy-link { text-align: center; }  
			.cookie-banner-buttons button, .cookie-banner-back-button-over button { height: -webkit-fill-available; border-width: " .  esc_attr( $btn_border_width ) . "px; border-radius: " .  esc_attr( $btn_border_radius ) . "px; transition: background-color .3s ease, color .3s ease, border-color .3s ease; } 
			.cookie-banner-accept-button { color: " .  esc_attr( $accept_all_btn_txt_color ) . "; background-color: " .  esc_attr( $accept_all_btn_bgcolor ) . "; border-color: " .  esc_attr( $accept_all_btn_border_color ) . "; } 
			.cookie-banner-decline-button { color: " .  esc_attr( $decline_btn_txt_color ) . "; background-color: " .  esc_attr( $decline_btn_bgcolor ) . "; border-color: " .  esc_attr( $decline_btn_border_color ) . "; } 
			.cookie-banner-back-button { color: " .  esc_attr( $back_btn_txt_color ) . "; background-color: " .  esc_attr( $back_btn_bgcolor ) . "; border-color: " .  esc_attr( $back_btn_border_color ) . "; } 
			.cookie-banner-customize-button { color: " .  esc_attr( $customize_btn_txt_color ) . "; background-color: " .  esc_attr( $customize_btn_bgcolor ) . "; border-color: " .  esc_attr( $customize_btn_border_color ) . "; } 

            .cb-onoffswitch-required-label, .cb-onoffswitch-checkbox:checked + .cb-onoffswitch-label { background-color: " .  esc_attr( $active_switch_color ) . "; }
            .cb-onoffswitch-required-label, .cb-onoffswitch-required-label:before,
            .cb-onoffswitch-checkbox:checked + .cb-onoffswitch-label,
            .cb-onoffswitch-checkbox:checked + .cb-onoffswitch-label:before { border-color: " .  esc_attr( $active_switch_border_color ) . "; }

            @media(hover: hover) and (pointer: fine) {
                .cookie-banner-accept-button:hover { color: " .  esc_attr( $accept_all_btn_hov_txt_color ) . "; background-color: " .  esc_attr( $accept_all_btn_hov_bgcolor ) . "; border-color: " .  esc_attr( $accept_all_btn_hov_border_color ) . "; } 
                .cookie-banner-decline-button:hover { color: " .  esc_attr( $decline_btn_hov_txt_color ) . "; background-color: " .  esc_attr( $decline_btn_hov_bgcolor ) . "; border-color: " .  esc_attr( $decline_btn_hov_border_color ) . "; } 
                .cookie-banner-back-button:hover { color: " .  esc_attr( $back_btn_hov_txt_color ) . "; background-color: " .  esc_attr( $back_btn_hov_bgcolor ) . "; border-color: " .  esc_attr( $back_btn_hov_border_color ) . "; } 
                .cookie-banner-customize-button:hover { color: " .  esc_attr( $customize_btn_hov_txt_color ) . "; background-color: " .  esc_attr( $customize_btn_hov_bgcolor ) . "; border-color: " .  esc_attr( $customize_btn_hov_border_color ) . "; } 
            }
            ";
        if( in_array( $banner_position, [ 'top-full', 'bottom-full' ] ) ) {
            $styles .= "
            @media screen and (min-width: 48em) {
                .cookie-banner { flex-direction: row; justify-content: space-between; align-items: center; }
                .cookie-banner-text-over { max-width: 800px; }
                .cookie-banner-text { padding-right: 20px; }
                .cookie-banner-buttons { width: fit-content; } 
            }
            ";
        }
        else {
            $styles .= "
            @media screen and (max-width: " . ($banner_width + 40 ) . "px) { .cookie-banner { max-width: 94%; } }
            ";
        }
        $styles .= "
            @media screen and (max-width: 600px) { .cookie-banner { padding-left: 10px; padding-right: 10px; width: 100%; max-width: 600px; border-radius: 0; left: 0; transform: none; } }
        ";
        $handle = 'cookie-banner-style';

        wp_add_inline_style( $handle, $styles );
    }

}

Altss_Cookie_Banner::init();