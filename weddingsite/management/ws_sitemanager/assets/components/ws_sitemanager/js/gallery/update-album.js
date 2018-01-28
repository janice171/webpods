/**
 * Loads the Wedding Site update album page
 * 
 * @class WSSM.page.UpdateAlbum
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype wssm-page-album-update
 */
WSSM.page.UpdateAlbum = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'wssm-panel-album-update'
        ,buttons: [{
            process: 'gallery/albums/update', text: _('save'), method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            text: _('cancel'), 
            handler: function() {
                location.href='?a='+MODx.request.a+'&action=getAlbumList&id='+WSSM.saved.User;
            }
        }]
        ,components: [{
            xtype: 'wssm-panel-album-update'
            ,renderTo: 'wssm-panel-user-div'
            ,album: config.album
            ,name: ''
        }]
	});
	WSSM.page.UpdateAlbum.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.page.UpdateAlbum,MODx.Component);
Ext.reg('wssm-page-album-update',WSSM.page.UpdateAlbum);






