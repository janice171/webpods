<?php

/**
 * Wedding site userGetWebsiteFromURL
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Gets the users website name from an incoming URL snippet
 * 
 * Parameters :-
 * 
 * None
 *  
 * Returns :-
 * 
 * The website name
 */
$pageURL = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

/* Parse the url */
$urlArray = parse_url($pageURL);
$pathArray = explode('/', $urlArray[path]);
  
/* If we are not a front end user get the website from the path */
$userType = $modx->runSnippet('userGetURLType');
if ($userType != 'front') {
  
    /* Logged in user */
    if ( $userType == 'user')
        return $pathArray[2];
    
    /* Not logged in and not front end, shouldn't happen */
    return '';
}

/* Return the front end users website(sub-domain) */
$hostArray = explode('.', $pathArray[0]);
return $hostArray[0];