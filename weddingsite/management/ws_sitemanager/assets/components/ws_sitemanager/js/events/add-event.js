/**
 * Loads the Wedding Site add event page
 * 
 * @class WSSM.page.AddEvent
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-event-add
 */
WSSM.page.AddEvent = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-event-add'
        ,buttons: [{
            process: 'events/add', text: _('save'), method: 'remote'
            ,checkDirty: false
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            text: _('cancel'), 
            handler: function() {
                 location.href='?a='+MODx.request.a;
            }
        }]
        ,components: [{
            xtype: 'wssm-panel-event-add'
            ,renderTo: 'wssm-panel-user-div'
            ,event: config.event
            ,name: ''
        }]
	});
	WSSM.page.AddEvent.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.AddEvent,MODx.Component);
Ext.reg('wssm-page-event-add',WSSM.page.AddEvent);






