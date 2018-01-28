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

$(function() {
    $('textarea.tinymce').tinymce({
        // Location of TinyMCE script
        script_url : WS.tinymceURL,

        // General options
        theme : "advanced",
        plugins : "style,advlink,inlinepopups,media,contextmenu,fullscreen,lioniteimages",

        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "bullist,numlist,|,undo,redo,|,link,unlink, lioniteimages, |,forecolor,media,|,fullscreen",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Example content CSS (should be your site CSS)
         content_css : "http://www.theweddingwebsitegroup.com/assets/templates/wedding/styles/main.css",

        // Drop lists for link/image/media/template dialogs
        //template_external_list_url : "lists/template_list.js",
        //external_link_list_url : "lists/link_list.js",
        //external_image_list_url : "lists/image_list.js",
        //media_external_list_url : "lists/media_list.js",

        // Replace values for the template plugin
        //template_replace_values : {
         //   username : "Some User",
        //    staffid : "991234"
        //},
        // WS customisations 
        height : '90%',
        //file_browser_callback : "WS.filebrowser",
        
        // Format using classes
        formats : {
                alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyleft'},
                aligncenter : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifycenter'},
                alignright : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyright'},
                alignfull : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyfull'}
                // You can also specify selectors if needed to match the text selection to
                //alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
        },
        
        // Edit this to change the elements in the 'format' dropdown menu'
        theme_advanced_blockformats : "p,h1,h2,h3,h4,h5"
        
    });
});
