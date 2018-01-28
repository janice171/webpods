<?php
/**
 * Wedding site getStyleColours
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Parameters :-
 * 
 * themeName - the name of the theme
 * styleName - the name of the style
 * themeData :- the theme template data structure as returned by getThemeTemplates
 * 
 * Returns :- an array containing the themes style colours
 */

$coloursArray = array();
foreach ( $themeData as $theme ) {
    
    if ( $theme[0] != $themeName ) continue;
    if ( $theme[1] != $styleName ) continue;
    $coloursArray[] = $theme[2];
}

/* Unique the array */
$coloursArray = array_unique($coloursArray);

/* Sort Alphabetically */
sort($coloursArray);

/* Return the data */
return json_encode($coloursArray);