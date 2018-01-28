<?php
/**
 * Wedding site User Edit Guestbook processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
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

/* Guestbook */
$guestbook = $javascriptPath . "js/pages/user/edit/guestbook/guestbook.js";
$modx->regClientStartupScript($guestbook);

/* Pajinate */
$pajinate = $wsJQueryPath . "js/pajinate/jquery.pajinate.min.js";
$modx->regClientStartupScript($pajinate);

$context = $modx->context->get('key');
$guestbookAJAXProcessorId = $modx->getOption('guestbookAJAXProcessorId');
$guestbookAJAXProcessorLink = $modx->makeURL($guestbookAJAXProcessorId, $context, "", "full");
$guestbookAJAXProcessorLink = '"' . $guestbookAJAXProcessorLink . '"';

$ready = '<script>
    
        WS.guestbookAJAXProcessorLink = ' . $guestbookAJAXProcessorLink . ';       
       
</script>';
$modx->regClientStartupScript($ready, true);