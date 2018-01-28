<?php

/**
 * Wedding site User Edit RSVP processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ($userId == "") {
    $modx->setPlaceholder('userEditRSVPError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ($weddingUser == null) {
    $modx->setPlaceholder('userEditRSVPError', "Error - No user found please log in");
    return;
}

$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Check common form processing */
$modx->runSnippet('userEditCommonPostProcessor');
