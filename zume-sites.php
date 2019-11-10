<?php
/**
 * Plugin Name: Zúme Sites
 * Plugin URI:  https://github.com/DiscipleTools/disciple-tools-multisite
 * Description: Plugin with specific scripts to configure the multisite Zume Training installation.
 * Version:     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function zume_new_blog_force_dt_theme( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    update_blog_option( $blog_id,'template','disciple-tools-theme' );
    update_blog_option( $blog_id,'stylesheet','disciple-tools-theme' );
    update_blog_option( $blog_id,'current_theme','Disciple Tools' );
}
add_action( 'wpmu_new_blog', 'zume_new_blog_force_dt_theme', 10, 6 );

