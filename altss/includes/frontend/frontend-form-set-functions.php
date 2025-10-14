<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/********************** ACTIONS FOR REVIEWS FORM************************/

add_action('admin_post_nopriv_altss_reviews', 'altss_process_reviews_form');
add_action('admin_post_altss_reviews', 'altss_process_reviews_form');

function altss_process_reviews_form() { //////////////// REVIEWS FORM FUNCTION
	global $wpdb;
        $prefix = $wpdb->prefix;
        $args = $_POST['site-reviews'];
        $user = wp_get_current_user()->data;
        $data = [];
        $data['review_text'] = sanitize_textarea_field( $args['content'] );
        if( isset( $user->ID ) ){
            $user_nick = get_user_meta( $user->ID, 'nickname', true );
            $user_name = get_user_meta( $user->ID, 'first_name', true );
            $data['review_author_name'] = '' != $user_name ? $user_name : $user_nick;
            $data['review_author_email'] = $user->user_email;
            $data['review_user_id'] = $user->ID;
        }
        else{
            $data['review_author_name'] = sanitize_text_field( $args['name'] );
            $data['review_author_email'] = sanitize_text_field( $args['email'] );
            $data['review_user_id'] = 0;
        }
        $data['review_author_location'] = sanitize_text_field( $args['location'] );
        $data['review_author_ip'] = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
        $data['review_author_ua'] = sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] );
        $data['review_create_date'] = current_time( 'mysql' );
        $data['review_create_date_gmt'] = current_time( 'mysql', 1 );
        $data['review_rating'] = intval( $args['rating'] );
        
        $altss_reviews_session['sendtime'] = time();
        
        $wpdb->insert( $prefix . 'altss_reviews', $data );

        set_transient( 'altss_reviews_session', $altss_reviews_session);
        $redirect_str = $args['_referer'];
	$redirect = $redirect_str;
	header( "Location: $redirect", true, 302 );
	die();

}/////////////********************* END OF FUNCTION *************************/


