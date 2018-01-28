/**
 * Loads the Wedding Site update user page
 * 
 * @class WSSM.page.UpdateUser
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-user-update
 */
WSSM.page.UpdateUser = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-user'
        ,buttons: [{
            process: 'users/update', text: _('save'), method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel', text: _('cancel'), params: {a:MODx.request.a}
        }]
        ,components: [{
            xtype: 'wssm-panel-user'
            ,renderTo: 'wssm-panel-user-div'
            ,user: config.user
            ,name: ''
        }]
	});
	WSSM.page.UpdateUser.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.UpdateUser,MODx.Component);
Ext.reg('wssm-page-user-update',WSSM.page.UpdateUser);

