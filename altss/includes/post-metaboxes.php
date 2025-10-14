<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('add_meta_boxes', 'altss_extra_seo_metabox', 1);

function altss_extra_seo_metabox() {
	add_meta_box(
		'seo_meta_box',
		__( "Data for SEO promotion", "altss" ),
		'altss_seo_meta_box', 
		[ 
			'page', 
			'post', 
			'news',
			'actions',
			'books',
			'docs', 
			'videos', 
		],
		'normal'
	);
}

function altss_seo_meta_box( $post ){
    $type = $post->post_type;
    switch ( $type ) {
        case 'page':
            $meta_title = __( "Page title text", "altss" );
            break;
        case 'post':
            $meta_title = __( "Post title text", "altss" );
            break;
    }
	?>
    <div class="seo-meta-box-container">
	<p><?php echo esc_html( $meta_title ); ?> (meta tag title):
		<input type="text" name="seo_meta_title" style="width:100%;" value="<?php echo esc_html( get_post_meta($post->ID, 'seo_meta_title', 1) ); ?>" />
	</p>
	<p><?php esc_html_e( "Article description", "altss" ); ?> (meta tag description):
		<textarea name="seo_meta_description" style="width:100%;height:100px;"><?php echo esc_textarea( get_post_meta($post->ID, 'seo_meta_description', 1) ); ?></textarea>
	</p>
    <p><?php esc_html_e( 'og:image', 'altss' ); ?><br />
       <?php esc_html_e( 'Optimal resolution 600x315 pixels', 'altss' ); ?><br />
        <?php 
            altss_include_uploadscript();
            altss_image_uploader_field( 'seo_meta_og_image', esc_url( get_post_meta($post->ID, 'seo_meta_og_image', 1) ) );
        ?>
    </p>
    </div>

	<input type="hidden" name="seo_meta_box_nonce" value="<?php echo esc_attr( wp_create_nonce(__FILE__) ); ?>" />
	<?php
}

add_action( 'save_post', 'altss_seo_meta_box_update', 0 );

function altss_seo_meta_box_update( $post_id ){
	if (
		! wp_verify_nonce( sanitize_text_field( wp_unslash( @$_POST['seo_meta_box_nonce'] ) ), __FILE__ )
		|| wp_is_post_autosave( $post_id )
		|| wp_is_post_revision( $post_id )
	)
		return false;

	$meta_title = sanitize_text_field( $_POST['seo_meta_title'] );
	$meta_description = sanitize_textarea_field ( $_POST['seo_meta_description'] );
	$meta_og_image = sanitize_url ( $_POST['seo_meta_og_image'] );
	
    if( empty( $meta_title ) ){
			delete_post_meta( $post_id, 'seo_meta_title' ); 
	}
    else {
        update_post_meta( $post_id, 'seo_meta_title', $meta_title );
    }

    if( empty( $meta_description ) ){
			delete_post_meta( $post_id, 'seo_meta_description' ); 
	}
    else {
        update_post_meta( $post_id, 'seo_meta_description', $meta_description );
    }

    if( empty( $meta_og_image ) ){
			delete_post_meta( $post_id, 'seo_meta_og_image' ); 
	}
    else {
        update_post_meta( $post_id, 'seo_meta_og_image', $meta_og_image );
    }

	

	return $post_id;
}


