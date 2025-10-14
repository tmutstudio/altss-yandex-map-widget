<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_cform_action', 'altss_cfajax_action_callback' );
add_action( 'wp_ajax_nopriv_cform_action', 'altss_cfajax_action_callback' );

function altss_cfajax_action_callback() {
	global $wpdb;
	$t1 = $wpdb->prefix . 'altss_cform_sendings';
	$t2 = $wpdb->prefix . 'altss_cform_sendings_fields';
	include ALTSITESET_INCLUDES_DIR.'/data-vars/cform-fields.php';

	$err_message = array();

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( @$_POST['nonce'] ) ), 'cform-nonce' ) ) {
		wp_die( 'n Error' );
	}

	$cfdata = $_POST['cfdata'];
	
	$fid = intval( $_POST['cform'] );

	$fields = get_option( "altss_settings_cforms_options_fields_{$fid}" );
	$req_fields = get_option( "altss_settings_cforms_options_reqfields_{$fid}" );
    $allowed_strong_html = array(
        'strong' => array()
     );

	foreach( $cfdata as $key => $val ){
		$req = isset( $req_fields[ $key ] ) ? true : false;
		$fieldSettings = get_option( "altss_settings_cforms_options_field_{$key}" );
		if ( empty( $val ) && $req && 'accept' !== $key ) {
            /* translators: %s: search label */
			$err_message[$key] = sprintf( wp_kses( __( "The <strong>%s</strong> field is not filled in.", "altss" ), $allowed_strong_html ), $fieldSettings['label'] );
		}
        elseif( ! isset( $cfdata['accept'] ) ){
            $err_message['accept'] = esc_html__( "Confirm your agreement with the privacy policy.", "altss" );
        }
        elseif ( !empty( $val ) && 'accept' !== $key ) {
			if( 'email' === $key && ! preg_match( '/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i', $val ) ){
				$err_message[$key] = esc_html__( "The email address was entered incorrectly.", "altss" );
			}
			elseif( 'phone' === $key && preg_match( '/[^\d\-\(\)\s+]/i', $val ) ){
				$err_message[$key] = esc_html__( "Unnecessary characters have been entered in the phone number.", "altss" );
			}
			elseif( 'phone' === $key && 7 > preg_match_all( "/[0-9]/", $val )  ){
				$err_message[$key] = esc_html__( "The phone number does not have enough digits.", "altss" );
			}
			elseif( 'textarea' != $FORM_FIELDS[$key]['type'] && 40 < strlen( $val )  ){
				$err_message[$key] = esc_html__( "Too many characters entered.", "altss" );
			}
			elseif( 'textarea' === $FORM_FIELDS[$key]['type'] && 300 < strlen( $val )  ){
				$err_message[$key] = esc_html__( "There are too many characters entered in the message.", "altss" );
			}
			else{
				$cfdata[$key] = 'textarea' === $key ? sanitize_textarea_field( $val ) : sanitize_text_field( $val );
			}
		}
	

	}

	if ( $err_message ) {

		wp_send_json_error( $err_message );

	} else {
        $dateTimeZone = new DateTimeZone("Europe/Moscow");
        $dateTime = new DateTime("now", $dateTimeZone);
        $timeOffset = $dateTimeZone->getOffset($dateTime);
		$user_ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
		$user_agent = sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] );
		$time = time() + $timeOffset;
		$cf_title = get_option( "altss_settings_cforms_options_title_{$fid}" );
		$altss_settings_options = get_option( "altss_settings_options" );

		$fields_value_html = "<table>\n";

		$wpdb->insert( $t1, [ 
			'form_id' => $fid,
			'form_title' => $cf_title,
			'create_time' => $time,
			'ip' => $user_ip,
			'user_agent' => $user_agent,
			] );
		$insert_id = $wpdb->insert_id;

		$wpdb->delete( $t2, [ 'sending_id' => $insert_id ] );

		$pos = 1;

		foreach( $cfdata as $key => $val ) {
            if( 'accept' === $key ) continue;
			$wpdb->insert( $t2, [ 
				'sending_id' => $insert_id,
				'field' => $key,
				'value' => $val,
				'position' => $pos,
				] );

			$f_title = get_option( "altss_settings_cforms_options_field_{$key}" );
			$f_title = @$f_title['label'];
			$fields_value_html .= "<tr><td style='width: 50%'>{$f_title}:</td><td>{$val}</td></tr>\n";
			$pos++;
		}

		$fields_value_html .= "</table>";

		$res = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$t1} WHERE id=%d", $insert_id ) );

        $success = false;

		if( $res ){
			$to_adm_mail_body = "<html><head></head><body>\n";
            /* translators: %s: search title, %d: search id */
			$to_adm_mail_body .= "<h3>" . sprintf( esc_html__( 'Message from the form &laquo;%1$s, ID: %2$d&raquo;', "altss" ), $res->form_title, $insert_id ) . "</h3>
			<p>" . esc_html__( "Form fields", "altss" ) . ":</p>
			{$fields_value_html}
			<p>&nbsp;</p>
			<p>" . esc_html__( "Sent from IP address", "altss" ) . ": {$res->ip}</p>
			<p>User Agent: {$res->user_agent}</p>
			<p>" . esc_html__( "Sending time", "altss" ) . ": " . Date( "d.m.Y H:i", $res->create_time ) . "</p>
			</body></html>";
            /* translators: %s: search title, %d: search id */
			$subject = preg_replace( ["/&laquo;/", "/&raquo;/"], ["«", "»"], sprintf( esc_html__( 'Notification of receipt of message ID: %1$d from the &laquo;%2$s&raquo; form', "altss" ), $insert_id, $res->form_title ) );

			$headers = array(
				'From: ' . get_bloginfo() . ' <info@' .  $_SERVER["SERVER_NAME"] . '>',
				'content-type: text/html',
			);
			$multiple_to_recipients = array(
				get_option( "altss_settings_cforms_options_firstemail_{$res->form_id}" ),
				get_option( "altss_settings_cforms_options_secondemail_{$res->form_id}" ),
				get_option( 'admin_email' )
			);
            
			if( wp_mail( $multiple_to_recipients, $subject, $to_adm_mail_body, $headers ) ) {
                $success = true;
            }
		}


        if( $success ){
            wp_send_json_success( wp_kses( __( "Thank you!<br>Your message has been sent.", "altss" ), [ 'br' => [] ] ) );
        }
        else {
            wp_send_json_error( [ "mail_error" => wp_kses( __( "An error occurred!<br>Message sending failed.", "altss" ), [ 'br' => [] ] ) ] );
        }
		
	}
		
	wp_die();

}


