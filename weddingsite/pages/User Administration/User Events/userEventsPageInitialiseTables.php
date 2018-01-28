<?php
/**
 * Wedding site Event Settings processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the event list
 */
        
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userSettingError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userSettingError', "Error - No user found please log in");
    return;
}

/* Create the page navigation links */
$modx->runSnippet('getGuestEventPageLinks');

/* Get the wedding users events */
$events = $weddingUser->getMany('Events');

/* Now Iterate through the events and create the table body */
$output = "";
$context = $modx->context->get('key');
foreach ($events as $event) {
    
    $eventArray = $event->toArray();
    
    /* Make the edit link */
    $args = "&eventId=" . $eventArray['id'];
    $userEditEventPage = $modx->getOption('userEditEventPage');
    $editURL = $modx->makeURL($userEditEventPage, $context, $args, "full" );
    
    /* Make the active link */
    $args = "&row_id=" . $eventArray['id'];
    $args .= "&type=activeedit";
    $userEventAJAX = $modx->getOption('userEventAJAX');
    $activeURL = $modx->makeURL($userEventAJAX, $context, $args, "full" );
    
    /* Calculate the total guests, only active guests count */
    $eventGuests = $event->getMany('guestEvents'); 
    $totalGuests = 0;
    foreach ( $eventGuests as $guestPtr ) {
        
        $guest = $guestPtr->getOne('weddingUserGuest');
        $active = $guest->get('active');
        if ( $active ) {
         
            /* Indicate attendance */
            $willAttend = $guestPtr->get('willAttend');
            if ( $willAttend == 1 ) $totalGuests++; 
            
        }
        
    }
    
    /* Get the active on/off state */
    $onOff = "off";
    if ( $eventArray['active'] == 1 ) $onOff = "on";
    
    $placeHolderArray = array ( 'userEventId' => $eventArray['id'],
                                'userEventName' => $eventArray['name'],
                                'userEventDate' => strftime('%d/%m/%y' ,$eventArray['date']),
                                'userEventLocation' => $eventArray['location'],
                                'userEventStartTime' => $eventArray['startTime'],
                                'userEventEndTime' => $eventArray['endTime'],
                                'userEventMaxGuests' => $eventArray['maxGuests'],
                                'userEventTotalGuests' => $totalGuests,
                                'userEventEditLink' => $editURL,
                                'userEventActiveLink' => $activeURL,
                                'userEventOnOff' => $onOff);
    
    $chunk = $modx->getChunk('userEventsList', $placeHolderArray);
    $output .= $chunk;
    
}

$modx->setPlaceholder('userEventList', $output);

return;