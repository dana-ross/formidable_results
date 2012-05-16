<?php
/*
Plugin Name: Formidable Results
Description: Display results from Formidable forms
Version: 1.0
Author URI: http://davidmichaelross.com
Author: Dave Ross
*/

include(dirname(__FILE__)."/FormidableResults_List_Table.inc");

class FormidableResults {
  
  /**
   * Test if the Formidable plugin is activated before allowing this
   * plugin to activate
   */
  public static function activate() {
 
    if (is_plugin_active( 'formidable/formidable.php')) {
      require_once ( WP_PLUGIN_DIR . '/formidable/formidable.php' );
    }
    else {
      // deactivate dependent plugin
      deactivate_plugins(__FILE__);
      exit ('Formidable Results requires the Formidable Forms plugin to be installed & activated.');
    }
  }

  /**
   * Register this plugin in the admin menu (under Formidable)
   */
  public static function admin_menu() {
    
    add_submenu_page(FRM_PLUGIN_NAME, FRM_PLUGIN_TITLE .' | '. __('Settings', 'formidable'), __('Results', 'formidable'), 'frm_change_settings', FRM_PLUGIN_NAME.'-results', array(__CLASS__, 'render'));
    
    // Hide the "Pro Form Entries" admin page if Pro isn't turned on
    // This plugin duplicates that page's functionality
    if(class_exists('FrmUpdate')) {
      $frm_update = new FrmUpdate();
      if(!$frm_update->pro_is_installed_and_authorized()) {
        remove_submenu_page(FRM_PLUGIN_NAME, FRM_PLUGIN_NAME.'-entries');
      }
    }
    
  }
  
  /**
   * Render the appropriate page body
   */
  public static function render() {
    
    if(isset($_REQUEST['entry'])) {
      self::single(intval($_REQUEST['entry']));
    }
    else {
      echo self::renderTemplate("formidable_results.tpl.php");
    }
    
  }
  
  /**
   * Render a single form submission
   * @param int $entry_id 
   */
  public static function single($entry_id) {
    
    $entry = FrmEntry::getOne($entry_id, true);
    $data = array(
      'entry_id' => $entry_id,
      'form_name' => FrmForm::getName($entry->form_id),
      'created_at' => $entry->created_at,
      'name' => $entry->name,
      'metas' => $entry->metas,
    );
       
    echo self::renderTemplate("formidable_detail.tpl.php", $data);
    
  }
  
  /**
   * Cheap template renderer (every plugin needs one)
   * @param string $template template filename (current dir)
   * @param array $data template variables
   * @return string HTML 
   */
  private static function renderTemplate($template, Array $data = array()) {
    extract($data);
    ob_start();
    include($template);
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }
}

add_action('admin_menu', array( 'FormidableResults', 'admin_menu' ), 30);
register_activation_hook( __FILE__, array('FormidableResults', 'activate'));
