/**
 * Loads the panel for managing wedding users events
 * 
 * @class WSSM.panel.Events
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-events
 */
WSSM.panel.Events = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'wssm-panel-events'
		,title: _('wssm_menu_events_tab')
        ,bodyStyle: ''
        ,padding: 10
        ,layout: 'form'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('wssm_events')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'wssm-events-header'
        },{            
              html: '<p>'+_('wssm_events_desc')+'</p>'
              ,border: false
        },{
              xtype: 'wssm-grid-event'
                         
        	}]
    });
    WSSM.panel.Events.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.Events,MODx.FormPanel);
Ext.reg('wssm-panel-events',WSSM.panel.Events);



