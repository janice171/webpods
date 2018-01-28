/**
 * Loads the Wedding Site add guest page
 * 
 * @class WSSM.page.AddGuest
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-guest-add
 */
WSSM.page.AddGuest = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-guest-add'
        ,buttons: [{
            process: 'guests/add', text: _('save'), method: 'remote'
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
            xtype: 'wssm-panel-guest-add'
            ,renderTo: 'wssm-panel-user-div'
            ,guest: config.guest
            ,name: ''
        }]
	});
	WSSM.page.AddGuest.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.AddGuest,MODx.Component);
Ext.reg('wssm-page-guest-add',WSSM.page.AddGuest);





