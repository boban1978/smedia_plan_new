Ext.define('Mediaplan.mediaPlan.scheduler.Dialog', {
    extend: 'Ext.window.Window',
    alias: 'widget.scheduleraddmovedialog',
    title: '',
    id: 'schedulerDialogWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 500,
    height: 270,
    initComponent: function()
    {

        this.listeners = {
            show: function() {
                var campaigneID = Ext.getCmp('clientDetailsCampaignePreviewWindow').campaigneID;

                Ext.getCmp('hdnCampaigneID').setValue(campaigneID);
                var spot = Ext.getCmp('blockMoveSpot');
                spot.store.proxy.extraParams.campaigneID = campaigneID;

/*
                if (typeof this.blockID != 'undefined') {*/
                    Ext.getCmp('hdnBlockUID').setValue(this.blockID);
                    Ext.getCmp('hdnBlockDate').setValue(this.blockDate);
                    var spotId = this.spotID
                    spot.store.load({callback: function(r, options, success) {
                            spot.setValue(spotId);
                        }
                    });
                    this.setTitle('Promena rasporeda emitovanja');
               /* } else {
                    this.setTitle('Dodavanje novog emitovanja');
                }*/
            }

        };


        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'schedulerDialogForm',
                border: false,
                frame: false,
                labelWidth: 180,
                items: [{
                        xtype: 'hidden',
                        id: 'hdnBlockUID',
                        name: 'blokID',
                        value: '-1'
                    }, {
                        xtype: 'hidden',
                        id: 'hdnBlockDate',
                        name: 'datumBloka',
                        value: '-1'
                    }, {
                        xtype: 'hidden',
                        id: 'hdnCampaigneID',
                        name: 'campaigneID',
                        value: '-1'
                    },



                    {
                        xtype: 'combobox',
                        fieldLabel: 'Spot',
                        id: 'blockMoveSpot',
                        labelWidth: 150,
                        store: Ext.create('Ext.data.Store', {
                            fields: ['EntryID', 'EntryName'],
                            proxy: {
                                type: 'ajax',
                                url: '../App/Controllers/Kampanja.php',
                                actionMethods: {
                                    read: 'POST'
                                },
                                extraParams: {
                                    action: 'GetSpotsForCampaigne',
                                    campaigneID: ''
                                },
                                reader: {
                                    type: 'json',
                                    root: 'rows'
                                }
                            }
                        }),
                        name: 'spotID',
                        queryMode: 'remote',
                        typeAhead: true,
                        queryParam: 'filter',
                        emptyText: Lang.Common_combobox_emptyText,
                        valueField: 'EntryID',
                        displayField: 'EntryName',
                        allowBlank: false,
                        width: 350
                    }, {
                        xtype: 'datefield',
                        labelWidth: 150,
                        fieldLabel: 'Datum emitovanja',
                        format: 'Y-m-d',
                        width: 300,
                        allowBlank: false,
                        name: 'datumEmitovanja'
                    }, {
                        xtype: 'timefield',
                        labelWidth: 150,
                        fieldLabel: 'Sat u kome se emituje',
                        name: 'sat',
                        minValue: '6:00',
                        maxValue: '23:00',
                        increment: 60,
                        format: 'H:i',
                        allowBlank: false,
                        width: 300
                    }, {
                        xtype: 'radiogroup',
                        labelWidth: 150,
                        fieldLabel: 'Blok',
                        columns: 2,
                        width: 400,
                        vertical: true,
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Blok 1',
                                name: 'blok',
                                inputValue: '1'
                            }, {
                                boxLabel: 'Blok 2',
                                name: 'blok',
                                inputValue: '2'
                            }, {
                                boxLabel: 'Blok 3',
                                name: 'blok',
                                inputValue: '3'
                            }, {
                                boxLabel: 'Blok 4',
                                name: 'blok',
                                inputValue: '4'
                            }]
                    }, {
                        xtype: 'radiogroup',
                        labelWidth: 150,
                        fieldLabel: 'Pozicija u bloku',
                        columns: 2,
                        width: 400,
                        vertical: true,
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Prvo mesto',
                                name: 'pozicija',
                                inputValue: '1'
                            }, {
                                boxLabel: 'Drugo mesto',
                                name: 'pozicija',
                                inputValue: '2'
                            }, {
                                boxLabel: 'Ostala mesta',
                                name: 'pozicija',
                                inputValue: '3'
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
        var form = Ext.getCmp('schedulerDialogForm').getForm();
        var entryID = parseInt(form.findField('hdnBlockUID').getValue());


        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('schedulerDialogWindow').getEl(), Lang.DataProcessing);
            waitBox.show();

            //var formAction = (entryID === -1) ? 'DodajBlok' : 'PremestiBlok';
            var formAction;
            if (entryID === -1) {
                if (Ext.getCmp('weekTemplatesCampaigneForm')) {
                    formAction = 'DodajBlokNedeljniSablon'
                } else {
                    formAction = 'DodajBlok'
                }
            } else {
                formAction = 'PremestiBlok'
            }
            var fieldValues = form.getValues();


            if (formAction == 'PremestiBlok') {

                var offersCount = Ext.getCmp('clientDetailsCampaignePreviewWindow').offersCount;
                var offerNo = Ext.getCmp('clientDetailsCampaignePreviewWindow').offerNo;

                fieldValues.offersCount = offersCount;
                fieldValues.offerNo = offerNo;

            }

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Kampanja.php',
                params: {action: formAction, fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {


                        Ext.getCmp('schedulerDialogWindow').close();

                        var data = Ext.decode(response.responseText).data;


                        if(offersCount!=2) {

                            var schedulerConfig = Common.schConfig;
                            schedulerConfig.showContextmenu = true;
                            schedulerConfig.commercials = data.schedulerCommercial;
                            schedulerConfig.dates = data.schedulerDates;
                            var campaignePrice = data.capmaignePrice;
                            var campaigneId = data.campaigneID;

                            var sablonId = data.sablonId;

                            Ext.getCmp('clientDetailsCampaignePreviewWindow').close();
                            var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindow', {
                                tbar: Ext.create('Ext.toolbar.Toolbar', {
                                    items: [{
                                        text: Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                        iconCls: 'scheduler-add',
                                        handler: function() {
                                            Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                        }
                                    }]
                                }),
                                height: 575,
                                showTbar: true,
                                config: schedulerConfig,
                                campaigneID: campaigneId,
                                price: campaignePrice,
                                sablonId: sablonId
                            });




                        }else {//dva predloga


                            var schedulerConfig = Common.schConfig;
                            schedulerConfig.showContextmenu = true;
                            schedulerConfig.commercials = data.schedulerCommercial;
                            schedulerConfig.dates = data.schedulerDates;
                            schedulerConfig.actionType = 'campaigne';


                            var schedulerConfig2 = Common.schConfig2;
                            schedulerConfig2.showContextmenu = true;
                            schedulerConfig2.commercials = data.schedulerCommercial2;
                            schedulerConfig2.dates = data.schedulerDates2;
                            schedulerConfig2.actionType = 'campaigne';


                            var campaignePrice = data.capmaignePrice;
                            var campaignePrice2 = data.capmaignePrice2;
                            var campaigneId = data.campaigneID;
                            Ext.getCmp('clientDetailsCampaignePreviewWindow').close();
                            var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindowtwooffers', {
                                //tbar: Ext.create('Ext.toolbar.Toolbar',{
                                // items:[{
                                //text:Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                // iconCls:'scheduler-add',
                                // handler:function(){
                                // Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                // }
                                // }]
                                // }),
                                showTbar: true,
                                height: 575,
                                config: schedulerConfig,
                                config2: schedulerConfig2,
                                campaigneID: campaigneId,
                                price: campaignePrice,
                                price2: campaignePrice2,
                                offerNo:offerNo
                            });

                            //Ext.getCmp('tabsss').setActiveTab( (offerNo-1) );


                        }


                        if (data.campaigneID != '') {
                            Ext.getCmp('campaignePreviewBtnNo').hide();
                            Ext.getCmp('campaignePreviewBtnYes').hide();
                        }
                        ;


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


//Ext.getCmp('clientDetailsCampaignePreviewWindow').spotID_add

Ext.define('Mediaplan.mediaPlan.scheduler.ShortDialog', {
    extend: 'Ext.window.Window',
    alias: 'widget.scheduleradddialog',
    title: 'Dodavanje emitovanja (parametri)',
    id: 'schedulerAddDialogWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 500,
    height: 180,
    initComponent: function()
    {
        this.listeners = {
            show: function() {

                var spotID_add = parseInt(Ext.getCmp('clientDetailsCampaignePreviewWindow').spotID_add);
                var pozicija_add = parseInt(Ext.getCmp('clientDetailsCampaignePreviewWindow').pozicija_add);

                Ext.getCmp('blockMoveSpot').getStore().load();
                Ext.getCmp('blockMoveSpot').setValue(spotID_add);

                if(pozicija_add>0){
                    Ext.getCmp('pozicija').items.items[pozicija_add - 1].setValue(true);
                }
            }


        };


        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'schedulerAddDialogForm',
                border: false,
                frame: false,
                labelWidth: 180,
                items: [
/*
                    {
                        xtype: 'hidden',
                        id: 'hdnDvaPredloga',
                        name: 'dvaPredloga',
                        value: '-1'
                    },
                    {
                        xtype: 'hidden',
                        id: 'hdnPredlogBr',
                        name: 'predlogBr',
                        value: '-1'
                    },
*/



                    {
                        xtype: 'hidden',
                        id: 'hdnBlockUID',
                        name: 'blokID',
                        value: this.blockID
                    }, {
                        xtype: 'hidden',
                        id: 'hdnBlockDate',
                        name: 'datumEmitovanja',
                        value: this.blockDate
                    }, {
                        xtype: 'hidden',
                        id: 'hdnCampaigneID',
                        name: 'campaigneID',
                        value: this.campaigneID
                    }, {
                        xtype: 'combobox',
                        fieldLabel: 'Spot',
                        id: 'blockMoveSpot',
                        labelWidth: 150,
                        store: Ext.create('Ext.data.Store', {
                            fields: ['EntryID', 'EntryName'],
                            proxy: {
                                type: 'ajax',
                                url: '../App/Controllers/Kampanja.php',
                                actionMethods: {
                                    read: 'POST'
                                },
                                extraParams: {
                                    action: 'GetSpotsForCampaigne',
                                    campaigneID: this.campaigneID
                                },
                                reader: {
                                    type: 'json',
                                    root: 'rows'
                                }
                            }
                        }),
                        name: 'spotID',
                        queryMode: 'remote',
                        typeAhead: true,
                        queryParam: 'filter',
                        emptyText: Lang.Common_combobox_emptyText,
                        valueField: 'EntryID',
                        displayField: 'EntryName',
                        allowBlank: false,
                        width: 350/*,

                        listeners: {
                            load: function () {
                                //this sets the default value to USA after the store loads
                                var combo = Ext.getCmp('blockMoveSpot');
                                alert(combo.getValue());
                            }
                        }*/




                    }, {
                        xtype: 'radiogroup',
                        id: 'pozicija',
                        labelWidth: 150,
                        fieldLabel: 'Pozicija u bloku',
                        columns: 2,
                        width: 400,
                        vertical: true,
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Prvo mesto',
                                name: 'pozicija',
                                inputValue: '1'
                            }, {
                                boxLabel: 'Drugo mesto',
                                name: 'pozicija',
                                inputValue: '2'
                            }, {
                                boxLabel: 'Ostala mesta',
                                name: 'pozicija',
                                inputValue: '3'
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
        var form = Ext.getCmp('schedulerAddDialogForm').getForm();
        var entryID = parseInt(form.findField('hdnBlockUID').getValue());



        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('schedulerAddDialogWindow').getEl(), Lang.DataProcessing);
            waitBox.show();

            var fieldValues = form.getValues();

            var offersCount = Ext.getCmp('clientDetailsCampaignePreviewWindow').offersCount;
            var offerNo = Ext.getCmp('clientDetailsCampaignePreviewWindow').offerNo;

            fieldValues.offersCount=offersCount;
            fieldValues.offerNo=offerNo;


/*
            alert(Ext.getCmp('clientDetailsCampaignePreviewWindow').blokID_add);
            alert(Ext.getCmp('clientDetailsCampaignePreviewWindow').datumEmitovanja_add);
            alert(Ext.getCmp('clientDetailsCampaignePreviewWindow').campaigneID_add);
            alert(Ext.getCmp('clientDetailsCampaignePreviewWindow').spotID_add);
            alert(Ext.getCmp('clientDetailsCampaignePreviewWindow').pozicija_add);
*/

            /*
            Ext.getCmp('clientDetailsCampaignePreviewWindow').blokID_add=fieldValues.blokID;
            Ext.getCmp('clientDetailsCampaignePreviewWindow').datumEmitovanja_add=fieldValues.datumEmitovanja;
            Ext.getCmp('clientDetailsCampaignePreviewWindow').campaigneID_add=fieldValues.campaigneID;*/
            Ext.getCmp('clientDetailsCampaignePreviewWindow').spotID_add=fieldValues.spotID;
            Ext.getCmp('clientDetailsCampaignePreviewWindow').pozicija_add=fieldValues.pozicija;


            waitBox.hide();
            Ext.getCmp('schedulerAddDialogWindow').close();

        }
    },







    saveData_oldddddddd: function() {
        var form = Ext.getCmp('schedulerAddDialogForm').getForm();
        var entryID = parseInt(form.findField('hdnBlockUID').getValue());



        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('schedulerAddDialogWindow').getEl(), Lang.DataProcessing);
            waitBox.show();

            var fieldValues = form.getValues();

            var offersCount = Ext.getCmp('clientDetailsCampaignePreviewWindow').offersCount;
            var offerNo = Ext.getCmp('clientDetailsCampaignePreviewWindow').offerNo;

            fieldValues.offersCount=offersCount;
            fieldValues.offerNo=offerNo;

            //alert_obj_boban(fieldValues);


            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Kampanja.php',
                params: {action: 'DodajBlok', fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {

                        Ext.getCmp('schedulerAddDialogWindow').close();

                        //rebind scheduler component
                        var data = Ext.decode(response.responseText).data;

                        //alert_obj_boban(data);

                        //alert("kampanja_id: "+data.campaigneID);


                        if(offersCount!=2) {


                            var schedulerConfig = Common.schConfig;
                            schedulerConfig.showContextmenu = true;
                            schedulerConfig.commercials = data.schedulerCommercial;
                            schedulerConfig.dates = data.schedulerDates;
                            var campaignePrice = data.capmaignePrice;
                            var campaigneId = data.campaigneID;

                            var sablonId = data.sablonId;

                            Ext.getCmp('clientDetailsCampaignePreviewWindow').close();
                            var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindow', {
                                tbar: Ext.create('Ext.toolbar.Toolbar', {
                                    items: [{
                                        text: Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                        iconCls: 'scheduler-add',
                                        handler: function () {
                                            Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                        }
                                    }]
                                }),
                                height: 575,
                                showTbar: true,
                                config: schedulerConfig,
                                campaigneID: campaigneId,
                                price: campaignePrice,
                                sablonId: sablonId
                            });
                        }else {//dva predloga


                            var schedulerConfig = Common.schConfig;
                            schedulerConfig.showContextmenu = true;
                            schedulerConfig.commercials = data.schedulerCommercial;
                            schedulerConfig.dates = data.schedulerDates;
                            schedulerConfig.actionType = 'campaigne';


                            var schedulerConfig2 = Common.schConfig2;
                            schedulerConfig2.showContextmenu = true;
                            schedulerConfig2.commercials = data.schedulerCommercial2;
                            schedulerConfig2.dates = data.schedulerDates2;
                            schedulerConfig2.actionType = 'campaigne';


                            var campaignePrice = data.capmaignePrice;
                            var campaignePrice2 = data.capmaignePrice2;
                            var campaigneId = data.campaigneID;
                            Ext.getCmp('clientDetailsCampaignePreviewWindow').close();
                            var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindowtwooffers', {
                                //tbar: Ext.create('Ext.toolbar.Toolbar',{
                                // items:[{
                                //text:Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                // iconCls:'scheduler-add',
                                // handler:function(){
                                // Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                // }
                                // }]
                                // }),
                                showTbar: true,
                                height: 575,
                                config: schedulerConfig,
                                config2: schedulerConfig2,
                                campaigneID: campaigneId,
                                price: campaignePrice,
                                price2: campaignePrice2,
                                offerNo:offerNo
                            });

                            //Ext.getCmp('tabsss').setActiveTab( (offerNo-1) );




                        }




                        if (data.campaigneID != '') {
                            Ext.getCmp('campaignePreviewBtnNo').hide();
                            Ext.getCmp('campaignePreviewBtnYes').hide();
                        }
                        ;


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







