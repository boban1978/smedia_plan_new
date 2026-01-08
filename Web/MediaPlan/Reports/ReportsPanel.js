Ext.define('Mediaplan.mediaPlan.reports.Panel', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.mediaplanreportspanel',
    border:false,
    frame:false,
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
            this.id = 'reportsPanel';
            this.items = [{
                    //xtype:'mediaplanclientsgrid',
                    id:'reportsPanelCenterPanel',
                    region:'center',
                    bodyPadding:'20',
                    flex:1
            },{
                    region:'west',
                    collapsible:true,
                    titleCollapse:true,
                    collapsed:false,
                    width:280,
                    title:Lang.MediaPlan_reports_leftMenu_Title,
                    items:[{
                            html:'<img  src="Images/Icons/chart_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.MediaPlan_reports_leftMenu_reportType1,
                            margin:'15 10 0 10',
                            //id:'administrationBtnUser',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 12px; color:gray; vertical-align:middle; margin-bottom:5px;',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                 var centralReportPanel = Ext.getCmp('reportsPanelCenterPanel');
                                                 var financialReportpanel = Ext.create('Mediaplan.mediaPlan.reports.financialReport.Panel');
                                                 centralReportPanel.removeAll();
                                                 centralReportPanel.add(financialReportpanel);
                                                 centralReportPanel.doLayout();
                                            }
                                        );
                                }
                            }
                   },{
                            html:'<img  src="Images/Icons/chart_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.MediaPlan_reports_leftMenu_reportType2,
                            margin:'5 3 0 10',
                            //id:'administrationBtnUser',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 12px; color:gray; vertical-align:middle; margin-bottom:5px;',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                 var centralReportPanel = Ext.getCmp('reportsPanelCenterPanel');
                                                 var offersReportpanel = Ext.create('Mediaplan.mediaPlan.reports.offersReport.Panel');
                                                 centralReportPanel.removeAll();
                                                 centralReportPanel.add(offersReportpanel);
                                                 centralReportPanel.doLayout();
                                            }
                                        );
                                }
                            }
                   },{
                            html:'<img  src="Images/Icons/chart_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.MediaPlan_reports_leftMenu_reportType3,
                            margin:'5 3 0 10',
                            //id:'administrationBtnUser',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 12px; color:gray; vertical-align:middle; margin-bottom:5px;',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                 var centralReportPanel = Ext.getCmp('reportsPanelCenterPanel');
                                                 var communicationReportpanel = Ext.create('Mediaplan.mediaPlan.reports.communicationReport.Panel');
                                                 centralReportPanel.removeAll();
                                                 centralReportPanel.add(communicationReportpanel);
                                                 centralReportPanel.doLayout();
                                            }
                                        );
                                }
                            }
                   }]
            }]    
            this.callParent(arguments);
        } //eo intitcomponent
        

});








