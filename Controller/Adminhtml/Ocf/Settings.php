<?php
if (!defined('ABSPATH')) exit; 

class Pektsekye_OptionConfigurations_Controller_Adminhtml_Ocf_Settings {

  protected $_option;
  protected $_importHandler;  
 
    
	public function __construct() {
    include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Option.php');		
		$this->_option = new Pektsekye_OptionConfigurations_Model_Option();  
		
    include_once( Pektsekye_OCF()->getPluginPath() . 'Model/Option/CsvImportHandler.php');  
    $this->_importHandler = new Pektsekye_OptionConfigurations_Model_Option_CsvImportHandler();		   			
	}
	
 
  public function execute(){
      
    if (!isset($_GET['action'])){
      return;
    }
  
    switch($_GET['action']){               
      case 'importOptions':       
        if (isset($_FILES['import_file'])){                        
          try {            
            $mode = isset($_POST['delete_old']) && $_POST['delete_old'] == 1 ? 'delete_old' : 'add_new';                              
            $this->_importHandler->importOptionsFromCsvFile($_FILES['import_file'], $mode);
            Pektsekye_OCF()->setMessage(__('Product Configuration Options CSV file has been imported.', 'product-configurations-table'));                 
          } catch (Exception $e){
            Pektsekye_OCF()->setMessage(__('Product Configuration Options CSV file has not been imported.', 'product-configurations-table') .' '. $e->getMessage(), 'error');                    
          }
        }
      break;       
      case 'exportOptions':
        if (isset($_POST['submit'])){           
                                                          
          $array = array();
          
          $data = $this->_option->getOptionsData();                       
          if (empty($data)){
            $data = $this->_option->getSampleOptionsData();
          }
          
          $array = array_merge($array, $data);
        
          $this->download_send_headers("product_configuration_options.csv");              
          echo $this->array2csv($array);
          die();
        }         
      break;                                                                                                
    }    
  }		
  
  	
  public function array2csv(array &$array)
  {
    if (count($array) == 0) {
     return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    //fputcsv($df, array_keys(reset($array)));
    foreach ($array as $row) {
      fputcsv($df, $row);
    }
    fclose($df);
    return ob_get_clean();
  }


  public function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
  }


}
