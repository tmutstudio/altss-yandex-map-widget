<?php
/**
 * Alternative Site Settings Plugin
 * ALTSS_Disable_ALL_Comments class
 * Description: Disables all WordPress comment functionality on the entire network.
 * 
 */

defined( 'ABSPATH' ) || exit;

class ALTSS_Disable_ALL_Comments {

	public function __construct() {
		add_action( 'widgets_init', [ $this, 'disable_rc_widget' ] );
		add_filter( 'wp_headers', [ $this, 'filter_wp_headers' ] );
		add_action( 'template_redirect', [ $this, 'filter_query' ], 9 );
		add_action( 'add_admin_bar_menus', [ $this, 'filter_admin_bar' ], 0 );
		add_action( 'admin_init', [ $this, 'filter_admin_bar' ] );
		add_action( 'wp_loaded', [ $this, 'setup_filters' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'filter_gutenberg_blocks' ] );
		add_filter( 'rest_endpoints', [ $this, 'filter_rest_endpoints' ] );
		add_filter( 'xmlrpc_methods', [ $this, 'disable_xmlrc_comments' ] );
		add_filter( 'rest_pre_insert_comment', [ $this, 'disable_rest_api_comments' ], 10, 2 );
		add_filter( 'comments_array', '__return_empty_array', 20 );
	}

	public function setup_filters() {
		$types = array_keys( get_post_types( [ 'public' => true ], 'objects' ) );
		if ( ! empty( $types ) ) {
			foreach ( $types as $type ) {
				if ( post_type_supports( $type, 'comments' ) ) {
					remove_post_type_support( $type, 'comments' );
					remove_post_type_support( $type, 'trackbacks' );
				}
			}
		}

		if ( is_admin() ) {
			add_action( 'admin_menu', [ $this, 'filter_admin_menu' ], 9999 );
			add_action( 'admin_print_styles-index.php', [ $this, 'admin_css' ] );
			add_action( 'admin_print_styles-profile.php', [ $this, 'admin_css' ] );
			add_action( 'wp_dashboard_setup', [ $this, 'filter_dashboard' ] );
			add_filter( 'pre_option_default_pingback_flag', '__return_zero' );
		} 
		else {
			add_action( 'template_redirect', [ $this, 'check_comment_template' ] );
			add_filter( 'comments_open', '__return_false', 20 );
			add_filter( 'pings_open', '__return_false', 20 );

			add_filter( 'post_comments_feed_link', '__return_false' );
			add_filter( 'comments_link_feed', '__return_false' );
			add_filter( 'comment_link', '__return_false' );

			add_filter( 'get_comments_number', '__return_false' );

			add_filter( 'feed_links_show_comments_feed', '__return_false' );
		}
	}

	public function check_comment_template() {
		if ( is_singular() ) {
			add_filter( 'comments_template', '__return_empty_string', 20 );
			wp_deregister_script( 'comment-reply' );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		}
	}

	public function filter_wp_headers( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}

	public function filter_query() {
		if ( is_comment_feed() ) {
			wp_die( esc_html__( 'Comments are closed.' ), '', [ 'response' => 403 ] );
		}
	}

	public function filter_admin_bar() {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		if ( is_multisite() ) {
			add_action( 'admin_bar_menu', [ $this, 'remove_network_comment_links' ], 500 );
		}
	}

	public function remove_network_comment_links( $wp_admin_bar ) {
		if ( is_user_logged_in() ) {
			foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {
				$wp_admin_bar->remove_menu( 'blog-' . $blog->userblog_id . '-c' );
			}
		}
	}

	public function filter_admin_menu() {
		global $pagenow;

		if ( in_array( $pagenow, [ 'comment.php', 'edit-comments.php', 'options-discussion.php' ], true ) ) {
			wp_die( esc_html__( 'Comments are closed.' ), '', [ 'response' => 403 ] );
		}

		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}

	public function filter_dashboard() {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	public function admin_css() {
		?>
		<style>
			#dashboard_right_now .comment-count,
			#dashboard_right_now .comment-mod-count,
			#latest-comments,
			#welcome-panel .welcome-comments,
			.user-comment-shortcuts-wrap{
				display:none !important;
			}
		</style>
		<?php
	}

	public function disable_rc_widget() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}

	public function filter_gutenberg_blocks( $hook ) {
		add_action( 'admin_footer', [ $this, 'print_footer_scripts' ] );
	}

	public function print_footer_scripts() {
		?>
		<script>
			wp.domReady( () => {
				const blockType = 'core/latest-comments';
				if( wp.blocks && wp.data && wp.data.select( 'core/blocks' ).getBlockType( blockType ) ){
					wp.blocks.unregisterBlockType( blockType );
				}
			} );
		</script>
		<?php
	}

	public function filter_rest_endpoints( $endpoints ) {
		if ( isset( $endpoints['comments'] ) ) {
			unset( $endpoints['comments'] );
		}
		if ( isset( $endpoints['/wp/v2/comments'] ) ) {
			unset( $endpoints['/wp/v2/comments'] );
		}
		if ( isset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] ) ) {
			unset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] );
		}

		return $endpoints;
	}

	public function disable_xmlrc_comments( $methods ) {
		unset( $methods['wp.newComment'] );

		return $methods;
	}

	public function disable_rest_api_comments( $prepared_comment, $request ) {
		return new WP_Error( 'rest_comment_disabled', 'Commenting is disabled.', [ 'status' => 403 ] );
	}

}