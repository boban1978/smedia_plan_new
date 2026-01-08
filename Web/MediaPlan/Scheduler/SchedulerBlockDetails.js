Ext.define('Mediaplan.mediaPlan.scheduler.BlockDetailsGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.schedulerblockdetailsgrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;
            loadMask: true,
           

        Ext.apply(this, {
						frame: true,
                        loadMask: true,
                        id:'schedulerBlockDetailsGrid',
						store:  Ext.create('Ext.data.Store', {
                            fields: ['KampanjaID','Redosled','Klijent','Naziv','SpotName','Glas','Brend','Delatnost', 'DatumPocetka', 'DatumKraja','SpotTrajanje','SpotLink'],
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
                                            action: 'BlockGetDetailsForBlock',
											blockID:grid.blockID,
											blockDate:grid.blockDate,
											campaigneID: grid.campaigneID
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
                            { header: Lang.MediaPlan_campaignes_grid_column_Order, dataIndex: 'Redosled',width:60},
                            { header: Lang.MediaPlan_campaignes_grid_column_Client, dataIndex: 'Klijent',width:100},
							{ header: Lang.MediaPlan_campaignes_grid_column_Activity, dataIndex: 'Brend',width:100},
                            { header: Lang.MediaPlan_campaignes_grid_column_Name, dataIndex: 'Naziv',flex:1},
							{ header: Lang.MediaPlan_campaignes_grid_column_Spot, dataIndex: 'SpotName',flex:1},
							{ header: Lang.MediaPlan_campaignes_grid_column_Voice, dataIndex: 'Glas',width:110},
                            //{ header: Lang.MediaPlan_campaignes_grid_column_StartDate, dataIndex: 'DatumPocetka',width:90},
                            //{ header: Lang.MediaPlan_campaignes_grid_column_EndDate, dataIndex: 'DatumKraja',width:90},
                            { header: Lang.MediaPlan_campaignes_grid_column_SpotDuration, dataIndex: 'SpotTrajanje',renderer:Format.seconds,width:80}
                        ]
		});
                
                this.bbar = Ext.create('Ext.PagingToolbar', {
                            pageSize: Common.PageSize,
                            store: grid.store,
                            displayInfo: true,
                            plugins: Ext.create('Ext.ux.SlidingPager', {})
                });


		this.callParent(arguments);
	}
});


Ext.define('Mediaplan.mediaPlan.scheduler.BlockDetailsWindow', {
	extend: 'Ext.window.Window',
    alias: 'widget.clientdetailscampaignesspotswindow',
        
    title: 'Lista spotova u bloku',
    id:'schedulerBlockDetailsWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
	modal: true,
	width: 900,
	height:300,
        initComponent: function ()
	{
            
            var window = this;
            this.listeners = {
                afterrender: function(){

                    
                }
                
            };
            
            this.items = [{
					xtype:'schedulerblockdetailsgrid',
					blockID:window.blockID,
					blockDate:window.blockDate,
					campaigneID:window.campaigneID
            }];
            

            this.callParent(arguments);
        }
        
        

        
        
});




