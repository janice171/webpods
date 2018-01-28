Ext.namespace('WSSM');
/**
 * The base Wedding Site Manager class
 *
 * @class WSSM
 * @extends Ext.Component
 * @param {Object} config An object of config properties
 * @xtype ws_sitemanager
 */
WSSM = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        base_url: MODx.config.assets_url+'components/ws_sitemanager/'
        ,connector_url: MODx.config.assets_url+'components/ws_sitemanager/connector.php'
    });
    WSSM.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(WSSM,Ext.Component,{
    config: {}
    ,panel: {} ,page: {} ,grid: {}, tree: {}, fields: {}, saved: {}, combo: {}
});
Ext.reg('ws_sitemanager',WSSM);
WSSM = new WSSM();

/* Add the Guest page fields */
WSSM.fields.Guest = function(config) {
return [{
            title: _('wssm_guest_information')
            ,defaults: { msgTarget: 'side' ,autoHeight: true }
            ,items: [{
                id: 'wssm-guest-id'
                ,name: 'id'
                ,xtype: 'hidden'
            },{
                id: 'wssm-guest-user'
                ,name: 'user'
                ,xtype: 'hidden'
            },{
            id: 'modx-user-fs-general'
            ,title: _('wssm_guest_information')
            ,xtype: 'fieldset'
            ,items: [{
                id: 'wssm-guest-name'
                ,name: 'name'
                ,fieldLabel: _('wssm_guest_name')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-guest-email'
                ,name: 'email'
                ,fieldLabel: _('wssm_guest_email')
                ,xtype: 'textfield'
                ,width: 300
                ,allowBlank: false
            },{
                id: 'modx-guest-address1'
                ,name: 'address1'
                ,fieldLabel: _('wssm_guest_address1')
                ,xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                
            },{
                id: 'modx-guest-address2'
                ,name: 'address2'
                ,fieldLabel: _('wssm_guest_address2')
                ,xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                
            },{
                id: 'wssm-guest-city'
                ,name: 'city'
                ,fieldLabel: _('wssm_guest_city')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-guest-postcode'
                ,name: 'postCode'
                ,fieldLabel: _('wssm_guest_postcode')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-guest-telephone'
                ,name: 'telephone'
                ,fieldLabel: _('wssm_guest_telephone')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-guest-guestof'
                ,name: 'guestOf'
                ,fieldLabel: _('wssm_guest_guestof')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-guest-active'
                ,name: 'active'
                ,fieldLabel: _('wssm_guest_active')
                ,xtype: 'checkbox' 
                ,inputValue: 1
                ,checked: false    
            },{
                id: 'wssm-guest-party'
                ,name: 'party'
                ,fieldLabel: _('wssm_guest_party')
                ,xtype: 'textfield' 
                ,width: 300 
            },{
                id: 'wssm-guest-notes'
                ,name: 'notes'
                ,fieldLabel: _('wssm_guest_notes')
                ,xtype: 'textarea' 
                ,width: 300 
            },{
                html: MODx.onUserFormRender
                ,border: false
            }]
         }]
       }];
    }    
    
/* Add the Event page fields */
WSSM.fields.Event = function(config) {
return [{
            title: _('wssm_event_information')
            ,defaults: { msgTarget: 'side' ,autoHeight: true }
            ,items: [{
                id: 'wssm-event-id'
                ,name: 'id'
                ,xtype: 'hidden'
            },{
                id: 'wssm-event-user'
                ,name: 'user'
                ,xtype: 'hidden'
            },{
            id: 'modx-user-fs-general'
            ,title: _('wssm_event_information')
            ,xtype: 'fieldset'
            ,items: [{
                id: 'wssm-event-name'
                ,name: 'name'
                ,fieldLabel: _('wssm_event_name')
                ,xtype: 'textfield'
                ,width: 300
            },{
                id: 'wssm-event-date'
                ,name: 'date'
                ,fieldLabel: _('wssm_event_date')
                ,xtype: 'xdatetime'
                ,dateFormat: 'd-m-y'
                ,timeFormat: MODx.config.manager_time_format
                ,dateWidth: 150
                ,timeWidth: 110
                ,hiddenFormat: 'd-m-Y H:i:s'
            },{
                id: 'modx-event-location'
                ,name: 'location'
                ,fieldLabel: _('wssm_event_location')
                ,xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                
            },{
                id: 'modx-event-address2'
                ,name: 'address2'
                ,fieldLabel: _('wssm_event_address2')
                ,xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                
            },{
                id: 'modx-event-address3'
                ,name: 'address3'
                ,fieldLabel: _('wssm_event_address3')
                ,xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                
            },{
                id: 'modx-event-address4'
                ,name: 'address4'
                ,fieldLabel: _('wssm_event_address4')
                ,xtype: 'textfield'
                ,width: 300
                ,maxLength: 255
                
            },{
                id: 'wssm-event-starttime'
                ,name: 'startTime'
                ,fieldLabel: _('wssm_event_starttime')
                ,xtype: 'textfield'
                ,width: 100
                ,maxLength: 30
            },{
                id: 'wssm-event-endtime'
                ,name: 'endTime'
                ,fieldLabel: _('wssm_event_endtime')
                ,xtype: 'textfield'
                ,width: 100
                ,maxLength: 30
            },{
                id: 'wssm-event-maxguests'
                ,name: 'maxGuests'
                ,fieldLabel: _('wssm_event_maxguests')
                ,xtype: 'textfield'
                ,width: 100
            },{
                id: 'wssm-event-totalguests'
                ,name: 'totalGuests'
                ,fieldLabel: _('wssm_event_totalguests')
                ,xtype: 'textfield'
                ,width: 100
            },{
                id: 'wssm-event-notes'
                ,name: 'notes'
                ,fieldLabel: _('wssm_event_notes')
                ,xtype: 'textarea' 
                ,width: 300 
            },{
                id: 'wssm-event-active'
                ,name: 'active'
                ,fieldLabel: _('wssm_event_active')
                ,xtype: 'checkbox' 
                ,inputValue: 1
                ,checked: false    
            },{
                html: MODx.onUserFormRender
                ,border: false
            }]
         }]
       }];
    }

/* Add the Invite page fields */
WSSM.fields.Invite = function(config) {
return [{
            title: _('wssm_invite_information')
            ,defaults: { msgTarget: 'side' ,autoHeight: true }
            ,items: [{
                id: 'wssm-invite-id'
                ,name: 'id'
                ,xtype: 'hidden'
            },{
                id: 'wssm-invite-user'
                ,name: 'user'
                ,xtype: 'hidden'
            },{
            id: 'modx-user-fs-general'
            ,title: _('wssm_invite_information')
            ,xtype: 'fieldset'
            ,items: [{
                id: 'wssm-invite-willattend'
                ,name: 'willAttend'
                ,fieldLabel: _('wssm_invite_willattend')
                ,xtype: 'checkbox' 
                ,inputValue: 1
                ,checked: false    
            },{
                id: 'wssm-invite-invitesent'
                ,name: 'InviteSent'
                ,fieldLabel: _('wssm_invite_invitesent')
                ,xtype: 'checkbox' 
                ,inputValue: 1
                ,checked: false    
            },{
                id: 'wssm-invite-RSVP-date'
                ,name: 'RSVPDate'
                ,fieldLabel: _('wssm_invite_rsvp_date')
                ,xtype: 'xdatetime'
                ,dateFormat: 'd-m-y'
                ,timeFormat: MODx.config.manager_time_format
                ,dateWidth: 150
                ,timeWidth: 110
                ,hiddenFormat: 'd-m-Y H:i:s'
            },{
                id: 'wssm-invite-lastreminder-date'
                ,name: 'lastReminderDate'
                ,fieldLabel: _('wssm_invite_lastreminder_date')
                ,xtype: 'xdatetime'
                ,dateFormat: 'd-m-y'
                ,timeFormat: MODx.config.manager_time_format
                ,dateWidth: 150
                ,timeWidth: 110
                ,hiddenFormat: 'd-m-Y H:i:s'
            },{
            id: 'wssm-rsvp-method-fieldset'
            ,title: _('wssm_rsvp_method')
            ,xtype: 'fieldset'
            ,width: 780
            ,items: [{
                xtype: 'radiogroup'
                ,labelSeparator: ''
                ,width: 250
                ,items: [{
                    name: 'rsvpMethod'
                    ,id: 'wssm-invite-online'
                    ,xtype: 'radio'
                    ,boxLabel: _('wssm_invite_online')
                    ,inputValue: 'RSVPdOnline'
                },{
                    name: 'rsvpMethod'
                    ,id: 'wssm-invite-manual'
                    ,xtype: 'radio'
                    ,boxLabel: _('wssm_invite_manual')
                    ,inputValue: 'RSVPdManual'
                },{
                    name: 'rsvpMethod'
                    ,id: 'wssm-invite-none'
                    ,xtype: 'radio'
                    ,boxLabel: _('wssm_invite_none')
                    ,inputValue: 'RSVPdNone'
                }]
            }]
        
            },{
                html: MODx.onUserFormRender
                ,border: false
            }]
         }]
       }];
    } 
/* Add the album page fields */
WSSM.fields.Album = function(config) {
return [{
            title: _('wssm_album_information')
            ,defaults: { msgTarget: 'side' ,autoHeight: true }
            ,items: [{
                id: 'wssm-album-id'
                ,name: 'id'
                ,xtype: 'hidden'
            },{
                id: 'wssm-album-user'
                ,name: 'user'
                ,xtype: 'hidden'
            },{
                id: 'modx-user-fs-general'
            ,title: _('wssm_album_information')
            ,xtype: 'fieldset'
            ,items: [{
                id: 'wssm-album-name'
                ,name: 'albumName'
                ,fieldLabel: _('wssm_album_name')
                ,xtype: 'textfield'
                ,width: 100
                ,maxLength: 30 
            },{
                id: 'wssm-album-description'
                ,name: 'albumDescription'
                ,fieldLabel: _('wssm_album_description')
                ,xtype: 'textfield'
                ,width: 500
                ,maxLength: 480 
            },{
                 id: 'wssm-album-active'
                ,name: 'active'
                ,fieldLabel: _('wssm_album_active')
                ,xtype: 'checkbox' 
                ,inputValue: 1
                ,checked: false    
            },{
                html: MODx.onUserFormRender
                ,border: false
            }]
         }]
       }];
    } 
    
WSSM.combo.Events = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'events'
        ,hiddenName: 'events'
        ,displayField: 'name'
        ,valueField: 'name'
        ,fields: ['name']
        ,pageSize: 20
        ,url: WSSM.config.connector_url
    });
    WSSM.combo.Events.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.combo.Events,MODx.combo.ComboBox);
Ext.reg('wssm-combo-events',WSSM.combo.Events);

WSSM.combo.Designs = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'theme'
        ,hiddenName: 'theme'
        ,displayField: 'name'
        ,valueField: 'name'
        ,fields: ['name']
        ,pageSize: 20
        ,url: WSSM.config.connector_url
    });
    WSSM.combo.Designs.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.combo.Designs,MODx.combo.ComboBox);
Ext.reg('wssm-combo-designs',WSSM.combo.Designs);

WSSM.combo.Package = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'packageType'
        ,hiddenName: 'packageType'
        ,displayField: 'type'
        ,valueField: 'type'
        ,fields: ['type']
        ,pageSize: 20
        ,url: WSSM.config.connector_url
    });
    WSSM.combo.Package.superclass.constructor.call(this,config);
};
Ext.extend(WSSM.combo.Package,MODx.combo.ComboBox);
Ext.reg('wssm-combo-package',WSSM.combo.Package);