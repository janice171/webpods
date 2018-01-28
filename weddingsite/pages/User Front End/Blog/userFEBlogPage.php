<?php
/**
 * Wedding site User Front End Blog processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 */

/* Get the users website */
$website = $modx->runSnippet('userGetWebsiteFromURL');

/* Get the blog count */
$blogCount = $modx->runSnippet('getUserBlogCount', array('website' => $website));

/* Select the chunk */
$chunk = $modx->getChunk('userFEBlogBlogsExist');
if ( intval($blogCount) == 0 ) $chunk = $modx->getChunk('userFEBlogNoBlogs');
$modx->setPlaceholder('userFEBlogPage', $chunk);