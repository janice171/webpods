/**
 * Loads a grid of MODx Wedding users albums.
 * 
 * @class WSSM.grid.AlbumList
 * @extends MODx.grid.Grid
 * @param {Object} config An object of config properties
 * @xtype wssm-grid-album-list
 */
WSSM.grid.AlbumList = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		url: WSSM.config.connector_url
		,baseParams: {
            action: 'gallery/albums/getlist'
            ,id: this.getId()
		}
		,preventRender: true
        ,autoWidth: true
		,fields: ['id','albumName','albumDescription','active']
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
	WSSM.grid.AlbumList.superclass.constructor.call(this,config);
};

Ext.extend(WSSM.grid.AlbumList,MODx.grid.Grid,{
	
    getId: function() {
        var params = MODx.getURLParameters();
        return params.id;
    },
    
    getMenu: function() {
        var m = [];
        m.push({
            text: _('wssm_album_update')
                    ,handler: this.updateAlbum
                });    
                
         m.push({
            text: _('wssm_album_remove')
                    ,handler: this.removeAlbum
                });    
        
         m.push({
            text: _('wssm_album_items')
                    ,handler: this.itemsAlbum
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
             header: _('wssm_album_name')
            ,dataIndex: 'albumName'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
                }
                
            },{
             header: _('wssm_album_description')
            ,dataIndex: 'albumDescription'
            ,sortable: true
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                metaData.attr = 'style="color:black;"';
                return value;
                }
		
	    },{
	     header: _('wssm_album_active')
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
  
    ,updateAlbum: function() {
        location.href = '?a='+MODx.request.a+'&action=updateAlbum'+'&id='+this.menu.record.id;

    }
    
    ,removeAlbum: function() {
        location.href = '?a='+MODx.request.a+'&action=removeAlbum'+'&id='+this.menu.record.id;

    }
    
    ,itemsAlbum: function() {
        location.href = '?a='+MODx.request.a+'&action=itemsAlbum'+'&id='+this.menu.record.id;

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
Ext.reg('wssm-grid-album-list',WSSM.grid.AlbumList);






