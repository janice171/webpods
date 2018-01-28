<?php
/**
 * Wedding site Events add/edit  processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  The parameters from the settings forms form are already valid at this point, we only need
 * to update the wedding user
 */

/* Check for an event add/edit submit operation */
if ( isset($_REQUEST['userEventsName']) ) {
	
	/* Get the MODX user */
    $user = $modx->user;
    $userId = $user->get('id');
    if ( $userId == "" ) {
        $modx->setPlaceholder('userSettingError', "Error - No MODX user found please log in");
        return;
    }
    
    $weddingUserObject = $modx->getObject('weddingUser', $userId);
    if ( $weddingUserObject == null ) {
        $modx->setPlaceholder('userSettingError', "Error - No Wedding user found please log in");
        return;
    }
	
    /* Check for an event Id, if none this is an add */
    if ( $_REQUEST['userEventsId'] == "" ) {
        
        $eventObject = $modx->newObject('weddingUserEvent');
        $updateType = "Add";
        
    } else {
        
    $eventObject = $modx->getObject('weddingUserEvent', 
                                     array('id' => $_REQUEST['userEventsId']));
    $updateType ="Update"; 
    
    }
    
    if ( $eventObject == null ) {
        $modx->setPlaceholder('userSettingError', "Error - No Event with this ID found ");
        return;
    }
    
    $eventObject->set('name', $_REQUEST['userEventsName']);
    $dateText = $_REQUEST['userEventsDate'];
    $dateArray = explode('/', $dateText);
    $temp = $dateArray[2];
    $dateArray[2] = $dateArray[0];
    $dateArray[0] = $temp;
    $dateText = implode('-', $dateArray);
    $date = strtotime($dateText);
    $eventObject->set('date', $date);
    $eventObject->set('location', $_REQUEST['userEventsLocation']);
    $eventObject->set('address2', $_REQUEST['userEventsAddress2']);
    $eventObject->set('address3', $_REQUEST['userEventsAddress3']);
    $eventObject->set('address4', $_REQUEST['userEventsAddress4']);
    $eventObject->set('startTime', $_REQUEST['userEventsStartTime']);
    $eventObject->set('endTime', $_REQUEST['userEventsEndTime']);
    $eventObject->set('maxGuests', $_REQUEST['userEventsMaxGuests']);  
    $eventObject->set('notes', strip_tags($_REQUEST['userEventsNotes']));
         
    /* Save the event */
    if ( $updateType == "Add" ) {
        
        /* Always set active for created events */
        $eventObject->set('active', 1);
        $weddingUserObject->addMany($eventObject, 'Events');
        if ( $weddingUserObject->save() == false ) {
            $modx->setPlaceholder('userSettingError', "Error - Failed to create this wedding user guest"); 
            return;
        }
        
    } else {
        
        if (!$eventObject->save()) 
            $modx->setPlaceholder('userSettingError', "Error - Failed to update this wedding user guest");    
        
    }
          
    /* Redirect back to the event list */
    $context = $modx->context->get('key');
    $eventPageId = $modx->getOption('eventPageId');
    $pageURL = $modx->makeURL($eventPageId, $context, "", "full");
    header("Location: {$pageURL}");
}




