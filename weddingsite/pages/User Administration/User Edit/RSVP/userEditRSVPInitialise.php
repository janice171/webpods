<?php

/**
 * Wedding site User Edit RSVP processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the users RSVP page */
        
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userEditRSVPError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userEditRSVPError', "Error - No user found please log in");
    return;
}

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$context = $modx->context->get('key');

/* Website links */
$modx->runSnippet('userAdminWebPageLinks', array('attributes' => $attributes));

/* Support */
$modx->runSnippet('userAdminSupportLinks', array('attributes' => $attributes));

/* Initialise the common form part */
$modx->runSnippet('userEditCommonInitialise');

/* Get the users message list */
$c = $modx->newQuery('modUserMessage');
$c->where(array(
    'recipient' => $modx->user->get('id')
));
$messages = $modx->getCollection('modUserMessage',$c);

$output = "";
$pageId = $modx->resource->get('id');

foreach ( $messages as $message ) {
    
    $messageArray = $message->toArray();
    $id = $messageArray['id'];
    $args = array('id' => $id);
    $messageLink = $modx->makeURL($pageId, $context, $args, "full");
    $subject = $messageArray['subject'];
    $boldClass = "";
    if ( !$messageArray['read'] ) $boldClass = 'user-message-unread';
    $date = $messageArray['date_sent'];
    $dateTime = strtotime($date);
    $dateString = strftime('%d/%m/%y', $dateTime);
    $params = array('userEditRSVPMessageDate' => $dateString,
                    'userEditRSVPMessageLink' => $messageLink,
                    'userEditRSVPMessageTitle' => $subject,
                    'userEditRSVPMessageSubject' => $subject,
                    'userEditRSVPMessageAJAXLink' => $messageLink,
                    'userEditRSVPBoldClass' => $boldClass);
    $output .= $modx->getChunk('userEditRSVPMessageListItem', $params);
    
}

/* Set the placeholder */
$modx->setPlaceholder('userEditRSVPMessageList', $output);
