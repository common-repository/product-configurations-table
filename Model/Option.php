<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Model_Option {
           
  protected $_wpdb;          
  protected $_mainTable;   
      
  public function __construct(){
    global $wpdb;    
    $this->_wpdb = $wpdb;  
    $this->_mainTable = "{$this->_wpdb->base_prefix}optionconfigurations_product_options";              		
  }	


  public function getProductOptions($productId)
  {
    $productOptions = array();
    if (function_exists('Pektsekye_PO')){
      include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option.php' );
      $optionModel = new Pektsekye_ProductOptions_Model_Option();
      $productOptions = $optionModel->getProductOptions($productId);
    }
    return $productOptions;  
  }


  public function getOptions($productId){ 
     
      $productId = (int) $productId;
      
      $select = "
        SELECT option_0, option_1, option_2, option_3, option_4, option_5, option_6, option_7, option_8, option_9
        FROM {$this->_mainTable} 
        WHERE  product_id = {$productId}	            
      ";
      
      return (array) $this->_wpdb->get_results($select, ARRAY_N);
  }
  
  
  public function getOptionsData(){    
    
      $select = "
        SELECT product_id, option_0, option_1, option_2, option_3, option_4, option_5, option_6, option_7, option_8, option_9
        FROM {$this->_mainTable}   		    
		    ORDER BY product_id, option_0, option_1, option_2, option_3, option_4, option_5, option_6, option_7, option_8, option_9	            
      ";
      
      $rows = $this->_wpdb->get_results($select, ARRAY_A);
      
      $productSkuById = array_flip($this->getProductIdsBySku());
      
      foreach($rows as $k => $row){
        $pId = $row['product_id'];
        if (isset($productSkuById[$pId]))
          $rows[$k]['product_id'] = $productSkuById[$pId];
      }
      
      return $rows; 
  }  


  public function getSampleOptionsData(){
  
    return array(
            array("QS03308","20","PA 6.6","0.20","50"),
            array("QS03308","20","PA 6.6","0.20","55"),
            array("QS03308","20","PA 6.6","0.20","60"),
            array("QS03308","20","PA 6.6","0.20","65")  
           );
  }
  
  
  public function hasOptions(){    
    
    $select = "SELECT product_id FROM {$this->_mainTable} LIMIT 1";
    
    $result = $this->_wpdb->get_var($select);

    return !empty($result); 
  }  
  
  
  public function addOptions($data){

    $sqlValuesStr = '';    
    foreach ($data as $row){
    
      $values = array_values($row);
      
      $cell = (int) $values[0];  // product_id    
      for ($i=1; $i <= 10; $i++){
        $value = isset($values[$i]) ? esc_sql(trim($values[$i])) : '';
        $cell .= ",'" . $value . "'";      
      }              
      $sqlValuesStr .= ($sqlValuesStr != '' ? ',' : '') . "({$cell})";  
    }
    
    $this->_wpdb->query("INSERT INTO {$this->_mainTable} (product_id, option_0, option_1, option_2, option_3, option_4, option_5, option_6, option_7, option_8, option_9) VALUES {$sqlValuesStr}");                  

  }

         
  public function getProductIdsBySku(){
    
    $select = "
      SELECT IF(LENGTH(postmeta.meta_value)>0, postmeta.meta_value, postmeta.post_id) as product_sku, postmeta.post_id as product_id 
      FROM {$this->_wpdb->posts} AS posts 
      JOIN {$this->_wpdb->postmeta} AS postmeta 
        ON postmeta.post_id = posts.ID 
      WHERE posts.post_type = 'product' AND postmeta.meta_key = '_sku'         
    ";
    $result = (array) $this->_wpdb->get_results($select, ARRAY_A);

    $productIds = array();
    foreach ($result as $row){
      $productIds[$row['product_sku']] = $row['product_id'];
    }
     
    return $productIds;   
  }
  
  
  public function deleteOptionsOfProducts($productIds){   
    if (count($productIds) == 0){
      return;
    }         
    $productIds = array_map('intval', $productIds);     
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id IN (" . implode(',', $productIds) . ")");                                   
  }
  
  
  public function deleteProductOptions($productId){    
    $productId = (int) $productId;      
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");                                   
  }


  public function emptyTable()
  {      
    $this->_wpdb->query("TRUNCATE TABLE {$this->_mainTable}"); 
  }	
  				

}
