<?php

/**
 * Wedding site User Settings processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/*  The parameters from the settings forms form are already valid at this point, we only need
 * to update the wedding user
 */

/* Get the MODX user */
$user = $modx->user;
$userId = $user->get('id');
if ($userId == "") {
    $modx->setPlaceholder('userSettingError', "Error - No MODX user found please log in");
    return;
}

$weddingUserObject = $modx->getObject('weddingUser', $userId);
if ($weddingUserObject == null) {
    $modx->setPlaceholder('userSettingError', "Error - No Wedding user found please log in");
    return;
}

$weddingUser = $weddingUserObject->getOne('Attributes');

/* Get the existing values of the settings status */
$existingWebSettings = $weddingUser->get('websiteDetails');

/* Check for a website settings submit operation */

if (isset($_REQUEST['userSettingsWebsiteSubmit'])) {

    /* Just set the details complete flag */
    $weddingUser->set('websiteDetails', 1);
    if (!$weddingUser->save()) {
        $modx->setPlaceholder('userSettingError', "Error - Failed to update this wedding user");
    }
}

/* Get the values of the settings status */
$webSettings = $weddingUser->get('websiteDetails');

/* Check for first completion, redirect to the home page if so */
if ( $webSettings ) {

    $redirect = false;
    if ($webSettings && !$existingWebSettings)
        $redirect = true;

    if ($redirect) {

        $context = $modx->context->get('key');
        $userHomePage = $modx->getOption('userHomePage');
        $pageURL = $modx->makeURL($userHomePage, $context, "", "full");
        header("Location: {$pageURL}");
    }
}