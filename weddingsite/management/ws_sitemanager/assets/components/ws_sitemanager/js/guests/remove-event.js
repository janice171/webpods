/**
 * Loads the Wedding Site remove event page
 * 
 * @class WSSM.page.RemoveEvent
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-event-remove
 */
WSSM.page.RemoveEvent = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-event-remove'
        ,buttons: [{
            process: 'guests/eventsRemove', text: _('save'), method: 'remote'
            ,checkDirty: false
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            text: _('cancel'), 
            handler: function() {
                location.href='?a='+MODx.request.a+'&action=getGuestList&id='+WSSM.saved.User;
            }
        }]
        ,components: [{
            xtype: 'wssm-panel-event-remove'
            ,renderTo: 'wssm-panel-user-div'
            ,guest: config.guest
            ,name: ''
        }]
	});
	WSSM.page.RemoveEvent.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.RemoveEvent,MODx.Component);
Ext.reg('wssm-page-event-remove',WSSM.page.RemoveEvent);



