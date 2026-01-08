Ext.define('Mediaplan.administration.clientAdministration.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientadministrationgrid',
        frame:true,
        border:true,
        title:Lang.Administration_clientAdministration_grid_Title ,
        iconCls:'vcard',
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'clientAdministrationGrid',
			frame: true,
                        loadMask: true,
                        listeners: {
                            afterrender: function (grid) { grid.store.load(); },
                            itemdblclick: function(grid,record, item, index,e){
                                Ext.create('Mediaplan.administration.clientAdministration.Dialog',{
                                    entryID:record.data.KlijentID,
                                    activateButtons:true
                                });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['KlijentID','Naziv', 'Adresa', 'Delatnost', 'Aktivan'],
                            remoteSort: true,
                            pageSize:Common.PageSize,
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
			dockedItems: [{
				xtype: 'toolbar',
				items: [{
                                    xtype:'button',
                                    text:Lang.Administration_clientAdministration_grid_btn_NewClient,
                                    iconCls:'vcard_add',
                                    handler:function(){
                                        Ext.widget('clientadministrationdialog',{
                                            activateButtons:true
                                        });
                                    }
                                }]
			}],
			//}],
			columns: [
                            { xtype: 'rownumberer', width: 50, sortable: false},
                            { header: Lang.Administration_clientAdministration_grid_column_Name,dataIndex: 'Naziv',flex:2},
                            { header: Lang.Administration_clientAdministration_grid_column_Address, dataIndex: 'Adresa',flex:4},
                            { header: Lang.Administration_clientAdministration_grid_column_Activity, dataIndex: 'Delatnost',flex:2},
                            { header: Lang.Administration_clientAdministration_grid_column_Activ, width:60, dataIndex: 'Aktivan'},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.administration.clientAdministration.Dialog',{
                                            entryID:record.data.KlijentID,
                                            activateButtons:true
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
                                                                    url: '../App/Controllers/Klijent.php',
                                                                    params: { action: 'KlijentDelete', entryID: record.data.KlijentID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                            //rebind grid
                                                                            Ext.getCmp('clientAdministrationGrid').getStore().load();
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
                            plugins: Ext.create('Ext.ux.SlidingPager', {}),
                            listeners: {
                                beforechange: function (pagingToolbar, params) {
                                    Ext.getCmp('clientAdministrationGrid').store.proxy.extraParams.filterValues = Ext.getCmp('clientAdministrationFilter').getJsonFilterValues();
                                }
                            }
                })

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('clientAdministrationFilter');
            grid.store.proxy.extraParams.filter = filter.getJsonFilterValues();
            grid.store.load();
        }
});




