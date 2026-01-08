Ext.define('Mediaplan.mediaPlan.clients.details.ContactsDialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientdetailscontactsdialog',

	title: Lang.MediaPlan_clients_details_contacts_dialog_Title,
        id:'clientDetailsContactsDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'user',
	modal: true,
	width: 450,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnContactID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    }
                    var clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                    Ext.getCmp('hdnContactClientID').setValue(clientID);
;                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'clientDetailsContactsDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnContactID',
                            name: 'kontaktID',
                            value: '-1'
                    },{
                            xtype: 'hidden',
                            id: 'hdnContactClientID',
                            name: 'klijentID'
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_contacts_dialog_Name,
                            name:'ime',
                            allowBlank:false,
                            width:250
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_contacts_dialog_Surname,
                            name:'prezime',
                            allowBlank:false,
                            width:250
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_contacts_dialog_Address,
                            name:'adresa',
                            width:400
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_contacts_dialog_Position,
                            name:'funkcija',
                            width:300
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_contacts_dialog_Phone1,
                            name:'telefon1',
                            width:220
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_contacts_dialog_Phone2,
                            name:'telefon2',
                            width:220
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_contacts_dialog_Phone3,
                            name:'telefon3',
                            width:220
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.MediaPlan_clients_details_contacts_dialog_Email,
                            name:'email',
                            width:350
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
                var form = Ext.getCmp('clientDetailsContactsDialogForm').getForm();


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsContactsDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = 'KontaktInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Kontakt.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('clientDetailsContactsDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('clientDetailsContactsGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsContactsDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Kontakt.php',
                        params: { action: 'KontaktLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('clientDetailsContactsDialogForm').getForm();
                            form.setValues(data);



                            waitBox.hide();
                        },
                        failure: function (response, request) {
                            waitBox.hide();
                        }
                    });
                }
        }
});







