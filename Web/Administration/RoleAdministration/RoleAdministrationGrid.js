Ext.define('Mediaplan.administration.roleAdministration.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.roleadministrationgrid',
        frame:true,
        border:true,
        title:Lang.Administration_roleAdministration_grid_Title ,
        iconCls:'user_group',
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'roleAdministrationGrid',
			frame: true,
                        loadMask: true,
                        listeners: {
                            afterrender: function (grid) { grid.store.load(); },
                            itemdblclick: function(grid,record, item, index,e){
                                Ext.create('Mediaplan.administration.roleAdministration.Dialog',{
                                    entryID:record.data.RolaID
                                });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['RolaID','Naziv', 'Opis'],
                            remoteSort: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Rola.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'RolaGetList',
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
                                    text:Lang.Administration_roleAdministration_grid_btn_NewRole,
                                    iconCls:'user_add',
                                    handler:function(){
                                        Ext.widget('roleadministrationdialog');
                                    }
                                }]
			}],
			//}],
			columns: [                    
                            { header: Lang.Administration_roleAdministration_grid_column_Name,dataIndex: 'Naziv',flex:2},
                            { header: Lang.Administration_roleAdministration_grid_column_Description, dataIndex: 'Opis',flex:4},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.administration.roleAdministration.Dialog',{
                                            entryID:record.data.RolaID
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
                                                                    url: '../App/Controllers/Rola.php',
                                                                    params: { action: 'RolaDelete', entryID: record.data.RolaID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                            //rebind grid
                                                                            Ext.getCmp('roleAdministrationGrid').getStore().load();
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
                                    Ext.getCmp('roleAdministrationGrid').store.proxy.extraParams.filterValues = Ext.getCmp('roleAdministrationFilter').getJsonFilterValues();
                                }
                            }
                })

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('roleAdministrationFilter');
            grid.store.proxy.extraParams.filter = filter.getJsonFilterValues();
            grid.store.load();
        }
});


