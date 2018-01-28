<?php

/**
 * Wedding site userGetDirectory
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Gets a users directory path snippet
 * 
 * Parameters :-
 * 
 *  directory : The directory needed, one of uploads, 
 *  gallery, images
 *  website : If passed use this as the website name
 *  
 * Returns :-
 * 
 * path - the directory path for the user.
 */
if (!isset($website)) {

    /* Get the user details */
    $context = $modx->context->get('key');
    if (!$modx->user->isAuthenticated($context))
        return "error";

    $user = $modx->user;
    $userId = $user->get('id');
    $weddingUser = $modx->getObject('weddingUser', $userId);
    $weddingUser->attributes = $weddingUser->getOne('Attributes');
    $attributes = $weddingUser->attributes->toArray();
    $website = $attributes['website'];
}

/* Construct the path */
$assetsPath = $modx->getOption('assets_path');
$userDirectory = $assetsPath . "users" . DIRECTORY_SEPARATOR . $website . DIRECTORY_SEPARATOR . $directory;
return $userDirectory;