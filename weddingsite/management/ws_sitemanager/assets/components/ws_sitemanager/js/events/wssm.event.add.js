/**
 * @class WSSM.panel.EventAdd
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-event-add
 */
WSSM.panel.EventAdd = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: WSSM.config.connector_url
		,baseParams: {
            action: 'events/add'
		}
        ,id: 'wssm-panel-event-add'
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
            ,items: WSSM.fields.Event(config)
        }]
        
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    WSSM.panel.EventAdd.superclass.constructor.call(this,config);
    
};
Ext.extend(WSSM.panel.EventAdd,MODx.FormPanel,{
    setup: function() {
            this.fireEvent('ready');
            Ext.get('modx-user-header').update('<h2>'+_('wssm_event_add_header')+'</h2>');
            Ext.getCmp('wssm-event-user').setValue(WSSM.saved.User);
            return false;
    }
    
    ,beforeSubmit: function(o) {
        
        Ext.apply(o.form.baseParams);
    }
    
    ,success: function(o) {
        
    }   
        
});
Ext.reg('wssm-panel-event-add',WSSM.panel.EventAdd);
