<?php

/**
 * Wedding site Packages processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Get the country */
$alias = $modx->resource->get('alias');
$aliasArray = explode('-', $alias);
$country = end($aliasArray);

/* Button chunk names */
$bronzeName = 'packagesBronze' . $country;
$silverName = 'packagesSilver' . $country;
$goldName = 'packagesGold' . $country;

/* Check for a logged in user */
$placeholders = array();
$context = $modx->context->get('key');
if (!$modx->user->isAuthenticated($context)) {

    $bronze = $modx->getChunk($bronzeName, $placeholders);
    $silver = $modx->getChunk($silverName, $placeholders);
    $gold = $modx->getChunk($goldName, $placeholders);
} else {

    /* Get the users details for paypal */
    $user = $modx->user;
    $userId = $user->get('id');
    if ($userId == "") {
        $modx->setPlaceholder('userPackageError', "Error - No user found please log in");
        return;
    }
    $weddingUser = $modx->getObject('weddingUser', $userId);
    if ($weddingUser == null) {
        $modx->setPlaceholder('userPackageError', "Error - No user found please log in");
        return;
    }

    $userProfile = $user->getOne('Profile');
    $profile = $userProfile->toArray();
    $weddingUser->attributes = $weddingUser->getOne('Attributes');
    $attributes = $weddingUser->attributes->toArray();
    
    /* Set the placeholder values */
   $itemNumberBronze = $userId. '-Bronze' ;
   $itemNumberSilver = $userId. '-Silver' ;
   $itemNumberGold = $userId. '-Gold' ;
   $placeholders['itemNumberBronze'] = $itemNumberBronze;
   $placeholders['itemNumberSilver'] = $itemNumberSilver;
   $placeholders['itemNumberGold'] = $itemNumberGold;
   
   
    /* Switch on Package type */
    $packageType = $attributes['packageType'];
    switch ( $packageType) {
        
        case 'Trial' :
            
            /* All valid */
            $bronze = $modx->getChunk($bronzeName, $placeholders);
            $silver = $modx->getChunk($silverName, $placeholders);
            $gold = $modx->getChunk($goldName, $placeholders);
            break;    
        
        case 'Bronze' :
            
            /* Gold and Silver valid */
            $bronze = $modx->getChunk('packagesInvalid');
            $silver = $modx->getChunk($silverName, $placeholders);
            $gold = $modx->getChunk($goldName, $placeholders);
            break;
        
        case 'Silver' :
            
             /* Gold valid */
            $bronze = $modx->getChunk('packagesInvalid');
            $silver = $modx->getChunk('packagesInvalid');
            $gold = $modx->getChunk($goldName, $placeholders);
            break;
        
        case 'Gold' :
            
            /* None valid */
            $bronze = $modx->getChunk('packagesInvalid');
            $silver = $modx->getChunk('packagesInvalid');
            $gold = $modx->getChunk('packagesInvalid');
            break;
    }
    
}


/* Set the placeholders */
$modx->setPlaceholder('packagesBronze', $bronze);
$modx->setPlaceholder('packagesSilver', $silver);
$modx->setPlaceholder('packagesGold', $gold);