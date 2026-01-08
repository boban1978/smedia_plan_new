Ext.define('Mediaplan.mediaPlan.brend.details.BrendGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailsbrendgrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsBrendGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['BrendID','Naziv','Delatnost'],
                            remoteSort: true,
                            loadMask: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Brend.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'BrendGetList',
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
			columns: [  
                            { xtype: 'rownumberer', width: 30, sortable: false},
                            { header: Lang.MediaPlan_clients_details_brend_grid_column_Name, flex:1, dataIndex: 'Naziv'},
                            { header: Lang.MediaPlan_clients_details_brend_grid_column_Activity, width:200, dataIndex: 'Delatnost'},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.BrendDialog',{
                                            entryID:record.data.BrendID
                                        });
                                    }   
                                },{
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
                                                                    url: '../App/Controllers/Brend.php',
                                                                    params: { action: 'BrendDelete', entryID: record.data.BrendID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {

                                                                                var grid = Ext.getCmp('clientDetailsBrendGrid');
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




