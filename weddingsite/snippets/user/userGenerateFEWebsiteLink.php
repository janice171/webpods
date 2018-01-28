<?php
/**
 * Wedding site userGenerateFEWebsiteLink
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Generates a link to the users website for quick access
 * 
 *  
 *  Parameters :- None
 * 
 * 
 * Returns - html for the generated link
 * 
 */

/* Get the user details */
$context = $modx->context->get('key');
if ( !$modx->user->isAuthenticated($context) ) return;
$user = $modx->user;
$userId = $user->get('id');
$weddingUser = $modx->getObject('weddingUser', $userId);
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Generate the link */
$website = $attributes['website'];
$domain = $modx->getOption('domain');
$tld = $modx->getOption('tld');
$linkTitle = $website . '.' . $domain . '.' . $tld;
$link = 'http://' . $linkTitle;
$output = $modx->getChunk('userWebsiteCopyLink', array('linktitle' => $linkTitle,
                                                       'link' => $link));
return $output;

