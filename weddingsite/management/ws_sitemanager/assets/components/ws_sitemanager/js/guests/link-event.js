/**
 * Loads the Wedding Site link event page
 * 
 * @class WSSM.page.LinkEvent
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-event-link
 */
WSSM.page.LinkEvent = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-event-link'
        ,buttons: [{
            process: 'guests/eventsUpdate', text: _('save'), method: 'remote'
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
            xtype: 'wssm-panel-event-link'
            ,renderTo: 'wssm-panel-user-div'
            ,guest: config.guest
            ,name: ''
        }]
	});
	WSSM.page.LinkEvent.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.LinkEvent,MODx.Component);
Ext.reg('wssm-page-event-link',WSSM.page.LinkEvent);





