Ext.define('Mediaplan.mediaPlan.sporno.sporneReklame.Panel', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.spornospornereklamegrid',
    //id: 'spornoSporneReklameGrid',
    frame:false,
    border:true,
    /*height:'100%',*/
    iconCls:'table',
    title:"Sporne reklame",
    layout:'fit',
    initComponent: function ()
    {
        var grid = this;

        Ext.apply(this, {
            frame: true,
            id:'spornoSporneReklameGrid',
            //layout:'fit',
            style:'overflow-y:auto;',
            store:  Ext.create('Ext.data.Store', {
                fields: ['SpotName','SpotID','KampanjaName','RadioStanica','RadioStanicaID','DatumPocetka'],
                autoLoad: true,
                /*listeners:{
                    beforeload:function(){
                        grid.mask = new Ext.LoadMask(grid, {msg:Lang.Loading});
                        grid.mask.show();
                    },
                    load:function(){
                        var mask = new Ext.LoadMask(grid, {msg:Lang.Loading});
                        grid.mask.hide();
                    }
                },*/

                proxy: {
                    type: 'ajax',
                    url: '../App/Controllers/PomocniAlati.php',
                    actionMethods: {
                        read: 'POST'
                    },
                    extraParams: {
                        action: 'SporneReklame'
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
                { header: "Kampanja", flex:1, dataIndex: 'KampanjaName'},
                { header: "Datum početka", flex:1, dataIndex: 'DatumPocetka'},
                { header: "Radio stanica", flex:1, dataIndex: 'RadioStanica'},
                { header: "Radio stanica ID", flex:1, dataIndex: 'RadioStanicaID',  hidden:true},
                {
                    xtype: 'rowactions',
                    actions:[{
                        iconCls: 'grid-rowaction-edit',
                        qtip: Lang.Common_Grid_toolTipEditRow,
                        action: 'rowActionEdit',
                        callback: function (grid, record, action, idx, col, e, target) {


                            /*
                            Ext.create('Mediaplan.mediaPlan.sporno.sporneReklame.SpotWindow',{
                                data:record.data
                            });*/

                            Ext.widget('clientdetailsspoteditwindow',{
                                data:record.data
                            });

                        }
                    }],
                    keepSelection: true
                }

            ]
        });

        this.callParent(arguments);
    }
});

















/*


Ext.define('Mediaplan.mediaPlan.sporno.sporneReklame.SpotWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.spornospornereklamespotwindow',
    title: "Izmena imena spota",
    id: 'spornoSporneReklameSpotWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal: true,
    width: 600,
    height: 200,
    autocomplete:1,
    initComponent: function()
    {

        var window = this;

        this.listeners = {
            show: function() {



                var spotID=window.data.spotID;
                var spotName=window.data.spotName;

                var radioStanicaID=window.data.radioStanicaID;
                //alert_obj_boban(window.data);

                Ext.getCmp('hdnSpotID').setValue(spotID);
                Ext.getCmp('hdnRadioStanicaID').setValue(radioStanicaID);




                Ext.Ajax.request({
                    url: '../App/Controllers/Common.php',
                    method: 'POST',
                    params: {
                        radioStanicaID: radioStanicaID,
                        action: 'getSpotsAutocomplete'
                    },
                    success: function(response){
                        var rows = Ext.decode(response.responseText).rows;
                        customarray = new Array();
                        rows.forEach(function(entry) {
                            customarray.push(entry);
                        });

                        actb_curr = document.getElementsByName("spotName")[0];
                        actb_curr.value = "";
                        var obj = actb(actb_curr, customarray);
                        Ext.getCmp('spotName').setValue(spotName);

                    }
                });







            }

        };

        this.items = [{
            xtype: 'form',
            bodyPadding: 10,
            id: 'spornoSporneReklameSpotForm',
            border: false,
            frame: false,
            labelWidth: 120,
            autoScroll: true,
            items: [{
                xtype: 'hidden',
                name: 'spotID',
                id: 'hdnSpotID'
            },{
                xtype: 'hidden',
                name: 'radioStanicaID',
                id: 'hdnRadioStanicaID'
            },{
                xtype: 'textfield',
                fieldLabel: Lang.MediaPlan_clients_details_campaignes_dialog_SpotName,
                name: 'spotName',
                id: 'spotName',
                allowBlank: false,
                width: 400,
                listeners: {
                    'change': function(){
                        var station = Ext.getCmp('hdnRadioStanicaID').getValue();
                        if(station==null){
                            actb_curr = document.getElementsByName("spotName")[0];
                            actb_curr.value="";
                            alert("Radio Stanica wrong !");
                        }
                    }
                }
            }]
        }],
            this.buttons = [{
                text: "Potvrdi",
                iconCls: 'refresh',
                handler: function() {
                    window.saveSpot();
                }
            }, '-', {
                text: Lang.MediaPlan_clients_details_campaignes_dialog_btn_Cancel,
                icon: Icons.Cancel16,

                scope: this,
                handler: this.close
            }];








        this.callParent(arguments);
    },

    saveSpot: function() {

        var form = Ext.getCmp('spornoSporneReklameSpotForm').getForm();

        if (form.isValid()) {
            var waitBox = Common.loadingBox(Ext.getCmp('spornoSporneReklameSpotWindow').getEl(), "processing...");
            waitBox.show();
            form.submit({
                timeout: 600000,
                url: '../App/Controllers/Spot.php?action=SpotNameUpdate',
                success: function(form, response) {

                    waitBox.hide();



                    Ext.getCmp('spornoSporneReklameSpotWindow').close();



                    var centralSpornoPanel = Ext.getCmp('spornoPanelCenterPanel');
                    var sporneReklamepanel = Ext.create('Mediaplan.mediaPlan.sporno.sporneReklame.Panel');
                    centralSpornoPanel.removeAll();
                    centralSpornoPanel.add(sporneReklamepanel);
                    centralSpornoPanel.doLayout();





                },
                failure: function(fp, o) {
                    waitBox.hide();
                    alert('Greška u obradi zahteva');
                }

            });
        }

    }






});


*/




