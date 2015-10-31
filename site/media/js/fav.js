$(document).ready(function() {
jQuery(document).on("click", "input.before", function(){
   var $this = $(this);
   jQuery.ajax({       
      url: 'addfav.php',
      type: 'POST',
      data: {'id': $this.closest("div").attr("id"),is_ajax: 1},
      success: function(html) {
        $this.removeClass('before');
        $this.addClass('after');
      }, 
      error: function() {
        jQuery('#error').html('<div>Error! Unable to add favourite.</div>');
      }
    });
});

jQuery(document).on("click", "input.after", function(){
   var $this = $(this);
   jQuery.ajax({       
      url: 'removefav.php',
      type: 'POST',
      data: {'id': $this.closest("div").attr("id"),is_ajax: 1},
      success: function(html) {
        $this.removeClass('after');
        $this.addClass('before');
      }, 
      error: function() {
        jQuery('#error').html('<div>Error! Unable to remove favourite.</div>');
      }
    });
});
});

<!--<div id="32"><input type="button" class="button before"/></div><div id="33"><input type="button" class="button before"/></div>-->