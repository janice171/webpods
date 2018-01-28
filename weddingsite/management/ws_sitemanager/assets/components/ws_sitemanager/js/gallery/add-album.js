/**
 * Loads the Wedding Site add album page
 * 
 * @class WSSM.page.AddAlbum
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-album-add
 */
WSSM.page.AddAlbum = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-album-add'
        ,buttons: [{
            process: 'gallery/albums/add', text: _('save'), method: 'remote'
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
            xtype: 'wssm-panel-album-add'
            ,renderTo: 'wssm-panel-user-div'
            ,album: config.album
            ,name: ''
        }]
	});
	WSSM.page.AddAlbum.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.AddAlbum,MODx.Component);
Ext.reg('wssm-page-album-add',WSSM.page.AddAlbum);








