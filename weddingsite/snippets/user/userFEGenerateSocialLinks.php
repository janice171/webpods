<?php
/**
 * Wedding site userFEGenerateSocialLinks
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Generates a users social links
 * 
 * Parameters :-
 * 
 * none
 *  
 * Returns :-
 * 
 * Social link html
 */

/* Get the user details */
$website = $modx->runSnippet('userGetWebsiteFromURL');
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:=' => $website));
$weddingUserAttributes = $modx->getObject('weddingUserAttribute', $c);
if (!$weddingUserAttributes) return;
$attributes = $weddingUserAttributes->toArray();

$output = "";

/* Generate each link if not empty */
if ( $attributes['socialGoogle'] != "" ) {
    
    $socialLink = "https://plus.google.com/" . $attributes['socialGoogle'];
    $socialClass = "google";
    $socialTitle = "Google";
    $output .= $modx->getChunk('userFESocialLink', array('socialLink' => $socialLink,
                                                         'socialClass' => $socialClass,
                                                         'socialTitle' => $socialTitle));
}

if ( $attributes['socialFacebook'] != "" ) {
    
    $socialLink = "http://www.facebook.com/" . $attributes['socialFacebook'];
    $socialClass = "face";
    $socialTitle = "Facebook";
    $output .= $modx->getChunk('userFESocialLink', array('socialLink' => $socialLink,
                                                         'socialClass' => $socialClass,
                                                         'socialTitle' => $socialTitle));
}

if ( $attributes['socialTwitter'] != "" ) {
    
    $socialLink = "http://twitter.com/#!/" . $attributes['socialTwitter'];
    $socialClass = "twit";
    $socialTitle = "Twitter";
    $output .= $modx->getChunk('userFESocialLink', array('socialLink' => $socialLink,
                                                         'socialClass' => $socialClass,
                                                         'socialTitle' => $socialTitle));
}

if ( $attributes['socialOther1'] != "" ) {
    
    $socialLink = "http://" . $attributes['socialOther1'];
    $socialClass = "other1";
    $socialTitle = "Other1";
    $output .= $modx->getChunk('userFESocialLink', array('socialLink' => $socialLink,
                                                         'socialClass' => $socialClass,
                                                         'socialTitle' => $socialTitle));
}

if ( $attributes['socialOther2'] != "" ) {
    
    $socialLink = "https://" . $attributes['socialOther2'];
    $socialClass = "other2";
    $socialTitle = "Other2";
    $output .= $modx->getChunk('userFESocialLink', array('socialLink' => $socialLink,
                                                         'socialClass' => $socialClass,
                                                         'socialTitle' => $socialTitle));
}

/* Return the links */
return $output;