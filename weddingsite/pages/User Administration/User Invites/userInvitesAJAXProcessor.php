<?php

/**
 * Wedding site User Invites processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ($user)
    $userId = $user->get('id');
if ($userId == "") {

    echo "Error! No logged in user";
    return;
}

/* Check for a command */
if ($_REQUEST['command'] == "getEventDetails") {

    /* Get the wedding user */
    $weddingUser = $modx->getObject('weddingUser', $userId);
    if ($weddingUser == null)
        return;
    $userProfile = $user->getOne('Profile');
    $profile = $userProfile->toArray();
    $weddingUser->attributes = $weddingUser->getOne('Attributes');
    $attributes = $weddingUser->attributes->toArray();

    /* Get the event and return the data */
    $eventId = $_REQUEST['event'];
    $event = $modx->getObject('weddingUserEvent', $eventId);
    if (!$event)
        return;
    $eventArray = $event->toArray();

    /* Guest details */
    $eventGuests = $event->getMany('guestEvents');
    $guestDetails = "";
    foreach ($eventGuests as $guestPtr) {

        $guestEventId = $guestPtr->get('id');

        $guest = $guestPtr->getOne('weddingUserGuest');
        $active = $guest->get('active');
        if (!$active)
            continue;

        /* Guest details */
        $guestName = $guest->get('name');

        /* RSVP status */
        $RSVPdOnline = $guestPtr->get('RSVPdOnline');
        $RSVPdManual = $guestPtr->get('RSVPdManual');
        $RSVPd = $RSVPdManual || $RSVPdOnline;
        $RSVPdMethodString = "";

        /* Invite status */
        $inviteSent = $guestPtr->get('InviteSent');
        $invitedOn = $guestPtr->get('lastReminderDate');
        $invitedOnString = "";
        if ($invitedOn != 0)
            $invitedOnString = strftime('%d/%m/%y', $invitedOn);
        $RSVPdOn = $guestPtr->get('RSVPDate');
        $RSVPdOnString = "";
        if ($RSVPdOn != 0)
            $RSVPdOnString = strftime('%d/%m/%y', $RSVPdOn);
        $willAttend = $guestPtr->get('willAttend');
        $willAttendString = "";
        if ($inviteSent) {

            if ($RSVPd) {

                $RSVPdMethodString = "Online";
                if ($RSVPdManual == 1)
                    $RSVPdMethodString = "Manual";
                $willAttendString = "No";
                if ($willAttend == 1)
                    $willAttendString = "Yes";
                
            }
            
            $sendInviteString = "No";
            
        } else {

            $sendInviteString = "Yes";
        }

        $placeholders = array('userGuestId' => $guestEventId,
            'userGuestName' => $guestName,
            'userInviteGuestSendInvite' => $sendInviteString,
            'userInvitesGuestInvitedOn' => $invitedOnString,
            'userInvitesGuestAttending' => $willAttendString,
            'userInvitesGuestRSVPdOn' => $RSVPdOnString,
            'userInvitesGuestRSVPMethod' => $RSVPdMethodString);

        $guestDetails .= $modx->getChunk('userInvitesGuestList', $placeholders);
    }

    $dateString = strftime('%d/%m/%y', $eventArray['date']);
    $eventData = array('partner1' => $attributes['partnerName1'] . ' & ' . $attributes['partnerName2'],
        'date' => $dateString,
        'location' => $eventArray['location'],
        'address2' => $eventArray['address2'],
        'address3' => $eventArray['address3'],
        'address4' => $eventArray['address4'],
        'start' => $eventArray['startTime'],
        'end' => $eventArray['endTime'],
        'phone' => $profile['phone'],
        'email' => $profile['email'],
        'guestDetails' => $guestDetails,
        'notes' => $eventArray['notes']);

    $eventDataString = json_encode($eventData);
    return $eventDataString;
}

/* Check for a datatables update */
if (isset($_REQUEST['row_id'])) {

    /* Assemble the parameters from the request data */
    $guestEventId = $_REQUEST['row_id'];
    $newValue = strip_tags($_REQUEST['value']);
    $columnName = $_REQUEST['column'];
    $updateType = $_REQUEST['type'];
    $echoValue = $newValue;

    /* Get the guest event data */
    $guestEventObject = $modx->getObject('guestEvents', array('id' => $guestEventId));
    if (!$guestEventObject) {

        echo "Error! No guest event map object found";
        return;
    }

    /* Process the edit update */
    switch ($updateType) {

        case 'yesnoedit' :

            /* Guest updates first */
            if ($columnName == "Send Invite") {

                $updateValue = 1;
                if ($newValue == "Yes")
                    $updateValue = 0;
                $guestEventObject->set('InviteSent', $updateValue);
                if (!$guestEventObject->save())
                    $echoValue = "Error! Cant save guest";
                break;
            }


            break;

        case 'normaledit' :


            break;



        default:

            /* Shouldn't happen */
            $echoValue = "Error! Invalid update type";
            break;
    }

    /* Return the output */
    echo $echoValue;
}


