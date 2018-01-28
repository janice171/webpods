<?php
/**
 * Wedding site getUserBlogCount
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Parameters - website the users website name
 * Returns :- a count of the users blogs
 */

/* Get the Wedding user */
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:=' => $website));
$weddingUserAttributes = $modx->getObject('weddingUserAttribute', $c);
if (!$weddingUserAttributes) return '0';
$attributes = $weddingUserAttributes->toArray();
$weddingUserObject = $modx->getObject('weddingUser', $attributes['user']);
if (!$weddingUserObject) return '0';

/* Get the users blog page */
$c = $modx->newQuery('weddingUserPage');
$c->where(array('editType:=' => 'blog'));
$pages = $weddingUserObject->getMany('Pages', $c);
if (!$pages) return '0';
$pages = array_values($pages);
$pageId = $pages[0]->get('pageId');
 
/* Get the children, sorted by menuindex */
$c = $modx->newQuery('modResource');
$c->where((array('parent:='  => $pageId)));
$children = $modx->getCollection('modResource',$c);
return count($children);