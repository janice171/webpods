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
WS.currentBlogId = 0;    
WS.displaySection = function(section) {
    $("#blogList").hide();
    $("#blogItem").hide();
    if ( section == '#blogItem') {
        
        $(".user-edit-right").append(WS.blogDisplayHTML);
        $("#userEditCommonForm").attr('onSubmit', "return WS.blogSubmit();");
    }
    if ( section == '#blogList') {
        
        $("#userEditBlogBackToList").remove();
        $("#userEditCommonForm").removeAttr('onSubmit');
    }
    $(section).show();
};
WS.blogEdit = function(link, event){
    event.preventDefault(); 
    /* Get the data from the backend */
    var url = WS.blogAJAXProcessorLink;
    var idArray = link.split('=');
    var id = idArray[1];
    url = url+"?id="+id+"&command=edit";
     $.getJSON(url,"",function(response){ 
        /* Check for error */
        if ( response.error != 'none') {
            $('#userEditBlogTinyMCEcontent').val('Sorry! No blog was found with this id');
            WS.displaySection("#blogItem");
            return false;
        };
        WS.currentBlogId = response.id;
        $('#userEditBlogTitle').val(response.title);
        $('#userEditBlogTinyMCEcontent').val(response.content);
        $('#userBlogPage').val(response.id);
        
        /* Switch panes */
        WS.displaySection("#blogItem");
        
     });   
};
WS.blogDelete = function(link, event){
    event.preventDefault(); 
    /* Send the data to the backend */
    var url = WS.blogAJAXProcessorLink;
    var idArray = link.split('=');
    var id = idArray[1];
    url = url+"?id="+id+"&command=delete";
    $.get(url,"",function(response){
        
        /* Just reload the page */
         window.location.reload(true);
    }); 
};
WS.blogSubmit = function(){
   var formData = $("#userEditBlogForm").serialize();
   var url = WS.blogAJAXProcessorLink + "?command=save";
   $.ajax({  
        type: "POST",  
        url: url,  
        data: formData,  
        success: function() {
            
            var commonData = $("#userEditCommonForm").serialize();
            commonData = commonData+"&userEditCommonFormSubmit=submit";
            var commonURL = window.location.href;
            $.ajax({  
                type: "POST",  
                url: commonURL,  
                data: commonData,  
                success: function() {
                    window.location.reload(true);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("An Error has occured");
        }
    });
    return false;
};
WS.blogCreate = function(link, event){
     event.preventDefault();
     /* Set the blog id to -1 to indicate a create */
     $('#userBlogPage').val("-1");
     /* Switch panes */
     WS.displaySection("#blogItem");  
       
};

WS.backToBlogs = function() {
    
    WS.displaySection('#blogList');
}

/* Page processing start */
$(document).ready(function(){
        
    /* Add the blog edit link processing */
    $(".ajaxBlogEditLink").bind("click",function(event){
        WS.blogEdit(this.href,event);
    });
    
    /* Add the blog delete link processing */
    $(".ajaxBlogDeleteLink").live("click",function(event){
        WS.blogDelete(this.href,event);
    });
    
    /* Add the blog create link processing */
    $(".ajaxBlogCreateLink").live("click",function(event){
        WS.blogCreate(this.href,event);
    });
    
    /* Pagination */
    $('#blogList').pajinate({
					items_per_page : 10,
                    nav_label_first : '<<',
					nav_label_last : '>>',
					nav_label_prev : '<',
					nav_label_next : '>'
	});
    
   /* Initial display */
    WS.displaySection("#blogList");
  
});



