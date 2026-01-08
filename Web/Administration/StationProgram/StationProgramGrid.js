Ext.define('Mediaplan.administration.stationProgram.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.stationprogramgrid',
	frame:true,
	border:true,
	title:Lang.Administration_stationProgram_grid_Title ,
	iconCls:'table',
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'stationProgramGrid',
			frame: true,
                        loadMask: true,
                        listeners: {
                            afterrender: function (grid) { grid.store.load(); },
                            itemdblclick: function(grid,record, item, index,e){
                                Ext.create('Mediaplan.administration.stationProgram.Dialog',{
                                    entryID:record.data.EmisijaID
                                });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['RadioStanicaProgramID','RadioStanica','Naziv','TipDana','PocetakEmitovanja', 'KrajEmitovanja'],
                            remoteSort: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/RadioStanicaProgram.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'RadioStanicaProgramGetList',
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
                                    text:Lang.Administration_stationProgram_grid_btn_NewProgram,
                                    iconCls:'table_add',
                                    handler:function(){
                                        Ext.widget('stationprogramdialog');
                                    }
                                }]
			}],
			//}],
			columns: [ 
                            { xtype: 'rownumberer', width: 50, sortable: false},
							{ header: Lang.Administration_stationProgram_grid_column_Station, width:200, dataIndex: 'RadioStanica'},
                            { header: Lang.Administration_stationProgram_grid_column_Name,dataIndex: 'Naziv',flex:1},
							{ header: Lang.Administration_stationProgram_grid_column_Start, width:120, dataIndex: 'PocetakEmitovanja'},
                            { header: Lang.Administration_stationProgram_grid_column_End, width:120, dataIndex: 'KrajEmitovanja'},
							{ header: Lang.Administration_stationProgram_grid_column_Workday, width:120, dataIndex: 'TipDana'},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.administration.StationProgram.Dialog',{
                                            entryID:record.data.RadioStanicaProgramID
                                        });
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
                                                                var waitBox = Common.loadingBox(Ext.getBody(), Lang.Deleting);
                                                                waitBox.show();

                                                                Ext.Ajax.request({
                                                                    timeout: Common.Timeout,
                                                                    url: '../App/Controllers/RadioStanicaProgram.php',
                                                                    params: { action: 'RadioStanicaProgramDelete', entryID: record.data.RadioStanicaProgramID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                            //rebind grid
                                                                            Ext.getCmp('stationProgramGrid').getStore().load();
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
                                    Ext.getCmp('stationProgramGrid').store.proxy.extraParams.filterValues = Ext.getCmp('stationProgramFilter').getJsonFilterValues();
                                }
                            }
                })

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('stationProgramFilter');
            grid.store.proxy.extraParams.filterValues = filter.getJsonFilterValues();
            grid.store.load();
        }
});






