/**
 * Loads the panel for managing wedding users gallery pictures
 * 
 * @class WSSM.panel.Gallery
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-gallery
 */
WSSM.panel.Gallery = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'wssm-panel-gallery'
		,title: _('wssm_menu_gallery_tab')
        ,bodyStyle: ''
        ,padding: 10
        ,layout: 'form'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('wssm_gallery')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'wssm-gallery-header'
        },{            
              html: '<p>'+_('wssm_gallery_desc')+'</p>'
              ,border: false
        },{
              xtype: 'wssm-grid-gallery'
                         
        	}]
    });
    WSSM.panel.Gallery.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.Gallery,MODx.FormPanel);
Ext.reg('wssm-panel-gallery',WSSM.panel.Gallery);


