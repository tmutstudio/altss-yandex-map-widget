<?php
if ( ! defined( 'ABSPATH' ) ) exit;


settings_fields( 'altss_settings_options_2' ); 
$cookie_banner_settings = get_option( 'altss_settings_options_cookie_banner_settings' );
extract( include ALTSITESET_INCLUDES_DIR . '/data-vars/cookie-banner-data.php' );
$altss_settings_options = get_option( "altss_settings_options" );

if( ! isset( $altss_settings_options['cookie_banner_on'] ) ) {
    wp_admin_notice(
            esc_html__( 'At the moment, cookie banner is not active!', 'altss' ),
            [
                'type'               => 'warning',
                'id'                 => 'cookie-banner-disabled',
            ]
        );
}
                        ?>
                        <div class="site-settings-tab-header-div">
                            <?php echo wp_kses_post( __( 'Cookie Banner Settings', 'altss' ) ); ?>
                        </div>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( 'Text information', 'altss' ); ?>:</p>
                            <dl>
                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Banner Title Text', 'altss' ); ?>:</p></dt>
                                <dd>
                                    <p class="section-hint">
                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default text', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_texts['banner_title_text'] ); ?>"><?php esc_html_e( 'Default text', 'altss' ); ?></span>:
                                        <strong><?php echo esc_html( $default_texts['banner_title_text'] ); ?></strong>
                                    </p>
                                    <input type="text" name="altss_settings_options_cookie_banner_settings[banner_title_text]" size="45" placeholder="<?php echo esc_attr( $default_texts['banner_title_text'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['banner_title_text'] ?? '' ); ?>">
                                </dd>
                                <dt><p class="site-settings-item-title"><?php esc_html_e( "Contents of the text block on the banner", "altss" ); ?></p></dt>
                                <dd>
                                    <p class="section-hint" style="margin: 0;">
                                        <?php esc_html_e( 'Default text', 'altss' ); ?>:
                                    </p>
                                    <div class="section-hint">
                                        <?php echo wp_kses_post( $default_texts['banner_text'] ); ?>
                                    </div>
                                    <p style="margin: 0 0 4px 0">
                                        <span class="set-to-default-value to-editor" title="<?php esc_attr_e( 'inset default text into Editor field', 'altss' ); ?>" data-type="tinymce"><span class="dashicons dashicons-edit"></span> - <?php esc_html_e( 'inset default text into Editor field', 'altss' ); ?></span>
                                    </p>
                                    <?php altss_add_editior_field("altss_settings_options_cookie_banner_settings[banner_text]", wp_unslash( $cookie_banner_settings['banner_text'] ?? '' ), 3, 'minimal' ); ?>
                                </dd>
                            <?php foreach ( $button_settings_labels as $btn_key => $btn_data ) { ?>
                                <dt><p class="site-settings-item-title"><?php echo esc_html( $btn_data['text'] ); ?>:</p></dt>
                                <dd>
                                    <p class="section-hint">
                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default text', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_texts[$btn_key . '_txt'] ); ?>"><?php esc_html_e( 'Default text', 'altss' ); ?></span>:
                                        <strong><?php echo esc_html( $default_texts[$btn_key . '_txt'] ); ?></strong>
                                    </p>
                                    <input type="text" name="altss_settings_options_cookie_banner_settings[<?php echo esc_attr( $btn_key ); ?>_txt]" size="45" placeholder="<?php echo esc_attr( $default_texts[$btn_key . '_txt'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings[$btn_key . '_txt'] ?? '' ); ?>">
                                </dd>
                            <?php } ?>
                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Settings dialog box title text', 'altss' ); ?>:</p></dt>
                                <dd>
                                    <p class="section-hint">
                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default text', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_texts['customize_title_text'] ); ?>"><?php esc_html_e( 'Default text', 'altss' ); ?></span>:
                                        <strong><?php echo esc_html( $default_texts['customize_title_text'] ); ?></strong>
                                    </p>
                                    <input type="text" name="altss_settings_options_cookie_banner_settings[customize_title_text]" size="45" placeholder="<?php echo esc_attr( $default_texts['customize_title_text'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['customize_title_text'] ?? '' ); ?>">
                                </dd>
                                <dt><p class="site-settings-item-title"><?php esc_html_e( "Contents of the text block in the settings dialog box", "altss" ); ?></p></dt>
                                <dd>
                                    <p class="section-hint" style="margin: 0;">
                                        <?php esc_html_e( 'Default text', 'altss' ); ?>:
                                    </p>
                                    <div class="section-hint">
                                        <?php echo wp_kses_post( $default_texts['customize_intro_text'] ); ?>
                                    </div>
                                    <p style="margin: 0 0 4px 0">
                                        <span class="set-to-default-value to-editor" title="<?php esc_attr_e( 'inset default text into Editor field', 'altss' ); ?>" data-type="tinymce"><span class="dashicons dashicons-edit"></span> - <?php esc_html_e( 'inset default text into Editor field', 'altss' ); ?></span>
                                    </p>
                                    <?php altss_add_editior_field("altss_settings_options_cookie_banner_settings[customize_intro_text]", wp_unslash( $cookie_banner_settings['customize_intro_text'] ?? '' ), 3, 'minimal' ); ?>
                                </dd>
                                <dt><p class="site-settings-item-title"><?php esc_html_e( "Setting cookie categories", "altss" ); ?></p></dt>
                                <dd>
                                    <div class="cookie-items-area">
                                        <div class="cookie-item">
                                            <p class="cookie-item-title"><?php echo esc_html( $cookie_select_list['tech'] ); ?>:</p>
                                            <div id="tech_ctg_note_text_over">
                                                <p style="margin-bottom: 4px;"><?php esc_html_e( "Contents of the note text", "altss" ); ?></p>
                                                <p class="section-hint" style="margin: 0;">
                                                    <?php esc_html_e( 'Default text', 'altss' ); ?>:
                                                </p>
                                                <div class="section-hint">
                                                    <?php echo esc_html( $default_texts['tech_ctg_note_text'] ); ?>
                                                </div>
                                                <p style="margin: 0 0 4px 0">
                                                    <span class="set-to-default-value to-editor" title="<?php esc_attr_e( 'inset default text into Editor field', 'altss' ); ?>" data-type="novisual"><span class="dashicons dashicons-edit"></span> - <?php esc_html_e( 'inset default text into Editor field', 'altss' ); ?></span>
                                                </p>
                                                <?php altss_add_editior_field("altss_settings_options_cookie_banner_settings[tech_ctg_note_text]", wp_unslash( $cookie_banner_settings['tech_ctg_note_text'] ?? '' ), 3, 'novisual'); ?>
                                            </div>
                                        </div>
                                        <?php foreach ( $cookie_select_list as $key => $val ) { 
                                            if( 'tech' === $key ) continue;?>
                                        <div class="cookie-item">
                                            <p class="cookie-item-title"><?php echo esc_html( $val ); ?>:</p>
                                            <div class="onoffswitch-over">
                                                <div class="onoffswitch-left">
                                                    <input type="checkbox" id="altss_settings_options_cookie_banner_settings_items_<?php echo esc_attr( $key ); ?>" name="altss_settings_options_cookie_banner_settings[items][<?php echo esc_attr( $key ); ?>]" data-item="<?php echo esc_attr( $key ); ?>" class="onoffswitch-checkbox" value="1"<?php checked( $cookie_banner_settings['items'][$key] ?? '', 1); ?> />
                                                    <label class="onoffswitch-label" for="altss_settings_options_cookie_banner_settings_items_<?php echo esc_attr( $key ); ?>"></label>
                                                </div>
                                                <label class="onoffswitch-label-text" for="altss_settings_options_cookie_banner_settings_items_<?php echo esc_attr( $key ); ?>">-  <?php esc_html_e( 'Enable cookie category', 'altss' ); ?></label>
                                            </div>
                                            <div id="<?php echo esc_attr( $key ); ?>_ctg_note_text_over" <?php  echo ( isset( $cookie_banner_settings['items'][$key] ) ? '' : 'style="display: none;"'); ?>>
                                                <p style="margin-bottom: 4px;"><?php esc_html_e( "Contents of the note text", "altss" ); ?></p>
                                                <p class="section-hint" style="margin: 0;">
                                                    <?php esc_html_e( 'Default text', 'altss' ); ?>:
                                                </p>
                                                <div class="section-hint">
                                                    <?php echo esc_html( $default_texts[$key . '_ctg_note_text'] ); ?>
                                                </div>
                                                <p style="margin: 0 0 4px 0">
                                                    <span class="set-to-default-value to-editor" title="<?php esc_attr_e( 'inset default text into Editor field', 'altss' ); ?>" data-type="novisual"><span class="dashicons dashicons-edit"></span> - <?php esc_html_e( 'inset default text into Editor field', 'altss' ); ?></span>
                                                </p>
                                                <?php altss_add_editior_field("altss_settings_options_cookie_banner_settings[{$key}_ctg_note_text]", wp_unslash( $cookie_banner_settings[$key . '_ctg_note_text'] ?? '' ), 3, 'novisual'); ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                        <?php
                            submit_button();
                        ?>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( 'Style settings', 'altss' ); ?>:</p>
                            <div class="to-two-columns">
                                <dl>
                                    <dd style="display: grid; margin: 0; row-gap: 10px;">
                                        <div>
                                            <dl>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Banner position', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['banner_position'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $banner_position_list[$default_values['banner_position']] ); ?></strong>
                                                    </p>
                                                    <select name="altss_settings_options_cookie_banner_settings[banner_position]">
                                                        <option value="" <?php selected( $cookie_banner_settings['banner_position'] ?? "", "" ); ?>><?php esc_html_e( 'Select position', 'altss' ); ?></option>
                                                        <?php foreach( $banner_position_list as $key => $val ) { ?>
                                                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $cookie_banner_settings['banner_position'] ?? "", $key ); ?>><?php echo esc_html( $val); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Banner width in px', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['banner_width'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['banner_width'] ); ?></strong><br>
                                                        <?php esc_html_e( 'For desktop display version only', 'altss' ); ?><br>
                                                        <?php esc_html_e( "Doesn&#039;t work in full width mode", 'altss' ); ?>
                                                    </p>
                                                    <input type="number" name="altss_settings_options_cookie_banner_settings[banner_width]" min="0" placeholder="<?php echo esc_attr( $default_values['banner_width'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['banner_width'] ?? 0 ); ?>">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Inner vertical padding from banner borders in px', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['banner_v_padding'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['banner_v_padding'] ); ?></strong>
                                                    </p>
                                                    <input type="number" name="altss_settings_options_cookie_banner_settings[banner_v_padding]" min="0" max="100" placeholder="<?php echo esc_attr( $default_values['banner_v_padding'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['banner_v_padding'] ?? 0 ); ?>">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Inner horizontal padding from banner borders in px', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['banner_h_padding'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['banner_h_padding'] ); ?></strong><br>
                                                        <?php esc_html_e( 'For desktop display version only', 'altss' ); ?>                                            
                                                    </p>
                                                    <input type="number" name="altss_settings_options_cookie_banner_settings[banner_h_padding]" min="0" max="100" placeholder="<?php echo esc_attr( $default_values['banner_h_padding'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['banner_h_padding'] ?? 0 ); ?>">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Banner border radius in px', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['banner_border_radius'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['banner_border_radius'] ); ?></strong><br>
                                                        <?php esc_html_e( 'For desktop display version only', 'altss' ); ?><br>
                                                        <?php esc_html_e( "Doesn&#039;t work in full width mode", 'altss' ); ?>
                                                    </p>
                                                    <input type="number" name="altss_settings_options_cookie_banner_settings[banner_border_radius]" min="0" max="100" placeholder="<?php echo esc_attr( $default_values['banner_border_radius'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['banner_border_radius'] ?? 0 ); ?>">
                                                </dd>
                                            </dl>
                                        </div>
                                        <div>
                                            <dl class="banner-colors-set-item">                                    
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Banner background color', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['banner_bgcolor'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['banner_bgcolor'] ); ?></strong>
                                                    </p>
                                                    <input name="altss_settings_options_cookie_banner_settings[banner_bgcolor]" type="text" value="<?php echo esc_attr( $cookie_banner_settings['banner_bgcolor'] ?? $default_values['banner_bgcolor'] ); ?>" class="iris_color">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Banner title color', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['banner_title_color'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['banner_title_color'] ); ?></strong>
                                                    </p>
                                                    <input name="altss_settings_options_cookie_banner_settings[banner_title_color]" type="text" value="<?php echo esc_attr( $cookie_banner_settings['banner_title_color'] ?? $default_values['banner_title_color'] ); ?>" class="iris_color">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Banner text color', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['banner_txt_color'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['banner_txt_color'] ); ?></strong>
                                                    </p>
                                                    <input name="altss_settings_options_cookie_banner_settings[banner_txt_color]" type="text" value="<?php echo esc_attr( $cookie_banner_settings['banner_txt_color'] ?? $default_values['banner_txt_color'] ); ?>" class="iris_color">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Customize dialog box title color', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['customize_title_color'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['customize_title_color'] ); ?></strong>
                                                    </p>
                                                    <input name="altss_settings_options_cookie_banner_settings[customize_title_color]" type="text" value="<?php echo esc_attr( $cookie_banner_settings['customize_title_color'] ?? $default_values['customize_title_color'] ); ?>" class="iris_color">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Color of the text block in the Customize dialog box', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['customize_txt_color'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['customize_txt_color'] ); ?></strong>
                                                    </p>
                                                    <input name="altss_settings_options_cookie_banner_settings[customize_txt_color]" type="text" value="<?php echo esc_attr( $cookie_banner_settings['customize_txt_color'] ?? $default_values['customize_txt_color'] ); ?>" class="iris_color">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Active switch color', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['active_switch_color'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['active_switch_color'] ); ?></strong>
                                                    </p>
                                                    <input name="altss_settings_options_cookie_banner_settings[active_switch_color]" type="text" value="<?php echo esc_attr( $cookie_banner_settings['active_switch_color'] ?? $default_values['active_switch_color'] ); ?>" class="iris_color">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Active switch border color', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['active_switch_border_color'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['active_switch_border_color'] ); ?></strong>
                                                    </p>
                                                    <input name="altss_settings_options_cookie_banner_settings[active_switch_border_color]" type="text" value="<?php echo esc_attr( $cookie_banner_settings['active_switch_border_color'] ?? $default_values['active_switch_border_color'] ); ?>" class="iris_color">
                                                </dd>
                                            </dl>
                                        </div>
                                        <div><button type="button" data-item="banner-colors" class="restore-to-default-btn"><?php esc_html_e( 'restore default colors', 'altss' ); ?></button></div>
                                    </dd>
                                </dl>
                                <dl>
                                    <dd style="display: grid; margin: 0; row-gap: 10px;">
                                        <div>
                                            <dl>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Buttons border width in px', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['btn_border_width'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['btn_border_width'] ); ?></strong>
                                                    </p>
                                                    <input type="number" name="altss_settings_options_cookie_banner_settings[btn_border_width]" min="0" max="100" placeholder="<?php echo esc_attr( $default_values['btn_border_width'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['btn_border_width'] ?? '' ); ?>">
                                                </dd>
                                                <dt><p class="site-settings-item-title"><?php esc_html_e( 'Buttons border radius in px', 'altss' ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['btn_border_radius'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                                        <strong><?php echo esc_html( $default_values['btn_border_radius'] ); ?></strong>
                                                    </p>
                                                    <input type="number" name="altss_settings_options_cookie_banner_settings[btn_border_radius]" min="0" max="100" placeholder="<?php echo esc_attr( $default_values['btn_border_radius'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['btn_border_radius'] ?? '' ); ?>">
                                                </dd>
                                            </dl>
                                        </div>
                                        <?php foreach ( $button_settings_labels as $btn_key => $btn_data ) { ?>
                                        <div>
                                            <dl class="button-colors-set-item">                                    
                                            <?php foreach ( $btn_data as $key => $label ) { 
                                                if( 'text' === $key ) continue; ?> 
                                                <dt><p class="site-settings-item-title"><?php echo esc_html( $label ); ?>:</p></dt>
                                                <dd>
                                                    <p class="section-hint">
                                                        <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_attr( $default_values['default_btn_' . $key] ); ?>"><span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def=""><?php esc_html_e( 'Default value', 'altss' ); ?></span></span>:
                                                        <strong><?php echo esc_html( $default_values['default_btn_' . $key] ); ?></strong>
                                                    </p>
                                                    <input name="altss_settings_options_cookie_banner_settings[<?php echo esc_attr( $btn_key ); ?>_<?php echo esc_attr( $key ); ?>]" type="text" value="<?php echo esc_attr( $cookie_banner_settings[$btn_key . '_' . $key] ?? $default_values['default_btn_' . $key] ); ?>" class="iris_color">
                                                </dd>
                                            <?php } ?>
                                            </dl>
                                        </div>
                                        <?php } ?>
                                        <div><button type="button" data-item="button-colors" class="restore-to-default-btn"><?php esc_html_e( 'restore button colors to default', 'altss' ); ?></button></div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="site-settings-options-gr-wrap">
                            <p class="site-settings-options-gr-title"><?php esc_html_e( 'Additional settings', 'altss' ); ?>:</p>
                                <dl>
                                    <dt><p class="site-settings-item-title"><?php esc_html_e( 'Delay time before displaying the banner in seconds', 'altss' ); ?>:</p></dt>
                                    <dd>
                                        <p class="section-hint">
                                            <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_html( $default_values['banner_delay_time'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                            <strong><?php echo esc_html( $default_values['banner_delay_time'] ); ?></strong>
                                        </p>
                                        <input type="number" name="altss_settings_options_cookie_banner_settings[banner_delay_time]" min="0" max="10" placeholder="<?php echo esc_attr( $default_values['banner_delay_time'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['banner_delay_time'] ?? '' ); ?>">
                                    </dd>
                                    <dt><p class="site-settings-item-title"><?php esc_html_e( 'Number of days to store the cookie banner state', 'altss' ); ?>:</p></dt>
                                    <dd>
                                        <p class="section-hint">
                                            <span class="set-to-default-value" title="<?php esc_attr_e( 'Set default value', 'altss' ); ?>" data-def="<?php echo esc_html( $default_values['cookie_consent_days'] ); ?>"><?php esc_html_e( 'Default value', 'altss' ); ?></span>:
                                            <strong><?php echo esc_html( $default_values['cookie_consent_days'] ); ?></strong>
                                        </p>
                                        <input type="number" name="altss_settings_options_cookie_banner_settings[cookie_consent_days]" min="0" max="100" placeholder="<?php echo esc_attr( $default_values['cookie_consent_days'] ); ?>" value="<?php echo esc_attr( $cookie_banner_settings['cookie_consent_days'] ?? '' ); ?>">
                                    </dd>
                                </dl>
                        </div>
                        <?php
                            submit_button();
                        ?>
    