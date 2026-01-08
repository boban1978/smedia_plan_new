Ext.define('Mediaplan.administration.servicePriceAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.servicepriceadministrationdialog',

	title: Lang.Administration_servicePriceAdministration_dialog_Title,
    id:'servicePriceAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
	modal: true,
	width: 400,
	height: 150,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnServicePriceID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'servicePriceAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnServicePriceID',
                            name: 'cenovnikUslugaID',
                            value: '-1'
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.Administration_servicePriceAdministration_dialog_Name,
                            name:'naziv',
                            width:300
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.Administration_servicePriceAdministration_dialog_Price,
                            name:'cena',
                            width:300
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
                var form = Ext.getCmp('servicePriceAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnServicePriceID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('servicePriceAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'CenovnikUslugaInsertUpdate' : 'CenovnikUslugaInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/CenovnikUsluga.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('servicePriceAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('servicePriceAdministrationGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('servicePriceAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/CenovnikUsluga.php',
                        params: { action: 'CenovnikUslugaLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('servicePriceAdministrationDialogForm').getForm();
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






