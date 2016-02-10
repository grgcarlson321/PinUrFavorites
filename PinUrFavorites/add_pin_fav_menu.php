<?php
/**
 * Created by PhpStorm.
 * User: gregcarlson
 * Date: 2/10/16
 * Time: 10:56 AM
 * Description: Class that handles WordPress Admin Dashboard menu for Pinned Favorites.
 * The class extends WP_List_Table that WP uses for creating table in the WP Dashboard.
 * The menu provides a link to display a table of all the Pinned Favorites based on
 * user_nicename, entry_date, email, and post_title.
 */

function add_pin_fav_menu(){

    if(!current_user_can('manage_options')){
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    $pin_fav_lists = new pin_favorites_list();
    $pin_fav_lists->prepare_items();

    ?> <div class='wrap'>
        <div id='icon-users' class='icon32'></div>
        <h2>Pinned Favorites Page</h2>
        <?php  $pin_fav_lists->display(); ?>
    </div>
    <?php
}
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class pin_favorites_list extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array    array('Name'=>$firstname." ".$lastname, 'Email'=>$email, 'Date'=>$pin_entry_date, 'Post_ID'=>$post_title);
     */
    public function get_columns()
    {
        $columns = array(
            'Name' => 'NAME',
            'Date' => 'DATE',
            'Email' => 'EMAIL',
            'Post' => 'POST'
        );
        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('Name' => array('Name', false), 'Email' => array('Email', false), 'Date' => array('Date', false), 'Post' => array('Post', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */

    private function table_data()
    {

        global $wpdb;

        $user_pinned_items = $wpdb->get_results($wpdb->prepare("SELECT user_nicename, entry_date, user_email, post_title, guid
                                                                FROM wp_users u, wp_posts p, wp_favorites f
                                                                WHERE u.ID = f.user_id
                                                                AND p.ID = f.post_id
                                                                ORDER BY f.user_id", ''), OBJECT);
        $pin_array = array();
        foreach ($user_pinned_items as $pin_item) {
            $user_name = $pin_item->user_nicename;
            $post_title = $pin_item->post_title;
            $email = $pin_item->user_email;
            $entry_date = $pin_item->entry_date;
            $guid = $pin_item->guid;

            //load up the array of with arrays
            $pin_array[] = array('Name' => $user_name . "<br>", 'Email' => $email, 'Date' => $entry_date, 'Post' => $post_title . "<br> <a href='" . $guid . "' target=blank>View Post</a>");
        }

        return $pin_array;

    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'Name':
            case 'Email':
            case 'Date':
            case 'Post':
                return $item[$column_name];

            default:
                return print_r($item, true);
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'title';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }


        $result = strcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }
}

?>