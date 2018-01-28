<?php
/**
 * Wedding site User Guest processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the guest edit/add events selection list
 */

/* Get the current logged in user details, ignore errors here, these will be 
 * picked up and reported on the main page form.
 */
$user = $modx->user;
if ( !$user ) return;
$userId = $user->get('id');
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( !$weddingUser ) return;
    
/* Get this users events */
$c = $modx->newQuery('weddingUserEvent');
$c->where(array('user' => $userId));      
$events = $modx->getCollection('weddingUserEvent',$c);
    
    
/* Check for a guest id in the URL, if none this is an add operation */
if ( $_REQUEST['guestId'] == "" ) {
    
    /* Add */
    $chunk = $modx->getChunk('userGuestsAddEditHeaderAdd');
    $modx->setPlaceholder('userGuestAddEditHeader', $chunk);
    
    /* Generate the event list */
    foreach ( $events as $event ) {
        
        $activeState = $event->get('active');
        /* Only active events */
        if ( $activeState ) {
        
            $eventId = $event->get('id');
            $eventName = $event->get('name');
            $eventNameConcat = str_replace( ' ', '', $eventName ); 
            $placeholderArray = array ( 'eventName' => $eventName,
                                    'eventNameConcat' => $eventNameConcat);
            $output .= $modx->getChunk('userGuestAddEditEventListAdd', $placeholderArray);
          
        }
        
    }
    
} else {
    
    /* Update */
    $chunk = $modx->getChunk('userGuestsAddEditHeaderUpdate');
    $modx->setPlaceholder('userGuestAddEditHeader', $chunk);
    
    /* Generate the event list */
    $guestId = $_REQUEST['guestId'];
    $output = "";
    foreach ( $events as $event ) {
        
        $activeState = $event->get('active');
        /* Only active events */
        if ( $activeState ) {
            
            $eventId = $event->get('id');
            $eventName = $event->get('name');
        
            /* Check if the guest has got this event */
            $onOff = "off";
            if ( $modx->getObject('guestEvents', array(
                                             'guestId' => $guestId,
                                             'eventId' => $eventId)))
            $onOff = "on";
        
            /* Assemble the output */
            $context = $modx->context->get('key');
            $args = "&row_id=$guestId&column=$eventName&type=eventlinkedit";
            $userGuestAJAX = $modx->getOption('userGuestAJAX');
            $url = $modx->makeURL($userGuestAJAX, $context, $args, "full" );
            $placeholderArray = array ( 'eventName' => $eventName,
                                    'eventClassOnOff' => $onOff,
                                    'eventOnOff' => ucfirst($onOff),
                                    'eventAJAXLink' => $url);
       
            $output .= $modx->getChunk('userGuestAddEditEventListEdit', $placeholderArray);
            
        }
        
    }
    
}

/* Set the output placeholder */
$modx->setPlaceholder('userGuestAddEditEvents', $output);
