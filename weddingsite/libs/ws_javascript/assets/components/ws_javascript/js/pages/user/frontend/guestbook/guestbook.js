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

/* Page code */
$(document).ready(function(){
     
    /* Properties */
    WS = new Object(); 
    WS.submitForm = false;
    WS.doSubmit = function() {
        $.post(document.URL, $("#quip-add-comment-qcom").serialize(), function(data) {
            window.location.reload();
        }).error(function(jqXHR, textStatus, errorThrown) { 
            window.location.reload();
        });
    };
        
    /* Hide the comment form */
    $("#userGuestbookFEFormWrapper").hide();
        
    /* Form opener functionality */
    $(".open-form").bind("click", function(event){
        event.preventDefault();
        $("#userGuestbookFEFormWrapper").show();
        $("#userGuestbookFEnospam").val(1);
        WS.submitForm = true;
    });
    
    /* Form submit functionality */
    $("#quip-add-comment-qcom").bind("submit", function(event){
        if ( WS.submitForm ) WS.doSubmit();
        event.preventDefault();
        return false;
    });
        
    /* Pagination */
    $("#guestbookComments").pajinate({
        items_per_page : 12,
        nav_label_first : "<< ",
        nav_label_last : " >>",
        nav_label_prev : "< ",
        nav_label_next : " >"
    });
        
});
