<?php

/**
 * Wedding site User Help processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the users home page */
 $modx->setPlaceholder('userHelpWebsiteError',"");
        
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userHelpWebsiteError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userHelpWebsiteError', "Error - No user found please log in");
    return;
}

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Website links */
$modx->runSnippet('userAdminWebPageLinks', array('attributes' => $attributes));

/* Support */
$modx->runSnippet('userAdminSupportLinks', array('attributes' => $attributes));

/* Tutorials */
$userTutorialsContainerId = $modx->getOption('userTutorialsContainerId');
$tutorialsContainer = $modx->getObject('modResource', $userTutorialsContainerId);
$children = $tutorialsContainer->getMany('Children');
$context = $modx->context->get('key');
$tutorialsOutput = "";
foreach ( $children as $child ) {
    
    $id = $child->get('id');
    $title = $child->get('pagetitle');
    $pageLink = $modx->makeURL($id, $context, "", "full");
    $linkParams = array('userHelpTutorialTitle' =>  $title ,
                        'userHelpTutorialLink' => $pageLink);
                                     
    
    $tutorialsOutput .= $modx->getChunk('userHelpTutorialsBody', $linkParams);
}

$modx->setPlaceholder('userHelpTutorialsBody', $tutorialsOutput);

