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
WS.messageRead = function(link, event){
    event.preventDefault(); 
    /* Get the data from the backend */
    var url = WS.rsvpAJAXProcessorLink;
    /* Mark as read */
    $(link).removeClass("user-message-unread");
    var idArray = $(link).attr("href").split('=');
    var id = idArray[1];
    url = url+"?id="+id+"&command=read";
     $.getJSON(url,"",function(response){ 
        /* Check for error */
        if ( response.error != 'none') {
            $('#userEditRSVPMessageContent').val('Sorry! No message was found with this id');
            return false;
        };
        WS.currentMessageId = response.id;
        $('#userEditRSVPMessageTitle').val(response.title);
        $('#userEditRSVPMessageContent').val(response.content);
        $('#userEditRSVPMessageSender').val(response.guestName);
        
     });   
};
WS.messageDelete = function(link, event){
    event.preventDefault(); 
    /* Send the data to the backend */
    var url = WS.rsvpAJAXProcessorLink;
    var idArray = link.split('=');
    var id = idArray[1];
    url = url+"?id="+id+"&command=delete";
    $.get(url,"",function(response){
        
        /* Just reload the page */
         window.location.reload(true);
    }); 
};

/* Page processing start */
$(document).ready(function(){
        
    /* Add the blog edit link processing */
    $(".ajaxRSVPMessageReadLink").bind("click",function(event){
        WS.messageRead(this,event);
    });
    
    /* Add the blog delete link processing */
    $(".ajaxRSVPMessageDeleteLink").live("click",function(event){
        WS.messageDelete(this.href,event);
    });
    
    /* Pagination */
    $('#messageList').pajinate({
					items_per_page : 5,
                    nav_label_first : '<<',
					nav_label_last : '>>',
					nav_label_prev : '<',
					nav_label_next : '>'
	});
    
});
