<?php

   /*
   Plugin Name: TC favorites
   Plugin URI:
   Description: Pin Ur Favorites is a plugin to allow users to pin their favorite posts on your website.
   Version: 1.2
   Author: Greg Carlson
   */

//enqueue scripts and css
function tcfavorites_scripts_with_jquery(){
    // Register the script like this for a plugin:
    //name of callback     path to js                                                          type of js
    wp_enqueue_script( 'tc_ajax_favorite', plugin_dir_url( __FILE__ ) . '/js/jquery.favoritescustomshortcode.js', array( 'jquery' ) );
    //name of callback     name of object referenced in js        path to wordpress ajax handler
    wp_localize_script( 'tc_ajax_favorite', 'TCFavAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script( 'tc_ajax_delete_favorite', 'TCFavAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'tcfavorites_scripts_with_jquery' );


//capture link click data
function tcfav_capture_link(){
    global $wpdb;
    $user_id=$_POST['user_id'];
    $simple_link_id=$_POST['post_id'];
    $IP=$_SERVER['REMOTE_ADDR'];
    $from_page = $_POST['current_page'];
    $user_email=$_POST['email'];
    $user_state=$_POST['state'];
    $user_role=$_POST['role'];
    $clickedlinkurl= $_POST['clickedlinkurl'];
    $wpdb->query($wpdb->prepare(
        "   INSERT INTO `wp_captured_links`
		( `user_id`, `simple_link_id`, `IP`, `from_page`, `user_email`, `user_state`, `user_role`)
		VALUES ( %d, %d, %s,%s,%s, %s,%s)
	    ",
        array(
            $user_id,
            $simple_link_id,
            $IP,
            $from_page,
            $user_email,
            $user_state,
            $user_role,)
    ));
    $url=get_post_meta($simple_link_id, 'web_address');
    if (!($url) || $url ==""){
        $url = $clickedlinkurl;
    }
    $returnvars = array(
        "url" =>$url,
        "email" =>$user_email,
        "state"=>$user_state,
        "role"=>$user_role,
    );
    print json_encode($returnvars);
    die();// wordpress may print out a spurious zero without this - can be particularly bad if using json
}

//register function calls with wordpress by adding wp_ajax to callback for function to run
add_action('wp_ajax_tc_ajax_capture_linkclick', 'tcfav_capture_link');
add_action( 'wp_ajax_nopriv_tc_ajax_capture_linkclick', 'tcfav_capture_link' );


//delete favorite
function tcfav_delete_favorite(){
    global $wpdb;
    $current_user = wp_get_current_user();
    $favorite_id = $_POST['favorite_id'];
    $success = $wpdb->query(
        "
		DELETE FROM wp_favorites
		WHERE favorite_id=".$favorite_id
    );
    die();
}
//register function calls with wordpress by adding wp_ajax to callback for function to run
add_action('wp_ajax_tc_ajax_delete_favorite', 'tcfav_delete_favorite');
add_action( 'wp_ajax_nopriv_tc_ajax_delete_favorite', 'tcfav_delete_favorite' );

//add favorite
function tcfav_add_favorite(){
    global $wpdb;
    $current_user = wp_get_current_user();
    $post_id = $_POST['post_id'];
    $wpdb->query($wpdb->prepare(
        "
		INSERT INTO wp_favorites
		( post_id, user_id)
		VALUES ( %d, %d)
	",
        array(
            $post_id,
            $current_user->ID)
    ));

    $insert_id=$wpdb->insert_id;
    $last_qString=$wpdb->last_query;
    print $insert_id;
    die();// wordpress may print out a spurious zero without this - can be particularly bad if using json
}

add_action('wp_ajax_tc_ajax_favorite', 'tcfav_add_favorite');
add_action( 'wp_ajax_nopriv_tc_ajax_favorite', 'tcfav_add_favorite' );





?>