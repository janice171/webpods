<?php

/**
 * Wedding site User Edit RSVP AJAX processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ($user) {
    $userId = $user->get('id');
    if ($userId == "")
        return "Error! No logged in user";
}


/* Get the command type and switch */
if (!isset($_REQUEST['command'])) {

    $response['error'] = "No command specified";
    $output = json_encode($response);
    return $output;
}

$command = $_REQUEST['command'];
$response = array();

switch ($command) {

    case 'read':
        
        /* Get the message details and return them */
        $error = "none";
        $id = $_REQUEST['id'];
        $message = $modx->getObject('modUserMessage', array('id' => $id));
        if (!$message) {

            $response['error'] = "No message found with id $id";
            $output = json_encode($response);
            return $output;
        }
        $guestId = $message->get('sender');
        $guest = $modx->getObject('weddingUserGuest', $guestId);
        $guestName = "Unknown";
        if ( $guest ) $guestName = $guest->get('name');
        $title = $message->get('subject');
        $content = $message->get('message');
        $response['error'] = "none";
        $response['title'] = $title;
        $response['content'] = $content;
        $response['id'] = $id;
        $response['guestName'] = $guestName;
        
        /* Mark the message as read */
        $message->set('read', 1);
        $message->save();
        $output = json_encode($response);

        break;
        
   case 'delete':
        
        /* Get the blog resource id */
        $error = "none";
        $id = $_REQUEST['id'];
        $message = $modx->getObject('modUserMessage', array('id' => $id));
        if (!$message) {

            $response['error'] = "No message found with id $id";
            $output = json_encode($response);
            return $output;
        }
        
        /* Delete it */
        $message->remove();
        
        break;

    default:
        
        return "Unknown command";
}

/* Return the response */
return $output;