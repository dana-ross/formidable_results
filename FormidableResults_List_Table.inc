<?php

if(!class_exists('WP_List_Table')) {
  include(ABSPATH."wp-admin/includes/class-wp-list-table.php");
}

/**
 * Displays a table of form responses, styled to be part of a WP admin page
 * @see http://codex.wordpress.org/Class_Reference/WP_List_Table
 */
class FormidableResults_List_Table extends WP_List_Table {

  function __construct() {
    parent::__construct(array(
        'plural' => 'responses',
        'singular' => 'response',
        'ajax' => true,
    ));
  }
  
  /////////////
  // Selection
  /////////////
  
  function get_per_page() {
     return 20;
  }
  
  function prepare_items() {
    
    $frmEntry = new FrmEntry();
    
    // Sorting
    $order_by = '';
    if(isset($_REQUEST['orderby'])) {
      if(!isset($_REQUEST['order']) || $_REQUEST['order'] == 'asc') {
        $sort_dir = 'asc';
      }
      else {
        $sort_dir = 'desc';
      }
      if(array_key_exists($_REQUEST['orderby'], $this->get_sortable_columns())) {
        $order_by = " ORDER BY {$_REQUEST['orderby']} {$sort_dir} ";
      }
    }
    
    // Pagination
    if(isset($_REQUEST['paged'])) {
      $paged = intval($_REQUEST['paged']);
    }
    else {
      $paged = 1;
    }
    $this->set_pagination_args(array(
      'total_items' => $frmEntry->getRecordCount(),
      'per_page' => $this->get_per_page(),
    ));

    // Results
    $entries = $frmEntry->getPage($paged, $this->get_per_page(), '', $order_by);
    $rows = array();
    foreach($entries as $entry) {
      
      $rows[] = array(
        'id' => $entry->id,
        'form_name' => FrmForm::getName($entry->form_id),
        'name' => $entry->name,
        'created_at' => $entry->created_at,
      );
      
    }
    $this->items = $rows;
    $this->_column_headers = $this->get_columns();
        
  }
  
  ///////////
  // Columns
  ///////////
  function get_columns() {
    $headers = array(
      'id' => __('ID'),
      'form_name' => __('Form'),
      'name' => __('Name'),
      'created_at' => __('Submitted'),
    );
    
    return $headers;
  }
  
  function get_column_info() {
    return array(
      $this->get_columns(),
      array(), // No hidden columns
      $this->get_sortable_columns(),
    );
  }
  
  function get_sortable_columns() {
    return array(
      'id' => array('id',true),
      'form_name' => array('form_name',true),
      'name' => array('name',true),
      'created_at' => array('created_at',true),
    );
  }

  /////////////////////
  // Column formatters
  /////////////////////
  
  function column_id(array $item) {
    $url = $_SERVER['REQUEST_URI']."&entry={$item['id']}";
    $html = '<a href="'.$url.'">'.$item['id'].'</a>';
    return $html;
  }
  
  function column_default(array $item, $column_name) {
    return $item[$column_name];
  }
}