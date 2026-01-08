Ext.define('Mediaplan.administration.userDetails.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.userdetailsdialog',

	title: Lang.UserDetails_dialog_Title,
        id:'userDetailsDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'user',
	modal: true,
	width: 500,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
               
                        this.populateFields(); 
                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'userDetailsDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                        xtype: 'hidden',
                        name: 'korisnikID',
                        id: 'hdnKorisnikID'
                    },{
                         xtype:'fieldset',
                         border:true,
                         title:Lang.UserDetails_form_fldset_PersonalData,
                         items:[{
                            xtype:'textfield',
                            fieldLabel:Lang.UserDetails_form_Name,
                            name:'ime',
                            allowBlank:false,
                            width:400
                         },{
                            xtype:'textfield',
                            fieldLabel:Lang.UserDetails_form_Surname,
                            name:'prezime',
                            allowBlank:false,
                            width:400
                         }]
                    },{
                         xtype:'fieldset',
                         border:true,
                         title:Lang.UserDetails_form_fldset_ConntactData,
                         items:[{
                            xtype:'textfield',
                            fieldLabel:Lang.UserDetails_form_Email,
                            name:'email',
                            width:400
                         },{
                            xtype:'textfield',
                            fieldLabel:Lang.UserDetails_form_Phone,
                            name:'fiksniTelefon',
                            width:300
                         },{
                            xtype:'textfield',
                            fieldLabel:Lang.UserDetails_form_Mobphone,
                            name:'mobilniTelefon',
                            width:300
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
                var form = Ext.getCmp('userDetailsDialogForm').getForm();


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('userDetailsDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = 'KorisnikDetailsUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Korisnik.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('userDetailsDialogWindow').close();

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
        
        populateFields:function(){
                var waitBox = Common.loadingBox(Ext.getCmp('userDetailsDialogWindow'), Lang.Loading);
                waitBox.show();

                Ext.Ajax.request({
                    timeout: Common.Timeout,
                    url: '../App/Controllers/Korisnik.php',
                    params: { action: 'KorisnikDetailsLoad'},
                    success: function (response, request) {
                        var data = Ext.decode(response.responseText).data;

                        var form = Ext.getCmp('userDetailsDialogForm').getForm();
                        form.setValues(data);



                        waitBox.hide();
                    },
                    failure: function (response, request) {
                        waitBox.hide();
                    }
                });                
        }
});








