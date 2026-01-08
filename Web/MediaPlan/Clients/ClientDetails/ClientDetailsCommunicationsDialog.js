Ext.define('Mediaplan.mediaPlan.clients.details.CommunicationsDialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientdetailscommunicationsdialog',

	title: Lang.MediaPlan_clients_details_communications_dialog_Title,
        id:'clientDetailsCommunicationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'communication',
	modal: true,
	width: 550,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    var clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                    Ext.getCmp('hdnCommunicationClientID').setValue(clientID);
;                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'clientDetailsCommunicationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 180,
                    fileUpload: true,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnCommunicationClientID',
                            name: 'klijentID'
                    },/*{
                            xtype: 'hidden',
                            name: 'istorijaKomunikacijeID',
                            value:'-1'
                    },*/{
                        xtype:'textarea',
                        labelWidth: 150,
                        fieldLabel:Lang.MediaPlan_clients_details_communications_dialog_Note,
                        width:500,
                        height:100,
                        allowBlank:false,
                        name:'napomena'
                    },{
                        xtype: 'combobox',
                        fieldLabel:Lang.MediaPlan_clients_details_communications_dialog_Type,
                        labelWidth:150,
                        name:'tipKomunikacijaID',
                        store: new Ext.data.Store({
                                fields: ['EntryID', 'EntryName'],
                                proxy: {
                                        type: 'ajax',
                                        url: '../App/Controllers/TipKomunikacija.php',
                                        actionMethods: {
                                                read: 'POST'
                                        },
                                        extraParams: {
                                                action: 'TipKomunikacijaGetForComboBox'
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
                        allowBlank:false,
                        width: 400
                    },{
                        xtype:'datefield',
                        labelWidth: 150,
                        format:'Y-m-d',
                        fieldLabel:Lang.MediaPlan_clients_details_communications_dialog_Date,
                        width:300,
                        allowBlank:false,
                        name:'datumKomunikacije'
                    },{
                        xtype:'textfield',
                        labelWidth: 150,
                        inputType:'file',
                        fieldLabel:Lang.MediaPlan_clients_details_communications_dialog_Attach,
                        name:'prilog',
                        width:450
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
                var form = Ext.getCmp('clientDetailsCommunicationDialogForm').getForm();

                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCommunicationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();
                    form.submit({
                        url: '../App/Controllers/IstorijaKomunikacija.php?action=IstorijaKomunikacijaInsertUpdate',
                        success: function (fp,o) {

                            waitBox.hide();
                            Ext.getCmp('clientDetailsCommunicationDialogWindow').close();

                            //rebind grid
                                var grid = Ext.getCmp('clientDetailsCommunicationsGrid');
                                grid.reloadGrid(grid);
                        },
                        failure: function (fp,o) {
                            waitBox.hide();
                            alert('Greška u obradi zahteva');
                        }

                    });
                }
        }
});








