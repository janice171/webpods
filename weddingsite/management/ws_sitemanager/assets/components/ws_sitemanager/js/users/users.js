/**
 * Loads the panel for managing wedding users.
 * 
 * @class WSSM.panel.Users
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-users
 */
WSSM.panel.Users = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'wssm-panel-users'
		,title: _('wssm_menu_users_tab')
        ,bodyStyle: ''
        ,padding: 10
        ,layout: 'form'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('wssm_users')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'wssm-users-header'
        },{            
              html: '<p>'+_('wssm_users_desc')+'</p>'
              ,border: false
        },{
              xtype: 'wssm-grid-user'
                         
        	}]
    });
    WSSM.panel.Users.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.Users,MODx.FormPanel);
Ext.reg('wssm-panel-users',WSSM.panel.Users);
