<?php
if (!defined('ABSPATH')) exit;

$optionTableValues = $this->getTableOptionValues();
$tableOptions = $this->getTableOptions();
?>
<?php if (count($this->getOptionIds()) > 0): ?>
  <script id="tmpl-ocf-table" type="text/html">
    <div class="field ocf-field">  
      <table class="ocf-configurations-table">
        <tr>            
        <?php foreach($tableOptions as $op): ?>
          <th><?php echo htmlspecialchars($op['title']); ?></th>
        <?php endforeach; ?>
        </tr>
        <tr>            
        <?php foreach($tableOptions as $k => $op): ?>
          <td class="ocf-column">
            <?php if(isset($optionTableValues[$k])): ?>                    
             <?php foreach($optionTableValues[$k] as $v): ?>
              <div class="ocf-table-cell" id="ocf_table_cell_<?php echo $v['id']; ?>" data-id="<?php echo $v['id']; ?>"><?php echo htmlspecialchars($v['title']); ?><span class="ocf-unselect" title="<?php echo __('Unselect', 'product-configurations-table'); ?>">×</span></div>                       
             <?php endforeach; ?>
            <?php endif; ?>                                          
          </td>
        <?php endforeach; ?>
        </tr>                  
      </table>
    </div>       
  </script>   
  <script type="text/javascript">
      jQuery("#pofw_product_options").optionConfigurations({ 
        requiredText    : "<?php echo __('This field is required.', 'product-configurations-table'); ?>",              
        optionIds       : <?php echo json_encode($this->getOptionIds()); ?>,  
        valueTitles     : <?php echo json_encode($this->getValueTitles()); ?>,
        oIdByVId        : <?php echo json_encode($this->getOIdByVId()); ?>,
        vIdsByOId       : <?php echo json_encode($this->getVIdsByOId()); ?>,
        combinations    : <?php echo json_encode($this->getCombinations()); ?>                                                                                                                                                     
      });
  </script>        
<?php endif; ?>
