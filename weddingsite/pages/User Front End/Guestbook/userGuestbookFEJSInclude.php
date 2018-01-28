<?php
/**
 * Wedding site User Front End Guestbook processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 */
/* Paths */
$assets_url = $modx->getOption('assets_url');
$wsJQueryPath = $modx->getOption('ws_jQuery.assets_url', null, $modx->getOption('assets_url') . 'components/ws_jQuery/');

/* WS Javascript */
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Guestbook  */
$guestbook = $javascriptPath . "js/pages/user/frontend/guestbook/guestbook.js";
$modx->regClientStartupScript($guestbook);

/* Pajinate */
$pajinate = $wsJQueryPath . "js/pajinate/jquery.pajinate.min.js";
$modx->regClientStartupScript($pajinate);