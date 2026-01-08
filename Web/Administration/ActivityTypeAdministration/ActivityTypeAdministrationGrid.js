Ext.define('Mediaplan.administration.activityTypeAdministration.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.activitytypeadministrationgrid',
        frame:true,
        border:true,
        title:Lang.Administration_activityTypeAdministration_grid_Title ,
        iconCls:'table',
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'activityTypeAdministrationGrid',
			frame: true,
                        loadMask: true,
                        listeners: {
                            afterrender: function (grid) { grid.store.load(); },
                            itemdblclick: function(grid,record, item, index,e){
                                Ext.create('Mediaplan.administration.activityTypeAdministration.Dialog',{
                                    entryID:record.data.DelatnostID
                                });
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            fields: ['DelatnostID','Naziv', 'Aktivan'],
                            remoteSort: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Delatnost.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'DelatnostGetList',
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
                                    text:Lang.Administration_activityTypeAdministration_grid_btn_NewActivity,
                                    iconCls:'table_add',
                                    handler:function(){
                                        Ext.widget('activitytypeadministrationdialog');
                                    }
                                }]
			}],
			//}],
			columns: [ 
                            { xtype: 'rownumberer', width: 50, sortable: false},
                            { header: Lang.Administration_activityTypeAdministration_grid_column_Name,dataIndex: 'Naziv',flex:1},
                            { header: Lang.Administration_activityTypeAdministration_grid_column_Active, width:80, dataIndex: 'Aktivan'},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.administration.activityTypeAdministration.Dialog',{
                                            entryID:record.data.DelatnostID
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
                                                                    url: '../App/Controllers/Delatnost.php',
                                                                    params: { action: 'DelatnostDelete', entryID: record.data.DelatnostID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                            //rebind grid
                                                                            Ext.getCmp('activityTypeAdministrationGrid').getStore().load();
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
                                    Ext.getCmp('activityTypeAdministrationGrid').store.proxy.extraParams.filterValues = Ext.getCmp('activityTypeAdministrationFilter').getJsonFilterValues();
                                }
                            }
                })

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('activityTypeAdministrationFilter');
            grid.store.proxy.extraParams.filter = filter.getJsonFilterValues();
            grid.store.load();
        }
});





