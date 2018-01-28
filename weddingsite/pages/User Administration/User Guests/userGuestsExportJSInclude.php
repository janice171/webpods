<?php
/**
 * Wedding site Guest Export processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* WS Javascript */
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Guests  */
$export = $javascriptPath . "js/pages/user/guests/export.js";
$modx->regClientStartupScript($export);



