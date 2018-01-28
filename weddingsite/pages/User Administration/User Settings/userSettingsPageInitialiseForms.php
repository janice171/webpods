<?php

/**
 * Wedding site User Settings processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the personal and website user setting forms
 */

$modx->setPlaceholder('userSettingWebsiteError',"");
        
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userSettingError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userSettingError', "Error - No user found please log in");
    return;
}

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Set the placeholders */

/* Website */
$modx->setPlaceholder('userSettingsPackage', $attributes['packageType']);
$modx->setPlaceholder('userSettingsPartner1', $attributes['partnerName1']);
$modx->setPlaceholder('userSettingsPartner2', $attributes['partnerName2']);
if ( $attributes['date'] == 0 ) $attributes['date'] = time();
$date = strftime('%d/%m/%y',$attributes['date']);
$modx->setPlaceholder('userSettingsDatepicker', $date);
$modx->setPlaceholder('userSettingsWebsiteAddress', $attributes['website']);
if ($attributes['theme'] == "" ) $attributes['theme'] = "Ornate Classic Blue";
$modx->setPlaceholder('userSettingsTheme', $attributes['theme']);
if ( $attributes['websiteSearchable'] == 1 ) {
    $modx->setPlaceholder('userSettingsWebsiteSearchable', "checked");  
}
if ( $attributes['displayCountdown'] == 1 ) {
    $modx->setPlaceholder('userSettingsDisplayCountdown', "checked");  
}
if ( $attributes['moderateGuestbook'] == 1 ) {
    $modx->setPlaceholder('userSettingsModerateGuestbook', "checked");  
}
$password = "";
if ( $attributes['passwordProtect'] == 1 ) {
    $modx->setPlaceholder('userSettingsPassProtect', "checked");  
}
$password = $attributes['websitePassword'];
$modx->setPlaceholder('userSettingsPassword', $password);
$modx->setPlaceholder('userSettingsPasswordRepeat', $password);

/* Personal */
$modx->setPlaceholder('userSettingsFirstName', $attributes['firstName']);
$modx->setPlaceholder('userSettingsLastName', $attributes['lastName']);
$modx->setPlaceholder('userSettingsEmail', $profile['email']);
$modx->setPlaceholder('userSettingsTel', $profile['phone']);
$modx->setPlaceholder('userSettingsAddressStreet', $profile['address']);
$modx->setPlaceholder('userSettingsCity', $profile['city']);

/* Country processing */
$country = strlen($profile['country']) != 0  ? $profile['country'] : 'None';
include_once $modx->getOption('core_path').'lexicon/country/en.inc.php';
$countriesOutput = "";

if ( $country == "None" ) {
    
    /* Nothing set, autodetect */
    $modxCountryKey =  array_search('United Kingdom', $_country_lang );
    $countryInfoString = $modx->runSnippet('locator', array('ipAddress'=> 'remote', 'toJSON' => 1));
    $countryInfo = json_decode( $countryInfoString, true);
    if ( $countryInfo['error'] == 'None') {
        
        $modxCountryKey = $countryInfo['modxCountryCode'];
      
        
    }
    
} else {
    
    $modxCountryKey = array_search($country, $_country_lang );
}



foreach ( $_country_lang as $countryKey => $countryName ) {
    
    $countrySelected = "";
    if (   $modxCountryKey  == $countryKey ) $countrySelected = ' selected="yes" ';
    $countriesOutput .= $modx->getChunk('userSettingsCountry', array( 
                                          'countrySelected' => $countrySelected,
                                          'countryValue' => $countryKey,
                                          'countryName' => $countryName));
}
$modx->setPlaceholder('userSettingsCountries', $countriesOutput);

/* Social */
$modx->setPlaceholder('userSettingsSocialFacebook', $attributes['socialFacebook']);
$modx->setPlaceholder('userSettingsSocialTwitter', $attributes['socialTwitter']);
$modx->setPlaceholder('userSettingsSocialGoogle', $attributes['socialGoogle']);
$modx->setPlaceholder('userSettingsSocialOther1', $attributes['socialOther1']);
$modx->setPlaceholder('userSettingsSocialOther2', $attributes['socialOther2']);

/* Link processing */
$context = $modx->context->get('key');
$packagePage = $modx->getOption('packagePageId');
$packagePageLink = $modx->makeURL($packagePage, $context, '', 'full');
$modx->setPlaceholder('packagePage', $packagePageLink);
$designsPage = $modx->getOption('designsPageId');
$designsPageLink = $modx->makeURL($designsPage, $context, array('settings'=> true), 'full');
$modx->setPlaceholder('designsPage', $designsPageLink);

    
/* Page layout */

/* Website links */
$modx->runSnippet('userAdminWebPageLinks', array('attributes' => $attributes));

/* Support */
$modx->runSnippet('userAdminSupportLinks', array('attributes' => $attributes));

return;