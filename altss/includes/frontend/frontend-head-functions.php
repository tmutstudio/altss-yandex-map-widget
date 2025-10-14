<?php


add_filter( 'document_title', 'altss_modify_document_title' );

function altss_modify_document_title( $title ) {
    if( empty( altss_get_settings_set( 'seo_meta_enabled' ) ) ) {
        return '';
    }

	if ( is_front_page() ) {
        $home_title = altss_get_settings_set( 'home_title' );
		return '' != $home_title ? $home_title : $title;
	}
	else if ( is_singular() ) {
        $post = get_queried_object();
		$meta_title = get_post_meta( $post->ID, 'seo_meta_title', true );
		return '' != $meta_title ? $meta_title : $title;
	}
	else if ( is_category() || is_tax() ) {
        $term_meta = get_term_meta( get_queried_object_id() ); 
        $meta_title = isset( $term_meta['meta_title'] ) ? $term_meta['meta_title'][0] : '';
        return '' != $meta_title ? $meta_title : $title;
	}
	else{
		return $title;
	}
}


add_action( "wp_head", "altss_add_wp_head_meta_tags", 1 );
 
function altss_add_wp_head_meta_tags() {
	global $post;
    
    if( empty( altss_get_settings_set( 'seo_meta_enabled' ) ) ) {
        return;
    }

    $altss_settings_options = get_option( "altss_settings_options" );
	$ogtitle = wp_get_document_title();
	$ogurl = get_self_link();
	$ogtype = 'article';
	$home_desc = altss_get_settings_set( 'home_desc' );
	$ogimg = altss_get_settings_set( 'meta_ogimage' );
    
	if( is_singular() && !is_front_page() ) {
        $meta_ogimage = get_post_meta( $post->ID, 'seo_meta_og_image', true );
        if( $meta_ogimage || has_post_thumbnail($post->ID) ) {
            if( $meta_ogimage ) {
                $ogimg = $meta_ogimage;
            }
            else if( has_post_thumbnail($post->ID) ) {
                $ogimg = wp_get_attachment_image_url( get_post_thumbnail_id( $post->ID ), 'large' );
            }
            
        }
		$meta_description = get_post_meta( $post->ID, 'seo_meta_description', true );
		$post_excerpt = wp_strip_all_tags( apply_filters( 'get_the_excerpt', $post->post_excerpt, $post ), true );
		$desc_value = $ogdesc = esc_attr( $meta_description ?: wp_strip_all_tags( $post_excerpt ) );
	}
    elseif( is_archive() ){
        if( is_category() || is_tax() ){
            $term_meta = get_term_meta( get_queried_object_id() ); 
            $meta_description = isset( $term_meta['meta_description'] ) ? $term_meta['meta_description'][0] : '';
            $meta_ogimage = isset( $term_meta['meta_ogimage'] ) ? $term_meta['meta_ogimage'][0] : null;
            $desc_value = $ogdesc = '' != $meta_description ? $meta_description : wp_strip_all_tags( get_the_archive_title()  . ' | ' . term_description() );
            if( $meta_ogimage ) {
                $ogimg = $meta_ogimage;
            }
        }
        else {
            $desc_value = $ogdesc = wp_strip_all_tags( get_the_archive_title()  . ' | ' . term_description() );
        }
    }
    else{
        $desc_value = $ogdesc = empty( $home_desc ) ? $ogtitle : $home_desc;
		$ogtype = 'website';
    }

    echo '
    <meta name="description" value="' . esc_attr( $desc_value ) . '" />
    <meta property="og:url" content="' . esc_url( @$ogurl ) . '" />
    <meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />
    <meta property="og:type" content="' . esc_attr( @$ogtype ) . '" />
    <meta property="og:title" content="' . esc_attr( @$ogtitle ) . '" />
    <meta property="og:description" content="' . esc_attr( $ogdesc ) . '" />
    <meta property="og:image" content="' . esc_url( $ogimg ) . '" />
    <meta property="og:image:alt" content="' . esc_attr( @$ogtitle ) . '" />

	';
}