<?php
/**
 * Wedding site userGetDirectoryManager
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Gets a users directory path snippet for use by the backend management component
 * 
 * Parameters :-
 * 
 *  directory : The directory needed, one of uploads, 
 *  gallery, images
 *  id - the wedding user id
 *  
 * Returns :-
 * 
 * path - the directory path for the user, 'error' means no user logged in
 */

/* Get the user details */
$weddingUser = $modx->getObject('weddingUser', $id);
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$website = $attributes['website'];

/* Construct the path */
$assetsPath = $modx->getOption('assets_path');
$userDirectory = $assetsPath . "users" .  DIRECTORY_SEPARATOR  .  $website .  DIRECTORY_SEPARATOR  . $directory;
return $userDirectory;