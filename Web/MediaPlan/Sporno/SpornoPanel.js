Ext.define('Mediaplan.mediaPlan.sporno.Panel', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.mediaplanspornopanel',
        border:false,
        frame:true,
        padding:0,
	width:'100%',
        height:'100%',
        layout: {
            //type: 'hbox',
            type:'border',
            align:'strech'
        },
	initComponent: function ()
	{
        this.id = 'spornoPanel';
        this.items = [{
                //xtype:'spornospornereklamegrid',
                id:'spornoPanelCenterPanel',
                region:'center',
                bodyPadding:'20',
                flex:1,
                layout:'fit'
        },{
                region:'west',
                collapsible:true,
                titleCollapse:true,
                collapsed:false,
                width:280,
                title:Lang.MediaPlan_sporno_leftMenu_Title,
                items:[{
                        html:'<img  src="Images/Icons/chart_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.MediaPlan_sporno_leftMenu_menu1,
                        margin:'15 10 0 10',
                        //id:'administrationBtnUser',
                        border:false,
                        bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 12px; color:gray; vertical-align:middle; margin-bottom:5px;',
                        listeners: {
                            render: function(c){c.el.on('click', function()
                                        {

                                            var waitBox = Common.loadingBox(Ext.getCmp('spornoPanelCenterPanel').getEl(), "Kron XML processing...");
                                            waitBox.show();

                                            Ext.Ajax.request({
                                                url: '../App/Controllers/Cron.php',
                                                method: 'POST',
                                                params: {
                                                    action: 'cron_xml'
                                                },
                                                success: function(response){

                                                    waitBox.hide();

                                                    var centralSpornoPanel = Ext.getCmp('spornoPanelCenterPanel');
                                                   var sporneReklamepanel = Ext.create('Mediaplan.mediaPlan.sporno.sporneReklame.Panel');



                                                    //var sporneReklamepanel = Ext.create('Mediaplan.mediaPlan.clients.Grid');



                                                    centralSpornoPanel.removeAll();
                                                    centralSpornoPanel.add(sporneReklamepanel);
                                                    centralSpornoPanel.doLayout();

                                                }
                                            });

                                        }
                                    );
                            }
                        }
               },



                    {
                        html:'<img  src="Images/Icons/chart_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+"Višak reklame",
                        margin:'15 10 0 10',
                        //id:'administrationBtnUser',
                        border:false,
                        bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 12px; color:gray; vertical-align:middle; margin-bottom:5px;',
                        listeners: {
                            render: function(c){c.el.on('click', function()
                                {

                                            var centralSpornoPanel = Ext.getCmp('spornoPanelCenterPanel');
                                            var visakReklamepanel = Ext.create('Mediaplan.mediaPlan.sporno.visakReklame.Panel');
                                            centralSpornoPanel.removeAll();
                                            centralSpornoPanel.add(visakReklamepanel);
                                            centralSpornoPanel.doLayout();

                                }
                            );
                            }
                        }



                    },




                    {
                    html:'<img  src="Images/Icons/chart_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+"Kronovi",
                    margin:'15 10 0 10',
                    //id:'administrationBtnUser',
                    border:false,
                    bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 12px; color:gray; vertical-align:middle; margin-bottom:5px;',
                    listeners: {
                        render: function(c){c.el.on('click', function()
                            {
                                var centralSpornoPanel = Ext.getCmp('spornoPanelCenterPanel');
                                var sporneReklamepanel = Ext.create('Mediaplan.mediaPlan.sporno.spornoCrons.Panel');
                                centralSpornoPanel.removeAll();
                                centralSpornoPanel.add(sporneReklamepanel);
                                centralSpornoPanel.doLayout();
                            }
                        );
                        }
                    }
                },






                    {
                        html:'<img  src="Images/Icons/chart_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+"Spot Upload",
                        margin:'15 10 0 10',
                        //id:'administrationBtnUser',
                        border:false,
                        bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 12px; color:gray; vertical-align:middle; margin-bottom:5px;',
                        listeners: {
                            render: function(c){c.el.on('click', function()
                                {


                                    Ext.create('Mediaplan.mediaPlan.clients.details.CampaigneDocumentWindow2');



                                }
                            );
                            }
                        }
                    }
















                ]
        }]
        this.callParent(arguments);
    } //eo intitcomponent
        

});








