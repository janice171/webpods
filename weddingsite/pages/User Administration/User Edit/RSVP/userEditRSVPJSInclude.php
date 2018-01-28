<?php
/**
 * Wedding site User Edit RSVP processing
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

/* RSVP */
$rsvp = $javascriptPath . "js/pages/user/edit/rsvp/rsvp.js";
$modx->regClientStartupScript($rsvp);

/* Pajinate */
$pajinate = $wsJQueryPath . "js/pajinate/jquery.pajinate.min.js";
$modx->regClientStartupScript($pajinate);

$context = $modx->context->get('key');
$RSVPAJAXProcessorId = $modx->getOption('RSVPAJAXProcessorId');
$RSVPAJAXProcessorLink = $modx->makeURL($RSVPAJAXProcessorId, $context, "", "full");
$RSVPAJAXProcessorLink = '"' . $RSVPAJAXProcessorLink . '"';

$ready = '<script>
    
        WS.rsvpAJAXProcessorLink = ' . $RSVPAJAXProcessorLink . ';       
       
</script>';
$modx->regClientStartupScript($ready, true);