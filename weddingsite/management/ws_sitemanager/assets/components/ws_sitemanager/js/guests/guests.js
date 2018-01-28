/**
 * Loads the panel for managing wedding users guests.
 * 
 * @class WSSM.panel.Guests
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-guests
 */
WSSM.panel.Guests = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'wssm-panel-guests'
		,title: _('wssm_menu_guests_tab')
        ,bodyStyle: ''
        ,padding: 10
        ,layout: 'form'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('wssm_guests')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'wssm-guests-header'
        },{            
              html: '<p>'+_('wssm_guests_desc')+'</p>'
              ,border: false
        },{
              xtype: 'wssm-grid-guest'
                         
        	}]
    });
    WSSM.panel.Guests.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.Guests,MODx.FormPanel);
Ext.reg('wssm-panel-guests',WSSM.panel.Guests);



