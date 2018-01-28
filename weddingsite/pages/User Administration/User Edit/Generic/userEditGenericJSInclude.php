<?php
/**
 * Wedding site User Edit Generic processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 */

/* Paths */
$assets_url = $modx->getOption('assets_url');
$wsJQueryPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Common Scripts */
$websiteLinks = $modx->runSnippet('userAdminWebPageLinksCommonJS');
$modx->regClientStartupScript($websiteLinks, true);

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) return;
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) return;
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$website = $attributes['website'];
$website = '"' . $website . '"';

/* Generic */
$generic = $javascriptPath . "js/pages/user/edit/generic/generic.js";
$modx->regClientStartupScript($generic);

$context = $modx->context->get('key');
$genericAJAXProcessorId = $modx->getOption('genericAJAXProcessorId');
$genericAJAXProcessorLink = $modx->makeURL($genericAJAXProcessorId, $context, "", "full");
$genericAJAXProcessorLink = '"' . $genericAJAXProcessorLink . '"';

/* TinyMCE */
$wsTinyMCEPath = $modx->getOption('ws_tinyMCE.assets_url',null,$modx->getOption('assets_url').'components/ws_tinyMCE/');
$jqueryTinyMCE = $wsTinyMCEPath . "js/jscripts/tiny_mce/jquery.tinymce.js";
$modx->regClientStartupScript($jqueryTinyMCE);

$tinyMCEURL = $wsTinyMCEPath . "js/jscripts/tiny_mce/tiny_mce.js";
$tinyMCEURL =  '"' . $tinyMCEURL . '"';
$ready = '<script>
    
        WS.tinymceURL = ' . $tinyMCEURL . ';
        WS.website = ' . $website . ';
        WS.genericAJAXProcessorLink = ' . $genericAJAXProcessorLink . ';       
       
</script>';
$modx->regClientStartupScript($ready, true);

$tinyMCE = $javascriptPath . "js/pages/user/edit/generic/tinymce.js";
$modx->regClientStartupScript($tinyMCE);