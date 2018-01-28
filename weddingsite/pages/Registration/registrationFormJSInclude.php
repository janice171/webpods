<?php
/**
 * Wedding site Registration processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Set our jQuery paths */
$validatorPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/').'js/jquery-validation-1.8.1';

/* Include the validation CSS */
$modx->regClientCSS('assets/templates/wedding/styles/validation.css');

/* Include the includes */
$modx->regClientStartupScript($validatorPath . '/lib/jquery.metadata.js');
$modx->regClientStartupScript($validatorPath . '/jquery.validate.js');

/* Ajax Link */
$registrationFormAjax = $modx->makeURL($modx->getOption('registrationAJAXPage'), 
                                $modx->context->get('key'), '', 'full');
$registrationFormAjaxLink = '"' . $registrationFormAjax . '"';

$ready = '<script>
    
        /* WS object */
        WS = new Object();
        
        WS.registrationFormAjaxLink= ' . $registrationFormAjaxLink . ';
            
</script>';

/* Load Sisyphus */
$sisyphusPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/').'js/sisyphus-master/';
$sisyphus = $sisyphusPath . 'sisyphus.min.js';
$modx->regClientStartupScript($sisyphus);

/* Now registration */
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');
$registration = $javascriptPath . "js/pages/registration/registration.js";
$modx->regClientStartupScript($registration);

/* Date Picker */
$datePicker = '<script>$(document).ready(function(){
  $(function() {
    $( "#userSettingsDatepicker" ).datepicker({
      showOn: "both",
      buttonImage: "' . $modx->getOption('assets_url'). 'templates/wedding/images/calendar.gif",
      buttonImageOnly: true,
      minDate: -2, maxDate: "+48M +28D",
      changeYear: true,
      changeMonth: true,
      dateFormat: "dd/mm/y"
    });
  });
    
});</script>';


$modx->regClientStartupScript($ready, true);
$modx->regClientStartupScript($datePicker, true);​