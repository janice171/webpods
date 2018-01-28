/**
 * Loads a grid of MODx Wedding users invites.
 * 
 * @class WSSM.grid.InviteList
 * @extends MODx.grid.Grid
 * @param {Object} config An object of config properties
 * @xtype wssm-grid-event-invite-list
 */
WSSM.grid.InviteList = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		url: WSSM.config.connector_url
		,baseParams: {
            action: 'events/getinvitelist'
            ,id: this.getId()
		}
		,preventRender: true
        ,autoWidth: true
		,fields: ['id','guestName','willAttend','InviteSent','RSVPDate','lastReminderDate']
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
	WSSM.grid.InviteList.superclass.constructor.call(this,config);
};

Ext.extend(WSSM.grid.InviteList,MODx.grid.Grid,{
	
    getId: function() {
        var params = MODx.getURLParameters();
        return params.id;
    },
    
    getMenu: function() {
        var m = [];
        m.push({
            text: _('wssm_event_update')
                    ,handler: this.updateInvite
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
             header: _('wssm_event_invite_guestname')
            ,dataIndex: 'guestName'
            ,sortable: true
            ,editable: false
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
	    header: _('wssm_event_invite_willattend')
            ,dataIndex: 'willAttend'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
               switch(value) {
               case false:
                    metaData.css = 'red';
                    return _('no');
                case true:
                    metaData.css = 'green';
                    return _('yes');
                }
            }
        },{
	     header: _('wssm_event_invite_sent')
            ,dataIndex: 'InviteSent'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                switch(value) {
               case false:
                    metaData.css = 'red';
                    return _('no');
                case true:
                    metaData.css = 'green';
                    return _('yes');
                }
            }
        },{
	     header: _('wssm_event_invite_rsvpdate')
            ,dataIndex: 'RSVPDate'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
	     header: _('wssm_event_invite_lastreminderdate')
            ,dataIndex: 'lastReminderDate'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		
        }];
	}
  
    ,updateInvite: function() {
        location.href = '?a='+MODx.request.a+'&action=updateInvite'+'&id='+this.menu.record.id;

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
Ext.reg('wssm-grid-event-invite-list',WSSM.grid.InviteList);








