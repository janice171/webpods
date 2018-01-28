<?php

/**
 * Wedding site Designs processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/*  Initialise the design page forms
 */

/* Check for logged in user */
$loggedIn = false;
$context = $modx->context->get('key');
if ($modx->user->isAuthenticated($context))
    $loggedIn = true;

$designFormAjaxPreviewPage = $modx->getOption('designFormAjaxPreviewPage');

/* Get the theme templates */
$themesString = $modx->runSnippet('getThemeTemplates');
$themeArray = json_decode($themesString, true);
$themeData = $themeArray[0];
$themeNames = $themeArray[1];
$allColours = array();

/* Set the style names in the filter and output the whole page */
$styleNameOutput = $modx->getChunk('designStyleNames', array('styleNameDisplay' => 'All',
        'styleName' => 'all'));

foreach ($themeNames as $themeNameFull) {

    /* Get the themes style name */
    $styleArray = explode('-', $themeNameFull);
    $styleName = $styleArray[1];
    $themeName = $styleArray[0];
    
    /* Style filter */
    $styleNameOutput .= $modx->getChunk('designStyleNames', array('styleNameDisplay' => ucfirst($styleName),
    'styleName' => $styleName));

    /* Get the styles colours */
    $coloursString = $modx->runSnippet('getStyleColours', array('themeName' => $themeName,
        'styleName' => $styleName,
        'themeData' => $themeData));

    $colours = json_decode($coloursString, true);
    $coloursOutput = "";
    $thumbsOutput = "";
    $previewLinkGenerated = false;
   
    /* Colours and thumbs */
    $firstColourSet = false;
    foreach ($colours as $colourName) {

        /* Add to colour filter */
        $allColours[] = $colourName;

        /* Set first colour */
        if ( !$firstColourSet ) {

            $firstColour = $colourName;
            $firstColourSet = true;
        }

        /* Preview link */
        $args = array('theme' => $themeName,
                        'style' => $styleName,
                        'colour' => $colourName);
        $designPreviewLink = $modx->makeURL($designFormAjaxPreviewPage,
                                            $context,
                                            $args,
                                            "full");
        $tooltipContent = $modx->getChunk('designsTooltipContent'); 

        /* Check for logged in or not and switch chunks */
        if (!$loggedIn) {

            $coloursOutput .= $modx->getChunk('designColours', array('designStyleColour' => $colourName,
                'designStyleColourDisplay' => ucfirst($colourName),
                'designStyleName' => $styleName,
                'themeName' => $themeName,
                'designPreviewLink' => $designPreviewLink,
                'designPreviewTooltip' =>  $tooltipContent));
        } else {

            $coloursOutput .= $modx->getChunk('designColoursLoggedIn', array('designStyleColour' => $colourName,
                'designStyleColourDisplay' => ucfirst($colourName),
                'designStyleName' => $styleName,
                'themeName' => $themeName,
                'designPreviewLink' => $designPreviewLink,
                'designPreviewTooltip' =>  $tooltipContent));
        }

        $thumbsOutput .= $modx->getChunk('designThumbs', array('designStyleColour' => $colourName,
            'designStyleColourDisplay' => ucfirst($colourName),
            'designStyleNameDisplay' => ucfirst($styleName),
            'designStyleName' => $styleName,
            'designThemeName' => $themeName));

        /* Preview button link */
        if ( !$previewLinkGenerated ) {


            $previewLinkGenerated = true;
        }
    }

    /* Styles page code */

    /* Get the selection button if logged in */
    $designSelectButton = "";
    if ( $loggedIn ) {
        $designSelectButton = $modx->getChunk('designSelectButton', array('designStyleName' => $styleName,
            'themeName' => $themeName,
            'designStyleColour' =>  $firstColour));

    }

    $stylesOutput = $modx->getChunk('designsPageFormStyle', array('styleNameDisplay' => ucfirst($styleName),
        'styleName' => $styleName,
        'themeName' => $themeName,
        'designColours' => $coloursOutput,
        'designThumbs' => $thumbsOutput,
        'designSelectButton' => $designSelectButton));

    /* Theme page code */
    if ( isset($_REQUEST['settings'])) $designFromSettings = "true";
    $themeOutput .= $modx->getChunk('designsPageFormTheme', array('themeName' => $themeName,
        'designStyles' => $stylesOutput,
        'designFromSettings' => $designFromSettings));
}

/* Set the page theme filter placeholder */
$modx->setPlaceholder('designStyleNames', $styleNameOutput);

/* Set the page colour filter placeholder */
$allColours = array_unique($allColours);
$colourFilterOutput = $modx->getChunk('designColourFilter', array('colourNameDisplay' => 'All',
                                                                                                                'colourName' => 'all'));
foreach ( $allColours as $colour ) {
    
    $colourFilterOutput .= $modx->getChunk('designColourFilter', array('colourNameDisplay' => ucfirst($colour),
                                                                                                                'colourName' => $colour));
    
}

$modx->setPlaceholder('designColourNames', $colourFilterOutput);


/* Set the page themes  placeholder */
$modx->setPlaceholder('designThemes', $themeOutput);