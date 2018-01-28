<?php
/**
 * Wedding site getThemeTemplates
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * 
 * Returns :- two data structures, themeTemplates containing the theme
 * templates in array form and themeArray containing the unique theme names.
 */

/* Get all the sub categories of the theme category */
$themeCategoryValue = $modx->getOption('themeCategoryValue');
$themeCategories = $modx->getCollection('modCategory', array('parent' => $themeCategoryValue));

/* Now get all the templates */
$themeTemplateNameArray = array();
foreach ( $themeCategories as $themeCategory ) {
    
    $categoryId = $themeCategory->get('id');
    $themeTemplates = $modx->getCollection('modTemplate', array('category' => $categoryId));
    foreach ( $themeTemplates as $themeTemplate ) {
        
        $templateName = $themeTemplate->get('templatename');
        $templateId = $themeTemplate->get('id');
        $themeTemplateNameArray[$templateName] = $templateId;
    }
}

/* Get the number of themes and create a mapping structure */
$themeArray = array();
$themeTemplates = array();
foreach ( $themeTemplateNameArray as $name => $id) {
    
    $tempThemeArray = explode('-', $name);
    $tempThemeArray[] = $id;
    $themeTemplates[] = $tempThemeArray;
    $themeArray[] = $tempThemeArray[0]. '-' . $tempThemeArray[1];
    
}

/* Re order the themes as per the ordering chunk */
$themeOrder = $modx->getChunk('themeOrder');
$orderStringArray = explode(',', $themeOrder);
$nameOrder = array();
$nameOrderStyle = array();
foreach ( $orderStringArray as $orderString) {
    
    $tempArray = explode(':', $orderString);
    $nameOrder[$tempArray[1]] = trim($tempArray[0]);
    
}

/* Sort by key value */
ksort($nameOrder);
$nameOrder = array_values($nameOrder);

/* Theme array ordered */
$themeArrayOrdered = array_intersect($nameOrder, $themeArray);

/* Return the data */
$returnArray = array();
$returnArray[0] = $themeTemplates;
$returnArray[1] = $themeArrayOrdered;
return json_encode($returnArray);
