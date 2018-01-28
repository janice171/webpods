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
    
   var gTable = $("#eventList").dataTable();
    
   /* Free form edit entries */
    $(".eventEditable", gTable.fnGetNodes()).editable( WS.ajaxEditableLink, {
        
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
                "type": "normaledit"
            };
        },
        "height": "14px",
        "tooltip"   : "Click to edit..."
    } );
        
    /* Date edit entries */
    $(".eventEditableDate", gTable.fnGetNodes()).editable( WS.ajaxEditableLink, {
        
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
                "type" : "dateedit"
            };
        },
        "height": "14px",
        "tooltip"   : "Click to edit...",
        "type" : "datepicker",
        datepicker: {
        changeMonth: true,
        changeYear: true,
        showOn: "both",
		minDate: -2, maxDate: "+48M +28D",
        dateFormat: "yy-mm-dd"}
    } );

  /* Add the events list active state AJAX processing */
   activeLinkEl = "";
   $(".userEventActiveLink").bind("click",function(event){
      activeLinkEl = this;
      event.preventDefault();       
      $.get(this.href,"", function(response){
             var activeLink = $(activeLinkEl);
            /* Toggle the class */
            if ( activeLink.hasClass("on")) {
                  activeLink.removeClass("on");
                  activeLink.addClass("off");
            } else {
                 activeLink.removeClass("off");
                 activeLink.addClass("on");
            }
        });
     });
     
    /* Top menu active selection */
     $(".sf-menu li a").each(function(){
         var title = $(this).attr("title");
         if ( title == "Guest and Event Planner") $(this).addClass("active");
      });
      
       /* Add mouseouts on editable classes to trigger closure */
        $(".eventEditable, .eventEditableDate").mouseout(function(){
        $(this).find("form").submit();
      });
      
});

