<?php
/**
 * Wedding site User Edit Guestbook processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the users guestbook page */
        
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userEditGuestbookError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userEditGuestError', "Error - No user found please log in");
    return;
}

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$context = $modx->context->get('key');
$userArray = $user->toArray();

/* Website links */
$modx->runSnippet('userAdminWebPageLinks', array('attributes' => $attributes));

/* Support */
$modx->runSnippet('userAdminSupportLinks', array('attributes' => $attributes));

/* Initialise the common form part */
$modx->runSnippet('userEditCommonInitialise');

/* Include Quip */
$corePath = $modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/');
$modx->addPackage('quip',$corePath . 'model/');

/* Get the users comment list */
$c = $modx->newQuery('quipComment');
$c->where(array('quipComment.thread' => $userArray['username']));
$c->sortBy('createdon', 'DESC');
$comments = $modx->getCollection('quipComment',$c);

$output = "";
$pageId = $modx->resource->get('id');

foreach ( $comments as $comment ) {
    
    $commentArray = $comment->toArray();
    $id = $commentArray['id'];
    $args = array('id' => $id);
    $commentLink = $modx->makeURL($pageId, $context, $args, "full");
    $name = $commentArray['name'];
    $date = $commentArray['createdon'];
    $dateTime = strtotime($date);
    $dateString = strftime('%d/%m/%y', $dateTime);
    if ( $attributes['moderateGuestbook'] == 1 ) {
        
        /* Moderate output required */
        $approved = $commentArray['approved'];
        $onOff = "on";
        if ( $approved == 0 ) $onOff = 'off';
        $params = array('userEditGuestbookMessageDate' => $dateString,
                        'userEditGuestbookMessageLink' => $commentLink,
                        'userEditGuestbookMessageName' => $name,
                        'userEditGuestbookMessageAJAXLink' => $commentLink,
                       'userEditGuestbookModerate' => $onOff);
        $output .= $modx->getChunk('userEditGuestbookListItemModerate', $params);
        
    } else {
        
    
        $params = array('userEditGuestbookMessageDate' => $dateString,
                        'userEditGuestbookMessageLink' => $commentLink,
                        'userEditGuestbookMessageName' => $name,
                        'userEditGuestbookMessageAJAXLink' => $commentLink);
        $output .= $modx->getChunk('userEditGuestbookListItem', $params);
    
    }
    
}

/* Set the placeholder */
$modx->setPlaceholder('userEditGuestbookMessageList', $output);

