<?php
// Define a class Vendor_Deny_List_Table which extends WP_List_Table
namespace arueka\includes;
class VendorAddonRequestList extends \WP_List_Table
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
    }

    /**
     * Prepare items for display.
     * This method retrieves data and prepares it for display in the table.
     */
    public function prepare_items()
    {
        $search = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';

        $args = array(
            'post_type' => 'vendor_request',
            'posts_per_page' => -1,
            'post_status' => array('publish'),
            'orderby' => array('post_status' => 'ASC', 'date' => 'DESC'),
            's' => $search, // Add search parameter
        );
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

        $posts = new \WP_Query($args);
        $data = array();
        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                $post_id = get_the_ID();
                $meta_data = get_post_meta($post_id);
                $status = get_post_status();
                /* if(get_post_status()=='publish'){
                                $status ='Pending';
                            } */
                $user_data = array(
                    'ID' => $post_id,
                    'date_submitted' => get_the_date(),
                    'status' => $status,
                    'business_name' => isset($meta_data['store_name'][0]) ? $meta_data['store_name'][0] : '',
                    'telephone_number' => isset($meta_data['business_telephone'][0]) ? $meta_data['business_telephone'][0] : '',
                    'trading_as_name' => '',
                    'mobile_number' => isset($meta_data['telephone_number'][0]) ? $meta_data['telephone_number'][0] : '',
                    'island' => isset($meta_data['industry'][0]) ? $meta_data['industry'][0] : '',
                    'business_email' => isset($meta_data['business_email'][0]) ? $meta_data['business_email'][0] : '',
                    'owner_first_name' => isset($meta_data['owner_first_name'][0]) ? $meta_data['owner_first_name'][0] : '',
                    'owner_last_name' => isset($meta_data['owner_last_name'][0]) ? $meta_data['owner_last_name'][0] : '',
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
        $sortable = '';
        $this->_column_headers = array($columns, $hidden, $sortable);
        $data = array_slice($data, $offset, $per_page);
        $this->items = $data;
    }

    // Method to define default columns
    public function get_columns()
    {
        $columns = array(
            // 'cb' => '<input type="checkbox" checked  />',
            // 'date_submitted' => 'Date Submitted',
            // 'status' => 'Status',
            'business_name' => 'Business Name',
            'telephone_number' => 'Telephone Number',
            'trading_as_name' => 'Trading As Name',
            'mobile_number' => 'Mobile Number',
            'island' => 'Island',
            'business_email' => 'Email ID',
            'owner_first_name' => 'Owner First Name',
            'owner_last_name' => 'Owner Last Name',
            'date_of_birth' => 'Owner Date of Birth',
            'action' => 'Action'
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

        public function column_default($item, $column_name)
    {

        switch ($column_name) {
            case 'action':
                $view_url = admin_url('admin.php?page=vendor_view_page&vendor_id=' . $item['ID'] . '&view_vendor=true');
                echo '<a href="' . esc_url($view_url) . '" class="button button-primary">View</a>';
                $confirm_approve_msg = "Are you sure you want to approve this vendor?"; // Confirmation message
                $approve_url = admin_url('admin.php?page=vendor_view_page&vendor_id=' . $item['ID'] . '&create_vendor=true');
                echo '<a href="' . esc_url($approve_url) . '" class="button button-primary" onclick="return confirmApprove(\'' . esc_js($confirm_approve_msg) . '\')">Approve</a>';
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
