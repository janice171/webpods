<?php

/**
 * Wedding site User Edit Guestbook AJAX processing
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

/* Include Quip */
$corePath = $modx->getOption('quip.core_path', null, $modx->getOption('core_path') . 'components/quip/');
$modx->addPackage('quip', $corePath . 'model/');

switch ($command) {

    case 'read':

        /* Get the comment details and return them */
        $error = "none";
        $id = $_REQUEST['id'];
        $comment = $modx->getObject('quipComment', array('id' => $id));
        if (!$comment) {

            $response['error'] = "No comment found with id $id";
            $output = json_encode($response);
            return $output;
        }
        $commentArray = $comment->toArray();
        $response['name'] = $commentArray['name'];
        $response['email'] = $commentArray['email'];
        $response['body'] = $commentArray['body'];
        $response['error'] = "none";
        $output = json_encode($response);

        break;

    case 'delete':

        /* Get the comment resource id */
        $error = "none";
        $id = $_REQUEST['id'];
        $comment = $modx->getObject('quipComment', array('id' => $id));
        if (!$comment) {

            $response['error'] = "No comment found with id $id";
            $output = json_encode($response);
            return $output;
        }

        /* Delete it */
        $comment->remove();

        break;

    case 'moderate':

        /* Get the comment resource id */
        $error = "none";
        $id = $_REQUEST['id'];
        $comment = $modx->getObject('quipComment', array('id' => $id));
        if (!$comment) {

            $response['error'] = "No comment found with id $id";
            $output = json_encode($response);
            return $output;
        }

        /* Moderate it */
        $approved = $comment->get('approved');
        $newApproved = 0;
        if ($approved == 0)
            $newApproved = 1;
        $comment->set('approved', $newApproved);
        if ($newApproved == 1) {

            $comment->set('approvedby', $userId);
            $approvedOn = strftime('%Y-%m-%d %T');
            $comment->set('approvedon', $approvedOn);
        } else {

            $comment->set('approvedby', 0);
            $comment->set('approvedon', '0000-00-00 00:00:00');
        }

        /* Save the comment */
        $comment->save();

        break;

    default:

        return "Unknown command";
}

/* Return the response */
return $output;


