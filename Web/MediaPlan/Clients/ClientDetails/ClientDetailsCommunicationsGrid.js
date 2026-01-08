Ext.define('Mediaplan.mediaPlan.clients.details.CommunicationsGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailscommunicationsgrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsCommunicationsGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['IstorijaKomunikacijaID','Sadrzaj','TipKomunikacija','Datum','Uneo', 'Link'],
                            remoteSort: true,
                            loadMask: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/IstorijaKomunikacija.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'IstorijaKomunikacijaGetList',
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
                            { header: Lang.MediaPlan_clients_details_communications_grid_column_Note,dataIndex: 'Sadrzaj',flex:1},
                            { header: Lang.MediaPlan_clients_details_communications_grid_column_Type, width:120, dataIndex: 'TipKomunikacija'},
                            { header: Lang.MediaPlan_clients_details_communications_grid_column_Date, dataIndex: 'Datum',width:80},
                            { header: Lang.MediaPlan_clients_details_communications_grid_column_User, dataIndex: 'Uneo',width:100},
                            { dataIndex: 'Link',renderer:Format.attachment,width:30},
                            {
                                xtype: 'rowactions',
                                actions:[/*{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.CommunicationsDialog',{
                                            entryID:record.data.IstorijaKomunikacijaID
                                        });
                                    }   
                                },*/{
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
                                                                    url: '../App/Controllers/IstorijaKomunikacija.php',
                                                                    params: { action: 'IstorijaKomunikacijaDelete', entryID: record.data.IstorijaKomunikacijaID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {

                                                                                var grid = Ext.getCmp('clientDetailsCommunicationsGrid');
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
												msg: 'Nemate odgovarajuću privilegiju za ovu akciju',
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




