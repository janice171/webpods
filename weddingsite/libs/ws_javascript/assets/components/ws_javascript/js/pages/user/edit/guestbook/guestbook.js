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
/* Initialise the page */

/* WS object properties and helper functions */
WS.currentMessageId = 0;    
WS.activeModerateLink = "";

WS.messageRead = function(link, event){
    event.preventDefault(); 
    /* Get the data from the backend */
    var url = WS.guestbookAJAXProcessorLink;
    var idArray = $(link).attr("href").split('=');
    var id = idArray[1];
    url = url+"?id="+id+"&command=read";
     $.getJSON(url,"",function(response){ 
        /* Check for error */
        if ( response.error != 'none') {
            $('#userEditGuestbookMessageContent').val('Sorry! No message was found with this id');
            return false;
        };
        WS.currentMessageId = response.id;
        $('#userEditGuestbookMessageSender').val(response.name);
        $('#userEditGuestbookMessageEmail').val(response.email);
        $('#userEditGuestbookMessageContent').val(response.body);
        
     });   
};
WS.messageDelete = function(link, event){
    event.preventDefault(); 
    /* Send the data to the backend */
    var url = WS.guestbookAJAXProcessorLink;
    var idArray = link.split('=');
    var id = idArray[1];
    url = url+"?id="+id+"&command=delete";
    $.get(url,"",function(response){
        
        /* Just reload the page */
         window.location.reload(true);
    }); 
};
WS.messageModerate = function(link, event){
    event.preventDefault(); 
    /* Send the data to the backend */
    var url = WS.guestbookAJAXProcessorLink;
    var idArray = link.split('=');
    var id = idArray[1];
    url = url+"?id="+id+"&command=moderate";
    $.get(url,"",function(response){
        var activeLink = $(WS.activeModerateLink);
            /* Toggle the class */
            if ( activeLink.hasClass("on")) {
                  activeLink.removeClass("on");
                  activeLink.addClass("off");
            } else {
                 activeLink.removeClass("off");
                 activeLink.addClass("on");
            }
       
    }); 
};

/* Page processing start */
$(document).ready(function(){
        
    /* Add the guestbook edit link processing */
    $(".ajaxGuestbookMessageReadLink").bind("click",function(event){
        WS.messageRead(this,event);
    });
    
    /* Add the guestbook delete link processing */
    $(".ajaxGuestbookMessageDeleteLink").live("click",function(event){
        WS.messageDelete(this.href,event);
    });
    
     /* Add the guestbook moderate link processing */
    $(".ajaxGuestbookMessageModerateLink").live("click",function(event){
        WS.activeModerateLink = this;
        WS.messageModerate(this.href,event);
    });
    
    /* Pagination */
    $('#guestbookList').pajinate({
					items_per_page : 5,
                    nav_label_first : '<<',
					nav_label_last : '>>',
					nav_label_prev : '<',
					nav_label_next : '>'
	});
    
});
