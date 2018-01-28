/**
 * Loads the panel for managing wedding users guests.
 * 
 * @class WSSM.panel.GuestsList
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-guests-list
 */
WSSM.panel.GuestsList = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'wssm-panel-guests-list'
		,title: _('wssm_menu_guests_list_tab')
        ,bodyStyle: ''
        ,padding: 10
        ,layout: 'form'
        ,renderTo:'wssm-panel-user-div'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('wssm_guests_list')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'wssm-guests-header'
        },{            
              html: '<p>'+_('wssm_guests_list_desc')+'</p>'
              ,border: false
        },{
              xtype: 'wssm-grid-guest-list'
                         
        	}]
    });
    WSSM.panel.GuestsList.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.GuestsList,MODx.FormPanel);
Ext.reg('wssm-panel-guests-list',WSSM.panel.GuestsList);

