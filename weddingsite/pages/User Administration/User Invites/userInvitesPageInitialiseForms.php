<?php

/**
 * Wedding site User Invites processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/*  Initialise the personal and website user setting forms
 */

$modx->setPlaceholder('userInviteWebsiteError', "");

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ($userId == "") {
    $modx->setPlaceholder('userInviteError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ($weddingUser == null) {
    $modx->setPlaceholder('userInviteError', "Error - No user found please log in");
    return;
}

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Set the placeholders for the invites form */

/* Page navigation links */
$modx->runSnippet('getGuestEventPageLinks');

/* Get the events */
$events = $weddingUser->getMany('Events');
$eventsOutput = "";
foreach ($events as $event) {

    $active = $event->get('active');
    if ($active) {
        $eventId = $event->get('id');
        $eventName = $event->get('name');
        $eventsOutput .= $modx->getChunk('userInvitesEvents', array(
            'eventId' => $eventId,
            'eventName' => $eventName));
    }
}
$modx->setPlaceholder('userInvitesEventsEventSelection', $eventsOutput);

/* Set the placeholders for the themes gallery */

/* Get the themes */
$themeCategory = $modx->getObject('modCategory', array('category' => $themeCategoryName));
if (!$themeCategory)
    return;
$catId = $themeCategory->get('id');
$c = $modx->newQuery('modChunk');
$c->where(array('category:=' => $catId));
$c->sortBy('name', 'ASC');

$themes = $modx->getCollection('modChunk', $c);

/* Get the theme files so we can get the filetypes */
$assetsPath = $modx->getOption('assets_path');
$inviteImageDirectory = $assetsPath . $invitesImagePath;
$fileExtensions = array();
if ($dir = opendir($inviteImageDirectory)) {
    while (($file = readdir($dir)) !== false) {

        if (($file == '..') || ($file == '.'))
            continue;
        $fileInfo = pathinfo($file);
        $fileExtensions[$fileInfo['filename']] = $fileInfo['extension'];
    }
}

/* Process into gallery placeholders */
$themeOutput = "";
$assetsURL = $modx->getOption('assets_url');
foreach ($themes as $theme) {

    $themeName = $theme->get('name');
    $themeImage = $assetsURL . "templates/wedding/images/invites/" . $themeName . ".$fileExtensions[$themeName]";
    $placeholders = array('theme-name' => $themeName,
        'userInvitesThemeImage' => $themeImage,
        'userInvitesThemeImageTitle' => $themeName);
    $themeOutput .= $modx->getChunk('userInvitesThemeItem', $placeholders);
}

$modx->setPlaceholder('userInvitesThemeItems', $themeOutput);

