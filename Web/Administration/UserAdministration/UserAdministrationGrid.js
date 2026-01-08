Ext.define('Mediaplan.administration.userAdministration.Grid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.useradministrationgrid',
    frame:true,
    border:true,
        title:Lang.Administration_userAdministration_grid_Title,
        iconCls:'user',
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			id:'userAdministrationGrid',
			frame:true,
                        /*border:true,
                        stripeRows: true,*/
                                                loadMask: true,
                        listeners: {
                            afterrender: function (grid) { 
                                grid.store.load();                            
                            },
                            itemdblclick: function(grid,record, item, index,e){
                                Ext.create('Mediaplan.administration.userAdministration.Dialog',{
                                    entryID:record.data.KorisnikID
                                }); 
                            }
                        },
			store:  Ext.create('Ext.data.Store', {
                            id:'nesto',
                            fields: ['KorisnikID','Username', 'ImePrezime','TipKorisnik','Aktivan'],
                            remoteSort: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Korisnik.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'KorisnikGetList',
                                            filterValues:''
                                    },
                                    reader: {
                                            totalProperty: 'total',
                                            type: 'json',
                                            root: 'rows',
                                            successProperty: 'success',
                                            messageProperty: 'message'
                                    }
                            },
                            headers:{ 'Content-Type': 'application/json; charset=UTF-8' }
			}),
			dockedItems: [{
				xtype: 'toolbar',
				items: [{
                                    xtype:'button',
                                    text:Lang.Administration_userAdministration_grid_btn_NewUser,
                                    iconCls:'user_add',
                                    handler:function(){
                                        Ext.widget('useradministrationdialog');
                                    }
                                }]
			}],
			columns: [  
                            { xtype: 'rownumberer', width: 50, sortable: false},
                            { header: Lang.Administration_userAdministration_grid_column_Username,dataIndex: 'Username',flex:2},
                            { header: Lang.Administration_userAdministration_grid_column_Name, dataIndex: 'ImePrezime',flex:4},
                            { header: Lang.Administration_userAdministration_grid_column_UserType,dataIndex: 'TipKorisnik',flex:1},
                            { header: Lang.Administration_userAdministration_grid_column_Active,dataIndex: 'Aktivan',flex:1},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.administration.userAdministration.Dialog',{
                                            entryID:record.data.KorisnikID
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
                                                                    url: '../App/Controllers/Korisnik.php',
                                                                    params: { action: 'KorisnikDelete', entryID: record.data.KorisnikID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {
                                                                            //rebind grid
                                                                            Ext.getCmp('userAdministrationGrid').getStore().load();
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
                                    Ext.getCmp('userAdministrationGrid').store.proxy.extraParams.filterValues = Ext.getCmp('userAdministrationFilter').getJsonFilterValues();
                                }
                            }
                })

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            var filter = Ext.getCmp('userAdministrationFilter');
            grid.store.proxy.extraParams.filterValues = filter.getJsonFilterValues();
            grid.store.load();
        }
});




