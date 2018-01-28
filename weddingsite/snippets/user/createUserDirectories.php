<?php
/**
 * Wedding site creatUserDirectories
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates the users directory set
 * 
 * Parameters :- 
 * 
 * None
 *  
*/

/* Get the directories */
$uploadDirectory = $modx->runSnippet('userGetDirectory', array('directory' => "uploads"));
$galleryDirectory = $modx->runSnippet('userGetDirectory', array('directory' => "gallery")); 
$imagesDirectory = $modx->runSnippet('userGetDirectory', array('directory' => "images")); 

/* Create them */
@mkdir($uploadDirectory, 0755, true);
@mkdir($galleryDirectory, 0755, true);
@mkdir($imagesDirectory, 0755, true);