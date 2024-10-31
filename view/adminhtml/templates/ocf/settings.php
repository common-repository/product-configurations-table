<?php 
if (!defined('ABSPATH')) exit;

$message = $this->getMessage();
 
?>
<div><h3><?php echo __('Product Configuration Options', 'product-configurations-table'); ?></h3></div>
<?php if (isset($message['error'])): ?>
  <div id="woocommerce_errors" class="error"><p><?php echo $message['error']; ?></p></div>
<?php endif;?>
<?php if (isset($message['text'])): ?>    
  <div id="message" class="updated notice notice-success is-dismissible below-h2">
  <p><?php echo $message['text']; ?></p>
  <?php if (isset($message['error_lines'])): ?>
    <textarea rows="4" cols="100"><?php echo implode($message['error_lines'], "\r\n"); ?></textarea>
  <?php endif;?>     
  <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __( 'Dismiss this notice.', 'woocommerce' );?></span></button></div>
<?php endif;?>
<div class="ocf-section">
  <div><h4><?php echo __('Import Configuration Options', 'product-configurations-table'); ?>:</h4></div>    
  <form action="?page=ocf_settings&action=importOptions" method="post" enctype="multipart/form-data">
      <fieldset class="ocf-fieldset">              
          <input type="file" name="import_file" class="input-file required-entry"/>
          <input type="checkbox" name="delete_old" id="ocf_delete_old" value="1"/>
          <label for="ocf_delete_old"><?php echo __('delete existing values', 'product-configurations-table'); ?></label>
          &nbsp;&nbsp;          
          <input name="submit" id="submit" class="button button-primary" value="<?php echo __('Import CSV', 'product-configurations-table'); ?>" type="submit">                                  
      </fieldset>
  </form>         
  <div><h4><?php echo __('Export Configuration Options', 'product-configurations-table'); ?>:</h4></div>     
  <form id="export_form" action="?page=ocf_settings&action=exportOptions" method="post" enctype="multipart/form-data">
      <fieldset class="ocf-fieldset">
          <input name="submit" id="submit" class="button button-primary" value="<?php echo $this->hasOptions() ? __('Export CSV', 'product-configurations-table') :  __('Export sample CSV', 'product-configurations-table') ; ?>" type="submit">                                
      </fieldset>
  </form>      
</div>
     

