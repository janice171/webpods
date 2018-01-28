/**
 * Loads the panel for managing wedding users albums.
 * 
 * @class WSSM.panel.AlbumsList
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-albums-list
 */
WSSM.panel.AlbumsList = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'wssm-panel-albums-list'
		,title: _('wssm_menu_albums_list_tab')
        ,bodyStyle: ''
        ,padding: 10
        ,layout: 'form'
        ,renderTo:'wssm-panel-user-div'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('wssm_albums_list')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'wssm-albums-header'
        },{            
              html: '<p>'+_('wssm_albums_list_desc')+'</p>'
              ,border: false
        },{
              xtype: 'wssm-grid-album-list'
                         
        	}]
    });
    WSSM.panel.AlbumsList.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.AlbumsList,MODx.FormPanel);
Ext.reg('wssm-panel-albums-list',WSSM.panel.AlbumsList);



