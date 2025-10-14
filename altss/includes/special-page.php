<?php

function altss_special_settings_page_html(){
    ?>
<div class="site-settings-page-wrapper">
    <h2 class="site-settings-admin-page-head"><?php esc_html_e( "Specialized settings and tools page", "altss" ); ?></h2> 

    
    <div class="site-settings-template-wrapp">
    <dl class="site-settings-custom-recs-dl">
        <dt><?php esc_html_e( "Clearing revisions in the posts table", "altss" ); ?></dt>
        <dd>
<?php 
    if( isset( $_POST['revisions_clear'] )){
        if( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'rrr55rds' ) && "kfujr674urf7" === $_POST['revisions_clear'] && altss_post_revisions_clear() ){
            echo "<p style='color: green;'>" . esc_html__( "Post table revisions have been cleared!", "altss" ) . "</p>";
        }
        else{
            echo "<p style='color: darkred;'>" . esc_html__( "The request returned a null result.", "altss" ) . "</p>";
        }
    }
?>
            <form method="POST">
                <?php wp_nonce_field("rrr55rds", "nonce"); ?>
                <input type="hidden" name="revisions_clear" value="kfujr674urf7"  />
                <div class="site-settings-template-item-wrapp">
                    <input type="submit" value="<?php esc_html_e( "Clear post revisions", "altss" ); ?>" />
                </div>
            </form>
        </dd>
    </dl>
    </div>
</div>
    <?php
}