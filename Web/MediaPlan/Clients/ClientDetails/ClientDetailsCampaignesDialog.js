

Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaigneswindow',
    title: Lang.MediaPlan_clients_details_campaignes_dialog_Title,
    id: 'clientDetailsCampaigneWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    spotCounter: 2,
    modal: true,
    width: 950,
    height: 550,
    autocomplete:1,
    initComponent: function()
    {

        var window = this;

        this.listeners = {
            show: function() {


                var clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                Ext.getCmp('hdnCampaigneClientID').setValue(clientID);
                if (typeof this.offerID != 'undefined') {
                    Ext.getCmp('hdnCampaigneOfferID').setValue(this.offerID);
                }
                ;
            }

        };

        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'clientDetailsCampaigneForm',
                border: false,
                frame: false,
                labelWidth: 120,
                fileUpload: true,
                autoScroll: true,
                items: [{
                        xtype: 'hidden',
                        name: 'klijentID',
                        id: 'hdnCampaigneClientID'
                    }, {
                        xtype: 'hidden',
                        name: 'ponudaID',
                        id: 'hdnCampaigneOfferID',
                        value: '-1'
                    }, {
                        xtype: 'hidden',
                        name: 'spotBroj',
                        id: 'hdnSpotCountID',
                        value: 1
                    }, {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelAlign: 'left',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Name,
                                name: 'naziv',
                                allowBlank: false,
                                width: 500
                            }, {
                                xtype: 'combobox',
                                fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Station,
                                labelAlign: 'right',
                                labelWidth: 100,
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
                                width: 350
                            }]
                    }, {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelAlign: 'left',
                        items: [{
                                xtype: 'datefield',
                                format: 'Y-m-d',
                                fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_DateStart,
                                name: 'datumPocetka',
                                allowBlank: false,
                                width: 250
                            }, {
                                xtype: 'datefield',
                                labelAlign: 'right',
                                labelWidth: 150,
                                format: 'Y-m-d',
                                fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_DateEnd,
                                name: 'datumKraja',
                                allowBlank: false,
                                width: 300
                            }, {
                                xtype: 'textfield',
                                labelAlign: 'right',
                                labelWidth: 150,
                                fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Budget,
                                name: 'budzet',
                                width: 300,
                                allowBlank: false
                            }]
                    }, {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelAlign: 'left',
                        items: [{
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
                            }, {
                                xtype: 'combobox',
                                fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Activity,
                                labelAlign: 'right',
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
                                            clientID: ''
                                        },
                                        reader: {
                                            type: 'json',
                                            root: 'rows'
                                        }
                                    },
                                    listeners: {
                                        beforeload: {
                                            fn: function(store, records, successful, eOpts) {
                                                store.proxy.extraParams.clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                                            }
                                        }
                                    }
                                }),
                                name: 'brendID',
                                queryMode: 'remote',
                                typeAhead: true,
                                queryParam: 'filter',
                                //emptyText: Lang.Common_combobox_emptyText,
                                valueField: 'EntryID',
                                displayField: 'EntryName',
                                width: 250
                            },



                            {
                                xtype: 'combobox',
                                fieldLabel:'Tip Plaćanja',
                                labelAlign: 'right',
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
                                width: 250

                            }


                        ]
                    },


                    {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelAlign: 'left',
                        items: [{
                            xtype:'textarea',
                            fieldLabel:"Ponuda (napomena)",
                            name:'napomena',
                            allowBlank: false,
                            width:900,
                            height:60,
                            allowBlank: false
                        }]
                    },


                    {
                        xtype: 'fieldset',
                        title: 'Spot 1',
                        items: [













/*
                            {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                labelAlign: 'left',
                                items: [*/


                                    {
                                        xtype: 'textfield',
                                        fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_SpotName,
                                        name: 'spot1naziv',
                                        id: 'spot1naziv',
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
                                    },



                                    {
                                        xtype: 'button',
                                        y:-26,
                                        x:610,
                                        height: 20,
                                        width: '20',
                                        autoEl: {tag: 'center'},
                                        text: 'Upload Spot',
                                        handler: function() {

                                            var station = Ext.getCmp('radioStanicaID').getValue();
                                            if(station==null){
                                                alert("Morate izabrati Radio Stanicu !");
                                                return;
                                            }


                                            Ext.create('Mediaplan.mediaPlan.clients.details.CampaigneDocumentWindow',{
                                                entryID:1,
                                                radioStanicaID:station
                                            });
                                        }
                                    },



/*
                                ]
                            },
*/
























                            {
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
                                items: [/*{
                                 xtype:'numberfield',
                                 fieldLabel:Lang.MediaPlan_clients_details_campaignes_dialog_Frequency,
                                 name:'spot1Ucestalost',
                                 allowBlank:false,
                                 value: 1,
                                 minValue: 1,
                                 maxValue: 100,
                                 width:200 
                                 },*/{
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
                                    }, {
                                        xtype: 'radiogroup',
                                        labelWidth: 60,
                                        fieldLabel: ' ',
                                        labelSeparator: '',
                                        columns: 3,
                                        width: 400,
                                        vertical: true,
                                        items: [{
                                                boxLabel: 'Samo obični',
                                                name: 'spot1premiumBlokovi',
                                                checked: true,
                                                inputValue: '1'
                                            }, {
                                                boxLabel: 'Samo premium',
                                                name: 'spot1premiumBlokovi',
                                                //checked:true,
                                                inputValue: '2'
                                            }, {
                                                boxLabel: 'Obični i premium',
                                                name: 'spot1premiumBlokovi',
                                                inputValue: '3'
                                            }]
                                    }/*{
                                     xtype:'checkbox',
                                     name:'spot1premiumBlokovi',
                                     labelWidth: 80,
                                     fieldLabel:' ',
                                     labelSeparator:'',
                                     inputValue:'1',
                                     checked: true,
                                     boxLabel:Lang.MediaPlan_clients_details_campaignes_dialog_PremiumBlocks
                                     }*/]
                            }, {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                labelAlign: 'left',
                                items: [{
                                        xtype: 'fieldset',
                                        title: 'Dani emitovanja spota',
                                        border: true,
                                        items: [{
                                                xtype: 'checkboxgroup',
                                                fieldLabel: 'Dani u nedelji kada se emituje spot',
                                                columns: 2,
                                                vertical: true,
                                                width: 500,
                                                height: 133,
                                                items: [
                                                    {boxLabel: 'Ponedeljak', name: 'spot1dan1', inputValue: '1', checked: true},
                                                    {boxLabel: 'Utorak', name: 'spot1dan2', inputValue: '2', checked: true},
                                                    {boxLabel: 'Sreda', name: 'spot1dan3', inputValue: '3', checked: true},
                                                    {boxLabel: 'Četvrtak', name: 'spot1dan4', inputValue: '4', checked: true},
                                                    {boxLabel: 'Petak', name: 'spot1dan5', inputValue: '5', checked: true},
                                                    {boxLabel: 'Subota', name: 'spot1dan6', inputValue: '6', checked: true},
                                                    {boxLabel: 'Nedelja', name: 'spot1dan7', inputValue: '7', checked: true}
                                                ]
                                            }]
                                    }, /*{
                                     xtype:'tbspacer',
                                     width:10
                                     },{
                                     xtype:'fieldset',
                                     border:true,
                                     height:140,
                                     items:[{
                                     xtype: 'checkboxgroup',
                                     fieldLabel: 'Period emitovanja',
                                     columns: 1,
                                     vertical: true,
                                     width:200,
                                     items: [
                                     { boxLabel: 'Od  6h do 8h', name: 'spot1period1', inputValue: '1',  margin:'5 0 0 0',checked: true },
                                     { boxLabel: 'Od  8h do 12h', name: 'spot1period2', inputValue: '2', margin:'10 0 0 0', checked: true },
                                     { boxLabel: 'Od 12h do 17h', name: 'spot1period3', inputValue: '3', margin:'10 0 0 0', checked: true },
                                     { boxLabel: 'Od 17h do 20h', name: 'spot1period4', inputValue: '4', margin:'10 0 0 0', checked: true },
                                     { boxLabel: 'Od 20h do 24h', name: 'spot1period5', inputValue: '5', margin:'10 0 0 0', checked: true }
                                     ] 
                                     }]
                                     },*/{
                                        xtype: 'tbspacer',
                                        width: 15
                                    }, {
                                        xtype: 'fieldset',
                                        border: true,
                                        title: 'Učestalost emitovanja spota',
                                        width: 300,
                                        height: 156,
                                        items: [{
                                                xtype: 'textfield',
                                                fieldLabel: 'Od  6h do 8h',
                                                labelWidth: 95,
                                                name: 'spot1Ucestalost1',
                                                width: 200
                                            }, {
                                                xtype: 'textfield',
                                                fieldLabel: 'Od  8h do 12h',
                                                labelWidth: 95,
                                                name: 'spot1Ucestalost2',
                                                width: 200
                                            }, {
                                                xtype: 'textfield',
                                                fieldLabel: 'Od 12h do 17h',
                                                labelWidth: 95,
                                                name: 'spot1Ucestalost3',
                                                width: 200
                                            }, {
                                                xtype: 'textfield',
                                                fieldLabel: 'Od 17h do 20h',
                                                labelWidth: 95,
                                                name: 'spot1Ucestalost4',
                                                width: 200
                                            }, {
                                                xtype: 'textfield',
                                                fieldLabel: 'Od 20h do 24h',
                                                labelWidth: 95,
                                                name: 'spot1Ucestalost5',
                                                width: 200
                                            }]
                                    }]
                            }]
                    }]
            }],
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
    clearData: function() {
        Ext.getCmp('clientDetailsCampaigneForm').getForm().reset();
    },
    addSpot: function() {





        var window = Ext.getCmp('clientDetailsCampaigneWindow');
        var form = Ext.getCmp('clientDetailsCampaigneForm');

        var spotCounter_pom=window.spotCounter;

        form.add({
            xtype: 'fieldset',
            title: 'Spot ' + window.spotCounter,
            items: [{
                    xtype: 'textfield',
                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_SpotName,
                    name: 'spot' + window.spotCounter + 'naziv',
                    id: 'spot' + window.spotCounter + 'naziv',
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
                },



                {
                    xtype: 'button',
                    y:-26,
                    x:610,
                    height: 20,
                    width: '20',
                    autoEl: {tag: 'center'},
                    text: 'Upload Spot',
                    handler: function() {

                        var station = Ext.getCmp('radioStanicaID').getValue();
                        if(station==null){
                            alert("Morate izabrati Radio Stanicu !");
                            return;
                        }

                        Ext.create('Mediaplan.mediaPlan.clients.details.CampaigneDocumentWindow',{
                            entryID:spotCounter_pom,
                            radioStanicaID:station
                        });
                    }
                },








                {
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
                    items: [/*{
                     xtype:'numberfield',
                     fieldLabel:Lang.MediaPlan_clients_details_campaignes_dialog_Frequency,
                     name:'spot'+ window.spotCounter + 'ucestalost',
                     allowBlank:false,
                     value: 1,
                     minValue: 1,
                     maxValue: 100,
                     width:200 
                     },*/{
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
                        }, {
                            xtype: 'radiogroup',
                            labelWidth: 60,
                            fieldLabel: ' ',
                            labelSeparator: '',
                            columns: 3,
                            width: 400,
                            vertical: true,
                            items: [{
                                    boxLabel: 'Samo obični',
                                    name: 'spot' + window.spotCounter + 'premiumBlokovi',
                                    checked: true,
                                    inputValue: '1'
                                }, {
                                    boxLabel: 'Samo premium',
                                    name: 'spot' + window.spotCounter + 'premiumBlokovi',
                                    //checked:true,
                                    inputValue: '2'
                                }, {
                                    boxLabel: 'Obični i premium',
                                    name: 'spot' + window.spotCounter + 'premiumBlokovi',
                                    inputValue: '3'
                                }]
                        }/*{
                         xtype:'checkbox',
                         name:'spot'+ window.spotCounter + 'premiumBlokovi',
                         labelWidth: 80,
                         fieldLabel:' ',
                         labelSeparator:'',
                         inputValue:'1',
                         checked: true,
                         boxLabel:Lang.MediaPlan_clients_details_campaignes_dialog_PremiumBlocks
                         }*/]
                }, {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    labelAlign: 'left',
                    items: [{
                            xtype: 'fieldset',
                            title: 'Dani emitovanja spota',
                            border: true,
                            items: [{
                                    xtype: 'checkboxgroup',
                                    fieldLabel: 'Dani u nedelji kada se emituje spot',
                                    columns: 2,
                                    vertical: true,
                                    width: 500,
                                    height: 133,
                                    items: [
                                        {boxLabel: 'Ponedeljak', name: 'spot' + window.spotCounter + 'dan1', inputValue: '1', checked: true},
                                        {boxLabel: 'Utorak', name: 'spot' + window.spotCounter + 'dan2', inputValue: '2', checked: true},
                                        {boxLabel: 'Sreda', name: 'spot' + window.spotCounter + 'dan3', inputValue: '3', checked: true},
                                        {boxLabel: 'Četvrtak', name: 'spot' + window.spotCounter + 'dan4', inputValue: '4', checked: true},
                                        {boxLabel: 'Petak', name: 'spot' + window.spotCounter + 'dan5', inputValue: '5', checked: true},
                                        {boxLabel: 'Subota', name: 'spot' + window.spotCounter + 'dan6', inputValue: '6', checked: true},
                                        {boxLabel: 'Nedelja', name: 'spot' + window.spotCounter + 'dan7', inputValue: '7', checked: true}
                                    ]
                                }]
                        }, /*{
                         xtype:'tbspacer',
                         width:10
                         },{
                         xtype:'fieldset',
                         border:true,
                         height:140,
                         items:[{
                         xtype: 'checkboxgroup',
                         fieldLabel: 'Period emitovanja',
                         columns: 1,
                         vertical: true,
                         width:200,
                         items: [
                         { boxLabel: 'Od  6h do 8h', name: 'spot'+ window.spotCounter + 'period1',   margin:'5 0 0 0', inputValue: '1', checked: true },
                         { boxLabel: 'Od  8h do 12h', name: 'spot'+ window.spotCounter + 'period2',  margin:'10 0 0 0', inputValue: '2', checked: true },
                         { boxLabel: 'Od 12h do 17h', name: 'spot'+ window.spotCounter + 'period3',  margin:'10 0 0 0', inputValue: '3', checked: true },
                         { boxLabel: 'Od 17h do 20h', name: 'spot'+ window.spotCounter + 'period4',  margin:'10 0 0 0', inputValue: '4', checked: true },
                         { boxLabel: 'Od 20h do 24h', name: 'spot'+ window.spotCounter + 'period5',  margin:'10 0 0 0', inputValue: '5', checked: true }
                         ] 
                         }]
                         },*/{
                            xtype: 'tbspacer',
                            width: 15
                        }, {
                            xtype: 'fieldset',
                            title: 'Učestalost emitovanja spota',
                            border: true,
                            width: 300,
                            height: 156,
                            items: [{
                                    xtype: 'textfield',
                                    fieldLabel: 'Od  6h do 8h',
                                    labelWidth: 95,
                                    name: 'spot' + window.spotCounter + 'Ucestalost1',
                                    width: 200
                                }, {
                                    xtype: 'textfield',
                                    fieldLabel: 'Od  8h do 12h',
                                    labelWidth: 95,
                                    name: 'spot' + window.spotCounter + 'Ucestalost2',
                                    width: 200
                                }, {
                                    xtype: 'textfield',
                                    fieldLabel: 'Od 12h do 17h',
                                    labelWidth: 95,
                                    name: 'spot' + window.spotCounter + 'Ucestalost3',
                                    width: 200
                                }, {
                                    xtype: 'textfield',
                                    fieldLabel: 'Od 17h do 20h',
                                    labelWidth: 95,
                                    margin: '5 0 5 0',
                                    name: 'spot' + window.spotCounter + 'Ucestalost4',
                                    width: 200
                                }, {
                                    xtype: 'textfield',
                                    fieldLabel: 'Od 20h do 24h',
                                    labelWidth: 95,
                                    name: 'spot' + window.spotCounter + 'Ucestalost5',
                                    width: 200
                                }]
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
    createCampaigne: function() {

        var form = Ext.getCmp('clientDetailsCampaigneForm').getForm();

        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneWindow').getEl(), Lang.CampaigneCreate);
            waitBox.show();
            form.submit({
                timeout: 600000,
                url: '../App/Controllers/Kampanja.php?action=KampanjaZahtevInsert',
                success: function(form, response) {





                    waitBox.hide();
                    var data = response.result.data;

/*
                    alert_obj_boban(data);
                    exit;*/

/*
                    alert(data);
                    return;*/


                    //alert_obj_boban(data.schedulerCommercial[3]);







                    var schedulerConfig = Common.schConfig;
                    schedulerConfig.showContextmenu = true;
                    schedulerConfig.commercials = data.schedulerCommercial;
                    schedulerConfig.dates = data.schedulerDates;
                    schedulerConfig.actionType = 'campaigne';
                    var campaignePrice = data.capmaignePrice;
                    var popust = data.popust;
                    var campaigneId = data.campaigneID;

                    var spotBroj=data.spotBroj;


                    if(data.twoOffers==1) {

                        var schedulerConfig2 = Common.schConfig2;
                        schedulerConfig2.showContextmenu = true;
                        schedulerConfig2.commercials = data.schedulerCommercial2;
                        schedulerConfig2.dates = data.schedulerDates2;
                        schedulerConfig2.actionType = 'campaigne';

                        var campaignePrice2 = data.capmaignePrice2;



                        var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindowtwooffers', {
                            showTbar: true,
                            height: 575,
                            config: schedulerConfig,
                            config2: schedulerConfig2,
                            campaigneID: campaigneId,
                            price: campaignePrice,
                            price2: campaignePrice2,
                            popust: popust,
                            spotBroj: spotBroj
                        });

                    }else{//one offers

                        var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindow', {
                            showTbar: true,
                            height: 575,
                            config: schedulerConfig,
                            campaigneID: campaigneId,
                            price: campaignePrice,
                            popust: popust,
                            spotBroj: spotBroj
                        });
                    }

                },
                failure: function(fp, o) {
                    waitBox.hide();
                    alert('Greška u obradi zahteva');
                }

            });
        }

    }






});


Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesTemplateWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignestemplatewindow',
    title: Lang.MediaPlan_clients_details_campaignesTemplate_dialog_Title,
    id: 'clientDetailsCampaigneTemplateWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    spotCounter: 2,
    modal: true,
    width: 950,
    height: 700,
    initComponent: function()
    {

        //alert("kampanja iz sablona");

        var window = this;

        this.listeners = {
            show: function() {
                var clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                Ext.getCmp('hdnCampaigneTemplateClientID').setValue(clientID);

                window.disableEnableTemplateFields(true);

            }
        };

        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'clientDetailsCampaigneTemplateForm',
                border: false,
                frame: false,
                labelWidth: 120,
                fileUpload: true,
                autoScroll: true,
                items: [{
                        xtype: 'hidden',
                        name: 'klijentID',
                        id: 'hdnCampaigneTemplateClientID'
                    }, {
                        xtype: 'hidden',
                        name: 'spotBroj',
                        id: 'hdnTemplateSpotCountID',
                        value: 1
                    }, {
                        xtype: 'fieldset',
                        border: true,
                        title: Lang.MediaPlan_clients_details_campaignesTemplate_dialog_fldSet_CampaigneData,
                        //collapsible: true,
                        items: [{
                                xtype: 'combobox',
                                fieldLabel: Lang.MediaPlan_clients_details_campaignesTemplate_dialog_TemplateStation,
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


                                            var station = Ext.getCmp('campaigneTempleteStation').getValue();



                                            var template = Ext.getCmp('campaigneTemplateName');
                                            /*
                                            template.clearValue();

                                            template.store.proxy.extraParams.RadioStanicaID = station;
                                            //template.load();
*/



                                            template.store.proxy.extraParams.RadioStanicaID = station;
                                            template.store.removeAll();
                                            template.lastQuery = null;
                                            template.store.load();
                                            template.setValue('');


                                            Ext.getCmp('datumPocetka').setValue('');
                                            Ext.getCmp('datumKraja').setValue('');


                                            var station = Ext.getCmp('campaigneTempleteStation').getValue();

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

                                                    var spotBroj = Ext.getCmp('hdnTemplateSpotCountID').getValue();
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
                                id: 'campaigneTempleteStation',
                                queryMode: 'remote',
                                typeAhead: true,
                                queryParam: 'filter',
                                emptyText: Lang.Common_combobox_emptyText,
                                valueField: 'EntryID',
                                displayField: 'EntryName',
                                allowBlank: false,
                                width: 550
                            }, {
                                xtype: 'combobox',
                                fieldLabel: Lang.MediaPlan_clients_details_campaignesTemplate_dialog_TemplateName,
                                name: 'sablonID',
                                id: 'campaigneTemplateName',
                                store: new Ext.data.Store({
                                    fields: ['EntryID', 'EntryName'],
                                    proxy: {
                                        type: 'ajax',
                                        url: '../App/Controllers/Sablon.php',
                                        actionMethods: {
                                            read: 'POST'
                                        },
                                        extraParams: {
                                            action: 'SablonGetForComboBox',
                                            RadioStanicaID: 10
                                        },
                                        reader: {
                                            type: 'json',
                                            root: 'rows'
                                        }
                                    }/*,
                                     listeners:{
                                     beforeload : {
                                     fn: function(store,records,successful,eOpts){
                                     store.removeAll();
                                     var station = Ext.getCmp('campaigneTempleteStation').getValue();
                                     store.proxy.extraParams.RadioStanicaID = station;
                                     }
                                     } 
                                     }*/
                                }),
                                listeners: {

                                    /*
                                    expand: {
                                        fn: function(combo, records, eOpts) {
                                            combo.store.removeAll();
                                            combo.store.load();
                                        }
                                    }

                                    ,*/


                                    select: {
                                        fn: function(combo, records, eOpts) {

                                            var template = Ext.getCmp('campaigneTemplateName');
                                            templates_id=template.getValue();
                                            window.populateTemplateData(templates_id);

                                            Ext.getCmp('datumPocetka').setValue('');
                                            Ext.getCmp('datumKraja').setValue('');




                                        }
                                    }









                                },
                                queryMode: 'remote',
                                typeAhead: true,
                                queryParam: 'filter',
                                emptyText: Lang.Common_combobox_emptyText,
                                valueField: 'EntryID',
                                displayField: 'EntryName',
                                allowBlank: false,
                                width: 550
                            },


                            {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                labelAlign: 'left',
                                items: [{
                                    xtype: 'datefield',
                                    format: 'Y-m-d',
                                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_DateStart,
                                    id: 'datumPocetka',
                                    name: 'datumPocetka',
                                    allowBlank: false,
                                    width: 250,

                                    listeners: {

                                        select: {
                                            fn: function(combo, records, eOpts) {
                                                var datum_pocetka = Ext.getCmp('datumPocetka').getValue();
                                                var trajanje = parseInt(Ext.getCmp('trajanje').getValue());
                                                var d_start = new Date(datum_pocetka);
                                                var d_end = new Date(datum_pocetka);
                                                for (i = 1; i <= (trajanje); i++) {
                                                    if(i>1) {
                                                        d_end = addDays(d_end, 1);
                                                    }
                                                    var day=d_end.getDay(); //ned-sub: 0-6
                                                    if(day==0){
                                                        day=7;
                                                    }
                                                    var day_selected = Ext.getCmp('dan'+day).getValue();
                                                    if(day_selected){
                                                    }else{
                                                        trajanje = trajanje + 1;
                                                    }
                                                }
                                                Ext.getCmp('datumKraja').setValue(d_end);
                                            }
                                        }

                                    }








                                }, {
                                    xtype: 'datefield',
                                    labelAlign: 'right',
                                    labelWidth: 150,
                                    format: 'Y-m-d',
                                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_DateEnd,
                                    id: 'datumKraja',
                                    name: 'datumKraja',
                                    allowBlank: false,
                                    disabled: true,
                                    width: 300
                                }]
                            },




                            {
                                xtype: 'combobox',
                                fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Activity,
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
                                            clientID: ''
                                        },
                                        reader: {
                                            type: 'json',
                                            root: 'rows'
                                        }
                                    },
                                    listeners: {
                                        beforeload: {
                                            fn: function(store, records, successful, eOpts) {
                                                store.proxy.extraParams.clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                                            }
                                        }
                                    }
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
                                /*disabled: true,*/
                                title: 'Šablon podaci',
                                items: [  {
                                    xtype: 'fieldcontainer',
                                    layout: 'hbox',
                                    labelAlign: 'left',
                                    items: [{
                                        xtype: 'fieldset',
                                        title: 'Dani emitovanja spota',
                                        border: true,
                                        items: [{
                                            xtype: 'checkboxgroup',
                                            fieldLabel: 'Dani u nedelji kada se emituje spot',
                                            columns: 2,
                                            vertical: true,
                                            width: 500,
                                            height: 133,
                                            items: [
                                                {boxLabel: 'Ponedeljak', id: 'dan1', name: 'dan1', inputValue: '1', checked: false},
                                                {boxLabel: 'Utorak', id: 'dan2', name: 'dan2', inputValue: '2', checked: false},
                                                {boxLabel: 'Sreda', id: 'dan3', name: 'dan3', inputValue: '3', checked: false},
                                                {boxLabel: 'Četvrtak', id: 'dan4', name: 'dan4', inputValue: '4', checked: false},
                                                {boxLabel: 'Petak', id: 'dan5', name: 'dan5', inputValue: '5', checked: false},
                                                {boxLabel: 'Subota', id: 'dan6', name: 'dan6', inputValue: '6', checked: false},
                                                {boxLabel: 'Nedelja', id: 'dan7', name: 'dan7', inputValue: '7', checked: false}
                                            ]
                                        }]
                                    }, /*{
                                     xtype:'tbspacer',
                                     width:10
                                     },{
                                     xtype:'fieldset',
                                     border:true,
                                     height:140,
                                     items:[{
                                     xtype: 'checkboxgroup',
                                     fieldLabel: 'Period emitovanja',
                                     columns: 1,
                                     vertical: true,
                                     width:200,
                                     items: [
                                     { boxLabel: 'Od  6h do 8h', name: 'spot1period1', inputValue: '1',  margin:'5 0 0 0',checked: true },
                                     { boxLabel: 'Od  8h do 12h', name: 'spot1period2', inputValue: '2', margin:'10 0 0 0', checked: true },
                                     { boxLabel: 'Od 12h do 17h', name: 'spot1period3', inputValue: '3', margin:'10 0 0 0', checked: true },
                                     { boxLabel: 'Od 17h do 20h', name: 'spot1period4', inputValue: '4', margin:'10 0 0 0', checked: true },
                                     { boxLabel: 'Od 20h do 24h', name: 'spot1period5', inputValue: '5', margin:'10 0 0 0', checked: true }
                                     ]
                                     }]
                                     },*/{
                                        xtype: 'tbspacer',
                                        width: 15
                                    }, {
                                        xtype: 'fieldset',
                                        border: true,
                                        title: 'Učestalost emitovanja spota',
                                        width: 300,
                                        height: 156,
                                        items: [{
                                            xtype: 'textfield',
                                            fieldLabel: 'Od  6h do 8h',
                                            labelWidth: 95,
                                            id: 'ucestalost1',
                                            name: 'ucestalost1',
                                            //disabled: true,
                                            width: 200
                                        }, {
                                            xtype: 'textfield',
                                            fieldLabel: 'Od  8h do 12h',
                                            labelWidth: 95,
                                            id: 'ucestalost2',
                                            name: 'ucestalost2',
                                            //disabled: true,
                                            width: 200
                                        }, {
                                            xtype: 'textfield',
                                            fieldLabel: 'Od 12h do 17h',
                                            labelWidth: 95,
                                            id: 'ucestalost3',
                                            name: 'ucestalost3',
                                            disabled: true,
                                            width: 200
                                        }, {
                                            xtype: 'textfield',
                                            fieldLabel: 'Od 17h do 20h',
                                            labelWidth: 95,
                                            id: 'ucestalost4',
                                            name: 'ucestalost4',
                                            //disabled: true,
                                            width: 200
                                        }, {
                                            xtype: 'textfield',
                                            fieldLabel: 'Od 20h do 24h',
                                            labelWidth: 95,
                                            id: 'ucestalost5',
                                            name: 'ucestalost5',
                                            //disabled: true,
                                            width: 200
                                        }]
                                    }

                                    ]
                                },{
                                    xtype: 'textfield',
                                    fieldLabel: "Broj dana",
                                    id: 'trajanje',
                                    name: 'trajanje',
                                    //disabled: true,
                                    width: 300,
                                    allowBlank: false
                                    /*layout: 'fit'*/
                                },{
                                    xtype: 'textfield',
                                    fieldLabel: "Popust %",
                                    id: 'popust',
                                    name: 'popust',
                                    //disabled: true,
                                    width: 300,
                                    allowBlank: false
                                    /*layout: 'fit'*/
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
                                                var station = Ext.getCmp('campaigneTempleteStation').getValue();
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
            }],
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
                scope: this,
                icon: Icons.Cancel16,
                handler: this.close
            }



        ];


        this.callParent(arguments);
    },
    clearData: function() {
        Ext.getCmp('clientDetailsCampaigneTemplateForm').getForm().reset();
    },
    addSpot: function() {
        var window = Ext.getCmp('clientDetailsCampaigneTemplateWindow');
        var form = Ext.getCmp('clientDetailsCampaigneTemplateForm');
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
                            var station = Ext.getCmp('campaigneTempleteStation').getValue();
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
        });

        var spotBroj = Ext.getCmp('hdnTemplateSpotCountID');
        var newSpotBroj = parseInt(spotBroj.getValue()) + 1;
        spotBroj.setValue(newSpotBroj);

        window.spotCounter = window.spotCounter + 1;



        actb_curr = document.getElementsByName("spot"+newSpotBroj+"naziv")[0];
        var obj = actb(actb_curr,customarray);








    },
    createCampaigne: function() {


        this_pom=this;




        Ext.getCmp('datumKraja').setDisabled(false);

        var form = Ext.getCmp('clientDetailsCampaigneTemplateForm').getForm();

        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneTemplateWindow').getEl(), Lang.CampaigneCreate);
            waitBox.show();

            form.submit({
                timeout: 600000,
                url: '../App/Controllers/Kampanja.php?action=KampanjaIzSablona',
                success: function(form, response) {






                    Ext.getCmp('datumKraja').setDisabled(true);

                    waitBox.hide();
                    var data = response.result.data;


/*
                    alert_obj_boban(data.schedulerCommercial);
                    return;*/
/*
alert(data);



                    alert_obj_boban(data);
                    return;
*/


                    var schedulerConfig = Common.schConfig;
                    schedulerConfig.showContextmenu = true;
                    schedulerConfig.commercials = data.schedulerCommercial;
                    schedulerConfig.dates = data.schedulerDates;
                    schedulerConfig.actionType = 'campaigne';
                    var campaignePrice = data.capmaignePrice;
                    var popust = data.popust;
                    var sablonId = data.sablonId;

                    var spotBroj = data.spotBroj;


                    var campaigneId = data.campaigneID;
                    var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindow', {
                        showTbar: true,
                        height: 575,
                        config: schedulerConfig,
                        campaigneID: campaigneId,
                        price: campaignePrice,
                        popust: popust,
                        sablonId: sablonId,
                        spotBroj: spotBroj
                    });
                    //Ext.getCmp('clientDetailsCampaigneTemplateWindow').close();
                    //rebind grid

                    /*
                    var grid1 = Ext.getCmp('campaignesGrid');
                    var grid2 = Ext.getCmp('clientDetailsCampaignesGrid');
                    if (grid1) {
                        grid1.reloadGrid(grid1)
                    }
                    ;
                    if (grid2) {
                        grid2.reloadGrid(grid2)
                    }
                    ;*/
                },
                failure: function(fp, o) {

                    Ext.getCmp('datumKraja').setDisabled(true);

                    waitBox.hide();
                    var message = o.result.msg;
                    Ext.Msg.show({
                        title: Lang.Message_Title,
                        msg: message,
                        buttons: Ext.Msg.OK,
                        icon: Ext.MessageBox.ERROR
                    });
                }

            });
        }else{
            Ext.getCmp('datumKraja').setDisabled(true);
        }

    },
    populateTemplateData: function(templates_id) {



        Ext.Ajax.request({
            url: '../App/Controllers/Sablon.php',
            params: {
                sablonID: templates_id,
                action: 'SablonLoadDetails'
            },
            success: function(response){


                var data = Ext.decode(response.responseText).data;


                Ext.getCmp('dan1').setValue(false);
                document.getElementById('dan1').checked = false;
                Ext.getCmp('dan2').setValue(false);
                document.getElementById('dan2').checked = false;
                Ext.getCmp('dan3').setValue(false);
                document.getElementById('dan3').checked = false;
                Ext.getCmp('dan4').setValue(false);
                document.getElementById('dan4').checked = false;
                Ext.getCmp('dan5').setValue(false);
                document.getElementById('dan5').checked = false;
                Ext.getCmp('dan6').setValue(false);
                document.getElementById('dan6').checked = false;
                Ext.getCmp('dan7').setValue(false);
                document.getElementById('dan7').checked = false;


                var daniZaEmitovanje=data.daniZaEmitovanje;
                var daniZaEmitovanje_arr = daniZaEmitovanje.split(",");
                var arrayLength = daniZaEmitovanje_arr.length;
                for (var i = 0; i < arrayLength; i++) {

                    field="dan"+daniZaEmitovanje_arr[i];
                    Ext.getCmp(field).setValue(true);
                    document.getElementById(field).checked = true;

                }


                var ucestalost=data.ucestalost;
                var ucestalost_arr = ucestalost.split(",");
                var arrayLength = ucestalost_arr.length;
                for (var i = 0; i < arrayLength; i++) {
                    field="ucestalost"+parseInt(i+1);

                    if(ucestalost_arr[i]==0){
                        ucestalost_arr[i]="";
                    }

                    Ext.getCmp(field).setValue(ucestalost_arr[i]);
                    //document.getElementById(field).checked = true;

                }

                var popust=data.popust;
                Ext.getCmp('popust').setValue(popust);

                var trajanje=data.trajanje;
                Ext.getCmp('trajanje').setValue(trajanje);


                // process server response here
            }
        });


    },


    disableEnableTemplateFields: function(TF) {

        Ext.getCmp('dan1').setDisabled(TF);
        Ext.getCmp('dan2').setDisabled(TF);
        Ext.getCmp('dan3').setDisabled(TF);
        Ext.getCmp('dan4').setDisabled(TF);
        Ext.getCmp('dan5').setDisabled(TF);
        Ext.getCmp('dan6').setDisabled(TF);
        Ext.getCmp('dan7').setDisabled(TF);

        Ext.getCmp('ucestalost1').setDisabled(TF);
        Ext.getCmp('ucestalost2').setDisabled(TF);
        Ext.getCmp('ucestalost3').setDisabled(TF);
        Ext.getCmp('ucestalost4').setDisabled(TF);
        Ext.getCmp('ucestalost5').setDisabled(TF);

        Ext.getCmp('popust').setDisabled(TF);
        Ext.getCmp('trajanje').setDisabled(TF);

        Ext.getCmp('datumKraja').setDisabled(TF);

    }

});



/*************************DEFINE***************************************/



















/*********************MEDIA PLAN****************************/
Ext.define('Mediaplan.mediaPlan.clients.details.CampaignePreviewWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignepreviewwindow',
    title: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Title,
    id: 'clientDetailsCampaignePreviewWindow',
    layout: 'fit',
    autoShow: true,
    //closable:false,
    iconCls: 'table',
    modal: true,
    width: 1015,
    //height:570,
    offersCount:1,
    offerNo:1,
    sablonId:0,//ako nula nije sablon pri kreiranju kampanje (posle i neprikazujem popust)


/*************************/
    /*
    blokID_add: 0,
    datumEmitovanja_add: '',
    campaigneID_add: 0,*/
    spotID_add: 0,
    pozicija_add: 0,
/*************************/


    initComponent: function()
    {
        //alert(this.campaigneID);
        var window = this;

        this.tools = [{
                type: 'help',
                tooltip: 'Objašnjenje elemenata prikaza',
                handler: function(event, toolEl, panel) {
                    Common.campaigneHelpPanelText();
                }
            }];

        this.tbar = Ext.create('Ext.toolbar.Toolbar', {
            items: [/*{
             text: Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
             id: 'campaignePreviewTbar',
             iconCls: 'scheduler-add',
             handler: function() {
             Ext.create('Mediaplan.mediaPlan.scheduler.Dialog', {
             //campaigneID:eventRecord.data.CampaigneID,
             })
             }
             }*/]
        });

        this.listeners = {
            show: function() {
                //var scheduler = SchView.Render(this.config, 'schedulerContainer');



                this.capmaigneShow(this.config,this.spotBroj);






                //this.campaigneSetPrice(this.price,this.popust);
                this.campaigneSetPrice();

                /*
                var this_pom=this;
                var popust=0;//zbog prikaza cene, ako je sablonska kampanja
                if(this.sablonId>0) {
                    Ext.Ajax.request({
                        url: '../App/Controllers/Sablon.php',
                        params: {
                            sablonID: this.sablonId,
                            action: 'SablonLoadDetails'
                        },
                        success: function(response){
                            var data = Ext.decode(response.responseText).data;
                            popust=data.popust;
                            //alert(popust);
                            this_pom.campaigneSetPrice(this_pom.price,popust);
                        }
                    });

                }else{
                    this.campaigneSetPrice(this.price,popust);
                }*/



            },
            afterrender: function() {
                if (!this.showTbar) {
                    //Ext.getCmp('campaignePreviewTbar').disable();
                }
            }

        };



        this.items = [



            {
                html: '<div id="schedulerContainer"></div>',
                height: 300
            }





        ],
        this.bbar = [


            {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Agenda
            }, {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Block1 + ' - 15`- 17` 30``'
            }, '-', {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Block2 + ' - 30`- 30` 30``'
            }, '-', {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Block3 + ' - 45`- 47` 30``'
            }, '-', {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Block4 + ' - 59` 30``- 00`'
            }, '-', {
                xtype: 'textfield',
                labelWidth: 100,
                id: 'campaignePreviewPrice',
                fieldLabel: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Price,
                width: 250
            }



            , '->', {
                xtype: 'button',
                id: 'campaignePreviewBtnYes',
                tooltip: Lang.MediaPlan_clients_details_campaignesPreview_dialog_btn_accept_Tooltip,
                icon: Icons.Accept16,
                handler: function() {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaignePreviewWindow').getEl(), Lang.Saving);
                    waitBox.show();
                    //Ispitaivanje da li je u pitanju generisanje kampanje ili template-a. U skladu sa tim menja se kontolser i akcija 
                    //if (Ext.getCmp('clientDetailsCampaigneForm') || Ext.getCmp('manualCampaigneForm') || Ext.getCmp('clientDetailsCampaigneTemplateForm')) {   
                    //var actionToDo = 'KampanjaPotvrdi';
                    //var urlToGo = '../App/Controllers/Kampanja.php';
                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Kampanja.php',
                        params: {action: 'KampanjaPotvrdi'},
                        success: function(response, request) {


                            //alert(response.responseText);



                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.Msg.show({
                                    title: Lang.Message_Title,
                                    msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.MessageBox.INFO
                                });
                                var grid = Ext.getCmp('clientDetailsCampaignesGrid');
                                if (grid) {
                                    grid.reloadGrid(grid);
                                }
                                waitBox.hide();
                                window.close();
                                var campaigneWindow = Ext.getCmp('clientDetailsCampaigneWindow');
                                if (campaigneWindow) {
                                    campaigneWindow.close();
                                }

                                var manualCampaigneWindow = Ext.getCmp('manualCampaigneWindow');
                                if (manualCampaigneWindow) {
                                    manualCampaigneWindow.close();
                                }

                                var templateWindow = Ext.getCmp('templatesCampaigneWindow');
                                if (templateWindow) {
                                    templateWindow.close();
                                }

                                var templateWindow2 = Ext.getCmp('clientDetailsCampaigneTemplateWindow');
                                if (templateWindow2) {
                                    templateWindow2.close();
                                }






                                var weekTemplateWindow = Ext.getCmp('weekTemplatesCampaigneWindow');
                                if (weekTemplateWindow) {
                                    weekTemplateWindow.close();
                                }
                            }
                            else {
                                Ext.Msg.show({
                                    title: Lang.Message_Title,
                                    msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                                waitBox.hide();
                                window.close();
                            }
                        }
                    });
                    //} 

                    /*if (Ext.getCmp('templatesCampaigneForm')) {
                     //alert('uslo u granu sa template');
                     //var actionToDo = 'SablonPotvrdi';
                     //var urlToGo = '../App/Controllers/Sablon.php'; 
                     Ext.Ajax.request({
                     timeout: Common.Timeout,
                     url: '../App/Controllers/Sablon.php',
                     params: { action: 'SablonPotvrdi'},
                     success: function (response, request) {
                     
                     if (Common.IsAjaxResponseSuccessfull(response)) {
                     Ext.Msg.show({
                     title: Lang.Message_Title,
                     msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                     buttons: Ext.Msg.OK,
                     icon: Ext.MessageBox.INFO
                     });
                     var grid = Ext.getCmp('campaigneTemplatesAdministrationGrid');
                     if (grid){
                     grid.reloadGrid(grid);
                     }
                     waitBox.hide();
                     window.close();
                     var campaigneWindow = Ext.getCmp('clientDetailsCampaigneWindow');
                     if (campaigneWindow) {
                     campaigneWindow.close();
                     }
                     
                     var templateWindow = Ext.getCmp('templatesCampaigneWindow');
                     if (templateWindow) {
                     templateWindow.close();
                     }
                     
                     var weekTemplateWindow = Ext.getCmp('weekTemplatesCampaigneWindow');
                     if (weekTemplateWindow) {
                     weekTemplateWindow.close();
                     }
                     }
                     else {
                     Ext.Msg.show({
                     title: Lang.Message_Title,
                     msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                     buttons: Ext.Msg.OK,
                     icon: Ext.MessageBox.ERROR
                     });
                     waitBox.hide();
                     window.close();
                     }
                     }
                     });                                 
                     };*/

                    if (Ext.getCmp('weekTemplatesCampaigneForm')) {
                        //alert('uslo u granu sa template');
                        //var actionToDo = 'SablonPotvrdi';
                        //var urlToGo = '../App/Controllers/Sablon.php'; 
                        Ext.Ajax.request({
                            timeout: Common.Timeout,
                            url: '../App/Controllers/Sablon.php',
                            params: {action: 'SablonPotvrdi'},
                            success: function(response, request) {

                                if (Common.IsAjaxResponseSuccessfull(response)) {
                                    Ext.Msg.show({
                                        title: Lang.Message_Title,
                                        msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                        buttons: Ext.Msg.OK,
                                        icon: Ext.MessageBox.INFO
                                    });
                                    var grid = Ext.getCmp('weekTemplatesAdministrationGrid');
                                    if (grid) {
                                        grid.reloadGrid(grid);
                                    }
                                    waitBox.hide();
                                    window.close();
                                    var campaigneWindow = Ext.getCmp('clientDetailsCampaigneWindow');
                                    if (campaigneWindow) {
                                        campaigneWindow.close();
                                    }

                                    var templateWindow = Ext.getCmp('templatesCampaigneWindow');
                                    if (templateWindow) {
                                        templateWindow.close();
                                    }

                                    var weekTemplateWindow = Ext.getCmp('weekTemplatesCampaigneWindow');
                                    if (weekTemplateWindow) {
                                        weekTemplateWindow.close();
                                    }
                                }
                                else {
                                    Ext.Msg.show({
                                        title: Lang.Message_Title,
                                        msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                        buttons: Ext.Msg.OK,
                                        icon: Ext.MessageBox.ERROR
                                    });
                                    waitBox.hide();
                                    window.close();
                                }
                            }
                        });
                    }


                    /*Ext.Ajax.request({
                     timeout: Common.Timeout,
                     url: urlToGo,
                     params: { action: actionToDo},
                     success: function (response, request) {
                     
                     if (Common.IsAjaxResponseSuccessfull(response)) {
                     Ext.Msg.show({
                     title: Lang.Message_Title,
                     msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                     buttons: Ext.Msg.OK,
                     icon: Ext.MessageBox.INFO
                     });
                     var grid = Ext.getCmp('clientDetailsCampaignesGrid');
                     if (grid){
                     grid.reloadGrid(grid);
                     }
                     waitBox.hide();
                     window.close();
                     var campaigneWindow = Ext.getCmp('clientDetailsCampaigneWindow');
                     if (campaigneWindow) {
                     campaigneWindow.close();
                     }
                     
                     var templateWindow = Ext.getCmp('templatesCampaigneWindow');
                     if (templateWindow) {
                     templateWindow.close();
                     }
                     }
                     else {
                     Ext.Msg.show({
                     title: Lang.Message_Title,
                     msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                     buttons: Ext.Msg.OK,
                     icon: Ext.MessageBox.ERROR
                     });
                     waitBox.hide();
                     window.close();
                     }
                     }
                     });*/
                }
            }, '-', {
                xtype: 'button',
                id: 'campaignePreviewBtnNo',
                tooltip: Lang.MediaPlan_clients_details_campaignesPreview_dialog_btn_discard_Tooltip,
                icon: Icons.Discard16,
                handler: function() {
                    window.close();

                }
            }];


        this.callParent(arguments);
    },
    campaigneSetPrice: function() {

        price=this.price;
        popust=this.popust;

        if(popust==0){
            var priceeur = price + '€';
        }else{

            var cena_bez_popusta = Math.round((price*100/(100-popust)) * 100) / 100;

            var priceeur = cena_bez_popusta + "-" + popust + "%=" + price + '€';
        }


        Ext.getCmp('campaignePreviewPrice').setValue(priceeur);
    },
    capmaigneShow: function(config,spotBroj) {

        var rowHeight=parseInt(spotBroj*13+8);
        if(rowHeight<40){
            rowHeight=40;
        }
        //var rowHeight=40;

        SchView.Render(config, 'schedulerContainer',rowHeight);


        //SchView.Scheduler.rowHeight=100;
       /* SchView.Scheduler.refresh();
*/


        this.doLayout();
        /*
        alert(SchView.Scheduler.rowHeight);
        SchView.Scheduler.rowHeight=100;
        alert(SchView.Scheduler.rowHeight);
        SchView.Scheduler.refresh();*/


    }


});
/********************KRAJ MEDIA PLAN************************************/







/************************* MEDIA PLAN NOVI ********************************/
Ext.define('Mediaplan.mediaPlan.clients.details.CampaignePreviewWindowTwoOffers', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignepreviewwindowtwooffers',
    title: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Title,
    id: 'clientDetailsCampaignePreviewWindow',
    layout: 'fit',
    autoShow: true,
    //closable:false,
    iconCls: 'table',
    modal: true,
    width: 1015,
    //height:570,
    offersCount:2,
    offerNo:1,
    filled:0,//iscrtavanje dijagrama u tabovima (problem)


    /*************************/
    /*
     blokID_add: 0,
     datumEmitovanja_add: '',
     campaigneID_add: 0,*/
    spotID_add: 0,
    pozicija_add: 0,
    /*************************/

    initComponent: function()
    {




        var window = this;

        this.tools = [{
            type: 'help',
            tooltip: 'Objašnjenje elemenata prikaza',
            handler: function(event, toolEl, panel) {
                Common.campaigneHelpPanelText();
            }
        }];

        this.tbar = Ext.create('Ext.toolbar.Toolbar', {
            items: [/*{
             text: Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
             id: 'campaignePreviewTbar',
             iconCls: 'scheduler-add',
             handler: function() {
             Ext.create('Mediaplan.mediaPlan.scheduler.Dialog', {
             //campaigneID:eventRecord.data.CampaigneID,
             })
             }
             }*/]
        });


        this.listeners = {
            show: function() {
                //var scheduler = SchView.Render(this.config, 'schedulerContainer');

                //alert("show: "+this.offerNo);

                this.filled=1;
                tabs.setActiveTab( (this.offerNo-1) );
                this.filled=0;

                this.capmaigneShow(this.config,this.spotBroj);
                this.capmaigneShow2(this.config2,this.spotBroj);


                this.campaigneSetPrice();

                //this.campaigneSetHdnPredlogBr(1);






            },
            afterrender: function() {
                if (!this.showTbar) {
                    //Ext.getCmp('campaignePreviewTbar').disable();
                }
            }
        };



        this.items = [






                    tabs = Ext.create('Ext.tab.Panel', {
                        deferredRender: true,
                        activeTab: 0,
                       /* id:'tabsss',*/
                        listeners: {
                            beforetabchange: function (tabs, newTab, oldTab) {
                                if (newTab.id == 'tab_1') {

                                    //this.up().campaigneSetHdnPredlogBr(1);
                                    this.up().offerNo=1;
                                    this.up().campaigneSetPrice();
                                }
                                if (newTab.id == 'tab_2') {

                                    //this.up().campaigneSetHdnPredlogBr(2);
                                    this.up().offerNo=2;
                                    this.up().campaigneSetPrice();
                                }
                            },
                            tabchange: function (tabs, newTab, oldTab) {

                                if(!this.up().filled) {
                                    if (newTab.id == 'tab_1') {
                                        //alert("tabchanged tab1: "+this.up().offerNo);
                                        var el2_holder = document.getElementById("schedulerContainer1_holder");
                                        var el2 = document.getElementById("schedulerContainer1_2");
                                        el2_holder.appendChild(el2);
                                    }


                                    if (newTab.id == 'tab_2') {
                                        //alert("tabchanged tab2: "+this.up().offerNo);
                                        var el2_holder = document.getElementById("schedulerContainer2_holder");
                                        var el2 = document.getElementById("schedulerContainer2_1");
                                        el2_holder.appendChild(el2);

                                    }

                                    this.up().filled=1;
                                }

                            }
                        },
                        items: [{
                            id: 'tab_1',
                            title: 'Predlog 1',
                            html: '<div id="schedulerContainer1_holder">' +
                            '</div>'+
                            '<div id="schedulerContainer1_1" style="width:1000px;height:480px;"></div>' +
                            '<div id="schedulerContainer2_1" style="width:1000px;height:480px;"></div>',
                            height: 480
                        }, {
                            id: 'tab_2',
                            title: "predlog 2",
                            html: '<div id="schedulerContainer2_holder">' +
                            '</div>'+
                            '<div id="schedulerContainer2_2" style="width:1000px;height:480px;"></div>'+
                            '<div id="schedulerContainer1_2" style="width:1000px;height:480px;"></div>',
                            height: 480
                        }]
                    })










            /*
            {
            html: '<div id="schedulerContainer"></div>',
            height: 300
        }

        */




        ];

        this.bbar = [


/*
            {
                xtype: 'hidden',
                id: 'hdnDvaPredlogaMain',
                name: 'dvaPredloga',
                value: '1'
            },
            {
                xtype: 'hidden',
                id: 'hdnPredlogBrMain',
                name: 'predlogBr',
                value: '-1'
            },*/


            {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Agenda
            }, {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Block1 + ' - 15`- 17` 30``'
            }, '-', {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Block2 + ' - 30`- 30` 30``'
            }, '-', {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Block3 + ' - 45`- 47` 30``'
            }, '-', {
                html: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Block4 + ' - 59` 30``- 00`'
            }, '-', {
                xtype: 'textfield',
                labelWidth: 100,
                id: 'campaignePreviewPrice',
                fieldLabel: Lang.MediaPlan_clients_details_campaignesPreview_dialog_Price,
                width: 250
            }, '->', {
                xtype: 'button',
                id: 'campaignePreviewBtnYes',
                tooltip: Lang.MediaPlan_clients_details_campaignesPreview_dialog_btn_accept_Tooltip,
                icon: Icons.Accept16,
                handler: function() {


                    var activeTab = tabs.getActiveTab();
                    var activeTabIndex = tabs.items.findIndex('id', activeTab.id);


                    var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaignePreviewWindow').getEl(), Lang.Saving);
                    waitBox.show();
                    //Ispitaivanje da li je u pitanju generisanje kampanje ili template-a. U skladu sa tim menja se kontolser i akcija
                    //if (Ext.getCmp('clientDetailsCampaigneForm') || Ext.getCmp('manualCampaigneForm') || Ext.getCmp('clientDetailsCampaigneTemplateForm')) {
                    //var actionToDo = 'KampanjaPotvrdi';
                    //var urlToGo = '../App/Controllers/Kampanja.php';
                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Kampanja.php',
                        params: {action: 'KampanjaPotvrdi',predlog: activeTabIndex},
                        success: function(response, request) {

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.Msg.show({
                                    title: Lang.Message_Title,
                                    msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.MessageBox.INFO
                                });
                                var grid = Ext.getCmp('clientDetailsCampaignesGrid');
                                if (grid) {
                                    grid.reloadGrid(grid);
                                }
                                waitBox.hide();
                                window.close();
                                var campaigneWindow = Ext.getCmp('clientDetailsCampaigneWindow');
                                if (campaigneWindow) {
                                    campaigneWindow.close();
                                }

                                var manualCampaigneWindow = Ext.getCmp('manualCampaigneWindow');
                                if (manualCampaigneWindow) {
                                    manualCampaigneWindow.close();
                                }

                                var templateWindow = Ext.getCmp('templatesCampaigneWindow');
                                if (templateWindow) {
                                    templateWindow.close();
                                }


                                var weekTemplateWindow = Ext.getCmp('weekTemplatesCampaigneWindow');
                                if (weekTemplateWindow) {
                                    weekTemplateWindow.close();
                                }
                            }
                            else {
                                Ext.Msg.show({
                                    title: Lang.Message_Title,
                                    msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                                waitBox.hide();
                                window.close();
                            }
                        }
                    });
                    //}

                    /*if (Ext.getCmp('templatesCampaigneForm')) {
                     //alert('uslo u granu sa template');
                     //var actionToDo = 'SablonPotvrdi';
                     //var urlToGo = '../App/Controllers/Sablon.php';
                     Ext.Ajax.request({
                     timeout: Common.Timeout,
                     url: '../App/Controllers/Sablon.php',
                     params: { action: 'SablonPotvrdi'},
                     success: function (response, request) {

                     if (Common.IsAjaxResponseSuccessfull(response)) {
                     Ext.Msg.show({
                     title: Lang.Message_Title,
                     msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                     buttons: Ext.Msg.OK,
                     icon: Ext.MessageBox.INFO
                     });
                     var grid = Ext.getCmp('campaigneTemplatesAdministrationGrid');
                     if (grid){
                     grid.reloadGrid(grid);
                     }
                     waitBox.hide();
                     window.close();
                     var campaigneWindow = Ext.getCmp('clientDetailsCampaigneWindow');
                     if (campaigneWindow) {
                     campaigneWindow.close();
                     }

                     var templateWindow = Ext.getCmp('templatesCampaigneWindow');
                     if (templateWindow) {
                     templateWindow.close();
                     }

                     var weekTemplateWindow = Ext.getCmp('weekTemplatesCampaigneWindow');
                     if (weekTemplateWindow) {
                     weekTemplateWindow.close();
                     }
                     }
                     else {
                     Ext.Msg.show({
                     title: Lang.Message_Title,
                     msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                     buttons: Ext.Msg.OK,
                     icon: Ext.MessageBox.ERROR
                     });
                     waitBox.hide();
                     window.close();
                     }
                     }
                     });
                     };*/

                    if (Ext.getCmp('weekTemplatesCampaigneForm')) {
                        //alert('uslo u granu sa template');
                        //var actionToDo = 'SablonPotvrdi';
                        //var urlToGo = '../App/Controllers/Sablon.php';
                        Ext.Ajax.request({
                            timeout: Common.Timeout,
                            url: '../App/Controllers/Sablon.php',
                            params: {action: 'SablonPotvrdi'},
                            success: function(response, request) {

                                if (Common.IsAjaxResponseSuccessfull(response)) {
                                    Ext.Msg.show({
                                        title: Lang.Message_Title,
                                        msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                        buttons: Ext.Msg.OK,
                                        icon: Ext.MessageBox.INFO
                                    });
                                    var grid = Ext.getCmp('weekTemplatesAdministrationGrid');
                                    if (grid) {
                                        grid.reloadGrid(grid);
                                    }
                                    waitBox.hide();
                                    window.close();
                                    var campaigneWindow = Ext.getCmp('clientDetailsCampaigneWindow');
                                    if (campaigneWindow) {
                                        campaigneWindow.close();
                                    }

                                    var templateWindow = Ext.getCmp('templatesCampaigneWindow');
                                    if (templateWindow) {
                                        templateWindow.close();
                                    }

                                    var weekTemplateWindow = Ext.getCmp('weekTemplatesCampaigneWindow');
                                    if (weekTemplateWindow) {
                                        weekTemplateWindow.close();
                                    }
                                }
                                else {
                                    Ext.Msg.show({
                                        title: Lang.Message_Title,
                                        msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                        buttons: Ext.Msg.OK,
                                        icon: Ext.MessageBox.ERROR
                                    });
                                    waitBox.hide();
                                    window.close();
                                }
                            }
                        });
                    }
                    ;

                    /*Ext.Ajax.request({
                     timeout: Common.Timeout,
                     url: urlToGo,
                     params: { action: actionToDo},
                     success: function (response, request) {

                     if (Common.IsAjaxResponseSuccessfull(response)) {
                     Ext.Msg.show({
                     title: Lang.Message_Title,
                     msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                     buttons: Ext.Msg.OK,
                     icon: Ext.MessageBox.INFO
                     });
                     var grid = Ext.getCmp('clientDetailsCampaignesGrid');
                     if (grid){
                     grid.reloadGrid(grid);
                     }
                     waitBox.hide();
                     window.close();
                     var campaigneWindow = Ext.getCmp('clientDetailsCampaigneWindow');
                     if (campaigneWindow) {
                     campaigneWindow.close();
                     }

                     var templateWindow = Ext.getCmp('templatesCampaigneWindow');
                     if (templateWindow) {
                     templateWindow.close();
                     }
                     }
                     else {
                     Ext.Msg.show({
                     title: Lang.Message_Title,
                     msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                     buttons: Ext.Msg.OK,
                     icon: Ext.MessageBox.ERROR
                     });
                     waitBox.hide();
                     window.close();
                     }
                     }
                     });*/
                }
            }, '-', {
                xtype: 'button',
                id: 'campaignePreviewBtnNo',
                tooltip: Lang.MediaPlan_clients_details_campaignesPreview_dialog_btn_discard_Tooltip,
                icon: Icons.Discard16,
                handler: function() {
                    window.close();

                }
            }];


        this.callParent(arguments);
    },
    campaigneSetPrice: function() {
        if(this.offerNo==2){
            price=this.price2;
        }else{
            price=this.price;
        }

        popust=this.popust;

        if(popust==0){
            var priceeur = price + '€';
        }else{


            var cena_bez_popusta = Math.round((price*100/(100-popust)) * 100) / 100;

            var priceeur = cena_bez_popusta + "-" + popust + "%=" + price + '€';
        }

        Ext.getCmp('campaignePreviewPrice').setValue(priceeur);

    },
    capmaigneShow: function(config,spotBroj) {
        var rowHeight=parseInt(spotBroj*13+8);
        if(rowHeight<40){
            rowHeight=40;
        }
        SchView.Render(config, 'schedulerContainer1_'+this.offerNo,rowHeight);
        this.doLayout();
    },
    capmaigneShow2: function(config2,spotBroj) {
        var rowHeight=parseInt(spotBroj*13+8);
        if(rowHeight<40){
            rowHeight=40;
        }
        SchView.Render(config2, 'schedulerContainer2_'+this.offerNo,rowHeight);
        this.doLayout();
    }









});
/************************* KRAJ MEDIA PLAN NOVI ********************************/





















Ext.define('Mediaplan.mediaPlan.clients.details.CampaigneDetailsWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignedetailswindow',
    title: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Title,
    id: 'clientDetailsCampaigneDetailsWindow',
    layout: 'fit',
    autoShow: true,
    //closable:false,
    iconCls: 'table',
    modal: true,
    width: 500,
    height: 500,
    initComponent: function()
    {

        //alert("pregled detalja o kampanji (poziva iz grida kampanja) ");


        var window = this;

        this.listeners = {
            show: function() {

                var form = Ext.getCmp('clientDetailsCampaigneDetailsForm').getForm();
                form.setValues(this.data);
                for (i = 0; i < Common.allUserPermisions.length; i++) {
                    var p = Common.allUserPermisions[i];
                    /*if (p == 220) {
                        Ext.getCmp('clientDetailsCampaigneDetailsBtnConfirm').enable();

                    }
                    ;*/
                    if (p == 225) {
                        Ext.getCmp('clientDetailsCampaigneDetailsBtnChangeStatus').enable();

                    }
                    ;
                    if (p == 230) {
                        Ext.getCmp('clientDetailsCampaigneDetailsBtnChangeFinStatus').enable();

                    }
                    ;


                    if (p == 235) {
                        Ext.getCmp('clientDetailsCampaigneDetailsBtnEditCampaigne').enable();

                    }
                    ;



                }
                ;
            }

        };

        this.tbar = Ext.create('Ext.toolbar.Toolbar', {
            id: 'clientDetailsCampaigneDetailsToolbar',
            items: [/*{
                    text: Lang.MediaPlan_clients_details_campaignesDetails_dialog_btn_Verify,
                    id: 'clientDetailsCampaigneDetailsBtnConfirm',
                    disabled: true,
                    handler: function() {
                        Ext.widget('clientdetailscampaignesconfirmwindow')
                    }
                }, '-', */{
                    text: Lang.MediaPlan_clients_details_campaignesDetails_dialog_btn_ChangeStatus,
                    id: 'clientDetailsCampaigneDetailsBtnChangeStatus',
                    disabled: true,
                    handler: function() {
                        Ext.widget('clientdetailscampaignesstatuswindow')
                    }
                }, '-', {
                    text: Lang.MediaPlan_clients_details_campaignesDetails_dialog_btn_ChangeFinStatus,
                    id: 'clientDetailsCampaigneDetailsBtnChangeFinStatus',
                    disabled: true,
                    handler: function() {
                        Ext.widget('clientdetailscampaignesfinstatuswindow')
                    }
                }, '-', {
                    text: 'Edit kampanje',
                    id: 'clientDetailsCampaigneDetailsBtnEditCampaigne',
                    disabled: true,
                    handler: function() {
                        Ext.widget('clientdetailscampaigneseditwindow')
                    }
                }]
            });


        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'clientDetailsCampaigneDetailsForm',
                border: false,
                frame: false,
                labelWidth: 160,
                fileUpload: true,
                items: [{
                        xtype: 'hidden',
                        id: 'hdnCampaigneDetailsCapmaigneID',
                        name: 'KampanjaID'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Name,
                        width: 300,
                        name: 'Naziv',
                        disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Client,
                        width: 300,
                        name: 'Klijent',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Activity,
                        width: 300,
                        name: 'Brend',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Agency,
                        width: 300,
                        name: 'Agencija',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_DateStart,
                        width: 300,
                        name: 'DatumPocetka',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_DateEnd,
                        width: 300,
                        id:'DatumKraja',
                        name: 'DatumKraja',
                    disabled: true
                    }, /*{
                     xtype:'textfield',
                     fieldLabel:Lang.MediaPlan_clients_details_campaignesDetails_dialog_Frequency,
                     width:300,
                     name:'Ucestalost'
                     },{
                     xtype:'textfield',
                     fieldLabel:Lang.MediaPlan_clients_details_campaignesDetails_dialog_SpotDuration,
                     width:300,
                     name:'SpotTrajanje'
                     }*/{
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Status,
                        width: 300,
                        name: 'Status',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_FinStatus,
                        width: 300,
                        name: 'FinansijskiStatus',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: "Popust %",
                        width: 300,
                        id:'Popust',
                        name: 'Popust',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Price,
                        width: 300,
                        name: 'CenaUkupno',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_DateCreatiion,
                        width: 300,
                        name: 'VremePostavke',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Creator,
                        width: 300,
                        name: 'KorisnikUneo',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_EndDateVerify,
                        width: 300,
                        name: 'VremeZaPotvrdu',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_DateVerified,
                        width: 300,
                        name: 'VremePotvrde',
                    disabled: true
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesDetails_dialog_Verificator,
                        width: 300,
                        name: 'KorisnikPOtvrdio',
                    disabled: true
                    }, {
                        xtype: 'hidden',
                        fieldLabel: 'Tip plaćanja',
                        width: 300,
                        id:'TipPlacanjaID',
                        name: 'TipPlacanjaID'
                    }



                ]
            }]
        this.callParent(arguments);
    }


});



//promena statusa kampanje form
Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesStatusWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesstatuswindow',
    title: Lang.MediaPlan_clients_details_campaignes_status_dialog_Title,
    id: 'clientDetailsCampaigneStatusWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 400,
    autoHeight: true,
    //height:550,
    initComponent: function()
    {

        var window = this;
        this.listeners = {
            show: function() {

                var kampanjaID = Ext.getCmp('hdnCampaigneDetailsCapmaigneID').getValue();
                Ext.getCmp('clientDetailsCampaigneStatusCampaigneID').setValue(kampanjaID);
            }

        };

        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'clientDetailsCampaigneStatusForm',
                border: false,
                frame: false,
                labelWidth: 120,
                items: [{
                        xtype: 'hidden',
                        name: 'kampanjaID',
                        id: 'clientDetailsCampaigneStatusCampaigneID'
                    }, {
                        xtype: 'combobox',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignes_status_dialog_Status,
                        store: Ext.create('Ext.data.Store', {
                            fields: ['EntryID', 'EntryName'],
                            proxy: {
                                type: 'ajax',
                                url: '../App/Controllers/StatusKampanja.php',
                                actionMethods: {
                                    read: 'POST'
                                },
                                extraParams: {
                                    action: 'StatusKampanjaForComboBox'
                                },
                                reader: {
                                    type: 'json',
                                    root: 'rows'
                                }
                            }
                        }),
                        name: 'statusID',
                        queryMode: 'remote',
                        typeAhead: true,
                        queryParam: 'filter',
                        emptyText: Lang.Common_combobox_emptyText,
                        valueField: 'EntryID',
                        displayField: 'EntryName',
                        allowBlank: false,
                        width: 300
                    },{
                    xtype:'textarea',
                    fieldLabel:Lang.MediaPlan_clients_details_offers_offernote_dialog_Note,
                    name:'napomena',
                    allowBlank: false,
                    width:450,
                    height:150
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
        var form = Ext.getCmp('clientDetailsCampaigneStatusForm').getForm();



        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneStatusWindow').getEl(), Lang.Saving);
            waitBox.show();


            var fieldValues = form.getValues();

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Kampanja.php',
                params: {action: 'KampanjaStatusPromena', fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {


                    //alert_obj_boban(response);


                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {
                        Ext.getCmp('clientDetailsCampaigneStatusWindow').close();
                        Ext.getCmp('clientDetailsCampaigneDetailsWindow').close();

                        //rebind grid
                        var grid1 = Ext.getCmp('campaignesGrid');
                        var grid2 = Ext.getCmp('clientDetailsCampaignesGrid');
                        if (grid1) {
                            grid1.reloadGrid(grid1)
                        }
                        ;
                        if (grid2) {
                            grid2.reloadGrid(grid2)
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





Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesFinStatusWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesfinstatuswindow',
    title: Lang.MediaPlan_clients_details_campaignes_finStatus_dialog_Title,
    id: 'clientDetailsCampaigneFinStatusWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 400,
    autoHeight: true,
    //height:550,
    initComponent: function()
    {

        var window = this;
        this.listeners = {
            show: function() {

                var kampanjaID = Ext.getCmp('hdnCampaigneDetailsCapmaigneID').getValue();
                Ext.getCmp('clientDetailsCampaigneFinStatusCampaigneID').setValue(kampanjaID);
            }

        };

        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'clientDetailsCampaigneFinStatusForm',
                border: false,
                frame: false,
                labelWidth: 120,
                items: [{
                        xtype: 'hidden',
                        name: 'kampanjaID',
                        id: 'clientDetailsCampaigneFinStatusCampaigneID'
                    }, {
                        xtype: 'combobox',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignes_finStatus_dialog_Status,
                        store: Ext.create('Ext.data.Store', {
                            fields: ['EntryID', 'EntryName'],
                            proxy: {
                                type: 'ajax',
                                url: '../App/Controllers/FinansijskiStatusKampanja.php',
                                actionMethods: {
                                    read: 'POST'
                                },
                                extraParams: {
                                    action: 'FinansijskiStatusGetForComboBox'
                                },
                                reader: {
                                    type: 'json',
                                    root: 'rows'
                                }
                            }
                        }),
                        name: 'finansijskiStatusKampanjaID',
                        queryMode: 'remote',
                        typeAhead: true,
                        queryParam: 'filter',
                        emptyText: Lang.Common_combobox_emptyText,
                        valueField: 'EntryID',
                        displayField: 'EntryName',
                        allowBlank: false,
                        width: 300
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
        var form = Ext.getCmp('clientDetailsCampaigneFinStatusForm').getForm();



        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneFinStatusWindow').getEl(), Lang.Saving);
            waitBox.show();


            var fieldValues = form.getValues();

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Kampanja.php',
                params: {action: 'KampanjaPromeniFinansijskiStatus', fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {




                        //alert_obj_boban(response);



                        Ext.getCmp('clientDetailsCampaigneFinStatusWindow').close();
                        Ext.getCmp('clientDetailsCampaigneDetailsWindow').close();

                        //rebind grid
                        var grid1 = Ext.getCmp('campaignesGrid');
                        var grid2 = Ext.getCmp('clientDetailsCampaignesGrid');
                        if (grid1) {
                            grid1.reloadGrid(grid1)
                        }
                        ;
                        if (grid2) {
                            grid2.reloadGrid(grid2)
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



Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesConfirmWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesconfirmwindow',
    title: Lang.MediaPlan_clients_details_campaignesConfirm_dialog_Title,
    id: 'clientDetailsCampaigneConfirmWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 500,
    height: 350,
    initComponent: function()
    {

        var window = this;

        this.listeners = {
            show: function() {

                var kampanjaID = Ext.getCmp('hdnCampaigneDetailsCapmaigneID').getValue();
                Ext.getCmp('clientDetailsCampaigneConfirmCampaigneID').setValue(kampanjaID);
                ;
            }

        };

        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'clientDetailsCampaigneConfirmForm',
                border: false,
                frame: false,
                labelWidth: 120,
                fileUpload: true,
                items: [{
                        xtype: 'hidden',
                        name: 'kampanjaID',
                        id: 'clientDetailsCampaigneConfirmCampaigneID'
                    }, {
                        xtype: 'textarea',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesConfirm_dialog_Note,
                        width: 400,
                        height: 200,
                        name: 'napomena'
                    }, {
                        xtype: 'textfield',
                        inputType: 'file',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesConfirm_dialog_File,
                        //allowBlank:false,
                        name: 'dokument',
                        width: 450
                    }/*,{
                     xtype:'textfield',
                     inputType:'file',
                     fieldLabel:Lang.MediaPlan_clients_details_campaignes_dialog_Spot,
                     allowBlank:false,
                     name:'spot',
                     width:450                         
                     }*/]
            }],
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

        var form = Ext.getCmp('clientDetailsCampaigneConfirmForm').getForm();

        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneConfirmWindow').getEl(), Lang.CampaigneCreate);
            waitBox.show();
            form.submit({
                url: '../App/Controllers/Kampanja.php?action=KampanjaConfirm',
                success: function(form, response) {
                    waitBox.hide();
                    Ext.getCmp('clientDetailsCampaigneConfirmWindow').close();
                    Ext.getCmp('clientDetailsCampaigneDetailsWindow').close();

                    //rebind grid
                    var grid1 = Ext.getCmp('campaignesGrid');
                    var grid2 = Ext.getCmp('clientDetailsCampaignesGrid');
                    if (grid1) {
                        grid1.reloadGrid(grid1)
                    }
                    ;
                    if (grid2) {
                        grid2.reloadGrid(grid2)
                    }
                    ;

                },
                failure: function(fp, o) {
                    waitBox.hide();
                    alert('Greška u obradi zahteva');
                }

            });
        }

    }
});

Ext.define('Mediaplan.mediaPlan.clients.details.CampaigneSpotWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesspotswindow',
    title: Lang.MediaPlan_clients_details_campaignes_spots_dialog_Title,
    id: 'clientDetailsCampaigneSpotsWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 600,
    height: 300,
    initComponent: function()
    {

        var window = this;
        this.listeners = {
            afterrender: function() {

                var kampanjaID = window.kampanja_id;

            }

        };

        this.items = [{
                xtype: 'clientdetailscampaignesspotsgrid',
                kampanjaID: window.kampanja_id
            }];


        this.callParent(arguments);
    }

});

Ext.define('Mediaplan.mediaPlan.clients.details.CampaigneServicesWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesserviceswindow',
    title: Lang.MediaPlan_clients_details_campaignes_services_dialog_Title,
    id: 'clientDetailsCampaigneServicesWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 600,
    height: 300,
    initComponent: function()
    {



        var window = this;
        this.listeners = {
            afterrender: function() {

                var kampanjaID = window.kampanja_id;

            }

        };

        this.tbar = Ext.create('Ext.toolbar.Toolbar', {
            items: [{
                    text: Lang.MediaPlan_clients_details_campaignes_services_dialog_btn_Add,
                    iconCls: 'scheduler-add',
                    handler: function() {
                        Ext.widget('clientdetailscampaignesservicedialog', {
                            campaigneID: window.kampanja_id
                        })
                    }
                }]
        });

        this.items = [{
                xtype: 'clientdetailscampaignesservicesgrid',
                kampanjaID: window.kampanja_id
            }];


        this.callParent(arguments);
    }

});

Ext.define('Mediaplan.mediaPlan.clients.details.CampaigneServices.Dialog', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesservicedialog',
    title: Lang.MediaPlan_clients_details_campaignes_services_dialog_Title,
    id: 'clientDetailsCampaigneServicesDialogWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 400,
    height: 120,
    initComponent: function()
    {

       // alert("dodavanje usluge");

        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'clientDetailsCampaigneServicesDialogForm',
                border: false,
                frame: false,
                labelWidth: 120,
                items: [{
                        xtype: 'hidden',
                        name: 'kampanjaID',
                        value: this.campaigneID
                    }, {
                        xtype: 'combobox',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignes_services_dialog_Name,
                        store: Ext.create('Ext.data.Store', {
                            fields: ['EntryID', 'EntryName'],
                            proxy: {
                                type: 'ajax',
                                url: '../App/Controllers/CenovnikUsluga.php',
                                actionMethods: {
                                    read: 'POST'
                                },
                                extraParams: {
                                    action: 'CenovnikUslugaGetForComboBox'
                                },
                                reader: {
                                    type: 'json',
                                    root: 'rows'
                                }
                            }
                        }),
                        name: 'cenovnikUslugaID',
                        queryMode: 'remote',
                        typeAhead: true,
                        queryParam: 'filter',
                        emptyText: Lang.Common_combobox_emptyText,
                        valueField: 'EntryID',
                        displayField: 'EntryName',
                        allowBlank: false,
                        width: 320
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
        var form = Ext.getCmp('clientDetailsCampaigneServicesDialogForm').getForm();


        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneServicesDialogWindow').getEl(), Lang.Saving);
            waitBox.show();

            var formAction = 'AddAdditionalServices';

            var fieldValues = form.getValues();

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Kampanja.php',
                params: {action: formAction, fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {
                        Ext.getCmp('clientDetailsCampaigneServicesDialogWindow').close();

                        //rebind grid
                        var grid = Ext.getCmp('clientDetailsCampaignesServicesGrid');
                        grid.kampanjaID = this.campaigneID;
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
    }
});


Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesSponsorshipWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesponsorshipwindow',
    title: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Title,
    id: 'clientDetailsCampaigneSponsorshipWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 500,
    autoHeight: true,
    initComponent: function()
    {


        //alert("sponzorstvo");


        var window = this;

        this.listeners = {
            show: function() {
                var clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                Ext.getCmp('clientDetailsCampaigneSponsorshipClientID').setValue(clientID);
            }

        };


        this.items = [{
                xtype: 'form',
                bodyPadding: 10,
                id: 'clientDetailsCampaigneSponsorshipForm',
                border: false,
                frame: false,
                labelWidth: 120,
                fileUpload: true,
                items: [{
						xtype: 'hidden',
						name: 'klijentID',
						id: 'clientDetailsCampaigneSponsorshipClientID'				
				},{
                        xtype: 'combobox',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Station,
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
                        id: 'sponsorshipDialogStationCombo',
                        name: 'radioStanicaID',
                        queryMode: 'remote',
                        typeAhead: true,
                        queryParam: 'filter',
                        emptyText: Lang.Common_combobox_emptyText,
                        valueField: 'EntryID',
                        displayField: 'EntryName',
                        allowBlank: false,
                        width: 350
                    }, {
                        xtype: 'combobox',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Show,
                        store: Ext.create('Ext.data.Store', {
                            fields: ['EntryID', 'EntryName'],
                            proxy: {
                                type: 'ajax',
                                url: '../App/Controllers/RadioStanicaProgram.php',
                                actionMethods: {
                                    read: 'POST'
                                },
                                extraParams: {
                                    action: 'RadioStanicaProgramGetForComboBox',
                                    radioStationID: ''
                                },
                                reader: {
                                    type: 'json',
                                    root: 'rows'
                                }
                            },
                            listeners: {
                                beforeload: {
                                    fn: function(store, records, successful, eOpts) {
                                        store.proxy.extraParams.radioStationID = Ext.getCmp('sponsorshipDialogStationCombo').getValue();
                                    }
                                }
                            }
                        }),
                        id: 'sponsorshipDialogShowCombo',
                        name: 'radioStanicaProgramID',
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
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_DateStart,
                        name: 'datumOd',
						format:'Y-m-d',
                        width: 250,
                        allowBlank: false
                    }, {
                        xtype: 'datefield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_DateEnd,
                        name: 'datumDo',
						format:'Y-m-d',
                        width: 250,
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Price,
                        name: 'cenaUkupno',
                        width: 250,
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Item1,
                        name: 'cobrending',
                        width: 200
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Item2,
                        name: 'najavaOdjava',
                        width: 200
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Item3,
                        name: 'premiumBlok',
                        width: 200
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Item4,
                        name: 'prsegment',
                        width: 200
                    }, {
                        xtype: 'textfield',
                        fieldLabel: Lang.MediaPlan_clients_details_campaignesSponsorship_dialog_Item5,
                        name: 'najavaEmisije',
                        width: 200
                    }]
            }],
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

        var form = Ext.getCmp('clientDetailsCampaigneSponsorshipForm').getForm();


        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneSponsorshipWindow').getEl(), Lang.CampaigneCreate);
            waitBox.show();

            var fieldValues = form.getValues();

            Ext.Ajax.request({
                timeout: 600000,
                url: '../App/Controllers/SablonSponzorstvo.php',
                params: {action: 'SablonSponzorstvoInsertUpdate', fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {
                        waitBox.hide();
                        Ext.getCmp('clientDetailsCampaigneSponsorshipWindow').close();
                        Ext.getCmp('clientDetailsCampaigneDetailsWindow').close();

                        //rebind grid
                        var grid1 = Ext.getCmp('campaignesGrid');
                        var grid2 = Ext.getCmp('clientDetailsCampaignesGrid');
                        if (grid1) {
                            grid1.reloadGrid(grid1)
                        }
                        ;
                        if (grid2) {
                            grid2.reloadGrid(grid2)
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








function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}










































Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesFinStatusWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesfinstatuswindow',
    title: Lang.MediaPlan_clients_details_campaignes_finStatus_dialog_Title,
    id: 'clientDetailsCampaigneFinStatusWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 400,
    autoHeight: true,
    //height:550,
    initComponent: function()
    {

        var window = this;
        this.listeners = {
            show: function() {

                var kampanjaID = Ext.getCmp('hdnCampaigneDetailsCapmaigneID').getValue();
                Ext.getCmp('clientDetailsCampaigneFinStatusCampaigneID').setValue(kampanjaID);
            }

        };

        this.items = [{
            xtype: 'form',
            bodyPadding: 10,
            id: 'clientDetailsCampaigneFinStatusForm',
            border: false,
            frame: false,
            labelWidth: 120,
            items: [{
                xtype: 'hidden',
                name: 'kampanjaID',
                id: 'clientDetailsCampaigneFinStatusCampaigneID'
            }, {
                xtype: 'combobox',
                fieldLabel: Lang.MediaPlan_clients_details_campaignes_finStatus_dialog_Status,
                store: Ext.create('Ext.data.Store', {
                    fields: ['EntryID', 'EntryName'],
                    proxy: {
                        type: 'ajax',
                        url: '../App/Controllers/FinansijskiStatusKampanja.php',
                        actionMethods: {
                            read: 'POST'
                        },
                        extraParams: {
                            action: 'FinansijskiStatusGetForComboBox'
                        },
                        reader: {
                            type: 'json',
                            root: 'rows'
                        }
                    }
                }),
                name: 'finansijskiStatusKampanjaID',
                queryMode: 'remote',
                typeAhead: true,
                queryParam: 'filter',
                emptyText: Lang.Common_combobox_emptyText,
                valueField: 'EntryID',
                displayField: 'EntryName',
                allowBlank: false,
                width: 300
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
        var form = Ext.getCmp('clientDetailsCampaigneFinStatusForm').getForm();



        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneFinStatusWindow').getEl(), Lang.Saving);
            waitBox.show();


            var fieldValues = form.getValues();

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Kampanja.php',
                params: {action: 'KampanjaPromeniFinansijskiStatus', fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {




                        //alert_obj_boban(response);



                        Ext.getCmp('clientDetailsCampaigneFinStatusWindow').close();
                        Ext.getCmp('clientDetailsCampaigneDetailsWindow').close();

                        //rebind grid
                        var grid1 = Ext.getCmp('campaignesGrid');
                        var grid2 = Ext.getCmp('clientDetailsCampaignesGrid');
                        if (grid1) {
                            grid1.reloadGrid(grid1)
                        }
                        ;
                        if (grid2) {
                            grid2.reloadGrid(grid2)
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
























Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesEditWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaigneseditwindow',
    title: "Izmena parametara kampanje",
    id: 'clientDetailsCampaigneEditWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 400,
    autoHeight: true,
    //height:550,
    initComponent: function()
    {

        var window = this;
        this.listeners = {
            show: function() {

                var kampanjaID = Ext.getCmp('hdnCampaigneDetailsCapmaigneID').getValue();
                Ext.getCmp('clientDetailsCampaigneEditCampaigneID').setValue(kampanjaID);






                var datumKraja = Ext.getCmp('DatumKraja').getValue();
                from = datumKraja.split(".");
                datumKraja=from[2]+"-"+from[1]+"-"+from[0];
                Ext.getCmp('clientDetailsCampaigneEditDatumKraja').setValue(datumKraja);


                var popust = Ext.getCmp('Popust').getValue();
                Ext.getCmp('clientDetailsCampaigneEditPopust').setValue(popust);


                var tipPlacanjaID = Ext.getCmp('TipPlacanjaID').getValue();
                Ext.getCmp('clientDetailsCampaigneEditTipPlacanjaID').setValue(tipPlacanjaID);



            }

        };

        this.items = [{
            xtype: 'form',
            bodyPadding: 10,
            id: 'clientDetailsCampaigneEditForm',
            border: false,
            frame: false,
            labelWidth: 120,
            items: [{
                xtype: 'hidden',
                name: 'kampanjaID',
                id: 'clientDetailsCampaigneEditCampaigneID'
            }, {
                xtype: 'fieldcontainer',
                layout: 'hbox',
                labelAlign: 'left',
                items: [ {
                    xtype: 'datefield',
                    labelAlign: 'right',
                    labelWidth: 150,
                    format: 'Y-m-d',
                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_DateEnd,
                    name: 'DatumKraja',
                    id: 'clientDetailsCampaigneEditDatumKraja',
                    allowBlank: false,
                    width: 300
                }]
            },{
                xtype: 'fieldcontainer',
                layout: 'hbox',
                labelAlign: 'left',
                items: [  {
                    xtype: 'textfield',
                    labelAlign: 'right',
                    labelWidth: 150,
                    fieldLabel: "Popust %",
                    name: 'popust',
                    id: 'clientDetailsCampaigneEditPopust',

                    width: 300,
                    allowBlank: false
                }]
            },



                {
                    xtype: 'combobox',
                    fieldLabel:'Tip Plaćanja',
                    labelAlign: 'right',
                    labelWidth: 150,
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
                    id: 'clientDetailsCampaigneEditTipPlacanjaID',
                    queryMode: 'remote',
                    typeAhead: true,
                    queryParam: 'filter',
                    emptyText: Lang.Common_combobox_emptyText,
                    valueField: 'EntryID',
                    displayField: 'EntryName',
                    allowBlank: false,
                    width: 250

                }






            ]
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
        var form = Ext.getCmp('clientDetailsCampaigneEditForm').getForm();



        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaigneEditWindow').getEl(), Lang.Saving);
            waitBox.show();


            var fieldValues = form.getValues();

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Kampanja.php',
                params: {action: 'KampanjaEdit', fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {




                        //alert_obj_boban(response);



                        Ext.getCmp('clientDetailsCampaigneEditWindow').close();
                        Ext.getCmp('clientDetailsCampaigneDetailsWindow').close();

                        //rebind grid
                        var grid1 = Ext.getCmp('campaignesGrid');
                        var grid2 = Ext.getCmp('clientDetailsCampaignesGrid');
                        if (grid1) {
                            grid1.reloadGrid(grid1)
                        }
                        ;
                        if (grid2) {
                            grid2.reloadGrid(grid2)
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










Ext.define('Mediaplan.mediaPlan.clients.details.SpotEditWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailsspoteditwindow',
    title: "Izmena naziva spota",
    id: 'clientDetailsSpotEditWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 600,
    autoHeight: true,
    height:200,

    initComponent: function()
    {

        var window = this;
        this.listeners = {
            show: function() {
               // alert_obj_boban(this.data);


                var radioStanicaID=this.data.RadioStanicaID;


                var spotName=this.data.SpotName;


                Ext.getCmp('clientDetailsSpotEditRadioStanicaID').setValue(radioStanicaID);

                Ext.getCmp('clientDetailsSpotEditSpotID').setValue(this.data.SpotID);
                Ext.getCmp('clientDetailsSpotEditSpotName').setValue(this.data.SpotName);



                Ext.Ajax.request({
                    url: '../App/Controllers/Common.php',
                    method: 'POST',
                    params: {
                        radioStanicaID: radioStanicaID,
                        action: 'getSpotsAutocomplete'
                    },
                    success: function(response){
                        var rows = Ext.decode(response.responseText).rows;
                        customarray = new Array();
                        rows.forEach(function(entry) {
                            customarray.push(entry);
                        });

                        actb_curr = document.getElementsByName("spotName")[0];
                        actb_curr.value = "";
                        var obj = actb(actb_curr, customarray);
                        Ext.getCmp('clientDetailsSpotEditSpotName').setValue(spotName);

                    }
                });


















            }

        };

        this.items = [{
            xtype: 'form',
            bodyPadding: 10,
            id: 'clientDetailsSpotEditForm',
            border: false,
            frame: false,
            labelWidth: 120,


            items: [


                {
                    xtype: 'hidden',
                    name: 'radioStanicaID',
                    id: 'clientDetailsSpotEditRadioStanicaID'
                },

                {
                xtype: 'hidden',
                name: 'spotID',
                id: 'clientDetailsSpotEditSpotID'
            },

                {
                xtype: 'fieldset',
                labelAlign: 'left',
                    height:'100%',
                    style:"border: 0;",
                items: [  {

                    xtype: 'textfield',
                    labelAlign: 'right',
                    labelWidth: 150,
                    fieldLabel: "Naziv Spota",
                    name: 'spotName',
                    id: 'clientDetailsSpotEditSpotName',
                    style:'overflow:visible;',
                    width: 450,
                    allowBlank: false,
                    listeners: {
                        'change': function(){
                            var station = Ext.getCmp('clientDetailsSpotEditRadioStanicaID').getValue();
                            if(station==null){
                                actb_curr = document.getElementsByName("spotName")[0];
                                actb_curr.value="";
                                alert("Radio Stanica wrong !");
                            }
                        }
                    }

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
        var form = Ext.getCmp('clientDetailsSpotEditForm').getForm();



        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsSpotEditWindow').getEl(), Lang.Saving);
            waitBox.show();


            var fieldValues = form.getValues();

            Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Spot.php',
                params: {action: 'SpotEdit', fieldValues: Ext.encode(fieldValues)},
                success: function(response, request) {
                    waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {


                        Ext.getCmp('clientDetailsSpotEditWindow').close();

                        //rebind grid

                        var grid1 = Ext.getCmp('clientDetailsCampaignesSpotsGrid');

                        var grid2 = Ext.getCmp('spornoSporneReklameGrid');



                        if (grid1) {
                            grid1.store.load();
                        }


                        if (grid2) {
                            grid2.store.load();
                        }




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
































































Ext.define('Mediaplan.mediaPlan.clients.details.Campaignes2Window', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.clientdetailscampaignes2window',
    id:'clientDetailsCampaignes2Window',
    border:false,
    frame:false,
    padding:0,
    width: '100%',
    height:'200',
    initComponent: function ()
    {

        var window = this;


        this.items =  new Ext.create('Ext.tab.Panel', {
            deferredRender: false,
            activeTab: 0,
            id:'clientDetailsCampaignes2TabPanel',
            items: [
            {
                title:'Fakture',
                id:'clientDetailsCampaignesFacturesTab',
                autoScroll: true,
                layout:'fit',
                items:[{
                    xtype:'clientdetailscampaignesfacturesgrid',
                    height:172
                }],
                listeners: {
                    activate:function(){
/*
                        var kampanjaID = Ext.getCmp('clientDetailsKampanja2ID').getValue();

                        Ext.getCmp('clientDetailsCampaignesFacturesGrid').store.proxy.extraParams.kampanjaID = kampanjaID;
                        Ext.getCmp('clientDetailsCampaignesFacturesGrid').store.load();
*/
                    }

                }
            }/*,



             {
             title:"Fakture",
             id:'clientDetailsOfferFaktureTab',
             autoScroll: true,
             layout:'fit',
             items:[{
             xtype:'clientdetailsoffersfakturegrid',
             height:237
             }],
             listeners: {
             activate:function(){
             var ponudaID = Ext.getCmp('clientDetailsOfferID').getValue();
             Ext.getCmp('clientDetailsOffersHistoryGrid').store.proxy.extraParams.ponudaID = ponudaID;
             Ext.getCmp('clientDetailsOffersHistoryGrid').store.load();
             }

             }
             }*/








                /*,{
                 title:Lang.MediaPlan_clients_details_offers_window_tab_OfferCampaignes,
                 id:'clientDetailsOfferCampaigneTab',
                 autoScroll: true,
                 layout:'fit',
                 items:[{
                 xtype:'clientdetailsofferscampaignegrid',
                 height:237
                 }],
                 listeners: {
                 activate:function(){
                 var ponudaID = Ext.getCmp('clientDetailsOfferID').getValue();
                 Ext.getCmp('clientDetailsOffersCampaigneGrid').store.proxy.extraParams.ponudaID = ponudaID;
                 Ext.getCmp('clientDetailsOffersCampaigneGrid').store.load();
                 }

                 }
                 }*/]
        });


        this.callParent(arguments);
    }


});
































Ext.define('Mediaplan.mediaPlan.clients.details.CampaigneDocumentWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignedocumentwindow',

    title: Lang.MediaPlan_clients_details_offers_offerdocument_dialog_Title,
    id:'clientsDetailsCampaigneDocumentWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'grid-rowaction-document',
    modal: true,
    width: 550,
    autoHeight: true,
    initComponent: function ()
    {

        this.listeners = {
            show: function(){
                Ext.getCmp('hdnSpotNum').setValue(this.entryID);
                Ext.getCmp('hdnRadioStanicaID').setValue(this.radioStanicaID);
            }

        };

        this.items = [{
            xtype:'form',
            bodyPadding: 10,
            id: 'uploadSpot',
            border: false,
            frame: false,
            labelWidth: 120,
            fileUpload: true,
            items:[{
                xtype:'hidden',
                name:'spotNum',
                id:'hdnSpotNum'
            },{
                xtype:'textfield',
                inputType:'file',
                fieldLabel:Lang.MediaPlan_clients_details_offers_offerdocument_dialog_Attach,
                name:'prilog',
                id:'prilog',
                width:450
            },
                {
                    xtype:'hidden',
                    name:'radioStanicaID',
                    id:'hdnRadioStanicaID'
                }

            ]
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
        var form = Ext.getCmp('uploadSpot').getForm();


        if (form.isValid()) {


            var waitBox = Common.loadingBox(Ext.getCmp('clientsDetailsCampaigneDocumentWindow').getEl(), Lang.Saving);
            waitBox.show();


            form.submit({
                //url: '../App/Controllers/PonudaDokument.php?action=PonudaDokumentInsertUpdate',
                url: '../App/Controllers/Spot.php?action=SpotUpload',
                success: function (fp,o) {



                    var spot_name=o.result.msg;
                    Ext.getCmp('clientsDetailsCampaigneDocumentWindow').close();







                    Ext.Ajax.request({
                        url: '../App/Controllers/Cron.php',
                        method: 'POST',
                        params: {
                            action: 'cron_xml'
                        },
                        success: function(response){
















                            var station = Ext.getCmp('radioStanicaID').getValue();

                            Ext.Ajax.request({
                                url: '../App/Controllers/Common.php',
                                method: 'POST',
                                sync:true,
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


                                    var fieldValues = form.getValues();
                                    //alert('spot'+fieldValues.spotNum+'naziv');
                                    Ext.getCmp('spot'+fieldValues.spotNum+'naziv').setValue(spot_name);
                                    waitBox.hide();

                                }
                            });













                        }
                    });


                },
                failure: function (fp, o) {
                    waitBox.hide();
                    alert(o.result.msg);
                }


            });

        }
    }
});

















Ext.define('Mediaplan.mediaPlan.clients.details.CampaigneDocumentWindow2', {
    extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignedocumentwindow2',

    title: Lang.MediaPlan_clients_details_offers_offerdocument_dialog_Title,
    id:'clientsDetailsCampaigneDocumentWindow2',
    layout: 'fit',
    autoShow: true,
    iconCls: 'grid-rowaction-document',
    modal: true,
    width: 550,
    autoHeight: true,
    initComponent: function ()
    {

        this.items = [{
            xtype:'form',
            bodyPadding: 10,
            id: 'uploadSpot',
            border: false,
            frame: false,
            labelWidth: 120,
            fileUpload: true,
            items:[{
                xtype:'hidden',
                name:'spotNum',
                id:'hdnSpotNum'
            },
                {
                    xtype: 'combobox',
                    fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_Station,
                    labelAlign: 'left',
                    labelWidth: 100,
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
                    name: 'radioStanicaID',
                    id: 'radioStanicaID',
                    queryMode: 'remote',
                    typeAhead: true,
                    queryParam: 'filter',
                    emptyText: Lang.Common_combobox_emptyText,
                    valueField: 'EntryID',
                    displayField: 'EntryName',
                    allowBlank: false,
                    width: 350
                }
                ,{
                xtype:'textfield',
                inputType:'file',
                fieldLabel:Lang.MediaPlan_clients_details_offers_offerdocument_dialog_Attach,
                labelAlign: 'left',
                labelWidth: 100,
                name:'prilog',
                id:'prilog',
                width:350
            }



            ]
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
        var form = Ext.getCmp('uploadSpot').getForm();


        if (form.isValid()) {


            var waitBox = Common.loadingBox(Ext.getCmp('clientsDetailsCampaigneDocumentWindow2').getEl(), Lang.Saving);
            waitBox.show();


            form.submit({
                //url: '../App/Controllers/PonudaDokument.php?action=PonudaDokumentInsertUpdate',
                url: '../App/Controllers/Spot.php?action=SpotUpload',
                success: function (fp,o) {



                    var spot_name=o.result.msg;
                    Ext.getCmp('clientsDetailsCampaigneDocumentWindow2').close();



                    Ext.Ajax.request({
                        url: '../App/Controllers/Cron.php',
                        method: 'POST',
                        params: {
                            action: 'cron_xml'
                        },
                        success: function(response){

                            waitBox.hide();

                        }
                    });


                },
                failure: function (fp, o) {
                    waitBox.hide();
                    alert(o.result.msg);
                }


            });

        }
    }
});