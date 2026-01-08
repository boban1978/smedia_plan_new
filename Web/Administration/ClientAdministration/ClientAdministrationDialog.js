Ext.define('Mediaplan.administration.clientAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientadministrationdialog',

	title: Lang.Administration_clientAdministration_dialog_Title,
        id:'clientAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'vcard',
	modal: true,
	width: 650,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnClientID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };
            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 5,
                    id: 'clientAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnClientID',
                            name: 'klijentID',
                            value: '-1'
                    },{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_clientAdministration_dialog_fldSet_GeneralData,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Name,
                                      name:'naziv',
                                      allowBlank:false,
                                      width:250
                                      
                                },{
                                      xtype:'textfield',
                                      labelAlign:'right',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Address,
                                      name:'adresa',
                                      width:350
                                }]   
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Contry,
                                      name:'drzava',
                                      width:250
                                      
                                },{
                                      xtype:'textfield',
                                      labelAlign:'right',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Email,
                                      name:'email',
                                      width:350
                                }]      
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Mobile,
                                      name:'telefonMobilni',
                                      width:250
                                      
                                },{
                                      xtype:'textfield',
                                      labelAlign:'right',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Phone,
                                      name:'telefonFiksni',
                                      width:250
                                }]
                            }]
                    },{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_clientAdministration_dialog_fldSet_OtherData,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Pib,
                                      name:'pib',
                                      width:250
                                      
                                },{
                                      xtype:'textfield',
                                      labelAlign:'right',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_RegistrationNumber,
                                      name:'maticni',
                                      width:250
                                }]
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Account,
                                      name:'racun',
                                      width:250
                                      
                                },{
                                      xtype:'textfield',
                                      labelAlign:'right',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_BillingAddress,
                                      name:'adresaZaRacun',
                                      width:350
                                }]
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype: 'combobox',
                    			fieldLabel:Lang.Administration_clientAdministration_dialog_ContractType,
                                        id:'clientAdminisrtationContractType',
                                        name:'tipUgovoraID',
                    			store: new Ext.data.Store({
                    				fields: ['EntryID', 'EntryName'],
                    				proxy: {
                    					type: 'ajax',
                    					url: '../App/Controllers/TipUgovora.php',
                    					actionMethods: {
                    						read: 'POST'
                    					},
                    					extraParams: {
                    						action: 'TipUgovoraGetForComboBox'
                    					},
                    					reader: {
                    						type: 'json',
                    						root: 'rows'
                    					}
                    				}
                    			}),
                    			queryMode: 'remote',
                    			typeAhead: true,
                    			queryParam: 'filter',
                    			emptyText: Lang.Common_combobox_emptyText,
                    			valueField: 'EntryID',
                    			displayField: 'EntryName',
                    			width: 250
                                      
                                },{
								  xtype:'numberfield',
								  labelAlign:'right',
								  fieldLabel:Lang.Administration_clientAdministration_dialog_Popust,
								  name:'popust',
								  allowBlank:false,
								  value: 0,
								  minValue: 0,
								  maxValue: 100,
								  width:220
								}]
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_CoverArea,
                                      name:'teritorijaPokrivanja',
                                      width:250
                                      
                                },{
                                    xtype:'radiogroup',
                                    fieldLabel:Lang.Administration_clientAdministration_dialog_Active,
									labelAlign:'right',
                                    columns: 2,
                                    width:250,
                                    vertical: true,
                                    id:'clientAdminisrtationActive',
                                    items:[{
                                            boxLabel: Lang.Common_Yes,
                                            name: 'aktivan',
                                            checked:true,
                                            inputValue: 'true'/*,
                                            id: 'clientAdminisrtationActiveTrue'*/
                                    },{
                                            boxLabel: Lang.Common_No,
                                            name: 'aktivan',
                                            inputValue: 'false'/* ,
                                            id: 'clientAdminisrtationActiveFalse'*/
                                    }] 
                                }]
                            },{



                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                labelAlign: 'left',
                                items: [{
                                    xtype: 'combobox',
                                    fieldLabel:Lang.Administration_clientAdministration_dialog_ActivityType,
                                    id:'clientAdminisrtationActivityType',
                                    name:'delatnostID',
                                    labelAlign:'left',
                                    store: new Ext.data.Store({
                                        fields: ['EntryID', 'EntryName'],
                                        proxy: {
                                            type: 'ajax',
                                            url: '../App/Controllers/Delatnost.php',
                                            actionMethods: {
                                                read: 'POST'
                                            },
                                            extraParams: {
                                                action: 'DelatnostGetForComboBox'
                                            },
                                            reader: {
                                                type: 'json',
                                                root: 'rows'
                                            }
                                        }
                                    }),
                                    queryMode: 'remote',
                                    typeAhead: true,
                                    queryParam: 'filter',
                                    emptyText: Lang.Common_combobox_emptyText,
                                    valueField: 'EntryID',
                                    displayField: 'EntryName',
                                    width: 250
                                }]




                            }










                            /*,{
                                    xtype:'radiogroup',
                                    fieldLabel:Lang.Administration_clientAdministration_dialog_Active,
                                    columns: 2,
                                    width:250,
                                    vertical: true,
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
                                }*/]
                    },/*{
                            xtype:'fieldset',
                            border:true,
                            title:'Delatnosti klijenta',
                            padding:10,
                            items:[{
                                xtype: 'checkboxlist',
                                id: 'cblClientAdministrationDialogClientActivity',
                                fieldLabel:'Agencije',
                                storeUrl: '../App/Controllers/Delatnost.php',
                                storeAction: 'DelatnostGetForComboBox',
                                storeAutoLoad: true,
                                textHeader: Lang.Administration_clientAdministration_dialog_ActivityType,
                                groupingEnabled: false,
                                listHeight: 100,
                                width: 600     
                            }]
					},*/{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_clientAdministration_dialog_fldSet_Agencies,
                            padding:10,
                            items:[{
                                xtype: 'checkboxlist',
                                id: 'cblClientAdministrationDialogClientList',
                                fieldLabel:'Agencije',
                                storeUrl: '../App/Controllers/Agencija.php',
                                storeAction: 'AgencijaGetForComboBox',
                                storeAutoLoad: true,
                                textHeader: Lang.Administration_clientAdministration_dialog_AgencyList,
                                groupingEnabled: false,
                                listHeight: 100,
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

                var form = Ext.getCmp('clientAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnClientID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'KlijentInsertUpdate' : 'KlijentInsertUpdate';

                    var fieldValues = form.getValues();
					//fieldValues.delatnostList = Ext.getCmp('cblClientAdministrationDialogClientActivity').getSelectedValues();
                    fieldValues.agencijaList = Ext.getCmp('cblClientAdministrationDialogClientList').getSelectedValues();
                    
                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Klijent.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('clientAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('clientAdministrationGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('clientAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Klijent.php',
                        params: { action: 'KlijentLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('clientAdministrationDialogForm').getForm();
                            form.setValues(data);



                            Ext.getCmp('clientAdminisrtationContractType').store.load({ callback: function (r, options, success) {
                                        Ext.getCmp('clientAdminisrtationContractType').setValue(data.tipUgovoraID);
                                    }
                             });
							 
                             //Ext.getCmp('cblClientAdministrationDialogClientActivity').selectItems(data.delatnostList);

                             Ext.getCmp('clientAdminisrtationActivityType').store.load({ callback: function (r, options, success) {
                                        Ext.getCmp('clientAdminisrtationActivityType').setValue(data.delatnostID);
                                    }
                             });

                            Ext.getCmp('clientAdminisrtationActive').items.each(function(item) {
                                    if(item.inputValue==data.aktivan){
                                        item.setValue(true);
                                    }
                            });




                            //Ext.getCmp('clientAdminisrtationActiveTrue').setValue((data.aktivan=='true'));
                            //Ext.getCmp('clientAdminisrtationActiveFalse').setValue((data.aktivan=='false'));


                            Ext.getCmp('cblClientAdministrationDialogClientList').selectItems(data.agencijaList);

                            waitBox.hide();
                        },
                        failure: function (response, request) {
                            waitBox.hide();
                        }
                    });
                }
        }
});





