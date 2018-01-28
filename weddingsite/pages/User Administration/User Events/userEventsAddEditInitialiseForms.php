<?php
/**
 * Wedding site User Event processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the event edit/add form
 */

$modx->setPlaceholder('userGuestError',"");

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userEventError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userEventError', "Error - No user found please log in");
    return;
}

/* Create the page navigation links */
$modx->runSnippet('getGuestEventPageLinks');

/* Check for add or edit */
if ( isset($_GET['eventId']) ) {
    
    /* Edit call, get the event data */
    $eventId = $_GET['eventId'];
    $weddingEvent = $modx->getObject('weddingUserEvent', array('id'=>$eventId));
    if ($weddingEvent == null ) {            
        $modx->setPlaceholder('userEventError', "Error - No event details found");     
        return;
    }
         
    /* Set the placeholders */
    $eventArray = $weddingEvent->toArray(); 
    $modx->setPlaceholder('userEventsId', $eventId);
    $modx->setPlaceholder('userEventsName', $eventArray['name']);
    $date = strftime('%d/%m/%y',$eventArray['date']);
    $modx->setPlaceholder('userEventsDate', $date);
    $modx->setPlaceholder('userEventsLocation', $eventArray['location']);
    $modx->setPlaceholder('userEventsAddress2', $eventArray['address2']);
    $modx->setPlaceholder('userEventsAddress3', $eventArray['address3']);
    $modx->setPlaceholder('userEventsAddress4', $eventArray['address4']);
    $modx->setPlaceholder('userEventsStartTime', $eventArray['startTime']);
    $modx->setPlaceholder('userEventsEndTime', $eventArray['endTime']);
    $modx->setPlaceholder('userEventsMaxGuests', $eventArray['maxGuests']);
    $modx->setPlaceholder('userEventsNotes', $eventArray['notes']);
}

