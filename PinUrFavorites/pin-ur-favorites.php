<?php

/*
 *
Plugin Name: Pin Ur Favorites
Description: Plugin allows for users who are logged in to pin their favorite posts to a Pinned Favorites template page.
On the Pinned Favorites template page users can keep a list of their favorite posts from your website and gives them the
option to visit the post or delete the post from their Pinned Favorites.
Version: 1.0
Author: gregcarlson
*/
//create wp_favorites table in database

//enqueue scripts and css
function tcfavorites_scripts_with_jquery(){
    // Register the script like this for a plugin:
    //name of callback     name of object referenced in js               path to js                   type of js
    wp_enqueue_script( 'tc_ajax_favorite', plugin_dir_url( __FILE__ ) . '/js/jquery.pinurfavs.js', array( 'jquery' ) );
    //name of callback     name of object referenced in js        path to wordpress ajax handler
    wp_localize_script( 'tc_ajax_favorite', 'pinned_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script( 'tc_ajax_delete_favorite', 'pinned_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'tcfavorites_scripts_with_jquery' );


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

//create class
if(!class_exists('pin_ur_favs')){
    class pin_ur_favs{


        //Unique identifier
        protected $plugin_slug;
        //reference to an instance of this class
        private static $instance;
        //the array of templates that this plugin uses
        protected $templates;

        public static function get_instance(){
            if(null == self::$instance){
                self::$instance = new pin_ur_favs();
            }
            return self::$instance;
        }


        //Initializes the plugin by setting filters and administration
        private function __construct()
        {
            $this->templates = array();

            //Add a filter to the attributes betabox to inject template into the cache
            add_filter('page_attributes_dropdown_pages_args', array($this, 'register_project_templates'));
            //Add a filter to the save post to inject out template into page cache
            add_filter('wp_insert_post_data', array($this, 'register_project_templates'));
            //Add a filter to the template include to determine if the page has our template assigned and return its path
            add_filter('template_include', array($this, 'view_project_template'));
            //Add your templates to this array
            $this->templates = array('templates/pinned_favorites_temp.php'=>"pinned favorites temp" );
        }



        public function register_project_templates($atts){
            //create teh key used for the themes cache

            // Get theme object
            $theme = wp_get_theme();
            // Create the key used for the themes cache
            $cache_key = 'page_templates-' . md5( $theme->get_theme_root() . '/' . $theme->get_stylesheet() );

            //Retrieve the cache list.
            //If it doesn't exist, or it's empty prepare an array
            $templates = wp_get_theme()->get_page_templates();
            if(empty($templates)){
                $templates = array();
            }

            //New cache, therefore remove the old one
            wp_cache_delete($cache_key, 'themes');

            //Now add our tempaltes to the list of templates by merging templates
            $templates = array_merge($templates, $this->templates);

            //Add the modified cache to allow WP to pick it up for listing available templates
            wp_cache_add($cache_key, $templates, 'themes', 1800);

            return $atts;
        }

        public function view_project_template($template){
            global $post;

            if(!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])){
                return $template;
            }

            $file = plugin_dir_path(__FILE__).get_post_meta($post->ID, '_wp_page_template', true);

            //Just to be safe, we check fi the file exist first
            if(file_exists($file)){
                return $file;
            }else{
                echo $file;
            }
            return $template;
        }

        public function pinned_get_favorites($user_id){
            global $wpdb;
            $find_post_id = $wpdb->get_results($wpdb->prepare("SELECT favorite_id, post_id, entry_date, post_title
                                                               FROM wp_favorites f, wp_users u, wp_posts p
                                                               WHERE u.ID = %d AND f.user_id = %d
                                                               AND f.post_id = p.ID",$user_id, $user_id ));
            return $find_post_id;
        }

        //Formats date for favorites list
        public function favorite_format_date($date){
            $formatD = strtotime($date);
            $my_format = date("m/d/y", $formatD);
            return $my_format;
        }

    }
}
add_action('plugins_loaded', array('pin_ur_favs', 'get_instance'));

function create_wp_favorites_tb() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name       = $wpdb->prefix . "favorites";

    if ( $wpdb->get_var( "SHOW TABLES LIKE $table_name" ) != $table_name ) {

        $sql = "CREATE TABLE IF NOT EXISTS $table_name  (
    favorite_id int(11) NOT NULL AUTO_INCREMENT,
    post_id double NOT NULL,
    user_id int(11) NOT NULL,
    entry_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (favorite_id)
    ) $charset_collate;";

        //reference to upgrade.php file
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        //update_option('keyword_ranker_version',$keyword_rankerdb);
    }
//action hook for plugin activation

}

//end of plugin installation
register_activation_hook( __FILE__, 'create_wp_favorites_tb' );



?>