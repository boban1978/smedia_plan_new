Ext.define('Mediaplan.mediaPlan.clients.details.OffersGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailsoffersgrid',
        frame:false,
        border:false,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsOffersGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['PonudaID','Sadrzaj','Status','Vrednost','Datum','Uneo','Kampanja'],
                            remoteSort: true,
                            loadMask: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Ponuda.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'PonudaGetList',
                                            klijentID:''
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
                                Ext.getCmp('clientDetailsOffersTabPanel').setActiveTab(Ext.getCmp('clientDetailsOfferDataTab'));
                                Ext.getCmp('clientDetailsOfferID').setValue(record.data.PonudaID);
                                Ext.getCmp('clientDetailsOfferDetailsDate').setValue(record.data.Datum);
                                Ext.getCmp('clientDetailsOfferDetailsUser').setValue(record.data.Uneo);
                                //Ext.getCmp('clientDetailsOfferDetailsStatus').setValue(record.data.Status);
                                Ext.getCmp('clientDetailsOfferDetailsStatus').setValue('nesto');
                                Ext.getCmp('clientDetailsOfferDetailsValue').setValue(record.data.Vrednost);
                                Ext.getCmp('clientDetailsOfferDetailsOffer').setValue(record.data.Sadrzaj);
                            }
                        },
			columns: [  
                            { xtype: 'rownumberer', width: 30, sortable: false},
                            { header: Lang.MediaPlan_clients_details_offers_grid_column_Status, dataIndex: 'Status',width:200},
                            { header: Lang.MediaPlan_clients_details_offers_grid_column_Date, dataIndex: 'Datum',width:90},
                            { header: 'Kampanja', dataIndex: 'Kampanja',width:200},
                            { header: 'Vrednost', dataIndex: 'Vrednost',width:100},
                            { header: Lang.MediaPlan_clients_details_offers_grid_column_User, dataIndex: 'Uneo',flex:1},
                            {
                                xtype: 'rowactions',
                                actions:[/*{
                                    iconCls: 'grid-rowaction-note',
                                    qtip: Lang.Common_Grid_toolTipAddNote,
                                    action: 'rowActionNote',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.OfferNoteWindow',{
                                            entryID:record.data.PonudaID
                                        });
                                    }   
                                },*/{
                                    iconCls: 'grid-rowaction-document',
                                    qtip: Lang.Common_Grid_toolTipAddDocument,
                                    action: 'rowActionAttach',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.OfferDocumentWindow',{
                                            entryID:record.data.PonudaID
                                        });
                                    }   
                                },/*{
                                    iconCls: 'grid-rowaction-campaigne',
                                    qtip: Lang.Common_Grid_toolTipAddCampaigne,
                                    action: 'rowActionCampaigne',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.CampaignesWindow',{
                                            offerID:record.data.PonudaID
                                        });
                                    }   
                                },*/{
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
                                                                var waitBox = Common.loadingBox(Ext.getBody(), Lang.Deleting);
                                                                waitBox.show();

                                                                Ext.Ajax.request({
                                                                    timeout: Common.Timeout,
                                                                    url: '../App/Controllers/Ponuda.php',
                                                                    params: { action: 'PonudaDelete', entryID: record.data.PonudaID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {

                                                                                var grid = Ext.getCmp('clientDetailsOffersGrid');
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







Ext.define('Mediaplan.mediaPlan.clients.details.OffersDocumentsGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailsoffersdocumentsgrid',
        frame:false,
        border:false,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsOffersDocumentsGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['PonudaDokumentID','Naziv','Link','Datum','Uneo'],
                            remoteSort: true,
                            loadMask: true,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/PonudaDokument.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'PonudaDokumentGetList',
                                            ponudaID:''
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
                            { header: Lang.MediaPlan_clients_details_offersDocuments_grid_column_Name, width:100, dataIndex: 'Naziv',flex:1},
                            { header: Lang.MediaPlan_clients_details_offersDocuments_grid_Date, dataIndex: 'Datum',width:90},
                            { header: Lang.MediaPlan_clients_details_offersDocuments_grid_User, dataIndex: 'Uneo',width:120},
                            { dataIndex: 'Link',renderer:Format.attachment,width:30},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-delete',
                                    qtip: Lang.Common_Grid_toolTipDeleteRow,
                                    action: 'rowActionDelete',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                            Ext.MessageBox.confirm(
                                                    Lang.Message_Title,
                                                    Lang.Common_Confirm,
                                                    function (button) {
                                                        if (button === 'yes') {
                                                                var waitBox = Common.loadingBox(Ext.getBody(), Lang.Deleting);
                                                                waitBox.show();

                                                                Ext.Ajax.request({
                                                                    timeout: Common.Timeout,
                                                                    url: '../App/Controllers/PonudaDokument.php',
                                                                    params: { action: 'PonudaDokumentDelete', entryID: record.data.PonudaDokumentID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {

                                                                                var grid = Ext.getCmp('clientDetailsOffersDocumentsGrid');
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
                                           );
                                    }
                                }],
                                keepSelection: true
                            }
                        ]
		});


		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            grid.store.load();
        }
});



Ext.define('Mediaplan.mediaPlan.clients.details.OffersNotesGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailsoffersnotesgrid',
        frame:false,
        border:false,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsOffersNotesGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['PonudaNapomenaID','Napomena','Datum','Uneo'],
                            remoteSort: true,
                            loadMask: true,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/PonudaNapomena.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'PonudaNapomeneGetList',
                                            ponudaID:''
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
                            { header: Lang.MediaPlan_clients_details_offersNotes_grid_column_Note,dataIndex: 'Napomena',flex:1},
                            { header: Lang.MediaPlan_clients_details_offersNotes_grid_column_Date, dataIndex: 'Datum',width:90},
                            { header: Lang.MediaPlan_clients_details_offersNotes_grid_column_User, dataIndex: 'Uneo',width:100},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-delete',
                                    qtip: Lang.Common_Grid_toolTipDeleteRow,
                                    action: 'rowActionDelete',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                            Ext.MessageBox.confirm(
                                                    Lang.Message_Title,
                                                    Lang.Common_Confirm,
                                                    function (button) {
                                                        if (button === 'yes') {
                                                                var waitBox = Common.loadingBox(Ext.getBody(), Lang.Deleting);
                                                                waitBox.show();

                                                                Ext.Ajax.request({
                                                                    timeout: Common.Timeout,
                                                                    url: '../App/Controllers/PonudaNapomena.php',
                                                                    params: { action: 'PonudaNapomenaDelete', entryID: record.data.PonudaNapomenaID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {

                                                                                var grid = Ext.getCmp('clientDetailsOffersNotesGrid');
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
                                           );
                                    }
                                }],
                                keepSelection: true
                            }
                        ]
		});

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            grid.store.load();
        }
});


Ext.define('Mediaplan.mediaPlan.clients.details.OffersHistoryGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailsoffershistorygrid',
        frame:false,
        border:false,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsOffersHistoryGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['PonudaIstorijaID','Status','Uneo','Datum','Napomena','MediaPlan'],
                            remoteSort: true,
                            loadMask: true,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/PonudaIstorija.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'PonudaIstorijaGetList',
                                            ponudaID:''
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
                            { header: Lang.MediaPlan_clients_details_offersHistory_grid_column_Status,dataIndex: 'Status',flex:1},
                            { header: 'Napomena', dataIndex: 'Napomena',width:200},


                {
                    header:    "Media plan",
                    dataIndex: "MediaPlan",
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
                            if(record.data.MediaPlan){
                                window.open("../App/Controllers/PonudaIstorija.php?PonudaIstorijaID=" + record.data.PonudaIstorijaID + "&action=ShowMediaPlan", "_newwindow", "width=400,height=200,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
                            }
                        }
                    }
                },


/*
                {
                    xtype: 'rowactions',
                    actions: [{
                        iconCls: 'grid-rowaction-pdf',
                        qtip: "Media Plan",
                        action: 'rowActionEdit',
                        callback: function (grid, record, action, idx, col, e, target) {
                            window.open("../App/Controllers/PonudaIstorija.php?PonudaIstorijaID=" + record.data.PonudaIstorijaID + "&action=ShowMediaPlan", "_newwindow", "width=400,height=200,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
                        }
                    }],
                    keepSelection: true

                },*/







                            { header: Lang.MediaPlan_clients_details_offersHistory_grid_column_User, dataIndex: 'Uneo',width:100},
                            { header: Lang.MediaPlan_clients_details_offersHistory_grid_column_Date, dataIndex: 'Datum',width:90}
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


Ext.define('Mediaplan.mediaPlan.clients.details.OffersCampaigneGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailsofferscampaignegrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsOffersCampaigneGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['KampanjaID','RadioStanicaID','Agencija','Klijent','Delatnost','Naziv','RadioStanica', 'DatumPocetka', 'DatumKraja', 'Ucestalost','SpotTrajanje','Status','NacinPlacanja','FinansijskiStatus','CenaUkupno','KorisnikUneo','VremeZaPotvrdu','VremePostavke','VremePotvrde','SpotLink','PrilogIzjava'],
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
                                            action: 'KampanjaPonudaGetList',
                                            ponudaID:''
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
                            { header: Lang.MediaPlan_campaignes_grid_column_Agency, width:70, dataIndex: 'Agencija'},
                            { header: Lang.MediaPlan_campaignes_grid_column_Name, dataIndex: 'Naziv',flex:1},
							{ header: Lang.MediaPlan_campaignes_grid_column_Station, dataIndex: 'RadioStanica',flex:1},
                            { header: Lang.MediaPlan_campaignes_grid_column_StartDate, dataIndex: 'DatumPocetka',width:80},
                            { header: Lang.MediaPlan_campaignes_grid_column_EndDate, dataIndex: 'DatumKraja',width:80},
                            //{ header: Lang.MediaPlan_campaignes_grid_column_Frequency, dataIndex: 'Ucestalost',width:70},
                            //{ header: Lang.MediaPlan_campaignes_grid_column_SpotDuration, dataIndex: 'SpotTrajanje',renderer:Format.seconds,width:80},
                            { header: Lang.MediaPlan_campaignes_grid_column_Status, dataIndex: 'Status',width:60},
                            { header: Lang.MediaPlan_campaignes_grid_column_FinStatus, dataIndex: 'FinansijskiStatus',width:90},
                            { dataIndex: 'SpotLink',renderer:Format.spot,width:30},
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
														var campaigneId = data.campaigneID;
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
                                                            price: campaignePrice
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
                                            Ext.MessageBox.confirm(
                                                    Lang.Message_Title,
                                                    Lang.Common_Confirm,
                                                    function (button) {
                                                        if (button === 'yes') {

                                                        }
                                                            
                                                    }
                                           );
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










