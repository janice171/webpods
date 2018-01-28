<?php
/**
 * Wedding site getThemeTemplateId
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Parameters :-
 * 
 * themeName - the name of the theme
 * styleName - the name of the style
 * colourName - the name of the colour
 * themeData :- the theme template data structure as returned by getThemeTemplates
 * 
 * Returns :- the id of the themes template
 */

foreach ( $themeData as $theme ) {
    
    if ( ($theme[0] == $themeName) &&
         ($theme[1] == $styleName) &&
         ($theme[2] == $colourName)) {
        
            return $theme[3];         
   }
}