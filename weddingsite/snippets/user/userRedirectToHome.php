<?php

/**
 * Wedding site userRedirectToHOme
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Redirects refs to a user container to the home page depending
 * on the domain we have come from
 * 
 *  
 *  Parameters :- None
 * 
 * 
 * Returns - None, issues a redirect
 * 
 */
/* Are we front or back end */
$userType = $modx->runSnippet('userGetURLType');
if ($userType != 'front') {

    /* Use first child redirect */
    $modx->runSnippet('FirstChildRedirect');
    
} else {

    /* Redirect using the users domain */
    $website = $modx->runSnippet('userGetWebsiteFromURL');
    $pageURL = $_SERVER["SERVER_NAME"];
    $redirectURL = 'http://' . $pageURL . '/home.html';
    $modx->sendRedirect($redirectURL);
}