<?php
/**
 * Wedding site userCreateWebsite
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates a users website
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
$modx->log(modX::LOG_LEVEL_ERROR, "Create User - No website set");
    return;
}
  
/* Create the users pages */
$modx->runSnippet('createUserPageSet', array('name' => $website));

/* Create the sub domain */
$modx->runSnippet('cPanelAddSubDomain', array('subDomainName' => $website));

/* Create the user directories */
$modx->runSnippet('createUserDirectories');
