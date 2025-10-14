<?php
if ( ! defined( 'ABSPATH' ) ) exit;

settings_fields( 'altss_settings_cforms_options_' . $tab );     

                        ?>
                    <div class="cfitms-top-btn-over">
                        <div class="cfitms-sliddown-button"><?php esc_html_e( "expand all", "altss" ); ?></div>
                        <div class="cfitms-slidup-button"><?php esc_html_e( "collapse all", "altss" ); ?></div>
                    </div>
                    <div class="site-settings-cform-all-items-over">

                    <?php
                    
    for ($i = 1; $i < ( ALTSITESET_CFORMS_AMOUNT + 1 ); $i++) {
        $formTitle = "altss_settings_cforms_options_title_{$i}";
        $formTitleShow = "altss_settings_cforms_options_titleshow_{$i}";
        $formDesc = "altss_settings_cforms_options_desc_{$i}";
        $formDescShow = "altss_settings_cforms_options_descshow_{$i}";
        $formFields = "altss_settings_cforms_options_fields_{$i}";
        $formReqFields = "altss_settings_cforms_options_reqfields_{$i}";
        $formFirstEmail = "altss_settings_cforms_options_firstemail_{$i}";
        $formSecondEmail = "altss_settings_cforms_options_secondemail_{$i}";
        $formSubmitBtnText = "altss_settings_cforms_options_submitbtntext_{$i}";
    
        $$formTitle = get_option($formTitle);
        $$formTitleShow = get_option($formTitleShow);
        $$formDesc = get_option($formDesc);
        $$formDescShow = get_option($formDescShow);
        $$formFields = get_option($formFields);
        $$formReqFields = get_option($formReqFields);
        $$formFirstEmail = get_option($formFirstEmail);
        $$formSecondEmail = get_option($formSecondEmail);
        $$formSubmitBtnText = get_option($formSubmitBtnText);
        if ( current_user_can('manage_options')) {
            ?>
                    <div class="site-settings-cform-item-over">
                        <div class="site-settings-cform-item-title" title="<?php esc_html_e( "expand", "altss" ); ?>">
                            <span class="dashicons dashicons-insert cfitms-toggle" data-key="<?php echo esc_attr( $i ); ?>"></span>
                            <span><?php esc_html_e( "Form", "altss" ); ?></span> <span class="section-number">#<?php echo esc_html( $i ); ?></span> <span>( <?php echo esc_html( $$formTitle ); ?> )</span>
                        </div>
                        <div class="site-settings-cform-item-wrapp" id="cform-item-<?php echo esc_attr( $i ); ?>">
                            <div class="site-settings-cform-setfield">
                                <label><?php esc_html_e( "Form title", "altss" );?>:</label>
                                <input type="text" value="<?php echo esc_attr( $$formTitle );?>" name="<?php echo esc_attr( $formTitle );?>" />
                            </div>
                            <div class="site-settings-cform-setfield">
                                <label><?php esc_html_e( "Form description", "altss" );?>:</label>
                                <?php altss_add_editior_field($formDesc, wp_unslash($$formDesc), 3); ?>
                            </div>
                            <div class="site-settings-cform-fields-title"><?php esc_html_e( "Set of form fields", "altss" );?>: <span class="site-settings-cform-fields-select" data-area="<?php echo esc_attr( $i ); ?>"><span class="dashicons dashicons-forms"></span> -- <span><?php esc_html_e( "select", "altss" );?></span></span></div>
                            <ul class="site-settings-cform-fields-area" id="cform_ul_<?php echo esc_attr( $i ); ?>">
                                <?php
                                   if (is_array($$formFields)) {
                                       foreach ($$formFields as $key) {
                                           ?>
                                        <li class="form-area-field" data-key="<?php echo esc_attr( $key );?>">
                                            <input type="hidden" name="altss_settings_cforms_options_fields_<?php echo esc_attr( $i );?>[]" value="<?php echo esc_attr( $key );?>"/>
                                            <div> 
                                                <label><?php echo esc_html( $FORM_FIELDS[$key]['label'] );?></label>
                                                <input type="checkbox" id="f<?php echo esc_attr( $i );?>_cb_<?php echo esc_attr( $key );?>"
                                                name="altss_settings_cforms_options_reqfields_<?php echo esc_attr( $i );?>[<?php echo esc_attr( $key );?>]" value="1" title="<?php esc_attr_e( "make required", "altss" );?>"
                                                <?php echo(isset($$formReqFields[$key]) ? " checked" : ""); ?> />
                                            </div>
                                        </li>
                                        <?php
                                       }
                                   }
            ?>
                            </ul>
                            <div class="site-settings-cform-setfield">
                                <label><?php esc_html_e( "Recipient's main email", "altss" );?>:</label>
                                <input type="email" value="<?php echo esc_attr( $$formFirstEmail );?>" name="<?php echo esc_attr( $formFirstEmail );?>" />
                            </div>
                            <div class="site-settings-cform-setfield">
                                <label><?php esc_html_e( "Additional recipient email", "altss" );?>:</label>
                                <input type="email" value="<?php echo esc_attr( $$formSecondEmail );?>" name="<?php echo esc_attr( $formSecondEmail );?>" />
                            </div>
                            <div class="site-settings-cform-setfield">
                                <label><?php esc_html_e( "Submit button text", "altss" );?>:</label>
                                <input type="text" value="<?php echo esc_attr( ( '' != $$formSubmitBtnText ? $$formSubmitBtnText : esc_attr__( "Submit" ) ) );?>" name="<?php echo esc_attr( $formSubmitBtnText );?>" />
                            </div>
                            <div class="site-settings-cform-setfield">
                                <label for="<?php echo esc_attr( $formTitleShow );?>">
                                <input type="checkbox" value="1" id="<?php echo esc_attr( $formTitleShow );?>" name="<?php echo esc_attr( $formTitleShow );?>"<?php checked($$formTitleShow, 1);?> />
                                - <?php esc_html_e( "display form title", "altss" );?>
                                </label>
                            </div>
                            <div class="site-settings-cform-setfield">
                                <label for="<?php echo esc_attr( $formDescShow );?>">
                                <input type="checkbox" value="1" id="<?php echo esc_attr( $formDescShow );?>" name="<?php echo esc_attr( $formDescShow );?>"<?php checked($$formDescShow, 1);?> />
                                - <?php esc_html_e( "display form description", "altss" );?>
                                </label>
                            </div>
            <?php
                            submit_button();
            ?>   
                        </div>
                    </div>
            <?php
        }
        else {
            ?>
            <h4><?php esc_html_e( "Form", "altss" ); ?> #<?php echo esc_html( $i );?>: «<?php echo esc_html( $$formTitle );?>»</h4>
            <?php
        }
    }
?>    
                </div>
                <div class="site-settings-cform-set-wrapp">
                    <div class="site-settings-cform-set-item-title"><?php esc_html_e( "ID of the form's popup container", "altss" );?></div>
                    <div class="site-settings-cform-setfield">
                        <label><?php esc_html_e( "default", "altss" );?>: <strong>popup-container-form-wrapper</strong></label>
                        <input type="text" value="<?php echo esc_attr( get_option( 'altss_settings_cforms_container_id' ) );?>" name="altss_settings_cforms_container_id" />
                    </div>
                </div>
                <div class="site-settings-cform-set-wrapp">
                    <div class="site-settings-cform-set-item-title"><?php esc_html_e( "Page containing the text of the privacy policy", "altss" );?></div>
                    <div class="site-settings-cform-setfield">
                    <label for="page_for_posts">
	<?php
	printf(
		/* translators: %s: Select field to choose the page for posts. */
		esc_html__( "Page: %s", "altss"  ),
		wp_dropdown_pages(
			array(
				'name'              => 'altss_settings_cforms_privacy_policy_page',
				'echo'              => 0,
				'show_option_none'  => __( '&mdash; Select &mdash;' ),
				'option_none_value' => '0',
				'selected'          => get_option( 'altss_settings_cforms_privacy_policy_page' ),
			)
		)
	);
	?>
</label>
                    </div>
                </div>
<?php
            submit_button();
            $cformFields_jsvar = '  var cformFields = {';
                foreach ($FORM_FIELDS as $key => $val) {
                    $cformFields_jsvar .= "      " . esc_js( $key ) . ": '" . esc_js( $val['label'] ) . "',";
                }
            $cformFields_jsvar .= '  }';
            wp_add_inline_script( 'altss-cforms-script', $cformFields_jsvar, 'before' );
