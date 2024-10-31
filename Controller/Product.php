<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Controller_Product {


	public function __construct() {
    add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts')); 	
    add_action('woocommerce_before_add_to_cart_button', array($this, 'display_options_on_product_page'), 11);	//after product options							  				
	}


  public function enqueue_frontend_scripts(){
    wp_enqueue_script('ocf_product_view', Pektsekye_OCF()->getPluginUrl() . 'view/frontend/web/main.js', array('jquery', 'jquery-ui-widget', 'wp-util'));      
    wp_enqueue_style('ocf_product_view', Pektsekye_OCF()->getPluginUrl() . 'view/frontend/web/main.css');  		  			
  }
  
  
	public function display_options_on_product_page() { 
    include_once(Pektsekye_OCF()->getPluginPath() . 'Block/Product/Js.php');
    $block = new Pektsekye_OptionConfigurations_Block_Product_Js();
    $block->toHtml();
  }
  

}
