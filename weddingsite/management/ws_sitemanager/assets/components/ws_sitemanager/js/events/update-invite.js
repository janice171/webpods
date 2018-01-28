/**
 * Loads the panel for managing wedding users invites.
 * 
 * @class WSSM.panel.InvitesList
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-events-list
 */
WSSM.panel.InvitesList = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'wssm-panel-events-invite-list'
		,title: _('wssm_menu_events_invite_list_tab')
        ,bodyStyle: ''
        ,padding: 10
        ,layout: 'form'
        ,renderTo:'wssm-panel-user-div'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('wssm_events_invites_list')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'wssm-events-invites-header'
        },{            
              html: '<p>'+_('wssm_events_invites_list_desc')+'</p>'
              ,border: false
        },{
              xtype: 'wssm-grid-event-invite-list'
                         
        	}]
    });
    WSSM.panel.InvitesList.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.InvitesList,MODx.FormPanel);
Ext.reg('wssm-panel-events-invites-list',WSSM.panel.InvitesList);




