<?php
/**
 * Wedding site Event list AJAX processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Assemble the parameters from the request data */
$eventId = $_REQUEST['row_id'];
$newValue = strip_tags($_REQUEST['value']);
$columnName = $_REQUEST['column'];
$updateType = $_REQUEST['type'];
$echoValue = $newValue;

/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ( $user ) $userId = $user->get('id');
if ( $userId == "" ) {
    
    echo "Error! No logged in user";
    return;
}

/* Get the event data */
$eventObject = $modx->getObject('weddingUserEvent', array('id' => $eventId));
if ( !$eventObject ) {
    
    echo "Error! No event found";
    return;
}

$user = $eventObject->get('user');
 
/* Process the edit update */
switch ( $updateType ) {
    
    case 'dateedit' :
        
        if ( $columnName == 'Date') {
            
            $dateValue = strtotime($newValue);
            $eventObject->set('date', $dateValue);
            
        }
        
        break;
    
    case 'activeedit' :
        
        /* Just a straight toggle */
        $newActiveState = 1;
        $activeState = $eventObject->get('active');
        if ( $activeState )  $newActiveState = 0;
        $eventObject->set('active', $newActiveState);
        
        break;
        
        
     case 'normaledit' :
        
        /* Update the event depending on the column name */
        if ( $columnName == 'Name') $eventObject->set('name', $newValue);
        if ( $columnName == 'Location') $eventObject->set('location', $newValue);
        if ( $columnName == 'Start Time') $eventObject->set('startTime', $newValue);
        if ( $columnName == 'End Time') $eventObject->set('endTime', $newValue);
        if ( $columnName == 'Max. Guests') $eventObject->set('maxGuests', $newValue);
             
        break;
    
    default:
        
        /* Shouldn't happen */
        $echoValue = "Error! Invalid update type";
        break;
                
}

/* Save the update */
if ( !$eventObject->save() ) $echoValue = "Error! Can't save event";
 
/* Return the output */
echo $echoValue;


