<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_init', 'altss_settings_template_options_init' );
function altss_settings_template_options_init() {
    include_once ALTSITESET_INCLUDES_DIR . '/data-vars/cform-field-keys.php';
    
    register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_container_id', 'altss_text_field_clean' );
    register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_privacy_policy_page', 'altss_text_field_clean' );

    for( $f = 1; $f < ( ALTSITESET_CFORMS_AMOUNT + 1 ); $f++ ){
        
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_title_' . $f, 'altss_text_field_clean' );
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_titleshow_' . $f, 'altss_text_field_clean' );
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_desc_' . $f, 'wp_kses_post' );
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_descshow_' . $f, 'altss_text_field_clean' );
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_fields_' . $f, 'altss_text_field_clean' );
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_reqfields_' . $f, 'altss_text_field_clean' );
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_firstemail_' . $f, 'altss_text_field_clean' );
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_secondemail_' . $f, 'altss_text_field_clean' );
        register_setting( 'altss_settings_cforms_options_1', 'altss_settings_cforms_options_submitbtntext_' . $f, 'altss_text_field_clean' );
        
    }

    foreach( $FORM_FIELD_KEYS as $val ){
        register_setting( 'altss_settings_cforms_options_2', 'altss_settings_cforms_options_field_' . $val, 'altss_text_field_clean' );
    }

    
}






function altss_cform_settings_page_html(){
    global $wpdb, $FORM_FIELDS;

    include_once ALTSITESET_INCLUDES_DIR . '/data-vars/cform-fields.php';
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'altss-fields-sortable-script', ALTSITESET_URL . '/admin/js/fields-sortable-script.js', [], ALTSITESET__VERSION, true );
    wp_enqueue_script( 'altss-cforms-script', ALTSITESET_URL . '/admin/js/cforms.js', [], ALTSITESET__VERSION, true );
    wp_set_script_translations( 'altss-cforms-script', 'altss', ALTSITESET_LANG_DIR . '/js' );
        
    $tab = isset( $_GET['tab'] ) ? intval( $_GET['tab'] )  : 0;

    $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] )  : 'start';

    
?>
<div class="nkt-settings-page-wrapper">
    <h2 class="nkt-settings-admin-page-head"><?php esc_html_e( "Contact Forms Settings Page", "altss" ); ?></h2> 
   
    <div id="welcome-panel" class="thadm-welcome-panel">
            <?php
            $tab_title = [
                0 => esc_html__( "Messages from forms", "altss" ),
                1 => esc_html__( "Form sets", "altss" ),
                2 => esc_html__( "Forms fields", "altss" )
            ];
            altss_navtabs( $tab_title, $tab ); 
            ?>
        
	<div class="">
        <?php  ?>
            <h1 class="site-settings-cform-header-<?php echo esc_attr( $tab );?>"><?php echo esc_html( $tab_title[$tab] );?></h1>
            <div class="site-settings-cform-wrapp-<?php echo esc_attr( $tab );?>">
                <form action="options.php" method="POST">
<?php switch ($tab): /////////////// TAB SWITCH
        case 0://////////////////////////////////// TAB 0 SECTION
            include_once ALTSITESET_INCLUDES_DIR.'/sub-includes/cforms-settings-tab-0.php';
            break;
        case 1://////////////////////////////////// TAB 1 SECTION
            include_once ALTSITESET_INCLUDES_DIR.'/sub-includes/cforms-settings-tab-1.php';
		break;
	case 2://////////////////////////////////// TAB 2 SECTION
        include_once ALTSITESET_INCLUDES_DIR.'/sub-includes/cforms-settings-tab-2.php';
		break;
        endswitch;/////////////// END TABS SWITCH

?>
            </form>
        </div>
    
        </div>
    </div>
</div>
<div class="popup-show-bg" id="popup_show_bg">
    <div class="popup-container">
        <div class="popup__close">
            <button type="button" class="popup-close-button" aria-label="<?php esc_attr_e( "Close dialog", "altss" ); ?>">
                <svg role="presentation" class="popup__close-icon" width="23px" height="23px" viewBox="0 0 23 23"
                    version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g stroke="none" stroke-width="1" fill="#fff" fill-rule="evenodd">
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
        <div id="popup-form-wrapper">
        </div>
    </div>
</div>
<?php
}