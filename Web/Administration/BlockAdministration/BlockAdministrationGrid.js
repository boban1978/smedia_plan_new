Ext.define('Mediaplan.administration.blockAdministration.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.blockadministrationgrid',
        frame:false,
        border:false,
        //title:Lang.Administration_blockAdministration_grid_Title ,
        iconCls:'table',
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'blockAdministrationGrid',
			frame: true,
                        listeners: {
                            afterrender: function (grid) { grid.store.load(); },
                            itemdblclick: function(grid,record, item, index,e){
                                Ext.create('Mediaplan.administration.blockAdministration.Dialog',{
                                    entryID:record.data.BlokID
                                });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['BlokID','Sat', 'RedniBrojSat','Vrsta','VremeStart','VremeEnd','Trajanje','Aktivan'],
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
                                    url: '../App/Controllers/Blok.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'BlokGetList'
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
                                    text:Lang.Administration_blockAdministration_grid_btn_NewBlock,
                                    iconCls:'table_add',
                                    handler:function(){
                                        Ext.widget('blockadministrationdialog');
                                    }
                                }]
			}],
			//}],
			columns: [   
                            { xtype: 'rownumberer', width: 50, sortable: false},
                            { header: Lang.Administration_blockAdministration_grid_column_OrderNumberDay,dataIndex: 'Sat',flex:1},
                            { header: Lang.Administration_blockAdministration_grid_column_OrderNumberHour,dataIndex: 'RedniBrojSat',flex:1},
                            { header: Lang.Administration_blockAdministration_grid_column_Type,dataIndex: 'Vrsta',flex:1},
                            { header: Lang.Administration_blockAdministration_grid_column_TimeStart,dataIndex: 'VremeStart',width:100},
                            { header: Lang.Administration_blockAdministration_grid_column_TimeEnd,dataIndex: 'VremeEnd',width:100},
                            { header: Lang.Administration_blockAdministration_grid_column_Duration,dataIndex: 'Trajanje', width: 80},
                            { header: Lang.Administration_blockAdministration_grid_column_Active, width:80, dataIndex: 'Aktivan'},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.administration.blockAdministration.Dialog',{
                                            entryID:record.data.BlokID
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
                                                                    url: '../App/Controllers/Blok.php',
                                                                    params: { action: 'BlokDelete', entryID: record.data.BlokID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                            //rebind grid
                                                                            Ext.getCmp('blockAdministrationGrid').getStore().load();
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
                            plugins: Ext.create('Ext.ux.SlidingPager', {})
                })

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            grid.store.load();
        }
});






