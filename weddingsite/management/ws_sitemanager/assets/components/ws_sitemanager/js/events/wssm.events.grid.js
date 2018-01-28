/**
 * Loads a grid of MODx Wedding users.
 * 
 * @class WSSM.grid.Event
 * @extends MODx.grid.Grid
 * @param {Object} config An object of config properties
 * @xtype wssm-grid-event
 */
WSSM.grid.Event = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		url: WSSM.config.connector_url
		,baseParams: {
            action: 'users/getlist'
		}
		,preventRender: true
        ,autoWidth: true
		,fields: ['id','username','website','firstName','lastName','blocked']
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
	WSSM.grid.Event.superclass.constructor.call(this,config);
};

Ext.extend(WSSM.grid.Event,MODx.grid.Grid,{
	
    getMenu: function() {
        var m = [];
        m.push({
            text: _('wssm_user_update')
                    ,handler: this.updateUser
                }); 
        m.push({
            text: _('wssm_event_add')
                    ,handler: this.addEvent
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
             header: _('wssm_name')
            ,dataIndex: 'username'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_user_email')
            ,dataIndex: 'website'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
        },{
			header: _('wssm_user_firstname')
            ,dataIndex: 'firstName'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
        },{
			header: _('wssm_user_lastname')
            ,dataIndex: 'lastName'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_user_block')
            ,dataIndex: 'blocked'
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
               switch(value) {
               case false:
                    metaData.css = 'green';
                    return _('no');
                case true:
                    metaData.css = 'red';
                    return _('yes');
                }
            }
        }];
	}
  
    ,filterByName: function(tf,newValue,oldValue) {
        this.getStore().baseParams = {
            action: 'users/getList'
            ,username: tf.getValue()           
        };
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    
    ,updateUser: function() {
        location.href = '?a='+MODx.request.a+'&action=getEventList'+'&id='+this.menu.record.id;

    }
    
    ,addEvent: function() {
        location.href = '?a='+MODx.request.a+'&action=addEvent'+'&id='+this.menu.record.id;

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
Ext.reg('wssm-grid-event',WSSM.grid.Event);






