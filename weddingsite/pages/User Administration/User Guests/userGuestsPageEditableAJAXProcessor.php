<?php

/**
 * Wedding site Guest list AJAX processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Assemble the parameters from the request data */
$guestId = $_REQUEST['row_id'];
$newValue = strip_tags($_REQUEST['value']);
$columnName = $_REQUEST['column'];
$updateType = $_REQUEST['type'];
$echoValue = $newValue;

/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ($user)
    $userId = $user->get('id');
if ($userId == "") {

    echo "Error! No logged in user";
    return;
}


/* Get the guest data and the associated events */
$guestObject = $modx->getObject('weddingUserGuest', array('id' => $guestId));
if (!$guestObject) {

    echo "Error! No guest found";
    return;
}

$user = $guestObject->get('user');

/* Look for an update type of eventlinkedit, this is in fact an invited
 * /not invited update from the guest add/edit page.
 */
if ( $updateType == 'eventlinkedit' ) {
    
    $updateType = 'inviteedit';
    if ( $newValue == 'Yes') {
        
        $newValue = 'Invited';
     
    } else {
        
        $newValue = 'Not Invited';
    }
        
}

/* Process the edit update */
switch ($updateType) {

    case 'yesnoedit' :

        if ($columnName == "Active") {

            $updateValue = 0;
            if ($newValue == "Yes")
                $updateValue = 1;
            $guestObject->set('active', $updateValue);
            if (!$guestObject->save())
                $echoValue = "Error! Cant save guest";
            break;
        }

        break;
        
    case 'inviteedit' :

        /* Guest to Event mapping updates */

        switch ($newValue) {

            case 'Not Invited' :

                /* If 'Not Invited' remove the link from this guest to the named event */
                $guestEvents = $guestObject->getMany('guestEvents');
                foreach ($guestEvents as $eventPtr) {

                    $event = $eventPtr->getOne('weddingUserEvent');
                    $name = $event->get('name');
                    if ($name == $columnName)
                        $eventPtr->remove();
                }

                break;

            case 'Invited' :

                /* Invite the guest */
                $eventObject = $modx->getObject('weddingUserEvent', array(
                    'user' => $user,
                    'name' => $columnName
                        ));
                if ($eventObject) {

                    $eventId = $eventObject->get('id');

                    /* Check for existence just in case */
                    $checkObject = $modx->getObject('guestEvents', array(
                        'guestId' => $guestId,
                        'eventId' => $eventId
                            ));
                    if (!$checkObject) {

                        $guestEventObject = $modx->newObject('guestEvents');
                        $guestEventObject->set('guestId', $guestId);
                        $guestEventObject->set('eventId', $eventId);
                        $guestEventObject->set('InviteSent', 0);
                        $guestEventObject->set('RSVPdManual', 0);
                        $guestEventObject->set('RSVPdOnline', 0);
                        $guestEventObject->set('RSVPDate', 0);
                        $guestEventObject->set('willAttend', 0);
                        $guestEventObject->set('lastReminderDate', 0);
                        if (!$guestEventObject->save())
                            $echoValue = "Error!";
                    }
                } else {

                    $echoValue = "Error! No such Event";
                }

                break;

            case 'Yes' :

                /* Manual attendance */
                $eventObject = $modx->getObject('weddingUserEvent', array(
                    'user' => $user,
                    'name' => $columnName
                        ));
                if ($eventObject) {

                    $eventId = $eventObject->get('id');

                    /* Get the guest event object */
                    $guestEventObject = $modx->getObject('guestEvents', array(
                        'guestId' => $guestId,
                        'eventId' => $eventId
                            ));
                    if (!$guestEventObject)
                        return "Error";

                    /* Update the RSVP data */
                    $guestEventObject->set('InviteSent', 1);
                    $guestEventObject->set('RSVPdManual', 1);
                    $guestEventObject->set('RSVPdOnline', 0);
                    $guestEventObject->set('RSVPDate', time());
                    $guestEventObject->set('willAttend', 1);
                    $guestEventObject->set('lastReminderDate', 0);
                    if (!$guestEventObject->save())
                        $echoValue = "Error!";
                } else {

                    $echoValue = "Error! No such Event";
                }

                break;

            case 'No' :

                /* Manual non attendance */
                $eventObject = $modx->getObject('weddingUserEvent', array(
                    'user' => $user,
                    'name' => $columnName
                        ));
                if ($eventObject) {

                    $eventId = $eventObject->get('id');

                    /* Get the guest event object */
                    $guestEventObject = $modx->getObject('guestEvents', array(
                        'guestId' => $guestId,
                        'eventId' => $eventId
                            ));
                    if (!$guestEventObject)
                        return "Error";

                    /* Update the RSVP data */
                    $guestEventObject->set('InviteSent', 1);
                    $guestEventObject->set('RSVPdManual', 1);
                    $guestEventObject->set('RSVPdOnline', 0);
                    $guestEventObject->set('RSVPDate', time());
                    $guestEventObject->set('willAttend', 0);
                    $guestEventObject->set('lastReminderDate', 0);
                    if (!$guestEventObject->save())
                        $echoValue = "Error!";
                } else {

                    $echoValue = "Error! No such Event";
                }

                break;
        }


        break;


    case 'normaledit' :

        /* Update the guest depending on the column name */
        if ($columnName == 'Name')
            $guestObject->set('name', $newValue);
        if ($columnName == 'Guest Of')
            $guestObject->set('guestOf', $newValue);
        if (!$guestObject->save())
            $echoValue = "Error! Can't save guest";

        break;

    case 'deleteGuest' :

        /* Delete the guest */
        $guestUser = $modx->getObject('weddingUserGuest', $guestId);
        if ($guestUser == null)
            return false;
        $guestUser->remove();

        /* Redirect back to the guest list page */
        $context = $modx->context->get('key');
        $guestPageId = $modx->getOption('guestPageId');
        $pageURL = $modx->makeURL( $guestPageId, $context, "", "full");
        header("Location: {$pageURL}");

        break;

    default:

        /* Shouldn't happen */
        $echoValue = "Error! Invalid update type";
        break;
}

/* Return the output */
echo $echoValue;


