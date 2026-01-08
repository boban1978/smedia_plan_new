Ext.define('Mediaplan.mediaPlan.blockDetails.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.mediaplanblockdetailsgrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;
            loadMask: true,
            
            /*this.listeners = {
                render:function(){
                    grid.store.load({
                        params: {datum: this.datum, blok: this.blok}
                    });
                }
            };*/

             Ext.apply(this, {
			frame: true,
                        loadMask: true,
                        id:'mediaPlanBlockDetailsGrid',
			store:  Ext.create('Ext.data.Store', {
                            fields: ['KampanjaID','Redosled','Klijent','Naziv','SpotName','Glas','Brend','Delatnost', 'DatumPocetka', 'DatumKraja','SpotTrajanje','SpotLink'],
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
                                            action: 'KampanjaGetListForBlock',
											RadioStanicaID:''
                                    },
                                    reader: {
                                            totalProperty: 'total',
                                            type: 'json',
                                            root: 'rows',
                                            successProperty: 'success',
                                            messageProperty: 'message'
                                    }
                            },
							listeners:{
							  beforeload : {
								fn: function(store,records,successful,eOpts){
									var station = Ext.getCmp('calendarRadioStation').getValue();
									if(station !== null) {
										store.proxy.extraParams.RadioStanicaID = station;
									} else {
										Ext.Msg.show({title : Lang.Message_Title,msg : 'Morate izabrati radio stanicu',buttons : Ext.Msg.OK,icon : Ext.MessageBox.INFO});
									}
								}
							  } 
							}
			}),
			columns: [  
                            { header: Lang.MediaPlan_campaignes_grid_column_Order, dataIndex: 'Redosled',width:60},
                            { header: Lang.MediaPlan_campaignes_grid_column_Client, dataIndex: 'Klijent',width:100},
							{ header: Lang.MediaPlan_campaignes_grid_column_Activity, dataIndex: 'Brend',width:100},
                            { header: Lang.MediaPlan_campaignes_grid_column_Name, dataIndex: 'Naziv',flex:1},
							{ header: Lang.MediaPlan_campaignes_grid_column_Spot, dataIndex: 'SpotName',flex:1},
							{ header: Lang.MediaPlan_campaignes_grid_column_Voice, dataIndex: 'Glas',width:90},
                            { header: Lang.MediaPlan_campaignes_grid_column_StartDate, dataIndex: 'DatumPocetka',width:90},
                            { header: Lang.MediaPlan_campaignes_grid_column_EndDate, dataIndex: 'DatumKraja',width:90},
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




