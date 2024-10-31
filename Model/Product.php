<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Model_Product {  
  
  protected $_wpdb;
  protected $_mainTable;      
      
      
  public function __construct(){
    global $wpdb;
    
    $this->_wpdb = $wpdb;   
    $this->_mainTable = "{$this->_wpdb->base_prefix}optionconfigurations_product";          		
  }	


  public function saveOptions($productId, $data)
  {
    $productId = (int) $productId;
    $optionIdsStr = implode(',', array_map('intval', $data['option_ids']));

    $select = "SELECT lp_product_id FROM {$this->_mainTable} WHERE product_id = {$productId} LIMIT 1";       
    $lpProductId = $this->_wpdb->get_var($select);    
    
    if (!empty($lpProductId)){       
      $this->_wpdb->query("UPDATE {$this->_mainTable} SET option_ids = '{$optionIdsStr}'WHERE lp_product_id = {$lpProductId}");                      
    } else {    
      $this->_wpdb->query("INSERT INTO {$this->_mainTable} SET product_id = {$productId}, option_ids = '{$optionIdsStr}'");                
    }           
  }


  public function getOptions($productId)
  {    
    $productId = (int) $productId;   
    $select = "SELECT option_ids FROM {$this->_mainTable} WHERE product_id = {$productId} LIMIT 1";       
    return $this->_wpdb->get_row($select, ARRAY_A);            
  }
  
  
  public function deleteProductOptions($productId){    
    $productId = (int) $productId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");                                   
  }

}
