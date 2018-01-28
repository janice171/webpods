/**
 * @class WSSM.panel.EventUpdate
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-event-update
 */
WSSM.panel.EventUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: WSSM.config.connector_url
		,baseParams: {
            action: 'events/get'
		}
        ,id: 'wssm-panel-event-update'
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
    WSSM.panel.EventUpdate.superclass.constructor.call(this,config);
    
};
Ext.extend(WSSM.panel.EventUpdate,MODx.FormPanel,{
    setup: function() {
        if (this.config.event === '' || this.config.event === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'events/get'
                ,id: this.config.event
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    Ext.get('modx-user-header').update('<h2>'+_('wssm_weddingevent')+': '+r.object.name+ '</h2>');
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
        var eventId = this.config.event;
        
    }   
        
});
Ext.reg('wssm-panel-event-update',WSSM.panel.EventUpdate);




