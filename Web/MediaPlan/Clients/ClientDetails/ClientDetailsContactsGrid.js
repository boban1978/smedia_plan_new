Ext.define('Mediaplan.mediaPlan.clients.details.ContactsGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailscontactsgrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsContactsGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['KontaktID','ImePrezime','Email','Telefon', 'Adresa'],
                            remoteSort: true,
                            loadMask: true,
                            pageSize:Common.PageSize,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/Kontakt.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'KontaktGetList',
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
                            { header: Lang.MediaPlan_clients_details_contacts_grid_column_Name, width:200, dataIndex: 'ImePrezime'},
                            { header: Lang.MediaPlan_clients_details_contacts_grid_column_Email, dataIndex: 'Email',flex:1},
                            { header: Lang.MediaPlan_clients_details_contacts_grid_column_Phone, dataIndex: 'Telefon',width:100},
                            { header: Lang.MediaPlan_clients_details_contacts_grid_column_Address, dataIndex: 'Adresa',flex:1},
                            {
                                xtype: 'rowactions',
                                actions:[{
                                    iconCls: 'grid-rowaction-edit',
                                    qtip: Lang.Common_Grid_toolTipEditRow,
                                    action: 'rowActionEdit',
                                    callback: function (grid, record, action, idx, col, e, target) {
                                        Ext.create('Mediaplan.mediaPlan.clients.details.ContactsDialog',{
                                            entryID:record.data.KontaktID
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
                                                                    url: '../App/Controllers/Kontakt.php',
                                                                    params: { action: 'KontaktDelete', entryID: record.data.KontaktID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {

                                                                                var grid = Ext.getCmp('clientDetailsContactsGrid');
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




