<?php
/**
 * Wedding site userFirstLogin
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Redirects to the user settings page if we don't have a valid set
 * of user wediing site parameters. 
 * 
 *  
 *  
 */

$context = $modx->context->get('key');
if ( !$modx->user->isAuthenticated($context) ) return;
$user = $modx->user;
$userId = $user->get('id');
$weddingUser = $modx->getObject('weddingUser', $userId);
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Check for an incomplete parameter set */
$websiteComplete = $attributes['websiteDetails'];
 /* Uncomment to get the welcome box  if ( !$websiteComplete) {
    
   $userSettingsPage = $modx->getOption('userSettingsPage');
   $pageURL = $modx->makeURL($userSettingsPage, $context, "", "full");
   header("Location: {$pageURL}");
} */
