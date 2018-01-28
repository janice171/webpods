/**
 * @class WSSM.panel.User
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype wssm-panel-user
 */
WSSM.panel.User = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: WSSM.config.connector_url
		,baseParams: {
            action: 'users/get'
		}
        ,id: 'wssm-panel-user'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,bodyStyle: ''
        ,items: [{
             html: '<h2>'+_('user_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-user-header'
        },{
            xtype: 'modx-tabs'
            ,id: 'modx-user-tabs'
            ,deferredRender: false
            ,border: true
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,labelWidth: 150
                ,bodyStyle: 'padding: 15px;'
            }
            ,items: this.getFields(config)
        }]
        
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    WSSM.panel.User.superclass.constructor.call(this,config);
    
};
Ext.extend(WSSM.panel.User,MODx.FormPanel,{
    setup: function() {
        if (this.config.user === '' || this.config.user === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'users/get'
                ,id: this.config.user
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    Ext.get('modx-user-header').update('<h2>'+_('wssm_weddinguser')+': '+r.object.firstName+ '  ' + r.object.lastName + '</h2>');
                    this.fireEvent('ready',r.object);
                    MODx.fireEvent('ready');
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        
        Ext.apply(o.form.baseParams);
    }
    
    ,success: function(o) {
        var userId = this.config.user;
        
    }
    
    ,getFields: function(config) {
        
        return [{
            title: _('wssm_general_information')
            ,defaults: { msgTarget: 'side' ,autoHeight: true }
            ,items: [{
                id: 'wssm-user-id'
                ,name: 'user'
                ,xtype: 'hidden'
            },{
            id: 'modx-user-fs-general'
            ,title: _('wssm_general_information')
            ,xtype: 'fieldset'
            ,items: [{
                id: 'wssm-user-userlastname'
                ,name: 'lastName'
                ,fieldLabel: _('wssm_user_lastname')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-user-userfirstname'
                ,name: 'firstName'
                ,fieldLabel: _('wssm_user_firstname')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'modx-user-email'
                ,name: 'email'
                ,fieldLabel: _('wssm_user_email')
                ,xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                ,allowBlank: false
            },{
                id: 'wssm-user-partner1'
                ,name: 'partnerName1'
                ,fieldLabel: _('wssm_partner1name')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-user-partner2'
                ,name: 'partnerName2'
                ,fieldLabel: _('wssm_partner2name')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-user-weddingdate'
                ,name: 'date'
                ,fieldLabel: _('wssm_weddingdate')
                ,xtype: 'xdatetime'
                ,dateFormat: 'd-m-y'
                ,timeFormat: MODx.config.manager_time_format
                ,dateWidth: 150
                ,timeWidth: 110
                ,hiddenFormat: 'd-m-Y H:i:s'
            },{
                id: 'wssm-user-registrationdate'
                ,name: 'registrationDate'
                ,fieldLabel: _('wssm_registrationdate')
                ,xtype: 'xdatetime'
                ,dateFormat: 'd-m-y'
                ,timeFormat: MODx.config.manager_time_format
                ,dateWidth: 150
                ,timeWidth: 110
                ,hiddenFormat: 'd-m-Y H:i:s'
            },{
                id: 'wssm-user-website'
                ,name: 'website'
                ,fieldLabel: _('wssm_website')
                ,xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
            },{
                xtype: 'wssm-combo-designs'
                ,fieldLabel: _('wssm_theme')
                ,name: 'theme'
                ,hiddenName: 'theme'
                ,id: 'wssm-user-design'
                ,editable: false
                ,width: 300
                ,baseParams: {
                    action: 'users/designs'
                    ,userId : WSSM.saved.User
                    ,combo: '1'
                    ,limit: 0
                }
            },{
                id: 'wssm-user-hearabout'
                ,name: 'hearAbout'
                ,fieldLabel: _('wssm_hearabout')
                ,xtype: 'textarea'
                ,width: 300
                ,grow: true
            },{
                xtype: 'wssm-combo-package'
                ,fieldLabel: _('wssm_package')
                ,name: 'packageType'
                ,hiddenName: 'packageType'
                ,id: 'wssm-user-package'
                ,editable: false
                ,width: 300
                ,baseParams: {
                    action: 'users/package'
                    ,userId : WSSM.saved.User
                    ,combo: '1'
                    ,limit: 0
                }
            },{
                id: 'wssm-user-passwordprotect'
                ,name: 'passwordProtect'
                ,fieldLabel: _('wssm_passwordprotect')
                ,xtype: 'checkbox' 
                ,inputValue: 1
                ,checked: false
            },{
                id: 'wssm-user-sitepassword'
                ,name: 'websitePassword'
                ,fieldLabel: _('wssm_websitePassword')
                ,xtype: 'textfield'
                ,width: 300
                ,grow: false
            },{
                id: 'wssm-user-websitedetails'
                ,name: 'websiteDetails'
                ,fieldLabel: _('wssm_websitedetails')
                ,xtype: 'checkbox' 
                ,inputValue: 1
                ,checked: false
            },{
                id: 'wssm-user-personaldetails'
                ,name: 'personalDetails'
                ,fieldLabel: _('wssm_personaldetails')
                ,xtype: 'checkbox' 
                ,inputValue: 1
                ,checked: false
            },{
                id: 'wssm-user-siteactive'
                ,name: 'websiteActive'
                ,fieldLabel: _('wssm_websiteactive')
                ,xtype: 'checkbox'
                ,inputValue: 1
                ,checked: false
            },{
                id: 'wssm-user-moderateguestbook'
                ,name: 'moderateGuestbook'
                ,fieldLabel: _('wssm_moderateguestbook')
                ,xtype: 'checkbox'
                ,inputValue: 1
                ,checked: false
            },{
                id: 'wssm-user-websitesearchable'
                ,name: 'websiteSearchable'
                ,fieldLabel: _('wssm_websiteserachable')
                ,xtype: 'checkbox'
                ,inputValue: 1
                ,checked: false
            },{
                id: 'wssm-user-displaycountdown'
                ,name: 'displayCountdown'
                ,fieldLabel: _('wssm_displaycountdown')
                ,xtype: 'checkbox'
                ,inputValue: 1
                ,checked: false
            },{
                id: 'wssm-user-domain'
                ,name: 'domain'
                ,fieldLabel: _('wssm_domain')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-user-social-facebook'
                ,name: 'socialFacebook'
                ,fieldLabel: _('wssm_socialfacebook')
                ,xtype: 'textarea'
                ,width: 300
            },{
                id: 'wssm-user-social-google'
                ,name: 'socialGoogle'
                ,fieldLabel: _('wssm_socialgoogle')
                ,xtype: 'textarea'
                ,width: 300
            },{
                id: 'wssm-user-social-twitter'
                ,name: 'socialTwitter'
                ,fieldLabel: _('wssm_socialtwitter')
                ,xtype: 'textarea'
                ,width: 300
            },{
                id: 'wssm-user-social-other1'
                ,name: 'socialOther1'
                ,fieldLabel: _('wssm_socialother1')
                ,xtype: 'textarea'
                ,width: 300
            },{
                id: 'wssm-user-social-other2'
                ,name: 'socialOther2'
                ,fieldLabel: _('wssm_socialother2')
                ,xtype: 'textarea'
                ,width: 300
            },{
                html: MODx.onUserFormRender
                ,border: false
            }]
         }]
       }];
    }
});
Ext.reg('wssm-panel-user',WSSM.panel.User);


