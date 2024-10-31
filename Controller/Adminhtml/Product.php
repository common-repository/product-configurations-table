<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Controller_Adminhtml_Product {


	public function __construct() {
	
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') , 15);// 15 to call it after WooCommerce
    	
    add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_tab') , 99 , 1 );	
    add_action( 'woocommerce_product_data_panels', array( $this, 'add_tab_fields') );   							  				
	}
  
  
  public function add_product_tab( $tabs ) {
    $tabs['ocf_product_data'] = array(
        'label' => __('PC Table', 'product-configurations-table' ),
        'target' => 'ocf_product_data',
        'class'  => array(),
        'priority' => 91 //after the Custom Options tab              
    );
    return $tabs;
  } 
  
  
  public function enqueue_admin_scripts(){
    global $pagenow;
    if ((isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit') || ('post-new.php' == $pagenow && isset($_GET['post_type']) && $_GET['post_type'] == 'product')){  
      wp_enqueue_script('ocf_product_calendar', Pektsekye_OCF()->getPluginUrl() . 'view/adminhtml/web/product/edit/main.js', array('jquery', 'jquery-ui-widget'));
      wp_enqueue_style('ocf_product_calendar', Pektsekye_OCF()->getPluginUrl() . 'view/adminhtml/web/product/edit/main.css');		    
    }    
  }
    
  
	public function add_tab_fields() { 
    include_once(Pektsekye_OCF()->getPluginPath() . 'Block/Adminhtml/Product/Edit/Tab/Options.php');
    $block = new Pektsekye_OptionConfigurations_Block_Adminhtml_Product_Edit_Tab_Options();
    $block->toHtml();
  }
  

}
