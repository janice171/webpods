<?php
/**
 * Wedding site User Events processing
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

/* And jeditable datepicker */
$html3 = $jQueryPath . 'js/jquery.jeditable.datepicker.js';
$modx->regClientStartupScript($html3);

$context = $modx->context->get('key');
$userEventAJAX = $modx->getOption('userEventAJAX');
$ajaxEditable= $modx->makeURL($userEventAJAX, $context, "", "full");
$ajaxEditableLink = '"' . $ajaxEditable . '"';

$ready = '<script>
    
        /* WS object */
        WS = new Object();
        
        WS.ajaxEditableLink = ' . $ajaxEditableLink . ';
            
</script>';
$modx->regClientStartupScript($ready, true);

/* WS Javascript */
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Events  */
$events = $javascriptPath . "js/pages/user/events/events.js";
$modx->regClientStartupScript($events);