<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class MenuListPage extends WP_List_Table {

    function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'menu',     //singular name of the listed records
            'plural'    => 'menus',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );

    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'name':
            case 'section':
                return $item->$column_name;
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_title($item){

        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&menu=%s">Редактировать</a>',$_REQUEST['page'],'edit',$item->id),
            'delete'    => sprintf('<a href="?page=%s&action=%s&menu=%s">Удалить</a>',$_REQUEST['page'],'delete',$item->id),
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:#c0c0c0">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item->name,
            /*$2%s*/ $item->id,
            /*$3%s*/ $this->row_actions($actions)
        );
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            $item->id              //The value of the checkbox should be the record's id
        );
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'name'     => 'Название',
            'section'    => 'Секция'
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'name'     => array('name',false),     //true means it's already sorted
            'section'    => array('section',false),
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }

    }

    function prepare_items() {
        global $wpdb;
        $per_page = 5;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $query = "SELECT * FROM " . $wpdb->prefix . "cat_menu";
        $data = $wpdb->get_results($query);

        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title';
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc';
            $result = strcmp($a[$orderby], $b[$orderby]);
            return ($order==='asc') ? $result : -$result;
        }

        usort($data, 'usort_reorder');

        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items/$per_page)
        ) );
    }


}