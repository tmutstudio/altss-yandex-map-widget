<?php
/* Alternative Site Settings Plugin
 * Installer class
 * 
 * */

class ALTSS_Installer {
	private $wpdb;
    private $charset;
    private $collate;

    public function __construct()
    {
        global $wpdb;
        $charset = defined( 'DB_CHARSET' ) ? DB_CHARSET : 'utf8mb4';
        if ( '' != $charset ) {
            $this->charset = 'utf8mb4' === DB_CHARSET ? 'utf8mb4 COLLATE=utf8mb4_unicode_520_ci' : DB_CHARSET;
            $this->collate = 'utf8mb4' === DB_CHARSET ? 'COLLATE utf8mb4_unicode_520_ci' : '';
        }
        else {
            $this->charset = 'utf8mb4 COLLATE=utf8mb4_unicode_520_ci';
            $this->collate = 'COLLATE utf8mb4_unicode_520_ci';
        }
        
        $this->wpdb = $wpdb;
        $this->createTables();
    }
			

			
			
			
    private function createTables()
        {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $sql = "CREATE TABLE IF NOT EXISTS `{$this->wpdb->prefix}altss_cform_sendings` 
            ( 
                    `id` INT NOT NULL AUTO_INCREMENT ,
                    `form_id` INT NOT NULL ,
                    `form_title` VARCHAR( 255 ) ,
                    `create_time` INT(12) NOT NULL ,
                    `ip` VARCHAR( 255 ) ,
                    `user_agent` TEXT NOT NULL ,
                    `status` INT NOT NULL ,
                        PRIMARY KEY (`id`)
            ) ENGINE = InnoDB DEFAULT CHARSET={$this->charset};";
            //$this->wpdb->query( $sql );
            dbDelta( $sql );

        
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->wpdb->prefix}altss_cform_sendings_fields` 
            ( 
                    `sending_id` INT NOT NULL ,
                    `field` VARCHAR( 255 ) ,
                    `value` TEXT NOT NULL ,
                    `position` INT NOT NULL 
            ) ENGINE = InnoDB DEFAULT CHARSET={$this->charset};";
            //$this->wpdb->query( $sql );
            dbDelta( $sql );

            $sql = "CREATE TABLE IF NOT EXISTS `{$this->wpdb->prefix}altss_reviews` 
            ( 
                    `review_id` INT NOT NULL AUTO_INCREMENT ,
                    `review_text` TEXT {$this->collate} NOT NULL ,
                    `review_response_text` TEXT {$this->collate} NOT NULL ,
                    `review_response_author` INT NOT NULL ,
                    `review_author_name` VARCHAR( 255 ) ,
                    `review_author_location` VARCHAR( 255 ) ,
                    `review_author_email` VARCHAR( 255 ) ,
                    `review_author_ip` VARCHAR( 255 ) ,
                    `review_author_ua` TEXT {$this->collate} NOT NULL ,
                    `review_create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
                    `review_create_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
                    `review_edit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
                    `review_response_create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
                    `review_response_edit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
                    `review_status` INT NOT NULL ,
                    `review_rating` INT NOT NULL ,
                    `review_user_id` INT NOT NULL ,
                        PRIMARY KEY (`review_id`)
            ) ENGINE = InnoDB DEFAULT CHARSET={$this->charset};";
            //$this->wpdb->query( $sql );
            dbDelta( $sql );
				
        }
			
			
			
			
			
}