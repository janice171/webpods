<?php
/**
 * Wedding site userAdminWebPageLinksCommonJS
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Supplies the common JS for the user admin area sidebar links
 * 
 *  
 *  Parameters :- None
 * 
 * 
 * Returns - a string containing the common JS
 */

/* AJAX links */
$context = $modx->context->get('key');
$linkSortAJAXId = $modx->getOption('linkSortAJAXId');
$linkSortAJAXPage = $modx->makeURL($linkSortAJAXId, $context, "", "full");

$websiteLinks = '<script>
    
   
WS = new Object();
WS.linkPos;
   
$(document).ready(function(){
   
   $(".btn-slide").click(function(event){
            event.preventDefault();
            $("#panel").slideToggle("slow");
            $(this).toggleClass("active");
        });
        
    $(function() {
        $( ".user-menu" ).sortable();
        $( ".user-menu" ).disableSelection();
        $( ".user-menu" ).bind( "sortupdate", function(event, ui) {
           WS.linkPos = []; 
           $( ".user-menu-padding" ).each( function() {
                
                var title = $(this).attr("title");
                WS.linkPos.push(title);
            });
            var linkString = JSON.stringify(WS.linkPos);
            var args = "order="+linkString;
            $.get("' . $linkSortAJAXPage . '",args);
        });
     });
    
    /* Add the page status list AJAX processing */
  currentLink = "";
  $(".ajaxLink").bind("click",function(event){
      event.preventDefault();
            currentLink = this;
        $.get(this.href,"",function(response){
               /* See if the link pressed is the page we are editing and 
                  set the web page active flip state */
               var flip = false;
               var pageQueryString = location.search;
               var pageQueryArray = pageQueryString.split("=");
               var pagePageId = pageQueryArray[1];
               var linkQueryArray = currentLink.href.split("=");
               var linkPageId = linkQueryArray[1];
               if ( pagePageId == linkPageId) flip = true;
               
               /* Response processing */
               if ( response == "on" ) {
                    currentLink.className = currentLink.className.replace(/\boff\b/,"");
                    currentLink.className += " on";
                    /* Flip the common web page active radio buttons */
                    if ( flip ) 
                        $("input[name=userCommonPageActive]:eq(0)").click();
               } else {
                    currentLink.className = currentLink.className.replace(/\bon\b/,"");
                    currentLink.className += " off";
                    /* Flip the common web page active radio buttons */
                    if ( flip )
                        $("input[name=userCommonPageActive]:eq(1)").click();
               }
        })  
     });
    
});</script>';

return $websiteLinks;