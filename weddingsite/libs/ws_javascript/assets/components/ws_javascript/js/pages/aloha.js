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

function saveEditable(event, eventProperties) {
        var content = eventProperties.editable.getContents();
        var id = eventProperties.editable.getId();
        var url = WS.AlohaAJAXProcessorLink+"?element="+id+"&content="+content+"&id="+WS.resourceId;
		$.ajax({url: url,
                async: true,
                dataType: "text",
                success: function(data){
                           data = decodeURI(data); 
                },
                error: function(a,b,c){
                           var wearehere = 0;
                }
        });
	}    


 
Aloha.ready(function() {
	$('.alohaeditable').aloha();
        Aloha.bind('aloha-editable-deactivated', saveEditable)
});
;


