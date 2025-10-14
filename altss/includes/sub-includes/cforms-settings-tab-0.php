<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$t_1 = $wpdb->prefix . 'altss_cform_sendings';
$t_2 = $wpdb->prefix . 'altss_cform_sendings_fields';

$page = isset( $_GET['p'] ) ? intval( $_GET['p'] ) : 1;

$to = 10;
$from = ( 1 < $page ) ? ( $to * ( $page -1 ) ) : 0;

$fs_count = count( $wpdb->get_results( "SELECT id FROM {$t_1} " ) );

$all_p = ceil( $fs_count / $to );

$cfs_res = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$t_1} ORDER BY create_time DESC LIMIT %d, %d", $from, $to ) );
$cfs_res_count = count( $cfs_res );
$pdata = 1 < $cfs_res_count ? $page : $page - 1;

$record_removed_id = get_transient( 'cfs_record_removed_id' );
$record_remove_error = get_transient( 'cfs_record_remove_error' );
            ?>
            <?php if( false !== $record_removed_id ){?>
                <div class="notice notice-warning is-dismissible" style="margin: 50px 0;">
                    <p><?php echo sprintf( 
                        wp_kses( 
                            /* translators: %d: search id */
                            __( 'Entry with <strong>ID: %d</strong> has been deleted!', "altss" ),
                            [ 'strong' => [] ]
                        ),
                        esc_attr( $record_removed_id ) ); ?>
                    </p>
                </div>
            <?php }
            elseif( false !== $record_remove_error ){?>
                <div class="notice notice-error is-dismissible" style="margin: 50px 0;">
                    <p><?php esc_html_e( 'WP nonce faled', "altss" ); ?></p>
                </div>
            <?php }
            delete_transient( 'cfs_record_removed_id' );

            if( $cfs_res ){
                /* translators: %1$d: search page, %2$d: search Total records count */?>
                <p><?php echo sprintf( esc_html__( 'Page: %1$d | Total records: %2$d', "altss" ), esc_attr( $page ), esc_attr( $fs_count ) ); ?></p>
                <table class="sendings-table">
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 15%"><?php esc_html_e( "Sending date", "altss" ); ?></th>
                    <th style="width: 20%"><?php esc_html_e( "Form title", "altss" ); ?></th>
                    <th style="width: 20%"><?php esc_html_e( "Sender", "altss" ); ?></th>
                    <th style="width: 10%"><?php esc_html_e( "e-mail", "altss" ); ?></th>
                    <th style="width: 20%"><?php esc_html_e( "Phone", "altss" ); ?></th>
                    <th style="width: 20%"><?php esc_html_e( "IP address", "altss" ); ?></th>
                    <th style="width: 10%"><?php esc_html_e( "Status", "altss" ); ?></th>
                </tr>
                <?php
            foreach( $cfs_res as $val ) {
                $fields_res = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$t_2} WHERE sending_id=%d", $val->id ) );
                $c_name = '--';
                $c_email = '--';
                $c_phone = '--';
                $f_name = '';
                $s_name = '';
                foreach ($fields_res as $value) {
                    if( 'fname' === $value->field ){
                        $f_name = $value->value;
                    }
                    else if( 'sname' === $value->field ){
                        $s_name = $value->value;
                    }
                    else if( 'email' === $value->field ){
                        $c_email = $value->value;
                    }
                    else if( 'phone' === $value->field ){
                        $c_phone = $value->value;
                    }
                }
                if( '' != $f_name ) $c_name = "{$f_name} {$s_name}";
                    ?>
                    <tr class="tr-color-<?php echo esc_attr( $val->form_id ); ?>">
                        <td><span class="fs-link" data-id="<?php echo esc_attr( $val->id ); ?>"><?php echo esc_html( $val->id ); ?></span></td>
                        <td><?php echo esc_html( Date( __( "Y-m-d H:i", "altss" ), $val->create_time ) ); ?></td>
                        <td><span class="fs-link" data-id="<?php echo esc_attr( $val->id ); ?>" data-p="<?php echo esc_attr( $pdata ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( "cfs_record_view" ) ); ?>"><?php echo esc_html( $val->form_title ); ?></span></td>
                        <td><?php echo esc_html( $c_name ); ?></td>
                        <td><?php echo esc_html( $c_email ); ?></td>
                        <td><?php echo esc_html( $c_phone ); ?></td>
                        <td><?php echo esc_html( $val->ip ); ?></td>
                        <td><?php echo esc_html( $val->status ); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </table>
                <?php
                $plink_ar = [];
                if( 1 < $all_p ){
                    for( $i = 1; $i < ( $all_p + 1 ); $i++ ){
                        if(  $page == $i ){
                            $plink_ar[] = $i;
                        }
                        else {
                            $plink_ar[] = '<a href="' . esc_url( add_query_arg( 'p', $i ) ) . '">' . $i . '</a>';
                        }
                        
                    }
                    echo '<div class="sendings-pagination">' . implode( " | ", $plink_ar ) . '</div>';
                }
        }
        else {
            ?>
            <div class=""><?php esc_html_e( "No messages found.", "altss" ); ?></div>
            <?php
        }

            
