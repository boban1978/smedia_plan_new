Ext.define('Mediaplan.administration.priceListAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.pricelistadministrationdialog',

	title: Lang.Administration_priceListAdministration_dialog_Title,
        id:'priceListAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
	modal: true,
	width: 450,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnPriceListID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'priceListAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 150,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnPriceListID',
                            name: 'cenovnikID',
                            value: '-1'
                    },{
                            xtype:'combobox',
                            fieldLabel:Lang.Administration_priceListAdministration_dialog_Station,
                            store: Ext.create('Ext.data.Store',{
                                    fields: ['EntryID', 'EntryName'],
                                    proxy: {
                                            type: 'ajax',
                                            url: '../App/Controllers/RadioStanica.php',
                                            actionMethods: {
                                                    read: 'POST'
                                            },
                                            extraParams: {
                                                    action: 'RadioStanicaGetForComboBox'
                                            },
                                            reader: {
                                                    type: 'json',
                                                    root: 'rows'
                                            }
                                    }
                            }),
                            id:'priceListAdministraionDialogStationCombo',
                            name:'radioStanicaID',
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
                            fieldLabel:Lang.Administration_priceListAdministration_dialog_Block,
                            store: Ext.create('Ext.data.Store',{
                                    fields: ['EntryID', 'EntryName'],
                                    proxy: {
                                            type: 'ajax',
                                            url: '../App/Controllers/Blok.php',
                                            actionMethods: {
                                                    read: 'POST'
                                            },
                                            extraParams: {
                                                    action: 'BlokGetForComboBox'
                                            },
                                            reader: {
                                                    type: 'json',
                                                    root: 'rows'
                                            }
                                    }
                            }),
                            id:'priceListAdministraionDialogBlockCombo',
                            name:'blokID',
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
                            fieldLabel:Lang.Administration_priceListAdministration_dialog_Category,
                            store: Ext.create('Ext.data.Store',{
                                    fields: ['EntryID', 'EntryName'],
                                    proxy: {
                                            type: 'ajax',
                                            url: '../App/Controllers/Cenovnik.php',
                                            actionMethods: {
                                                    read: 'POST'
                                            },
                                            extraParams: {
                                                    action: 'KategorijaCenaGetForComboBox'
                                            },
                                            reader: {
                                                    type: 'json',
                                                    root: 'rows'
                                            }
                                    }
                            }),
                            id:'priceListAdministraionDialogCategoryCombo',
                            name:'kategorijaCenaID',
                            queryMode: 'remote',
                            typeAhead: true,
                            queryParam: 'filter',
                            emptyText: Lang.Common_combobox_emptyText,
                            valueField: 'EntryID',
                            displayField: 'EntryName',
                            allowBlank:false,
                            width: 300                        
                    },{
                           xtype:'numberfield',
                           fieldLabel:Lang.Administration_priceListAdministration_dialog_Price,
                           name:'cena',
                           allowBlank:false,
                           value: 0,
                           minValue: 0,
                           maxValue: 10000000,
                           width:240  
                    },{
                            xtype:'radiogroup',
                            fieldLabel:Lang.Administration_priceListkAdministration_dialog_Weekdays,
                            columns: 2,
                            width:240,
                            vertical: true,
                            items:[{
                                    boxLabel: Lang.Common_Yes,
                                    name: 'vikend',
                                    inputValue: 'true'
                            },{
                                    boxLabel: Lang.Common_No,
                                    name: 'vikend',
                                    checked:true,
                                    inputValue: 'false'                                            
                            }]  
                    },{
                            xtype:'radiogroup',
                            fieldLabel:Lang.Administration_priceListkAdministration_dialog_Active,
                            columns: 2,
                            width:240,
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
                var form = Ext.getCmp('priceListAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnPriceListID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('priceListAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'CenovnikInsertUpdate' : 'CenovnikInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Cenovnik.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('priceListAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('priceListAdministrationGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('priceListAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Cenovnik.php',
                        params: { action: 'CenovnikLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('priceListAdministrationDialogForm').getForm();
                            form.setValues(data);
							
                            Ext.getCmp('priceListAdministraionDialogStationCombo').store.load({ callback: function (r, options, success) {
                                    Ext.getCmp('priceListAdministraionDialogStationCombo').setValue(data.radioStanicaID);
                                }
                            });

                            Ext.getCmp('priceListAdministraionDialogBlockCombo').store.load({ callback: function (r, options, success) {
                                    Ext.getCmp('priceListAdministraionDialogBlockCombo').setValue(data.blokID);
                                }
                            });
                            
                            Ext.getCmp('priceListAdministraionDialogCategoryCombo').store.load({ callback: function (r, options, success) {
                                    Ext.getCmp('priceListAdministraionDialogCategoryCombo').setValue(data.kategorijaCenaID);
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








