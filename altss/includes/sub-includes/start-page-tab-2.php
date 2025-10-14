<?php
if ( ! defined( 'ABSPATH' ) ) exit;


settings_fields( 'altss_settings_options_txt' ); 
                        ?>
                        <div class="site-settings-tab-header-div"><?php echo wp_kses(
                            __( 'Text blocks for inserting into a template via the <strong>altss_insertable_text_block( $num, $class )</strong> function:', 'altss' ),
                            [ 'strong' => [] ]
                        ); 
                         ?>
                         </div>
                        <div class="site-settings-template-item-wrapp">
                            <dl>
                                <?php for( $i = 1; $i < 6; $i++ ){?>
                                <dt><p class="site-settings-item-title"><?php echo esc_html__( "Contents of text block No. ", "altss" ) . ' ' . $i; ?></p></dt>
                                <dd>
                                    <?php altss_add_editior_field("altss_settings_options_embedded_text_{$i}", wp_unslash(get_option("altss_settings_options_embedded_text_{$i}")), 3); ?>
                                </dd>
                                <?php } ?>
                            </dl>
                        </div>
                        <?php
                            submit_button();
                        ?>
    