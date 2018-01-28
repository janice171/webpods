<?php
/**
 * Wedding site User Invites processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 */
/* Paths */
$assets_url = $modx->getOption('assets_url');
$jQueryPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');
$wsJQueryPath = $modx->getOption('ws_jQuery.assets_url', null, $modx->getOption('assets_url') . 'components/ws_jQuery/');
$javascriptPath = $modx->getOption('ws_javascript.assets_url', null, $modx->getOption('assets_url') . 'components/ws_javascript/');
$cssPath = $assets_url . "templates/wedding/styles/";

$datatablesMediaPath = $jQueryPath  . 'js/DataTables-1.8.1/media/';
$datatablesJSPath = $datatablesMediaPath . 'js/';
$jeditablePath = $jQueryPath . 'js/jeditable/';

/* Styles */
$css1 = $cssPath . "galleryStyle.css";
$modx->regClientCSS($css1);
$css2 = $cssPath . "galleryElastislide.css";
$modx->regClientCSS($css2);
$css3 = '<noscript>
      <style>
        .es-carousel ul{
          display:block;
        }
      </style>
    </noscript>';
$modx->regClientCSS($css3);

/* AJAX link */
$context = $modx->context->get('key');
$userInvitesAJAXPage = $modx->getOption('userInvitesAJAXPage');
$ajaxLinkRaw = $modx->makeURL($userInvitesAJAXPage, $context, "", "full");
$ajaxLink = '"' . $ajaxLinkRaw . '"';

/* AJAX HTML link */
$userInvitesAJAXHTMLPage = $modx->getOption('userInvitesAJAXHTMLPage');
$ajaxHTMLLinkRaw = $modx->makeURL($userInvitesAJAXHTMLPage, $context, "", "full");
$ajaxHTMLLink = '"' . $ajaxHTMLLinkRaw . '"';

/* Head scripts */
$iframeSrc = $ajaxHTMLLinkRaw . '?command=iframeLoad&event=0&guest=0&design=none&initial=yes';
$head1 = '<script id="img-wrapper-tmpl" type="text/x-jquery-tmpl">  
      <div class="rg-image-wrapper">
      <div class="rg-image-nav">
            <a href="#" class="rg-image-nav-prev">Previous Image</a>
            <a href="#" class="rg-image-nav-next">Next Image</a>
          </div>
      
        <iframe id="userInvitesThemeIframe" src="' . $iframeSrc . '" seamless style="margin: 0 auto" width="85%" height="750px" scrolling="no"></iframe>
      </div>
    </script>';
$modx->regClientStartupScript($head1, true);

/* Object setup */
$selector = '"{' . "'Yes'" . ":'Yes'," . "'No'" . ":'No'" .'}"';
$setup = '<script>
    
    WS = new Object;
    WS.ajaxLink = ' . $ajaxLink . ';
    WS.ajaxHTMLLink = ' . $ajaxHTMLLink . ';
    WS.selector = ' . $selector . ';     
    WS.currentTheme = 0;
    
</script>';
$modx->regClientStartupScript($setup, true);

/* Body scripts, same as FE gallery so use it */
$ui1 = $wsJQueryPath . 'js/galleryFE/jquery.tmpl.min.js"';
$modx->regClientScript($ui1);
$ui2 = $wsJQueryPath . 'js/galleryFE/jquery.easing.1.3.js"';
$modx->regClientScript($ui2);
$ui3 = $wsJQueryPath . 'js/galleryFE/jquery.elastislide.js"';
$modx->regClientScript($ui3);
$ui4 = $javascriptPath . "js/pages/user/invites/gallery.js";
$modx->regClientScript($ui4);

/* Include datatables itself */
$html1 = $datatablesJSPath . 'jquery.dataTables.min.js';
$modx->regClientStartupScript($html1);

/* And jeditable */
$html2 = $jeditablePath . 'jquery.jeditable.js';
$modx->regClientStartupScript($html2);

/* WS Javascript */
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Invites  */
$invites = $javascriptPath . "js/pages/user/invites/invites.js";
$modx->regClientStartupScript($invites);

