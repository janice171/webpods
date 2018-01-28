<?php

/**
 * Wedding site creatUserDirectoriesManager
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates the users directory set for use by the backend management component
 * 
 * Parameters :- 
 * 
 * id - the wedding user id
 *  
 */
/* Get the directories */
$uploadDirectory = $modx->runSnippet('userGetDirectoryManager', array('directory' => "uploads",
    'id' => $id));
$galleryDirectory = $modx->runSnippet('userGetDirectoryManager', array('directory' => "gallery",
    'id' => $id));
$imagesDirectory = $modx->runSnippet('userGetDirectoryManager', array('directory' => "images",
    'id' => $id));

/* Create them */
@mkdir($uploadDirectory, 0755, true);
@mkdir($galleryDirectory, 0755, true);
@mkdir($imagesDirectory, 0755, true);