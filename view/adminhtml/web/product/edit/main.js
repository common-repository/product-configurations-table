( function ($) {
  "use strict";

$.widget("pektsekye.optionConfigurations", {
  
  _create: function(){   		    

    $.extend(this, this.options); 

    this._on({
      "change select, input": $.proxy(this.setChanged, this),
    });
     
  },
  

  setChanged : function(){
    $('#ocf_changed').val(1);     
  }   

});

})(jQuery);