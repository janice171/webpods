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
    
/* WS object properties and helper functions */
WS.currentAlbum = 0;    
WS.displaySection = function(section) {
    $("#galleryAlbum").hide();
    $("#galleryAlbumCreate").hide();
    $("#galleryItem").hide();
    $(section).show();
};
WS.createAlbum = function() {
    if ( WS.albumCount <= 5 ) {
        WS.displaySection("#galleryAlbumCreate");
    } else {
        alert("You can only create 6 albums in total.");
    }
};
WS.albumEdit = function(link, event) { 
    event.preventDefault();    
    /* Set the current album id */
    var hrefArray  = link.split("=");
    var albumIdString = hrefArray[1];
    var albumIdArray = albumIdString.split("&");
    var albumId = albumIdArray[0];
    WS.currentAlbum = albumId;
            
    /* Get the album details */
    $.getJSON(link,"",function(response){ 
        /* Response processing */
        $('#userEditGalleryItemAlbumName').val(response.name); 
        $('#userEditGalleryItemAlbumDescription').val(response.description);
        /* Switch panels */
        WS.displaySection("#galleryItem");
        /* Get the pictures */
        WS.getAlbumItems(WS.currentAlbum);           
        /* Hide the common header */
        $("#userEditGalleryEditCommon").hide();
    });
        
};      
WS.albumDetailUpdate = function() { 
    /* Get the album details */
    var albumName = $('#userEditGalleryItemAlbumName').val();
    var albumDescription = $('#userEditGalleryItemAlbumDescription').val();
    /* Construct the url */
    var urlParameters = 'id='+WS.currentAlbum+'&command=albumDetailsUpdate&name='+albumName+'&description='+albumDescription;
    /* Post the update */
    $.ajax({  
        type: "POST",  
        url: WS.galleryAJAXProcessorLink,  
        data: urlParameters,  
        success: function() {
            
            /* Alert on success */
            alert("Thank You - your gallery album settings have been updated");
            
        }
    });
    return false;
};
WS.filesAdded = function() {
    /* Signal the change to the server */
    /* Nothing to do here currently */ 
    return true;    
};
WS.uploadComplete = function() {
    /* Signal the change to the server */
    var urlParameters = 'id='+WS.currentAlbum+'&command=uploadComplete';
    $.ajax({  
        type: "POST",  
        url: WS.galleryAJAXProcessorLink,  
        data: urlParameters,  
        success: function() {
            /* Reload the pictures */
            WS.getAlbumItems(WS.currentAlbum);
        }
    });
         
    return true;    
       
};
WS.getAlbumItems = function(albumId) {
    /* Remove the existing pictures */
    $('#userEditGalleryItems').empty();
    /* Construct the url */
    var urlParameters = 'id='+WS.currentAlbum+'&command=albumGetItems';
    $.getJSON( WS.galleryAJAXProcessorLink,urlParameters,function(response){ 
        /* Response processing */
        if ( response.items.length == 0 ) {
            $(".gallery-inner").hide();
            $("#galleryDragDropText").text("");
        } else {
            $.each(response.items, function(key,value) {
                $('#userEditGalleryItems').append(value);
              
                });
            /* Click the first gallery item */
            $("#userEditGalleryItems").children(":first").children(":first").trigger("click");
            $(".gallery-inner").show(); 
            $("#galleryDragDropText").text("Drag the photos to re-order them.");
        }
           
    });   
                
};
WS.itemEdit = function(link, event) { 
    event.preventDefault();   
    /* Update the legend */
    $('#userEditGalleryItemLegend').html("");
    /* Move the picture to the edit area */
    var image = $(link).children();
    var imageSrc = image.attr("src");
    var imageAlt = image.attr("alt");
    $(".gallery-itemEditImage").attr("src", imageSrc);
    $(".gallery-itemEditImage").attr("alt", imageAlt);
    /* Get the item details */
    var url = link.href;
    $.getJSON(url,"",function(response){ 
        /* Check for error */
        if ( response.error != 'none') {
            $('#userEditGalleryItemLegend').html('Sorry! No item was found with this id');
            return false;
        };
        /* Process the response */
        var imageSrc = $(".gallery-itemEditImage").attr("src");
        $(".gallery-itemEditImageDetail").attr("src", imageSrc);
        $('#userEditGalleryItemDisplayName').val(response.displayname);
        $('#userEditGalleryItemDescription').val(response.description);
        $('#userEditGalleryItemAlbumImage').removeAttr('checked');
        if ( response.useForAlbum ) {
            $('#userEditGalleryItemAlbumImage').attr('checked','checked');
        }
        $('#userEditGalleryItemActive').attr('checked','');
        if ( response.status == 1 ) $('#userEditGalleryItemActive').attr('checked','checked');
        $('#userEditGalleryItemId').val(response.id);
        return true;
    });
        
};
WS.albumItemDetailUpdate = function() { 
    /* Check for a valid edit image */
    var id = $('#userEditGalleryItemId').val();
    if ( id == -1 ) return false;
    /* Construct the url parameters */
    var uncheckAlbumImage = 1;
    if ( $('#userEditGalleryItemAlbumImage').is(':checked') ) {
        uncheckAlbumImage = 0;
    }
    var urlParameters = $("#userEditGalleryItemDetailForm").serialize();
    urlParameters = urlParameters + '&id='+WS.currentAlbum+'&command=albumUpdateItem&uncheckAlbumImage='+uncheckAlbumImage;
    /* Post the update */
    $.ajax({  
        type: "POST",  
        url: WS.galleryAJAXProcessorLink,  
        data: urlParameters,  
        success: function() { 
            /* Update the legend */
            $('#userEditGalleryItemLegend').html("");
            /* Set the new title of the item */
            var editAltAttr = $(".gallery-itemEditImage").attr("alt");
            $(".gallery-itemImageSrc").each(function(index){
                /* Compare the alt ids*/
                var altAttr = $(this).attr('alt');
                if (editAltAttr == altAttr ){
                    /* Update the title and exit */
                    var title = $('#userEditGalleryItemDisplayName').val();
                    $(this).attr('title', title);
                    return false;     
                }
                         
            });
        }
    });
    return false;
};
WS.itemDelete = function(){
    /* Delete the picture */
    var id = $('#userEditGalleryItemId').val();
    var urlParameters = '&id='+WS.currentAlbum+'&command=albumItemDelete&itemId='+id;
    $.ajax({  
        type: "POST",  
        url: WS.galleryAJAXProcessorLink,  
        data: urlParameters,  
        success: function() {
            /* Update the legend */
            $('#userEditGalleryItemLegend').html("");
            /* Reset the edit picture */
            $(".gallery-itemEditImage").attr("src", "assets/templates/wedding/images/small/ornate-classic-silverBlue.jpg");
            /* Set the edit image id to not available */
            $('#userEditGalleryItemId').val(-1);
            /* Clear the form fields */
            $('#userEditGalleryItemDisplayName').val("");
            $('#userEditGalleryItemDescription').val("");
            $('#userEditGalleryItemActive').attr('checked','');
            /* Reload the pictures */
            WS.getAlbumItems(WS.currentAlbum);              
        }
    });
    return false;
};
WS.sortItems = function(event) {
      
    var imageArray = new Array();
    /* Get all the images */
    $(".gallery-itemImageSrc").each(function(index){
           
        /* Split off the id */
        var altAttr = $(this).attr('alt');
        var idArray = altAttr.split('-');
        var id = idArray[0];
        /* Set the new position for this id */
        imageArray[index] = id;             
    });
    /* Check for no items */
    if ( imageArray.length == 0 ) return false;
    /* Stringify */
    var data = JSON.stringify(imageArray);
    /* Post the update */
    var urlParameters = 'id='+WS.currentAlbum+'&command=galleryItemReposition&positions='+data;
    $.ajax({  
        type: "POST",  
        url: WS.galleryAJAXProcessorLink,  
        data: urlParameters,  
        success: function() {
               
        }
    });
};

WS.sortAlbums = function(event) {
      
    var albumArray = new Array();
    /* Get all the images */
   var here =  $(".gallery-albumImageSrc").each(function(index){
           
        /* Split off the id */
        var altAttr = $(this).attr('alt');
        var idArray = altAttr.split('-');
        var id = idArray[0];
        /* Set the new position for this id */
        albumArray[index] = id;   
        WS.currentAlbum = id;
    });
    /* Check for no items */
    if ( albumArray.length == 0 ) return false;
    /* Stringify */
    var data = JSON.stringify(albumArray);
    /* Post the update */
    var urlParameters = 'id='+WS.currentAlbum+'&command=albumReposition&positions='+data;
    $.ajax({  
        type: "POST",  
        url: WS.galleryAJAXProcessorLink,  
        data: urlParameters,  
        success: function() {
               
        }
    });
};
   
/* Page processing start */
$(document).ready(function(){
    
    /* Add the page status list AJAX processing */
    currentLink = "";
    $(".gallery-ajaxLink").bind("click",function(event){
        event.preventDefault();
        currentLink = this;
        $.get(this.href,"",function(response){ 
            /* Response processing */
            if ( response == "on" ) {
                currentLink.className = currentLink.className.replace(/\boff\b/,"");
                currentLink.className += " on";
            } else {
                currentLink.className = currentLink.className.replace(/\bon\b/,"");
                currentLink.className += " off";
            }
        })  
    });
     
    /* Add the album link processing */
    $(".gallery-albumLink").bind("click",function(event){
        WS.albumEdit(this.href,event);
    });
    
    /* Add the item  link processing */
    $(".gallery-itemLink").live("click",function(event){
        WS.itemEdit(this,event);
    });
          
    /* Add the image delete link processing */
    $(".userEditGalleryItemDelete").bind("click",function(event){
        event.preventDefault();
        WS.itemDelete();
    });
          
    /* Change bindings for the item detail edit */
    $('#userEditGalleryItemAlbumImage').change(WS.albumItemDetailUpdate);
    /* Mouse  out bindings for the item detail edit */
    $('#userEditGalleryItemDisplayName').mouseout(WS.albumItemDetailUpdate);
    $('#userEditGalleryItemDescription').mouseout(WS.albumItemDetailUpdate);
 
    /* Make the images sortable */
    WS.sortable =  $("#userEditGalleryItems").sortable();
    $(WS.sortable).bind("sortupdate", function(event){
        WS.sortItems(event);
    });
   
    /* Make the album list sortable */
    WS.albumSortable = $(".user-gallery-albums").sortable();
    $(WS.albumSortable).bind("sortupdate", function(event){
        WS.sortAlbums(event);
    });
    
    /* Initial display */
    $(".gallery-inner").hide();
    WS.displaySection("#galleryAlbum");
    
    /* Confirmation alerts 
    $('#userSettingsWebsiteSubmit').click( function() {
    	alert("Thank You - your gallery settings have been updated");
    }); */
  
});

