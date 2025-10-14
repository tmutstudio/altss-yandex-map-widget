<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Duplicate Post Functional Class.
 */
final class ALTSS_Post_Duplicator {
	public static function init(){
		add_action( 'init', [ __CLASS__, 'hooks' ], 30 );
	}

	public static function hooks(){
        add_action( 'post_submitbox_misc_actions', [ __CLASS__, 'post_submitbox_misc_actions' ], 30 );
        add_action( 'wp_footer', [ __CLASS__, 'render_banner_html' ], 30 );
        add_filter( 'post_row_actions', [ __CLASS__, 'duplicate_post_link' ], 10, 2 );
        add_filter( 'page_row_actions', [ __CLASS__, 'duplicate_post_link' ], 10, 2 );
        add_action( 'admin_action_post_as_draft', [ __CLASS__, 'duplicate_post_as_draft' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_inline_script' ], 99 );
	}

    public static function post_submitbox_misc_actions( $post ) {
        if( 'auto-draft' === $post->post_status && empty( $post->post_title ) ) {
            return;
        }
        if( 'page' === $post->post_type ) {
            $link_title = __( 'Duplicate page', 'altss' );
        }
        else {
            $link_title = __( 'Duplicate post', 'altss' );
        }
        if (current_user_can('edit_posts')) {
            echo '<div class="misc-pub-section">
                    <span class="dashicons dashicons-admin-page"></span>
                    <a href="' . wp_nonce_url('admin.php?action=post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_post_nonce' ) . '"  rel="permalink" class="duplicate-post-link">' . esc_html( $link_title ) . '</a>
                </div>';
        }
    }

    
    public static function duplicate_post_link( $actions, $post ) {
        if( 'page' === $post->post_type ) {
            $link_title = __( 'Duplicate this page', 'altss' );
        }
        else {
            $link_title = __( 'Duplicate this post', 'altss' );
        }
        if (current_user_can('edit_posts')) {
            $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_post_nonce' ) . '" title="' . esc_html( $link_title ) . '" rel="permalink" class="duplicate-post-link">' . esc_html__( 'Duplicate', 'altss' ) . '</a>';
        }
        return $actions;
    }

    
    public static function duplicate_post_as_draft(){
        global $wpdb;
        if ( ! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'post_as_draft' == $_REQUEST['action'] ) ) ) {
            wp_die('No post to duplicate has been supplied!');
        }
    
        if ( ! isset( $_GET['duplicate_post_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_post_nonce'], basename( __FILE__ ) ) )
            return;
    
        $post_id = ( isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
        $post = get_post( $post_id );
    
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;
    
        if (isset( $post ) && $post != null) {
    
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status'    => $post->ping_status,
                'post_author'    => $new_post_author,
                'post_content'   => $post->post_content,
                'post_excerpt'   => $post->post_excerpt,
                'post_name'      => $post->post_name,
                'post_parent'    => $post->post_parent,
                'post_password'  => $post->post_password,
                'post_status'    => 'draft',
                'post_title'     => $post->post_title . ' ' .  __( '( Copy )', 'altss' ),
                'post_type'      => $post->post_type,
                'to_ping'        => $post->to_ping,
                'menu_order'     => $post->menu_order
            );
    
            $new_post_id = wp_insert_post( $args );
    
            $taxonomies = get_object_taxonomies($post->post_type);
            foreach ( $taxonomies as $taxonomy ) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }
    
            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if ( count( $post_meta_infos ) != 0 ) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ( $post_meta_infos as $meta_info ) {
                    $meta_key = $meta_info->meta_key;
                    if( $meta_key == '_wp_old_slug' ) continue;
                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
                $sql_query.= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);
            }
    
    
            wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
            exit;
        } 
        else {
            wp_die( 'Post creation failed, could not find original post: ' . $post_id );
        }
    }



    public static function enqueue_inline_script() {
        global $post;
        $screen = get_current_screen();
        if( $screen->base !== 'edit' && ( empty( $post ) || ( 'auto-draft' === $post->post_status && empty( $post->post_content ) && empty( $post->post_name ) ) ) ) {
            return;
        }
        $dpData = array(
                'duplicatePageLabel' => __( "Duplicate page", "altss" ),
                'duplicatePostLabel' => __( "Duplicate post", "altss" ),
                'duplicateConfirmTextPage' => __( "Are you sure you want to duplicate this page?", "altss" ),
                'duplicateConfirmTextPost' => __( "Are you sure you want to duplicate this post?", "altss" ),
        );
        if( $screen->base !== 'edit' ) {
            $is_page = false;
            if( 'page' === $post->post_type ) {
                $is_page = true;
            }
            $dpData['duplicatePostUrl'] = wp_nonce_url('admin.php?action=post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_post_nonce' );

            if( function_exists( 'use_block_editor_for_post' ) && use_block_editor_for_post( $post->ID ) ){
                $js = '

    (function(){
        var el = wp.element.createElement;
        var registerPlugin = wp.plugins.registerPlugin;
        var PluginPostStatusInfo = wp.editor.PluginPostStatusInfo;
        var ExternalLink = wp.components.ExternalLink;

        function openConfirm(event) {
            event.preventDefault();
            if(confirm(' . ( $is_page ? 'dpData.duplicateConfirmTextPage' : 'dpData.duplicateConfirmTextPost' ) . ')){
                var link = document.createElement("a");
                link.href = dpData.duplicatePostUrl;
                link.target = "_blank";
                link.rel = "permalink";
                link.click();
            }
        }

        function duplicatePostPlugin({}) {
            return el(
                PluginPostStatusInfo,
                {
                    className: "duplicate-post-link-over"
                },
                el(
                    ExternalLink,
                    {
                        href: "#",
                        children: ' . ( $is_page ? 'dpData.duplicatePageLabel' : 'dpData.duplicatePostLabel' ) . ',
                        onClick: openConfirm,
                    }
                )
            );
        }

        registerPlugin( "duplicate-post-as-draft", {
            render: duplicatePostPlugin
        } );
    } )(); 
            ';
                wp_add_inline_script( 'wp-edit-post', $js );
                wp_localize_script( 'wp-edit-post', 'dpData', $dpData );
            }

            if( ! function_exists( 'use_block_editor_for_post' ) || ! use_block_editor_for_post( $post->ID ) ){
                $js_to_classic = '
    (function($){
        $(".duplicate-post-link").on("click", function(event){
            event.preventDefault();
            if(confirm(' . ( $is_page ? 'dpData.duplicateConfirmTextPage' : 'dpData.duplicateConfirmTextPost' ) . ')){
                    window.open(dpData.duplicatePostUrl);
            }
        });
    })(jQuery);    
            '; 
            
                wp_add_inline_script( 'editor', $js_to_classic );
                wp_localize_script( 'editor', 'dpData', $dpData );
            }
        }
        else{
            $js_to_conf = '
    (function($){
        $(".duplicate-post-link").on("click", function(event){
            event.preventDefault();
            var href = $(this).attr("href");
            if(confirm(' . ( $screen->post_type === 'page' ? 'dpData.duplicateConfirmTextPage' : 'dpData.duplicateConfirmTextPost' ) . ')){
                var link = document.createElement("a");
                link.href = href;
                link.target = "_blank";
                link.rel = "permalink";
                link.click();
            }
        });
    })(jQuery);    
            '; 
            wp_add_inline_script( 'inline-edit-post', $js_to_conf );
            wp_localize_script( 'inline-edit-post', 'dpData', $dpData );
        }

    }


}

