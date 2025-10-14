<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_init', 'altss_settings_options_init' );
function altss_settings_options_init() {
    
    register_setting( 'altss_settings_options', 'blogname', 'altss_text_field_clean' );
    register_setting( 'altss_settings_options', 'blogdescription', 'altss_text_field_clean' );
    register_setting( 'altss_settings_options', 'altss_settings_options', 'altss_kses_post_clean' );
    register_setting( 'altss_settings_options', 'copyright_info', 'altss_text_field_clean' );

    register_setting( 'altss_settings_options_1', 'altss_settings_options_custom_recs', 'altss_text_field_clean' );
    register_setting( 'altss_settings_options_1', 'altss_settings_options_custom_recs_settings', 'altss_text_field_clean' );
    
    register_setting( 'altss_settings_options_2', 'altss_settings_options_cookie_banner_settings', 'altss_kses_post_clean' );

    for( $i = 1; $i < 6; $i++ ) {
        register_setting( 'altss_settings_options_txt', 'altss_settings_options_embedded_text_' . $i, 'altss_kses_post_clean' );
    }
}


function altss_settings_start_page_html(){
	$action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] )  : 'start';
	$tab = isset( $_GET['tab'] ) ? intval( $_GET['tab'] )  : 0;


    $form_title_ar = [];
    for ($i = 1; $i < ALTSITESET_CFORMS_AMOUNT; $i++) {
        $form_title_ar[$i] = get_option( "altss_settings_cforms_options_title_{$i}" );
    }
    wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
    
    wp_enqueue_style( 'custom_controls_css', ALTSITESET_URL . '/admin/css/custom_controls.css', [], ALTSITESET__VERSION);
    wp_enqueue_script('settings-script', ALTSITESET_URL . '/admin/js/settings-script.js', [], ALTSITESET__VERSION, true);

	wp_localize_script( 'settings-script', 'ssData',
		array(
			'confirmResetColors' => esc_html__( 'Are you sure you want to reset color settings to default values?', 'altss' ),
		)
	);
    
?>
<div class="site-settings-page-wrapper">
    <h2 class="site-settings-admin-page-head"><?php esc_html_e( "Start page for site settings", "altss" ); ?></h2> 
	
   
    <div id="welcome-panel" class="thadm-welcome-panel">
    <?php
            $tab_title = [
                0 => esc_html__( "Main settings", "altss" ),
                1 => esc_html__( "Custom records", "altss" ),
                2 => esc_html__( "Text blocks", "altss" ),
                3 => esc_html__( "Cookie Banner", "altss" )
            ];
            altss_navtabs( $tab_title, $tab ); 
            ?>
        
	<div class="">
            
            <div class="site-settings-template-wrapp">
                <form action="options.php" method="POST">
<?php switch ($tab): /////////////// TAB SWITCH
        case 0://////////////////////////////////// TAB 0 SECTION
            include_once ALTSITESET_INCLUDES_DIR.'/sub-includes/start-page-tab-0.php';
            break;
        case 1://////////////////////////////////// TAB 1 SECTION
            include_once ALTSITESET_INCLUDES_DIR.'/sub-includes/start-page-tab-1.php';
		    break;
        case 2://////////////////////////////////// TAB 2 SECTION
            include_once ALTSITESET_INCLUDES_DIR.'/sub-includes/start-page-tab-2.php';
            break;
        case 3://////////////////////////////////// TAB 3 SECTION
            include_once ALTSITESET_INCLUDES_DIR.'/sub-includes/start-page-tab-3.php';
            break;
    endswitch;/////////////// END TABS SWITCH

?>
                </form>
            </div>
    
        </div>
    </div>
</div>
<?php
}