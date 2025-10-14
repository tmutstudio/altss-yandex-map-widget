<?php
if ( ! defined( 'ABSPATH' ) ) exit;

settings_fields( 'altss_settings_options' );
altss_include_uploadscript();
$altss_settings_options = get_option( "altss_settings_options" );

                        ?>
                        <div class="site-settings-tab-header-div"><?php esc_html_e( "Main site settings:", "altss" ); ?></div>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( "Info for the HEADER section:", "altss" ); ?></p>
                            <dl>
                                <dt><p><?php echo esc_html__( "Site Title" ); ?>:</p></dt>
                                <dd>
                                    <p>
                                        <input type="text" name="blogname" size="45" value="<?php echo esc_attr( get_option( "blogname" ) ); ?>">
                                    </p>
                                </dd>
                                <dt><p><?php echo esc_html__( "Tagline" ); ?>:</p></dt>
                                <dd>
                                    <p>
                                        <textarea name="blogdescription" size="45" rows="1"><?php echo esc_textarea( get_option( "blogdescription" ) ); ?></textarea>
                                    </p>
                                </dd>
                                <dt><p><?php esc_html_e( "Alternative Site Title:", "altss" ); ?></p></dt>
                                <dd>
                                    <p>
                                        <input type="text" name="altss_settings_options[alt_blogname]" size="45" value="<?php echo esc_attr( $altss_settings_options['alt_blogname'] ?? '' ); ?>">
                                    </p>
                                </dd>
                                <dt><p><?php esc_html_e( "Additional field for the HEADER section:", "altss" ); ?></p></dt>
                                <dd>
                                    <p>
                                        <input type="text" name="altss_settings_options[opt_header_text]" size="45" value="<?php echo esc_attr( $altss_settings_options['opt_header_text'] ?? '' ); ?>">
                                    </p>
                                </dd>
                                <dt><p><?php esc_html_e( "Additional text for HEADER", "altss" ); ?>:</p></dt>
                                <dd>
                                        <?php altss_add_editior_field( "altss_settings_options[opt_header_textarea]", wp_kses_post( $altss_settings_options['opt_header_textarea'] ?? '' ), 4, 'minimal'); ?>
                                </dd>
                                <dt><p><?php esc_html_e( "Button name for HEADER on the home page", "altss" ); ?>:</p></dt>
                                <dd>
                                    <p>
                                        <input type="text" name="altss_settings_options[header_btn_text]" size="45" value="<?php echo esc_attr( $altss_settings_options['header_btn_text'] ?? '' ); ?>">
                                    </p>
                                </dd>
                                <dt><p><?php esc_html_e( "Form for a button in the HEADER on the home page", "altss" ); ?>:</p></dt>
                                <dd>
                                    <select name="altss_settings_options[header_form_id]">
                                        <option value="0"><?php esc_html_e( "Select form", "altss" ); ?></option>
                                    <?php foreach ( $form_title_ar as $key => $value ) {
                                        ?>
                                        <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $altss_settings_options['header_form_id'] ?? '' ); ?>><?php echo esc_html__( "form", "altss" ) . ": # " . esc_attr( $key ) . " - «" . esc_html( $value ) . "»"; ?></option>
                                        <?php
                                    }
                                    ?>
                                    </select>
                                </dd>
                            </dl>
                            <p class="section-hint">
                                <?php echo wp_kses( __( "To display additional fields in the “HEADER” section of the frontend, use the <strong>altss_header_option_field( 'opt_header_text' )</strong> or <strong>altss_header_option_field( 'alt_blogname' )</strong> function.", "altss" ),
                                [ 'strong' => [] ] ); ?>
                            </p>
                        </div>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( "Additionally:", "altss" ); ?></p>
                            <dl>
                                <dt><p><?php esc_html_e( "Post duplication functionality", "altss" ); ?>:</p></dt>
                                <dd>
                                    <?php altss_add_onoff_switch( 'altss_settings_options[duplicate_post_enable]', 1, $altss_settings_options['duplicate_post_enable'] ?? '', __( "check the box to enable the duplicate post functionality", "altss" ) ); ?>
                                </dd>
                                <dt><p><?php esc_html_e( "Collapse the admin bar on the front end", "altss" ); ?>:</p></dt>
                                <dd>
                                    <?php altss_add_onoff_switch( 'altss_settings_options[collapse_admin_bar]', 1, $altss_settings_options['collapse_admin_bar'] ?? '', __( "check the box to collapse the admin bar on the front end to the upper left corner", "altss" ) ); ?>
                                </dd>
                                <dt><p><?php esc_html_e( "Disable absolutely all comments on the site", "altss" ); ?>:</p></dt>
                                <dd>
                                    <?php altss_add_onoff_switch( 'altss_settings_options[disable_all_comments]', 1, $altss_settings_options['disable_all_comments'] ?? '', __( "check the box to disable absolutely all comments on the site", "altss" ) ); ?>
                                </dd>
                                <dt><p><?php esc_html_e( "Disable Reviews on the site", "altss" ); ?>:</p></dt>
                                <dd>
                                    <?php altss_add_onoff_switch( 'altss_settings_options[disable_reviews]', 1, $altss_settings_options['disable_reviews'] ?? '', __( "check the box to disable Reviews page", "altss" ) ); ?>
                                </dd>
                                <dt><p><?php esc_html_e( "Blog Title", "altss" ); ?>:</p></dt>
                                <dd>
                                    <p>
                                        <input type="text" name="altss_settings_options[blog_title]" size="45" value="<?php echo esc_attr( $altss_settings_options['blog_title'] ?? '' ); ?>">
                                    </p>
                                </dd>
                                <dt><p><?php esc_html_e( "Additional text for FOOTER", "altss" ); ?>:</p></dt>
                                <dd>
                                        <?php altss_add_editior_field( "altss_settings_options[footer_textarea]", wp_kses_post( $altss_settings_options['footer_textarea'] ?? '' ), 4, 'minimal'); ?>
                                </dd>
                                <dt><p><?php esc_html_e( "Text for the button in the footer of the site", "altss" ); ?>:</p></dt>
                                <dd>
                                    <p>
                                        <input type="text" name="altss_settings_options[footer_btn_text]" size="45" value="<?php echo esc_attr( $altss_settings_options['footer_btn_text'] ?? '' ); ?>">
                                    </p>
                                </dd>
                                <dt><p><?php esc_html_e( "Form for a button in the FOOTER of the site", "altss" ); ?>:</p></dt>
                                <dd>
                                    <select name="altss_settings_options[footer_form_id]">
                                        <option value="0"><?php esc_html_e( "Select form", "altss" ); ?></option>
                                    <?php foreach ( $form_title_ar as $key => $value ) {
                                        ?>
                                        <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $altss_settings_options['footer_form_id'] ?? '' ); ?>><?php echo esc_html__( "form", "altss" ) . ": # " . esc_attr( $key ) . " - «" . esc_attr( $value ) . "»"; ?></option>
                                        <?php
                                    }
                                    ?>
                                    </select>
                                </dd>
                                <dd style="padding-top: 20px;">
                                    <div class="options-seo-and-meta-over" style="padding-top: 20px;">
                                        <?php altss_add_onoff_switch( 'altss_settings_options[enable_footer_section]', 1, $altss_settings_options['enable_footer_section'] ?? '', __( "check the box to anable display a footer section", "altss" ), 'footer-section' ); ?>
                                        <dl id="footer-section-area" <?php echo ( ! isset( $altss_settings_options['enable_footer_section'] ) ? 'style="display: none;"' : "" ); ?>>
                                            <dt><p><?php esc_html_e( "Menu ID (for block theme)", "altss" ); ?>:</p></dt>
                                            <dd>
                                                <p>
                                                    <input type="number" name="altss_settings_options[block_menu_id]" min="0" value="<?php echo esc_attr( $altss_settings_options['block_menu_id'] ?? '' ); ?>">
                                                </p>
                                            </dd>
                                            <dd>
                                                <div class="to-two-columns" style="justify-content: flex-start;">
                                                    <div>
                                                        <p class="site-settings-item-title"><?php esc_html_e( 'Footer section text color', 'altss' ); ?>:</p>
                                                        <p class="section-hint">
                                                            <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="#FFFFFF"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                            <strong>#FFFFFF</strong>
                                                        </p>
                                                        <input name="altss_settings_options[footer_section_styles][color]" type="text" value="<?php echo esc_attr( $altss_settings_options['footer_section_styles']['color'] ?? '#FFFFFF' ); ?>" class="iris_color">
                                                        <p class="site-settings-item-title"><?php esc_html_e( 'Footer section background color', 'altss' ); ?>:</p>
                                                        <p class="section-hint">
                                                            <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="#919191"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                            <strong>#919191</strong>
                                                        </p>
                                                        <input name="altss_settings_options[footer_section_styles][bgcolor]" type="text" value="<?php echo esc_attr( $altss_settings_options['footer_section_styles']['bgcolor'] ?? '#919191' ); ?>" class="iris_color">
                                                    </div>
                                                    <div>
                                                        <p class="site-settings-item-title"><?php esc_html_e( 'Footer section link color', 'altss' ); ?>:</p>
                                                        <p class="section-hint">
                                                            <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="#FFFFFF"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                            <strong>#FFFFFF</strong>
                                                        </p>
                                                        <input name="altss_settings_options[footer_section_styles][link_color]" type="text" value="<?php echo esc_attr( $altss_settings_options['footer_section_styles']['link_color'] ?? '#FFFFFF' ); ?>" class="iris_color">
                                                        <p class="site-settings-item-title"><?php esc_html_e( 'Footer section link hover color', 'altss' ); ?>:</p>
                                                        <p class="section-hint">
                                                            <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="#FBFF00"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                            <strong>#FBFF00</strong>
                                                        </p>
                                                        <input name="altss_settings_options[footer_section_styles][link_hov_color]" type="text" value="<?php echo esc_attr( $altss_settings_options['footer_section_styles']['link_hov_color'] ?? '#FBFF00' ); ?>" class="iris_color">
                                                    </div>
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                        <?php
                            submit_button();
                        ?>
                            <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( "Contact details", "altss" ); ?>:</p>
                            <dl>
                                <dt><p><?php esc_html_e( "Contact block title:", "altss" ); ?></p></dt>
                                <dd>
                                    <input name="altss_settings_options[contacts][contacts_title]" type="text" value="<?php echo esc_attr( $altss_settings_options['contacts']['contacts_title'] ?? '' ); ?>">
                                </dd>
                                <dt><p><?php esc_html_e( "Address", "altss" ); ?>:</p></dt>
                                <dd style="max-width: 500px">
                                    <?php altss_add_editior_field( "altss_settings_options[contacts][contacts_location]", esc_textarea( $altss_settings_options['contacts']['contacts_location'] ?? '' ), 5, 'novisual'  ); ?>
                                </dd>
                                <dt><p><?php esc_html_e( 'Phone number in the site header:', 'altss' ); ?></p></dt>
                                <dd>
                                    <p>
                                        <input type="text" name="altss_settings_options[header_phone]" size="45" value="<?php echo esc_attr( $altss_settings_options['header_phone'] ?? '' ); ?>">
                                    </p>
                                </dd>
                                <dt><p><?php esc_html_e( 'Phone number in the site footer:', 'altss' ); ?></p></dt>
                                <dd>
                                    <p class="section-hint"><?php esc_html_e( 'Perhaps several separated by «;»', 'altss' ); ?><br><?php esc_html_e( "If left blank, the phone number will be substituted for the site header.", "altss" ); ?></p>
                                    <input type="text" name="altss_settings_options[footer_phone]" size="45" value="<?php echo esc_attr( $altss_settings_options['footer_phone'] ?? '' ); ?>">
                                </dd>
                                <dt><p><?php esc_html_e( 'Phone number for contact block:', 'altss' ); ?></p></dt>
                                <dd>
                                    <p class="section-hint"><?php esc_html_e( 'Perhaps several separated by «;»', 'altss' ); ?><br><?php esc_html_e( "If left blank, the phone number will be substituted for the site header.", "altss" ); ?></p>
                                    <input name="altss_settings_options[contacts][contacts_phone]" type="text" value="<?php echo esc_attr( $altss_settings_options['contacts']['contacts_phone'] ?? '' ); ?>">
                                </dd>
                                <dt><p>Whatsapp:</p></dt>
                                <dd>
                                    <p class="section-hint"><?php esc_html_e( 'Perhaps several separated by «;»', 'altss' ); ?></p>
                                    <input name="altss_settings_options[contacts][contacts_whatsapp]" type="text" value="<?php echo esc_attr( $altss_settings_options['contacts']['contacts_whatsapp'] ?? '' ); ?>">
                                </dd>
                                <dt><p>Telegram:</p></dt>
                                <dd>
                                    <p class="section-hint"><?php esc_html_e( 'Perhaps several separated by «;»', 'altss' ); ?></p>
                                    <input name="altss_settings_options[contacts][contacts_telegram]" type="text" value="<?php echo esc_attr( $altss_settings_options['contacts']['contacts_telegram'] ?? '' ); ?>">
                                </dd>
                                <dt><p>Emali:</p></dt>
                                <dd>
                                    <input name="altss_settings_options[contacts][contacts_email]" type="text" value="<?php echo esc_attr( $altss_settings_options['contacts']['contacts_email'] ?? '' ); ?>">
                                </dd>
                            </dl>
                            </div>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( 'Data for displaying the map', 'altss' ); ?>:</p>
                            <?php 
                            $altss_map_settings_show = true;
                            $altss_map_settings_show = apply_filters( 'altss_map_settings_section_show', $altss_map_settings_show ); 
                            ?>
                            <dl <?php echo ( ! $altss_map_settings_show ? 'style="display: none;"' : "" ); ?>>
                                <dt><p><?php esc_html_e( 'Map display type', 'altss' ); ?>:</p></dt>
                                <dd>
                                    <div class="customize-control-radio">
                                        <label><input type="radio" name="altss_settings_options[map_display_type]" value="shortcode"<?php checked( $altss_settings_options['map_display_type'] ?? '', "shortcode" ); ?>> <span><?php esc_html_e( 'Shortcode from the map plugin ( e.g. Very Simple Google Maps )', 'altss' ); ?></span></label>
                                        <label><input type="radio" name="altss_settings_options[map_display_type]" value="static_image"<?php checked( $altss_settings_options['map_display_type'] ?? '', "static_image" ); ?>> <span><?php esc_html_e( 'Static image', 'altss' ); ?></span></label>
                                    </div>
                                </dd>
                                <dt class="map-shortcode-field" <?php echo ( 'shortcode' !== @$altss_settings_options['map_display_type'] ? 'style="display: none;"' : "" ); ?>><p><?php esc_html_e( 'Shortcode', 'altss' ); ?>:</p></dt>
                                <dd class="map-shortcode-field" <?php echo ( 'shortcode' !== @$altss_settings_options['map_display_type'] ? 'style="display: none;"' : "" ); ?>>
                                    <textarea name="altss_settings_options[map_shortcode]" class="code2"><?php echo esc_textarea( $altss_settings_options['map_shortcode'] ?? '' ); ?></textarea>
                                </dd>
                                <dt class="map-static-image" <?php echo ( 'static_image' !== @$altss_settings_options['map_display_type'] ? 'style="display: none;"' : "" ); ?>><?php esc_html_e( 'Image', 'altss' ); ?></dt>
                                <dd class="map-static-image" <?php echo ( 'static_image' !== @$altss_settings_options['map_display_type'] ? 'style="display: none;"' : "" ); ?>>
                                    <p><?php esc_html_e( 'Optimal resolution 800x600 pixels', 'altss' ); ?></p>
                                    <?php 
                                        altss_image_uploader_field( 'altss_settings_options[map_static_image]', esc_url( $altss_settings_options['map_static_image'] ?? '' ) );
                                    ?>
                                    <p>
                                        <label><?php esc_html_e( 'Link for Image', 'altss' ); ?>:</label><br>
                                        <input type="text" name="altss_settings_options[map_static_image_link]" size="45" value="<?php echo esc_attr( $altss_settings_options['map_static_image_link'] ?? '' ); ?>">
                                    </p>
                                    <p>
                                        <label><?php esc_html_e( 'Title attribute for the link', 'altss' ); ?>:</label><br>
                                        <input type="text" name="altss_settings_options[map_static_image_link_title]" size="45" value="<?php echo esc_attr( $altss_settings_options['map_static_image_link_title'] ?? '' ); ?>">
                                    </p>
                                </dd>
                            </dl>
                            <?php
                            do_action( 'altss_admin_after_map_settings_section', $altss_settings_options ); ?>
                        </div>
                        <?php
                            submit_button();
                        ?>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( 'Meta & SEO', 'altss' ); ?>:</p>
                            <?php
                            ?>
                            <div class="options-seo-and-meta-over">
                                <dl>
                                    <dd>
                                        <?php altss_add_onoff_switch( 'altss_settings_options[seo_meta_enabled]', 1, $altss_settings_options['seo_meta_enabled'] ?? '', __( "check the box to enable META &amp; SEO functionality", "altss" ), "seo-meta" ); ?>
                                    </dd>
                                </dl>
                                <dl id="seo-meta-area" <?php echo ( ! isset( $altss_settings_options['seo_meta_enabled'] ) ? 'style="display: none;"' : "" ); ?>>
                                    <dt><p><?php esc_html_e( 'Meta Tag «Title» for the home page', 'altss' ); ?>:</p></dt>
                                    <dd>
                                        <p>
                                            <input type="text" name="altss_settings_options[home_title]" size="45" value="<?php echo esc_attr( $altss_settings_options['home_title'] ?? '' ); ?>">
                                        </p>
                                    </dd>
                                    <dt><p><?php esc_html_e( 'Meta Tag «Description» for the home page', 'altss' ); ?>:</p></dt>
                                    <dd>
                                        <p>
                                            <textarea name="altss_settings_options[home_desc]" rows="3" cols="110"><?php echo esc_textarea( $altss_settings_options['home_desc'] ?? '' ); ?></textarea>
                                        </p>
                                    </dd>
                                    <dt><?php esc_html_e( 'og:image', 'altss' ); ?></dt>
                                    <dd>
                                        <p><?php esc_html_e( 'Optimal resolution 600x315 pixels', 'altss' ); ?></p>
                                        <?php 
                                            altss_image_uploader_field( 'altss_settings_options[meta_ogimage]', esc_url( $altss_settings_options['meta_ogimage'] ?? '' ) );
                                        ?>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( 'Copyright in the footer', 'altss' ); ?>:</p>
                            <?php
                            $copyright_info = get_option( 'copyright_info' );
                            ?>
                            <dl>
                                    <dd>
                                        <p>
                                            <input name="copyright_info[start_year]" type="number" size="4" min="1990" max="2099" value="<?php echo esc_attr( $copyright_info['start_year'] ?? '' ); ?>"> - 
                                            <label><?php esc_html_e( 'Year, start of activity', 'altss' ); ?></label>
                                        </p>
                                        <p>
                                            <input name="copyright_info[holder_text]" type="text" value="<?php echo esc_attr( $copyright_info['holder_text'] ?? '' ); ?>"> - 
                                            <label><?php esc_html_e( 'Copyright holder name', 'altss' ); ?></label>
                                        </p>
                                        <p>
                                            <input name="copyright_info[optional_text]" type="text" value="<?php echo esc_attr( $copyright_info['optional_text'] ?? '' ); ?>"> - 
                                            <label><?php esc_html_e( 'Additional text (if necessary)', 'altss' ); ?></label>
                                        </p>
                                    </dd>
                            </dl>
                        </div>
                        <div class="site-settings-options-gr-wrap options-analytics">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( 'Analytics', 'altss' ); ?>:</p>
                            <dl>
                                <dt><p><?php esc_html_e( 'Google tag (gtag.js)', 'altss' ); ?>:</p></dt>
                                <dd>
                                    <?php altss_add_onoff_switch( 'altss_settings_options[google_tag_enabled]', 1, $altss_settings_options['google_tag_enabled'] ?? '', __( "check the box to enable Google tag", "altss" ), "gtag" ); ?>
                                </dd>
                                <dd id="gtag-area" <?php echo ( ! isset( $altss_settings_options['google_tag_enabled'] ) ? 'style="display: none;"' : "" ); ?>>
                                    <p style="margin-bottom: 4px;"><?php esc_html_e( 'Google tag ID', 'altss' ); ?>:</p>
                                    <input type="text" name="altss_settings_options[google_tag_id]" size="45" value="<?php echo esc_attr( $altss_settings_options['google_tag_id'] ?? '' ); ?>">
                                    <p class="section-hint" style="margin-top: 4px;"><?php esc_html_e( "If left blank, the script will not load.", "altss" ); ?></p>
                                    <p style="margin: 20px 0 4px; 0"><?php esc_html_e( 'Method of blocking a script', 'altss' ); ?>:</p>
                                    <div class="customize-control-radio">
                                        <label><input type="radio" name="altss_settings_options[google_tag_blocking]" value="not_load"<?php checked( $altss_settings_options['google_tag_blocking'] ?? 'not_load', "not_load" ); ?>> <span><?php esc_html_e( 'The script doesn&#039;t load at all.', 'altss' ); ?></span></label>
                                        <label><input type="radio" name="altss_settings_options[google_tag_blocking]" value="js"<?php checked( $altss_settings_options['google_tag_blocking'] ?? '', "js" ); ?>> <span><?php esc_html_e( 'The script is controlled by JavaScript methods.', 'altss' ); ?></span></label>
                                    </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt style="margin-top: 20px;"><p><?php esc_html_e( 'Yandex.Metrika', 'altss' ); ?>:</p></dt>
                                <dd>
                                    <?php altss_add_onoff_switch( 'altss_settings_options[yandex_metrika_enabled]', 1, $altss_settings_options['yandex_metrika_enabled'] ?? '', __( "check the box to enable Yandex.Metrika", "altss" ), "ym" ); ?>
                                </dd>
                                <dd id="ym-area" <?php echo ( ! isset( $altss_settings_options['yandex_metrika_enabled'] ) ? 'style="display: none;"' : "" ); ?>>
                                    <p style="margin-bottom: 4px;"><?php esc_html_e( 'Yandex.Metrika ID', 'altss' ); ?>:</p>
                                    <input type="text" name="altss_settings_options[yandex_metrika_id]" size="45" value="<?php echo esc_attr( $altss_settings_options['yandex_metrika_id'] ?? 0 ); ?>">
                                    <p class="section-hint" style="margin-top: 4px;"><?php esc_html_e( "If left blank, the script will not load.", "altss" ); ?></p>
                                    <p>
                                        <label>
                                        <input type="checkbox" id="" name="altss_settings_options[yandex_metrika_webvisor]" value="1"<?php checked( $altss_settings_options['yandex_metrika_webvisor'] ?? 0, 1); ?> />
                                        -  <?php esc_html_e( 'Yandex.Metrica webvisor is enabled', 'altss' ); ?>
                                        </label>
                                    </p>
                                    <p style="margin: 20px 0 4px; 0"><?php esc_html_e( 'Method of blocking a script', 'altss' ); ?>:</p>
                                    <div class="customize-control-radio">
                                        <label><input type="radio" name="altss_settings_options[yandex_metrika_blocking]" value="not_load"<?php checked( $altss_settings_options['yandex_metrika_blocking'] ?? 'not_load', "not_load" ); ?>> <span><?php esc_html_e( 'The script doesn&#039;t load at all.', 'altss' ); ?></span></label>
                                        <label><input type="radio" name="altss_settings_options[yandex_metrika_blocking]" value="js"<?php checked( $altss_settings_options['yandex_metrika_blocking'] ?? '', "js" ); ?>> <span><?php esc_html_e( 'The script is controlled by JavaScript methods.', 'altss' ); ?></span></label>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( 'Privacy Policy', 'altss' ); ?>:</p>
                            <dl>
                                <dt><p style="margin-bottom: 4px;"><?php esc_html_e( 'Page containing the text of the privacy policy', 'altss' ); ?>:</p></dt>
                                <dd style="margin-bottom: 20px;">
                                    <label for="altss_settings_options_privacy_policy_page">
                                        <?php
                                        $privacy_policy_page = $altss_settings_options['privacy_policy_page'] ?? ( get_option( 'altss_settings_cforms_privacy_policy_page' ) ?? 0 );//For compatibility
                                        printf(
                                            /* translators: %s: Select field to choose the page for posts. */
                                            esc_html__( "Page: %s", "altss"  ),
                                            wp_dropdown_pages(
                                                array(
                                                    'name'              => 'altss_settings_options[privacy_policy_page]',
                                                    'id'                => 'altss_settings_options_privacy_policy_page',
                                                    'echo'              => 0,
                                                    'show_option_none'  => __( '&mdash; Select &mdash;' ),
                                                    'option_none_value' => '0',
                                                    'selected'          => $privacy_policy_page,
                                                )
                                            )
                                        );
                                        ?>
                                    </label>
                                </dd>
                                <dt><p style="margin-bottom: 4px;"><?php esc_html_e( 'Page containing the text of the Cookie Policy', 'altss' ); ?>:</p></dt>
                                <dd>
                                    <label for="altss_settings_options_cookie_policy">
                                        <?php
                                        $cookie_policy_page = $altss_settings_options['cookie_policy'] ?? 0;
                                        printf(
                                            /* translators: %s: Select field to choose the page for posts. */
                                            esc_html__( "Page: %s", "altss"  ),
                                            wp_dropdown_pages(
                                                array(
                                                    'name'              => 'altss_settings_options[cookie_policy]',
                                                    'id'                => 'altss_settings_options_cookie_policy',
                                                    'echo'              => 0,
                                                    'show_option_none'  => __( '&mdash; Select &mdash;' ),
                                                    'option_none_value' => '0',
                                                    'selected'          => $cookie_policy_page,
                                                )
                                            )
                                        );
                                        ?>
                                    </label>
                                </dd>
                                <dt><p><?php esc_html_e( "Activate Cookie Banner", "altss" ); ?>:</p></dt>
                                <dd>
                                    <?php altss_add_onoff_switch( 'altss_settings_options[cookie_banner_on]', 1, $altss_settings_options['cookie_banner_on'] ?? '', __( "check the box to activate the work of the cookie banner", "altss" ) ); ?>
                                </dd>
                            </dl>
                        </div>
                        <?php
                            submit_button();
                        ?>
    