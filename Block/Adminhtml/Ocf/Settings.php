<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Block_Adminhtml_Ocf_Settings {


  protected $_option;  


	public function __construct() {
    include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Option.php');		
		$this->_option =  new Pektsekye_OptionConfigurations_Model_Option(); 
	}
 
 
 
  public function toHtml()
  {
    include_once( Pektsekye_OCF()->getPluginPath() . 'view/adminhtml/templates/ocf/settings.php');
  }



  public function hasOptions() 
  { 
    return $this->_option->hasOptions();
  }
  
  
  
  public function getMessage() 
  {
    return Pektsekye_OCF()->getMessage();
  }
  
  
}
