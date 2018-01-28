<?php
/**
 * Wedding site createSitePreviewLink
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates the default site preview link
 * 
 * Parameters :- 
 * 
 * None
 *  
 */
$context = $modx->context->get('key');
if ( !$modx->user->isAuthenticated($context) ) return;
$user = $modx->user;
$userId = $user->get('id');
$weddingUser = $modx->getObject('weddingUser', $userId);
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$website = $attributes['website'];
$previewURL = $modx->getOption('site_url') . "users/$website";
$modx->setPlaceholder('userEditCommonSitePreview', $previewURL);

