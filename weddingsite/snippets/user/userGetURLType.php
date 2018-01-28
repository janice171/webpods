<?php
/**
 * Wedding site userGetURLType
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Gets the users type, this can be one of :-
 * 
 * front - an access to the front end pages of a users web site
 * back  - an access to the www section of the site visible to non logged in users
 * user  - an access to the www section of the site whilst logged in
 * 
 * Parameters :-
 * 
 * None
 *  
 * Returns :-
 * 
 * User type, either front, back or user
 */

$pageURL = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

/* Parse the url */
$urlArray = parse_url($pageURL);
$pathArray = explode('/', $urlArray[path] );


/* Check for the admin(www) domain */
$hostArray = explode('.', $pathArray[0]);
if ( $hostArray[0] == 'www') {
    
    $context = $modx->context->get('key');
    if ( $modx->user->isAuthenticated($context) ) {
        
        /* Logged in user */
        return 'user';
    
    }
    
    /* Must be back end */
    return 'back';
    
}

/* Must be a front end user */
return 'front';
