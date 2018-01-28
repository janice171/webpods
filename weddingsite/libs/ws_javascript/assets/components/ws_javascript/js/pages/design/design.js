 /* Wedding site external javascript files (ws_javascript)
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * A library package to support the functionality of the
 * wedding sites project
 *
 *
 * @package ws_javascript
 */
jQuery(document).ready(function()
    {
            
        /* WS object */
        WS = new Object();
   
        /* WS functions */
        WS.filterTheme = function(theme){
            $(".designThemeForm").hide();
            if ( theme == "all" ) {
                window.location.reload();
            } else {
                var themeSelected = "#designThemeForm_"+theme;
                $(themeSelected).show();
                $(".page_navigation").hide();
            }

        };

        WS.filterStyle = function(style){
            $(".design").hide();
            if ( style == "all" ) {
                window.location.reload();
            } else {
                var styleSelected = ".design-"+style;
                $(styleSelected).show();
                $(".page_navigation").hide();
            }

        };

        WS.filterColour = function(colour){
            if ( colour != "all" ) {
                $(".design").hide();
                $(".blue-button").hide();
                var picSelected = ".pic-"+colour;
                $(".design .design-thumbs li").hide();
                var showPic = ".design .design-thumbs li" + picSelected;
                $(showPic).show();
                var radioSelected = ".radio-"+colour;
                $(".design .design-colours li input").hide();
                var showRadio = ".design .design-colours li input" + radioSelected;
                $(showRadio).show();
                var colourSelected = ".colour-"+colour;
                $(".design .design-colours li a").hide();
                var showColour = ".design .design-colours li a" + colourSelected;
                $(showColour).show();
                $(showColour).closest(".design-colours").siblings("div").children("input").show();
                $(showColour).closest(".design").show();
                $(showColour).trigger('click');
          
            } else {
             
                $(".design").show();
                $(".design .design-thumbs li").show();
                $(".design .design-colours li input").show();
                $(".design .design-colours li a").show();
                $(".blue-button").show();
  

            }
    
        };

        /* Colour no click class */
        $(".colour-noclick").bind("click",function(event){
       
            event.preventDefault();   
            return false;
       
        },false);
        
        /* Filter change theme  function */
        $("#designThemesFilter").change(function() {

            /* Show the selected theme */
            var themeSelectedFilter = $("#designThemesFilter :selected").val();
            WS.filterTheme(themeSelectedFilter);
        
            /* Show the selected colour */
            var colourSelectedFilter = $("#designColoursFilter :selected").val();
            WS.filterColour(colourSelectedFilter);        

       
        });
   
        /* Filter change style  function */
        $("#designStylesFilter").change(function() {

            /* Show the selected colour */
            var colourSelectedFilter = $("#designColoursFilter :selected").val();
            WS.filterColour(colourSelectedFilter);  
  
            /* Show the selected style */
            var styleSelectedFilter = $("#designStylesFilter :selected").val();
            WS.filterStyle(styleSelectedFilter);
              

       
        });
   
        /* Filter change colour  function */
        $("#designColoursFilter").change(function() {

            /* Show the selected style */
            var styleSelectedFilter = $("#designStylesFilter :selected").val();  
            if ( styleSelectedFilter != "all" )
                WS.filterStyle(styleSelectedFilter);

            /* Filter by colour*/
            var colourSelectedFilter = $("#designColoursFilter :selected").val();
            WS.filterColour(colourSelectedFilter);
        
        });
        
        jQuery('a.colour-label').click(function(event)
        {
            event.preventDefault();
                        
            if( $(this).is(':visible') ) {
			
                var colourClasses, thisJQ, colourArray, colourClass, colour;
	
                colourClasses = $(this).attr('class');
                colourArray = colourClasses.split(" ");
                colourClass =  colourArray[1].split("-");
                colour = colourClass[1];
                                
                var tagName = 'tag-'+colourArray[2]+'-'+colourArray[3];
                var tag = '.'+tagName;
                thisJQ = jQuery(this);
				
                newThumb = thisJQ.closest('li.design').find('.pic-'+colour+':hidden');
                newThumb.parent().find('li:visible').stop(false, true).stop(false, true).fadeOut(200, function()
                {
                    newThumb.fadeIn(200);
                });
				
                thisJQ.closest('ul').find('a.colour-label').removeClass('colour-selected');
                thisJQ.addClass('colour-selected');
                                
                /* Get the submit button tag if present */      
                var tagEl = $(tag);
                if ( tagEl.length == 0 ) return false;
                                
                /* Change its colour */
                var addColour = 'colour-'+colour;
                tagEl.removeClass('colour-selected');
                var colourClassListString = $(tagEl).attr('class');
                var colourClassList = colourClassListString.split(" ");
                for (var i = 0; i < colourClassList.length; i++) {
                    if ( (colourClassList[i] == 'colour-label-tag') || 
                        (colourClassList[i] == 'colour-noclick') ||
                        (colourClassList[i] == tagName) ) continue;
                    $(tagEl).removeClass(colourClassList[i]);
                }
                               
                tagEl.addClass(addColour);
                tagEl.addClass('colour-selected');
                                
                /* Add the colour value*/
                var colourTagName = 'tagColour-'+colourArray[2]+'-'+colourArray[3];
                var colourTag = '.'+colourTagName;
                var colourTagEl = $(colourTag);
                $(colourTagEl).val(addColour);
                                
				
                return false;
				
            }
        });
	
        jQuery('.design-thumbs ul').each(function()
        {
            if( $(this).is(':visible') ) {
		   
                var thumbs;
                thumbs = jQuery(this).find('li:not(:first)').hide();
            }
        });
	
        jQuery('.design-thumbs a ').hover(
            function()
            {
                if( $(this).is(':visible') )
                {
                    var ul;

                    ul = jQuery(this).closest('ul');
                    ul.addClass('magnify');
                    jQuery(this).fadeTo(200, 0.6);
				   
                    /* Add the lightbox attributes */
                    var className = $(this).attr("class");
                    className = '.'+className;
                    $(className).each(function()
                    { 
                        $(this).attr( "rel", "lightbox[1]");
                    });
                    $("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el)
                    {
                        return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
                    });
                }
            },
            function()
            {
                if( $(this).is(':visible') )
                {
                    var ul;
	
                    ul = jQuery(this).closest('ul');
                    ul.removeClass('magnify');
                    jQuery(this).fadeTo(200, 1);
			   
                    /* Remove the lightbox attributes */
                    var className = $(this).attr("class");
                    className = '.'+className;
                    $(className).each(function()
                    {
                        $(this).removeAttr( "rel");
                    });
                }
            }
            );
			
        jQuery('li[class^="pic-"]:visible').each(function()
        {
            var colourClassMatches;

            colourClassMatches = this.className.match(/\bpic-(.*)?\b/);
            jQuery(this).closest('li.design').find('a.colour-'+colourClassMatches[1]).addClass('colour-selected');
        });
    });
