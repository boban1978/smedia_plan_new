Ext.define('Mediaplan.mediaPlan.clients.details.Form', {
	extend: 'Ext.form.Panel',
	alias: 'widget.clientdetailsform',
        border:false,
        frame:false,
        padding:10,
	width:'100%',
        height:'100%',
        layout: {
            //type: 'hbox',
            type:'hbox',
            align:'strech'
        },
	initComponent: function ()
	{
            this.id = 'clientsDetailsForm';
            this.items = [{
                    xtype: 'hidden',
                    id: 'hdnClientDetailsID'
            },{
                    xtype:'fieldset',
                    title:Lang.MediaPlan_clients_details_dialog_fldSet_GeneralData,
                    border:true,
                    height:440,
                    width:500,
                    labelWidth:120,
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
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Mobile,
                                      name:'telefonMobilni',
                                      width:220
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
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Phone,
                                      name:'telefonFiksni',
                                      width:220
                                }]      
                            },{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Email,
                                      name:'email',
                                      width:470
                             },{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Address,
                                      name:'adresa',
                                      width:470
                            },{
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
                                      width:220
                                }]
                            },{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_Account,
                                      name:'racun',
                                      width:350
                                      
                           },{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_BillingAddress,
                                      name:'adresaZaRacun',
                                      width:470
                           },{
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
                                        width: 350
                                      
                           },{
                                        xtype: 'combobox',
                    			fieldLabel:Lang.Administration_clientAdministration_dialog_ActivityType,
                                        id:'clientAdminisrtationActivityType',
                                        name:'delatnostID',
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
                    			width: 350
                            },/*{
									xtype: 'checkboxlist',
									margin:'0 0 10 105',
									id: 'cblClientDialogClientActivity',
									fieldLabel:'Delatnosti',
									storeUrl: '../App/Controllers/Delatnost.php',
									storeAction: 'DelatnostGetForComboBox',
									storeAutoLoad: true,
									textHeader: Lang.Administration_clientAdministration_dialog_ActivityType,
									groupingEnabled: false,
									listHeight: 110,
									width: 365 							
							},*/{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                      xtype:'textfield',
                                      fieldLabel:Lang.Administration_clientAdministration_dialog_CoverArea,
                                      name:'teritorijaPokrivanja',
                                      width:250
                                      
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
                                }]
            },{
                    xtype:'fieldcontainer',
                    //border:false,
                    height:440,
                    margin:'0 0 0 5 ',
                    flex:1,
                    items:[{
                            xtype:'fieldset',
                            title:Lang.MediaPlan_clients_details_dialog_fldSet_ImportantDates,
                            border:true,
                            padding:5,
                            width:'100%',
                            height:215,
                            items:[{
                                    xtype:'clientdetailsimportantdatesgrid',
                                    width:'100%',
                                    height:185
                            }]
                    },{
                            xtype:'fieldset',
                            title:Lang.MediaPlan_clients_details_dialog_fldSet_Agencies,
                            border:true,
                            padding:5,
                            width:'100%',
                            height:215,
                            items:[{
                                xtype: 'checkboxlist',
                                id: 'cblClientAdministrationDialogClientList',
                                fieldLabel:'Agencije',
                                storeUrl: '../App/Controllers/Agencija.php',
                                storeAction: 'AgencijaGetForComboBox',
                                storeAutoLoad: true,
                                textHeader: Lang.Administration_clientAdministration_dialog_AgencyList,
                                groupingEnabled: false,
                                listHeight: 185,
                                width: 345 
                            }]
                    }]
            }]    
            this.callParent(arguments);
        } //eo intitcomponent
        

});

