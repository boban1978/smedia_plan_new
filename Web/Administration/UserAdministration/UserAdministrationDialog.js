Ext.define('Mediaplan.administration.userAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.useradministrationdialog',

	title: Lang.Administration_userAdministration_dialog_Title,
        id:'userAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'user',
	modal: true,
	width: 650,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnUserID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };
            var window = this;
            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 5,
                    id: 'userAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnUserID',
                            name: 'korisnikID',
                            value: '-1'
                    },{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_userAdministration_dialog_fldSet_GeneralData,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_userAdministration_dialog_Name,
                                      name:'ime',
                                      allowBlank:false,
                                      width:300
                                      
                                },{
                                      xtype:'textfield',
                                      labelAlign:'right',
                                      fieldLabel:Lang.Administration_userAdministration_dialog_Surname,
                                      name:'prezime',
                                      allowBlank:false,
                                      width:300
                                }]
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'textfield',
                                        fieldLabel:Lang.Administration_userAdministration_dialog_Email,
                                        name:'email',
                                        width:300
                                    },{
                                      xtype:'textfield',
                                      labelAlign:'right',
                                      fieldLabel:Lang.Administration_userAdministration_dialog_Mobile,
                                      name:'mobilniTelefon',
                                      width:200
                                }]
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'textfield',
                                        fieldLabel:Lang.Administration_userAdministration_dialog_Address,
                                        name:'adresa',
                                        width:300
                                    },{
                                      xtype:'textfield',
                                      labelAlign:'right',
                                      fieldLabel:Lang.Administration_userAdministration_dialog_Phone,
                                      name:'fiksniTelefon',
                                      width:200
                                }]
                            }]
                    },{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_userAdministration_dialog_fldSet_UserData,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'textfield',
                                        fieldLabel:Lang.Administration_userAdministration_dialog_Username,
                                        name:'username',
                                        allowBlank:false,
                                        width:300
                                },{
                                        xtype:'textfield',
                                        id:'passField',
                                        labelAlign:'right',
                                        inputType: 'password',
                                        fieldLabel:Lang.Administration_userAdministration_dialog_Password,
                                        name:'password',
                                        allowBlank:true,
                                        width:300
                                }]
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                    xtype:'radiogroup',
                                    fieldLabel:Lang.Administration_userAdministration_dialog_Active,
                                    columns: 2,
                                    width:300,
                                    vertical: true,
                                    id: 'userAdministraionDialogActive',
                                    items:[{
                                            boxLabel: Lang.Common_Yes,
                                            name: 'aktivan',
                                            checked:true,
                                            inputValue: 'true'
                                    },{
                                            boxLabel: Lang.Common_No,
                                            name: 'aktivan',
                                            inputValue: 'false'                                            
                                    }]    
                                },{
                                    xtype:'textfield',
                                    id:'passRetype',
                                    inputType: 'password',
                                    labelAlign:'right',
                                    fieldLabel:Lang.Administration_userAdministration_dialog_PasswordRetype,
                                    name:'passwordPonovo',
                                    allowBlank:true,
                                    width:300
                                }]
                            }]
                    },{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_userAdministration_dialog_fldSet_UserType,
                            items:[{
                                xtype: 'fieldcontainer',
                                id:'userAdministrationUserTypeContainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                                border:false,
                    		items: [{
                                    xtype:'radiogroup',
                                    fieldLabel:Lang.Administration_userAdministration_dialog_UserType,
                                    columns: 1,
                                    width:300,
                                    vertical: true,
                                    allowBlank:false,
                                    id: 'userAdministraionDialogUserType',
                                    items:[{
                                            boxLabel: Lang.Administration_userAdministration_dialog_UserType_1,
                                            name: 'tipKorisnik',
                                            inputValue: 1,
                                            listeners: {
                                                change: function () {
                                                    if(this.getValue()){
                                                       var comboClient = Ext.getCmp('userAdministraionDialogClientCombo');
                                                       var comboAgency = Ext.getCmp('userAdministraionDialogAgencyCombo');
                                                       var roleList = Ext.getCmp('roleFldset');
                                                        comboClient.disable();
                                                        comboClient.hide();
                                                        comboAgency.disable();
                                                        comboAgency.hide();
                                                        roleList.enable();
                                                        //roleList.show();
                                                    }
                                                }
                                            }
                                    },{
                                            boxLabel: Lang.Administration_userAdministration_dialog_UserType_2,
                                            name: 'tipKorisnik',
                                            inputValue: 2,
                                            listeners: {
                                                change: function () {
                                                    var comboClient = Ext.getCmp('userAdministraionDialogClientCombo');
                                                    var comboAgency = Ext.getCmp('userAdministraionDialogAgencyCombo');
                                                    var roleList = Ext.getCmp('roleFldset');
                                                    if (this.getValue()) {
                                                        comboClient.enable();
                                                        comboClient.show();
                                                        comboAgency.disable();
                                                        comboAgency.hide();
                                                        roleList.disable();
                                                        
                                                    }
                                                }
                                            }                                            
                                    },{
                                            boxLabel:Lang.Administration_userAdministration_dialog_UserType_3,
                                            name:'tipKorisnik',
                                            inputValue:3,
                                            listeners: {
                                                change: function () {
                                                    var comboClient = Ext.getCmp('userAdministraionDialogClientCombo');
                                                    var comboAgency = Ext.getCmp('userAdministraionDialogAgencyCombo');
                                                    var roleList = Ext.getCmp('roleFldset');
                                                    if (this.getValue()) {
                                                        comboClient.disable();
                                                        comboClient.hide();
                                                        comboAgency.enable();
                                                        comboAgency.show();
                                                        roleList.disable();
                                                        
                                                    }
                                                }
                                            }
                                    }]
                                },{
                                    xtype:'fieldset',
                                    border:false,
                                    frame:false,
                                    padding:1,
                                    items:[{
                                            xtype:'combobox',
                                            fieldLabel:Lang.Administration_userAdministration_dialog_combo_Client,
                                            store: Ext.create('Ext.data.Store',{
                                                    fields: ['EntryID', 'EntryName'],
                                                    proxy: {
                                                            type: 'ajax',
                                                            url: '../App/Controllers/Klijent.php',
                                                            actionMethods: {
                                                                    read: 'POST'
                                                            },
                                                            extraParams: {
                                                                    action: 'KlijentGetForComboBox'
                                                            },
                                                            reader: {
                                                                    type: 'json',
                                                                    root: 'rows'
                                                            }
                                                    }
                                            }),
                                            id:'userAdministraionDialogClientCombo',
                                            name:'klijentID',
                                            disabled:true,
                                            hidden:true,
                                            labelAlign:'right',
                                            queryMode: 'remote',
                                            typeAhead: true,
                                            queryParam: 'filter',
                                            emptyText: Lang.Common_combobox_emptyText,
                                            valueField: 'EntryID',
                                            displayField: 'EntryName',
                                            allowBlank:false,
                                            width: 300
                                    },{
                                            xtype:'combobox',
                                            fieldLabel:Lang.Administration_userAdministration_dialog_combo_Agency,
                                            store: Ext.create('Ext.data.Store',{
                                                    fields: ['EntryID', 'EntryName'],
                                                    proxy: {
                                                            type: 'ajax',
                                                            url: '../App/Controllers/Agencija.php',
                                                            actionMethods: {
                                                                    read: 'POST'
                                                            },
                                                            extraParams: {
                                                                    action: 'AgencijaGetForComboBox'
                                                            },
                                                            reader: {
                                                                    type: 'json',
                                                                    root: 'rows'
                                                            }
                                                    }
                                            }),
                                            id:'userAdministraionDialogAgencyCombo',
                                            name:'agencijaID',
                                            disabled:true,
                                            hidden:true,
                                            labelAlign:'right',
                                            queryMode: 'remote',
                                            typeAhead: true,
                                            queryParam: 'filter',
                                            emptyText: Lang.Common_combobox_emptyText,
                                            valueField: 'EntryID',
                                            displayField: 'EntryName',
                                            allowBlank:false,
                                            width: 300
                                    }]
                                }]
                            }]
                    },{
                            xtype:'fieldset',
                            id:'roleFldset',
                            disabled:true,
                            //hidden:true,
                            border:true,
                            title:Lang.Administration_userAdministration_dialog_fldSet_RoleList,
                            padding:10,
                            items:[{
                                xtype: 'checkboxlist',
                                id: 'cblUserAdministrationDialogRoleList',
                                fieldLabel:'Role',
                                storeUrl: '../App/Controllers/Rola.php',
                                storeAction: 'RolaGetForComboBox',
                                storeAutoLoad: true,
                                textHeader: Lang.Administration_userAdministration_dialog_RoleList,
                                groupingEnabled: false,
                                listHeight: 150,
                                width: 600 
                            }]
                    }]
            }];
        
            		this.buttons = [
            {
            	text: 'Save',
            	icon: Icons.Save16,
            	handler: function ()
            	{
                    this.ownerCt.ownerCt.saveData();
            	}
            },
            {
            	text: 'Cancel',
            	scope: this,
            	icon: Icons.Cancel16,
            	handler: this.close
            }
        ];
            this.callParent(arguments);
        },
        
        saveData:function(){
                var form = Ext.getCmp('userAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnUserID').getValue());

                form.findField('passField').allowBlank = (entryID != -1);
                form.findField('passRetype').allowBlank = (entryID != -1);

                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('userAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'KorisnikInsertUpdate' : 'KorisnikInsertUpdate';

                    var fieldValues = form.getValues();
/*
                    var output = '';
                    for (var property in fieldValues) {
                        output += property + ': ' + fieldValues[property]+'; ';
                    }
                    alert(output);*/

                    fieldValues.RoleList = Ext.getCmp('cblUserAdministrationDialogRoleList').getSelectedValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Korisnik.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {


/*
                            var output = '';
                            for (var property in response) {
                                output += property + ': ' + response[property]+'; ';
                            }
                            alert(output);
*/


                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {

                                Ext.getCmp('userAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('userAdministrationGrid');
                                grid.reloadGrid(grid);
                            }
                            else {
                                Ext.Msg.show({
                                    title: Lang.Message_Title,
                                    msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                            }
                        }
                    });
                }
        },
        
        populateFields:function(entryID){
                var waitBox = Common.loadingBox(Ext.getCmp('userAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {
                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Korisnik.php',
                        params: { action: 'KorisnikLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('userAdministrationDialogForm').getForm();
                            form.setValues(data);
                            
  
                            if (data.agencijaID != null) {
                                var agencyCombo = Ext.getCmp('userAdministraionDialogAgencyCombo');
                                agencyCombo.show();
                                agencyCombo.enable();
                                agencyCombo.store.load({ callback: function (r, options, success) {
                                        agencyCombo.setValue(data.agencijaID);
                                    }
                                });
                            };
                            
                            if (data.klijentID != null) {
                                var clientCombo = Ext.getCmp('userAdministraionDialogClientCombo');
                                clientCombo.show();
                                clientCombo.enable();
                                clientCombo.store.load({ callback: function (r, options, success) {
                                        clientCombo.setValue(data.klijentID);
                                    }
                                });
                            };


                            Ext.getCmp('userAdministraionDialogActive').items.each(function(item) {
                                if(item.inputValue==data.aktivan){
                                    item.setValue(true);
                                }
                            });

                            Ext.getCmp('userAdministraionDialogUserType').items.each(function(item) {
                                if(item.inputValue==data.tipKorisnik){
                                    item.setValue(true);
                                    item.fireEvent('change',item);
                                }
                            });



                            Ext.getCmp('cblUserAdministrationDialogRoleList').selectItems(data.RoleList);

                            waitBox.hide();
                        },
                        failure: function (response, request) {
                            waitBox.hide();
                            Ext.Msg.show({
                                    title: Lang.Message_Title,
                                    msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                        }
                    });
                }
        }
});


