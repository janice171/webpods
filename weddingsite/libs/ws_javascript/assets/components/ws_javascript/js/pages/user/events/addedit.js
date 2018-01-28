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

// Check for generally unwanted characters, allow ":" for time fields
  jQuery.validator.addMethod("validcharsgeneral", function (value) {
          var result = true;
          // unwanted character list
          var iChars = "!@#$%^&*()=[]\\\';,/{}|\"<>?";
          for (var i = 0; i < value.length; i++) {
             if (iChars.indexOf(value.charAt(i)) != -1) {
                return false;
             }
          }
          return result;

  }, "Invalid characters entered");
  
  // Check for unwanted address characters
  jQuery.validator.addMethod("validcharsaddress", function (value) {
          var result = true;
          // unwanted character list
          var iChars = "!@#$%^&*()=[]\\\;/{}|\"<>?";
          for (var i = 0; i < value.length; i++) {
             if (iChars.indexOf(value.charAt(i)) != -1) {
                return false;
             }
          }
          return result;

  }, "Invalid characters entered");
  
  /* Main form */
  
  $(document).ready(function(){
    $("#eventAddEditForm").validate({
    	
       messages: { 
       
            userEventsName: {
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
  
   /* Top menu active selection */
     $(".sf-menu li a").each(function(){
         var title = $(this).attr("title");
         if ( title == "Guest and Event Planner") $(this).addClass("active");
      });
         
  });
