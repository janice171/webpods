/**
 * Loads a grid of MODx Wedding users events.
 * 
 * @class WSSM.grid.EventList
 * @extends MODx.grid.Grid
 * @param {Object} config An object of config properties
 * @xtype wssm-grid-event-list
 */
WSSM.grid.EventList = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		url: WSSM.config.connector_url
		,baseParams: {
            action: 'events/getlist'
            ,id: this.getId()
		}
		,preventRender: true
        ,autoWidth: true
		,fields: ['id','name','date','location','startTime','endTime','maxGuests']
        ,columns: this.getColumns()
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec){
                return rec.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }}
	});
	WSSM.grid.EventList.superclass.constructor.call(this,config);
};

Ext.extend(WSSM.grid.EventList,MODx.grid.Grid,{
	
    getId: function() {
        var params = MODx.getURLParameters();
        return params.id;
    },
    
    getMenu: function() {
        var m = [];
        m.push({
            text: _('wssm_event_update')
                    ,handler: this.updateEvent
                });    
                
         m.push({
            text: _('wssm_event_remove')
                    ,handler: this.removeEvent
                });    
        
         m.push({
            text: _('wssm_event_invites')
                    ,handler: this.invitesEvent
                }); 
                
        this.addContextMenuItem(m);
    }
    ,getColumns: function() {		
		var gs = new Ext.data.SimpleStore({
			fields: ['text','value']
			,data: [['-',0],[_('male'),1],[_('female'),2]]
		});
		
		return [{
			header: _('id')
            ,dataIndex: 'id'
            ,sortable: false
            ,editor: {xtype: 'textfield'}
            ,editable: false
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
             header: _('wssm_event_name')
            ,dataIndex: 'name'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_event_date')
            ,dataIndex: 'date'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
        },{
			header: _('wssm_event_location')
            ,dataIndex: 'location'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
        },{
			header: _('wssm_event_starttime')
            ,dataIndex: 'startTime'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_event_endtime')
            ,dataIndex: 'endTime'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_event_maxguests')
            ,dataIndex: 'maxGuests'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
        }];
	}
  
    ,updateEvent: function() {
        location.href = '?a='+MODx.request.a+'&action=updateEvent'+'&id='+this.menu.record.id;

    }
    
    ,removeEvent: function() {
        location.href = '?a='+MODx.request.a+'&action=removeEvent'+'&id='+this.menu.record.id;

    }
    
    ,invitesEvent: function() {
        location.href = '?a='+MODx.request.a+'&action=invitesEvent'+'&id='+this.menu.record.id;

    }
    
    ,rendYesNo: function(d,c) {
        switch(d) {
            case '':
                return '-';
            case false:
                c.css = 'red';
                return _('no');
            case true:
                c.css = 'green';
                return _('yes');
        }
    }
});
Ext.reg('wssm-grid-event-list',WSSM.grid.EventList);






