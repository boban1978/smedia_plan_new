Ext.define('Mediaplan.administration.priceListAdministration.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.pricelistadministrationgrid',
        frame:false,
        border:false,
        iconCls:'table',
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'priceListAdministrationGrid',
			frame: true,
                        loadMask: true,
                        listeners: {
                            afterrender: function (grid) { grid.store.load(); },
                            itemdblclick: function(grid,record, item, index,e){
                                    Ext.create('Mediaplan.administration.priceListAdministration.Dialog',{
                                        entryID:record.data.CenovnikID
                                    });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['CenovnikID','RadioStanica','Blok', 'Kategorija','Cena','Vikend','Aktivan'],
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
                                    url: '../App/Controllers/Cenovnik.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'CenovnikGetList'
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
                                    text:Lang.Administration_priceListAdministration_grid_btn_NewPriceList,
                                    iconCls:'table_add',
                                    handler:function(){
                                        Ext.widget('pricelistadministrationdialog');
                                    }
                                }]
			}],
			//}],
			columns: [   
                            { xtype: 'rownumberer', width: 50, sortable: false},
							{ header: Lang.Administration_priceListAdministration_grid_column_Station,dataIndex: 'RadioStanica',flex:1},
                            { header: Lang.Administration_priceListAdministration_grid_column_Block,dataIndex: 'Blok',width:150,},
                            { header: Lang.Administration_priceListAdministration_grid_column_Category,dataIndex: 'Kategorija',width:150,},
                            { header: Lang.Administration_priceListAdministration_grid_column_Price,dataIndex: 'Cena', width:100, renderer:Format.amount},
                            { header: Lang.Administration_priceListAdministration_grid_column_Weekdays,dataIndex: 'Vikend', width:100},
                            { header: Lang.Administration_priceListAdministration_grid_column_Active, width:80, dataIndex: 'Aktivan'},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.administration.priceListAdministration.Dialog',{
                                            entryID:record.data.CenovnikID
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
                                                                    url: '../App/Controllers/Cenovnik.php',
                                                                    params: { action: 'CenovnikDelete', entryID: record.data.CenovnikID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                            //rebind grid
                                                                            Ext.getCmp('priceListAdministrationGrid').getStore().load();
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








