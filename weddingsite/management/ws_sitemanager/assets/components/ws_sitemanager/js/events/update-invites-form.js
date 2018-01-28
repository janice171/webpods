/**
 * Loads the Wedding Site update invite page
 * 
 * @class WSSM.page.UpdateInvite
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-invite-update
 */
WSSM.page.UpdateInvite = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-invite-update'
        ,buttons: [{
            process: 'events/updateinvite', text: _('save'), method: 'remote'
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
            xtype: 'wssm-panel-invite-update'
            ,renderTo: 'wssm-panel-user-div'
            ,event: config.event
            ,invite: config.invite
            ,name: ''
        }]
	});
	WSSM.page.UpdateInvite.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.UpdateInvite,MODx.Component);
Ext.reg('wssm-page-invite-update',WSSM.page.UpdateInvite);






