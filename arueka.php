<?php
/*
Plugin Name: arueka Plugin
Description: This plugin manages vendor requests and approvals.
Version: 1.0
Author: Gc
*/
use arueka\includes\VendorDenyListTable;
use arueka\includes\VendorAddonRequestList;
// require_once 'includes/VendorDenyListTable.php';
// Check if WP_List_Table class exists, if not, include it
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
// Define a class Vendor_Request_Table which extends WP_List_Table
class Vendor_Request_Table extends WP_List_Table
{
    // Constructor method
    public function __construct()
    {
        parent::__construct(
            array(
                'singular' => 'user',
                'plural' => 'users',
                'ajax' => false,
            )
        );
        // Enqueue admin CSS
        //add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_styles'));

        // Enqueue admin JS
        //add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Prepare items for display.
     * This method retrieves vendor request posts and prepares them for display in the table.
     */
    public function prepare_items()
    {
       
        $status = isset($_REQUEST['status']) ? sanitize_text_field($_REQUEST['status']) : '';
        $search = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';

        $args = array(
            'post_type' => 'vendor_request',
            'posts_per_page' => -1,
            'post_status' => array('pending', 'publish'),
            'orderby' => array('post_status' => 'ASC', 'date' => 'DESC'),
            's' => $search, // Add search parameter
        );

        // If status filter is set, include it in query args
        if ($status) {
            $args['post_status'] = $status;
        }
        if ($search) {
            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'business_email',
                    'value' => $search,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'store_name',
                    'value' => $search,
                    'compare' => 'LIKE',
                ),
                // Add more meta queries for other columns as needed
            );
        }
            // If search query is set, include it in query args
        if ($search) {
            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'store_name',
                    'value' => $search,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'business_telephone',
                    'value' => $search,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'mobile_number',
                    'value' => $search,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'owner_first_name',
                    'value' => $search,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'owner_last_name',
                    'value' => $search,
                    'compare' => 'LIKE',
                ),
            );
        }

        $posts = new WP_Query($args);
        $data = array();


        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                $post_id = get_the_ID();
                // wp_delete_post($post_id, true);
                $meta_data = get_post_meta($post_id);
                $status = get_post_status();
                
                $user_data = array(
                    'ID' => $post_id,
                    'date_submitted' => get_the_date(),
                    'status' => $status,
                    'business_name' => isset($meta_data['store_name'][0]) ? $meta_data['store_name'][0] : '',
                    'telephone_number' => isset($meta_data['business_telephone'][0]) ? $meta_data['business_telephone'][0] : '',
                    // 'trading_as_name' => '',
                    'mobile_number' => isset($meta_data['telephone_number'][0]) ? $meta_data['telephone_number'][0] : '',
                    'industry' => isset($meta_data['industry'][0]) ? $meta_data['industry'][0] : '',
                    'business_email' => isset($meta_data['business_email'][0]) ? $meta_data['business_email'][0] : '',
                    'owner_first_name' => isset($meta_data['owner_first_name'][0]) ? $meta_data['owner_first_name'][0] : '',
                    'owner_last_name' => isset($meta_data['owner_last_name'][0]) ? $meta_data['owner_last_name'][0] : '',
                    'Business_Trading_As_Name' => isset($meta_data['Business_Trading_As_Name'][0]) ? $meta_data['Business_Trading_As_Name'][0] : '',
                    'date_of_birth' => isset($meta_data['gc_dob'][0]) ? $meta_data['gc_dob'][0] : '',

                );

                $data[] = $user_data;
            }
            wp_reset_postdata();
        }

        // Sorting logic
        $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'date_submitted';
        $order = isset($_GET['order']) ? $_GET['order'] : 'desc';

        usort($data, function ($a, $b) use ($orderby, $order) {
            $dateA = strtotime($a[$orderby]);
            $dateB = strtotime($b[$orderby]);

            if ($dateA == $dateB) {
                return 0;
            }
            return ($order == 'asc') ? ($dateA - $dateB) : ($dateB - $dateA);
        });


        // Pagination
        $per_page = $this->get_items_per_page('users_per_page', 20);
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $offset = (($current_page - 1) * $per_page);
        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page' => $per_page,
            )
        );

        // Define the columns
        $columns = $this->get_columns();
        $hidden = array();
        //$sortable = $this->get_sortable_columns();
        $sortable = '';
        $this->_column_headers = array($columns, $hidden, $sortable);

        // Slice the data based on pagination
        $data = array_slice($data, $offset, $per_page);

        // Set the items for display
        $this->items = $data;
    }

    // Method to define default columns
    public function get_columns()
    {
        $columns = array(
            // 'cb' => '<input type="checkbox" checked  />',
            'date_submitted' => 'Date Submitted',
            'status' => 'Status',
            'business_name' => 'Business Name',
            'telephone_number' => 'Telephone Number',
            'Business_Trading_As_Name' => 'Trading As Name',
            'mobile_number' => 'Mobile Number',
            'industry' => 'Industry',
            'business_email' => 'Email ID',
            'owner_first_name' => 'Owner First Name',
            'owner_last_name' => 'Owner Last Name',
            'date_of_birth' => 'Owner Date of Birth',
            'action' => 'Action',

        );
        return $columns;
    }

    // Method to define sortable columns
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'telephone_number' => array('telephone_number', true),
            'owner_first_name' => array('owner_first_name', true),
            'owner_last_name' => array('owner_last_name', true),
            'business_email' => array('business_email', true),
            'business_license' => array('business_license', true),
        );
        return $sortable_columns;
    }

    //add filter by status 
    protected function extra_tablenav($which)
    {
        if ($which == 'top') {
            $statuses = array(
                '' => 'All',
                'pending' => 'Pending',
                'publish' => 'Published',
                // 'draft' => 'Draft',
            );
            ?>
            <div class="alignleft actions">
                <label class="screen-reader-text" for="status"><?php _e('Filter by status'); ?></label>
                <select name="status" id="status">
                    <?php foreach ($statuses as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($value, isset($_REQUEST['status']) ? $_REQUEST['status'] : ''); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php submit_button(__('Filter'), 'button', 'filter_action', false); ?>
            </div>
            <?php
        }
    }

    //for add search baar for vendor request table
    public function search_box($text, $input_id)
    {
        $input_id = $input_id . '-search-input';
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id; ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id; ?>" name="s" value="<?php _admin_search_query(); ?>">
            <?php submit_button($text, 'button', false, false, array('ID' => 'search-submit')); ?>
        </p>
        <?php
    }

   

    /**
     * Default callback function to render table columns.
     *
     * @param array $item The current row's data.
     * @param string $column_name The name of the column being rendered.
     * @return string The HTML markup for the column.
     */
    public function column_default($item, $column_name)
    {
        $vendor_hide='';
        $current_post_status = get_post_status($item['ID']);
        if($current_post_status=='publish'){
            $vendor_hide='gc_vendor_hide';
        }

        switch ($column_name) {
            case 'action':
                $deny_url = admin_url('admin.php?page=vendor_view_page&vendor_id=' . $item['ID'] . '&view_by_vendor=true');
                echo '<a class="button button-primary gc-view-users" vendor_id="' . $item['ID'] . '">View</a>';
                $confirm_approve_msg = "Are you sure you want to approve this vendor?"; // Confirmation message
                $approve_url = admin_url('admin.php?page=vendor_view_page&vendor_id=' . $item['ID'] . '&create_vendor=true');
                echo '<a href="' . esc_url($approve_url) . '" class="button button-primary '.$vendor_hide.'" onclick="return confirmApprove(\'' . esc_js($confirm_approve_msg) . '\')">Approve</a>';
                //$confirm_deny_message = "Are you sure you want to deny this vendor?"; // Confirmation message
                $deny_url = admin_url('admin.php?page=vendor_view_page&vendor_id=' . $item['ID'] . '&deny_vendor=true');
                echo '<a href="#" class="button button-danger gc-deny-user" vendor_id="' . $item['ID'] . '">Deny</a>';
            //return '<a href="' . esc_url($deny_url) . '" class="button button-danger">Deny</a>';
            default:
                // Default behavior for other columns
                return isset($item[$column_name]) ? $item[$column_name] : '';
        }
    }
}

// Function to render LiveChat JS code
function lh_add_livechat_js_code()
{
    ?>
    <div class="gcunique-popup">

        <div class="gcunique-popup-content">
            <div class="gcunique-close-btn-x">X</div>
            <p class="gcunique-review">Please add a reason why you want to deny this user.</p>
            <form method="post" class="gcunique-form" action="#">
                <label for="gcunique-description">Description:</label>
                <textarea id="gcunique-description" name="description" required></textarea>
                <input type="submit" value="Submit" name="gc_deny">
            </form>

        </div>
    </div>

    <?php
}
add_action('admin_footer', 'lh_add_livechat_js_code'); // For back-end

// Function to render LiveChat JS code by vinay
function view_by_vendor_js_code()
{
    ?>
        <div class="loading-indicator" style="display: none;">
            <div class="loader"></div>
        </div>
        <div class="gcunique-popup2">
            <div class="gcunique-popup-content">
                <div class="gcunique-close-btn-xx">X</div>
                <h3>Vendor Store Data</h3>
                <form method="get" class="gcunique-form" action="#">
                <?php 
                require_once plugin_dir_path( __FILE__ ) . 'includes/VendorRequestViewPop.php'; 
                ?>
                </form>
            </div>
        </div>
    <?php
}
add_action('admin_footer', 'view_by_vendor_js_code'); // For back-end

// Function  code by vinay
add_action('wp_ajax_vendor_view_popup', 'vendor_view_popup_callback');
add_action('wp_ajax_nopriv_vendor_view_popup', 'vendor_view_popup_callback'); // If you want to allow non-logged-in users to access the AJAX endpoint

function vendor_view_popup_callback() {
    $vendor_id = isset($_POST['vendor_id']) ? $_POST['vendor_id'] : '';
    $vendor_data = get_post_meta($vendor_id);
    // Check if the 'industry' key exists in the $vendor_data array
    $catname = '';
    if (isset($vendor_data['industry'])) {
        // Change the value associated with 'industry' key
        $cat_id = $vendor_data['industry'][0];
        if( $term = get_term_by( 'id', $cat_id, 'product_cat' ) ){
            $catname = $term->name;
        }
        $vendor_data['industry'] = $catname;
    }
    echo json_encode($vendor_data);
    wp_die();
}

// Function  code by vinay
add_action('wp_ajax_vendor_view_send_mail_popup', 'send_mail_popup_callback');
function send_mail_popup_callback() {
    $email = isset($_POST['business_email']) ? $_POST['business_email'] : '';
    if ($email) {
        $site_name = get_bloginfo('name');
        $admin_email = get_option('admin_email');
        $to = $email;
        $message = 'Your Requst Approve by Admin';
        $subject = 'Your Vendor Request Approved By Admin Please Click Here To Active Your Account.';
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            "From: $site_name <$admin_email>"
        );
        $sendmail = wp_mail($to, $subject, $message, $headers);
        if ($sendmail) {
            echo 'Email sent successfully.';
        } else {
            echo 'Error sending email.';
        }
    } else {
        echo 'Email address is not available.';
    }
    wp_die();
}

// Instantiate the plugin class
//$your_arueka_plugin = new Vendor_Request_Table();
/**
 * Register vendor menu.
 * This function adds the top-level menu page for managing vendor requests.
 */
add_action('admin_menu', 'register_vendor_menu');
function register_vendor_menu()
{
    add_menu_page(
        'Vendor Request',
        'Vendor Request',
        'manage_options',
        'vendor_request_list',
        'vendor_menu_page'
    );
}

/**
 * Callback function for the admin menu page.
 * This function renders the content for the vendor request list page.
 */
function vendor_menu_page()
{
    ?>
    <div class="gc-list-wrap">
        <h2>Requested Vendor List</h2>
        <form method="post" action="<?php echo admin_url('admin.php?page=vendor_request_list'); ?>">
            <?php
            $my_products_list = new Vendor_Request_Table();
            $my_products_list->search_box('Search', 'vendor-request');
            $my_products_list->prepare_items();
            $my_products_list->display();
            ?>
        </form>
    </div>
    <?php

}
/**
 * Register vendor submenu page.
 * This function adds a submenu page for vendor view in the WordPress admin menu.
 */
add_action('admin_menu', 'register_vendor_submenu');
function register_vendor_submenu()
{
    add_submenu_page(
        'vendor_request_list',
        'Vendor View',
        'Vendor View',
        'manage_options',
        'vendor_view_page',
        'vendor_view_callback'
    );
    add_submenu_page(
        'vendor_request_list',
        'Arueka Settings',
        'Arueka Settings',
        'manage_options',
        'arueka_settings_page',
        'arueka_settings_callback',
    );
    add_submenu_page(
        'vendor_request_list',
        'Vendor Deny Requests',
        'Vendor Deny Requests',
        'manage_options',
        'vendor_deny_request_page',
        'vendor_deny_page_callback',
    );
    add_submenu_page(
        'vendor_request_list',
        'Addon Request',
        'Addon Request',
        'manage_options',
        'vendor_addon_request_page',
        'vendor_addon_request_callback',
    );
}


/**
 * Submenu page callback function.
 * This function handles the display of Arueka Settings page text and image upload.
 */
function arueka_settings_callback() {
    ?>
    <div class="wrap">
        <h1>Arueka Settings</h1>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <?php wp_nonce_field('arueka_settings_nonce', 'arueka_settings_nonce'); ?>
            <input type="hidden" name="action" value="save_arueka_settings">

            <h2>Vendor Signup form Declaration Message</h2>
            <?php
            $editor_content = get_option('arueka_editor_content');
            wp_editor($editor_content, 'arueka_editor_content', array(
                'textarea_name' => 'arueka_editor_content',
                'textarea_rows' => 10,
                'wpautop'       => true,
            ));
            ?>

            <h2>Vendor Request Popup Heading</h2>
            <?php
            $vendor_request_popup_heading = get_option('vendor_request_popup_heading');
            wp_editor($vendor_request_popup_heading, 'vendor_request_popup_heading', array(
                'textarea_name' => 'vendor_request_popup_heading',
                'textarea_rows' => 10,
                'wpautop'       => true,
            ));
            ?>

            <h2>After signup form submit show heading in popup</h2>
            <?php
            $vendor_success_popup_heading = get_option('vendor_success_popup_heading');
            wp_editor($vendor_success_popup_heading, 'vendor_success_popup_heading', array(
                'textarea_name' => 'vendor_success_popup_heading',
                'textarea_rows' => 10,
                'wpautop'       => true,
            ));
            ?>

            <h2>After signup form submit show message in popup</h2>
            <?php
            $vendor_success_popup_heading2 = get_option('vendor_success_popup_heading2');
            wp_editor($vendor_success_popup_heading2, 'vendor_success_popup_heading2', array(
                'textarea_name' => 'vendor_success_popup_heading2',
                'textarea_rows' => 10,
                'wpautop'       => true,
            ));
            ?>

            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
     require_once plugin_dir_path( __FILE__ ) . 'includes/arueka-save-text-settings.php';
}

add_action('admin_post_save_arueka_settings', 'save_arueka_settings');

function save_arueka_settings() {
    // Check if user has proper capabilities and nonce is valid
    if (!current_user_can('manage_options') || !isset($_POST['arueka_settings_nonce']) || !wp_verify_nonce($_POST['arueka_settings_nonce'], 'arueka_settings_nonce')) {
        return;
    }

    // Update options based on posted data
    if (isset($_POST['arueka_editor_content'])) {
        update_option('arueka_editor_content', wp_kses_post($_POST['arueka_editor_content']));
    }

    if (isset($_POST['vendor_request_popup_heading'])) {
        update_option('vendor_request_popup_heading', wp_kses_post($_POST['vendor_request_popup_heading']));
    }

    if (isset($_POST['vendor_success_popup_heading'])) {
        update_option('vendor_success_popup_heading', wp_kses_post($_POST['vendor_success_popup_heading']));
    }

    if (isset($_POST['vendor_success_popup_heading2'])) {
        update_option('vendor_success_popup_heading2', wp_kses_post($_POST['vendor_success_popup_heading2']));
    }

    // Redirect back to settings page with success message
    $redirect_url = add_query_arg('settings-updated', 'true', admin_url('admin.php?page=arueka_settings_page'));
    wp_redirect($redirect_url);
    exit;
}


/**
 * Submenu page callback function.
 * This function handles the display of deny vendor data list.
 */
function vendor_deny_page_callback()
{
    ?>
    <div class="gc-list-wrap">
        <h2>Vendor Requeste Deny List</h2>
            <?php
            require_once 'includes/VendorDenyListTable.php';
           $deny_list_table = new VendorDenyListTable();
           $deny_list_table->search_box('Search', 'vendor-request');
           $deny_list_table->prepare_items();
           $deny_list_table->display();
            ?>
    </div>
    <?php
}

/**
 * Submenu page callback function.
 * This function handles the display of vendor addon request list.
 */
function vendor_addon_request_callback(){
    ?>
    <div class="gc-list-wrap">
        <h2>Vendor Addon Request</h2>
            <?php
            require_once 'includes/VendorAddonRequestList.php';
           $addon_request_table = new VendorAddonRequestList();
           $addon_request_table->search_box('Search', 'vendor-request');
           $addon_request_table->prepare_items();
           $addon_request_table->display();
            ?>
    </div>
    <?php
}


/**
 * Submenu page callback function.
 * This function handles the display of vendor data and creation of a new vendor.
 */
function vendor_view_callback()
{
     //denyVendorRequest start
    //denyVendorRequest start    

    
    if (isset($_GET['vendor_id']) && isset($_GET['deny_vendor'])) {

        if (isset($_POST['gc_deny'])) {
            $vendor_id = $_GET['vendor_id'];
            $message = $_POST['description'];
            $vendor_data = get_post_meta($vendor_id);
            wp_update_post(
                array(
                    'ID' => $vendor_id,
                    'post_status' => 'draft'
                )
            );

            $business_email = isset($vendor_data['business_email'][0]) ? $vendor_data['business_email'][0] : '';
            if (!empty($business_email)) {
                $site_name = get_bloginfo('name');
                $admin_email = get_option('admin_email');
                $to = $business_email;
                $subject = 'Your Vendor Request Deny By Admin';
                //$message = 'This is the content of the arueka email.';
                $headers = array(
                    'Content-Type: text/html; charset=UTF-8',
                    "From: $site_name <$admin_email>"
                );
                // Send the email
                $sendmail = wp_mail($to, $subject, $message, $headers);
                if ($sendmail) {
                    echo 'Success and email send to user';
                }
            }
        }

        echo '<br>';
        ?>
        <a href="<?php echo admin_url('admin.php?page=vendor_request_list'); ?>" class="button button-go-to-list">Go to List</a>
        <?php
    }
    //denyVendorRequest end

    add_action('wp_head', 'update_user_meta_with_category_name');

    function update_user_meta_with_category_name() {
        // Get the category ID (replace 22297 with your actual category ID)
        $post_id = 22297;

        // Get the categories assigned to the post
        $categories = wp_get_post_categories($post_id);
         var_dump($categories);
    }
    

    //Display vendor data
    if (isset($_GET['vendor_id']) && isset($_GET['view_vendor'])) {
        // Only 'vendor_id' is provided, display vendor data in a form
        $vendor_id = intval($_GET['vendor_id']);
        $vendor_data = get_post_meta($vendor_id);
        ?>
        <div class="gc-wrap-vendor">
			<h2>Vendor Detail Page</h2>
            <?php
                /* show vendor request data */
                require_once plugin_dir_path( __FILE__ ) . 'includes/vendor-request-view.php';
                /* show vendor request data */
                echo '<br>';
                /*show vendor subscription plan*/
                require_once plugin_dir_path( __FILE__ ) . 'includes/vendor-subscription.php';
                /*show vendor subscription plan*/
            ?>
        </div>
        <?php
    }

    if (isset($_GET['vendor_id']) && isset($_GET['create_vendor'])) {
		$goback = admin_url('admin.php?page=vendor_request_list');
        // Both 'vendor_id' and 'create_vendor' are provided, create a new vendor
        $vendor_id = $_GET['vendor_id'];
        $r_post_id = $_GET['vendor_id'];
        $vendor_data = get_post_meta($vendor_id);

        // Extract vendor data
        $store_name = isset($vendor_data['store_name'][0]) ? $vendor_data['store_name'][0] : '';
        $business_license = isset($vendor_data['business_license'][0]) ? $vendor_data['business_license'][0] : '';
        $business_country = isset($vendor_data['business_country'][0]) ? $vendor_data['business_country'][0] : '';
        $business_city = isset($vendor_data['business_city'][0]) ? $vendor_data['business_city'][0] : '';
        $business_settlement = isset($vendor_data['business_settlement'][0]) ? $vendor_data['business_settlement'][0] : '';
        $telephone_number = isset($vendor_data['telephone_number'][0]) ? $vendor_data['telephone_number'][0] : '';
        $industry = isset($vendor_data['industry'][0]) ? $vendor_data['industry'][0] : '';
        $owner_first_name = isset($vendor_data['owner_first_name'][0]) ? $vendor_data['owner_first_name'][0] : '';
        $owner_last_name = isset($vendor_data['owner_last_name'][0]) ? $vendor_data['owner_last_name'][0] : '';
        $business_email = isset($vendor_data['business_email'][0]) ? $vendor_data['business_email'][0] : '';
        $password = isset($vendor_data['password'][0]) ? $vendor_data['password'][0] : '';
        $confirmPassword = isset($vendor_data['confirmPassword'][0]) ? $vendor_data['confirmPassword'][0] : '';
        $business_telephone = isset($vendor_data['business_telephone'][0]) ? $vendor_data['business_telephone'][0] : '';
        // Create the new user
        $username = $business_email;
        if (email_exists($business_email) || empty($business_email)) {

            echo '<div class="gc-vendor-error-message">This email is already in use by another Vendor. Please choose a different email address.</div>';
			
			echo '<br><a href="'.$goback.'" class="button button-go-to-list">Go to List</a>';
        } else {

            // Create new user data
            $user_data = array(
                'user_login' => $username, // Provide a valid username
                'user_email' => $business_email,
                'user_pass' => $password,
                'role' => 'seller' // Assign role as 'aruekaer'
            );

            // Create a new user
            $user_id = wp_insert_user($user_data);

            if (!is_wp_error($user_id)) {
                // User created successfully
                echo '<div class="gc-vendor-success-message">Vendor created successfully. A verification email has been sent to the vendor\'s email address.</div>';
				echo '<br><a href="'.$goback.'" class="button button-go-to-list">Go to List</a>';
        
                // Add user to the WooCommerce aruekaer list
                if (class_exists('WC_aruekaer')) {
                    $aruekaer = new WC_aruekaer($user_id);
                    $aruekaer->save();
                }

                // Optionally, you can update user meta data
                update_user_meta($user_id, 'first_name', $owner_first_name);
                update_user_meta($user_id, 'last_name', $owner_last_name);
            }

            if (!is_wp_error($user_id)) {

                //Set post status as draft
                wp_update_post(
                    array(
                        'ID' => $vendor_id,
                        'post_status' => 'publish'
                    )
                );

                $user = get_user_by('id', $user_id);
                // Add seller role
                $user->add_role('seller');
                $user_id = wp_update_user(
                    array(
                        'ID' => $user_id,
                        'role' => 'seller',
                        'first_name' => $owner_first_name,
                        'last_name' => $owner_last_name,
                    ));
                $user_id = wp_update_user(
                    [
                        'ID' => $user_id,
                        'user_nicename' => $store_name,
                    ]
                );
                // Update user meta with vendor details
                update_user_meta($user_id, 'owner_first_name', $owner_first_name);
                update_user_meta($user_id, 'owner_last_name', $owner_last_name);
                update_user_meta($user_id, 'store_name', $store_name);
                update_user_meta($user_id, 'business_license', $business_license);
                update_user_meta($user_id, 'business_country', $business_country);
                update_user_meta($user_id, 'business_city', $business_city);
                update_user_meta($user_id, 'business_settlement', $business_settlement);
                update_user_meta($user_id, 'telephone_number', $telephone_number);
                update_user_meta($user_id, 'industry', $industry);
                update_user_meta($user_id, 'telephone_number', $telephone_number);

                update_user_meta($user_id, 'dokan_enable_selling', 'yes');

                // Update Dokan settings
                $dokan_settings = array(
                    'store_name' => $store_name,
                    'social' => array(),
                    'payment' => array(),
                    'phone' => $business_telephone,
                    'show_email' => 'yes',
                    'location' => '',
                    'find_address' => '',
                    'dokan_category' => '',
                    'banner' => 0,
                );
                update_user_meta($user_id, 'dokan_profile_settings', $dokan_settings);
                update_user_meta($user_id, 'r_post_id', $r_post_id);

            } else {
                echo '<div class="gc-vendor-error-message">Failed to create Vendor. Please try again.</div>';
            }
        }
    }
}
// shortshort
function arueka_enqueue_files()
{
    // Enqueue Font Awesome CSS
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');

    wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
    $random_version = rand(1, 999999);
    //wp_register_style('your-plugin-admin-styles', plugins_url('/public/css/admin-styles.css', __FILE__));
    wp_register_style('your-plugin-admin-styles', plugins_url('/public/css/admin-styles.css', __FILE__), array(), $random_version);
    wp_enqueue_style('your-plugin-admin-styles');

    //wp_enqueue_script('your-plugin-admin-scripts', plugins_url('/public/js/admin-scripts.js', __FILE__), array('jquery'), null, true);
	
	
    // Localize the script with new data
    $script_data_array = array(
        'ajaxurl' => admin_url('admin-ajax.php'), 
    );
   
	
    wp_enqueue_script('admin-scripts', plugins_url('/public/js/admin-script.js', __FILE__), array(), $random_version);
 
	wp_localize_script('admin-scripts', 'blog', $script_data_array);
    wp_localize_script('admin-scripts', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('admin_enqueue_scripts', 'arueka_enqueue_files');

// add_action('wp_head', 'runtinycode');

// function runtinycode(){
// echo get_option('arueka_editor_content');
// }


// Add Subscription Product Tab
function dokan_add_subscription_product_tab($tabs)
{
    if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) {
        $tabs['subscription_product_tab'] = array(
            'label' => __('Subscription addons', 'dokan'),
            'target' => 'subscription_product_options',
            'class' => array('show_if_product_pack'),
            'icon' => 'dashicons-clock',
        );
    }
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'dokan_add_subscription_product_tab');

function gc_enqueue_arueka_scripts()
{


    // Enqueue script
    //wp_enqueue_script('arueka-script', plugin_dir_url(__FILE__) . 'public/js/admin-script.js', array('jquery'), '1.0', true);
}
//add_action('admin_enqueue_scripts', 'gc_enqueue_arueka_scripts');


function dokan_subscription_product_panel()
{
   require_once plugin_dir_path( __FILE__ ) . 'includes/dokan-subscription-product-panel.php';
}
add_action('woocommerce_product_data_panels', 'dokan_subscription_product_panel');

function save_custom_product_meta_data($post_id)
{
    if (isset($_POST['gc-addon-submit'])) {
        $addon_prefixes = $_POST['select-addon-dropdown'];

        // Get all addon data meta keys for the current product
        $existing_addon_meta_keys = get_post_custom_keys($post_id);
        
        // Loop through existing addon meta keys to identify which ones to delete
        if (!empty($existing_addon_meta_keys)) {
            foreach ($existing_addon_meta_keys as $meta_key) {
                if (strpos($meta_key, 'addon_data_') === 0 && !in_array(substr($meta_key, 11), $addon_prefixes)) {
                    // Delete addon data and permission meta
                    delete_post_meta($post_id, $meta_key);
                    delete_post_meta($post_id, substr($meta_key, 11) . '_permission');
                }
            }
        }

        // Loop through submitted addon prefixes to update meta data
        foreach ($addon_prefixes as $addon_prefix) {
            if (!empty($addon_prefix)) {
                $permission = isset($_POST[$addon_prefix . '_permission']) ? sanitize_text_field($_POST[$addon_prefix . '_permission']) : '';
                $meta_key = 'addon_data_' . $addon_prefix;

                $addon_data = array(
                    'layer1' => array(
                        'min_quantity' => sanitize_text_field($_POST[$addon_prefix . '_layer1_min_quantity']),
                        'max_quantity' => sanitize_text_field($_POST[$addon_prefix . '_layer1_max_quantity']),
                        'price' => sanitize_text_field($_POST[$addon_prefix . '_layer1_price'])
                    ),
                    'layer2' => array(
                        'min_quantity' => sanitize_text_field($_POST[$addon_prefix . '_layer2_min_quantity']),
                        'max_quantity' => sanitize_text_field($_POST[$addon_prefix . '_layer2_max_quantity']),
                        'price' => sanitize_text_field($_POST[$addon_prefix . '_layer2_price'])
                    ),
                    'layer3' => array(
                        'min_quantity' => sanitize_text_field($_POST[$addon_prefix . '_layer3_min_quantity']),
                        'max_quantity' => sanitize_text_field($_POST[$addon_prefix . '_layer3_max_quantity']),
                        'price' => sanitize_text_field($_POST[$addon_prefix . '_layer3_price'])
                    )
                );
                update_post_meta($post_id, $meta_key, serialize($addon_data));
                //permission
                update_post_meta($post_id, $addon_prefix . '_permission', $permission);
            }
        }
    }
}
add_action('save_post', 'save_custom_product_meta_data');

//THIS CODE IS USING FOR ADD AND HIDE CONDITON FOR  ADDITIONAL LOCATION MANGEMENT STYTEM....
add_action('wp_footer', 'hide_multiple_location_checkbox');
function hide_multiple_location_checkbox()
{
    $vendor_id = get_current_user_id();

    $additional_locations = get_user_meta($vendor_id, 'additional_locations', true);

    // Check if $additional_locations is empty and handle it appropriately
    if (empty($additional_locations)) {
        $additional_locations = ''; // Set it to an empty string
    }
    /* else {
           $additional_locations = json_encode($additional_locations); // Convert to JSON for JavaScript
       } */
    ?>
        <script>
            jQuery(document).ready(function ($) {
                var conditionMet = <?php echo !empty($additional_locations) ? $additional_locations : "''"; ?>;
                if (conditionMet === 'No') {
                    $('#multiple-store-location').closest('.dokan-form-group').hide();
                }
            });
        </script>
        <?php
}

// Add action hook to manage bookable products
add_action('wp_footer', 'manage_bookble_products');

function manage_bookble_products()
{
    $vendor_id = get_current_user_id();
    $additional_locations = get_user_meta($vendor_id, 'additional_locations', true);

    // Check if the condition is true
    if ($additional_locations === 'No') {
        ?>
            <script>
                jQuery(document).ready(function ($) {
                    // Hide the div with class dokan-product-listing
                    $('.dokan-product-listing').hide();
                });
            </script>
            <?php
    }
}


//THIS CODE IS USING FOR ADD CONDITON FOR  ADDITIONAL DELIVERY MANGEMENT STYTEM....
add_action('dokan_delivery_time_dashboard_content_before', 'manage_dilevery_time_by_additinal_delivery');
function manage_dilevery_time_by_additinal_delivery()
{
    $vendor_id = get_current_user_id();
    $adelivery_menagement = get_user_meta($vendor_id, 'delivery_menagement', true);
    $current_url = $_SERVER['REQUEST_URI'];
    ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var deliveryManagementValue = '<?php echo esc_js($adelivery_menagement); ?>';
                var currentUrl = '<?php echo esc_js($current_url); ?>';
                var calendarElement = document.getElementById('delivery-time-calendar');
                var wrapperElement = document.querySelector('.dokan-delivery-type-wrapper');
                if (deliveryManagementValue === 'No') {
                    calendarElement.style.display = 'none';
                    wrapperElement.style.display = 'none';
                    // Show a message
                    var messageElement = document.createElement('div');
                    messageElement.innerHTML = "You are not permitted to manage this option. For use this feature contact to admin. <br> Thank you..!";
                    messageElement.style.color = 'red';
                    messageElement.style.marginTop = '20px';
                    wrapperElement.parentNode.insertBefore(messageElement, wrapperElement.nextSibling);
                }
            });
        </script>
        <?php
}

//THIS CODE USING FOR HIDE ADD NEW PRODUCT BUTTONS ON  PRODUCT MENU OPTION
add_action('dokan_dashboard_content_inside_before', 'manage_limit_by_addition_product');
function manage_limit_by_addition_product()
{
    $vendor_id = get_current_user_id();
    $additional_products = get_user_meta($vendor_id, 'additional_products', true);
    $defaults = array(
        'vendor_id' => $vendor_id,
    );
    $staffs = dokan_get_all_vendor_staffs($defaults);
      if (count($staffs['staffs']) > 0) {
        foreach ($staffs['staffs'] as $staff) { 
            update_user_meta($staff->ID,'additional_products_to_staff', $additional_products ); 
        }
    } 

    $additional_products_to_staff  = get_user_meta($vendor_id, 'additional_products_to_staff', true);
    $args = array(
        'author' => $vendor_id,
        'posts_per_page' => -1,  // Set to -1 to retrieve all products
        'post_status' => array('publish', 'draft', 'pending', 'future'),
    );
    $product_query = dokan()->product->all($args);
    $total_product_count = $product_query->found_posts;
    if ($total_product_count >= $additional_products && $total_product_count >= $additional_products_to_staff) {
        ?>
            <script>
                jQuery(document).ready(function ($) {
                    var totalProductCount = <?php echo $total_product_count; ?>;
                    var maxProductCount = <?php echo $additional_products; ?>;
                    if (totalProductCount >= maxProductCount) {
                        $('.dokan-add-product-link a:contains("Add new product"), .dokan-add-product-link a:contains("Import")').hide();
                    }
                });
            </script>
            <?php
    }

}

//THIS CODE USING FOR MANAGE ADDITIONAL STAFF  BY  CONDITONALY....
add_action('dokan_staffs_content_before', 'manage_staff_limt_by_additional_staffs');
function manage_staff_limt_by_additional_staffs()
{
    $vendor_id = get_current_user_id();
    $additional_staff = get_user_meta($vendor_id, 'additional_staff', true);

    $args = array(
        'meta_key' => '_vendor_id',
        'meta_value' => $vendor_id,
    );

    // Perform the query to get the existing staff count
    $existing_staff_query = new WP_User_Query($args);
    $existing_staff_count = $existing_staff_query->get_total();

    ?>
        <script>
            jQuery(document).ready(function ($) {
                if (window.location.href.indexOf("/dashboard/staffs/") > -1) {
                    var additional_staff = <?php echo $additional_staff; ?>;
                    var staffCount = <?php echo $existing_staff_count; ?>;

                    $(window).on('load', function () {
                        // Check the staff count and hide the button if needed
                        if (staffCount >= additional_staff) {
                            $('.dokan-btn.dokan-btn-theme.dokan-right').hide();
                        }
                    });
                }

                // Stop form submission if the maximum staff limit is reached
                $('.vendor-staff.register').on('submit', function (e) {
                    var maxStaffLimit = <?php echo $additional_staff; ?>;
                    var existingStaffCount = <?php echo $existing_staff_count; ?>;

                    if (existingStaffCount >= maxStaffLimit) {
                        alert('You have reached the maximum staff limit.');
                        e.preventDefault();
                    }
                });
            });
        </script>
        <?php
}




//Gc add option to product
// Add checkboxes for dokan subscription custom product fields
//for get_a_quote and business_license
function add_arueka_product_fields()
{
    global $woocommerce, $post;

    echo '<div class="options_group">';

    // Virtual product checkbox
    woocommerce_wp_checkbox(
        array(
            'id' => 'gc_get_a_quote',
            'label' => __('Get a quote', 'woocommerce'),
            'description' => __('Enable this if the product is a Get a quote product (i.e., no shipping required).', 'woocommerce')
        )
    );
	woocommerce_wp_checkbox(
        array(
            'id' => 'gc_business_license',
            'label' => __('Business License', 'woocommerce'),
            'description' => __('Enable this if the business license user show the product subscription).', 'woocommerce')
        )
    );

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_arueka_product_fields');

//Save arueka dokan subscription custom product fields
//for get_a_quote and business_license
function save_arueka_product_fields($post_id)
{
    // Virtual product checkbox
    $virtual = isset($_POST['gc_get_a_quote']) ? 'yes' : 'no';
    update_post_meta($post_id, 'gc_get_a_quote', $virtual);
	
	$bus_license = isset($_POST['gc_business_license']) ? 'yes' : 'no';
    update_post_meta($post_id, 'gc_business_license', $bus_license);
}
add_action('woocommerce_process_product_meta', 'save_arueka_product_fields');




