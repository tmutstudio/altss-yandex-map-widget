<?php
if ( ! defined( 'ABSPATH' ) ) exit;

settings_fields( 'altss_settings_options_1' );
$custom_recs_data = get_option( "altss_settings_options_custom_recs" );
$custom_recs_settigs = get_option( "altss_settings_options_custom_recs_settings" );

include ALTSITESET_INCLUDES_DIR.'/data-vars/custom-type-vars.php';

                        ?>
                        <div class="site-settings-tab-header-div"><?php esc_html_e( "Activation/deactivation of custom records:", "altss" ); ?></div>
                        <div class="site-settings-template-item-wrapp">
                            <div style="margin: 20px 0; padding: 20px; width:100%; max-width: 900px; font-size: 12pt; border-left: 4px solid darkred;">
                            <span style="color: darkred;"><?php esc_html_e( "Important", "altss" ); ?>!</span> <?php esc_html_e( "After connecting or disconnecting a custom post type, you must go to the page:", "altss" ); ?><br>
                             <a href="<?php echo esc_url( admin_url( "options-general.php" ) );?>"><?php echo esc_html__( "Settings" ); ?></a> → <a href="<?php echo esc_url( admin_url( "options-permalink.php" ) );?>"><?php echo esc_html__( "Permalinks" ); ?></a>.<br>
                            <?php esc_html_e( "This is necessary so that the Permalinks rules are recreated and the rules of the new record type are added there.", "altss" ); ?>
                            </div>
                            <dl class="site-settings-custom-recs-dl">
                                <?php
                                foreach ($CUSTOM_TYPES as $key => $val) {
                                    ?>
                                <dt><?php echo esc_html__( "Custom records", "altss" ); ?> <span>«<?php echo esc_html( $val['label'] ); ?> (<?php echo esc_html( $key ); ?>)»</span></dt>
                                <dd>
                                    <p>
                                        <?php esc_html_e( "The archive will be available at the link:", "altss" ); ?>
                                        <a href="<?php echo esc_url( site_url( $key ) ); ?>/" target="_blank"><?php echo esc_url( site_url( $key ) ); ?>/</a>
                                    </p>
                                    <div class="onoffswitch-over">
                                        <div class="onoffswitch-left">
                                            <input type="checkbox" id="altss_settings_options_custom_recs_<?php echo esc_attr( $key ); ?>" name="altss_settings_options_custom_recs[<?php echo esc_attr( $key ); ?>]" class="onoffswitch-checkbox" value="1"<?php checked( ( isset( $custom_recs_data[$key] ) ? 1 : 0 ), 1); ?> />
                                            <label class="onoffswitch-label" for="altss_settings_options_custom_recs_<?php echo esc_attr( $key ); ?>"></label>
                                        </div>
                                        <label class="onoffswitch-label-text" for="altss_settings_options_custom_recs_<?php echo esc_attr( $key ); ?>">-  <?php esc_html_e( "check the box to activate this post type", "altss" ); ?></label>
                                    </div>
                                    <div class="onoffswitch-over">
                                        <div class="onoffswitch-left">
                                            <input type="checkbox" id="altss_settings_options_custom_recaltss_settings_<?php echo esc_attr( $key ); ?>_ggeditor" name="altss_settings_options_custom_recs_settings[<?php echo esc_attr( $key ); ?>][ggeditor]" class="onoffswitch-checkbox" value="1"<?php checked( ( isset( $custom_recs_settigs[$key]['ggeditor'] ) ? 1 : 0 ), 1); ?> />
                                            <label class="onoffswitch-label" for="altss_settings_options_custom_recaltss_settings_<?php echo esc_attr( $key ); ?>_ggeditor"></label>
                                        </div>
                                        <label class="onoffswitch-label-text" for="altss_settings_options_custom_recaltss_settings_<?php echo esc_attr( $key ); ?>_ggeditor">-  <?php esc_html_e( "activate Gutenberg for this post type", "altss" ); ?></label>
                                    </div>
                                    <div class="onoffswitch-over">
                                        <div class="onoffswitch-left">
                                            <input type="checkbox" id="altss_settings_options_custom_recaltss_settings_<?php echo esc_attr( $key ); ?>_nocomments" name="altss_settings_options_custom_recs_settings[<?php echo esc_attr( $key ); ?>][nocomments]" class="onoffswitch-checkbox" value="1"<?php checked( ( isset( $custom_recs_settigs[$key]['nocomments'] ) ? 1 : 0 ), 1); ?> />
                                            <label class="onoffswitch-label" for="altss_settings_options_custom_recaltss_settings_<?php echo esc_attr( $key ); ?>_nocomments"></label>
                                        </div>
                                        <label class="onoffswitch-label-text" for="altss_settings_options_custom_recaltss_settings_<?php echo esc_attr( $key ); ?>_nocomments">-  <?php esc_html_e( "disable comments for this post type", "altss" ); ?></label>
                                    </div>
                                </dd>
                                <?php
                                }
                                ?>
                            </dl>
                        </div>
                        <?php
                            submit_button();
                        ?>
