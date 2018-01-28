<?php

/**
 * Wedding site User Edit Generic processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the users home page */
        
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userEditGenericError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userEditGenericError', "Error - No user found please log in");
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

/* Initialise the common form part */
$modx->runSnippet('userEditCommonInitialise');

/* Set the TinyMCE content area */
$userPageId = $_REQUEST['userPage'];

/* Set the page content*/
$userPage = $modx->getObject('modResource', $userPageId );
if ( !$userPage ) {
    $modx->setPlaceholder('userEditGenericError', "Error - No user page found");
    return;
}
$content = $userPage->get('content');
$modx->setPlaceholder('userEditGenericContent', $content);

/* Allow submission from the common page header for generic pages */
$genericSubmit = 'onSubmit="return WS.genericSubmit();"';
$modx->setPlaceholder('userEditGenericSubmit', $genericSubmit);

/* Set the page id in the tiny mce form */
$modx->setPlaceholder('userPageId', $userPageId);