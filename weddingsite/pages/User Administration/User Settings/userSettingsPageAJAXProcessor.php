<?php

/**
 * Wedding site User Settings processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ($user)
    $userId = $user->get('id');
if ($userId == "") {

    echo "Error! No logged in user";
    return;
}


/* Check for an update command, if not its simple field validation */
if ($_REQUEST['command'] == "ajaxUpdate") {


    /* Get the element and the value */
    $element = $_REQUEST['element'];
    $value = $_REQUEST['val'];

    /* Get the user details */
    $user = $modx->user;
    $userId = $user->get('id');
    $weddingUserObject = $modx->getObject('weddingUser', $userId);
    if ($weddingUserObject == null)
        return;
    $userProfile = $user->getOne('Profile');
    $weddingUser = $weddingUserObject->getOne('Attributes');

    /* Process the element */
    switch ($element) {

        case 'userSettingsWebsiteAddress' :

            $website = $value;
            $websiteChanged = false;
            $existingWebsite = $weddingUser->get('website');   
            $existingWebsiteSet = true;
            if ($existingWebsite == "")
                $existingWebsiteSet = false;
            if ($existingWebsite != $website)
                $websiteChanged = true;
            $weddingUser->set('website', $website);
            
            /* Save it now for later processing */
            $weddingUser->save();

            /* If we don't have any existing website do the user create */
            if (!$existingWebsiteSet) {

               $modx->runSnippet('userCreateWebsite', array('website' => $website));
                
            } else {

                /* Update, check for a website change */
                if ($websiteChanged) {

                      $modx->runSnippet('userRenameWebsite', array('website' => $website,
                                                                   'existingWebsite' => $existingWebsite));
                    
                }
            }

            break;

        case 'userSettingsPackage' :

            $weddingUser->set('packageType', $value);
            $weddingUser->save();

            break;

        case 'userSettingsPartner1' :

            $weddingUser->set('partnerName1', $value);
            $weddingUser->save();

            break;

        case 'userSettingsPartner2' :

            $weddingUser->set('partnerName2', $value);
            $weddingUser->save();

            break;

        case 'userSettingsDatepicker':

            $dateText = $value;
            $dateArray = explode('/', $dateText);
            $temp = $dateArray[2];
            $dateArray[2] = $dateArray[0];
            $dateArray[0] = $temp;
            $dateText = implode('-', $dateArray);
            $date = strtotime($dateText);
            $weddingUser->set('date', $date);
            $weddingUser->save();

            break;

        case 'userSettingsTheme' :

            $weddingUser->set('theme', $value);
            $weddingUser->save();

            break;

        case 'userSettingsPassProtect' :

            $weddingUser->set('passwordProtect', 0);
            if ($value == 1)
                $weddingUser->set('passwordProtect', 1);
            $weddingUser->save();

            break;
            
       case 'userSettingsWebsiteSearchable' :

            $weddingUser->set('websiteSearchable', 0);
            if ($value == 1)
                $weddingUser->set('websiteSearchable', 1);
            $weddingUser->save();

            break;
            
        case 'userSettingsDisplayCountdown' :

            $weddingUser->set('displayCountdown', 0);
            if ($value == 1)
                $weddingUser->set('displayCountdown', 1);
            $weddingUser->save();

            break;
            
        case 'userSettingsModerateGuestbook' :

            $weddingUser->set('moderateGuestbook', 0);
            if ($value == 1)
                $weddingUser->set('moderateGuestbook', 1);
            $weddingUser->save();

            break;
            
        case 'userSettingsPassword' :

            $weddingUser->set('websitePassword', $value);
            $weddingUser->save();

            break;
        
        case 'userSettingsFirstName' :
            
            $weddingUser->set('firstName', $value);
            $weddingUser->save();

            break;
        
        case 'userSettingsLastName' :
            
            $weddingUser->set('lastName', $value);
            $weddingUser->save();

            break;
        
        case 'userSettingsEmail' :
            
            $userProfile->set('email', $value);
            $userProfile->save();
            
            break;
        
        case 'userSettingsTel' :
            
            $userProfile->set('phone', $value);
            $userProfile->save();
            
            break;
        
        case  'userSettingsCountry' :
            
            /* Convert the country code into text for MODX */
            $_country_lang = array();
            include $modx->getOption('core_path').'lexicon/country/en.inc.php';
            $country = $_country_lang[$value];
            $userProfile->set('country', $country);
            $userProfile->save();
            
            break;
        
        case 'userSettingsAddressStreet' :
            
            $userProfile->set('address', $value);
            $userProfile->save();
            
            break;
        
        case 'userSettingsCity' :
            
            $userProfile->set('city', $value);
            $userProfile->save();
            
            break;
        
        case 'userSettingsSocialFacebook' :
            
            $weddingUser->set('socialFacebook', $value);
            $weddingUser->save();

            break;
        
        case 'userSettingsSocialTwitter' :
            
            $weddingUser->set('socialTwitter', $value);
            $weddingUser->save();

            break;
        
        case 'userSettingsSocialGoogle' :
       
            $weddingUser->set('socialGoogle', $value);
            $weddingUser->save();

            break;
        
        case 'userSettingsSocialOther1' :
            
            $weddingUser->set('socialOther1', $value);
            $weddingUser->save();

            break;
        
         case 'userSettingsSocialOther2' :
            
            $weddingUser->set('socialOther2', $value);
            $weddingUser->save();

            break;
        
        default:

            break;
        
    }
    
    return  $element . ':' . $value;
}

/* Validate the nominated remote fields, validator requires a simple 'true' or 'false' 
 *  value returning, indicating passed validation or failed respectively */

/* Email */
if ($_GET['userSettingsEmail'] != "") {

    /* Check if we already have one in use by another user */
    $userEmail = $_GET['userSettingsEmail'];
    $id = $modx->user->get('id');
    $c = $modx->newQuery('modUser');
    $c->leftJoin('modUserProfile', 'Profile');
    $c->where(array('Profile.email:=' => $userEmail));
    $c->andCondition(array('Profile.internalKey:!=' => $id));
    $count = $modx->getCount('modUser', $c);

    if ($count == 0) {
        return 'true';
    } else {
        return 'false';
    }
}

/* Website address */
if ($_GET['userSettingsWebsiteAddress'] != "") {

    /* Check if we already have one in use by another user */
    $id = $modx->user->get('id');
    $webSite = $_GET['userSettingsWebsiteAddress'];
    $c = $modx->newQuery('weddingUser');
    $c->leftJoin('modUserProfile', 'Profile');
    $c->rightJoin('weddingUserAttribute', 'Attributes');
    $c->where(array('Attributes.website:=' => $webSite));
    $c->andCondition(array('Profile.internalKey:!=' => $id));
    $users = $modx->getCollection('weddingUser', $c);

    if (empty($users)) {
        return 'true';
    } else {
        return 'false';
    }
}

/* If we have reached here its an unknown field so fail validation */
return 'false';

