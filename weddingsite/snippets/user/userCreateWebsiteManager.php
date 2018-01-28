<?php
/**
 * Wedding site userCreateWebsiteManager
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates a users website when called from the backend management component
 * 
 * Parameters :-
 *
 * website - the website name
 * id - the wedding user id
 * 
 * 
 * Returns :-
 * 
 * None
 *
 * 
 */
/* Sanity check the website */
if ($website == "")
    return;

/* Create the users pages */
$modx->runSnippet('createUserPageSetManager', array('name' => $website, 'id' => $id ));

/* Create the sub domain */
$modx->runSnippet('cPanelAddSubDomain', array('subDomainName' => $website));

/* Create the user directories */
$modx->runSnippet('createUserDirectoriesManager', array('id' => $id));
