( function ($) {
  "use strict";

  $.widget("pektsekye.optionConfigurations", {
  		   
    inputByOId : {},
    selectedValues : {},
    
       
    _create: function(){   		    

      $.extend(this, this.options);
      
      var template = wp.template('ocf-table');
      
      this.element.find('#pofw_option_'+this.optionIds[0]).closest('.field').before(template({}));
      
      var hasRequired = false;
           
      var oId, div, input, field;
      var l = this.optionIds.length;
      for (var i=0;i<l;i++){ 
        oId = this.optionIds[i];
        input = this.element.find('#pofw_option_'+oId);
        this.inputByOId[oId] = input;        
        field = input.closest('.field');
        if (field.hasClass('pofw-required')){
          hasRequired = true;
        }
        field.hide();                 
      } 
      
      if (hasRequired){
        this.element.closest('form').on("submit", $.proxy(this.validate, this));
      }
      
      this._on({
        "click .ocf-table-cell:not(.ocf-selected):not(.ocf-disabled)": $.proxy(this.selectValue, this),
        "click .ocf-unselect": $.proxy(this.unselectValue, this),    
      });      
               
    },
    
    
    selectValue: function(e){ 
      var cell = $(e.target);
      var vId = cell.data('id');
      cell.addClass('ocf-selected');
      cell.closest('td.ocf-column').find('.ocf-table-cell:not(.ocf-selected)').addClass('ocf-disabled');      
      var oId = this.oIdByVId[vId];
      this.inputByOId[oId].val(this.valueTitles[vId]);
      this.selectedValues[oId] = vId;
      this.updateAvailableCombinations();      
    },
       
                   
    unselectValue: function(e){
      e.stopPropagation(); 
      var cell = $(e.target).closest('.ocf-table-cell');
      var vId = cell.data('id');      
      cell.removeClass('ocf-selected');
      cell.closest('td.ocf-column').find('.ocf-table-cell').removeClass('ocf-disabled');
      var oId = this.oIdByVId[vId];
      this.inputByOId[oId].val(''); 
      this.selectedValues[oId] = null;
      this.updateAvailableCombinations();                 
    },
    
    
    updateAvailableCombinations: function(){
       
      var i,ii,l,ll,oId,vId,comb,found;
      
      var selectedComb = [];
            
      l = this.optionIds.length;
      for (i=0;i<l;i++){ 
        oId = this.optionIds[i];
        vId = this.selectedValues[oId] ? this.selectedValues[oId] : null;
        selectedComb.push(vId);
      }  
      
      var enabledVIds = {};
      
      l = this.combinations.length;
      ll = selectedComb.length;
      for (i=0;i<l;i++){
        comb = this.combinations[i];
        found = true;
        for (ii=0;ii<ll;ii++){
          if (selectedComb[ii] && selectedComb[ii] != comb[ii]){ //selected column and the selected value does not match
            found = false;
          }
        }
        
        if (found){
          for (ii=0;ii<ll;ii++){
            if (selectedComb[ii] == null){ // not selected column
              enabledVIds[comb[ii]] = 1;
            }
          }         
        }
        
      }

      l = this.optionIds.length;
      for (i=0;i<l;i++){ 
        oId = this.optionIds[i];
        if (!this.selectedValues[oId]){ // not selected column
          ll = this.vIdsByOId[oId].length;
          for (ii=0;ii<ll;ii++){
            vId = this.vIdsByOId[oId][ii];
            if (enabledVIds[vId]){
              $('#ocf_table_cell_'+vId).removeClass('ocf-disabled');
            } else {
              $('#ocf_table_cell_'+vId).addClass('ocf-disabled');           
            }
          }            
        }
      }
    
    },
    
      
    validate : function(){
      
      var formValid = true;
      
      var ocfField = this.element.find('.ocf-field');

      ocfField.removeClass('pofw-not-valid');
      ocfField.find('.pofw-required-text').remove();
      
      var valid = true;
      
      var oId;
      var l = this.optionIds.length;
      for (var i=0;i<l;i++){ 
        oId = this.optionIds[i];
        if (!this.selectedValues[oId]){
          valid = false;
          break;
        }
      }

      if (!valid){
        ocfField.addClass('pofw-not-valid');
        ocfField.append('<div class="pofw-required-text">'+ this.requiredText +'</div>');        
        formValid = false;          
      }
      
      return formValid;
    }        
        
  });

})(jQuery);
    


