Ext.define('Mediaplan.administration.centralPanel', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.administrationmainpanel',
	layout: 'border',
    defaults: { border: false, frame: true, split: true },
	width:'100%',
    height:'100%',
        
	initComponent: function ()
	{
            this.items = [{
                region:'west',
                layout:'accordion',
                width: 220,
				minSize: 220,
				maxSize: 220,
                items:[{
                   title:Lang.Administration_leftMenu_accordian_userAdministration_Title,
                   iconCls: 'user',
                   items:[{
                            html:'<img  src="Images/Icons/user_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_userAdministration_UserAccounts,
                            margin:'5 3 0 15',
                            hidden:true,
                            id:'administrationBtnUser',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px;',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('usersAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_userAdministration_UserAccounts,
                                                        id: 'usersAdministrationTab',
                                                        bodyStyle: 'padding:10px;',
                                                        closable: true,
                                                        autoScroll: true,
                                                        iconCls:'user',
                                                        items: [Ext.widget('useradministrationfilter'),{
                                                                xtype:'useradministrationgrid',
                                                                height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 170
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('usersAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('usersAdministrationTab');
                                                }
                                            }
                                        );
                                }
                            }
                   },{
                            html:'<img  src="Images/Icons/user_group_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_userAdministration_UserRoles,
                            margin:'5 3 0 15',
                            hidden:true,
                            id:'administrationBtnRole',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px;',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('rolesAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_userAdministration_UserRoles,
                                                        id: 'rolesAdministrationTab',
                                                        bodyStyle: 'padding:10px;',
                                                        closable: true,
                                                        autoScroll: true,
                                                        iconCls:'user_group',
                                                        items: [Ext.widget('roleadministrationfilter'),{
                                                                xtype:'roleadministrationgrid',
                                                                height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('rolesAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('rolesAdministrationTab');
                                                }
                                            }
                                        );
                                }
                            }
                   }]    
                },{
                   title:Lang.Administration_leftMenu_accordian_clients_Title,
                   iconCls:'vcard',
                   items:[{
                            html:'<img  src="Images/Icons/vcard_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_clients_Clients,
                            margin:'5 3 0 15',
                            hidden:true,
                            id:'administrationBtnClient',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('clientsAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_clients_Clients,
                                                        id: 'clientsAdministrationTab',
                                                        bodyStyle: 'padding:10px;',
                                                        closable: true,
                                                        autoScroll: true,
                                                        iconCls:'vcard',
                                                        items: [Ext.widget('clientadministrationfilter'),{
                                                                xtype:'clientadministrationgrid',
                                                                height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 170
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('clientsAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('clientsAdministrationTab');
                                                }
                                            }
                                        );
                                }
                            }
                   },{
                             html:'<img  src="Images/Icons/vcard_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_clients_Agencies,
                             margin:'5 3 0 15',
                             hidden:true,
                             id:'administrationBtnAgency',
                             border:false,
                             bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
                             listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('agenciesAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_clients_Agencies,
                                                        id: 'agenciesAdministrationTab',
                                                        bodyStyle: 'padding:10px;',
                                                        closable: true,
                                                        autoScroll: true,
                                                        iconCls:'vcard',
                                                        items: [Ext.widget('agencyadministrationfilter'),{
                                                                xtype:'agencyadministrationgrid',
                                                                height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 170
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('agenciesAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('agenciesAdministrationTab');
                                                }
                                            }
                                        );
                                }
                             }
                  }]
                },{
                   title:Lang.Administration_leftMenu_accordian_coodeBooks_Title,
                   iconCls:'table',
                   items:[{
                            html:'<img  src="Images/Icons/table_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_coodeBooks_ContractType,
                            margin:'5 3 0 15',
                            hidden:true,
                            id:'administrationBtnContractType',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('contractTypeAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_coodeBooks_ContractType,
                                                        id: 'contractTypeAdministrationTab',
                                                        bodyStyle: 'padding:10px;',
                                                        closable: true,
                                                        autoScroll: true,
                                                        iconCls:'table',
                                                        items: [Ext.widget('contracttypeadministrationfilter'),{
                                                                xtype:'contracttypeadministrationgrid',
                                                                height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('contractTypeAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('contractTypeAdministrationTab');
                                                }
                                            }
                                        );
                                }
                            }
                   },{
                             html:'<img  src="Images/Icons/table_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_coodeBooks_ActivityType,
                             margin:'5 3 0 15',
                             hidden:true,
                             id:'administrationBtnActivityTyepe',
                             border:false,
                             bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
                             listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('activityTypeAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_coodeBooks_ActivityType,
                                                        id: 'activityTypeAdministrationTab',
                                                        bodyStyle: 'padding:10px;',
                                                        closable: true,
                                                        autoScroll: true,
                                                        iconCls:'table',
                                                        items: [Ext.widget('activitytypeadministrationfilter'),{
                                                                xtype:'activitytypeadministrationgrid',
                                                                height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('activityTypeAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('activityTypeAdministrationTab');
                                                }
                                            }
                                        );
                                }
                             }
                },{
                             html:'<img  src="Images/Icons/table_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_coodeBooks_Blocks,
                             margin:'5 3 0 15',
                             hidden:true,
                             id:'administrationBtnBlock',
                             border:false,
                             bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
                             listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('blockAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_coodeBooks_Blocks,
                                                        id: 'blockAdministrationTab',
                                                        closable: true,
                                                        layout:'fit',
                                                        autoScroll: true,
                                                        iconCls:'table',
                                                        items: [{
                                                                xtype:'blockadministrationgrid'
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('blockAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('blockAdministrationTab');
                                                }
                                            }
                                        );
                                }
                             }
                },{
                             html:'<img  src="Images/Icons/table_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_coodeBooks_PriceList,
                             margin:'5 3 0 15',
                             hidden:true,
                             id:'administrationBtnPriceList',
                             border:false,
                             bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
                             listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('priceListAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_coodeBooks_PriceList,
                                                        id: 'priceListAdministrationTab',
                                                        closable: true,
                                                        layout:'fit',
                                                        autoScroll: true,
                                                        iconCls:'table',
                                                        items: [{
                                                                xtype:'pricelistadministrationgrid'
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('priceListAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('priceListAdministrationTab');
                                                }
                                            }
                                        );
                                }
                             }
                },{
                             html:'<img  src="Images/Icons/table_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_coodeBooks_ServicePriceList,
                             margin:'5 3 0 15',
                             hidden:true,
                             id:'administrationBtnServicePriceList',
                             border:false,
                             bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
                             listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('servicesPriceListAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_coodeBooks_ServicePriceList,
                                                        id: 'servicePriceListAdministrationTab',
														bodyStyle: 'padding:10px;',
														closable: true,
														autoScroll: true,
														iconCls:'table',
                                                        items: [Ext.widget('servicepriceadministrationfilter'),{
                                                                xtype:'servicepriceadministrationgrid',
																height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('servicePriceListAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('servicePriceListAdministrationTab');
                                                }
                                            }
                                        );
                                }
                             }
                },{
					 html:'<img  src="Images/Icons/table_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_coodeBooks_VoiceList,
					 margin:'5 3 0 15',
					 //hidden:true,
					 id:'administrationBtnVoiceList',
					 border:false,
					 bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
					 listeners: {
						render: function(c){c.el.on('click', function() 
									{
										if (!Ext.getCmp('voiceListAdministrationTab')) {
										   Ext.getCmp('administratinCentralTabPanel').add({
												title: Lang.Administration_leftMenu_accordian_coodeBooks_VoiceList,
												id: 'voiceListAdministrationTab',
												bodyStyle: 'padding:10px;',
												closable: true,
												autoScroll: true,
												iconCls:'table',
												items: [Ext.widget('voiceadministrationfilter'),{
														xtype:'voiceadministrationgrid',
														height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
												}]
											});
											Ext.getCmp('administratinCentralTabPanel').setActiveTab('voiceListAdministrationTab');
										} else {
											Ext.getCmp('administratinCentralTabPanel').setActiveTab('voiceListAdministrationTab');
										}
									}
								);
						}
					 }				
				},{
					 html:'<img  src="Images/Icons/table_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_coodeBooks_StationList,
					 margin:'5 3 0 15',
					 //hidden:true,
					 id:'administrationBtnStationList',
					 border:false,
					 bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
					 listeners: {
						render: function(c){c.el.on('click', function() 
									{
										if (!Ext.getCmp('stationListAdministrationTab')) {
										   Ext.getCmp('administratinCentralTabPanel').add({
												title: Lang.Administration_leftMenu_accordian_coodeBooks_StationList,
												id: 'stationListAdministrationTab',
												bodyStyle: 'padding:10px;',
												closable: true,
												autoScroll: true,
												iconCls:'table',
												items: [Ext.widget('stationadministrationfilter'),{
														xtype:'stationadministrationgrid',
														height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
												}]
											});
											Ext.getCmp('administratinCentralTabPanel').setActiveTab('stationListAdministrationTab');
										} else {
											Ext.getCmp('administratinCentralTabPanel').setActiveTab('stationListAdministrationTab');
										}
									}
								);
						}
					 }				
				},{
					 html:'<img  src="Images/Icons/table_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_coodeBooks_StationProgramList,
					 margin:'5 3 0 15',
					 //hidden:true,
					 id:'administrationBtnStationProgram',
					 border:false,
					 bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px',
					 listeners: {
						render: function(c){c.el.on('click', function() 
									{
										if (!Ext.getCmp('stationProgramAdministrationTab')) {
										   Ext.getCmp('administratinCentralTabPanel').add({
												title: Lang.Administration_leftMenu_accordian_coodeBooks_StationProgramList,
												id: 'stationProgramAdministrationTab',
												bodyStyle: 'padding:10px;',
												closable: true,
												autoScroll: true,
												iconCls:'table',
												items: [Ext.widget('stationprogramfilter'),{
														xtype:'stationprogramgrid',
														height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
												}]
											});
											Ext.getCmp('administratinCentralTabPanel').setActiveTab('stationProgramAdministrationTab');
										} else {
											Ext.getCmp('administratinCentralTabPanel').setActiveTab('stationProgramAdministrationTab');
										}
									}
								);
						}
					 }	
				}] 
                },{
                   title:Lang.Administration_leftMenu_accordian_campaigneAdministration_Title,
                   iconCls: 'form',
                   items:[


/*
                       {
                            html:'<img  src="Images/Icons/form_add_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_campaigneAdministration_Campaigne,
                            margin:'5 3 0 15',
                            hidden:true,
                            id:'campaigneBtnManual',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px;',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                alert('manuelni unos je premesten u sekciju klijenta!');
                                                //Ext.widget('manualcampaigneswindow');
                                            }
                                        );
                                }
                            }
                   },
*/


                       {
                            html:'<img  src="Images/Icons/form_add_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_campaigneAdministration_CampaigneTemplates,
                            margin:'5 3 0 15',
                            hidden:true,
                            id:'campaigneBtnTemplate',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px;',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('campaigneTemplateAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_campaigneAdministration_CampaigneTemplates,
                                                        id: 'campaigneTemplateAdministrationTab',
                                                        closable: true,
                                                        bodyStyle: 'padding:10px;',
                                                        autoScroll: true,
                                                        iconCls:'form',
                                                        items: [{
                                                                xtype:'campaignetemplatesfilter'
                                                        },{
                                                                xtype:'campaignetemplatesgrid',
                                                                height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('campaigneTemplateAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('campaigneTemplateAdministrationTab');
                                                }
                                            }
                                        );
                                }
                            }
                   }/*,{
                            html:'<img  src="Images/Icons/form_add_16.png"  style="border-style: none; vertical-align:middle;" />&nbsp;&nbsp;'+Lang.Administration_leftMenu_accordian_campaigneAdministration_WeekTemplates,
                            margin:'5 3 0 15',
                            hidden:true,
                            id:'weekBtnTemplate',
                            border:false,
                            bodyStyle: 'cursor:hand; font-family: tahoma,arial,verdana,sans-serif; font-size: 11px; vertical-align:middle; margin-bottom:5px;',
                            listeners: {
                                render: function(c){c.el.on('click', function() 
                                            {
                                                if (!Ext.getCmp('weekTemplateAdministrationTab')) {
                                                   Ext.getCmp('administratinCentralTabPanel').add({
                                                        title: Lang.Administration_leftMenu_accordian_campaigneAdministration_WeekTemplates,
                                                        id: 'weekTemplateAdministrationTab',
                                                        closable: true,
                                                        bodyStyle: 'padding:10px;',
                                                        autoScroll: true,
                                                        iconCls:'form',
                                                        items: [{
                                                                xtype:'weektemplatesfilter'
                                                        },{
                                                                xtype:'weektemplatesgrid',
                                                                height:Ext.getCmp('administratinCentralTabPanel').getHeight() - 135
                                                        }]
                                                    });
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('weekTemplateAdministrationTab');
                                                } else {
                                                    Ext.getCmp('administratinCentralTabPanel').setActiveTab('weekTemplateAdministrationTab');
                                                }
                                            }
                                        );
                                }
                            }
                   }*/]
                }]
            },
                Ext.create('Ext.tab.Panel', {
                        region: 'center',
                        id:'administratinCentralTabPanel',
                        deferredRender: false,
                        activeTab: 0
                })    
            ]
            this.callParent(arguments);
        }
});

