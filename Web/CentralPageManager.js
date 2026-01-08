Ext.Loader.setConfig({
    enabled: true
});

Ext.Loader.setPath('Ext.ux', 'ExtJS/ux/');
Ext.Loader.setPath('Sch', 'ExtJS/ux/scheduler');

Ext.require([
    'Ext.Viewport',
    'Ext.ux.statusbar.StatusBar',
    'Ext.toolbar.Toolbar',
    'Ext.panel.Panel',
    'Ext.tab.Panel',
    'Ext.form.Panel',
    'Ext.ux.grid.RowActions',
    'Ext.ux.grid.CheckBoxList',
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Extensible.calendar.CalendarPanel',
    'Extensible.calendar.data.MemoryEventStore',
    'Extensible.example.calendar.data.Events',
    'Ext.toolbar.Paging',
    'Ext.ux.SlidingPager',
    'Sch.panel.SchedulerGrid',
    
    'Ext.layout.container.Border',
    'Extensible.calendar.CalendarPanel',
    'Extensible.calendar.gadget.CalendarListPanel',
    'Extensible.calendar.data.MemoryCalendarStore',
    'Extensible.calendar.data.MemoryEventStore',
    'Extensible.example.calendar.data.Events',
    'Extensible.example.calendar.data.Calendars'
]);

Ext.onReady(function ()
{


	Ext.QuickTips.init();
        
        
        var statusBar = Ext.create('Ext.ux.statusbar.StatusBar',{
            items:[
                Ext.Date.format(new Date(), 'd.m.Y'),
                '-',
                Lang.CentralPage_bottomBar_Manufacturer
            ]
        });
        
        var topBar = Ext.create('Ext.toolbar.Toolbar',{
            border:false,
            items:[{
                    xtype:'image',
                    src:'Images/company_logo_16.png'
            },
            //Lang.CentralPage_topBar_AppName,
            {
                xtype:'tbfill'
            },{
                xtype:'button',
                id:'centralPageUserNameSurname',
                text:'--------------------------------------------',
                tooltip:Lang.CentralPage_topBar_tooltip_UserDetails,
                iconCls:'central_page_user',
                menu:{
                    items:[{
                         text:Lang.CentralPage_topBar_UserDetails,
                         iconCls:'central_page_tool',
                         handler:function(){
                             Ext.widget('userdetailsdialog')
                         }
                    },{
                         text:Lang.CentralPage_topBar_PassChange,
                         iconCls:'central_page_key',
                         handler:function(){
                             Ext.widget('userpasschangedialog')
                         }
                    }]
                }
            },'-',{
                xtype:'button',
                icon:'Images/logout_16.png',
                text:Lang.CentralPage_topBar_Logout,
                tooltip:Lang.CentralPage_topBar_tooltip_Logout,
                handler:function(){
                    window.location = "../logout.php";
                }
            }]
        });

        
        var viewport = Ext.create('Ext.Viewport', {
		layout: 'border',
		items: [{
			region: 'north',
			//height: 25,
			layout: 'fit',
			margins: '0 0 0 0',
			items: [topBar]
		}, {
			region: 'south',
			height: 25,
			items: [statusBar]
		},
                        Ext.create('Ext.tab.Panel', {
                                region: 'center',
                                id:'centralPageTabPanel',
                                deferredRender: false,
                                activeTab: 0,
                                items: [{
                                    title:Lang.CentralPage_tabs_MediaPlan,
                                    id:'centralPageTabMediaplan',
                                    items:[{
                                            xtype:'mediaplanmaintoolbar'
                                    },
                                    Ext.create('Ext.tab.Panel', {
                                            deferredRender: false,
                                            id:'mediaPlanTabPanel',
                                            items: []
                                    })
                                ]
                                },{
                                    title:Lang.CentralPage_tabs_Administration,
                                    id:'centralPageTabAdministration',
                                    disabled:true,
                                    items:[{
                                            xtype:'administrationmainpanel'
                                    }]
                                }]
                        })
                ]
	    });
        
        
        Common.LoggedUserNameSurname();
        
        var globalPermissions;
        
        Common.getUserPermissions(function(response){

                 var permissions = response;
                 //var permissions = [110,120,130,140,150,210,220,225,230,240,250,260,270,310,320,330,340,350,360,370,380,390];
                 Common.allUserPermisions = permissions;

                 for (i = 0; i < permissions.length; i++) {
                    var p=permissions[i];
                    
                    if(p == 110){
                        Ext.getCmp('mediaPlanBtnClients').enable();
                    };
                    
                    if(p == 120){
                        Ext.getCmp('mediaPlanBtnAgencies').enable();
                    };
                    
                    if(p == 130){
                        Ext.getCmp('mediaPlanBtnCampaignes').enable();
                    };
                    
                    if(p == 140){
                        Ext.getCmp('mediaPlanBtnMediaPlan').enable();
                    };
                    
                    if(p == 150){
                        Ext.getCmp('mediaPlanBtnReports').enable();
                    };

                     if(p == 160){
                         Ext.getCmp('mediaPlanBtnPomocniAlati').enable();
                     };





                   if(p == 310){
                        Ext.getCmp('centralPageTabAdministration').enable();
                        Ext.getCmp('administrationBtnUser').show();
                        Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
   
                   if(p == 320){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       Ext.getCmp('administrationBtnRole').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    
                   if(p == 330){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       Ext.getCmp('administrationBtnClient').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    
                   if(p == 340){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       Ext.getCmp('administrationBtnAgency').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    
                   if(p == 350){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       Ext.getCmp('administrationBtnContractType').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    
                   if(p == 360){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       Ext.getCmp('administrationBtnActivityTyepe').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    
                   if(p == 370){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       Ext.getCmp('administrationBtnBlock').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    
                   if(p == 380){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       Ext.getCmp('administrationBtnPriceList').show();
					   Ext.getCmp('administrationBtnServicePriceList').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    if(p == 390){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       //Ext.getCmp('campaigneBtnManual').show();
                       Ext.getCmp('campaigneBtnTemplate').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    if(p == 400){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       //Ext.getCmp('campaigneBtnManual').show();
                       Ext.getCmp('campaigneBtnTemplate').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    };
                    if(p == 410){
                       Ext.getCmp('centralPageTabAdministration').enable();
                       //Ext.getCmp('campaigneBtnManual').show();
                       //Ext.getCmp('weekBtnTemplate').show();
                       Ext.getCmp('centralPageTabPanel').setActiveTab(Ext.getCmp('centralPageTabMediaplan'));
                    }
                 };
        });
        
});

