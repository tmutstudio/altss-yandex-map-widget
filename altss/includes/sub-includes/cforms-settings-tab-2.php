<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (current_user_can('manage_options')) {
    settings_fields('altss_settings_cforms_options_' . $tab); 
    $allowed_strong_html = array(
        'strong' => array()
        );

    foreach ($FORM_FIELDS as $key => $val) {
        $fieldsSettings = get_option("altss_settings_cforms_options_field_{$key}");
        ?>
            <div class="cf-fields-item">
                <dl>
                    <dt><?php esc_html_e( "Field", "altss" ); ?>: <span><?php echo esc_html( $val['label'] ); ?></span></dt>
                    <dd>
                        <label><strong><?php esc_html_e( "Field label", "altss" ); ?>:</strong></label>
                        <input type="text" name="altss_settings_cforms_options_field_<?php echo esc_attr( $key );?>[label]"
                        value="<?php echo esc_attr( ('' != @$fieldsSettings['label'] ? $fieldsSettings['label'] : $val['label']) );?>" data-dv="<?php echo esc_attr( $val['label'] ); ?>" />
                    </dd>
                    <dd>
                        <label><?php echo wp_kses( __( "<strong>Placeholder</strong>", "altss" ), $allowed_strong_html ); ?>:</label>
                        <input type="text" name="altss_settings_cforms_options_field_<?php echo esc_attr( $key );?>[placeholder]"
                        value="<?php echo esc_attr( ('' != @$fieldsSettings['placeholder'] ? $fieldsSettings['placeholder'] : $val['placeholder']) );?>" data-dv="<?php echo esc_attr( $val['placeholder'] ); ?>" />
                    </dd>
                </dl>
            </div>
            <?php
            submit_button();
    }
    ?>
    <div class="">
        <span id="fields-reset"><?php esc_html_e( "Reset fields", "altss" ); ?></span>
    </div>
                <?php
}
else {
    ?>
    <div class="">
        <?php esc_html_e( "This tab is intended for the site administrator!", "altss" ); ?>
    </div>
    <?php
}
        