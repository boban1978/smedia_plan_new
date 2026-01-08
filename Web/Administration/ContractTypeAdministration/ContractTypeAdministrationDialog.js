Ext.define('Mediaplan.administration.contractTypeAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.contracttypeadministrationdialog',

	title: Lang.Administration_contractTypeAdministration_dialog_Title,
        id:'contractTypeAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
	modal: true,
	width: 450,
	height: 150,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnContractID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'contractTypeAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnContractID',
                            name: 'tipUgovoraID',
                            value: '-1'
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.Administration_contractTypeAdministration_filter_Name,
                            name:'naziv',
                            width:300
                    },{
                            xtype:'radiogroup',
                            fieldLabel:Lang.Administration_contractTypeAdministration_filter_Active,
                            columns: 2,
                            width:200,
                            vertical: true,
                            id: 'contractTypeAdministrationActive',
                            items:[{
                                    boxLabel: Lang.Common_Yes,
                                    name: 'aktivan',
                                    checked:true,
                                    value:true,
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
                var form = Ext.getCmp('contractTypeAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnContractID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('contractTypeAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'TipUgovoraInsertUpdate' : 'TipUgovoraInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/TipUgovora.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('contractTypeAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('contractTypeAdministrationGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('contractTypeAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/TipUgovora.php',
                        params: { action: 'TipUgovoraLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('contractTypeAdministrationDialogForm').getForm();
                            form.setValues(data);

                            Ext.getCmp('contractTypeAdministrationActive').items.each(function(item) {
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






