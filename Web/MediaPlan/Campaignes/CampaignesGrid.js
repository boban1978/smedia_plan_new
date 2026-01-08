Ext.define('Mediaplan.mediaPlan.campaignes.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.mediaplancampaignesgrid',

	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'campaignesGrid',
			frame:false,
                        border:true,
                        stripeRows: true,
                        listeners: {
                            afterrender: function (grid) { 
                                grid.store.load();  
                            },
                            itemdblclick: function(grid,record, item, index,e){
                                Ext.create('Mediaplan.mediaPlan.clients.details.CampaigneDetailsWindow',{
                                    data:record.data
                                });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['KampanjaID','RadioStanicaID','Agencija','Klijent','Brend','Naziv','RadioStanica','DatumPocetka', 'DatumKraja', 'Ucestalost','SpotTrajanje','Status','NacinPlacanja','FinansijskiStatus','Popust','CenaUkupno','KorisnikUneo','VremeZaPotvrdu','VremePostavke','VremePotvrde','SpotLink','PrilogIzjava','TipPlacanjaID'],
                            remoteSort: true,
                            pageSize:Common.PageSize,
                            listeners:{
                                beforeload:function(){
                                    grid.mask = new Ext.LoadMask(grid, {msg:Lang.Loading});
                                    grid.mask.show();
                                    
                                },
                                load:function(){
                                    var mask = new Ext.LoadMask(grid, {msg:Lang.Loading});
                                    grid.mask.hide()
                                }
                            },
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Kampanja.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'KampanjaGetList',
                                            filterValues:''
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
			//}],
			columns: [  
                            { xtype: 'rownumberer', width: 30, sortable: false},
                            { header: 'KampanjaID', width:100, dataIndex: 'KampanjaID'},
                            { header: Lang.MediaPlan_campaignes_grid_column_Agency, width:100, dataIndex: 'Agencija'},
                            { header: Lang.MediaPlan_campaignes_grid_column_Client, width:100, dataIndex: 'Klijent'},
							{ header: Lang.MediaPlan_campaignes_grid_column_Activity, width:100, dataIndex: 'Brend'},
							{ header: Lang.MediaPlan_campaignes_grid_column_Station, width:100, dataIndex: 'RadioStanica'},
                            { header: Lang.MediaPlan_campaignes_grid_column_Name, dataIndex: 'Naziv',flex:1},
                            { header: Lang.MediaPlan_campaignes_grid_column_StartDate, dataIndex: 'DatumPocetka',width:100},
                            { header: Lang.MediaPlan_campaignes_grid_column_EndDate, dataIndex: 'DatumKraja',width:100},
                            //{ header: Lang.MediaPlan_campaignes_grid_column_Frequency, dataIndex: 'Ucestalost',width:80},
                            //{ header: Lang.MediaPlan_campaignes_grid_column_SpotDuration, dataIndex: 'SpotTrajanje',renderer:Format.seconds,width:100},
                            { header: Lang.MediaPlan_campaignes_grid_column_Status, dataIndex: 'Status',width:100},
                            { header: Lang.MediaPlan_campaignes_grid_column_FinStatus, dataIndex: 'FinansijskiStatus',width:120},



                            { header: "Uneo", dataIndex: 'KorisnikUneo',width:120},

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



                                            var waitBox = Common.loadingBox(Ext.getCmp('campaignesGrid').getEl(), Lang.Loading);
                                            waitBox.show();


                                            Ext.Ajax.request({
                                                timeout: Common.Timeout,
                                                url: '../App/Controllers/Kampanja.php',
                                                params: { action: 'KampanjaPregledEmitovanja', kampanjaID:record.data.KampanjaID, radioStanicaID:record.data.RadioStanicaID },
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
															}
														}






                                                        var data = Ext.decode(response.responseText).data;


//alert_obj_boban(data);


                                                        var schedulerConfig = Common.schConfig;
                                                        //schedulerConfig.showContextmenu = false;
                                                        schedulerConfig.commercials = data.schedulerCommercial;
                                                        schedulerConfig.dates = data.schedulerDates;
                                                        var campaignePrice = data.capmaignePrice;
                                                        var popust = data.popust;
														var campaigneId = data.campaigneID;

                                                        var sablonId = data.sablonId;

                                                        var spotBroj=data.spotBroj;


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
								},{
                                    iconCls: 'grid-rowaction-pdf',
                                    qtip: Lang.Common_Grid_toolTipPDF,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        window.open("../App/Controllers/KampanjaPdf.php?kampanjaID="+record.data.KampanjaID, "_newwindow", "width=400,height=200,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
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
                            plugins: Ext.create('Ext.ux.SlidingPager', {}),
                            listeners: {
                                beforechange: function (pagingToolbar, params) {
                                    Ext.getCmp('campaignesGrid').store.proxy.extraParams.filterValues = Ext.getCmp('campaignesFilter').getJsonFilterValues();
                                }
                            }
                });

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('campaignesFilter');
            grid.store.proxy.extraParams.filter = filter.getJsonFilterValues();
            grid.store.load();
        }
});









