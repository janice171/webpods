<?php
/**
 * Wedding site Designs processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Get the themes */
$themesString = $modx->runSnippet('getThemeTemplates');
$themeArray = json_decode($themesString, true);
$themeData = $themeArray[0];
$themeNames = $themeArray[1];

/* Check for a submission button for each theme and its styles */
$themeFound = false;
foreach ($themeNames as $themeNameFull) {

    if ( $themeFound ) break; 
    /* Get the themes style name */
    $styleArray = explode('-', $themeNameFull);
    $styleName = $styleArray[1];
    $themeName = $styleArray[0];
    $stylesOutput = "";
    $buttonName = $themeName . "-" . $styleName;
    if (isset($_REQUEST[$buttonName])) {
            
    	/* Got a submission, get the colour and generate the template name */
        $hiddenName = $buttonName . '-designColourTag'; 
        if ( isset($_REQUEST[$hiddenName])) {
                
        	$themeColourString = $_REQUEST[$hiddenName];
            $colourArray = explode('-', $themeColourString);
            /* Check for a colour selection */
            if ( count($colourArray) == 2 ) {
                    
            	$colourName = $colourArray[1];
                $templateName = $buttonName . "-" . $colourName;
                $themeFound = true;
                break;
            }
                
        }
    }
}

/* Update the users theme attribute and the users resources */
if ( $themeFound ) {
    
    /* Get the MODX user */
    $user = $modx->user;
    $userId = $user->get('id');
    if ( $userId == "" ) return;
    $weddingUserObject = $modx->getObject('weddingUser', $userId);
    if ( $weddingUserObject == null ) return;
    $weddingUser = $weddingUserObject->getOne('Attributes');
    /* Create the theme name for the user */
    $nameArray = explode('-', $templateName);
    $userTemplateName = ucfirst($nameArray[1]) . " " . ucfirst($nameArray[2]);
	$weddingUser->set('theme', $userTemplateName);
    $weddingUser->save();
    
    /* Update the resources */
    $templateId = $modx->runSnippet('getThemeTemplateId', array('themeName' => $nameArray[0],
        'styleName' => $nameArray[1],
        'colourName' => $nameArray[2],
        'themeData' => $themeData));
    
    $templateId = intval($templateId);
    
    /* Get user container */
    $website = $weddingUser->get('website');
    $c = $modx->newQuery('modResource');
    $c->where(array('alias:='  => $website));
    $userWebpageContainerId = $modx->getOption('userWebpageContainerId');
    $c->andCondition(array('parent:='  => $userWebpageContainerId));
    $userContainer = $modx->getObject('modResource', $c);
    
    /* If no container just exit, back to settings if needed */
    if ( !$userContainer ) { 
        
        if ( $_REQUEST['designFromSettings'] == 'true' ) {
  
            $params = array('restore' => 1);
            $userSettingsPage = $modx->getOption('userSettingsPage');
            $pageURL = $modx->makeURL($userSettingsPage, $context, $params, "full");
            header("Location: {$pageURL}");
        
        } else {
        
            return;
        }
    }
    
    /* Get the children */
    $children = $userContainer->getMany('Children');
    foreach ( $children as $child ) {
        
        $child->set('template', $templateId);
        $child->save();
        
        /* Check for blog pages under the blog main page */
        $type = $child->get('longtitle');
        if ( $type == 'blog' ) {
			
			$blogContainer = $child->getMany('Children');
			if ( $blogContainer ) {
				
				foreach ( $blogContainer as $blog ) {
					
					$blog->set('template', $templateId);
					$blog->save();
					
				}
				
			}
			
		}
    }
    
    /* Clear the cache */
    $context = $modx->context->get('key');
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache(array (
            "{$context}/",
        ),
        array(
            'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
            'publishing' => true
        )
    );
            
}

/* If we've come from the settings page redirect back */
if ( $_REQUEST['designFromSettings'] == 'true' ) {

        $userSettingsPage = $modx->getOption('userSettingsPage');
        $pageURL = $modx->makeURL($userSettingsPage, $context, "", "full");
        header("Location: {$pageURL}");
}
