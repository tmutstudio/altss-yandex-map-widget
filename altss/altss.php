<?php
/**
 * Plugin Name: Alternative Site Settings
 * Plugin URI:  https://github.com/tmutstudio/alternative-site-settings
 * Description: Plugin for managing site settings, including feedback forms, photo gallery, reviews and contacts.
 * Version:     1.2.0
 * Author:      tmutarakan-dev
 * Author URI:  https://github.com/tmutstudio
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: altss
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}


define( 'ALTSITESET_DIR', dirname( realpath( __FILE__ ) ) );
define( 'ALTSITESET_URL', plugins_url( "", __FILE__ ) );
define( 'ALTSITESET_INCLUDES_DIR' , ALTSITESET_DIR . "/includes" );
define( 'ALTSITESET_ADMIN_DIR' , ALTSITESET_DIR . "/admin" );
define( 'ALTSITESET_CLASSES_DIR' , ALTSITESET_DIR . "/classes" );
define( 'ALTSITESET_LANG_DIR' , ALTSITESET_DIR . "/languages" );

define( 'ALTSITESET__VERSION', '1.2.0' );

define( 'ALTSITESET_CFORMS_AMOUNT', 10 );

add_action( 'plugins_loaded', function(){
	load_plugin_textdomain( 'altss', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
} );



function altss_settings_Autoload( $ClassName ){
	preg_match( "/ALTSS_(\S+)/", $ClassName, $class );
	if( $class ){
		$file = ALTSITESET_CLASSES_DIR . "/class.{$class[1]}.php";
		if ( file_exists( $file ) )
		{
			require_once( $file );
		}
	}
}

spl_autoload_register( 'altss_settings_Autoload' );


$altss_page = ( isset( $_GET['page'] ) ) ? sanitize_text_field( $_GET['page'] ) : NULL;



function altss_settings_copy_for_theme(){
    if( ! class_exists( "WP_Filesystem_Direct" ) ){
        include_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
    }
    $WP_Filesystem_Direct = new WP_Filesystem_Direct( null );
	$stylesheet = get_stylesheet();
	$assets_theme_dir = get_theme_root() . "/{$stylesheet}/assets";
	$css_theme_dir = $assets_theme_dir . "/css";
	$js_theme_dir = $assets_theme_dir . "/js";
	$img_theme_dir = $assets_theme_dir . "/images";
	if( ! is_dir( $assets_theme_dir ) ){
        $WP_Filesystem_Direct->mkdir( $assets_theme_dir, 0755 );
        $WP_Filesystem_Direct->mkdir( $css_theme_dir, 0755 );
        $WP_Filesystem_Direct->mkdir( $js_theme_dir, 0755 );
        $WP_Filesystem_Direct->mkdir( $img_theme_dir, 0755 );
    }
    else{
        if( ! is_dir( $css_theme_dir ) ){
            $WP_Filesystem_Direct->mkdir( $css_theme_dir, 0755 );
        }
        if( ! is_dir( $js_theme_dir ) ){
            $WP_Filesystem_Direct->mkdir( $js_theme_dir, 0755 );
        }
        if( ! is_dir( $img_theme_dir ) ){
            $WP_Filesystem_Direct->mkdir( $img_theme_dir, 0755 );
        }
    }
	if( is_dir( $css_theme_dir ) ){
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/css/for-theme/cf-style.css', $css_theme_dir . '/cf-style.css' );
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/css/for-theme/reviews-style.css', $css_theme_dir . '/reviews-style.css' );
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/css/for-theme/footer-section.css', $css_theme_dir . '/footer-section.css' );
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/css/for-theme/cookie-banner.css', $css_theme_dir . '/cookie-banner.css', true );
	}
	if( is_dir( $js_theme_dir ) ){
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/js/for-theme/cf-script.js', $js_theme_dir . '/cf-script.js', true );
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/js/for-theme/reviews-form.js', $js_theme_dir . '/reviews-form.js', true );
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/js/for-theme/cookie-banner.js', $js_theme_dir . '/cookie-banner.js', true );
	}
	if( is_dir( $img_theme_dir ) ){
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/images/for-theme/star-empty.svg', $img_theme_dir . '/star-empty.svg' );
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/images/for-theme/star-error.svg', $img_theme_dir . '/star-error.svg' );
		$WP_Filesystem_Direct->copy( ALTSITESET_DIR . '/admin/images/for-theme/star-full.svg', $img_theme_dir . '/star-full.svg' );
	}
	
}


function altss_settings_plugin_activate() {
	$installer = new ALTSS_Installer();
	altss_settings_copy_for_theme();
	altss_settings_add_reviews_post_record();
}
function altss_settings_plugin_deactivate() {
	altss_settings_remove_reviews_post_record();
}

register_activation_hook( __FILE__, 'altss_settings_plugin_activate' );
register_deactivation_hook( __FILE__, 'altss_settings_plugin_deactivate' );


function altss_settings_add_reviews_post_record(){
    global $wpdb;
    $wpdb->delete( "{$wpdb->prefix}posts", [ 'post_name' => 'reviews' ] ); 
    $post_data = array(
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'post_content'   => '<!-- wp:shortcode -->[reviews_page]<!-- /wp:shortcode -->',
        'post_name'      => 'reviews',
        'post_parent'    => 0,
        'post_status'    => 'publish',
        'post_title'     => esc_html__( "Reviews", "altss" ),
        'post_type'      => 'page',
    ); 
    $post_id = wp_insert_post( wp_slash( $post_data ) );  
}
function altss_settings_remove_reviews_post_record(){
    $id = url_to_postid( site_url('/reviews/') );
    wp_delete_post( $id, true );    
}


add_filter('set-screen-option', 'altss_settings_set_option', 10, 3);
function altss_settings_set_option($status, $option, $value) {
	return $value;
}


$altss_settings_per_page_options_list = [ 
	'reviews_per_page',
];

function altss_settings_keep_option($keep, $option, $value) {
	return $value;
}
foreach( $altss_settings_per_page_options_list as $v ){
	add_filter("set_screen_option_$v", 'altss_settings_keep_option', 10, 3);
}


global $altss_settings_options;

$altss_settings_options = get_option( "altss_settings_options" );

if( isset( $altss_settings_options['disable_all_comments'] ) ){
    $disable_all_comments = new ALTSS_Disable_ALL_Comments();
}


include_once ALTSITESET_INCLUDES_DIR.'/post-metaboxes.php';
include_once ALTSITESET_INCLUDES_DIR.'/custom-types-register.php';
include_once ALTSITESET_INCLUDES_DIR.'/frontend/frontend-ajax-functions.php';
include_once ALTSITESET_INCLUDES_DIR.'/frontend/frontend-form-set-functions.php';

if( is_admin() ){
    include_once ALTSITESET_INCLUDES_DIR.'/admin-plugin-functions.php';
    include_once ALTSITESET_INCLUDES_DIR.'/plugin-media-functions.php';
    include_once ALTSITESET_INCLUDES_DIR.'/admin-menu.php';    
    include_once ALTSITESET_INCLUDES_DIR.'/start-page.php';
    include_once ALTSITESET_INCLUDES_DIR.'/cforms-settings-page.php';
    include_once ALTSITESET_INCLUDES_DIR.'/special-page.php';
    include_once ALTSITESET_INCLUDES_DIR.'/reviews-page.php';
    include_once ALTSITESET_INCLUDES_DIR.'/altss-svg-icon-to-base64.php';

    if( isset( $altss_settings_options['duplicate_post_enable'] ) ) {
        ALTSS_Post_Duplicator::init();
    }

    add_action('admin_head','altss_js_head_add');
                    function altss_js_head_add(){
                        echo "<script id='altss_plugin_js'>
                        var wp_admin_url = '" . esc_url( admin_url() ) . "';
                        var altss_referer = '" . esc_url( wp_get_referer() ) . "';
                        var altss_current_url = '" . esc_url( altss_get_current_url() ) . "';
                        </script>";
                    }


    
}
else{
	include_once ALTSITESET_INCLUDES_DIR.'/frontend/frontend-functions.php';
	include_once ALTSITESET_INCLUDES_DIR.'/frontend/frontend-head-functions.php';
	include_once ALTSITESET_INCLUDES_DIR.'/frontend/frontend-tag-functions.php';
    if( isset( $altss_settings_options['collapse_admin_bar'] ) ){
        include_once ALTSITESET_INCLUDES_DIR.'/frontend/classes/class-collapse-adminbar.php';
    }
    if( isset( $altss_settings_options['cookie_banner_on'] ) ){
        include_once ALTSITESET_INCLUDES_DIR.'/frontend/classes/class-cookie-banner.php';
    }
	include_once ALTSITESET_INCLUDES_DIR.'/frontend/frontend-analytics-scripts.php';
	
}

add_action( 'admin_enqueue_scripts', 'altss_styles_for_adminpanel' );
function altss_styles_for_adminpanel(){
    wp_register_style( "alt-site-settings-style", ALTSITESET_URL . "/admin/css/alt-site-settings-style.css", [], ALTSITESET__VERSION );
    wp_enqueue_style( "alt-site-settings-style" );
}


add_filter( 'display_post_states', 'altss_special_page_mark', 10, 2 );
function altss_special_page_mark( $post_states, $post ){
	if( isset( $post->post_type ) && $post->post_type === 'page' && $post->post_name === 'reviews' ){
        $post_states[] = esc_html__( "Page for the &laquo;Reviews&raquo; section", "altss" );
	}

	return $post_states;
}

