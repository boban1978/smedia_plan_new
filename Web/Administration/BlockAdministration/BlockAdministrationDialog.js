Ext.define('Mediaplan.administration.blockAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.blockadministrationdialog',

	title: Lang.Administration_blockAdministration_dialog_Title,
        id:'blockAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
	modal: true,
	width: 550,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnBlockID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'blockAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 150,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnBlockID',
                            name: 'blokID',
                            value: '-1'
                    },{
                            xtype: 'fieldcontainer',
                            layout: 'hbox',
                            labelAlign: 'left',
                            items: [{
                                  xtype:'numberfield',
                                  labelWidth: 130,
                                  fieldLabel:Lang.Administration_blockAdministration_dialog_Hour,
                                  name:'sat',
                                  allowBlank:false,
                                  value: 0,
                                  minValue: 0,
                                  maxValue: 23,
                                  width:270                                    
                            },{
                                  xtype:'numberfield',
                                  labelAlign:'right',
                                  labelWidth: 150,
                                  fieldLabel:Lang.Administration_blockAdministration_dialog_BlockOrderNumber,
                                  name:'redniBrojSat',
                                  allowBlank:false,
                                  value: 0,
                                  minValue: 0,
                                  maxValue: 100,
                                  width:200                                
                            }]
                    },{
                          xtype:'fieldset',
                          style: {
                                border: 0,
                                padding: 0
                          },
                          padding:0,
                          layout:'column',
                          items:[{
                                  xtype:'fieldset',
                                  border:false,
                                  width:180,
                                  style: {
                                    border: 0,
                                    padding: 0
                                  },
                                  padding:0,
                                  items:[{
                                      xtype:'numberfield',
                                      labelWidth: 130,
                                      fieldLabel:Lang.Administration_blockAdministration_dialog_TimeStart,
                                      name:'vremeStartMin',
                                      allowBlank:false,
                                      value: 0,
                                      minValue: 0,
                                      maxValue: 60,
                                      width:180
                                  }]
                          },{
                                  xtype:'fieldset',
                                  border:false,
                                  width:70,
                                  style: {
                                    border: 0,
                                    padding: 0
                                  },
                                  padding:0,
                                  items:[{
                                      xtype:'numberfield',
                                      labelWidth:2,
                                      fieldLabel:' ',
                                      name:'vremeStartSec',
                                      allowBlank:false,
                                      value: 0,
                                      minValue: 0,
                                      maxValue: 60,
                                      width:50
                                  }]
                          }]
                    },{
                          xtype:'fieldset',
                          style: {
                                border: 0,
                                padding: 0
                          },
                          padding:0,
                          layout:'column',
                          items:[{
                                  xtype:'fieldset',
                                  border:false,
                                  width:180,
                                  style: {
                                    border: 0,
                                    padding: 0
                                  },
                                  padding:0,
                                  items:[{
                                      xtype:'numberfield',
                                      labelWidth: 130,
                                      fieldLabel:Lang.Administration_blockAdministration_dialog_TimeEnd,
                                      name:'vremeEndMin',
                                      allowBlank:false,
                                      value: 0,
                                      minValue: 0,
                                      maxValue: 60,
                                      width:180
                                  }]
                          },{
                                  xtype:'fieldset',
                                  border:false,
                                  width:70,
                                  style: {
                                    border: 0,
                                    padding: 0
                                  },
                                  padding:0,
                                  items:[{
                                      xtype:'numberfield',
                                      labelWidth:2,
                                      fieldLabel:' ',
                                      name:'vremeEndSec',
                                      allowBlank:false,
                                      value: 0,
                                      minValue: 0,
                                      maxValue: 60,
                                      width:50
                                  }]
                          }]                                                      
                    },{
                            xtype: 'fieldcontainer',
                            layout: 'hbox',
                            labelAlign: 'left',
                            items: [{
                                    xtype:'radiogroup',
                                    labelWidth: 130,
                                    fieldLabel:Lang.Administration_blockAdministration_dialog_Type,
                                    columns: 2,
                                    width:270,
                                    vertical: true,
                                    items:[{
                                            boxLabel: Lang.Administration_blockAdministration_dialog_Type_Option1,
                                            name: 'vrsta',
                                            checked:true,
                                            inputValue: 0
                                    },{
                                            boxLabel: Lang.Administration_blockAdministration_dialog_Type_Option2,
                                            name: 'vrsta',
                                            inputValue: 1                                            
                                    }]                                     
                            },{
                                  xtype:'numberfield',
                                  labelAlign:'right',
                                  labelWidth: 150,
                                  fieldLabel:Lang.Administration_blockAdministration_dialog_Duration,
                                  name:'trajanje',
                                  allowBlank:false,
                                  value: 0,
                                  minValue: 0,
                                  maxValue: 1000,
                                  width:240                              
                            }]                        
                    },{
                            xtype:'radiogroup',
                            labelWidth: 130,
                            fieldLabel:Lang.Administration_blockAdministration_dialog_Active,
                            columns: 2,
                            width:240,
                            vertical: true,
                            items:[{
                                    boxLabel: Lang.Common_Yes,
                                    name: 'aktivan',
                                    checked:true,
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
                var form = Ext.getCmp('blockAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnBlockID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('blockAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = (entryID === -1) ? 'BlokInsertUpdate' : 'BlokInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Blok.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('blockAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('blockAdministrationGrid');
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
                var waitBox = Common.loadingBox(Ext.getCmp('blockAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Blok.php',
                        params: { action: 'BlokLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('blockAdministrationDialogForm').getForm();
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






