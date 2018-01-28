<?php

/**
 * Wedding site User Edit Home processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the users home page */
        
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userEditHomeError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userEditHomeError', "Error - No user found please log in");
    return;
}

/* Create the page navigation links */
$modx->runSnippet('getGuestEventPageLinks');

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Page links */
$context = $modx->context->get('key');

$tutorialsPage = $modx->getOption('helpPageId');
$tutorialsPageLink = $modx->makeURL($tutorialsPage, $context, '', 'full');
$modx->setPlaceholder('tutorialsPage', $tutorialsPageLink);
$eventPage = $modx->getOption('eventPageId');
$eventPageLink = $modx->makeURL($eventPage, $context, '', 'full');
$modx->setPlaceholder('eventsPage', $eventPageLink);
$settingsPage = $modx->getOption('settingPageId');
$settingsPageLink = $modx->makeURL($settingsPage, $context, '', 'full');
$modx->setPlaceholder('settingsPage', $settingsPageLink);
$invitesPage = $modx->getOption('invitePageId');
$invitesPageLink = $modx->makeURL($invitesPage, $context, '', 'full');
$modx->setPlaceholder('invitesPage', $invitesPageLink);

/* Website links */
$modx->runSnippet('userAdminWebPageLinks', array('attributes' => $attributes));

/* Support */
$modx->runSnippet('userAdminSupportLinks', array('attributes' => $attributes));

/* Website active radio buttons */
if ( $attributes['websiteActive'] == 1 ) {
    
    $modx->setPlaceholder('yesSelected', 'checked'); 

} else {

    $modx->setPlaceholder('noSelected', 'checked'); 
   
}

