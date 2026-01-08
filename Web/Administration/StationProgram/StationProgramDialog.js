Ext.define('Mediaplan.administration.StationProgram.Dialog', {
    extend: 'Ext.window.Window',
    alias: 'widget.stationprogramdialog',
    title: Lang.Administration_stationProgram_dialog_Title,
    id: 'stationProgramDialogWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 400,
    autoHeight: true,
    initComponent: function()
    {
        this.listeners = {
            show: function() {

                if (typeof this.entryID != 'undefined') {
                    Ext.getCmp('hdnStationProgramID').setValue(this.entryID);
                    this.populateFields(this.entryID);
                }
            }

        };


        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'stationProgramDialogForm',
                border: false,
                frame: false,
                labelWidth: 140,
                items: [{
                        xtype: 'hidden',
                        id: 'hdnStationProgramID',
                        name: 'radioStanicaProgramID',
                        value: '-1'
                    }, {
                        xtype: 'combobox',
                        fieldLabel: Lang.Administration_stationProgram_dialog_Station,
						labelWidth: 130,
                        store: Ext.create('Ext.data.Store', {
                            fields: ['EntryID', 'EntryName'],
                            proxy: {
                                type: 'ajax',
                                url: '../App/Controllers/RadioStanica.php',
                                actionMethods: {
                                    read: 'POST'
                                },
                                extraParams: {
                                    action: 'RadioStanicaGetForComboBox'
                                },
                                reader: {
                                    type: 'json',
                                    root: 'rows'
                                }
                            }
                        }),
                        id: 'stationProgramDialogStationCombo',
                        name: 'radioStanicaID',
                        queryMode: 'remote',
                        typeAhead: true,
                        queryParam: 'filter',
                        emptyText: Lang.Common_combobox_emptyText,
                        valueField: 'EntryID',
                        displayField: 'EntryName',
                        allowBlank: false,
                        width: 320
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.Administration_stationProgram_dialog_Name,
						labelWidth: 130,
                        name: 'naziv',
                        width: 320
                    }, {
                        xtype: 'timefield',
                        fieldLabel: Lang.Administration_stationProgram_dialog_StartTime,
						labelWidth: 130,
                        name: 'pocetakEmitovanja',
                        allowBlank: false,
                        format: 'H:i',
                        minValue: '6:00 AM',
                        maxValue: '11:59 PM',
                        increment: 30,
                        width: 220
                    }, {
                        xtype: 'timefield',
                        fieldLabel: Lang.Administration_stationProgram_dialog_EndTime,
						labelWidth: 130,
                        name: 'krajEmitovanja',
                        allowBlank: false,
                        format: 'H:i',
                        minValue: '6:00 AM',
                        maxValue: '11:59 PM',
                        increment: 30,
                        width: 220
                    },{
						xtype:'radiogroup',
						fieldLabel:Lang.Administration_stationProgram_dialog_Workday,
						labelWidth: 130,
						columns: 2,
						width:300,
						vertical: true,
						items:[{
								boxLabel: 'Radni dan',
								name: 'radniDan',
								checked:true,
								value:true,
								inputValue: 'true'
						},{
								boxLabel: 'Vikend',
								name: 'radniDan',
								inputValue: 'false'                                            
						}]
					}]
            }];

        this.buttons = [
            {
                text: 'Save',
                icon: Icons.Save16,
                handler: function()
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
    saveData: function() {
        var form = Ext.getCmp('stationProgramDialogForm').getForm();
        var entryID = parseInt(form.findField('hdnStationProgramID').getValue());


        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('stationProgramDialogWindow').getEl(), Lang.Saving);
            waitBox.show();

            var formAction = (entryID === -1) ? 'RadioStanicaProgramInsertUpdate' : 'RadioStanicaProgramInsertUpdate';

            var fieldValues = form.getValues();

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/RadioStanicaProgram.php',
                params: {action: formAction, fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {
                        Ext.getCmp('stationProgramDialogWindow').close();

                        //rebind grid
                        var grid = Ext.getCmp('stationProgramGrid');
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
    populateFields: function(entryID) {
        var waitBox = Common.loadingBox(Ext.getCmp('stationProgramDialogWindow'), Lang.Loading);
        waitBox.show();
        if (entryID === -1) {
            waitBox.hide();
        }
        else {

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/RadioStanicaProgram.php',
                params: {action: 'RadioStanicaProgramLoad', entryID: entryID},
                success: function(response, request) {
                    var data = Ext.decode(response.responseText).data;

                    var form = Ext.getCmp('stationProgramDialogForm').getForm();
                    form.setValues(data);

                    Ext.getCmp('stationProgramDialogStationCombo').store.load({callback: function(r, options, success) {
                            Ext.getCmp('stationProgramDialogStationCombo').setValue(data.radioStanicaID);
                        }
                    });


                    waitBox.hide();
                },
                failure: function(response, request) {
                    waitBox.hide();
                }
            });
        }
    }
});






