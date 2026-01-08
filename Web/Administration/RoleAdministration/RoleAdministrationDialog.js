Ext.define('Mediaplan.administration.roleAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.roleadministrationdialog',

	title: Lang.Administration_roleAdministration_dialog_Title,
        id:'roleAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'user_group',
	modal: true,
	width: 650,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnRoleID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };
            var window = this;
            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 5,
                    id: 'roleAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnRoleID',
                            name: 'rolaID',
                            value: '-1'
                    },{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_roleAdministration_dialog_fldSet_GeneralData,
                            items:[{
                                xtype:'textfield',
                                fieldLabel:Lang.Administration_roleAdministration_dialog_Name,
                                width:400,
                                allowblank:false,
                                name:'naziv'
                            },{
                                xtype:'textarea',
                                fieldLabel:Lang.Administration_roleAdministration_dialog_Description,
                                width:600,
                                height:100,
                                name:'opis'
                            }]
                    },{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_userAdministration_dialog_fldSet_PermissionsList,
                            padding:10,
                            items:[{
                                xtype: 'checkboxlist',
                                id: 'cblRoleAdministrationDialogPermisssionList',
                                fieldLabel:'Role',
                                storeUrl: '../App/Controllers/Rola.php',
                                storeAction: 'PermisijaGetForComboBox',
                                storeAutoLoad: true,
                                textHeader: Lang.Administration_userAdministration_dialog_PermissionsList,
                                groupingEnabled: false,
                                listHeight: 200,
                                width: 600 
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
                var form = Ext.getCmp('roleAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnRoleID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('roleAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'RolaInsertUpdate' : 'RolaInsertUpdate';

                    var fieldValues = form.getValues();
                    fieldValues.permisijaList = Ext.getCmp('cblRoleAdministrationDialogPermisssionList').getSelectedValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Rola.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('roleAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('roleAdministrationGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('roleAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Rola.php',
                        params: { action: 'RolaLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('roleAdministrationDialogForm').getForm();
                            form.setValues(data);




                           // alert(data.permisijaList);


                            Ext.getCmp('cblRoleAdministrationDialogPermisssionList').selectItems(data.permisijaList);


                            waitBox.hide();
                        },
                        failure: function (response, request) {
                            waitBox.hide();
                        }
                    });
                }
        }
});



