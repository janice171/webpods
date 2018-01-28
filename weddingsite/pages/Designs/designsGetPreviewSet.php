<?php
/**
 * Wedding site Design page processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 * Parameters :- 
 * 
 * templateName - the name of the template in theme-style-colour format
 * 
 * Returns :- The id of the preview set to use
 */

/* Get the containers under the design preview pages */
$designFormPreviewContainerId = $modx->getOption('designFormPreviewContainerId');
$previewContainer = $modx->getObject('modResource', $designFormPreviewContainerId);
if ( !$previewContainer ) return;
$previewChildren = $previewContainer->getMany('Children');

/* Get the set for this template */
foreach ( $previewChildren as $previewChild ) {
    
    $container = $previewChild->get('isfolder');
    if ( $container == 1 ) {
        
        $templates = $previewChild->getMany('Children');
        foreach ( $templates as $template ) {
        
            $alias = $template->get('alias');
            if ( $alias == $templateName ) {
            
                $id = $template->get('id');
                return $id;
            }
        }
    }
}
