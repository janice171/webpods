<?php
/**
 * Wedding site creatUserDirectoriesRegistration
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates the users directory set
 * 
 * Parameters :- 
 * 
 * website : The website name of the user
 * 
 * None
 *  
*/

/* Get the directories */
$uploadDirectory = $modx->runSnippet('userGetDirectory', array('directory' => 'uploads', 'website' => $website));
$galleryDirectory = $modx->runSnippet('userGetDirectory', array('directory' => 'gallery', 'website' => $website)); 
$imagesDirectory = $modx->runSnippet('userGetDirectory', array('directory' => 'images', 'website' => $website)); 

/* Create them */
@mkdir($uploadDirectory, 0755, true);
@mkdir($galleryDirectory, 0755, true);
@mkdir($imagesDirectory, 0755, true);