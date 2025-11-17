<?php
/**
 * Plugin Name: ASS Yandex Map Widget
 * Requires Plugins: alternative-site-settings
 * Plugin URI:  https://github.com/tmutstudio/alternative-site-settings
 * Description: Plugin for adding a Yandex Maps widget to your website. The plugin is an extension of the Alternative Site Settings plugin.
 * Version:     1.1.1
 * Author:      tmutarakan-dev
 * Author URI:  https://github.com/tmutstudio
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: ass-ymw
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}



add_action( 'plugins_loaded', function(){
	load_plugin_textdomain( 'ass-ymw', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
} );

function ass_ymw_plugin_settings_link($links) { 
	$settings_link = '<a href="options-general.php?page=altss-ymw">' . esc_html__( 'Settings', 'altss' ) . '</a>'; 
	array_push( $links, $settings_link ); 
	return $links; 
}

$plugin_file = plugin_basename(__FILE__); 
add_filter( "plugin_action_links_$plugin_file", 'ass_ymw_plugin_settings_link' );

add_filter( 'altss_map_settings_section_show', function(){
    return false;
});

add_action( 'altss_admin_after_map_settings_section', 'ass_ymw_map_widget_settings_section' );
function ass_ymw_map_widget_settings_section( $settings_options ) {
        wp_admin_notice(
            esc_html__( 'Please note: Map display is currently controlled by the ASS Yandex Map Widget plugin.', 'ass-ymw' ),
            [
                'type'               => 'warning',
                'id'                 => 'ass-ymw-enabled',
            ]
        );

    ?>
        <div class="">
            <p><?php esc_html_e( "The widget controls are on this page", "ass-ymw" ); ?>:</p>
            <p><a href="<?php echo esc_url( admin_url( 'options-general.php?page=altss-ymw' ) ); ?>">Yandex Map Widget settings</a></p>
        </div>
    <?php
}

/**
 * Check if cookies are accepted.
 *
 * @return bool
 */
function ass_ymw_cookies_accepted() {
    return isset( $_COOKIE['cookie_consent_choice'] ) && str_contains( $_COOKIE['cookie_consent_choice'], 'tech|' );
}


add_action( 'admin_init', 'ass_ymw_settings_options_init' );
function ass_ymw_settings_options_init() {
    
    register_setting( 'altss_ymw_settings_options', 'altss_ymw_settings_options', 'altss_text_field_clean' );
}



add_action('admin_menu', 'ass_ymw_plugin_menu');
function ass_ymw_plugin_menu() {
	add_options_page('ASS Yandex Map Widget Options', 'ASS Yandex Map Widget', 'manage_options', 'altss-ymw', 'ass_ymw_settings_page');
}

add_action( 'wp_enqueue_scripts', function(){
    $altss_settings = get_option( "altss_settings_options" );
    $cb_enabled = $altss_settings['cookie_banner_on'] ?? false;
    $analytic_consent = false;
    if( $cb_enabled && ass_ymw_cookies_accepted() ) {
        $consent = explode( "|", $_COOKIE['cookie_consent_choice'] );
        $analytic_consent = in_array( 'analytics', $consent );
    }
    if( ! $analytic_consent ) {
        wp_enqueue_style( 'ymw-style', plugin_dir_url( __FILE__ ) . 'admin/css/ymw.css', [], '1.1.0' );
    }
    
});

function ass_ymw_settings_page(){
    altss_include_uploadscript();
    $settings_options = get_option( "altss_ymw_settings_options" );
    wp_enqueue_style( 'custom_controls_css', ALTSITESET_URL . '/admin/css/custom_controls.css', [], ALTSITESET__VERSION);
    wp_enqueue_script('settings-script', ALTSITESET_URL . '/admin/js/settings-script.js', [], ALTSITESET__VERSION, true);

?>
    <div class="site-settings-page-wrapper">
        <h2 class="site-settings-admin-page-head"><?php esc_html_e( "ASS Yandex Map Widget Plugin Options", "ass-ymw" ); ?></h2> 
        
    
        <div id="welcome-panel" class="thadm-welcome-panel">
            <div class="site-settings-template-wrapp">
                <form action="options.php" method="POST">
                    <?php settings_fields( 'altss_ymw_settings_options' ); ?>
                    <div class="site-settings-options-gr-wrap">
                        <p class="site-settings-options-gr-title"><?php esc_html_e( "Yandex Map Widget settings", "ass-ymw" ); ?>:</p>
                        <dl>
                            <dt><p><?php _e( 'Unique organization identifier in the Yandex Maps app (oid)', 'ass-ymw' ); ?>:</p></dt>
                            <dd>
                                <p>
                                    <input name="altss_ymw_settings_options[oid]" type="number" value="<?php echo $settings_options['oid'] ?? ''; ?>" style="width: 200px">
                                </p>
                            </dd>
                            <dt class="map-static-image" ><?php esc_html_e( 'Static image in case of cookie rejection', 'ass-ymw' ); ?></dt>
                            <dd class="map-static-image" >
                                <p><?php esc_html_e( 'Recommended resolution: 1920x500 pixels', 'ass-ymw' ); ?></p>
                                <?php 
                                    altss_image_uploader_field( 'altss_ymw_settings_options[map_static_image]', esc_url( $settings_options['map_static_image'] ?? '' ) );
                                ?>
                            </dd>
                        </dl>
                    </div>
                    <?php
                        submit_button();
                    ?>
                </form>
            </div>
        </div>
    </div>
<?php
}

add_shortcode( 'ass_ymw', 'ass_ymw' );


function ass_ymw( $atts = [], $mapcontainer = 'ymw-container' ){
	global $ymw_settings;
    $atts = shortcode_atts( array(
		'height' => '500px',
	), $atts );
    $altss_settings = get_option( "altss_settings_options" );
    $cb_enabled = $altss_settings['cookie_banner_on'] ?? false;
    $ymw_settings = get_option( "altss_ymw_settings_options" );
    $analytic_consent = false;
    if( $cb_enabled && ass_ymw_cookies_accepted() ) {
        $consent = explode( "|", $_COOKIE['cookie_consent_choice'] );
        $analytic_consent = in_array( 'analytics', $consent );
    }

    if( $analytic_consent ) {
        return ass_ymw_widget_yamap_func( $atts, $mapcontainer );
    }
    else {
        return ass_ymw_static_yamap_func( $atts, $mapcontainer );
    }
}


function ass_ymw_static_yamap_func( $atts, $mapcontainer ){
	global $ymw_settings;
    $img_url = $ymw_settings['map_static_image'] ?? '';
    $oid = $ymw_settings['oid'] ?? '';
    if( empty( $img_url ) || empty( $oid ) ) {
        return '';
    }
    $map_link_block = '';

        $link_url = 'https://yandex.ru/maps/org/avto_oligarkh/' . esc_attr( $oid ) . '/?utm_medium=mapframe&utm_source=maps';

        $map_link_block = '
        <div class="ymw-link-block">
            <a href="' . esc_url( $link_url ) . '" role="button" target="_blank" class="ymw-link-button">
                <span class="ymw-link-button-icon" aria-hidden="true">
                    <span tag="span" class="ymw-link-button-icon-inline-image" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 1a9.002 9.002 0 0 0-6.366 15.362c1.63 1.63 5.466 3.988 5.693 6.465.034.37.303.673.673.673.37 0 .64-.303.673-.673.227-2.477 4.06-4.831 5.689-6.46A9.002 9.002 0 0 0 12 1zm0 12.079a3.079 3.079 0 1 1 0-6.158 3.079 3.079 0 0 1 0 6.158z" fill="currentColor"></path></svg>
                    </span>
                </span>
                <span class="ymw-link-button-text">' . esc_attr__( 'Open in Yandex&nbsp;Maps', 'ass-ymw' ) . '</span>
            </a>
        </div>
        ';

    return '<div id="' . esc_attr( $mapcontainer ) . '"  style="position: relative; height: fit-content; line-height: 0; margin-bottom: 0 !important;">
    ' . $map_link_block . '<img src="' . esc_url( $img_url ) . '" />
    </div>';
}


function ass_ymw_widget_yamap_func( $atts, $mapcontainer ){
	global $ymw_settings;
    $oid = $ymw_settings['oid'] ?? '';
    if( empty( $oid ) ) {
        return '';
    }
    return '<div id="'.esc_attr($mapcontainer).'"  style="position: relative; height: '.esc_attr($atts["height"]).'; margin-bottom: 0 !important;">
    <iframe src="https://yandex.ru/map-widget/v1/?from=mapframe&ll=39.091609%2C45.027164&mode=search&oid='.esc_attr( $oid ).'&ol=biz&z=17.02" width="100%" height="500" frameborder="1" allowfullscreen="true" style="position:relative;"></iframe>
    </div>';
}



