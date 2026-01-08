Ext.define('Mediaplan.mediaPlan.sporno.visakReklame.Panel', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.spornovisakreklamegrid',

    frame:false,
    border:true,
    /*height:'100%',*/
    iconCls:'table',
    title:"Višak reklame",
    layout:'fit',
    initComponent: function ()
    {
        var grid = this;


        Ext.apply(this, {
            frame: true,
            id:'spornoVisakReklameGrid',
            //layout:'fit',
            style:'overflow-y:auto;',
            listeners: {
                afterrender: function (grid) { grid.store.load(); }
            },
            store:  Ext.create('Ext.data.Store', {
                fields: ['SpotName','RadioStanica','DatumKraja','RadioStanicaID'],
                remoteSort: true,
                autoLoad: false,
                pageSize:Common.PageSize,
                listeners:{
                    beforeload:function(){
                        grid.mask = new Ext.LoadMask(grid, {msg:Lang.Loading});
                        grid.mask.show();
                    },
                    load:function(){
                        var mask = new Ext.LoadMask(grid, {msg:Lang.Loading});
                        grid.mask.hide()
                    }
                },
                proxy: {
                    type: 'ajax',
                    url: '../App/Controllers/PomocniAlati.php',
                    actionMethods: {
                        read: 'POST'
                    },
                    extraParams: {
                        action: 'VisakReklame'
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
                { header: Lang.MediaPlan_campaignes_spots_grid_column_Name, flex:1, dataIndex: 'SpotName'},
                //{ header: "Spot ID", flex:1, dataIndex: 'SpotID',  hidden:true},
                //{ header: "Kampanja", flex:1, dataIndex: 'KampanjaName'},
                { header: "Radio stanica", flex:1, dataIndex: 'RadioStanica'},
                { header: "Datum kraja", flex:1, dataIndex: 'DatumKraja'},
                { header: "Radio stanica ID", flex:1, dataIndex: 'RadioStanicaID',  hidden:true},
                {
                    xtype: 'rowactions',
                    actions:[{
                        iconCls: 'grid-rowaction-delete',
                        qtip: Lang.Common_Grid_toolTipDeleteRow,
                        action: 'rowActionDelete',
                        callback: function (grid, record, action, idx, col, e, target) {


                            var allowDelete = false;
                            for (i = 0; i < Common.allUserPermisions.length; i++) {
                                var p=Common.allUserPermisions[i];
                                if(p == 160){
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
                                                url: '../App/Controllers/Spot.php',
                                                params: { action: 'SpotDeleteFile', SpotName: record.data.SpotName, RadioStanica: record.data.RadioStanica },
                                                success: function (response, request) {
                                                    //waitBox.hide();

                                                    if (Common.IsAjaxResponseSuccessfull(response)) {


                                                        Ext.Ajax.request({
                                                            url: '../App/Controllers/Cron.php',
                                                            method: 'POST',
                                                            params: {
                                                                action: 'cron_xml'
                                                            },
                                                            success: function(response){

                                                                waitBox.hide();

                                                                //rebind grid
                                                                Ext.getCmp('spornoVisakReklameGrid').getStore().load();

                                                                /*
                                                                var centralSpornoPanel = Ext.getCmp('spornoPanelCenterPanel');
                                                                var sporneReklamepanel = Ext.create('Mediaplan.mediaPlan.sporno.sporneReklame.Panel');


                                                                centralSpornoPanel.removeAll();
                                                                centralSpornoPanel.add(sporneReklamepanel);
                                                                centralSpornoPanel.doLayout();*/

                                                            }
                                                        });






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
                    }]
                }

            ]
        });


        this.bbar = Ext.create('Ext.PagingToolbar', {
            pageSize: Common.PageSize,
            store: grid.store,
            displayInfo: true,
            plugins: Ext.create('Ext.ux.SlidingPager', {})
        })


        this.callParent(arguments);
    }/*,

    reloadGrid: function(grid){
        grid.store.load();
    }*/
});






