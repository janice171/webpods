<?php
/**
 * Wedding site getRSVPMessageLink
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Gets a users RSVP page link and message count
 * 
 * Parameters :- 
 * 
 *  
 */

/* Get the user details */
$context = $modx->context->get('key');
if ( !$modx->user->isAuthenticated($context) ) return "Error no authenticated user";

$user = $modx->user;
$userId = $user->get('id');
$weddingUser = $modx->getObject('weddingUser', $userId);
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$website = $attributes['website'];

/* Get the users messages */
$c = $modx->newQuery('modUserMessage');
$c->where(array(
    'recipient' => $modx->user->get('id')
));
$c->andCondition(array(
    'read' => 0
));
$messages = $modx->getCollection('modUserMessage', $c);
$count = count($messages);

/* Get the users rsvp page id */
$c = $modx->newQuery('weddingUserPage');
$c->where(array('editType:=' => 'rsvp'));

$pages = $weddingUser->getMany('Pages', $c);
if (!$pages) {
    $modx->setPlaceholder('userInviteError', "Error - No user page data found");
    return;
}
$pages = array_values($pages);
$pageId = $pages[0]->get('pageId');
$params = array('userPage' => $pageId);

/* Create the link to the RSVP edit page */
$RSVPEditPageId = $modx->getOption('RSVPEditPageId');
$pageLink = $modx->makeURL($RSVPEditPageId, $context, $params, 'full');
$pageLink = htmlspecialchars_decode($pageLink);

/* Return the output */
$output = $modx->getChunk('userRSVPMessageCount', array('userRSVPMessageLink' => $pageLink,
                                                        'userRSVPMessageCount' => $count));
return $output;
