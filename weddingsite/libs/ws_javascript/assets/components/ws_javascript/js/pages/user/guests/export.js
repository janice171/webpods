/**
 * Wedding site external javascript files (ws_javascript)
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * A library package to support the functionality of the
 * wedding sites project
 *
 *
 * @package ws_javascript
 */

$(document).ready(function(){
        
    /* Top menu active selection */
     $(".sf-menu li a").each(function(){
         var title = $(this).attr("title");
         if ( title == "Guest and Event Planner") $(this).addClass("active");
      });
         
  });
  


