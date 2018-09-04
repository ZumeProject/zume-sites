<?php
/**
 * Plugin Name: Zume Project Sites
 * Plugin URI:  https://github.com/DiscipleTools/disciple-tools-multisite
 * Description: Plugin with specific scripts to configure the multisite Zume Project installation.
 * Version:     1.0
 */


function zume_new_blog_force_dt_theme( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    update_blog_option( $blog_id,'template','disciple-tools-theme' );
    update_blog_option( $blog_id,'stylesheet','disciple-tools-theme' );
    update_blog_option( $blog_id,'current_theme','Disciple Tools' );
}
add_action( 'wpmu_new_blog', 'zume_new_blog_force_dt_theme', 10, 6 );


function zume_multisite_disable_header() {
    ?>
    <style type="text/css">
        div#signup-content {
            margin: 15% auto;
            width: 30%;
            border: 1px solid #323A68;
        }
        .mu_register h2 {display:none;}
        p .submit {
            padding: 15px;
        }
    </style>
    <script>
        jQuery(document).ready(function(){

        })
    </script>
    <?php
}
add_action( 'signup_header', 'zume_multisite_disable_header' );
