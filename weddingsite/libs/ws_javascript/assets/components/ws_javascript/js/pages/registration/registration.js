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

jQuery.validator.setDefaults({
  success: "valid"
});

// Check for generally unwanted characters
  jQuery.validator.addMethod("validcharsgeneral", function (value) {
          var result = true;
          // unwanted character list
          var iChars = "!@#$%^*=[]\\\';/{}|\"<>?";
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
          var iChars = "!$%^*=\\\'/{}|\"<>?";
          for (var i = 0; i < value.length; i++) {
             if (iChars.indexOf(value.charAt(i)) != -1) {
                return false;
             }
          }
          return result;

  }, "Invalid characters entered");
  
  // Check for URL allowed characters for the website name
  jQuery.validator.addMethod("validcharsURL", function (value) {
          var valid = false;
          var validArray = value.match(/^[0-9a-z]*$/);
          if ( validArray != null ) valid = true;
          if (!valid)
          	{
          	jQuery('#website-name-suffix').remove();
          	}
          return valid;
  }, "Please use the letters a-z or digits with no spaces.");


jQuery(document).ready(function(){
    
    jQuery("#registrationForm").validate({
      rules: { 
             registrationEmail: {    
                remote: WS.registrationFormAjaxLink
            }, 
            userPassword: {  
                minlength: 8
            }, 
            userPasswordRetype: { 
                minlength: 8, 
                equalTo: "#userPassword" 
            },
            userRegistrationWebsiteName: {
             maxlength: 63,
             remote: WS.registrationFormAjaxLink
            }
        }, 
        messages: { 
            userPassword: { 
                rangelength: jQuery.format("Enter at least {0} characters.") 
            }, 
            userPasswordRetype: { 
                minlength: jQuery.format("Enter at least {0} characters."), 
                equalTo: "Not the same as above!" 
            }, 
            registrationEmail: { 
                remote: "Sorry, already in use.",
                email: "Sorry - this is not valid."
            },  
            userRegistrationWebsiteName: {
                remote: "Sorry, this website name is already in use.",
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
            
        errorElement: "strong"
          
  });
  
  /* Initialise Sisyphus */
  jQuery('#registrationForm').sisyphus();

});

