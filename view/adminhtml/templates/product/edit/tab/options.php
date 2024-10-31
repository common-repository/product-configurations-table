<?php
if (!defined('ABSPATH')) exit;
?>
<div class="ocf-container">
<?php if (!$this->getProductOptionsPluginEnabled()): ?>
  <div class="lensesprescription-create-ms"><?php echo __('Please, install and enable the <a href="https://wordpress.org/plugins/product-options-for-woocommerce/" target="_blank">Product Options</a> plugin.', 'product-configurations-table'); ?></div>
<?php  elseif (!$this->getDropdownOptionExists()): ?> 
  <div class="lensesprescription-create-ms"><?php echo sprintf(__('Create product options with type Field and save the product. (<a href="%s" target="_blank">screenshot</a>)', 'product-configurations-table'), Pektsekye_OCF()->getPluginUrl() . 'view/adminhtml/web/product/edit/field_options.png'); ?></div>
<?php else: ?>
  <div id="ocf_options">
    <input type="hidden" id="ocf_changed" name="ocf_changed" value="0">           
    <div>
      <p class="form-field">
        <label for="ocf_text_option_select"><?php echo __('Table options', 'product-configurations-table'); ?></label>  <span class="woocommerce-help-tip" data-tip="<?php echo htmlspecialchars(__("Product options that will be combined and displayed in the Product Configurations table.", 'product-configurations-table'));?>"></span>    
        <select id="ocf_text_option_select" name="ocf_text_option[]" multiple="multiple" size="10">
        <?php foreach ($this->getTextFieldSelectOptions() as $key => $value ): ?>
          <option value="<?php echo esc_attr($key); ?>" <?php echo in_array($key, $this->getOptionIds()) ? 'selected="selected"' : ''; ?> ><?php echo esc_html($value); ?></option>
        <?php endforeach; ?>
        </select>
      </p>       
    </div>           
  </div> 
   <script type="text/javascript">
    jQuery('#ocf_options').optionConfigurations({});        
  </script>                 
<?php endif; ?>     
</div>

    