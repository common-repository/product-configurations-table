<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Block_Adminhtml_Product_Edit_Tab_Options {


  protected $_ocfOption;
  protected $_ocfProduct;
  
  protected $_productOptions;    
  protected $_ocfProductData;  
    
    
	public function __construct() {

    include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Option.php' );
    $this->_ocfOption = new Pektsekye_OptionConfigurations_Model_Option();
    
    include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Product.php' );
    $this->_ocfProduct = new Pektsekye_OptionConfigurations_Model_Product();
  }



  public function getProductId() {
    global $post;    
    return (int) $post->ID;  
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
    $options = $this->getOcfProductData();
    return !empty($options) ? explode(',', $options['option_ids']) : array();
  } 
    

  public function getDropdownOptionExists()
  {     
    $optionExists = false;
    foreach ($this->getProductOptions() as $option){
      if ($option['type'] == 'field'){
        $optionExists = true;
        break;
      }
    }    
    return $optionExists; 
  }    


  public function getTextFieldSelectOptions() {
    $options = array('' => __('-- select text field options --', 'product-configurations-table'));
    foreach($this->getProductOptions() as $optionId => $option){
      if ($option['type'] == 'field'){
        $options[$optionId] = $option['title'];
      }    
    }
    return $options;
  } 
  
  
  public function getProductOptionsPluginEnabled(){
    return function_exists('Pektsekye_PO');  
  }
   
  
  public function toHtml() {
  
    echo '<div id="ocf_product_data" class="panel woocommerce_options_panel hidden">';
    
    include_once(Pektsekye_OCF()->getPluginPath() . 'view/adminhtml/templates/product/edit/tab/options.php');
    
    echo ' </div>';
  }

}
