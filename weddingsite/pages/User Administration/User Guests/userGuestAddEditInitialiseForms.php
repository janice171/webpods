<?php
/**
 * Wedding site User Guest processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the guest edit/add form
 */

$modx->setPlaceholder('userGuestError',"");

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userGuestError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userGuestError', "Error - No user found please log in");
    return;
}

/* Create the page navigation links */
$modx->runSnippet('getGuestEventPageLinks');

/* Check for add or edit */
if ( isset($_GET['guestId']) ) {
    
    /* Edit call, get the guest data */
    $guestId = $_GET['guestId'];
    $weddingGuest = $modx->getObject('weddingUserGuest', array('id'=>$guestId));
    if ($weddingGuest == null ) {            
        $modx->setPlaceholder('userGuestError', "Error - No guest details found");     
        return;
    }
         
    /* Set the placeholders */
    $guestArray = $weddingGuest->toArray(); 
    $modx->setPlaceholder('userGuestsId', $guestId);
    $modx->setPlaceholder('userGuestsName', $guestArray['name']);
    $modx->setPlaceholder('userGuestsEmail', $guestArray['email']);
    $modx->setPlaceholder('userGuestsAddressLine1', $guestArray['address1']);
    $modx->setPlaceholder('userGuestsAddressLine2', $guestArray['address2']);
    $modx->setPlaceholder('userGuestsCity', $guestArray['city']);
    $modx->setPlaceholder('userGuestsPostcode', $guestArray['postCode']);
    $modx->setPlaceholder('userGuestsTel', $guestArray['telephone']);
    $modx->setPlaceholder('userGuestsGuestOf', $guestArray['guestOf']);
    $modx->setPlaceholder('userGuestsRSVP', $guestArray['rsvp'] ? "checked" : "");
    $modx->setPlaceholder('userGuestsActive', $guestArray['active'] ? "checked" : "");
       
}

