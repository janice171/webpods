/**
 * @class WSSM.panel.GuestUpdate
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-guest-update
 */
WSSM.panel.GuestUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: WSSM.config.connector_url
		,baseParams: {
            action: 'guests/get'
		}
        ,id: 'wssm-panel-guest-update'
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
            ,items: WSSM.fields.Guest(config)
        }]
        
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    WSSM.panel.GuestUpdate.superclass.constructor.call(this,config);
    
};
Ext.extend(WSSM.panel.GuestUpdate,MODx.FormPanel,{
    setup: function() {
        if (this.config.guest === '' || this.config.guest === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'guests/get'
                ,id: this.config.guest
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    Ext.get('modx-user-header').update('<h2>'+_('wssm_weddingguest')+': '+r.object.name+ '  -  ' +_('wssm_guest_guid')+': '+r.object.guid+'</h2>');
                    this.fireEvent('ready',r.object);
                    MODx.fireEvent('ready');
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        
        Ext.apply(o.form.baseParams);
    }
    
    ,success: function(o) {
        var guestId = this.config.guest;
        
    }   
        
});
Ext.reg('wssm-panel-guest-update',WSSM.panel.GuestUpdate);



