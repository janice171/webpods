jQuery(document).ready(function()
    {
        jQuery('.colour-label').hover(function()
        {
            if( $(this).is(':visible') ) {
            
                var colourClass, colourArray, colour, theme, style;
	
                colourClass = this.classList[1];
                colourArray = colourClass.split('-');
                colour =  colourArray[1];
                theme = this.classList[2];
                style = this.classList[3];
                
                newThumb = jQuery(this).closest('li.design').find('.pic-'+colour+':hidden');
                newThumb.parent().find('li:visible').stop(false, true).stop(false, true).fadeOut(200, function()
                {
                    newThumb.fadeIn(200);
                });
                
                /* Update the design preview link colour */
                var selectString = ".btn-preview."+theme+"."+style;
                var button = $(selectString);
                var link = $(button).attr('href');
                var newColour = "colour="+colour;
                var newLink = link.replace(/colour=.*$/, newColour);
                $(button).attr('href', newLink);
                
            }
        });
	
        jQuery('.colour-label').click(function()
        {
		
            if( $(this).is(':visible') ) {
                
                var colourClass, colourArray, colour, theme, style;

                colourClass = this.classList[1];
                colourArray = colourClass.split('-');
                colour =  colourArray[1];
                theme = this.classList[2];
                style = this.classList[3];

                /* Update the design preview link colour */
                var selectString = ".btn-preview."+theme+"."+style;
                var button = $(selectString);
                var link = $(button).attr('href');
                var newColour = "colour="+colour;
                var newLink = link.replace(/colour=.*$/, newColour);
                $(button).attr('href', newLink);
                
                jQuery(this).closest('li.design').find('.pic-'+colour+' a').click();
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
                if( $(this).is(':visible') ) {
                
                    var ul;
		
                    ul = jQuery(this).closest('ul');
                    ul.addClass('magnify');
                    jQuery(this).fadeTo(200, 0.6);
                   
                   /* Add the lightbox attributes */
                   var className = $(this).attr("class");
                   className = '.'+className;
                   $(className).each(function(){ 
                       $(this).attr( "rel", "lightbox[1]");
                   });
                   $("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
			return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});
                   
                }
            },
            function()
            {
                if( $(this).is(':visible') ) {
                    
                    var ul;
		
                    ul = jQuery(this).closest('ul');
                    ul.removeClass('magnify');
                    jQuery(this).fadeTo(200, 1);
                   
                   /* Remove the lightbox attributes */
                   var className = $(this).attr("class");
                   className = '.'+className;
                   $(className).each(function(){
                       $(this).removeAttr( "rel");
                        });
                   
                }
            }
            );
    });
