<?php
/**
 * Wedding site getThemeStyles
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Parameters :-
 * 
 * themeName - the name of the theme
 * themeData :- the theme template data structure as returned by getThemeTemplates
 * 
 * Returns :- an array containing the themes style names
 */

$stylesArray = array();
foreach ( $themeData as $theme ) {
    
    if ( $theme[0] != $themeName ) continue;
    $stylesArray[] = $theme[1];
}

/* Unique the array */
$stylesArray = array_unique($stylesArray);

/* Sort Alphabetically */
sort($stylesArray);

/* Return the data */
return json_encode($stylesArray);