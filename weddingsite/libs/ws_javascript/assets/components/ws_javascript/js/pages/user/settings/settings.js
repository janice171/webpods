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
WS.webValidator = 0;
WS.personalValidator = 0;
WS.socialValidator = 0;
WS.lastUpdateValid = true;


WS.checkLinks = function(event){
    if ( WS.lockLinks ){
        event.preventDefault();
        alert("You MUST enter a website URL before continuing");
        return false;
    }
    return true;
};
WS.updateElement = function(id,value) {
        
    /* Validate the form before submitting */
    if (  !WS.lastUpdateValid ) {
        
         WS.lastUpdateValid = true;
         return true;
    }
    var selectorId = '#'+id;
    var form = $(selectorId).closest('form');
    var formId = $(form).attr('id');
    var valid = false;
    
    /* Website */
    if ( formId.indexOf("website") != -1 ) {        
        valid = WS.webValidator.valid();
        /* Empty check, valid() omits this */
        if ( valid ) {         
            if ( ($(selectorId).hasClass('text')) && ($(selectorId).hasClass('required')) ) {             
                if ( $(selectorId).val() == '' ) {                   
                    valid = false;
                }
            }
            /* Re-check for the website address */
            if ( id == "userSettingsWebsiteAddress" ) {
                var validArray = value.match(/^[0-9a-z]*$/);
                if ( validArray == null ) valid = false;
                
            }
        }
       
    }
    
    /* Personal */
    if ( formId.indexOf("personal") != -1 ) {      
        valid = WS.personalValidator.valid();
        /* Empty check, valid() omits this */
        if ( valid ) {        
            if ( ($(selectorId).hasClass('text')) && ($(selectorId).hasClass('required')) ) {              
                if ( $(selectorId).val() == '' ) {                  
                    valid = false;
                }
            }
        }
    }

    /* Social */
    if ( formId.indexOf("social") != -1 ) {
        value =  encodeURIComponent(value);
        valid = WS.socialValidator.valid();
        /* Empty check, valid() omits this */
        if ( valid ) {        
            if ( ($(selectorId).hasClass('text')) && ($(selectorId).hasClass('required')) ) {              
                if ( $(selectorId).val() == '' ) {                  
                    valid = false;
                }
            }
        }
        
    }
    
    
    /* Post the update */
    if ( valid ) {
        var urlParameters = "command=ajaxUpdate&element="+id+"&val="+value;
        $.ajax({  
            type: "GET",  
            url: WS.settingsAJAXLink,  
            data: urlParameters,  
            success: function() {}
        });
    
    }
};
        
// Check for generally unwanted characters
jQuery.validator.addMethod("validcharsgeneral", function (value) {
    var result = true;
    // unwanted character list
    var iChars = "!@#$%^&*()+=[]\\\';,/{}|\":<>?";
    for (var i = 0; i < value.length; i++) {
        if (iChars.indexOf(value.charAt(i)) != -1) {
            WS.lastUpdateValid = false;
            return false;
        }
    }   
    return result;

}, "Invalid characters entered");
 
// Check for URL allowed characters
jQuery.validator.addMethod("validcharsURL", function (value) {
          var valid = false;
          var validArray = value.match(/^[0-9a-z]*$/);
          if ( validArray != null ) valid = true;
          if ( !valid ) WS.lastUpdateValid = false;
          return valid;
  }, "Please use the letters a-z or digits with no spaces.");
  
  
// Check for unwanted address characters
jQuery.validator.addMethod("validcharsaddress", function (value) {
    var result = true;
    // unwanted character list
    var iChars = "!@#$%^&*()=[]\\\;/{}|\"<>?";
    for (var i = 0; i < value.length; i++) {
        if (iChars.indexOf(value.charAt(i)) != -1) {
            WS.lastUpdateValid = false;
            return false;
        }
    }
    return result;

}, "Invalid characters entered");
 

// Send these elements to the backend
jQuery.validator.addMethod("tobackend", function (value, element) {
    var result = true;
    var id = $(element).attr("id");
    /* Look for a checkbox and adjust the value */
    var type = $(element).attr("type");
    if ( type == "checkbox" ) {
        value = 0;
        if ( $(element).attr("checked") )value = 1;
    }   
    WS.updateElement(id, value);
    /* Unlock the links if website has been entered */
    if ( id == "userSettingsWebsiteAddress" )   WS.lockLinks = false;
    return result;

}, "");
  
/* Page forms */
$(document).ready(function(){
    
    /* Validation */
    WS.webValidator = $("#websiteForm").validate({
        rules: {
      
            userSettingsPassword: {  
                minlength: 8
            },
            userSettingsPasswordRepeat: {
                minlength: 8,
                equalTo: "#userSettingsPassword"
            },
            userSettingsWebsiteAddress: {
                maxlength: 63,
                remote: "' . $ajaxLink . '"
            }           
        },
        messages: {
            
            userSettingsPassword: {
                rangelength: jQuery.format("Enter at least {0} characters.")
            },
            userSettingsPasswordRepeat: {
                minlength: jQuery.format("Enter at least {0} characters."),
                equalTo: "Not the same as the password entered"
            },
            userSettingsWebsiteAddress: {
                remote: "Sorry, this website is already in use.",
                url: "Sorry - this is not a valid URL."
        
            },    
            userSettingsDatepicker: {
                required: "This field is required."
            },
            
            userSettingsTheme: {
                required: "This field is required."
            },
            userSettingsPartner1: {
                required: "This field is required."
            },
            
            userSettingsPartner2: {
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
  
    /* Checkbox handling */
    $(".checkbox").bind("click", function() {
        var id = $(this).attr("id");
        var value = 0;
        if ( $(this).attr("checked") )value = 1;
        WS.updateElement(id, value);
    });


    WS.personalValidator = $("#personalForm").validate({
        rules: {
      
            userSettingsEmail: {
                remote: "' . $ajaxLink . '"
            }
        },
        messages: {
            
            userSettingsEmail: {
                remote: "Sorry, this email address is already in use.",
                email: "Sorry - this is not a valid email address."
        
            },    
            userSettingsFirstName: {
                required: "This field is required."
            },         
            userSettingsLastName: {
                required: "This field is required."
            },
            userSettingsAddressStreet: {
                required: "This field is required."
            },
            userSettingsTel: {
                required: "This field is required."
            },
            userSettingsCity: {
                required: "This field is required."
            },
            userSettingsCountry: {
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
  
    WS.socialValidator = $("#socialForm").validate();
    
    /* Add mouseouts on tobackend classes */
    $(".tobackend").mouseout(function(){
        var id = $(this).attr("id");
        var value = $(this).val();
        WS.updateElement(id, value);

    });
    
    /* Add the lock links processing */
    $("a").live("click",function(event){
        WS.checkLinks(event);
    });
    
    /* Tabs */
    jQuery("#main-tabs" ).tabs();
        
    /* Confirmation alerts */
    $('#userSettingsWebsiteSubmit').click( function() {
    	alert("Thank You - your Website settings have been updated");
    });
    
    /* Confirmation alerts */
    $('#userSettingsPersonalSubmit').click( function() {
    	alert("Thank You - your Personal settings have been updated");
    });
    
    /* Confirmation alerts */
    $('#userSettingsSocialSubmit').click( function() {
    	alert("Thank You - your Social settings have been updated");
    });
});

