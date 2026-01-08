Ext.define('Mediaplan.mediaPlan.agencies.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.mediaplanagenciesgrid',

	initComponent: function ()
	{
             var grid = this;

             Ext.apply(this, {
			id:'agenciesGrid',
			frame:false,
                        border:true,
                        stripeRows: true,
                        listeners: {
                            afterrender: function (grid) { 
                                grid.store.load();  
                            },
                            itemdblclick: function(grid,record, item, index,e){
                                Ext.create('Mediaplan.administration.agencyAdministration.Dialog',{
                                    entryID:record.data.AgencijaID,
                                    activateButtons:false
                                });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['AgencijaID','Naziv', 'Adresa','KontaktOsoba', 'Aktivna'],
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
                                    url: '../App/Controllers/Agencija.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'AgencijaGetList',
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
                            { header: Lang.MediaPlan_agencies_grid_column_Name,dataIndex: 'Naziv',flex:2},
                            { header: Lang.MediaPlan_agencies_grid_column_Address, dataIndex: 'Adresa',flex:4},
                            { header: Lang.MediaPlan_agencies_grid_column_Contact, dataIndex: 'KontaktOsoba',flex:1},
                            { header: Lang.MediaPlan_agencies_grid_column_Activ, width:60, dataIndex: 'Aktivan'},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                    Ext.create('Mediaplan.administration.agencyAdministration.Dialog',{
                                            entryID:record.data.AgencijaID,
                                            activateButtons:false
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
                                    Ext.getCmp('agenciesGrid').store.proxy.extraParams.filterValues = Ext.getCmp('agenciesFilter').getJsonFilterValues();
                                }
                            }
                });

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('agenciesFilter');
            grid.store.proxy.extraParams.filter = filter.getJsonFilterValues();
            grid.store.load();
        }
});







