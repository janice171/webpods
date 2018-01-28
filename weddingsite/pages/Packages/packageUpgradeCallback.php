<?php

/**
 * Wedding site Packages processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userPackageError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userPackageError', "Error - No user found please log in");
    return;
}

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$userName = $user->get('username');

/* Page layout */

/* Website links */
$modx->runSnippet('userAdminWebPageLinks', array('attributes' => $attributes));

/* Check and process the Paypal IPN callback */
$paypalPath = $modx->getOption('ws_paypal.core_path',null,$modx->getOption('core_path').'components/ws_paypal/');
include_once $paypalPath . "paypal_class.php";

$payPal = new paypal_class();
switch ($_GET['action']) {
    
    case 'success':      // Order was successful...
   
      /* Update the database */
      if ( isset($_REQUEST['item_number']) ) {
          
          $itemNumber = $_REQUEST['item_number'];
          $packageArray = explode('-', $itemNumber);
          $packageType = end($packageArray);
          $weddingUser->set('packageType', $packageType);
          $weddingUser->save();
          
          /* Output success chunk */
          $output = $modx->getChunk('packagesPaypalSuccess');
          
      }  else {
          
          $modx->log(modX::LOG_LEVEL_ERROR, "WS - Paypal - Item Number not set in Paypal response for user $userName");
          $output = $modx->getChunk('packagesPaypalFail');
           
      }
      
      break;
      
   case 'cancel':       // Order was canceled...

      // The order was canceled before being completed.
 
      $modx->log(modX::LOG_LEVEL_ERROR, "WS - Paypal - Order cancelled for user $userName"); 
      
      /* Output cancel chunk */
      $output = $modx->getChunk('packagesPaypalCancelled'); 
       
      break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
      
      if (!$payPal->validate_ipn()) {
          
         $modx->log(modX::LOG_LEVEL_ERROR, "WS - Paypal - IPN failed validation for user $userName"); 
         
         /* Output validate fail chunk */
         $output = $modx->getChunk('packagesPaypalIPNFail');
      }
     
      break;
      
   default:
       
       $modx->log(modX::LOG_LEVEL_ERROR, "WS - Paypal - No action for user $userName"); 
       
       /* Output the no response chunk */
         $output = $modx->getChunk('packagesPaypalNoResponse');
       
       break;
        
}

$modx->setPlaceholder('packagesPaypalResponse', $output);
