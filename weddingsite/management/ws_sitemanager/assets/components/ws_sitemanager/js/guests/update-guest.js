/**
 * Loads the Wedding Site update guest page
 * 
 * @class WSSM.page.UpdateGuest
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-guest-update
 */
WSSM.page.UpdateGuest = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-guest-update'
        ,buttons: [{
            process: 'guests/update', text: _('save'), method: 'remote'
            ,checkDirty: true
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
            xtype: 'wssm-panel-guest-update'
            ,renderTo: 'wssm-panel-user-div'
            ,guest: config.guest
            ,name: ''
        }]
	});
	WSSM.page.UpdateGuest.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.UpdateGuest,MODx.Component);
Ext.reg('wssm-page-guest-update',WSSM.page.UpdateGuest);



