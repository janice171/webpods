<?php
/**
 * Wedding site Guest Settings processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the guest list
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

/* Get the wedding users guests */
$guests = $weddingUser->getMany('Guests');

/* Get the wedding users events */
$events = $weddingUser->getMany('Events');

/* Create the table columns from the wedding users events */
$output = "";
$eventNameArray = array();
foreach ( $events as $event ) {
    
    $activeState = $event->get('active');
    /* Only active events */
    if ( $activeState ) {
    
        $name = $event->get('name');
        $id = $event->get('id');
        $eventIdArray[] = $id;
        $output .= "<th>" . $name . "</th>";
    
    }
}

/* Set the column placeholder */
$modx->setPlaceholder('userGuestEventColumns', $output);

/* Now Iterate through the guests and create the table body */
$output = "";
$context = $modx->context->get('key');
foreach ($guests as $guest) {
    
    $guestArray = $guest->toArray();
    
    /* Get the event array into a string we can use in the table body for this guest */
    $attendOutput = "";
    foreach ( $eventIdArray as $id ) {
    
        /* See if we are invited and what attendance there has been */
        $guestEvent = $modx->getObject('guestEvents', array(
                               'guestId' => $guestArray['id'],
                               'eventId' => $id)); 
        
        if ( !$guestEvent )  {
            
            $attend = "Not Invited";
        
        } else {
            
            /* Invited , check status */
            $attend = "Invited";
            $attendance = $guestEvent->get('willAttend');
            if ( $attendance == 1 ) { 
                
                 $attend = "Yes";
                
            } else {
                
                $rsvpOnline = $guestEvent->get('RSVPdOnline');
                $rsvpManual = $guestEvent->get('RSVPdManual');
                if (  $rsvpOnline ||  $rsvpManual) {
                    
                     $attend = "No";
                }
                
            }
        }
         $attendOutput .= '<td class="guestEditableInvite">' . $attend. "</td>";
        
    }
    
   

    /* Get the guests GUID */
    $guid = $modx->runSnippet('getGuestsGUID', array('guestId' => $guestArray['id'] ));
    
    /* Make the edit link */
    $args = "&guestId=" . $guestArray['id'];
    $userGuestEditPage = $modx->getOption('userGuestEditPage');
    $editURL = $modx->makeURL($userGuestEditPage, $context, $args, "full" );
    
    /* Make the delete link */
    $args = "&row_id=" . $guestArray['id'];
    $args .= "&type=deleteGuest";
    $userGuestAJAX = $modx->getOption('userGuestAJAX');
    $deleteURL = $modx->makeURL($userGuestAJAX, $context, $args, "full" );
    
    $placeHolderArray = array ( 'userGuestId' => $guestArray['id'],
                                'userGuestName' => $guestArray['name'],
                                'userGuestEvents' => $attendOutput,
                                'userGuestGuestOf' => $guestArray['guestOf'],
                                'userGuestGUID' => $guid,
                                'userGuestActive' => $guestArray['active'] ? "Yes" : "No",
                                'userGuestEditLink' => $editURL,
                                'userGuestDeleteLink' => $deleteURL);
    
    $chunk = $modx->getChunk('userGuestsList', $placeHolderArray);
    $output .= $chunk;
    
}

$modx->setPlaceholder('userGuestList', $output);

return;