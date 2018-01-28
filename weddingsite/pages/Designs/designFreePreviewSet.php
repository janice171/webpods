<?php
/**
 * Wedding site Design page processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 * Parameters :- 
 * 
 * setName - the name of the set to free
 * 
 * Returns :- Nothing
 */

/* Get the preview container */
$c = $modx->newQuery('modResource');
$designFormPreviewContainerId = $modx->getOption('designFormPreviewContainerId');
$c->andCondition(array('parent:='  => $designFormPreviewContainerId));
$c->where(array('alias:='  => $setName));
$previewContainer = $modx->getObject('modResource', $c);
if ( !$previewContainer) return;

/* Free it */
$previewContainer->set('longtitle', 'free');
$previewContainer->save();

return;