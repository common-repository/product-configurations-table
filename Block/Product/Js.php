<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Block_Product_Js {

  protected $_ocfProduct;
  protected $_ocfOption;  
  
  protected $_productOptions;  
  protected $_ocfProductData;
  protected $_ocfOptionIds;
  protected $_ocfOptionValues;  
  protected $_ocfValueTitles;
  protected $_ocfOIdByVId;
  protected $_ocfVIdsByOId;  
  protected $_ocfCombinations;


	public function __construct(){
    
    include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Product.php' );
    $this->_ocfProduct = new Pektsekye_OptionConfigurations_Model_Product();
    
    include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Option.php' );
    $this->_ocfOption = new Pektsekye_OptionConfigurations_Model_Option();    			 		  			
	}


  public function getProductId(){
    global $product;
    return (int) $product->get_id();              
  }
  
  
  public function getProductOptions() {  
    if (!isset($this->_productOptions)){
      $this->_productOptions = $this->_ocfOption->getProductOptions($this->getProductId());
    }    
    return $this->_productOptions;              
  }
    

  public function getOcfProductData()
  {
    if (!isset($this->_ocfProductData)){
      $this->_ocfProductData = $this->_ocfProduct->getOptions($this->getProductId());
    }    
    return $this->_ocfProductData;  
  }
  
  
  public function getOptionIds()
  {
    if (!isset($this->_ocfOptionIds)){
      $optionIds = array();            
      $options = $this->getOcfProductData();
      if (!empty($options)){  
        $ids = array_map('intval', explode(',', $options['option_ids']));
        foreach($this->getProductOptions() as $option){ 
          $oId = (int) $option['option_id'];
          if ($option['type'] == 'field' && in_array($oId, $ids)){
            $optionIds[] = $oId;
          }
        }
      }
      $this->_ocfOptionIds = $optionIds;
    }
    return $this->_ocfOptionIds;    
  }   


  public function initOcfOptions(){
    
    $options = array(); 
    $titles = array();
    $oIdByVId = array();
    $vIdsByOId = array();
    $combinations = array();
    
    $optionIds = $this->getOptionIds();
         
    $rows = $this->_ocfOption->getOptions($this->getProductId()); 
     
    $k = 1;
    $prevValues = array();
    foreach($rows as $row){
      $comb = array();
      foreach($row as $columnInd => $v){
        if (empty($v) || !isset($optionIds[$columnInd])){
          continue;
        }
         
        $comb[] = isset($prevValues[$columnInd][$v]) ? $prevValues[$columnInd][$v] : $k;
                
        if (isset($prevValues[$columnInd][$v])){
          continue;
        }        
        
        $oId = $optionIds[$columnInd];
        $options[$columnInd][] = array('id' => $k, 'title' => $v);
        $titles[$k] = $v;
        $oIdByVId[$k] = $oId;
        $vIdsByOId[$oId][] = $k;      
        $prevValues[$columnInd][$v] = $k;
        $k++; 
      }
      $combinations[] = $comb;  
    }
    
    foreach($options as $k => $values){
      usort($values, array($this, 'sortValues'));
      $options[$k] = $values;
    }
    
    $this->_ocfOptionValues = $options;
    $this->_ocfValueTitles = $titles;  
    $this->_ocfOIdByVId = $oIdByVId;
    $this->_ocfVIdsByOId = $vIdsByOId; 
    $this->_ocfCombinations = $combinations;            
  }  
  
  
  function sortValues($v1, $v2)
  { 
    $a = $v1['title'];
    $b = $v2['title'];    
    if ($a == $b) {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }  
  
  
  public function getTableOptions(){
    $optionIds = $this->getOptionIds();
    $tableOptions = array();
    foreach($this->getProductOptions() as $option){
      if (in_array($option['option_id'], $optionIds)){
        $tableOptions[] = $option;
      }
    } 
    return $tableOptions;
  }
  
   
  public function getTableOptionValues(){  
    return $this->_ocfOptionValues;
  }
 
 
  public function getValueTitles(){  
    return $this->_ocfValueTitles;
  }


  public function getOIdByVId(){ 
    return $this->_ocfOIdByVId;
  }  
  
    
  public function getVIdsByOId(){ 
    return $this->_ocfVIdsByOId;
  }
  
      
  public function getCombinations(){ 
    return $this->_ocfCombinations;
  }    
    
    
  public function toHtml(){
  
    $this->initOcfOptions();
    
    include_once(Pektsekye_OCF()->getPluginPath() . 'view/frontend/templates/product/js.php');
  }


}
