<?php
class Email_Notify_Table extends WP_List_Table
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
        $action = $this->current_action();

        $data = $this->table_data();
        usort($data, array(&$this, 'usort_reorder'));

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage,
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
      
        $this->items = $data;
    }

    // Sorting function
    function usort_reorder($a, $b)
    {
        // If no sort, default to user_login
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'ID';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';
        // Determine sort order
        $result = strnatcmp($a[$orderby], $b[$orderby]);
        
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" name="notification[]" />',
            'email' => 'Email',
            '_date' => 'Date',
            'notify_date' => 'Notify date',
            'notified' => 'Last sent',
            'date' => 'Registered'
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
        return array(
            'date' => array('date', true),
            '_date' => array('_date', true),
            'notify_date' => array('notify_date', true),
            'notified' => array('notified', true)
        );
    }    

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        global $wpdb;
        $data = array();

        $notifications = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}email_notifications");
        if($notifications && !is_wp_error( $notifications )){
            foreach($notifications as $notification){

                $beforeAfter = ((get_option( 'default_before_after_months' )) ? get_option( 'default_before_after_months' ) : '');
                $modifiedDate = $notification->date;
                
                if($beforeAfter < 0 || $beforeAfter > 0){
                    $modifiedDate = get_notify_date($beforeAfter, $modifiedDate);
                }

                $array = [
                    'ID' => $notification->ID,
                    'email' => $notification->email,
                    '_date' => date("F, Y", strtotime($notification->date)),
                    'notify_date' => date("F, Y", strtotime($modifiedDate)),
                    'notified' => ((strtotime($notification->notified) > 0) ? date("F, Y", strtotime($notification->notified)) : 'null'),
                    'date' => date("F j, Y, g:i a", strtotime($notification->date))
                ];
                $data[] = $array;
            }
        }

        
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case $column_name:
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    public function column_email($item){
        $actions = array(
            'view' => '<a href="?page=en-notifications&action=edit&notification='.$item['ID'].'">Edit</a>',
            'delete' => '<a href="?page=en-notifications&action=delete&notification='.$item['ID'].'">Delete</a>',
        );

        return sprintf('%1$s %2$s', $item['email'], $this->row_actions($actions));
    }

    public function get_bulk_actions(){
        $actions = array(
            'delete' => 'Delete',
        );
        return $actions;
    }

    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="notification[]" value="%s" />', $item['ID']
        );
    }

    // All form actions
    public function current_action(){
        global $wpdb;
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete' && isset($_REQUEST['notification'])) {
            if(is_array($_REQUEST['notification'])){
                $ids = $_REQUEST['notification'];
                foreach($ids as $ID){
                    $wpdb->query("DELETE FROM {$wpdb->prefix}email_notifications WHERE ID = $ID");
                }
            }else{
                $ID = intval($_REQUEST['notification']);
                $wpdb->query("DELETE FROM {$wpdb->prefix}email_notifications WHERE ID = $ID");
            }
        }
    }

} //class
