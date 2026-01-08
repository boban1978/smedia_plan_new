Ext.define('Mediaplan.administration.weekTemplatesAdministration.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.weektemplatesgrid',
        frame:true,
        border:true,
        title:Lang.Administration_weekTemplatesAdministration_grid_Title ,
        iconCls:'table',
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'weekTemplatesAdministrationGrid',
			frame: true,
                        loadMask: true,
                        listeners: {
                            afterrender: function (grid) { grid.store.load(); },
                            itemdblclick: function(grid,record, item, index,e){

                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['sablonID','RadioStanica','naziv','datumPocetak','datumZavrsetak','popust','vremePostavka', 'Aktivan'],
                            remoteSort: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Sablon.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'NedeljniSablonGetList',
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
			dockedItems: [{
				xtype: 'toolbar',
				items: [{
                                    xtype:'button',
                                    text:Lang.Administration_weekTemplatesAdministration_grid_btn_NewTemplate,
                                    iconCls:'form_add',
                                    handler:function(){
                                        Ext.widget('weekstemplatewindow');
                                    }
                                }]
			}],
			//}],
			columns: [ 
                            { xtype: 'rownumberer', width: 50, sortable: false},
							{ header: Lang.Administration_weekTemplatesAdministration_grid_column_Station, width:200, dataIndex: 'RadioStanica'},
                            { header: Lang.Administration_weekTemplatesAdministration_grid_column_Name,dataIndex: 'naziv',flex:1},
                            { header: Lang.Administration_weekTemplatesAdministration_grid_column_DateStart, width:100, dataIndex: 'datumPocetak'},
                            { header: Lang.Administration_weekTemplatesAdministration_grid_column_DateEnd, width:100, dataIndex: 'datumZavrsetak'},
                            { header: Lang.Administration_weekTemplatesAdministration_grid_column_Discount, width:80, dataIndex: 'popust'},
                            { header: Lang.Administration_weekTemplatesAdministration_grid_column_CreationDate, width:100, dataIndex: 'vremePostavka'},
                            { header: Lang.Administration_weekTemplatesAdministration_grid_column_Active, width:80, dataIndex: 'Aktivan'},
                            {
                                xtype: 'rowactions',
                                actions:[/*{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                            var waitBox = Common.loadingBox(Ext.getCmp('weekTemplatesAdministrationGrid').getEl(), Lang.Loading);
                                            waitBox.show();


                                            Ext.Ajax.request({
                                                timeout: Common.Timeout,
                                                url: '../App/Controllers/Sablon.php',
                                                params: { action: 'SablonLoad', sablonID:record.data.sablonID },
                                                success: function (response, request) {
                                                    waitBox.hide();

                                                    if (Common.IsAjaxResponseSuccessfull(response)) {
                                                        waitBox.hide();
                                                        
                                                        var data = Ext.decode(response.responseText).data;
                                                        var schedulerConfig = Common.schConfig;
                                                        schedulerConfig.showContextmenu = false;
                                                        schedulerConfig.commercials = data.schedulerCommercial;
                                                        schedulerConfig.dates = data.schedulerDates;
                                                        var campaignePrice = data.capmaignePrice;
                                                        
                                                        Ext.widget('clientdetailscampaignepreviewwindow',{
                                                            height:575,
                                                            config:schedulerConfig,
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
                                },*/{
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
                                                                    url: '../App/Controllers/Sablon.php',
                                                                    params: { action: 'SablonDelete', entryID: record.data.SablonID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                            //rebind grid
                                                                            Ext.getCmp('weekTemplatesAdministrationGrid').getStore().load();
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
                
                this.bbar = Ext.create('Ext.PagingToolbar', {
                            pageSize: Common.PageSize,
                            store: grid.store,
                            displayInfo: true,
                            plugins: Ext.create('Ext.ux.SlidingPager', {}),
                            listeners: {
                                beforechange: function (pagingToolbar, params) {
                                    Ext.getCmp('weekTemplatesAdministrationGrid').store.proxy.extraParams.filterValues = Ext.getCmp('weekTemplatesAdministrationFilter').getJsonFilterValues();
                                }
                            }
                })

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('weekTemplatesAdministrationFilter');
            grid.store.proxy.extraParams.filterValues = filter.getJsonFilterValues();
            grid.store.load();
        }
});












