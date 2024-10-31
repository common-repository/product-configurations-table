<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Model_Observer {  

  protected $_ocfProduct;        
  protected $_ocfOption;                
      
  public function __construct(){           
    include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Product.php' );
    $this->_ocfProduct = new Pektsekye_OptionConfigurations_Model_Product();
    
    include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Option.php' );
    $this->_ocfOption = new Pektsekye_OptionConfigurations_Model_Option();
          
    add_action('woocommerce_process_product_meta', array($this, 'save_product_options'));         
		add_action('delete_post', array($this, 'delete_post'));    	          		
  }	  


 
  public function save_product_options($post_id){
    if (isset($_POST['ocf_changed']) && $_POST['ocf_changed'] == 1){
      $productId = (int) $post_id;         
      $optionIds = array_map('intval', $_POST['ocf_text_option']);
      if (isset($optionIds[0]) && $optionIds[0] < 1){
        unset($optionIds[0]);
      }

      $data = array('option_ids' => $optionIds);
      
      $this->_ocfProduct->saveOptions($productId, $data);                     
    }
  }
    
	
	public function delete_post($id){
		if (!current_user_can('delete_posts') || !$id || get_post_type($id) != 'product'){
			return;
		}
    $this->_ocfOption->deleteProductOptions($id);   		
    $this->_ocfProduct->deleteProductOptions($id);             
	}		
		
}
