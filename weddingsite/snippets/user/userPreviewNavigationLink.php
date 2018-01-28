<?php
/**
 * Wedding site userPreviewNavigationLink
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Adds a back to Admin link if a logged in user is previewing
 * 
 *  
 */

/* If logged in add the admin link */
$context = $modx->context->get('key');
if ( !$modx->user->isAuthenticated($context) ) return;
$modx->runSnippet('getGuestEventPageLinks');
$output = $modx->getChunk('userPreviewNavigation');
return $output;



