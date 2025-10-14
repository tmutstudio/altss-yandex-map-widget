<?php
/* Alternative Site Settings Plugin
 * Reviews Table class
 * 
 * */

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ALTSS_Reviews_List_Table extends WP_List_Table {

    private $_wpdb;
		
    public function __construct( $args = array() ) {
        parent::__construct(
                array(
                    'singular' => 'review',
                    'plural'   => 'reviews',
                    'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
                )
                );
        $this->_wpdb = $this->get_wpdb();
        
    }
				
    private function get_wpdb(){
        global $wpdb;
        return $wpdb;
    }
    

                
    function verify_nonce(){
        if( isset( $_POST['_wpnonce'] ) ) return wp_verify_nonce( esc_attr( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ), 'bulk-' . $this->_args['plural'] );
        return false;
    }

    public function prepare_items() {
        $wp_pref = $this->_wpdb->prefix;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        if( isset( $_GET['view'] ) ){
            if( $_GET['view'] === 'show' ) $view_status = 1;
            else if( $_GET['view'] === 'hide' ) $view_status = 0;
            else if( $_GET['view'] === 'trash_bin' ) $view_status = 2;
        }
        else{
            $view_status = 'all';
        }

        if( isset( $_POST['_wpnonce'] ) && ! $this->verify_nonce() ) return;

        $where_part = ( isset( $_POST['s'] ) ? $this->_wpdb->prepare( " AND `review_text` LIKE '%s'", '%' . sanitize_text_field( $_POST['s'] ) . '%' ) : "" ). 
                        ( 'all' !== $view_status ? $this->_wpdb->prepare( " AND review_status=%d", $view_status ) : " AND review_status!='2'" );
        $total_items = count( $this->_wpdb->get_results( "SELECT review_id FROM {$wp_pref}altss_reviews
                WHERE 1". $where_part ) );
        $per_page = $this->get_items_per_page('reviews_per_page', 5);
        $this->set_pagination_args( array(
                'total_items' => $total_items,                  
                'per_page'    => $per_page                    
        ) );
        
        
        $orderby = ( isset( $_REQUEST['orderby'] ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'review_id' );
        $order = ( isset( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : 'DESC' );
        $from = ( isset( $_REQUEST['paged'] ) ? ( intval( $_REQUEST['paged'] ) - 1 ) * $per_page : 0 );
        
        $sql = $this->_wpdb->prepare( "SELECT review_id, review_text, review_response_text, review_author_name, review_create_date, review_rating, review_status FROM {$wp_pref}altss_reviews WHERE 1{$where_part} ORDER BY %s %s LIMIT %d, %d", $orderby, $order, $from, $per_page );
        
        $this->items = $this->_wpdb->get_results( $sql );
    }
		
		/**
		 * Output 'no users' message.
		 *
		 * @since 3.1.0
		 */
		public function no_items() {
			esc_html_e( "The list is empty.", "altss" );
		}
				

        function get_views(){
            $wp_pref = $this->_wpdb->prefix;
            $s_n = count( $this->_wpdb->get_results( "SELECT review_id FROM {$wp_pref}altss_reviews
            WHERE review_status='1'" ) );
            $h_n = count( $this->_wpdb->get_results( "SELECT review_id FROM {$wp_pref}altss_reviews
            WHERE review_status='0'" ) );
            $t_n = count( $this->_wpdb->get_results( "SELECT review_id FROM {$wp_pref}altss_reviews
            WHERE review_status='2'" ) );
            $views = array();
            $current = ( !empty($_REQUEST['view']) ? $_REQUEST['view'] : 'all');

            //All link
            $class = ($current == 'all' ? ' class="current"' :'');
            $all_url = remove_query_arg('view');
            $views['all'] = "<a href='{$all_url }' {$class} >" . esc_html__( "All reviews", "altss" ) ."</a> (" . ( $s_n + $h_n ) . ")";

            //Show link
            $show_url = add_query_arg('view','show');
            $class = ($current == 'show' ? ' class="current"' :'');
            $views['show'] = "<a href='{$show_url}' {$class} >" . esc_html__( "Only published", "altss" ) ."</a> ({$s_n})";

            //Hide link
            $hide_url = add_query_arg('view','hide');
            $class = ($current == 'hide' ? ' class="current"' :'');
            $views['hide'] = "<a href='{$hide_url}' {$class} >" . esc_html__( "Only hidden", "altss" ) ."</a> ({$h_n})";

            //Trash bin link
            $trash_bin_url = add_query_arg('view','trash_bin');
            $class = ($current == 'trash_bin' ? ' class="current"' :'');
            $views['trash_bin'] = "<a href='{$trash_bin_url}' {$class} >" . esc_html__( "Trash bin", "altss" ) ."</a> ({$t_n})";

            return $views;
        }                

        function get_bulk_actions() {
            if( 'trash_bin' === @$_REQUEST['view'] ){
                $actions = array(
                    'restore'    => esc_html__( "Restore", "altss" ),
                );
            }
            else{
                $actions = array(
                    'show'    => esc_html__( "Publish", "altss" ),
                    'hide'    => esc_html__( "Hide", "altss" ),
                    'trash'    => esc_html__( "To the trash bin", "altss" )
                );
            }
            return $actions;
        }

                
        public function search_box( $text, $input_id ) {
            if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
                    return;
            }

            $input_id = $input_id . '-search-input';

            if ( ! empty( $_REQUEST['orderby'] ) ) {
                    echo '<input type="hidden" name="orderby" value="' . esc_attr( sanitize_text_field( $_REQUEST['orderby'] ) ) . '" />';
            }
            if ( ! empty( $_REQUEST['order'] ) ) {
                    echo '<input type="hidden" name="order" value="' . esc_attr( sanitize_text_field( $_REQUEST['order'] ) ) . '" />';
            }
            if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
                    echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( sanitize_text_field( $_REQUEST['post_mime_type'] ) ) . '" />';
            }
            if ( ! empty( $_REQUEST['detached'] ) ) {
                    echo '<input type="hidden" name="detached" value="' . esc_attr( sanitize_text_field( $_REQUEST['detached'] ) ) . '" />';
            }
            ?>
        <p class="search-box">
        <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?>:</label>
        <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
                <?php submit_button( $text, '', 'sub-search', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
                <?php
        }
        
    /**
	 * Get a list of columns for the list table.
	 *
	 * @since 3.1.0
	 *
	 * @return string[] Array of column titles keyed by their column name.
	 */
	public function get_columns() {
		$c = array(
			'cb'                             => '<input type="checkbox" />',
			'review_id'                      => 'ID',
			'review_text'                    => esc_html__( "Review text", "altss" ),
            'review_author_name'             => esc_html__( "Author", "altss" ),
            'review_create_date'             => esc_html__( "Review date", "altss" ),
			'review_rating'                  => esc_html__( "Rating", "altss" ),
			'review_status'                  => esc_html__( "Status", "altss" ),
		);

		return $c;
	}


	/**
	 * Get a list of sortable columns for the list table.
	 *
	 * @since 3.1.0
	 *
	 * @return array Array of sortable columns.
	 */
	protected function get_sortable_columns() {
		$sortable_columns = array(
				'review_id'  => array('review_id',false),
				'review_create_date' => array('review_create_date',false),
				'review_rating' => array('review_rating',false),
				'review_status' => array('review_status',false),
		);
		return $sortable_columns;
	}
	
	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'review_id':
			case 'review_text':
			case 'review_author_name':
			case 'review_create_date':
			case 'review_rating':
			case 'review_status':
				return $item->$column_name;
			default:
				return print_r( $item, true ) ; 
		}
	}
        function hideshow_review( $item ){
            if( $item->review_status != 1 ){
                return '<span class="color-green" data-act="show" data-id="' . $item->review_id . '" data-nonce="' . wp_create_nonce( "review_public" ) . '">' . esc_html__( "Publish a review", "altss" ) . '</span>';
            }
            else{
                 return '<span class="color-brown" data-act="hide" data-id="' . $item->review_id . '" data-nonce="' . wp_create_nonce( "review_public" ) . '">' . esc_html__( "Hide review", "altss" ) . '</span>';
           }
        }
        function column_review_text( $item ) {
            $txttrim = altss_trim_words( $item->review_text, 20 );
            $morespan = $txttrim[1] ? '</span> (<span class="morespan" data-more="1"> . . . ' . esc_html__( "expand text", "altss" ) . '</span>)' : '';
            $shorttext = $txttrim[0];
            $fulltext = esc_html( $item->review_text );
            if( 2 != $item->review_status ){
                $actions = array(
                    'respond'  => sprintf( '<a href="?page=%s&action=%s&review_id=%s">' . ( '' !== $item->review_response_text ? esc_html__( "Edit answer", "altss" ) : esc_html__( "Reply to review", "altss" ) ) . '</a>', $_REQUEST['page'], 'respond', $item->review_id ),
                    'public'   => $this->hideshow_review( $item ),
                    'trash'    => '<span class="color-brown" data-act="trash" data-id="' . $item->review_id . '" data-nonce="' . wp_create_nonce( "review_trash" ) . '">' . esc_html__( "To the trash bin", "altss" ) . '</span>',
                );
            }
            else{
                $actions = array(
                    'restore'  => '<span class="color-brown" data-act="restore" data-id="' . $item->review_id . '" data-nonce="' . wp_create_nonce( "review_trash" ) . '">' . esc_html__( "Restore", "altss" ) . '</span>',
                    'delete'   => '<span class="color-brown" data-act="delete" data-id="' . $item->review_id . '" data-nonce="' . wp_create_nonce( "review_trash" ) . '">' . esc_html__( "Delete", "altss" ) . '</span>',
                );
            }
		
    		return sprintf('%1$s %2$s', sprintf( '<div class="review-row-title-text" data-id="' . $item->review_id .'" data-ftext="' . $fulltext .'"><div class="textdiv">' . $shorttext . '</div>' . $morespan . '</div>',
                            $_REQUEST['page'], 'respond', $item->review_id ), $this->row_actions( $actions ) );
	    }
	

	function column_review_status( $item ) {
		return ( $item->review_status == 1 ? '<span  class="color-green" id="review-status-' . $item->review_id . '">' . esc_html__( "published", "altss" ) . '</span>' : '<span class="color-brown" id="review-status-' . $item->review_id . '">' . esc_html__( "hidden", "altss" ) . '</span>' );
	}
	
	function column_review_rating( $item ) {
        $res = '';
        for( $i = 1; $i < 6; $i++ ){
            $class = $item->review_rating < $i ? 'altss-star-full' : 'altss-star-empty';
            $res .= '<span class="altss-star ' . $class . '"></span>';
        }
		return $res;
	}
	
	
	function column_cb( $item ) {
		return sprintf(
				'<input type="checkbox" name="provider[]" value="%s" />', $item->review_id
				);
	}
	
	
	
	/**
	 * Generate the list table rows.
	 *
	 * @since 3.1.0
	 */
	public function display_rows() {
		// Query the post counts for this page.
		foreach ( $this->items as $k => $p_variant ) {
			echo "\n\t";
            $this->single_row( $p_variant );
		}
	}

	
	function single_row( $item )
	{
		static $row_class = '';
		$row_class = $row_class == '' ? 'alternate ' : '';
		echo '<tr id="optional-upload-' . esc_attr( $item->review_id ) . '" class="' . esc_attr( $row_class ) . ($item->review_status == 0 ? 'nopublic-bg' : '') . '">';
		parent::single_row_columns( $item );
		echo '</tr>';
	}
	
	
	
	
	/**
	 * Gets the name of the default primary column.
	 *
	 * @since 4.3.0
	 *
	 * @return string Name of the default primary column, in this case, 'username'.
	 */
	protected function get_default_primary_column_name() {
		return 'id';
	}


} //class







