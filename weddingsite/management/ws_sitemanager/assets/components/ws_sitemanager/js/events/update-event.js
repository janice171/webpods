/**
 * Loads the Wedding Site update event page
 * 
 * @class WSSM.page.UpdateEvent
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-event-update
 */
WSSM.page.UpdateEvent = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-event-update'
        ,buttons: [{
            process: 'events/update', text: _('save'), method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            text: _('cancel'), 
            handler: function() {
                location.href='?a='+MODx.request.a+'&action=getEventList&id='+WSSM.saved.User;
            }
        }]
        ,components: [{
            xtype: 'wssm-panel-event-update'
            ,renderTo: 'wssm-panel-user-div'
            ,event: config.event
            ,name: ''
        }]
	});
	WSSM.page.UpdateEvent.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.UpdateEvent,MODx.Component);
Ext.reg('wssm-page-event-update',WSSM.page.UpdateEvent);




