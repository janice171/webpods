/**
 * Loads the panel for managing wedding users events.
 * 
 * @class WSSM.panel.EventsList
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-events-list
 */
WSSM.panel.EventsList = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'wssm-panel-events-list'
		,title: _('wssm_menu_events_list_tab')
        ,bodyStyle: ''
        ,padding: 10
        ,layout: 'form'
        ,renderTo:'wssm-panel-user-div'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('wssm_events_list')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'wssm-events-header'
        },{            
              html: '<p>'+_('wssm_events_list_desc')+'</p>'
              ,border: false
        },{
              xtype: 'wssm-grid-event-list'
                         
        	}]
    });
    WSSM.panel.EventsList.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.EventsList,MODx.FormPanel);
Ext.reg('wssm-panel-events-list',WSSM.panel.EventsList);



