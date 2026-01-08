Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailscampaignesgrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;




             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsCampaignesGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['KampanjaID','RadioStanicaID','Agencija','Klijent','Brend','Naziv','RadioStanica', 'DatumPocetka', 'DatumKraja', 'Ucestalost','SpotTrajanje','Status','NacinPlacanja','FinansijskiStatus','Popust','CenaUkupno','KorisnikUneo','VremeZaPotvrdu','VremePostavke','VremePotvrde','SpotLink','PrilogIzjava','TipPlacanjaID'],
                            remoteSort: true,
                            loadMask: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Kampanja.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'KampanjaGetList',
                                            klijentID:this.clientID
                                    },
                                    reader: {
                                            totalProperty: 'total',
                                            type: 'json',
                                            root: 'rows',
                                            successProperty: 'success',
                                            messageProperty: 'message'
                                    }
                            }
			}),







                 listeners:{
                     select:function(sm,record,index){
                        //alert(record.data.KampanjaID);

/*
                         Ext.getCmp('clientDetailsCampaignes2TabPanel').setActiveTab(Ext.getCmp('clientDetailsCampaignesFacturesTab'));
                         Ext.getCmp('clientDetailsKampanja2ID').setValue(record.data.KampanjaID);
*/

                         Ext.getCmp('clientDetailsCampaignesFacturesGrid').store.proxy.extraParams.kampanjaID = record.data.KampanjaID;
                         Ext.getCmp('clientDetailsCampaignesFacturesGrid').store.load();





                     }
                 },










			columns: [  
                            { xtype: 'rownumberer', width: 30, sortable: false},
                            { header: 'KampanjaID', width:70, dataIndex: 'KampanjaID'},
                            { header: Lang.MediaPlan_campaignes_grid_column_Agency, width:70, dataIndex: 'Agencija'},
                            { header: Lang.MediaPlan_campaignes_grid_column_Name, dataIndex: 'Naziv',flex:1},
							{ header: Lang.MediaPlan_campaignes_grid_column_Station, dataIndex: 'RadioStanica',flex:1},
                            { header: Lang.MediaPlan_campaignes_grid_column_StartDate, dataIndex: 'DatumPocetka',width:80},
                            { header: Lang.MediaPlan_campaignes_grid_column_EndDate, dataIndex: 'DatumKraja',width:80},
                            //{ header: Lang.MediaPlan_campaignes_grid_column_Frequency, dataIndex: 'Ucestalost',width:70},
                            //{ header: Lang.MediaPlan_campaignes_grid_column_SpotDuration, dataIndex: 'SpotTrajanje',renderer:Format.seconds,width:80},
                            { header: Lang.MediaPlan_campaignes_grid_column_Status, dataIndex: 'Status',width:60},
                            { header: Lang.MediaPlan_campaignes_grid_column_FinStatus, dataIndex: 'FinansijskiStatus',width:90},

                            //{ dataIndex: 'SpotLink',renderer:Format.spot,width:30},
                            { dataIndex: 'PrilogIzjava',renderer:Format.attachment,width:30},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'sound',
                                    qtip: Lang.Common_Grid_toolTipShowSpots,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.CampaigneSpotWindow',{
                                            kampanja_id:record.data.KampanjaID
                                        });
                                    } 								
								},{
                                    iconCls: 'table',
                                    qtip: Lang.Common_Grid_toolTipCampaigneReview,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaignesGrid').getEl(), Lang.Loading);
                                            waitBox.show();


                                            Ext.Ajax.request({
                                                timeout: Common.Timeout,
                                                url: '../App/Controllers/Kampanja.php',
                                                params: { action: 'KampanjaPregledEmitovanja', kampanjaID:record.data.KampanjaID, radioStanicaID:record.data.RadioStanicaID  },
                                                success: function (response, request) {
                                                    waitBox.hide();

                                                    if (Common.IsAjaxResponseSuccessfull(response)) {
                                                        waitBox.hide();
                                                        
														//var showConfirm = false;
														var showContextMenu = false;
														var showAddBtn = false;
														
														for (i = 0; i < Common.allUserPermisions.length; i++) {
															var p=Common.allUserPermisions[i];
														  
															if(p == 215){
																//showConfirm = true;
																showContextMenu = true;
																showAddBtn = true;
															}; 
														};
																												
                                                        
                                                        var data = Ext.decode(response.responseText).data;
                                                        var schedulerConfig = Common.schConfig;
                                                        //schedulerConfig.showContextmenu = false;
                                                        schedulerConfig.commercials = data.schedulerCommercial;
                                                        schedulerConfig.dates = data.schedulerDates;
                                                        var campaignePrice = data.capmaignePrice;
                                                        var popust = data.popust;
														var campaigneId = data.campaigneID;

                                                        var sablonId = data.sablonId;

                                                        var spotBroj = data.spotBroj;

														if (showContextMenu) {
																schedulerConfig.showContextmenu = true;							
														} else {
															    schedulerConfig.showContextmenu = false;
														} ;
                                                        
                                                        Ext.widget('clientdetailscampaignepreviewwindow',{
                                                            height:575,
															showTbar:showAddBtn,
                                                            config:schedulerConfig,
															campaigneID:campaigneId,
                                                            price: campaignePrice,
                                                            popust: popust,
                                                            sablonId: sablonId,
                                                            spotBroj: spotBroj
                                                        });

														Ext.getCmp('campaignePreviewBtnNo').hide();
														Ext.getCmp('campaignePreviewBtnYes').hide();							

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
                                },{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.CampaigneDetailsWindow',{
                                            data:record.data
                                        });
                                    }   
                                },{
                                    iconCls: 'vcard',
                                    qtip: Lang.Common_Grid_toolTipShowServices,
                                    action: 'rowActionService',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.CampaigneServicesWindow',{
                                            kampanja_id:record.data.KampanjaID
                                        });
                                    } 								
								}


                                    ,{
                                    iconCls: 'grid-rowaction-pdf',
                                    qtip: Lang.Common_Grid_toolTipPDF,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        window.open("../App/Controllers/KampanjaPdf.php?kampanjaID="+record.data.KampanjaID, "_newwindow", "width=400,height=200,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
                                    }   
                                },{
                                    iconCls: 'grid-rowaction-delete',
                                    qtip: Lang.Common_Grid_toolTipDeleteRow,
                                    action: 'rowActionDelete',
                                    callback: function (grid, record, action, idx, col, e, target) {
										var allowDelete = false;
										for (i = 0; i < Common.allUserPermisions.length; i++) {
											var p=Common.allUserPermisions[i];
											if(p == 335){
												allowDelete = true;
												
											};
										};
										if (allowDelete) {
                                            Ext.MessageBox.confirm(
                                                    Lang.Message_Title,
                                                    Lang.Common_Confirm,
                                                    function (button) {
                                                        if (button === 'yes') {


                                                            var entryID = record.data.KampanjaID;
                                                            //alert(entryID);

                                                            Ext.Ajax.request({
                                                                timeout: Common.Timeout,
                                                                url: '../App/Controllers/Kampanja.php',
                                                                params: {
                                                                    entryID: entryID,
                                                                    action: 'KampanjaDelete'
                                                                },

                                                                success: function (response, request) {


                                                                    //alert_obj_boban(response);


                                                                    //waitBox.hide();

                                                                    if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                        //Ext.getCmp('clientDetailsBrendDialogWindow').close();

                                                                        //rebind grid
                                                                        var grid = Ext.getCmp('clientDetailsCampaignesGrid');
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
                                                                }/*,



                                                                success: function(response){
                                                                    alert_obj_boban(response);

                                                                    //var data = Ext.decode(response.responseText).data;

                                                                    //var daniZaEmitovanje=data.daniZaEmitovanje;

                                                                    alert(entryID);

                                                                }*/




                                                            });


























                                                        }
                                                            
                                                    }
                                           );
										} else {
											Ext.Msg.show({
												title: Lang.Message_Title,
												msg: 'Nemate odgovorajuću privilegiju za ovu akciju',
												buttons: Ext.Msg.OK,
												icon: Ext.MessageBox.INFO
											});
										};
                                    }
                                }],
                                keepSelection: true
                            }
                        ]
		});
                
                this.bbar = Ext.create('Ext.PagingToolbar', {
                            pageSize: Common.PageSize,
                            store: grid.store,
                            displayInfo: true,
                            plugins: Ext.create('Ext.ux.SlidingPager', {})
                });

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            grid.store.load();
        }
});

Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesSpotsGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailscampaignesspotsgrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
            id:'clientDetailsCampaignesSpotsGrid',
            loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['SpotID','SpotName','Ucestalost','SpotTrajanje','RadioStanicaID','Glas'],
                            remoteSort: true,
							autoLoad:true,
                            loadMask: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Kampanja.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'KampanjaGetSpotList',
                                            kampanjaID:grid.kampanjaID
                                    },
                                    reader: {
                                            totalProperty: 'total',
                                            type: 'json',
                                            root: 'rows',
                                            successProperty: 'success',
                                            messageProperty: 'message'
                                    }
                            }
			}),
			columns: [  
                            { xtype: 'rownumberer', width: 30, sortable: false},
                            { header: Lang.MediaPlan_campaignes_spots_grid_column_Name, flex:1, dataIndex: 'SpotName'},
                            { header: 'Glas', flex:1, dataIndex: 'Glas'},
                            { header: Lang.MediaPlan_campaignes_grid_spots_column_Frequency, dataIndex: 'Ucestalost',width:70},
							{ header: Lang.MediaPlan_campaignes_grid_spots_column_Duration, dataIndex: 'SpotTrajanje',width:60}//,
                            //{ dataIndex: 'SpotLink',renderer:Format.spot,width:30}

,
                {
                    xtype: 'rowactions',
                    actions:[

                        {
                            iconCls: 'grid-rowaction-edit',
                            qtip: Lang.Common_Grid_toolTipEditRow,
                            action: 'rowActionEdit',
                            callback: function (grid, record, action, idx, col, e, target) {
                                /*
                                Ext.create('Mediaplan.mediaPlan.clients.details.CampaigneServicesWindow',{
                                    kampanja_id:record.data.KampanjaID
                                });*/

                                Ext.widget('clientdetailsspoteditwindow',{
                                    data:record.data
                                });

                            }
                        }

                    ]
                }







            ]
		});
                

		this.callParent(arguments);
	}
});


Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesServicesGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailscampaignesservicesgrid',
    frame:false,
    border:true,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
            id:'clientDetailsCampaignesServicesGrid',
            loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['CenovnikUslugaID','Naziv','Cena'],
                            remoteSort: true,
							autoLoad:true,
                            loadMask: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Kampanja.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'GetAdditionalServices',
                                            kampanjaID:grid.kampanjaID
                                    },
                                    reader: {
                                            totalProperty: 'total',
                                            type: 'json',
                                            root: 'rows',
                                            successProperty: 'success',
                                            messageProperty: 'message'
                                    }
                            }
			}),
			columns: [  
                            { xtype: 'rownumberer', width: 30, sortable: false},
                            { header: Lang.MediaPlan_campaignes_grid_services_column_Name, flex:1, dataIndex: 'Naziv'},
                            { header: Lang.MediaPlan_campaignes_grid_services_column_Price, dataIndex: 'Cena',width:100}
                        ]
		});
                

		this.callParent(arguments);
	}
});


































Ext.define('Mediaplan.mediaPlan.clients.details.CampaignesFacturesGrid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.clientdetailscampaignesfacturesgrid',
    frame:false,
    border:false,
    initComponent: function ()
    {
        var grid = this;

        Ext.apply(this, {
            frame: true,
            id:'clientDetailsCampaignesFacturesGrid',
            loadMask: true,
            store:  Ext.create('Ext.data.Store', {
                fields: ['FakturaID','Dokument'],
                remoteSort: true,
                loadMask: true,
                proxy: {
                    type: 'ajax',
                    url: '../App/Controllers/Faktura.php',
                    actionMethods: {
                        read: 'POST'
                    },
                    extraParams: {
                        action: 'FakturaGetList',
                        kampanjaID:''
                    },
                    reader: {
                        totalProperty: 'total',
                        type: 'json',
                        root: 'rows',
                        successProperty: 'success',
                        messageProperty: 'message'
                    }
                }
            }),
            columns: [
                { xtype: 'rownumberer', width: 30, sortable: false},
                { header: 'Faktura ID',dataIndex: 'FakturaID',flex:1},


                {
                    header:    "Dokument",
                    dataIndex: "Dokument",
                    width:     70,
                    renderer: function(value, metaData){
                        metaData.style += "text-align: center!important;";
                        if(value) {
                            return "<img src=\"Images/Icons/page_16.png\" style=\"cursor:pointer;\"/>";
                        }
                        return value;
                    },
                    listeners: {
                        click: function(gridView, htmlSomething, rowIndex, columnIndex, theEvent){
                            var record = gridView.getStore().getAt(rowIndex);  // Get the Record
                            if(record.data.Dokument){
                                window.open("../App/Controllers/Faktura.php?fakturaID=" + record.data.FakturaID + "&action=ShowDocument", "_newwindow", "width=400,height=200,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
                            }
                        }
                    }
                }/*,
                { header: Lang.MediaPlan_clients_details_offersHistory_grid_column_Date, dataIndex: 'Datum',width:90}*/
            ]
        });

        this.bbar = Ext.create('Ext.PagingToolbar', {
            pageSize: Common.PageSize,
            store: grid.store,
            displayInfo: true,
            plugins: Ext.create('Ext.ux.SlidingPager', {})
        });

        this.callParent(arguments);
    },

    reloadGrid: function(grid){
        grid.store.load();
    }
});