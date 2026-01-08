Ext.define('Mediaplan.mediaPlan.clients.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.mediaplanclientsgrid',

	initComponent: function ()
	{
            var grid = this;
            

             Ext.apply(this, {
			id:'clientsGrid',
			frame:false,
                        border:true,
                        stripeRows: true,
                        listeners: {
                            afterrender: function (grid) { 
                                grid.store.load(); 
                            },
                            itemdblclick: function(grid,record, item, index,e){
                                    Ext.create('Mediaplan.mediaPlan.clients.details.Window',{
                                        entryID:record.data.KlijentID,
                                        clientName: record.data.Naziv
                                    });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['KlijentID','Naziv', 'Pib', 'MaticniBroj', 'Adresa', 'Delatnost', 'Aktivan'],
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
                                    url: '../App/Controllers/Klijent.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'KlijentGetList',
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
                            { header: Lang.MediaPlan_clients_grid_column_Name,dataIndex: 'Naziv',flex:2},
                            { header: Lang.MediaPlan_clients_grid_column_Address, dataIndex: 'Adresa',flex:4},
                            { header: 'Pib', dataIndex: 'Pib',flex:4},
                            { header: 'Matični broj', dataIndex: 'MaticniBroj',flex:4},
                            { header: Lang.MediaPlan_clients_grid_column_Activity, dataIndex: 'Delatnost',flex:2},
                            { header: Lang.MediaPlan_clients_grid_column_Activ, width:60, dataIndex: 'Aktivan'},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.Window',{
                                            entryID:record.data.KlijentID,
                                            clientName: record.data.Naziv
                                        });
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
                                    Ext.getCmp('clientsGrid').store.proxy.extraParams.filterValues = Ext.getCmp('clientsFilter').getJsonFilterValues();
                                }
                            }
                })

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('clientsFilter');
            grid.store.proxy.extraParams.filter = filter.getJsonFilterValues();
            grid.store.load();
        }
});






