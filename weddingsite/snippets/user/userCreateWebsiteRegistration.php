<?php

/**
 * Wedding site userCreateWebsiteRegistration
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates a users website from registration processing
 * 
 * Parameters :-
 *
 * website - the website name
 * 
 * 
 * Returns :-
 * 
 * None
 *
 * 
 */
/* Sanity check the website */
if ($website == "") {
    $modx->log(modX::LOG_LEVEL_ERROR, "Create User Registration- No website set");
    return;
}

/* Create the users pages */
$modx->runSnippet('createUserPageSetRegistration', array('name' => $website));

/* Create the sub domain */
$modx->runSnippet('cPanelAddSubDomain', array('subDomainName' => $website));

/* Create the user directories */
$modx->runSnippet('createUserDirectoriesRegistration', array('website' => $website));
