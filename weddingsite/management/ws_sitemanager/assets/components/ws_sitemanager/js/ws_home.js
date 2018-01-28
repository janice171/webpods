/**
 * The Wedding Site home page
 *
 * @class WSSM
 * @extends Ext.Component
 * @param {Object} config An object of config properties
 * @xtype ws_sitemanager
 */

Ext.onReady(function() {
    MODx.load({
        xtype: 'wssm-page-home'
    });
});

WSSM.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		html: '<h2>'+_('ws_sitemanager')+'</h2>'
		,cls: 'modx-page-header'
		,renderTo: 'wssm-panel-header-div'
        ,components: [{
            xtype: 'wssm-panel-home'
            ,renderTo: 'wssm-panel-home-div'
        }]
    });
    WSSM.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.Home,MODx.Component);
Ext.reg('wssm-page-home',WSSM.page.Home);


WSSM.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'wssm-panel-home'
        ,border: false
        ,defaults: { autoHeight: true}
        ,items: [{
                xtype: 'wssm-panel-users'
            },{
                xtype: 'wssm-panel-guests'
            },{
                xtype: 'wssm-panel-events'
            },{
                xtype: 'wssm-panel-gallery'
            }]
    });
    WSSM.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.panel.Home,MODx.Tabs);
Ext.reg('wssm-panel-home',WSSM.panel.Home);



