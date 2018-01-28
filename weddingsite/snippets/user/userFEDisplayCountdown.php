<?php
/**
 * Wedding site userFEDisplayCountdown
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Displays the countdown if selected by the user
 * 
 * Parameters :-
 * 
 * none
 *  
 * Returns :-
 * 
 * Countdown html
 */
/* Get the user details */
$website = $modx->runSnippet('userGetWebsiteFromURL');
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:=' => $website));
$weddingUserAttributes = $modx->getObject('weddingUserAttribute', $c);
if (!$weddingUserAttributes) return;
$displayCountdown = $weddingUserAttributes->get('displayCountdown');
if ( $displayCountdown == 1 ) {
    
    $output = $modx->getChunk('userCountdown');
    return $output;
}