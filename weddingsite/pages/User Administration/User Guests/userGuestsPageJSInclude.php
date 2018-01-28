<?php
/**
 * Wedding site User Guests processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Set our jQuery and datatables paths */
$jQueryPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');
 
$datatablesMediaPath = $jQueryPath  . 'js/DataTables-1.8.1/media/';
$datatablesJSPath = $datatablesMediaPath . 'js/';
$jeditablePath = $jQueryPath . 'js/jeditable/';

/* Include datatables itself */
$html1 = $datatablesJSPath . 'jquery.dataTables.min.js';
$modx->regClientStartupScript($html1);

/* And jeditable */
$html2 = $jeditablePath . 'jquery.jeditable.js';
$modx->regClientStartupScript($html2);

/* And jConfirmAction */
$html3 = $jQueryPath . 'js/jConfirmAction/jconfirmaction.jquery.js';
$modx->regClientStartupScript($html3);

/* Include the confirmation CSS */
$confirmationCSS = 'assets/templates/wedding/styles/confirmation.css';
$modx->regClientCSS($confirmationCSS);

/* AJAX */
$context = $modx->context->get('key');
$userGuestAJAX = $modx->getOption('userGuestAJAX');
$ajaxEditable = $modx->makeURL($userGuestAJAX, $context, "", "full");
$ajaxEditableLink = '"' . $ajaxEditable . '"';

/* Include the setup code */
$selector = '"{' . "'Yes'" . ":'Yes'," . "'No'" . ":'No'" .'}"';
$inviteSelector = '"{' . "'Yes'" . ":'Yes'," . "'No'" . ":'No'," . "'Invited'" . ":'Invited',". "'Not Invited'" . ":'Not Invited'".'}"';

/* WS object */
$ready = '<script>
    
        /* WS object */
        WS = new Object();
        
        WS.ajaxEditableLink = ' . $ajaxEditableLink . ';
        WS.selector = ' . $selector . ';  
        WS.inviteSelector = ' . $inviteSelector . ';     
            
</script>';

$modx->regClientStartupScript($ready, true);

/* WS Javascript */
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Guests  */
$guests = $javascriptPath . "js/pages/user/guests/guests.js";
$modx->regClientStartupScript($guests);

