/**
 * @class WSSM.panel.AlbumAdd
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-album-add
 */
WSSM.panel.AlbumAdd = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: WSSM.config.connector_url
		,baseParams: {
            action: 'gallery/albums/add'
		}
        ,id: 'wssm-panel-album-add'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,bodyStyle: ''
        ,items: [{
             html: '<h2>'+_('user_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-user-header'
        },{
            xtype: 'modx-tabs'
            ,id: 'modx-user-tabs'
            ,deferredRender: false
            ,border: true
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,labelWidth: 150
                ,bodyStyle: 'padding: 15px;'
            }
            ,items: WSSM.fields.Album(config)
        }]
        
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    WSSM.panel.AlbumAdd.superclass.constructor.call(this,config);
    
};
Ext.extend(WSSM.panel.AlbumAdd,MODx.FormPanel,{
    setup: function() {
            this.fireEvent('ready');
            Ext.get('modx-user-header').update('<h2>'+_('wssm_album_add_header')+'</h2>');
            Ext.getCmp('wssm-album-user').setValue(WSSM.saved.User);
            return false;
    }
    
    ,beforeSubmit: function(o) {
        
        Ext.apply(o.form.baseParams);
    }
    
    ,success: function(o) {
        
    }   
        
});
Ext.reg('wssm-panel-album-add',WSSM.panel.AlbumAdd);


