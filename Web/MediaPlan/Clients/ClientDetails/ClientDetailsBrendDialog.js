Ext.define('Mediaplan.mediaPlan.clients.details.BrendDialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientdetailsbrenddialog',

	title: Lang.MediaPlan_clients_details_brend_dialog_Title,
        id:'clientDetailsBrendDialogWindow',
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
                        Ext.getCmp('hdnBrendID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    }
                    var clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                    Ext.getCmp('hdnBrendClientID').setValue(clientID);
;                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'clientDetailsBrendDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnBrendID',
                            name: 'brendID',
                            value: '-1'
                    },{
                            xtype: 'hidden',
                            id: 'hdnBrendClientID',
                            name: 'klijentID'
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_brend_dialog_Name,
                            name:'naziv',
                            allowBlank:false,
                            width:350
                    },{
							xtype:'combobox',
							fieldLabel:Lang.MediaPlan_clients_details_brend_dialog_Activity,
							id:'clientDetailsActivity',
							store: Ext.create('Ext.data.Store',{
									fields: ['EntryID', 'EntryName'],
									proxy: {
											type: 'ajax',
											url: '../App/Controllers/Delatnost.php',
											actionMethods: {
													read: 'POST'
											},
											extraParams: {
													action: 'DelatnostGetForComboBox',
													clientID:''
											},
											reader: {
													type: 'json',
													root: 'rows'
											}
									},
									listeners:{
									  beforeload : {
										fn: function(store,records,successful,eOpts){
											store.proxy.extraParams.clientID = Ext.getCmp('hdnClientDetailsID').getValue();
										}
									  } 
									}
							}),
							name:'delatnostID',
							queryMode: 'remote',
							typeAhead: true,
							queryParam: 'filter',
							emptyText: Lang.Common_combobox_emptyText,
							valueField: 'EntryID',
							displayField: 'EntryName',
							allowBlank:false,
							width: 350				
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
                var form = Ext.getCmp('clientDetailsBrendDialogForm').getForm();


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsBrendDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = 'BrendInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Brend.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('clientDetailsBrendDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('clientDetailsBrendGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsBrendDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Brend.php',
                        params: { action: 'BrendLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('clientDetailsBrendDialogForm').getForm();
                            form.setValues(data);
							
							var activityCombo = Ext.getCmp('clientDetailsActivity');
							activityCombo.store.load({ callback: function (r, options, success) {
									activityCombo.setValue(data.delatnostID);
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







