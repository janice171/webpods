<?php
/**
 * Wedding site Design page processing
 */

/* Include our JS for this page */

/* WS javascript */
$assets_url = $modx->getOption('assets_url');
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');
$designs = $javascriptPath . "js/pages/design/design.js";
$modx->regClientStartupScript($designs);

/* Slimbox */
$slimbox = $assets_url . "templates/wedding/js/slimbox-2/js/slimbox2.js";
$modx->regClientStartupScript($slimbox);
$slimboxCSS = $assets_url . "templates/wedding/js/slimbox-2/css/slimbox2.css";
$modx->regClientCSS($slimboxCSS);

/* Pajinate - only include if we have more than stylesPerPage styles in total */
$themesString = $modx->runSnippet('getThemeTemplates');
$themeArray = json_decode($themesString, true);
$themeData = $themeArray[0];
$themeNames = $themeArray[1];
$count = 0;
foreach ($themeNames as $themeName) {

    /* Get the themes styles */
    $stylesString = $modx->runSnippet('getThemeStyles', array('themeName' => $themeName,
        'themeData' => $themeData));
    $styles = json_decode($stylesString, true);
    $count = $count + count($styles);
}
if ( $count > $stylesPerPage ) {
    
    $wsJQueryPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');
    $pajinate = $wsJQueryPath . "js/pajinate/jquery.pajinate.min.js";
    $modx->regClientStartupScript($pajinate);
    $pajinateCode = '<script>$(document).ready(function(){
        
        /* Pagination */
        $("#themeFilter").pajinate({
            items_per_page : ' . $stylesPerPage . ',
            nav_label_first : "<<",
            nav_label_last : ">>",
            nav_label_prev : "<",
            nav_label_next : ">"
        });

    });</script>';
    $modx->regClientStartupScript($pajinateCode, true);

}

/* Page code */
$ready = '<script>$(document).ready(function(){
    
  /* Theme selection warning */
    $(".blue-button").click(function() {
    
        var btnName = $(this).attr("name");
        var radioName = "radio-"+btnName;
        var selection = "input[@name="+radioName+"]:checked";
        var value = $(selection).val();
        if ( value == undefined ) {
        
            alert("' . $noColourSelected . '");
           return false;   
        }
  
        return true;
        
      });
        
});</script>';

$modx->regClientStartupScript($ready, true);