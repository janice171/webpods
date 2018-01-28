<?php

/**
 * Wedding site userValidateRegistration
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Validates a users registration from the email validation link.
 * 
 *  
 *  Parameters :- 
 * uid - User id 
 * 
 * Returns - None, issues a redirect or outputs  a chunk
 * 
 */

/* Check for a UID */
if ( !isset($_REQUEST['uid'] )) {
    
    $output = $modx->getChunk('registrationValidateNormal');
    return $output;
}

/* Process the registration */
$uid = $_REQUEST['uid'];
if ( $uid == "" ) {
    
    $output = $modx->getChunk('registrationValidateNoUser');
    return $output;
    
}
$user = $modx->getObject('modUser', $uid);
if (!$user) {
    
    $output = $modx->getChunk('registrationValidateNoUser');
    return $output;
}

$user->profile = $user->getOne('Profile');
$weddingUser = $modx->getObject('weddingUser', $uid);
$weddingUser->attributes = $weddingUser->getOne('Attributes');

/* Check for a registration date of 0 */
if ( $weddingUser->attributes->get('registrationDate') != 0 ) {
    
    $output = $modx->getChunk('registrationValidateAlreadyRegistered');
    return $output;
    
}

/* Create the users website and associated data */
$website = $weddingUser->attributes->get('website');
$modx->runSnippet('userCreateWebsiteRegistration', array('website' => $website));

/* Ok, complete the registration */
$weddingUser->attributes->set('registrationDate', time());
$weddingUser->save();
$user->profile->set('blocked', 0);
$user->save();

/* Redirect to login */
$context = $modx->context->get('key');
$userLoginPage = $modx->getOption('userLoginPage');
$redirectURL = $modx->makeURL($userLoginPage, $context, "", 'full');
$modx->sendRedirect($redirectURL);