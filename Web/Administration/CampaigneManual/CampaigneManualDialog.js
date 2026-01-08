Ext.define('Mediaplan.administration.campaigneManual.CampaignesWindow', {
	extend: 'Ext.window.Window',
    alias: 'widget.manualcampaigneswindow',
    title: Lang.Administration_campaigneManual_dialog_Title,
    id:'manualCampaigneWindow',

	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
    spotCounter: 2,
	modal: true,
	width: 750,

	height:550,
        initComponent: function ()
	{
            
            var window = this;




            this.listeners = {
                show: function() {


                    var clientID = Ext.getCmp('hdnClientDetailsID').getValue();

                    Ext.getCmp('hdnCampaigneClientID').setValue(clientID);

                    var BrendCombo = Ext.getCmp('campaigneManualBrendID');
                    BrendCombo.store.proxy.extraParams.clientID = clientID;

                    BrendCombo.store.removeAll();
                    BrendCombo.lastQuery = null;

                    BrendCombo.store.load();
                    BrendCombo.setValue('');

                    //alert(clientID);


                    /*
                    Ext.getCmp('hdnCampaigneClientID').setValue(clientID);
                    if (typeof this.offerID != 'undefined') {
                        Ext.getCmp('hdnCampaigneOfferID').setValue(this.offerID);
                    }
                    ;*/
                }

            };



            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'manualCampaigneForm',

                    border: false,
                    frame: false,
                    labelWidth: 120,
                    fileUpload: true,
                    autoScroll: true,
                    items:[{
                        xtype: 'fieldset',
                        border: true,
                        title: Lang.Administration_campaigneManual__dialog_fldSet_CampaigneData,
                        //collapsible: true,
                        items: [


                            {
                                xtype: 'hidden',
                                name: 'spotBroj',
                                id: 'hdnSpotCountID',
                                value: 1
                            },

                            {
                            xtype: 'textfield',
                            fieldLabel: Lang.Administration_campaigneManual__dialog_Name,
                            name: 'naziv',
                            allowBlank: false,
                            width: 550
                        }, {
                            xtype: 'combobox',
                            fieldLabel: Lang.Administration_campaigneManual_dialog_Station,
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
                            listeners: {
                                select: {
                                    fn: function(combo, records, eOpts) {

                                        var station = Ext.getCmp('radioStanicaID').getValue();

                                        Ext.Ajax.request({
                                            url: '../App/Controllers/Common.php',
                                            method: 'POST',
                                            params: {
                                                radioStanicaID: station,
                                                action: 'getSpotsAutocomplete'
                                            },
                                            success: function(response){
                                                var rows = Ext.decode(response.responseText).rows;
                                                customarray = new Array();
                                                rows.forEach(function(entry) {
                                                    customarray.push(entry);
                                                });

                                                var spotBroj = Ext.getCmp('hdnSpotCountID').getValue();
                                                for (var i = 1; i <= spotBroj; i++) {
                                                    actb_curr = document.getElementsByName("spot" + i + "naziv")[0];
                                                    actb_curr.value = "";
                                                    /*if (window.autocomplete == 1) {
                                                     window.autocomplete=0;*/



                                                    var obj = actb(actb_curr, customarray);


                                                    /*}*/

                                                }

                                            }
                                        });



                                    }
                                }
                            },
                            name: 'radioStanicaID',
                            id: 'radioStanicaID',
                            queryMode: 'remote',
                            typeAhead: true,
                            queryParam: 'filter',
                            emptyText: Lang.Common_combobox_emptyText,
                            valueField: 'EntryID',
                            displayField: 'EntryName',
                            allowBlank: false,
                            width: 550
                        },





                        /*

                            {
                            xtype: 'combobox',
                            fieldLabel: Lang.Administration_campaigneManual_dialog_Client,
                            id: 'campaigneManualClientID',
                            name: 'klijentID',
                            store: new Ext.data.Store({
                                fields: ['EntryID', 'EntryName'],
                                proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Klijent.php',
                                    actionMethods: {
                                        read: 'POST'
                                    },
                                    extraParams: {
                                        action: 'KlijentGetForComboBox'
                                    },
                                    reader: {
                                        type: 'json',
                                        root: 'rows'
                                    }
                                }
                            }),
                            queryMode: 'remote',
                            typeAhead: true,
                            queryParam: 'filter',
                            emptyText: Lang.Common_combobox_emptyText,
                            valueField: 'EntryID',
                            displayField: 'EntryName',
                            width: 350,
                            listeners: {
                                select: {
                                    fn: function (store, records, successful, eOpts) {
                                        var BrendCombo = Ext.getCmp('campaigneManualBrendID');
                                        BrendCombo.store.proxy.extraParams.clientID = Ext.getCmp('campaigneManualClientID').getValue();

                                        BrendCombo.store.removeAll();
                                        BrendCombo.lastQuery = null;

                                        BrendCombo.store.load();
                                        BrendCombo.setValue('');
                                    }
                                }
                            }
                        },

*/
                            {
                                xtype: 'hidden',
                                name: 'klijentID',
                                id: 'hdnCampaigneClientID'
                            },




                            {
                            xtype: 'combobox',
                                id: 'campaigneManualBrendID',
                            fieldLabel: Lang.Administration_campaigneManual_dialog_Activity,
                            store: Ext.create('Ext.data.Store', {
                                fields: ['EntryID', 'EntryName'],
                                proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Brend.php',
                                    actionMethods: {
                                        read: 'POST'
                                    },
                                    extraParams: {
                                        action: 'BrendGetForComboBox',
                                        clientID: 0
                                    },
                                    reader: {
                                        type: 'json',
                                        root: 'rows'
                                    }
                                }/*,
                                listeners: {
                                    beforeload: {
                                        fn: function (store, records, successful, eOpts) {
                                            store.proxy.extraParams.clientID = Ext.getCmp('campaigneManualClientID').getValue();
                                        }
                                    }
                                }*/
                            }),
                            name: 'brendID',
                            queryMode: 'remote',
                            typeAhead: true,
                            queryParam: 'filter',
                            //emptyText: Lang.Common_combobox_emptyText,
                            valueField: 'EntryID',
                            displayField: 'EntryName',
                            width: 350
                        }, {
                            xtype: 'combobox',
                            fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Agency,
                            store: Ext.create('Ext.data.Store', {
                                fields: ['EntryID', 'EntryName'],
                                proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Agencija.php',
                                    actionMethods: {
                                        read: 'POST'
                                    },
                                    extraParams: {
                                        action: 'AgencijaGetForComboBox'
                                    },
                                    reader: {
                                        type: 'json',
                                        root: 'rows'
                                    }
                                }
                            }),
                            name: 'agencijaID',
                            queryMode: 'remote',
                            typeAhead: true,
                            queryParam: 'filter',
                            emptyText: Lang.Common_combobox_emptyText,
                            valueField: 'EntryID',
                            displayField: 'EntryName',
                            allowBlank: false,
                            width: 350
                        },









                            {
                                xtype: 'combobox',
                                fieldLabel:'Tip Plaćanja',
                                //labelAlign: 'right',
                                store: new Ext.data.Store({
                                    fields: ['EntryID', 'EntryName'],
                                    proxy: {
                                        type: 'ajax',
                                        url: '../App/Controllers/TipPlacanja.php',
                                        actionMethods: {
                                            read: 'POST'
                                        },
                                        extraParams: {
                                            action: 'TipPlacanjaGetForComboBox'
                                        },
                                        reader: {
                                            type: 'json',
                                            root: 'rows'
                                        }
                                    }
                                }),
                                name:'tipPlacanjaID',
                                queryMode: 'remote',
                                typeAhead: true,
                                queryParam: 'filter',
                                emptyText: Lang.Common_combobox_emptyText,
                                valueField: 'EntryID',
                                displayField: 'EntryName',
                                allowBlank: false,
                                width: 350

                            },









                         /*{
                            xtype: 'combobox',
                            fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Voice,
                            store: Ext.create('Ext.data.Store', {
                                fields: ['EntryID', 'EntryName'],
                                proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Glas.php',
                                    actionMethods: {
                                        read: 'POST'
                                    },
                                    extraParams: {
                                        action: 'GlasGetForComboBox'
                                    },
                                    reader: {
                                        type: 'json',
                                        root: 'rows'
                                    }
                                }
                            }),
                            name: 'glasID',
                            queryMode: 'remote',
                            typeAhead: true,
                            queryParam: 'filter',
                            emptyText: Lang.Common_combobox_emptyText,
                            valueField: 'EntryID',
                            displayField: 'EntryName',
                            allowBlank: false,
                            width: 350
                        },*/ /*{
                            xtype: 'textfield',
                            fieldLabel: Lang.Administration_campaigneManual_dialog_Spot,
                            name: 'nazivSpot',
                            allowBlank: false,
                            width: 550
                        },*/ {
                            xtype: 'fieldcontainer',
                            layout: 'hbox',
                            labelAlign: 'left',
                            items: [{
                                xtype: 'datefield',
                                format: 'Y-m-d',
                                fieldLabel: Lang.Administration_campaigneManual_dialog_DateStart,
                                name: 'datumPocetka',
                                allowBlank: false,
                                width: 250
                            }, {
                                xtype: 'datefield',
                                labelAlign: 'right',
                                labelWidth: 150,
                                format: 'Y-m-d',
                                fieldLabel: Lang.Administration_campaigneManual_dialog_DateEnd,
                                name: 'datumKraja',
                                allowBlank: false,
                                width: 300
                            }]
                        }, /*{
                            xtype: 'fieldcontainer',
                            layout: 'hbox',
                            labelAlign: 'left',
                            items: [{
                                xtype: 'numberfield',
                                //labelWidth: 150,
                                fieldLabel: Lang.Administration_campaigneManual_dialog_SpotDuration,
                                name: 'spotTrajanje',
                                allowBlank: false,
                                value: 1,
                                minValue: 1,
                                maxValue: 1000,
                                width: 250
                            }]
                        },*/



                            {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                labelAlign: 'left',
                                items: [{
                                    xtype:'textarea',
                                    fieldLabel:"Ponuda (napomena)",
                                    name:'napomena',
                                    allowBlank: false,
                                    width:550,
                                    height:60,
                                    allowBlank: false
                                }]
                            },




                            {
                                xtype: 'fieldset',
                                title: 'Spot 1',
                                items: [{
                                    xtype: 'textfield',
                                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_SpotName,
                                    name: 'spot1naziv',
                                    allowBlank: false,
                                    width: 600,
                                    listeners: {
                                        'change': function(){
                                            var station = Ext.getCmp('radioStanicaID').getValue();
                                            if(station==null){
                                                actb_curr = document.getElementsByName("spot1naziv")[0];
                                                actb_curr.value="";
                                                alert("Morate izabrati Radio Stanicu !");
                                            }
                                        }
                                    }
                                }, {
                                    xtype: 'combobox',
                                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Voice,
                                    store: Ext.create('Ext.data.Store', {
                                        fields: ['EntryID', 'EntryName'],
                                        proxy: {
                                            type: 'ajax',
                                            url: '../App/Controllers/Glas.php',
                                            actionMethods: {
                                                read: 'POST'
                                            },
                                            extraParams: {
                                                action: 'GlasGetForComboBox'
                                            },
                                            reader: {
                                                type: 'json',
                                                root: 'rows'
                                            }
                                        }
                                    }),
                                    name: 'spot1glasID',
                                    queryMode: 'remote',
                                    typeAhead: true,
                                    queryParam: 'filter',
                                    emptyText: Lang.Common_combobox_emptyText,
                                    valueField: 'EntryID',
                                    displayField: 'EntryName',
                                    allowBlank: false,
                                    width: 350
                                }, {
                                    xtype: 'fieldcontainer',
                                    layout: 'hbox',
                                    labelAlign: 'left',
                                    items: [{
                                        xtype: 'numberfield',
                                        //labelAlign:'right',
                                        //labelWidth: 150,
                                        fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_SpotDuration,
                                        name: 'spot1trajanje',
                                        allowBlank: false,
                                        value: 1,
                                        minValue: 1,
                                        maxValue: 1000,
                                        width: 200
                                    }]
                                }]
                            }]
                    }]
            }]





                ,
                this.buttons = [{
                    text: Lang.MediaPlan_clients_details_campaignes_dialog_btn_AddSpot,
                    iconCls: 'form_add',
                    handler: function() {
                        window.addSpot();
                    }
                }, '-', {
                    text: Lang.MediaPlan_clients_details_campaignes_dialog_btn_Create,
                    iconCls: 'refresh',
                    handler: function() {
                        window.createCampaigne();
                    }
                }, '-', {
                    text: Lang.MediaPlan_clients_details_campaignes_dialog_btn_Cancel,
                    icon: Icons.Cancel16,
                    /*handler: function() {
                     window.clearData();
                     }*/
                    scope: this,
                    handler: this.close
                }];





            
                        
            this.callParent(arguments);
        },



        addSpot: function() {
            var window = Ext.getCmp('manualCampaigneWindow');
            var form = Ext.getCmp('manualCampaigneForm');
            form.add({
                xtype: 'fieldset',
                title: 'Spot ' + window.spotCounter,
                items: [{
                    xtype: 'textfield',
                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_SpotName,
                    name: 'spot' + window.spotCounter + 'naziv',
                    allowBlank: false,
                    width: 600,
                    listeners: {
                        'change': function(){
                            var station = Ext.getCmp('radioStanicaID').getValue();
                            if(station==null){
                                actb_curr = document.getElementsByName("spot"+window.spotCounter+"naziv")[0];
                                actb_curr.value="";
                                alert("Morate izabrati Radio Stanicu !");
                            }
                        }
                    }
                }, {
                    xtype: 'combobox',
                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Voice,
                    store: Ext.create('Ext.data.Store', {
                        fields: ['EntryID', 'EntryName'],
                        proxy: {
                            type: 'ajax',
                            url: '../App/Controllers/Glas.php',
                            actionMethods: {
                                read: 'POST'
                            },
                            extraParams: {
                                action: 'GlasGetForComboBox'
                            },
                            reader: {
                                type: 'json',
                                root: 'rows'
                            }
                        }
                    }),
                    name: 'spot' + window.spotCounter + 'glasID',
                    queryMode: 'remote',
                    typeAhead: true,
                    queryParam: 'filter',
                    emptyText: Lang.Common_combobox_emptyText,
                    valueField: 'EntryID',
                    displayField: 'EntryName',
                    allowBlank: false,
                    width: 350
                }, {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    labelAlign: 'left',
                    items: [{
                        xtype: 'numberfield',
                        //labelAlign:'right',
                        //labelWidth: 150,
                        fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_SpotDuration,
                        name: 'spot' + window.spotCounter + 'trajanje',
                        allowBlank: false,
                        value: 1,
                        minValue: 1,
                        maxValue: 1000,
                        width: 200
                    }]
                }]
            });

            var spotBroj = Ext.getCmp('hdnSpotCountID');
            var newSpotBroj = parseInt(spotBroj.getValue()) + 1;
            spotBroj.setValue(newSpotBroj);

            window.spotCounter = window.spotCounter + 1;


            actb_curr = document.getElementsByName("spot"+newSpotBroj+"naziv")[0];
            var obj = actb(actb_curr,customarray);






        },



        clearData: function(){
            Ext.getCmp('manualCampaigneForm').getForm().reset();
        },
        
        createCampaigne: function(){

                var form = Ext.getCmp('manualCampaigneForm').getForm();
                               
                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('manualCampaigneWindow').getEl(), Lang.CampaigneCreate);
                    waitBox.show();
                    form.submit({
                        timeout: 600000,
                        url: '../App/Controllers/Kampanja.php?action=KampanjaManualCreate',
                        success: function (form, response) {
                            waitBox.hide();
                            var data = response.result.data;
                            var schedulerConfig = Common.schConfig;
                            schedulerConfig.showContextmenu = true;
                            schedulerConfig.commercials = data.schedulerCommercial;
                            schedulerConfig.dates = data.schedulerDates;
                            var campaignePrice = data.capmaignePrice;
                            var popust = data.popust;
                            var spotBroj = data.spotBroj;

                            var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindow',{
                               tbar: Ext.create('Ext.toolbar.Toolbar',{
                                    items:[{
                                            text:Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                            iconCls:'scheduler-add',
                                            handler:function(){
                                                 Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                            }
                                    }]
                                }),
								showTbar: true,
                                height:575,
                                config:schedulerConfig,
                                price: campaignePrice,
                                popust: popust,
                                spotBroj: spotBroj
                            });

                        },
                        failure: function (fp, o) {
                            waitBox.hide();
                            alert('Greška u obradi zahteva');
                        }

                    });
                }
            
        }
        
        
});


