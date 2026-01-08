Ext.define('Mediaplan.administration.agencyAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.agencyadministrationdialog',

	title: Lang.Administration_agencyAdministration_dialog_Title,
        id:'agencyAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'vcard',
	modal: true,
	width: 650,
	height:370,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnAgencyID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                        Ext.getCmp('agencyAdministrationClientsGrid').store.proxy.extraParams.filterValues = '{"agencijaID":"'+this.entryID+'"}';
                        Ext.getCmp('agencyAdministrationClientsGrid').getStore().load();
                    } 
                    
                    if(this.activateButtons){
                        Ext.getCmp('agencyAdministrationDialogBtnSave').show();
                    }
                }
                
            };

            this.items = [                        
                        Ext.create('Ext.tab.Panel', {
                                deferredRender: false,
                                activeTab: 0,
                                items: [{
                                    title:Lang.Administration_agencyAdministration_dialog_tab_GeneralData,
                                    items:[{
                                            xtype:'form',
                                            bodyPadding: 5,
                                            id: 'agencyAdministrationDialogForm',
                                            border: false,
                                            frame: false,
                                            labelWidth: 120,
                                            items:[{
                                                    xtype: 'hidden',
                                                    id: 'hdnAgencyID',
                                                    name: 'agencijaID',
                                                    value: '-1'
                                            },{
                                                    xtype:'fieldset',
                                                    border:true,
                                                    title:Lang.Administration_agencyAdministration_dialog_fldSet_GeneralData,
                                                    items:[{
                                                            xtype: 'fieldcontainer',
                                                            layout: 'hbox',
                                                            labelAlign: 'left',
                                                            items: [{
                                                                  xtype:'textfield',
                                                                  fieldLabel:Lang.Administration_agencyAdministration_dialog_Name,
                                                                  name:'naziv',
                                                                  allowBlank:false,
                                                                  width:250

                                                            },{
                                                                  xtype:'textfield',
                                                                  labelAlign:'right',
                                                                  fieldLabel:Lang.Administration_agencyAdministration_dialog_Address,
                                                                  name:'adresa',
                                                                  width:350
                                                            }]   
                                                        },{
                                                            xtype: 'fieldcontainer',
                                                            layout: 'hbox',
                                                            labelAlign: 'left',
                                                            items: [{
                                                                  xtype:'textfield',
                                                                  fieldLabel:Lang.Administration_agencyAdministration_dialog_Contact,
                                                                  name:'kontaktOsoba',
                                                                  width:250

                                                            },{
                                                                  xtype:'textfield',
                                                                  labelAlign:'right',
                                                                  fieldLabel:Lang.Administration_agencyAdministration_dialog_Email,
                                                                  name:'email',
                                                                  width:350
                                                            }]      
                                                        },{
                                                            xtype: 'fieldcontainer',
                                                            layout: 'hbox',
                                                            labelAlign: 'left',
                                                            items: [{
                                                                  xtype:'textfield',
                                                                  fieldLabel:Lang.Administration_agencyAdministration_dialog_Mobile,
                                                                  name:'telefonMobilni',
                                                                  width:250

                                                            },{
                                                                  xtype:'textfield',
                                                                  labelAlign:'right',
                                                                  fieldLabel:Lang.Administration_agencyAdministration_dialog_Phone,
                                                                  name:'telefonFiksni',
                                                                  width:250
                                                            }]
                                                        },{
                                                            xtype:'textfield',
                                                            fieldLabel:Lang.Administration_agencyAdministration_dialog_Contry,
                                                            width:250,
                                                            name:'drzava'
                                                        }]
                                            },{
                                                    xtype:'fieldset',
                                                    border:true,
                                                    title:Lang.Administration_agencyAdministration_dialog_fldSet_OtherData,
                                                    items:[{
                                                                xtype: 'fieldcontainer',
                                                                layout: 'hbox',
                                                                labelAlign: 'left',
                                                                items: [{
                                                                      xtype:'textfield',
                                                                      fieldLabel:Lang.Administration_agencyAdministration_dialog_Pib,
                                                                      name:'pib',
                                                                      width:250

                                                                },{
                                                                      xtype:'textfield',
                                                                      labelAlign:'right',
                                                                      fieldLabel:Lang.Administration_agencyAdministration_dialog_RegistrationNumber,
                                                                      name:'maticni',
                                                                      width:250
                                                                }]
                                                            },{
                                                                xtype: 'fieldcontainer',
                                                                layout: 'hbox',
                                                                labelAlign: 'left',
                                                                items: [{
                                                                      xtype:'textfield',
                                                                      fieldLabel:Lang.Administration_agencyAdministration_dialog_Account,
                                                                      name:'racun',
                                                                      width:250

                                                                },{
                                                                      xtype:'textfield',
                                                                      labelAlign:'right',
                                                                      fieldLabel:Lang.Administration_agencyAdministration_dialog_BillingAddress,
                                                                      name:'adresaZaRacun',
                                                                      width:350
                                                                }]
                                                            },{
                                                                xtype: 'fieldcontainer',
                                                                layout: 'hbox',
                                                                labelAlign: 'left',
                                                                items: [{
                                                                      xtype:'numberfield',                                                                      
                                                                      fieldLabel:Lang.Administration_agencyAdministration_dialog_Popust,
                                                                      name:'popust',
                                                                      allowBlank:false,
                                                                      value: 0,
                                                                      minValue: 0,
                                                                      maxValue: 100,
                                                                      width:250
                                                                },{ 
                                                                    xtype:'radiogroup',
                                                                    labelAlign:'right',
                                                                    fieldLabel:Lang.Administration_agencyAdministration_dialog_Active,
                                                                    columns: 2,
                                                                    width:250,
                                                                    vertical: true,
                                                                    id:'agencyAdminisrtationActive',
                                                                    items:[{
                                                                            boxLabel: Lang.Common_Yes,
                                                                            name: 'aktivan',
                                                                            checked:true,
                                                                            inputValue: 'true'
                                                                    },{
                                                                            boxLabel: Lang.Common_No,
                                                                            name: 'aktivan',
                                                                            inputValue: 'false'
                                                                    }]}]
                                                            }]                                               
                                            }]
                                    }]
                                },{
                                    title:Lang.Administration_agencyAdministration_dialog_tab_Clients,
                                    frame:false,
                                    items:[{
                                            xtype:'agencyadministrationclientsgrid',
                                            width:'100%',
                                            height:280,
                                            border:false,
                                            frame:false
                                    }]
                                }]
                        })
            ];

        
        this.buttons = [
            {
            	text: 'Save',
                id:'agencyAdministrationDialogBtnSave',
                hidden:true,
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
                var form = Ext.getCmp('agencyAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnAgencyID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('agencyAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'AgencijaInsertUpdate' : 'AgencijaInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Agencija.php',
                        params: {action: formAction, fieldValues: Ext.encode(fieldValues)},
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('agencyAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('agencyAdministrationGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('agencyAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Agencija.php',
                        params: {action: 'AgencijaLoad', entryID: entryID},
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('agencyAdministrationDialogForm').getForm();
                            form.setValues(data);


                            Ext.getCmp('agencyAdminisrtationActive').items.each(function(item) {
                                if(item.inputValue==data.aktivan){
                                    item.setValue(true);
                                }
                            });


                            waitBox.hide();
                        },
                        failure: function (response, request) {
                            waitBox.hide();
                        }
                    });
                }
        }
});










