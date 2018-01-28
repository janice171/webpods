<?php
/**
 * Wedding site User Settings processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 *
 */

/* Determine if we need to include colorbox or not */
$user = $modx->user;
$userId = $user->get('id');
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( !$weddingUser ) return;
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$weddingAttributes = $modx->getObject('weddingUserAttribute', array('user' => $userId));
if ( !$weddingAttributes ) return;
$userSettingsFirstLogin = "";
$websiteComplete = false;
$personalComplete = false; 
if ( $attributes['websiteDetails'] == 1 ) $websiteComplete = true;
if ( $attributes['personalDetails'] == 1 ) $personalComplete = true;

/* Lock all links until a valid website has been entered */
$lockLinks = "false";
if ($attributes['website'] == ""  ) $lockLinks = "true";

/* Check for first login */
if ( ($websiteComplete == false) && ($personalComplete == true) ) {
   
    $chunkName="userSettingsFirstLogin";
    /* Uncomment this to get the welcome box $includeColorBox = true; */
    $weddingAttributes->set('personalDetails', 0);
    $weddingAttributes->set('websiteDetails', 0);
    $weddingAttributes->save();
}

/* Web site links */
$websiteLinks = $modx->runSnippet('userAdminWebPageLinksCommonJS');

$modx->regClientStartupScript($websiteLinks, true);

/* AJAX link */
$context = $modx->context->get('key');
$userSettingsAJAXPage = $modx->getOption('userSettingsAJAXPage');
$ajaxLink = $modx->makeURL($userSettingsAJAXPage, $context, "", "full");
$wsAJAXLink = '"' . $ajaxLink . '"';

$setup =  '<script>
    
    WS.lockLinks = ' . $lockLinks . ';
    WS.settingsAJAXLink = ' . $wsAJAXLink . ';
        
</script>';  

$modx->regClientStartupScript($setup, true);    

$modx->setPlaceholder('userSettingsFirstLogin', $userSettingsFirstLogin);

/* Other includes */
$assets_url = $modx->getOption('assets_url');
$wsJQueryAssets = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');

/* Set our Validation jQuery paths */
$validatorPath = $wsJQueryAssets . 'js/jquery-validation-1.8.1';

/* Include the validation CSS */
$validationCSS = 'assets/templates/wedding/styles/validation.css';
$modx->regClientCSS($validationCSS);

/* Include the includes */
$html1 = $validatorPath . '/lib/jquery.metadata.js';
$html2 = $validatorPath . '/jquery.validate.js';
$modx->regClientStartupScript($html1);
$modx->regClientStartupScript($html2);

/* Include the initialise code */
$initialise = '<script>jQuery.validator.setDefaults({
  debug: ' . $debug . ',
  success: "valid"
});
  
</script>';

/* WS Javascript */
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Settings  */
$settings = $javascriptPath . "js/pages/user/settings/settings.js";
$modx->regClientStartupScript($settings);

/* Date Picker */
$datePicker = '<script>$(document).ready(function(){
  $(function() {
    $( "#userSettingsDatepicker" ).datepicker({
      showOn: "both",
      buttonImage: "' . $assets_url. 'templates/wedding/images/calendar.gif",
      buttonImageOnly: true,
      minDate: -2, maxDate: "+48M +28D",
      changeYear: true,
      changeMonth: true,
      dateFormat: "dd/mm/y",
      onClose: function(dateText, inst) {
           var value = dateText;
           id = $(inst).attr("id");
           WS.updateElement(id,value);
      }
    });
  });
    
});</script>';

$modx->regClientStartupScript($initialise, true);
$modx->regClientStartupScript($datePicker, true);
