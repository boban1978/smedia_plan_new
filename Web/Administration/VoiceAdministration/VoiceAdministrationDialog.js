Ext.define('Mediaplan.administration.VoiceAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.voiceadministrationdialog',

	title: Lang.Administration_voiceAdministration_dialog_Title,
    id:'voiceAdministrationDialogWindow',
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
                        Ext.getCmp('hdnVoiceID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'voiceAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnVoiceID',
                            name: 'glasID',
                            value: '-1'
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.Administration_voiceAdministration_dialog_Name,
                            name:'imePrezime',
                            width:300
                    },{
                            xtype:'radiogroup',
                            fieldLabel:Lang.Administration_voiceAdministration_dialog_Active,
                            columns: 2,
                            width:200,
                            vertical: true,
                            id: 'voiceAdministrationActive',
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
                var form = Ext.getCmp('voiceAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnVoiceID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('voiceAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'GlasInsertUpdate' : 'GlasInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Glas.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('voiceAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('voiceAdministrationGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('voiceAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Glas.php',
                        params: { action: 'GlasLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('voiceAdministrationDialogForm').getForm();
                            form.setValues(data);

                            Ext.getCmp('voiceAdministrationActive').items.each(function(item) {
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






