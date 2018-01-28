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


/* WS setup */
WS.gTable = null;

/* Functions */
WS.getEventData = function(id){
    if (!id) return;
    /* Destroy datatables */
    if ( WS.gTable ) WS.gTable.fnDestroy(); 
    /* Get the data */
    var urlParams = "command=getEventDetails&event="+id;
    $.getJSON(WS.ajaxLink,urlParams,function(response){ 
        /* Response processing */
        $("#userInvitesEventsPartner1").val(response.partner1); 
        $("#userInvitesEventsDatepicker").val(response.date);
        $("#userInvitesEventsAddress1").val(response.location);
        $("#userInvitesEventsAddress2").val(response.address2);
        $("#userInvitesEventsAddress3").val(response.address3);
        $("#userInvitesEventsAddress4").val(response.address4);
        $("#userInvitesEventsStartTime").val(response.start);
        $("#userInvitesEventsEndTime").val(response.end);
        $("#userInvitesEventsPhone").val(response.phone);
        $("#userInvitesEventsEmail").val(response.email);
        $("#invitesGuestListBody").html(response.guestDetails);    
        $("#userInvitesEventsNotes").html(response.notes);  
        /* Attach datatables */
        $("#invitesGuestList").css("width", "100%");
        WS.gTable =  $("#invitesGuestList").dataTable({
            "bDestroy": true
        });
                
        /* Apply the jEditable handlers to the table */
        $(".guestEditableYN", WS.gTable.fnGetNodes()).editable( WS.ajaxLink, {

            "id"   : "elementid",
            "callback": function( sValue, y ) {
                var aPos = WS.gTable.fnGetPosition( this );
                WS.gTable.fnUpdate( sValue, aPos[0], aPos[1] );
            },
            "submitdata": function ( value, settings ) {
                var ret_arr = WS.gTable.fnGetPosition( this );
                var row = ret_arr[0];
                var data_row = WS.gTable.fnGetData(row);
                var primaryKey = data_row[0];
                var col = ret_arr[2];
                var oSettings = WS.gTable.fnSettings();
                var columnName = oSettings.aoColumns[col].sTitle;  
                return {
                    "row_id": primaryKey,
                    "column": columnName,
                    "type"  : "yesnoedit"
                };
            },
            "height": "14px",
            "type" : "select",
            "data" : WS.selector,
            "tooltip"   : "Click to edit..."
        });

        $(".guestEditable", WS.gTable.fnGetNodes()).editable( WS.ajaxLink, {

            "id"   : "elementid",
            "callback": function( sValue, y ) {
                var aPos = WS.gTable.fnGetPosition( this );
                if ( sValue == "" ) {
                    this.editing = true;
                    this.reset();
                    this.editing = false;
                } else {
                    WS.gTable.fnUpdate( sValue, aPos[0], aPos[1] );
                }
            },
            "submitdata": function ( value, settings ) {
                var ret_arr = WS.gTable.fnGetPosition( this );
                var col = ret_arr[2];
                var row = ret_arr[0];
                var data_row = WS.gTable.fnGetData(row);
                var primaryKey = data_row[0];
                var oSettings = WS.gTable.fnSettings();
                var columnName = oSettings.aoColumns[col].sTitle;  
                return {
                    "row_id": primaryKey,
                    "column": columnName,
                    "type"  : "normaledit"
                };
            },
            "height": "14px",
            "tooltip"   : "Click to edit..."
        });  
        
        /* Select the current theme */
        var current = WS.currentTheme;
        var selection = '.invite-theme:eq('+current+')';
    	var selected = $(selection).click();
    	selection++;
    });
}; 
    
/* Page code */
$(document).ready(function(){  
      
    /* Events */
	
	$("#userInvitesEventsEventSelection").change(function() {
        var id = $("#userInvitesEventsEventSelection").val();
        WS.getEventData(id);
    });
    
    /* Invite themes select the current one */
	var current = WS.currentTheme;
	var selection = '.invite-theme:eq('+current+')';
	var selected = $(selection).click();
	
	/* Trigger the currently selected event */
    $("#userInvitesEventsEventSelection").trigger("change");
    
    
    /* Datatables/Jeditable */
     
    /* Force submit on change of Yes/No selectors */
    $("table select").live("change", function () {
        $(this).parent().submit();
    });
     
    /* Top menu active selection */
    $(".sf-menu li a").each(function(){
        var title = $(this).attr("title");
        if ( title == "Guest and Event Planner") $(this).addClass("active");
    });
      
    /* Add mouseouts on editable classes to trigger closure */
    $(".guestEditable").mouseout(function(){
        $(this).find("form").submit();
    });
      
});


