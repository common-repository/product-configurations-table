<?php
/**
 * Plugin Name: Product Configurations Table
 * Description: Displays product options as a table with selectable values.
 * Version: 1.0.0
 * Author: Pektsekye
 * Author URI: http://hottons.com
 * License: GPLv2     
 * Requires at least: 4.7
 * Tested up to: 6.3.2
 *
 * Text Domain: product-configurations-table
 *
 * WC requires at least: 3.0
 * WC tested up to: 8.2.0
 * 
 * @package OptionConfigurations
 * @author Pektsekye
 */
if (!defined('ABSPATH')) exit;

final class Pektsekye_OptionConfigurations {

  protected static $_instance = null;

  protected $_pluginUrl; 
  protected $_pluginPath;    
  
  public $_message = array();
  
  
  public static function instance(){
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
      self::$_instance->initApp();
    }
    return self::$_instance;
  }


  public function __construct(){
    $this->_pluginPath = plugin_dir_path(__FILE__);
    $this->_pluginUrl  = plugins_url('/', __FILE__);
  }


  public function initApp(){
    $this->includes();
    $this->init_hooks();
    $this->init_controllers();
  }
  
  
  public function includes(){  
    include_once('Model/Observer.php'); 
    if ($this->is_request('admin')){      
      include_once('Block/Adminhtml/Ocf/Settings.php');                         
    }                  
  }
  

  private function init_hooks(){ 
    new Pektsekye_OptionConfigurations_Model_Observer(); 
    add_action('admin_menu', array($this, 'set_admin_menu' ), 70);             
  }    


  private function init_controllers(){

		if ($this->is_request('frontend')){
      include_once('Controller/Product.php');
      new Pektsekye_OptionConfigurations_Controller_Product();    	
    } elseif ($this->is_request('admin')){ 
      global $pagenow;
      if ((isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit') || ('post-new.php' == $pagenow && isset($_GET['post_type']) && $_GET['post_type'] == 'product')){         
        include_once('Controller/Adminhtml/Product.php');
        new Pektsekye_OptionConfigurations_Controller_Adminhtml_Product();                    
      }            
      if (isset($_GET['page']) && $_GET['page'] == 'ocf_settings') {
        include_once('Controller/Adminhtml/Ocf/Settings.php');
        add_action('init', array(new Pektsekye_OptionConfigurations_Controller_Adminhtml_Ocf_Settings(), 'execute'));
      }          
    }     	  
  } 


  public function set_admin_menu() {
    add_menu_page(_x('PC Table', 'Admin menu', 'product-options-for-woocommerce'), _x('PC Table', 'Admin menu', 'product-configurations-table'), 'manage_woocommerce', 'ocf_settings', array(new Pektsekye_OptionConfigurations_Block_Adminhtml_Ocf_Settings(), 'toHtml'), 'dashicons-list-view');  
  }
  

  public function is_request($type){
    switch ($type) {
      case 'admin' :
        return is_admin();        
      case 'ajax' :
        return defined('DOING_AJAX');
      case 'cron' :
        return defined('DOING_CRON');
      case 'frontend' :
        return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
    }
  } 
  
  
  public function getPluginUrl(){
    return $this->_pluginUrl;
  }
  
  
  public function getPluginPath(){
    return $this->_pluginPath;
  }  
    
    
  public function setMessage($message, $type = 'text') {
    if ($type == 'error_lines'){
      $this->_message[$type][] = $message;        
    } else {    
      $this->_message[$type] = $message;                 
    }    
  }


  public function getMessage() {
    return $this->_message;
  }
  
      
}


function Pektsekye_OCF(){
	return Pektsekye_OptionConfigurations::instance();
}

include_once('Setup/Install.php');  
register_activation_hook(__FILE__, array('Pektsekye_OptionConfigurations_Setup_Install', 'install'));

// If WooCommerce plugin is installed and active.
if (in_array('woocommerce/woocommerce.php', (array) get_option('active_plugins', array())) || in_array('woocommerce/woocommerce.php', array_keys((array) get_site_option('active_sitewide_plugins', array())))){
  Pektsekye_OCF();
}





