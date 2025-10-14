<?php

function altss_remove_site_icon_setting( $wp_customize ){
    $wp_customize->remove_setting( 'custom_logo' );
    $wp_customize->remove_setting( 'site_icon' );
}



function altss_image_uploader_field( $name, $value = '', $w = 200, $h = 200) {
	$default = plugin_dir_url(__DIR__) . 'admin/images/no-img.jpg';
	if( $value ) {
		$src = $value;
		$w = $h = 'auto';
	} else {
		$src = $default;
	}
	echo '
	<div>
		<img data-src="' . esc_attr( $default ) . '" src="' . esc_attr( $src ) . '" width="' . esc_attr( $w ) . '" height="' . esc_attr( $h ) . '" />
		<div>
			<input type="hidden" name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '" value="' . esc_html( $value ) . '" />
			<button type="button" class="upload_image_button button">' . esc_html__( "Upload" ) . '</button>
			<button type="button" class="remove_image_button button">×</button>
		</div>
	</div>
	';
}/////////////********************* END OF FUNCTION *************************/

function altss_include_uploadscript() {
	if ( ! did_action( 'altss_enqueue_media' ) ) {
		wp_enqueue_media();
	}
	wp_enqueue_script( 'altss-uploader-script', plugin_dir_url(__DIR__) . 'admin/js/img-uploader.js', array('jquery'), ALTSITESET__VERSION, true );
}/////////////********************* END OF FUNCTION *************************/





