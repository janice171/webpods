/**
 * @class WSSM.panel.EventREmove
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-event-remove
 */

WSSM.panel.EventRemove = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: WSSM.config.connector_url
		,baseParams: {
            action: 'guests/eventsremove'
		}
        ,id: 'wssm-panel-event-remove'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,bodyStyle: ''
        ,items: [{
             html: '<h2>'+_('wssm_link_events')+'</h2>'
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
        ,items: [{
                title: _('wssm_event_link_information-remove')
                ,defaults: { msgTarget: 'side' ,autoHeight: true }
                ,items: [{
                    id: 'wssm-guest-id'
                    ,name: 'id'
                    ,xtype: 'hidden'
                },{
                    id: 'wssm-guest-user'
                    ,name: 'user'
                    ,xtype: 'hidden'
                },{
                    xtype: 'wssm-combo-events'
                    ,fieldLabel: _('wssm_link_events')
                    ,name: 'wssm-link-event'
                    ,hiddenName: 'wssm-link-event'
                    ,id: 'wssm-link-event'
                    ,value: 'none'
                    ,baseParams: {
                        action: 'guests/events'
                        ,id : WSSM.saved.Guest
                        ,userId : WSSM.saved.User
                        ,type : "remove"
                        
                    }
            
                }]
            }]
        }]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    WSSM.panel.EventRemove.superclass.constructor.call(this,config);
    
};
Ext.extend(WSSM.panel.EventRemove,MODx.FormPanel,{
    setup: function() {
        WSSM.config.guestId = this.config.guest;
        Ext.getCmp('wssm-guest-user').setValue(WSSM.saved.User);
        Ext.getCmp('wssm-guest-id').setValue(WSSM.saved.Guest);
        this.fireEvent('ready');
        return false;
    }
    ,beforeSubmit: function(o) {
        
        Ext.apply(o.form.baseParams);
    }
    
    ,success: function(o) {
        var guestId = this.config.guest;
        
    }   
        
});
Ext.reg('wssm-panel-event-remove',WSSM.panel.EventRemove);







