/**
 * Wedding site external javascript files (ws_javascript)
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * A library package to support the functionality of the
 * wedding sites project
 *
 *
 * @package ws_javascript
 */
/* For generic pages we need to submit tiny then the generic form */

WS.genericSubmit = function(){
   var formData = $("#userEditGenericForm").serialize();
   $.ajax({  
        type: "POST",  
        url: WS.genericAJAXProcessorLink,  
        data: formData,  
        success: function() {
            
            var commonData = $("#userEditCommonForm").serialize();
            commonData = commonData+"&userEditCommonFormSubmit=submit";
            var commonURL = window.location.href;
            $.ajax({  
                type: "POST",  
                url: commonURL,  
                data: commonData,  
                success: function() {
                    window.location.reload(true);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("An Error has occured");
        }
    });
    return false;
};

