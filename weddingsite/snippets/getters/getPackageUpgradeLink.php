<?php

/**
 * Wedding site getPackageUpgradeLink
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Parameters :-
 * 
 * None
 * 
 * Returns :- the package upgrade link if needed
 */

/* Get the user details */
$context = $modx->context->get('key');
if ( !$modx->user->isAuthenticated($context) ) return "Error no authenticated user";

$user = $modx->user;
$userId = $user->get('id');
$weddingUser = $modx->getObject('weddingUser', $userId);
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

$output = "";
if ( $attributes['packageType'] != 'Gold' ) {
    
    $packageUpgradePageId = $modx->getOption('packagePageId');
    $packageUpgradeLink = $modx->makeURL($packageUpgradePageId, $context, $params, 'full');
    $output = $modx->getChunk('packagesUpgradeLink', array('packageUpgradeLink' => $packageUpgradeLink));
    
}

return $output;