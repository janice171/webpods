<?php

/**
 * Wedding site User Front End Blog processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 */
/* Paths */
$assets_url = $modx->getOption('assets_url');
$wsJQueryPath = $modx->getOption('ws_jQuery.assets_url', null, $modx->getOption('assets_url') . 'components/ws_jQuery/');
$javascriptPath = $modx->getOption('ws_javascript.assets_url', null, $modx->getOption('assets_url') . 'components/ws_javascript/');

/* Get the users website */
$website = $modx->runSnippet('userGetWebsiteFromURL');

/* Get the blog count */
$blogCount = $modx->runSnippet('getUserBlogCount', array('website' => $website));

/* if no blogs return */
if ( intval($blogCount == 0) ) return;

/* Pajinate */
$pajinate = $wsJQueryPath . "js/pajinate/jquery.pajinate.min.js";
$modx->regClientScript($pajinate);

/* Blog */
$blog = $javascriptPath . "js/pages/user/frontend/blog/blog.js";
$modx->regClientStartupScript($blog);

