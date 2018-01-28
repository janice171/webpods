/**
 * Loads a grid of MODx Wedding users guests.
 * 
 * @class WSSM.grid.GuestList
 * @extends MODx.grid.Grid
 * @param {Object} config An object of config properties
 * @xtype wssm-grid-guest-list
 */
WSSM.grid.GuestList = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		url: WSSM.config.connector_url
		,baseParams: {
            action: 'guests/getlist'
            ,id: this.getId()
		}
		,preventRender: true
        ,autoWidth: true
		,fields: ['id','name','email','address1','address2','city','telephone','guid', 'active']
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
	WSSM.grid.GuestList.superclass.constructor.call(this,config);
};

Ext.extend(WSSM.grid.GuestList,MODx.grid.Grid,{
	
    getId: function() {
        var params = MODx.getURLParameters();
        return params.id;
    },
    
    getMenu: function() {
        var m = [];
        m.push({
            text: _('wssm_guest_update')
                    ,handler: this.updateGuest
                });    
                
         m.push({
            text: _('wssm_guest_remove')
                    ,handler: this.removeGuest
                });  
                
         m.push({
            text: _('wssm_event_link')
                    ,handler: this.linkEvent
                });  
                
         m.push({
            text: _('wssm_event_link-remove')
                    ,handler: this.removeEvent
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
             header: _('wssm_guest_name')
            ,dataIndex: 'name'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_guest_email')
            ,dataIndex: 'email'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
        },{
			header: _('wssm_guest_address1')
            ,dataIndex: 'address1'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
        },{
			header: _('wssm_guest_address2')
            ,dataIndex: 'address2'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_guest_city')
            ,dataIndex: 'city'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_guest_telephone')
            ,dataIndex: 'telephone'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_guest_guid')
            ,dataIndex: 'guid'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
            }
		},{
			header: _('wssm_guest_active')
            ,dataIndex: 'active'
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
        }];
	}
  
    ,updateGuest: function() {
        location.href = '?a='+MODx.request.a+'&action=updateGuest'+'&id='+this.menu.record.id;

    }
    
    ,removeGuest: function() {
        location.href = '?a='+MODx.request.a+'&action=removeGuest'+'&id='+this.menu.record.id;

    }
    ,linkEvent: function() {
        location.href = '?a='+MODx.request.a+'&action=linkEvent'+'&id='+this.menu.record.id;

    }
    
    ,removeEvent: function() {
        location.href = '?a='+MODx.request.a+'&action=removeEvent'+'&id='+this.menu.record.id;

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
Ext.reg('wssm-grid-guest-list',WSSM.grid.GuestList);



