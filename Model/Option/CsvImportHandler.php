<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Model_Option_CsvImportHandler {

    protected $_option;   
       
    protected $_delimiter = ',';
    
    
    public function __construct(){
      include_once(Pektsekye_OCF()->getPluginPath() . 'Model/Option.php');		
      $this->_option = new Pektsekye_OptionConfigurations_Model_Option();                                                
    }


    public function importOptionsFromCsvFile($file, $mode){
      if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        throw new Exception(__('Please select a .csv file and then click the Import button', 'product-configurations-table'));
      }
      
      $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
      if (strtolower($fileExt) != 'csv') {
        throw new Exception(sprintf(__('Invalid file type "%s". Please upload a .csv file.', 'product-configurations-table'), $file['name']));
      }        
      
      $rows = array();
      
      ini_set("auto_detect_line_endings", true);
            
      if (($handle = fopen($file['tmp_name'], "r" )) !== false) {
        while (($row = fgetcsv($handle, 0, $this->_delimiter)) !== false) {
          $rows[] = $row;
        }
        fclose($handle);
      }

      if (count($rows) == 0) {
        throw new Exception(sprintf(__('The file "%s" is empty', 'product-configurations-table'), $file['name']));
      }             
      
      $productIdsBySku = $this->_option->getProductIdsBySku();
      
      $data = array();
      $productIds = array();
      
      $lastSku = '';
      $changePart = false;      
      $part = 0;      
      $countRows = 0;    
      foreach ($rows as $rowIndex => $row) {
    
        if (count($row) == 1 && $row[0] === null) // skip empty lines
          continue;
        
        $productSku = isset($row[0]) ? trim($row[0]) : '';
        if (empty($productSku)){
          Pektsekye_OCF()->setMessage(sprintf(__('Row #%d was not imported. The "product_sku" field should not be empty.', 'product-configurations-table'), $rowIndex), 'error_lines');
          continue;                  
        }

        if (!isset($productIdsBySku[$productSku])){
          Pektsekye_OCF()->setMessage(sprintf(__('Row #%d was not imported. The product with SKU or ID "%s" does not exist.', 'product-configurations-table'), $rowIndex, $productSku), 'error_lines');
          continue;          
        }      
        
        $row[0] = $productIdsBySku[$productSku]; //save product id instead of product SKU in the database                  
        $productIds[] = $productIdsBySku[$productSku];                   
   
        $data[$part][] = $row;
                   
        if ($countRows % 1000 == 0){
          $changePart = true;
        }
          
        if ($changePart && $productSku != $lastSku){  
          $part++;
          $changePart = false;            
        }
        
        $lastSku = $productSku;
        $countRows++; 
               
      }
      
      if ($mode == 'delete_old'){
        $this->_option->emptyTable();      
      } else {                              
        $this->_option->deleteOptionsOfProducts($productIds);
      }
      
      foreach ($data as $dataPart) {
        $this->_option->addOptions($dataPart);        
      } 
                           
    }
    
    
}
