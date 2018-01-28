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

    /* Plupload */
    $(function() {
        
        $("#uploader").plupload({
        
            // General settings
            runtimes : 'html5,flash,silverlight,html4,gears,browserplus',
            url : WS.pluploadURL,
            max_file_size : '50mb',
            chunk_size : '10mb',
            unique_names : false,
            // Specify what files to browse for
            filters : [
            {
                title : "Image files", 
                extensions : "jpg,jpeg,jpe,gif,png"
            },
            {   title : "Zip files", 
                extensions : "zip"}
            ],
 
            // Flash settings
            flash_swf_url : WS.pluploadFlash,

            // Silverlight settings
            silverlight_xap_url : WS.pluploadSilverlight
        });
    
        /* Get the uploader */
        var uploader = $('#uploader').plupload('getUploader');
   
        /* Event bindings */
        uploader.bind('FilesAdded', WS.filesAdded);
        uploader.bind('UploadComplete', WS.uploadComplete);
    
    
    });

});

