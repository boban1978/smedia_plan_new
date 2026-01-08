Ext.define('Mediaplan.mediaPlan.ToolBar', {
	extend: 'Ext.toolbar.Toolbar',
	alias: 'widget.mediaplanmaintoolbar',


        initComponent: function ()
	{
            this.items = [{
                text:Lang.MediaPlan_toolBar_Clients,
                tooltip:Lang.MediaPlan_toolBar_Clients_toolTip,
                id:'mediaPlanBtnClients',
                disabled:true,
                scale:'large',
                icon:'Images/client_32.png',
                iconAlign:'top',
                width:80,
                handler:function(){
                   if (!Ext.getCmp('clientsTab')) {
                       var clientsTab = Ext.getCmp('mediaPlanTabPanel').add({
                            title: Lang.MediaPlan_toolBar_Clients,
                            id:'clientsTab',
                            closable: true,
                            autoScroll: true,
                            layout:'fit',
                            iconCls:'client',
                            height:Ext.getBody().getHeight() - 180,
                            items: [Ext.widget('mediaplanclientspanel')]
                        });
                        Ext.getCmp('mediaPlanTabPanel').setActiveTab(clientsTab);
                   } else {
                       Ext.getCmp('mediaPlanTabPanel').setActiveTab(Ext.getCmp('clientsTab'));
                   }
                }
            },'-',{
                text:Lang.MediaPlan_toolBar_Agencies,
                tooltip:Lang.MediaPlan_toolBar_Agencies_toolTip,
                id:'mediaPlanBtnAgencies',
                disabled:true,
                scale:'large',
                icon:'Images/agency_32.png',
                iconAlign:'top',
                width:80,
                handler:function(){
                    if (!Ext.getCmp('agenciesTab')) {
                        var agenciesTab = Ext.getCmp('mediaPlanTabPanel').add({
                            title: Lang.MediaPlan_toolBar_Agencies,
                            id:'agenciesTab',
                            closable: true,
                            autoScroll: true,
                            layout:'fit',
                            iconCls:'agency',
                            height:Ext.getBody().getHeight() - 180,
                            items: [Ext.widget('mediaplanagenciespanel')]
                        });
                        Ext.getCmp('mediaPlanTabPanel').setActiveTab(agenciesTab);
                    } else {
                       Ext.getCmp('mediaPlanTabPanel').setActiveTab(Ext.getCmp('agenciesTab'));
                   }
                }
            },'-',{
                text:Lang.MediaPlan_toolBar_Campaignes,
                tooltip:Lang.MediaPlan_toolBar_Campaignes_toolTip,
                id:'mediaPlanBtnCampaignes',
                disabled:true,
                scale:'large',
                icon:'Images/campaigne_32.png',
                iconAlign:'top',
                width:80,
                handler:function(){
                    if (!Ext.getCmp('campaignesTab')) {
                        var campaignesTab = Ext.getCmp('mediaPlanTabPanel').add({
                            title: Lang.MediaPlan_toolBar_Campaignes,
                            id:'campaignesTab',
                            closable: true,
                            autoScroll: true,
                            iconCls:'campaigne',
                            height:Ext.getBody().getHeight() - 180,
                            items: [Ext.widget('mediaplancampaignespanel')]
                        });
                    Ext.getCmp('mediaPlanTabPanel').setActiveTab(campaignesTab);
                   } else {
                       Ext.getCmp('mediaPlanTabPanel').setActiveTab(Ext.getCmp('campaignesTab'));
                   }
                }
            },'-',{
                text:Lang.MediaPlan_toolBar_MediaPlan,
                tooltip:Lang.MediaPlan_toolBar_MediaPlan_toolTip,
                id:'mediaPlanBtnMediaPlan',
                disabled:true,
                scale:'large',
                icon:'Images/media_plan_32.png',
                iconAlign:'top',
                width:80,
                handler:function(){
                    if (!Ext.getCmp('mediaPlanTab')) {
                        var mediaPlanTab = Ext.getCmp('mediaPlanTabPanel').add({
                            title: Lang.MediaPlan_toolBar_MediaPlan,
                            id:'mediaPlanTab',
                            closable: true,
                            autoScroll: true,
                            iconCls:'media_plan',
                            layout:'border',
                            height:Ext.getBody().getHeight() - 180,
                            /*items: [{
                                xtype: 'extensible.calendarpanel',
                                //title: 'Calendar',
                                eventStore: MediaPlan.eventStore,
                                calendarStore:MediaPlan.calendarStore, 
                                showMultiDayView: false,
                                showWeekView: false,
                                showMultiWeekView: false,
                                showMonthView: false,
                                showTodayText: false,
                                showTime: false,
                                viewStartHour: 6,
                                viewEndHour: 23,
                                width: '100%',
                                height: '100%',
                                activeItem: 1,
                                // this is a good idea since we are in a TabPanel and we don't want
                                // the user switching tabs on us while we are editing an event:
                                editModal: true
                            }]*/
                            items:[{
                                id: 'app-header',
                                region: 'north',
                                height: 35,
                                border: false,
                                items:[{
                                        border:false,
                                        html:'<div id="app-header-content"><div id="app-logo"><div class="logo-top">&#160;</div><div id="logo-body">&#160;</div><div class="logo-bottom">&#160;</div></div><h1>Media plan - pregled</h1></div>'
                                }]
                            },{
                                id: 'app-center',
                                title: '...', // will be updated to the current view's date range
                                region: 'center',
                                layout: 'border',
                                items:[{
                                        id:'app-west',
                                        region: 'west',
                                        width: 179,
                                        border: false,
                                        items: [{
                                            xtype: 'datepicker',
                                            id: 'app-nav-picker',
                                            cls: 'ext-cal-nav-picker',
                                            listeners: {
                                                'select': {
                                                    fn: function(dp, dt){
                                                        Ext.getCmp('app-calendar').setStartDate(dt);
                                                    },
                                                    scope: this
                                                }
                                            }
                                        },{
                                            xtype: 'extensible.calendarlist',
                                            store: MediaPlan.calendarsStore,
                                            title:'Oznake blokova',
                                            border: false,
                                            width: 178
                                        }]
                                },{
                                            xtype: 'extensible.calendarpanel',
                                            eventStore: MediaPlan.eventStore,
                                            calendarStore: MediaPlan.calendarsStore,
                                            border: false,
                                            id:'app-calendar',
                                            region: 'center',
                                            activeItem: 1,
                                            viewConfig: {
                                                viewStartHour: 6,
                                                viewEndHour: 24,
                                                showTime: false
                                            },

                                            showMultiDayView: false,
                                            showWeekView: false,
                                            showMultiWeekView: false,
                                            showMonthView: false,
                                            showTodayText: false,

                                            listeners: {
                                                'eventclick': {
                                                    fn: function(vw, rec, el){
                                                        var M = Extensible.calendar.data.EventMappings;
                                                        //alert(rec.data[M.EventId.name]);
                                                        var dateSt = rec.data[M.StartDate.name];
                                                        var blokID = rec.data[M.Location.name];
                                                        var naslov = rec.data[M.Title.name];
                                                        Ext.getCmp('app-calendar').datum = dateSt;
                                                        Ext.getCmp('app-calendar').blok = blokID;
                                                        Ext.getCmp('app-calendar').naslov = naslov;
                                                        Ext.create('Mediaplan.mediaPlan.blockDetails.Window');
                                                        
                                                        //Ext.getCmp('mediaPlanBlockDetailsGrid').store.load({params:{datum:dateSt, blok:blokID}});
                                                    },
                                                    scope: this
                                                },
                                                'viewchange': {
                                                    fn: function(p, vw, dateInfo){
                                                        if(dateInfo){
                                                            MediaPlan.updateTitle(dateInfo.viewStart, dateInfo.viewEnd);
                                                        }
                                                    },
                                                    scope: this
                                                }
                                            }
                                }]
                            }]
                        });
                        Ext.getCmp('mediaPlanTabPanel').setActiveTab(mediaPlanTab);
                   } else {
                       Ext.getCmp('mediaPlanTabPanel').setActiveTab(Ext.getCmp('mediaPlanTab'));
                   }
                }
            }
                ,'-',{
                    text:Lang.MediaPlan_toolBar_Reports,
                    tooltip:Lang.MediaPlan_toolBar_Reports_toolTip,
                    id:'mediaPlanBtnReports',
                    disabled:true,
                    scale:'large',
                    icon:'Images/report_32.png',
                    iconAlign:'top',
                    width:80,
                    handler:function(){
                        if (!Ext.getCmp('reportsTab')) {
                            var reportsTab = Ext.getCmp('mediaPlanTabPanel').add({
                                title: Lang.MediaPlan_toolBar_Reports,
                                id:'reportsTab',
                                closable: true,
                                autoScroll: true,
                                iconCls:'report',
                                height:Ext.getBody().getHeight() - 180,
                                items: [Ext.widget('mediaplanreportspanel')]
                            });
                            Ext.getCmp('mediaPlanTabPanel').setActiveTab(reportsTab);
                        } else {
                            Ext.getCmp('mediaPlanTabPanel').setActiveTab(Ext.getCmp('reportsTab'));
                        }
                    }
                }
                ,'-',{
                    text:Lang.MediaPlan_toolBar_Sporno,
                    tooltip:Lang.MediaPlan_toolBar_Sporno_toolTip,
                    id:'mediaPlanBtnPomocniAlati',
                    disabled:true,
                    scale:'large',
                    icon:'Images/sporno_32.png',
                    iconAlign:'top',
                    width:80,
                    handler:function(){
                        if (!Ext.getCmp('spornoTab')) {
                            var spornoTab = Ext.getCmp('mediaPlanTabPanel').add({
                                title: Lang.MediaPlan_toolBar_Sporno,
                                id:'spornoTab',
                                closable: true,
                                autoScroll: true,
                                iconCls:'sporno',
                                height:Ext.getBody().getHeight() - 180,
                                items: [Ext.widget('mediaplanspornopanel')]
                            });
                            Ext.getCmp('mediaPlanTabPanel').setActiveTab(spornoTab);
                        } else {
                            Ext.getCmp('mediaPlanTabPanel').setActiveTab(Ext.getCmp('spornoTab'));
                        }
                    }
                }


            ];
            this.callParent(arguments);
        }
})
