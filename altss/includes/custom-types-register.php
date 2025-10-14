<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'altss_custom_type_register' );

function altss_custom_type_register() {
    $custom_recs_data = get_option( "altss_settings_options_custom_recs" );
    $custom_recs_settings = get_option( "altss_settings_options_custom_recs_settings" );
    if( is_array( $custom_recs_data ) ){
        include ALTSITESET_INCLUDES_DIR.'/data-vars/custom-type-vars.php';
        $pos = 20;
        foreach( $custom_recs_data as $key => $val ){
            $c_type = $CUSTOM_TYPES[$key];
            $show_in_rest = isset( $custom_recs_settings[$key]['ggeditor'] ) ? true : false;
            $comments = isset( $custom_recs_settings[$key]['nocomments'] ) ? '' : 'comments';
            register_taxonomy( $c_type['cat_slug'], [ $key ], [
                'label'                 => $c_type['cat_label'], 
                'labels'                => array(
                    'name'              => $c_type['cat_labels']['name'],
                    'singular_name'     => $c_type['cat_labels']['singular_name'],
                    'search_items'      => $c_type['cat_labels']['search_items'],
                    'all_items'         => $c_type['cat_labels']['all_items'],
                    'parent_item'       => $c_type['cat_labels']['parent_item'],
                    'parent_item_colon' => $c_type['cat_labels']['parent_item_colon'],
                    'edit_item'         => $c_type['cat_labels']['edit_item'],
                    'update_item'       => $c_type['cat_labels']['update_item'],
                    'add_new_item'      => $c_type['cat_labels']['add_new_item'],
                    'new_item_name'     => $c_type['cat_labels']['new_item_name'],
                    'menu_name'         => $c_type['cat_labels']['menu_name'],
                    ),
                'description'           => $c_type['cat_description'], 
                'public'                => true,
                'show_in_nav_menus'     => true,
                'show_ui'               => true, 
                'show_tagcloud'         => false, 
                'hierarchical'          => true,
                'rewrite'               => array('slug'=>$c_type['cat_slug'], 'hierarchical'=>false, 'with_front'=>false, 'feed'=>false, 'paged'=>true ),
                'show_admin_column'     => true, 
            ] );

            register_taxonomy( $c_type['tag_slug'], $key,
                    array(
                            'hierarchical' => false,
                            'label' => esc_html__( 'Tags' , "altss" ),
                            'query_var' => $c_type['tag_slug'],
                            'rewrite' => array('slug' => $c_type['tag_slug'], 'paged'=>true ),
                            'show_ui' => true,
                    )
                    );
                
                
            register_post_type( $key, [
                'label'               => $c_type['label'],
                'labels'              => array(
                    'name'          => $c_type['labels']['name'],
                    'singular_name' => $c_type['labels']['singular_name'],
                    'menu_name'     => $c_type['labels']['menu_name'],
                    'all_items'     => $c_type['labels']['all_items'],
                    'add_new'       => $c_type['labels']['add_new'],
                    'add_new_item'  => $c_type['labels']['add_new_item'],
                    'edit'          => $c_type['labels']['edit'],
                    'edit_item'     => $c_type['labels']['edit_item'],
                    'new_item'      => $c_type['labels']['new_item'],
                    ),
                'description'         => $c_type['description'],
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_rest'        => $show_in_rest,
                'rest_base'           => '',
                'show_in_menu'        => true,
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'hierarchical'        => false,
                'rewrite'             => array( 'slug'=>$c_type['rewrite_slug'], 'with_front'=>false, 'pages'=>false, 'feeds'=>false, 'feed'=>false, 'paged'=>true ),
                'has_archive'         => $c_type['has_archive'],
                'query_var'           => true,
                'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', $comments ),
                'taxonomies'          => $c_type['taxonomies'],
                'menu_icon'           => $c_type['menu_icon'],
                'menu_position'       => $c_type['menu_position'],
            ] );
            $pos++;

        }
        

    }

}