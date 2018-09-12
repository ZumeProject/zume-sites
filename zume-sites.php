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

//function zume_force_smtp_email_service () {
////    $option = get_option( 'swpsmtp_options' );
//    if ( true ) {
//        $email_setup_info = 'a:8:{s:16:"from_email_field";s:26:"no-repy@wp.zumeproject.com";s:15:"from_name_field";s:22:"Zúme Project Coaching";s:23:"force_from_name_replace";b:0;s:13:"smtp_settings";a:9:{s:4:"host";s:16:"smtp.mailgun.org";s:15:"type_encryption";s:3:"tls";s:4:"port";s:4:"2525";s:13:"authentication";s:3:"yes";s:8:"username";s:29:"postmaster@wp.zumeproject.com";s:8:"password";s:44:"YmU4NzZhYWYzM2M1MDkwMTk0NzBhMTI0OTNmYmUzZjk=";s:12:"enable_debug";b:0;s:12:"insecure_ssl";b:0;s:12:"encrypt_pass";b:0;}s:15:"allowed_domains";s:32:"Y29hY2hlcy56dW1lcHJvamVjdC5jb20=";s:14:"reply_to_email";s:20:"info@zumeproject.com";s:17:"email_ignore_list";s:0:"";s:19:"enable_domain_check";b:0;}';
//        update_option( 'swpsmtp_options', $email_setup_info );
//    }
//}
//add_action('init', 'zume_force_smtp_email_service', 99);

