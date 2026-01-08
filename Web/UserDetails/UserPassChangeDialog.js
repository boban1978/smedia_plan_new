Ext.define('Mediaplan.administration.userPassChange.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.userpasschangedialog',

	title:Lang.UserPassChange_WindowTitile,
        id:'userPassChangeDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'key',
	modal: true,
	width: 500,
	autoHeight: true,
        initComponent: function ()
	{
            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'userPassChangeDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 150,
                    items:[{
                            xtype: 'textfield',
                            inputType: 'password',
                            labelWidth: 150,
                            fieldLabel:Lang.UserPassChange_form_Pass,
                            name:'NewPassword',
                            id:'userPass',
                            allowBlank:false, blankText:Lang.UserPassChange_form_Pass_BlankText,
                            width:400
                    },{
                            xtype: 'textfield',
                            inputType: 'password',
                            labelWidth: 150,
                            fieldLabel: Lang.UserPassChange_form_PassConfirm,
                            name: 'NewPasswordRetype',
                            initialPassField: 'userPass', // id of the initial password field
                            allowBlank:false, blankText:Lang.UserPassChange_form_PassConfirm_BlankText,
                            width:400
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
                var form = Ext.getCmp('userPassChangeDialogForm').getForm();


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('userPassChangeDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = 'KorisnikPassChange';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Korisnik.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('userPassChangeDialogWindow').close();

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
        }
});










