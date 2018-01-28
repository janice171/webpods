<?php

/**
 * Wedding site User FE Invites Page  processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Get the website name from the URL */
$website = $modx->runSnippet('userGetWebsiteFromURL');

/* Get the user details */
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:=' => $website));
$weddingUserAttributes = $modx->getObject('weddingUserAttribute', $c);
if (!$weddingUserAttributes) return;
$weddingUserId = $weddingUserAttributes->get('user');
$weddingUser = $modx->getObject('weddingUser', $weddingUserId);
if (!$weddingUser) return;
$attributes = $weddingUserAttributes->toArray();
$modxUser = $modx->getObject('modUser', $weddingUserId);
if (!$modxUser) return;
$userProfile = $modxUser->getOne('Profile');
$profile = $userProfile->toArray();
$thankYou = false;

/* Create a link back to here using the correct domain for the forms */
$pageId = $modx->resource->get('id');
$params = array();
$formLink = $modx->runSnippet('userCreateFELink', array('id' => $pageId,
                                                        'website' => $website,
                                                        'params' => $params));
/* Get the event list for this wedding user */
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

/* First check for acceptance of an invite */
if (isset($_REQUEST['userInvitesFEResponseFormSubmit'])) {

    /* Get the map object from the event and guest id's */
    $guestId = $_REQUEST['userInvitesFEResponseGuestId'];
    $eventId = $_REQUEST['userInvitesFEResponseEventId'];
    $c = $modx->newQuery('guestEvents');
    $c->where(array('guestId:=' => $guestId));
    $c->andCondition(array('eventId:=' => $eventId));
    $mapObject = $modx->getObject('guestEvents', $c);
    if (!$mapObject) {

        $modx->setPlaceholder('userInvitesFEResponseError', "Failed to get map Object");
        return;
    }

    /* Get Acceptance */
    $accepted = $_REQUEST['userInvitesFEResponseAcceptance'] == 1 ? true : false;
    if ($accepted) {

        $mapObject->set('willAttend', 1);
    } else {

        $mapObject->set('willAttend', 0);
    }
    $mapObject->set('RSVPDate', time());
    $mapObject->set('RSVPdOnline', 1);
    $mapObject->set('RSVPdManual', 0);

    /* Save the update */
    $mapObject->save();

    /* Message here */
    $messageText = $_REQUEST['userInvitesFEResponseMessage'];
    $messageText = strip_tags($messageText);
    if ($messageText != "") {

        $guest = $modx->getObject('weddingUserGuest', $guestId);
        if (!$guest)
            return;
        $event = $modx->getObject('weddingUserEvent', $eventId);
        if (!$event)
            return;
        $guestName = $guest->get('name');
        $guestId = $guest->get('id');
        $eventName = $event->get('name');
        $subject = $modx->getChunk('userInvitesFEMessageSubject', array('userInvitesFEGuestName' => $guestName,
            'userInvitesFEEventName' => $eventName));

        $message = $modx->newObject('modUserMessage');
        $message->set('subject', $subject);
        $message->set('message', $messageText);
        $message->set('sender', $guestId);
        $message->set('recipient', $weddingUserId);
        $message->set('private', true);
        $message->set('date_sent', time());
        $message->set('read', false);

        $message->save();
    }
    
    /* Set thank you */
    $thankYou = true;

}

/* Check for a guid from ether a link or the response form */
$guid = "";
if (isset($_REQUEST['guid'])) {

    $guid = $_REQUEST['guid'];
}

if (isset($_REQUEST['userInvitesFEGUID'])) {

    $guid = $_REQUEST['userInvitesFEGUID'];
}

/* Process the page */
if ($guid == "") {

    /* Check for thank you */
    if ( $thankYou ) {
        
        $output = $modx->getChunk('userInvitesFEThankYou');
        $thankYou = false;
        
    } else {
    
        $output = $modx->getChunk('userInvitesFENormal', array('partner1' => $attributes['partnerName1'],
            'partner2' => $attributes['partnerName2'], 'userInvitesFEEventSelection' => $eventsOutput,
            'formLink' => $formLink));
    }
    return $output;
    
} else {

    /* Decode the guid */
    $guidArray = explode('-', $guid);
    $guidGuestId = $guidArray[1];

    /* Check the guest exists */
    $guest = $modx->getObject('weddingUserGuest', $guidGuestId);
    if (!$guest) {
        $failedOutput = $modx->getChunk('userInvitesFENormal', array('partner1' => $attributes['partnerName1'],
            'partner2' => $attributes['partnerName2'], 'userInvitesFEEventSelection' => $eventsOutput,
            'invalid' => $noSuchGuest,
            'formLink' => $formLink));
        return $failedOutput;
    }
    $guestArray = $guest->toArray();

    /* Check guest validity */
    if (!$guestArray['active']) {

        $failedOutput = $modx->getChunk('userInvitesFENormal', array('partner1' => $attributes['partnerName1'],
            'partner2' => $attributes['partnerName2'], 'userInvitesFEEventSelection' => $eventsOutput,
            'invalid' => $guestNotActive,
            'formLink' => $formLink));
        return $failedOutput;
    }


    /* Check the GUID */
    $calculatedGUID = $modx->runSnippet('getGuestsGUID', array('guestId' => $guestArray['id']));
    if ($calculatedGUID != $guid) {
        $failedOutput = $modx->getChunk('userInvitesFENormal', array('partner1' => $attributes['partnerName1'],
            'partner2' => $attributes['partnerName2'], 'userInvitesFEEventSelection' => $eventsOutput,
            'invalid' => $incorrectGuestCode,
            'formLink' => $formLink));
        return $failedOutput;
    }

    /* Ok, guid is valid, get the event */
    $eventId = "";
    if (isset($_REQUEST['event'])) {

        $eventId = $_REQUEST['event'];
    }

    if (isset($_REQUEST['userInvitesFEEventSelection'])) {

        $eventId = $_REQUEST['userInvitesFEEventSelection'];
    }

    if ($eventId == "") {
        $failedOutput = $modx->getChunk('userInvitesFENormal', array('partner1' => $attributes['partnerName1'],
            'partner2' => $attributes['partnerName2'], 'userInvitesFEEventSelection' => $eventsOutput,
            'invalid' => $noEventSelected,
            'formLink' => $formLink));
        return $failedOutput;
    }

    /* Check event details */
    $event = $modx->getObject('weddingUserEvent', $eventId);
    if (!$event) {
        $failedOutput = $modx->getChunk('userInvitesFENormal', array('partner1' => $attributes['partnerName1'],
            'partner2' => $attributes['partnerName2'], 'userInvitesFEEventSelection' => $eventsOutput,
            'invalid' => $eventNoLongerExists,
            'formLink' => $formLink));
        return $failedOutput;
    }

    $eventArray = $event->toArray();
    if (!$eventArray['active']) {
        $failedOutput = $modx->getChunk('userInvitesFENormal', array('partner1' => $attributes['partnerName1'],
            'partner2' => $attributes['partnerName2'], 'userInvitesFEEventSelection' => $eventsOutput,
            'invalid' => $eventNoLongerActive,
            'formLink' => $formLink));
        return $failedOutput;
    }

    /* Check if the guest has been invited */
    $guestEvent = $modx->getObject('guestEvents', array(
        'guestId' => $guestArray['id'],
        'eventId' => $eventArray['id']));

    if (!$guestEvent) {
        $failedOutput = $modx->getChunk('userInvitesFENormal', array('partner1' => $attributes['partnerName1'],
            'partner2' => $attributes['partnerName2'], 'userInvitesFEEventSelection' => $eventsOutput,
            'invalid' => $eventNotInvited,
            'formLink' => $formLink));
        return $failedOutput;
    }

    /* Ok, all checks passed */

    /* Add other placeholders to the event array */
    $eventArray['date'] = strftime('%d.%B %Y', $eventArray['date']);
    $eventArray['partner1'] = $attributes['partnerName1'];
    $eventArray['partner2'] = $attributes['partnerName2'];
    $eventArray['guestName'] = $guestArray['firstName'];
    $eventArray['userName'] = $attributes['name'];
    $eventArray['userPhone'] = $profile['phone'];
    $eventArray['userEmail'] = $profile['email'];
    $eventArray['userInvitesFEResponseEventId'] = $eventArray['id'];
    $eventArray['userInvitesFEResponseGuestId'] = $guestArray['id'];
    $eventArray['formLink'] = $formLink;
    
    $output = $modx->getChunk('userInvitesFEResponse', $eventArray);
    
    /* Correctly form any links in the output */
    
    return $output;
}



