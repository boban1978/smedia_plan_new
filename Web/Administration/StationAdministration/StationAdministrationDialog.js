Ext.define('Mediaplan.administration.StationAdministration.Dialog', {
	extend: 'Ext.window.Window',
	alias: 'widget.stationadministrationdialog',

	title: Lang.Administration_stationAdministration_dialog_Title,
    id:'stationAdministrationDialogWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
	modal: true,
	width: 400,
	height: 170,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    
                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnStationID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    } 
                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'stationAdministrationDialogForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    fileUpload: true,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnStationID',
                            name: 'radioStanicaID',
                            value: '-1'
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.Administration_stationAdministration_dialog_Name,
                            name:'naziv',
                            width:300
                    },{
                            xtype:'textfield',
                            fieldLabel:Lang.Administration_stationAdministration_dialog_Address,
                            name:'adresa',
                            width:300
                    },

                        {
                            xtype:'textfield',
                            inputType:'file',
                            fieldLabel:'Logo',
                            name:'logo',
                            width:450
                        },



                        {
                            xtype:'radiogroup',
                            fieldLabel:Lang.Administration_stationAdministration_dialog_Active,
                            columns: 2,
                            width:200,
                            vertical: true,
                            id: 'stationAdministrationActive',
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
                var form = Ext.getCmp('stationAdministrationDialogForm').getForm();
                var entryID = parseInt(form.findField('hdnStationID').getValue());


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('stationAdministrationDialogWindow').getEl(), Lang.Saving);
                    waitBox.show();





                    form.submit({
                        url: '../App/Controllers/RadioStanica.php?action=RadioStanicaInsertUpdate',
                        success: function (fp,o) {
                            waitBox.hide();


                            //alert_obj_boban(o);


                            Ext.getCmp('stationAdministrationDialogWindow').close();

                            //rebind grid
                            var grid = Ext.getCmp('stationAdministrationGrid');
                            grid.reloadGrid(grid);







                        },
                        failure: function (fp, o) {
                            waitBox.hide();
                            alert('Greška u obradi zahteva');
                        }

                    });








                    /*
                    var formAction = (entryID === -1) ? 'RadioStanicaInsertUpdate' : 'RadioStanicaInsertUpdate';
                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/RadioStanica.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('stationAdministrationDialogWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('stationAdministrationGrid');
                                grid.reloadGrid(grid);


                                alert_obj_boban(response);



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
                    */


                }
        },
        
        populateFields:function(entryID){
                var waitBox = Common.loadingBox(Ext.getCmp('stationAdministrationDialogWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/RadioStanica.php',
                        params: { action: 'RadioStanicaLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('stationAdministrationDialogForm').getForm();
                            form.setValues(data);


                            Ext.getCmp('stationAdministrationActive').items.each(function(item) {
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






