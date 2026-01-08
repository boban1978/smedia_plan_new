Ext.define('Mediaplan.mediaPlan.clients.details.ImportantDatesGrid', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.clientdetailsimportantdatesgrid',
        frame:false,
        border:true,
	initComponent: function ()
	{
            var grid = this;

             Ext.apply(this, {
			frame: true,
                        id:'clientDetailsImportantDatesGrid',
                        loadMask: true,
			store:  Ext.create('Ext.data.Store', {
                            fields: ['BitanDatumID','Datum','Opis'],
                            remoteSort: true,
                            loadMask: true,
                            proxy: {
                                    type: 'ajax',
                                    url: '../App/Controllers/BitanDatum.php',
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: 'BitanDatumGetList',
                                            clientID: ''
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
                            { header: Lang.MediaPlan_clients_details_importantDates_grid_column_Date, width:100, dataIndex: 'Datum'},
                            { header: Lang.MediaPlan_clients_details_importantDates_grid_column_Description, dataIndex: 'Opis',flex:1},
                            {
                                xtype: 'rowactions',
                                actions:[{
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
                                                                    url: '../App/Controllers/BitanDatum.php',
                                                                    params: { action: 'BitanDatumDelete', entryID: record.data.BitanDatumID },
                                                                    success: function (response, request) {
                                                                        waitBox.hide();

                                                                        if (Common.IsAjaxResponseSuccessfull(response)) {

                                                                                var grid = Ext.getCmp('clientDetailsImportantDatesGrid');
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
                                    }
                                }],
                                keepSelection: true
                            }
                        ]
		});
                

		this.callParent(arguments);
	},
        
        reloadGrid: function(grid){
            grid.store.load();
        }
});



