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
            margin: 5% auto;
            width: 30%;
            border: 1px solid #323A68;
        }
        p .submit {
            padding: 15px;
        }
        .wp-activate-container {
            padding: 15px;
        }
        #zume-main-menu {
            display:none;
        }
    </style>
    <?php
}
add_action( 'signup_header', 'zume_multisite_disable_header' );
add_action( 'activate_wp_head', 'zume_multisite_disable_header' );


/**
 * Functions to modify the registration page
 * @see https://codex.wordpress.org/Customizing_the_Registration_Form
 */
//1. Add a new form element...

function zume_mu_register_form() {

    $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( sanitize_key( wp_unslash( $_POST['first_name'] ) ) ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( sanitize_key( wp_unslash( $_POST['last_name'] ) ) ) : '';
    $zume_phone_number = ( ! empty( $_POST['zume_phone_number'] ) ) ? trim( sanitize_key( wp_unslash( $_POST['zume_phone_number'] ) ) ) : '';
    $zume_address = ( ! empty( $_POST['zume_address'] ) ) ? trim( sanitize_key( wp_unslash( $_POST['zume_address'] ) ) ) : '';

    ?>
    <p>
        <label for="first_name"><?php esc_attr_e( 'First Name (optional for coaching)', 'zume' ) ?><br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
    </p>
    <p>
        <label for="last_name"><?php esc_attr_e( 'Last Name (optional for coaching)', 'zume' ) ?><br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
    </p>
    <p>
        <label for="zume_phone_number"><?php esc_attr_e( 'Phone Number (optional for coaching)', 'zume' ) ?><br />
            <input type="text" name="zume_phone_number" id="zume_phone_number" class="input" value="<?php echo esc_attr( wp_unslash( $zume_phone_number ) ); ?>" size="25" /></label>
    </p>
    <p>
        <label for="zume_address"><?php esc_attr_e( 'Address (optional for coaching)', 'zume' ) ?><br />
            <input type="text" name="zume_address" id="zume_address" class="input" value="<?php echo esc_attr( wp_unslash( $zume_address ) ); ?>" size="25" />
        </label>
    </p>
    <?php
    $key = '';
    if ( isset( $_GET['affiliation'] ) ) {
        $key = strtoupper( sanitize_key( wp_unslash( $_GET['affiliation'] ) ) );
    }
    ?>
    <p class="grid-x grid-padding-x" style="width:100%">
        <label for="zume_affiliation_key"><?php esc_attr_e( 'Affiliation Key (optional)', 'zume' ) ?></label><br>
        <input type="text" value="<?php echo esc_html( $key ) ?>" id="zume_affiliation_key"
               name="zume_affiliation_key" maxlength="5" />
    </p>
    <br clear="all" />
    <style>#signup-content .wp-signup-container h2 {display:none;}</style>

    <?php
}
add_action( 'signup_extra_fields', 'zume_mu_register_form' );

//2. Add validation. In this case, we make sure first_name is required.
add_filter( 'registration_errors', 'zume_mu_registration_errors', 10, 3 );
function zume_mu_registration_errors( $errors, $sanitized_user_login, $user_email ) {

    if ( empty( $_POST['zume_address'] ) || ! empty( $_POST['zume_address'] ) && trim( sanitize_key( wp_unslash( $_POST['zume_address'] ) ) ) == '' ) {

        $results = Disciple_Tools_Google_Geocode_API::query_google_api( trim( sanitize_key( wp_unslash( $_POST['zume_address'] ) ) ), 'validate' );

        if ( ! $results ) {
            $errors->add( 'zume_address_error', esc_attr__( '<strong>ERROR</strong>: We can not recognize this address as valid location. Please, check spelling.', 'zume' ) );
        }
    }

    return $errors;
}

//3. Finally, save our extra registration user meta.
//add_action( 'signup_extra_fields', 'zume_mu_user_register' );
//function zume_mu_user_register( $user_id ) {
//    // Capture user submitted fields
//    if ( ! empty( $_POST['first_name'] ) ) {
//        update_user_meta( $user_id, 'first_name', trim( sanitize_key( wp_unslash( $_POST['first_name'] ) ) ) );
//    }
//    if ( ! empty( $_POST['last_name'] ) ) {
//        update_user_meta( $user_id, 'last_name', trim( sanitize_key( wp_unslash( $_POST['last_name'] ) ) ) );
//    }
//    if ( ! empty( $_POST['zume_phone_number'] ) ) {
//        update_user_meta( $user_id, 'zume_phone_number', trim( sanitize_key( wp_unslash( $_POST['zume_phone_number'] ) ) ) );
//    }
//    if ( ! empty( $_POST['zume_address'] ) ) {
//
//        $results = Disciple_Tools_Google_Geocode_API::query_google_api( trim( sanitize_key( wp_unslash( $_POST['zume_address'] ) ) ), 'core' );
//
//        if ( $results ) {
//            update_user_meta( $user_id, 'zume_user_address', $results['formatted_address'] );
//            update_user_meta( $user_id, 'zume_user_lng', $results['lng'] );
//            update_user_meta( $user_id, 'zume_user_lat', $results['lat'] );
//            update_user_meta( $user_id, 'zume_raw_location', $results );
//        }
//    }
//    if ( ! empty( $_POST['zume_affiliation_key'] ) ) {
//        update_user_meta( $user_id, 'zume_affiliation_key', trim( sanitize_key( wp_unslash( $_POST['zume_affiliation_key'] ) ) ) );
//    }
//
//    zume_update_user_ip_address_and_location( $user_id ); // record ip address and location
//
//    update_user_meta( $user_id, 'zume_language', zume_current_language() );
//}

// Stores form data for activation
add_filter( 'add_signup_meta' , 'custom_add_signup_meta' );
function custom_add_signup_meta( $meta )
{
    $first_name = '';
    $last_name = '';
    $zume_phone_number = '';
    $zume_address = '';
    $zume_affiliation_key = '';

    if ( ! empty( $_POST['first_name'] ) ) {
        $first_name = trim( sanitize_key( wp_unslash( $_POST['first_name'] ) ) );
    }
    if ( ! empty( $_POST['last_name'] ) ) {
        $last_name = trim( sanitize_key( wp_unslash( $_POST['last_name'] ) ) );
    }
    if ( ! empty( $_POST['zume_phone_number'] ) ) {
        $zume_phone_number = trim( sanitize_key( wp_unslash( $_POST['zume_phone_number'] ) ) );
    }
    if ( ! empty( $_POST['zume_address'] ) ) {
        $zume_address = trim( sanitize_key( wp_unslash( $_POST['zume_address'] ) ) );
    }
    if ( ! empty( $_POST['zume_affiliation_key'] ) ) {
        $zume_affiliation_key = trim( sanitize_key( wp_unslash( $_POST['zume_affiliation_key'] ) ) );
    }

    $user_meta = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'zume_phone_number' => $zume_phone_number,
        'zume_address' => $zume_address,
        'zume_affiliation_key' => $zume_affiliation_key,
    );

    $meta['user_meta'] = $user_meta;

    return $meta;

}

// Save after activation step
add_action( 'wpmu_activate_blog' , 'custom_wpmu_activate_blog' , 10 , 5 );
function custom_wpmu_activate_blog( $blog_id , $user_id , $password , $site_title , $meta ) {

    // Capture user submitted fields
    if ( isset( $meta['user_meta']['first_name'] ) && ! empty( $meta['user_meta']['first_name'] ) ) {
        update_user_meta( $user_id, 'first_name', trim( sanitize_key( wp_unslash( $meta['user_meta']['first_name'] ) ) ) );
    }
    if ( isset( $meta['user_meta']['last_name'] ) && ! empty( $meta['user_meta']['last_name'] ) ) {
        update_user_meta( $user_id, 'last_name', trim( sanitize_key( wp_unslash( $meta['user_meta']['last_name'] ) ) ) );
    }
    if ( isset($meta['user_meta']['zume_phone_number'] ) && ! empty( $meta['user_meta']['zume_phone_number'] ) ) {
        update_user_meta( $user_id, 'zume_phone_number', trim( sanitize_key( wp_unslash( $meta['user_meta']['zume_phone_number'] ) ) ) );
    }
    if ( isset( $meta['user_meta']['zume_address'] ) && ! empty( $meta['user_meta']['zume_address'] ) ) {

        $results = Disciple_Tools_Google_Geocode_API::query_google_api( trim( sanitize_key( wp_unslash( $meta['user_meta']['zume_address'] ) ) ), 'core' );

        if ( $results ) {
            update_user_meta( $user_id, 'zume_user_address', $results['formatted_address'] );
            update_user_meta( $user_id, 'zume_user_lng', $results['lng'] );
            update_user_meta( $user_id, 'zume_user_lat', $results['lat'] );
            update_user_meta( $user_id, 'zume_raw_location', $results );
        }
    }
    if ( isset( $meta['user_meta']['zume_affiliation_key'] ) && ! empty( $meta['user_meta']['zume_affiliation_key'] ) ) {
        update_user_meta( $user_id, 'zume_affiliation_key', trim( sanitize_key( wp_unslash( $meta['user_meta']['zume_affiliation_key'] ) ) ) );
    }

    zume_update_user_ip_address_and_location( $user_id ); // record ip address and location

    update_user_meta( $user_id, 'zume_language', zume_current_language() );

//    switch_to_blog( $blog_id );
//
//    delete_option( 'user_meta' );
//
//    restore_current_blog();

}



// Adds columns to the user table in the admin area
function zume_mu_add_user_admin_columns( $columns ) {

    $columns['zume_phone_number'] = __( 'Phone Number', 'zume' );
    $columns['zume_user_address'] = __( 'User Address', 'zume' );
    $columns['zume_address_from_ip'] = __( 'GeoCoded IP', 'zume' );
    return $columns;

} // end theme_add_user_zip_code_column
add_filter( 'manage_users_columns', 'zume_mu_add_user_admin_columns' );


function zume_mu_show_custom_column_content( $value, $column_name, $user_id ) {

    if ( 'zume_phone_number' == $column_name ) {
        return get_user_meta( $user_id, 'zume_phone_number', true );
    } // end if
    if ( 'zume_user_address' == $column_name ) {
        if ( empty( get_user_meta( $user_id, 'zume_user_address', true ) ) ) {
            $content = '';
        }
        else {
            $content = get_user_meta( $user_id, 'zume_user_address', true ) . '<br><a target="_blank" href="https://www.google.com/maps/search/?api=1&query='.get_user_meta( $user_id, 'zume_user_lat', true ).','.get_user_meta( $user_id, 'zume_user_lng', true ).'">map</a>';
        }
        return $content;
    } // end if
    if ( 'zume_address_from_ip' == $column_name ) {
        if ( empty( get_user_meta( $user_id, 'zume_address_from_ip', true ) ) ) {
            $content = '';
        }
        else {
            $content = get_user_meta( $user_id, 'zume_address_from_ip', true ) . '<br><a target="_blank" href="https://www.google.com/maps/search/?api=1&query='.get_user_meta( $user_id, 'zume_lat_from_ip', true ).','.get_user_meta( $user_id, 'zume_lng_from_ip', true ).'">map</a>';
        }
        return $content;
    } // end if

} // end theme_show_user_zip_code_data
add_action( 'manage_users_custom_column', 'zume_mu_show_custom_column_content', 10, 3 );