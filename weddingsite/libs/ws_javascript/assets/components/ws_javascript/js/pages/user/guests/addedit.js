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

/* Setup */

/* WS object */
WS = new Object();
WS.currentLink = 0;

// Check for generally unwanted characters
jQuery.validator.addMethod("validcharsgeneral", function (value) {
    var result = true;
    // unwanted character list
    var iChars = "!@#$%^&*()+=[]\\\';,/{}|\":<>?";
    for (var i = 0; i < value.length; i++) {
        if (iChars.indexOf(value.charAt(i)) != -1) {
            return false;
        }
    }
    return result;

}, "Invalid characters entered");
  
/* Main form */
$(document).ready(function(){
    $("#guestAddEditForm").validate({
    
        messages: { 
            
            userGuestsEmail: { 
                email: "Sorry - this is not a valid email address."
				
            },		
            userGuestsName: {
                required: "This field is required."
            }, 
            userGuestsEmail: {
                required: "This field is required."
            }
        }, 
        errorPlacement: function(error, element) {                                                    
            element.parent().children("#formvalidationerror").append(error);  			
        },
        invalidHandler: function() { 
            alert("Please correct the errors on the form and re-submit it"); 
        }
    });
  
    /* Add the events list AJAX processing */
    $(".ajaxLink").bind("click",function(event){
        event.preventDefault();
        var yesno = "No";
        if ( $(this).hasClass('off') ) {
            yesno = "Yes";
        }
        var args = "&value="+yesno;
        WS.currentLink = this;
        var url = $(this).attr('href');
        $.get(url,args,function(response){
            if ( response == "Yes" ) {
                $(WS.currentLink).removeClass('off');
                $(WS.currentLink).addClass('on');
            } else {
                $(WS.currentLink).removeClass('on');
                $(WS.currentLink).addClass('off');
            }
        })  
    });
     
    /* Add guest event checkbox handling */
    $(".guestCheckBoxClass").change(function(){
        if($(this).is(":checked")){
            $(this).next("label").addClass("guestLabelSelected");
        }else{
            $(this).next("label").removeClass("guestLabelSelected");
        }
    });
        
    /* Top menu active selection */
    $(".sf-menu li a").each(function(){
        var title = $(this).attr("title");
        if ( title == "Guest and Event Planner") $(this).addClass("active");
    });
         
});
  
  
