// JavaScript Document
 var AjaxUpdater = function(url){
        $(".positive").click(function () {
                        var usr = $(".positive").val();
                        if (usr.length >= 2) {
                         $("#breadcrumbs").html('<img src="loader.gif" align="absmiddle">&nbsp;Checking availability...');
                         $.ajax({
                             type: "POST",
                             //url: "index.php?option=com_test&view=check_user",
							 url: "url",
							 async: true,
                             //data: "username=" + usr,
                             success: function (msg) {
                             $("#breadcrumbs").ajaxComplete(function (event, request, settings) {
                             if (msg == 'OK') {
                                $(".positive").removeClass('object_error'); // if necessary
                                    $(".positive").addClass("object_ok");
                             }
                             else {
                                   $(".positive").removeClass('object_ok'); // if necessary
                                   $(".positive").addClass("object_error");
                                   $(this).html(msg);
                             }
                           });
                          }
                        });
                   }    
              }); 
};
 