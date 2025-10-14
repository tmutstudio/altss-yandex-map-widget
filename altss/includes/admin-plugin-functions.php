<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Renders an editor field.
 * @param string $textarea_name TEXTAREA name attribute value for the textarea_name and editor_id.
 *                          May contain square brackets.
 * @param string $content   Initial content for the editor.
 * @param int  $rows  Number of rows in 'novisual' mode.
 * @param string  $mode Editor mode: full/minimal/novisual.  
 * @param string  $body_class  Editor area body class.
 * @param int  $media_buttons   Media Buttons.  
 * @param int  $height   Height for Editor field. 
 */
function altss_add_editior_field( $textarea_name, $content='', $rows=20, $mode='full', $body_class='', $media_buttons = 1, $height = 0 ){
    $editor_id = preg_replace( "/[\[\]]/", "_", $textarea_name );
    if ( str_ends_with( $editor_id, '_' ) ) $editor_id = substr( $editor_id, 0, -1 );
    if( 'full' === $mode ){
        $mce_plugins = 'fullscreen image link media charmap hr lists colorpicker compat3x directionality paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink  wptextpattern wpview';

        $mce_buttons = [
                        'bold,italic', 'strikethrough', 'bullist', 'numlist', 'blockquote', 'hr', 'alignleft', 'aligncenter',
                        'alignright', 'link', 'unlink', 'wp_more', 'spellchecker', 'fullscreen', 'wp_adv'
        ];

        $mce_buttons_2 = [
                        'formatselect', 'fontsizeselect', 'underline', 'alignjustify', 'forecolor', 'backcolor', 'pastetext', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'wp_help'
        ];

        $mce_buttons_3 = [
                        'image'
        ];
        
        
        $quicktags = 1;
    }
    else if( 'minimal' === $mode  ){
        $mce_plugins = 'fullscreen link hr lists colorpicker compat3x directionality paste tabfocus textcolor wordpress wpautoresize wplink  wptextpattern wpview';

        $mce_buttons = [
                        'bold,italic', 'forecolor', 'backcolor', 'strikethrough', 'bullist', 'numlist', 'blockquote', 'hr', 'alignleft', 'aligncenter',
                        'alignright', 'link', 'unlink', 'wp_more', 'spellchecker', 'fullscreen'
        ];
        $mce_buttons_2 = [
                        ''
        ];

        $mce_buttons_3 = [
                        ''
        ];
        
        $media_buttons = 0;
        $quicktags = 0;
    }

    if( 'novisual' === $mode  ){
        $mceInit = false;
        $media_buttons = 1;
        $quicktags = 1;
    }
    else {
        $mceInit = array (
            'selector' => "#$editor_id",
            'resize'   => 'vertical',
            'plugins'  => $mce_plugins,
            'menubar'  => false,
            'wpautop'  => false,
            'toolbar1' => implode( ',', $mce_buttons ),
            'toolbar2' => implode( ',', $mce_buttons_2 ),
            'toolbar3' => implode( ',', $mce_buttons_3 ),
            'body_class' => @$body_class,
            'height' => $height,
            );
    }


    wp_editor( $content, $editor_id, array(
            'wpautop'       => 1,
            'media_buttons' => $media_buttons,
            'textarea_name' => $textarea_name,
            'textarea_rows' => $rows,
            'tabindex'      => null,
            'editor_css'    => '',
            'editor_class'  => '',
            'teeny'         => 0,
            'dfw'           => 0,
            'tinymce'       => $mceInit,
            'quicktags'     => $quicktags,
            'drag_drop_upload' => false
    ) );                                
}/////////////********************* END OF FUNCTION *************************/



/**
 * Reending Chekbox, stylized for on-off switch.
 * @param string $name CHECKBOX name attribute.
 *                          May contain square brackets.
 * @param string $value   CHECKBOX value attribute.
 * @param string $saved_value   saved value from options.
 * @param string $label_text   Text for display in the label.
 * @param string $data_item   A string key that may be needed for identification in JavaScript.
 */
function altss_add_onoff_switch( $name, $value, $saved_value, $label_text, $data_item = '' ) {
    $switch_id = preg_replace( "/[\[\]]/", "_", $name );
    if ( str_ends_with( $switch_id, '_' ) ) $switch_id = substr( $switch_id, 0, -1 );
    ?>
    <div class="onoffswitch-over">
        <div class="onoffswitch-left">
            <input type="checkbox" id="<?php echo esc_attr( $switch_id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="onoffswitch-checkbox" data-item="<?php echo esc_attr( $data_item ); ?>" value="<?php echo esc_attr( $value ); ?>"<?php checked( $saved_value, $value); ?> />
            <label class="onoffswitch-label" for="<?php echo esc_attr( $switch_id ); ?>"></label>
        </div>
        <label class="onoffswitch-label-text" for="<?php echo esc_attr( $switch_id ); ?>">-  <?php echo esc_html( $label_text ); ?></label>
    </div>
    <?php


}/////////////********************* END OF FUNCTION *************************/




function altss_trim_words( $text, $num_words = 55 ) {

	$original_text = $text;
	$text          = wp_strip_all_tags( $text );
	$num_words     = (int) $num_words;

	if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep         = '';
	} else {
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		$sep         = ' ';
	}

	if ( count( $words_array ) > $num_words ) {
		array_pop( $words_array );
		$text = implode( $sep, $words_array );
		$more = true;
	} else {
		$text = implode( $sep, $words_array );
                $more = false;
	}
        

	return array( $text, $more );
}/////////////********************* END OF FUNCTION *************************/





function altss_navtabs( $tab_titles, $tab ){ ////////////// nav tabs function
if (is_array($tab_titles)) {
    ?>
        <nav class="nav-tab-wrapper wp-clearfix">
            <?php
    foreach ($tab_titles as $key => $val ) {
        echo '<a href="' . esc_url( add_query_arg( 'tab', $key ) ) . '" class="nav-tab' . ($tab == $key ? ' nav-tab-active' : '') . '">' . esc_html( $val ) . '</a>';
    }
    ?>
	</nav>
    <?php
}
}/////////////********************* END OF FUNCTION *************************/

function altss_post_revisions_clear(){ ////////////// post revisions clear function
    global $wpdb;
    $sql = "DELETE a,b,c FROM {$wpdb->prefix}posts a
    LEFT JOIN {$wpdb->prefix}term_relationships b ON (a.ID = b.object_id)
    LEFT JOIN {$wpdb->prefix}postmeta c ON (a.ID = c.post_id)
    WHERE a.post_type = 'revision'";

    if ( false != $wpdb->query( $sql ) ) {
        return true;
    }
    else{
        return false;
    }
}/////////////********************* END OF FUNCTION *************************/

function altss_isPunycode( $value ){
    if ( false === ( 'ASCII' === mb_detect_encoding($value, 'ASCII', true ) ) ) {
        return false;
    }

    return (0 === mb_stripos($value, 'xn--', 0, 'UTF-8'));
}/////////////********************* END OF FUNCTION *************************/

function altss_isPunycodeDomain( $domain ){
        $hasPunycode = false;

        foreach ( explode( '.', $domain ) as $part ) {
            if ( altss_isPunycode($part) ) {
                $hasPunycode = true;
            }
        }

        return $hasPunycode;
}/////////////********************* END OF FUNCTION *************************/


function altss_siteDomain2latinUpperSlug(){
    $domain = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_url( $_SERVER['HTTP_HOST'] ) : '';
    if( altss_isPunycodeDomain( $domain ) ){
        $domain = altss_cyrtolat_slug( idn_to_utf8( $domain ) );
    }
    else {
        $domain = altss_cyrtolat_slug( $domain );
    }

    return strtoupper( $domain );
}/////////////********************* END OF FUNCTION *************************/

function altss_cyrtolat_slug( $slug ) {

        $iso9_table = array(
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Ѓ' => 'G',
		'Ґ' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Є' => 'YE',
		'Ж' => 'ZH', 'З' => 'Z', 'Ѕ' => 'Z', 'И' => 'I', 'Й' => 'J',
		'Ј' => 'J', 'І' => 'I', 'Ї' => 'YI', 'К' => 'K', 'Ќ' => 'K',
		'Л' => 'L', 'Љ' => 'L', 'М' => 'M', 'Н' => 'N', 'Њ' => 'N',
		'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
		'У' => 'U', 'Ў' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS',
		'Ч' => 'CH', 'Џ' => 'DH', 'Ш' => 'SH', 'Щ' => 'SHH', 'Ъ' => '',
		'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ѓ' => 'g',
		'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'ye',
		'ж' => 'zh', 'з' => 'z', 'ѕ' => 'z', 'и' => 'i', 'й' => 'j',
		'ј' => 'j', 'і' => 'i', 'ї' => 'yi', 'к' => 'k', 'ќ' => 'k',
		'л' => 'l', 'љ' => 'l', 'м' => 'm', 'н' => 'n', 'њ' => 'n',
		'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
		'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
		'ч' => 'ch', 'џ' => 'dh', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '',
		'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
	);
	$geo2lat = array(
		'ა' => 'a', 'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v',
		'ზ' => 'z', 'თ' => 'th', 'ი' => 'i', 'კ' => 'k', 'ლ' => 'l', 'მ' => 'm',
		'ნ' => 'n', 'ო' => 'o', 'პ' => 'p','ჟ' => 'zh','რ' => 'r','ს' => 's',
		'ტ' => 't','უ' => 'u','ფ' => 'ph','ქ' => 'q','ღ' => 'gh','ყ' => 'qh',
		'შ' => 'sh','ჩ' => 'ch','ც' => 'ts','ძ' => 'dz','წ' => 'ts','ჭ' => 'tch',
		'ხ' => 'kh','ჯ' => 'j','ჰ' => 'h'
	);
	$iso9_table = array_merge($iso9_table, $geo2lat);

	$locale = get_locale();
	switch ( $locale ) {
		case 'bg_BG':
			$iso9_table['Щ'] = 'SHT';
			$iso9_table['щ'] = 'sht'; 
			$iso9_table['Ъ'] = 'A';
			$iso9_table['ъ'] = 'a';
			break;
		case 'uk':
		case 'uk_ua':
		case 'uk_UA':
			$iso9_table['И'] = 'Y';
			$iso9_table['и'] = 'y';
			break;
	}
        $slug = strtr($slug, apply_filters('ctl_table', $iso9_table));
        if (function_exists('iconv')){
                $slug = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $slug);
        }
        $slug = preg_replace("/[^A-Za-z0-9'_\-]/", '-', $slug);
        $slug = preg_replace('/\-+/', '-', $slug);
        $slug = preg_replace('/^-+/', '', $slug);
        $slug = preg_replace('/-+$/', '', $slug);

        return $slug;
        
}/////////////********************* END OF FUNCTION *************************/





function altss_theme_add_editor_styles() {
	add_editor_style( 'editor-styles.css' );
}
add_action( 'current_screen', 'altss_theme_add_editor_styles' );



add_action( 'wp_ajax_view_cfs_record', 'altss_view_cfs_record' );
function altss_view_cfs_record() {
    global $wpdb;

    $t_1 = $wpdb->prefix . 'altss_cform_sendings';
    $t_2 = $wpdb->prefix . 'altss_cform_sendings_fields';
    $verify_nonce = false;

    if( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'cfs_record_view') ) {
        $id = intval( $_POST['id'] );
        $p = intval( $_POST['p'] );

        $cfs_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$t_1} WHERE id=%d", $id ) );
        $cfs_fields = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$t_2} WHERE sending_id=%d ORDER BY position", $id ) );

        $verify_nonce = true;
    }

    ?>
    <div class="view-cfs-record-wrap">
        <?php if( $verify_nonce ){ ?>
        <h3><?php
        /* translators: %s: search title */
         echo sprintf( esc_html__( "Message from the form: %s", "altss" ), esc_html( $cfs_row->form_title ) ); 
         ?></h3>
        <div>
            <table>
                <tr>
                    <td class="cfs-record-td-left">ID:</td>
                    <td class="cfs-record-td-right"><?php echo esc_attr( $id ); ?></td>
                </tr>
                <tr>
                    <td class="cfs-record-td-left"><?php esc_html_e( "Sending time", "altss" ); ?>:</td>
                    <td class="cfs-record-td-right"><?php echo esc_html( Date( __( "Y-m-d H:i", "altss" ), $cfs_row->create_time ) ); ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="cfs-record-td-spacer"></td>
                </tr>
                <?php 
                foreach( $cfs_fields as $fld ) {
                    $f_title = get_option( "altss_settings_cforms_options_field_{$fld->field}" );
                    $f_title = @$f_title['label'];
                            ?>
                <tr>
                    <td class="cfs-record-td-left"><?php echo esc_html( $f_title ); ?>:</td>
                    <td class="cfs-record-td-right"><?php echo esc_html( $fld->value ); ?></td>
                </tr>
                    <?php

                }
                ?>
                <tr>
                    <td colspan="2" class="cfs-record-td-spacer"></td>
                </tr>
                <tr>
                    <td class="cfs-record-td-left"><?php esc_html_e( "Sender IP", "altss" ); ?>:</td>
                    <td class="cfs-record-td-right"><?php echo esc_html( $cfs_row->ip ); ?></td>
                </tr>
            </table>
        </div>
        <?php if ( current_user_can( 'manage_options' ) ) {?>
        <div class="view-cfs-record-actions"><span id="view-cfs-record-actions-delite-span" data-id="<?php echo esc_attr( $id ); ?>" data-p="<?php echo esc_attr( $p ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( "cfs_record_remove" ) ); ?>"><?php esc_html_e( "delete", "altss" ); ?></span></div>
        <?php }
        }
        else {
            ?>
            <div class="notice notice-error is-dismissible" style="margin: 50px 0;">
                <p><?php esc_html_e( 'WP nonce faled' , "altss" ); ?></p>
            </div>
            <?php
        } ?>
    </div>
    <?php
    die();
}

add_action( 'admin_post_cfs_record_remove', 'altss_cfs_record_remove' );
function altss_cfs_record_remove() {
    global $wpdb, $wp_settings_errors;

    $t_1 = $wpdb->prefix . 'altss_cform_sendings';
    $t_2 = $wpdb->prefix . 'altss_cform_sendings_fields';
    $p = 1;


    if( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'cfs_record_remove') ) {
        $id = intval( $_POST['id'] );
        $p = intval( $_POST['p'] );

        $wpdb->delete( $t_2, [ 'sending_id' => $id ], [ '%d' ] );
        $wpdb->delete( $t_1, [ 'id' => $id ], [ '%d' ] );

        set_transient( 'cfs_record_removed_id', "{$id}", 12 );
    }
    else {
        set_transient( 'cfs_record_remove_error', "nonce failed", 12 );        
    }

    $redirect = admin_url( "admin.php?page=cform_settings_page" . ( 1 < $p ? "&p={$p}" : "" ) );
    header( "Location: $redirect", true, 302 );

    die();
}






/********************** ACTION FOR REVIEW PUBLIC ************************/
add_action( 'wp_ajax_review-public', 'altss_review_public__ajax_callback' );

function altss_review_public__ajax_callback( $args = NULL ){ //////// ***** FUNCTION FOR REVIEW PUBLIC *****
        global $wpdb;
        $t = "{$wpdb->prefix}altss_reviews";
        if( NULL == $args ){
            $args = [];
            if( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), "review_public" ) ){
                if( !isset( $_POST['act'] ) ) die( 'Error!' );
                $args['id'] = intval( $_POST['id'] );
                $args['act'] = sanitize_title( $_POST['act'] );
            }
            else die( 'Error!' );
            $ajaxmode = true;
        }
        else{
            $ajaxmode = false;
        }
        

        $id = $args['id'];
        $status_vars = [ 'hide' => 0, 'show' => 1 ];
        $status = intval( $status_vars[$args['act']] );
        
        $wpdb->query( $wpdb->prepare( "UPDATE {$t} SET review_status=%d WHERE review_id=%d", $status, $id ) );
        if( $ajaxmode ) die();
}/////////////********************* END OF FUNCTION *************************/



/********************** ACTION FOR TRASH RESTORE REVIEW ************************/
add_action( 'admin_post_review_trash_restore', 'altss_review_trash_restore' );

function altss_review_trash_restore( $args = NULL ){ //////// ***** FUNCTION FOR TRASH RESTORE REVIEW *****
        global $wpdb;
        $t = "{$wpdb->prefix}altss_reviews";
        if( NULL == $args ){
            $args = [];
            if( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), "review_trash" ) ){
                if( !isset( $args['id'] ) ) die( 'Error!' );
                $args['id'] = intval( $_POST['id'] );
                $args['act'] = sanitize_title( $_POST['act'] );
                $args['url'] = sanitize_url( $_POST['url'] );
            }
            else die( 'Error!' );
            $m = true;
        }
        else{
            $m = false;
        }
        $stm = [
          'trash' => 2,
          'restore' => 0
        ];
        $id = $args['id'];
        $data['review_status'] = $stm[ $args['act'] ];

        if( 'delete' != $args['act'] ){
            $wpdb->update( $t, $data, ['review_id' => $id] );
        }
        else{
            $wpdb->delete( $t, ['review_id' => $id], ['%d'] );
        }


        if( $m ){
            $redirect_str = $args['url'];
            $redirect = $redirect_str;
            header( "Location: $redirect", true, 302 );
            die();
        }
}/////////////********************* END OF FUNCTION *************************/



function altss_get_current_url(){
    return ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function altss_current_page(){
    $page = ( isset( $_GET['page'] ) ) ? sanitize_text_field( $_GET['page'] ) : NULL;
    return $page;
}



function altss_text_field_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'altss_text_field_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : null;
	}
}


function altss_kses_post_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'altss_kses_post_clean', $var );
	} else {
		return is_scalar( $var ) ? wp_kses_post( $var ) : null;
	}
}



