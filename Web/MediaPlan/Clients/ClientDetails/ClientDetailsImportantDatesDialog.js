Ext.define('Mediaplan.mediaPlan.clients.details.ImportantDatesDialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientdetailsimportantdatesdialog',

	title: Lang.MediaPlan_clients_details_importantDates_dialog_Title,
        id:'clientDetailsImportantDatesDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'calendar',
	modal: true,
	width: 450,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnDateID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    }
                    var clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                    Ext.getCmp('hdnDateClientID').setValue(clientID);
;                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'clientDetailsImportantDatesDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnDateID',
                            name: 'bitanDatumID',
                            value: '-1'
                    },{
                            xtype: 'hidden',
                            id: 'hdnDateClientID',
                            name: 'ID'
                    },{
                            xtype:'hidden',
                            name:'vrsta',
                            value:'2'
                    },{
                            xtype:'datefield',
                            fieldLabel:Lang.MediaPlan_clients_details_importantDates_dialog_Date,
                            name:'datum',
                            format:'Y-m-d',
                            allowBlank:false,
                            width:250
                    },{
                            xtype:'textarea',
                            name:'opis',
                            fieldLabel:Lang.MediaPlan_clients_details_importantDates_dialog_Description,
                            width:400,
                            height:100
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
                var form = Ext.getCmp('clientDetailsImportantDatesDialogForm').getForm();


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsImportantDatesDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = 'BitanDatumInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/BitanDatum.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('clientDetailsImportantDatesDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('clientDetailsImportantDatesGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsImportantDatesDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/BitanDatum.php',
                        params: { action: 'BitanDatumLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('clientDetailsImportantDatesDialogForm').getForm();
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






