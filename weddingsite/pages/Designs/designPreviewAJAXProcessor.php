<?php
/**
 * Wedding site Design page processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Get the URL parameters */
$theme = $_REQUEST['theme'];
$style = $_REQUEST['style'];
$colour = $_REQUEST['colour'];

/* Get this templates preview set */
$templateName = $theme . "-" . $style . "-" . $colour;
$templateName = strtolower($templateName);
$previewContainerId = $modx->runSnippet('designsGetPreviewSet', array('templateName' => $templateName));
$previewContainer = $modx->getObject('modResource', $previewContainerId);
if ( !$previewContainer ) return;
$context = $modx->context->get('key');

/* Build the link array */
$urlArray = array();
$children = $previewContainer->getMany('Children');
foreach ( $children as $child ) {
        
    $id = $child->get('id');
    $childLink = $modx->makeURL($id, $context, "", "full");
    $urlArray[] = $childLink;
}

$dataArray = array();

/* Generate the title */
$title = "<strong>" . ucfirst($theme) . " " . ucfirst($style) . " " . ucfirst($colour) . "</strong>";

/* Build the data array */
$dataArray['links'] = $urlArray;
$dataArray['title'] = $title;

/* Return the data array */
$dataString = json_encode($dataArray);
echo $dataString;