<?php
/**
 * Wedding site Guest Add/Edit processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Set our jQuery paths */
$validatorPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');
$validatorPath .= 'js/jquery-validation-1.8.1';
$assets_url = $modx->getOption('assets_url');

/* Include the validation CSS */
$validationCSS = 'assets/templates/wedding/styles/validation.css';
$modx->regClientCSS($validationCSS);

/* Include the includes */
$html1 = $validatorPath . '/lib/jquery.metadata.js';
$html2 = $validatorPath . '/jquery.validate.js';
$modx->regClientStartupScript($html1);
$modx->regClientStartupScript($html2);

/* Include the code */
$initialise = '<script>jQuery.validator.setDefaults({
	debug: ' . $debug . ',
	success: "valid"
});
  
</script>';

/* WS Javascript */
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Add Edit */
$addEdit = $javascriptPath . "js/pages/user/guests/addedit.js";
$modx->regClientStartupScript($addEdit);


$modx->regClientStartupScript($initialise, true);

