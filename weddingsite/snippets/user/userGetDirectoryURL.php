<?php

/**
 * Wedding site userGetDirectoryURL
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Gets users URL to a specified directory 
 * 
 * Parameters :-
 * 
 *  directory : The URL needed, one of uploads, gallery
 *  
 * Returns :-
 * 
 * url - the URL for the users gallery
 */

/* Get the user type */
$userType = $modx->runSnippet('userGetURLType');

/* Get the website name */
if ( $userType == 'user' ) {

    /* User logged in, get the  details */
    $user = $modx->user;
    $userId = $user->get('id');
    $weddingUser = $modx->getObject('weddingUser', $userId);
    $weddingUser->attributes = $weddingUser->getOne('Attributes');
    $attributes = $weddingUser->attributes->toArray();
    $website = $attributes['website'];
    
} else {

    /* Front/back end */
    $website = $modx->runSnippet('userGetWebsiteFromURL');
}

/* Construct the path */
$assetsURL = $modx->getOption('assets_url');
$userURL = $assetsURL . "users" . DIRECTORY_SEPARATOR . $website . DIRECTORY_SEPARATOR . $directory;
return $userURL;
