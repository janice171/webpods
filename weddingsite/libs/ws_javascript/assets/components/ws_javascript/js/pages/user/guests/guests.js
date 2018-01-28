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
    var gTable = $("#guestList").dataTable();
    
    /* Apply the jEditable handlers to the table */
    $(".guestEditableYN", gTable.fnGetNodes()).editable( WS.ajaxEditableLink, {
    
        "id"   : "elementid",
        "callback": function( sValue, y ) {
            var aPos = gTable.fnGetPosition( this );
            gTable.fnUpdate( sValue, aPos[0], aPos[1] );
        },
        "submitdata": function ( value, settings ) {
             var ret_arr = gTable.fnGetPosition( this );
             var row = ret_arr[0];
             var data_row = gTable.fnGetData(row);
             var primaryKey = data_row[0];
             var col = ret_arr[2];
             var oSettings = gTable.fnSettings();
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
    } );
    
$(".guestEditableInvite", gTable.fnGetNodes()).editable( WS.ajaxEditableLink, {
    
        "id"   : "elementid",
        "callback": function( sValue, y ) {
            var aPos = gTable.fnGetPosition( this );
            gTable.fnUpdate( sValue, aPos[0], aPos[1] );
        },
        "submitdata": function ( value, settings ) {
             var ret_arr = gTable.fnGetPosition( this );
             var row = ret_arr[0];
             var data_row = gTable.fnGetData(row);
             var primaryKey = data_row[0];
             var col = ret_arr[2];
             var oSettings = gTable.fnSettings();
             var columnName = oSettings.aoColumns[col].sTitle;  
             return {
                "row_id": primaryKey,
                "column": columnName,
                "type"  : "inviteedit"
            };
        },
        "height": "14px",
        "type" : "select",
        "data" : WS.inviteSelector,
        "tooltip"   : "Click to edit..."
    } );
    
   $(".guestEditable", gTable.fnGetNodes()).editable( WS.ajaxEditableLink, {
        
        "id"   : "elementid",
        "callback": function( sValue, y ) {
            var aPos = gTable.fnGetPosition( this );
            if ( sValue == "" ) {
                this.editing = true;
                this.reset();
                this.editing = false;
            } else {
                gTable.fnUpdate( sValue, aPos[0], aPos[1] );
            }
        },
        "submitdata": function ( value, settings ) {
            var ret_arr = gTable.fnGetPosition( this );
            var col = ret_arr[2];
            var row = ret_arr[0];
            var data_row = gTable.fnGetData(row);
            var primaryKey = data_row[0];
            var oSettings = gTable.fnSettings();
            var columnName = oSettings.aoColumns[col].sTitle;  
             return {
                "row_id": primaryKey,
                "column": columnName,
                "type"  : "normaledit"
            };
        },
        "height": "14px",
        "tooltip"   : "Click to edit..."
    } );
    
    /* Force submit on change of Yes/No selectors */
    $("select").live("change", function () {
             $(this).parent().submit();
        });
      
    /* Confirm box for the delete link */
    $(".deleteLink").jConfirmAction();
    
    /* Add mouseouts on editable classes to trigger closure */
        $(".guestEditable").mouseleave(function(){
        $(this).find("form").submit();
      });
      
    /* Top menu active selection */
     $(".sf-menu li a").each(function(){
         var title = $(this).attr("title");
         if ( title == "Guest and Event Planner") $(this).addClass("active");
      });
    
});


