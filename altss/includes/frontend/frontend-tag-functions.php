<?php
if ( ! defined( 'ABSPATH' ) ) exit;



function altss_email_to_script( $email = '' ) {
    $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
    $letters = substr( str_shuffle( $permitted_chars ), 0, 3 );

    if( !empty( $email ) && preg_match( "/@/", $email ) ){
        $parts = explode( "@", $email );
        echo '<script>var aeml' . $letters . ',beml' . $letters . ',apart' . $letters . '; aeml' . $letters . '="' . $parts[0] . '"; beml' . $letters . '="' . $parts[1] . '"; apart' . $letters . '="<a href=\""; document.write( apart' . $letters . ' + "mailto:" + aeml' . $letters . ' + "@" + beml' . $letters . ' + "\"><span class=\"dashicons dashicons-email-alt\"></span> "  + aeml' . $letters . ' + "@" + beml' . $letters . ' + "</a>");</script>';
    }
    else {
        echo '';
    }
}

function altss_the_copyright() {
    $o = get_option( "copyright_info" );
    ?>
        <div id="copyright-site-info">
            &copy <?php echo $o['start_year']; ?> — <?php echo date('Y'); ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( htmlentities( $o['holder_text'] ) ); ?>" rel="home">
            <?php echo htmlentities( $o['holder_text'] ); ?>
            </a>
        </div>
        <div id="copyright-site-info-optional">
            <?php echo $o['optional_text']; ?>
        </div>
    <?php
}

function altss_phone_to_link_wi( $phone, $print = false ) {
    $return = '<a href="tel:' . preg_replace( "/[^\d\+]/", "", $phone ) . '"><span class="dashicons dashicons-phone"></span> <span class="">' . $phone . '</span></a>';
    if( $print ){
        echo $return;
    }
    else {
        return $return;
    }
}



function altss_the_contact_phone_numbers( $data = '' ) {
    $g_phone_number = get_option( "general_phone_number" );
    if( ! empty( $data ) ){
        if( preg_match( "/;/", $data ) ){
            $tel_ar = explode( ";", $data );
            foreach  ($tel_ar as $val ) {
                $tel = trim( $val );
                echo '<div class="section-contacts-text-phone">'."\n";
                echo altss_phone_to_link_wi( $tel );
                echo "</div>\n";
            }

        }
        else {
            echo '<div class="section-contacts-text-phone">'."\n";
            echo altss_phone_to_link_wi( $data );
            echo "</div>\n";
        }
    }
    else{
        echo '<div class="section-contacts-text-phone">'."\n";
        echo altss_phone_to_link_wi( $g_phone_number );
        echo "</div>\n";
    }
}

function altss_the_contact_whatsapp( $data = '' ) {
    if( ! empty( $data ) ){
        if( preg_match( "/;/", $data ) ){
            $tel_ar = explode( ";", $data );
            foreach  ($tel_ar as $val ) {
                $tel = trim( $val );
                echo '<div class="section-contacts-text-whatsapp">'."\n";
                echo '<a href="https://wa.me/' . preg_replace( "/[^\d]/", "", $tel ) . '" class=""><span class="dashicons dashicons-whatsapp"></span> ' . $tel . '</a>';
                echo "</div>\n";
            }

        }
        else {
            echo '<div class="section-contacts-text-whatsapp">'."\n";
            echo '<a href="https://wa.me/' . preg_replace( "/[^\d]/", "", $data ) . '" class=""><span class="dashicons dashicons-whatsapp"></span> ' . $data . '</a>';
            echo "</div>\n";
        }
    }
    else{
        echo '';
    }
}


function altss_the__contact_telegram( $data = '' ) {
    if( ! empty( $data ) ){
        $svg_icon = '<svg width="24" height="24" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 8L5 12.5L9.5 14M18 8L9.5 14M18 8L14 18.5L9.5 14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        if( preg_match( "/;/", $data ) ){
            $tel_ar = explode( ";", $data );
            foreach  ($tel_ar as $val ) {
                $tel = trim( $val );
                echo '<div class="section-contacts-text-telegram">'."\n";
                echo '<a href="https://t.me/' . preg_replace( "/[^a-z\d@\+_]/", "", $tel ) . '" class="">' . $svg_icon . ' ' . $tel . '</a>';
                echo "</div>\n";
            }

        }
        else {
            echo '<div class="section-contacts-text-telegram">'."\n";
            echo '<a href="https://t.me/' . preg_replace( "/[^a-z\d@\+_]/", "", $data ) . '" class="">' . $svg_icon . ' ' . $data . '</a>';
            echo "</div>\n";
        }
    }
    else{
        echo '';
    }
}



function altss_the_contacts_block(){
        global $ALTSS_GLOBAL_VARS;
        $settings_options = $ALTSS_GLOBAL_VARS['altss_settings_options'];
        ?>
                <div id="contacts" class="section-contacts-text-over">
                    <?php if( ! empty( $settings_options['contacts']['contacts_title'] ) ){?>
                    <div class="section-contacts-text-title"><?php echo $settings_options['contacts']['contacts_title'] ?? ''; ?></div>
                    <?php } ?>
                    <?php altss_the_contact_phone_numbers( $settings_options['contacts']['contacts_phone'] ?? '' ); ?>
                    <?php altss_the_contact_whatsapp( $settings_options['contacts']['contacts_whatsapp'] ?? '' ); ?>
                    <?php altss_the__contact_telegram( $settings_options['contacts']['contacts_telegram'] ?? '' ); ?>
                    <div class="section-contacts-text-email"><?php altss_email_to_script( $settings_options['contacts']['contacts_email'] ?? '' ); ?></div>
                    <div class="section-contacts-text-lacation"><?php echo $settings_options['contacts']['contacts_location'] ?? ''; ?></div>
                </div>
                <div class="section-contacts-yamap-over">
                    <?php altss_the_contact_section_map();?>
                </div>
       <?php         
}



function altss_the_footer_section(){
        global $ALTSS_GLOBAL_VARS;
        $settings_options = $ALTSS_GLOBAL_VARS['altss_settings_options'];
        if( ! isset( $settings_options['enable_footer_section'] ) ) {
            return;
        }

        ?>
<footer id="footer-section" class="footer-section">
    <div class="footer-section-blocks-over">
        <div class="footer-section-first">
            <div class="footer-section-block-logo"> 
                <div class="footer-section-logo">
                    <?php the_custom_logo(); ?>
                </div>
                <div class="footer-section-bloginfo">
                    <a href="/"><?php bloginfo(); ?></a>
                </div>
            </div>
            <?php echo do_blocks( '<!-- wp:navigation {"ref":85} /-->' ); ?>
            <?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
            <nav aria-label="<?php esc_attr_e( 'Secondary menu', 'tama' ); ?>" class="footer-navigation">
                <ul class="footer-navigation-wrapper">
                    <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'footer-menu',
                                    'items_wrap'     => '%3$s',
                                    'container'      => false,
                                    'depth'          => 1,
                                    'link_before'    => '<span>',
                                    'link_after'     => '</span>',
                                    'fallback_cb'    => false,
                                )
                            );
                            ?>
                </ul><!-- .footer-navigation-wrapper -->
            </nav><!-- .footer-navigation -->
            <?php endif; ?>
            <?php if ( ! empty( $settings_options['footer_btn_text'] ) ) : ?>
                <div class="footer-button-over">
                    <button id="footer-form-button" class="footer-button"><?php echo altss_sanitize_text( $settings_options['footer_btn_text'] ); ?></button>
                </div>
            <?php endif; ?>
            <div class="footer-site-info">
                <?php altss_the_copyright();?>
            </div><!-- .site-info -->
            <div class="footer-optional-text"><?php echo  altss_sanitize_textarea( $settings_options['footer_textarea'] ); ?></div>
            <div class="footer-section-counters">
            </div>
        </div>
        <div class="footer-section-second">
            <div class="footer-section-block-logo-mobile">
                <div class="footer-section-logo">
                    <?php the_custom_logo(); ?>
                </div>
                <div class="footer-section-bloginfo">
                    <a href="/"><?php bloginfo(); ?></a>
                </div>
            </div>
            <?php altss_the_contacts_block(); ?>
        </div>
    </div>
</footer>
        <?php

}

