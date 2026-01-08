Ext.define('Mediaplan.mediaPlan.clients.details.Window', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientdetailswindow',

	title: ' ',
        id:'clientDetailsWindow',
	layout: 'fit',
	autoShow: true,
        padding:3,
	iconCls: 'user',
	modal: true,
	width: 900,
	height: 550,
        initComponent: function ()
	{
           this.listeners = {
                show: function(){
                    var title = Lang.MediaPlan_clients_details_window_Title + this.clientName;
                    this.setTitle(title);
                    if(typeof this.entryID != 'undefined'){
                        this.populateFields(this.entryID);
                        Ext.getCmp('clientDetailsImportantDatesGrid').store.proxy.extraParams.clientID = this.entryID;
                        Ext.getCmp('clientDetailsImportantDatesGrid').getStore().load()
                    }
                    Ext.getCmp('hdnClientDetailsID').setValue(this.entryID);

                    for (i = 0; i < Common.allUserPermisions.length; i++) {
                        var p=Common.allUserPermisions[i];
                      
                        if(p == 210){
                            Ext.getCmp('userDetailsMenuBtnCampaigne').enable();
                            Ext.getCmp('userDetailsMenuBtnCampaigneTemplate').enable();
							Ext.getCmp('userDetailsMenuBtnManualCampaigne').enable();
							Ext.getCmp('userDetailsMenuBtnSponsorship').enable();
                            
                        };

                        if(p == 240){
                            Ext.getCmp('userDetailsMenuBtnOffers').enable();
                            Ext.getCmp('clientDetailsOfferTab').enable();
                        };
                        
                        if(p == 250){
                            Ext.getCmp('userDetailsMenuBtnCommunications').enable();
                            Ext.getCmp('clientDetailsCommunicationTab').enable();
                        };
                        
                        if(p == 260){
                            Ext.getCmp('userDetailsMenuBtnImportantDates').enable();
                        };
                        
                        if(p == 270){
                            Ext.getCmp('userDetailsMenuBtnContacts').enable();
                            Ext.getCmp('clientDetailsContactsTab').enable();
                        };
                    }
                }
                
            };
            var window = this;
            
            this.tbar = [{
                text:Lang.MediaPlan_clients_details_window_tbar_Contacts,
                iconCls:'user',
                menu:[{
                        text:Lang.MediaPlan_clients_details_window_tbar_ContactsAdd,
                        iconCls:'user_add',
                        id:'userDetailsMenuBtnContacts',
                        disabled:true,
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsGeneralDataTab'));
                             Ext.widget('clientdetailscontactsdialog');
                        }
                }]
            },'-',{
                text:Lang.MediaPlan_clients_details_window_tbar_Brend,
                iconCls:'table',
                menu:[{
                        text:Lang.MediaPlan_clients_details_window_tbar_BrendAdd,
                        iconCls:'table_add',
                        id:'userDetailsMenuBtnBrend',
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsBrendTab'));
                            Ext.widget('clientdetailsbrenddialog');
                        }
                }]
			},'-',{
                text:Lang.MediaPlan_clients_details_window_tbar_ImportantDates,
                iconCls:'calendar',
                menu:[{
                        text:Lang.MediaPlan_clients_details_window_tbar_ImportantDatesAddClient,
                        iconCls:'calendar_add',
                        id:'userDetailsMenuBtnImportantDates',
                        disabled:true,
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsGeneralDataTab'));
                            Ext.widget('clientdetailsimportantdatesdialog');
                        }
                }/*,{
                        text:Lang.MediaPlan_clients_details_window_tbar_ImportantDatesAddContact,
                        iconCls:'calendar_add',
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsContactsTab'));
                        }
                }*/]
            },'-',{
                text:Lang.MediaPlan_clients_details_window_tbar_Communicaton,
                iconCls:'communication',
                menu:[{
                        text:Lang.MediaPlan_clients_details_window_tbar_CommunicatonAdd,
                        iconCls:'communication_add',
                        id:'userDetailsMenuBtnCommunications',
                        disabled:true,
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsCommunicationTab'));
                            Ext.widget('clientdetailscommunicationsdialog');
                        }
                }]
            },'-',{
                text:Lang.MediaPlan_clients_details_window_tbar_Offers,
                iconCls:'offer',
                menu:[{
                        text:'Ponude',
                        iconCls:'offer_add',
                        id:'userDetailsMenuBtnOffers',
                        disabled:true,
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsOfferTab'));
                            //Ext.widget('clientdetailsnewofferwindow');
                        }
                }]
            },'-',{
                text:Lang.MediaPlan_clients_details_window_tbar_Campaignes,
                iconCls:'table',
                menu:[{
                        text:Lang.MediaPlan_clients_details_window_tbar_CampaignesAdd,
                        iconCls:'table_add',
                        id:'userDetailsMenuBtnCampaigne',
                        disabled:true,
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsCampaignesTab'));
                            Ext.widget('clientdetailscampaigneswindow');
                        }
                },{
                        text:Lang.MediaPlan_clients_details_window_tbar_CampaignesTemplateAdd,
                        iconCls:'form_add',
                        id:'userDetailsMenuBtnCampaigneTemplate',
                        disabled:true,
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsCampaignesTab'));
                            Ext.widget('clientdetailscampaignestemplatewindow');
                        }                    
                },{
                        text:Lang.MediaPlan_clients_details_window_tbar_ManualCampaigneAdd,
                        iconCls:'form_add',
                        id:'userDetailsMenuBtnManualCampaigne',
                        disabled:true,
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsCampaignesTab'));
                             Ext.widget('manualcampaigneswindow');
                        }  				
				},{
                        text:Lang.MediaPlan_clients_details_window_tbar_SponsorshipAdd,
                        iconCls:'form_add',
                        id:'userDetailsMenuBtnSponsorship',
                        disabled:true,
                        handler:function(){
                            Ext.getCmp('clientDetailsTabPanel').setActiveTab(Ext.getCmp('clientDetailsCampaignesTab'));
                            Ext.widget('clientdetailscampaignesponsorshipwindow');
                        }  				
				}]
            }];
            
            this.items =  new Ext.create('Ext.tab.Panel', {
                                deferredRender: false,
                                activeTab: 0,
                                id:'clientDetailsTabPanel',
                                items: [{
                                        title:Lang.MediaPlan_clients_details_window_tab_GeneralData,
                                        id:'clientDetailsGeneralDataTab',
                                        layout:'fit',
                                        items:[{
                                                xtype:'clientdetailsform',
                                                entryID:this.entryID
                                        }]
                                },{
                                        title:Lang.MediaPlan_clients_details_window_tbar_Brend,
                                        id:'clientDetailsBrendTab',
                                        autoScroll: true,
                                        layout:'fit',
                                        items:[{
                                                xtype:'clientdetailsbrendgrid'
                                        }],
                                        listeners: {
                                            activate:function(){
                                                Ext.getCmp('clientDetailsBrendGrid').store.proxy.extraParams.klijentID = this.ownerCt.ownerCt.entryID;
                                                Ext.getCmp('clientDetailsBrendGrid').store.load();
                                            }
                              
                                        }
								},{
                                        title:Lang.MediaPlan_clients_details_window_tab_Contacts,
                                        id:'clientDetailsContactsTab',
                                        autoScroll: true,
                                        disabled:true,
                                        layout:'fit',
                                        items:[{
                                                xtype:'clientdetailscontactsgrid'
                                        }],
                                        listeners: {
                                            activate:function(){
                                                Ext.getCmp('clientDetailsContactsGrid').store.proxy.extraParams.klijentID = this.ownerCt.ownerCt.entryID;
                                                Ext.getCmp('clientDetailsContactsGrid').store.load();
                                            }
                              
                                        }
                                },{
                                        title:Lang.MediaPlan_clients_details_window_tab_Communications,
                                        id:'clientDetailsCommunicationTab',
                                        autoScroll: true,
                                        disabled:true,
                                        layout:'fit',
                                        items:[{
                                                xtype:'clientdetailscommunicationsgrid'
                                        }],
                                        listeners: {
                                            activate:function(){
                                                Ext.getCmp('clientDetailsCommunicationsGrid').store.proxy.extraParams.klijentID = this.ownerCt.ownerCt.entryID;
                                                Ext.getCmp('clientDetailsCommunicationsGrid').store.load();
                                            }
                              
                                        }
                                },{
                                        title:Lang.MediaPlan_clients_details_window_tab_Offers,
                                        id:'clientDetailsOfferTab',
                                        autoScroll: true,
                                        disabled:true,
                                        //layout:'fit',
                                        items:[{
                                                xtype:'clientdetailsoffersgrid',
                                                width:'100%',
                                                height:200
                                        },{
                                            xtype:'clientdetailsofferwindow',
                                            height:265
                                        }],
                                        listeners: {
                                            activate:function(){
                                                Ext.getCmp('clientDetailsOffersGrid').store.proxy.extraParams.klijentID = this.ownerCt.ownerCt.entryID;
                                                Ext.getCmp('clientDetailsOffersGrid').store.load();
                                            }
                              
                                        }
                                },{
                                        title:Lang.MediaPlan_clients_details_window_tab_Campaignes,
                                        id:'clientDetailsCampaignesTab',
                                        autoScroll: true,
                                        //disabled:true,
                                        //layout:'fit',
                                        items:[{
                                                xtype:'clientdetailscampaignesgrid',
                                                clientID:this.entryID,
                                                width:'100%',
                                                height:265
                                        }, {
                                            xtype: 'clientdetailscampaignes2window',
                                            height: 200
                                        },{
                                            xtype:'hidden',
                                            id:'clientDetailsKampanja2ID'
                                        }],
                                        listeners: {
                                            activate:function(){
                                                Ext.getCmp('clientDetailsCampaignesGrid').store.proxy.extraParams.klijentID = this.ownerCt.ownerCt.entryID;
                                                Ext.getCmp('clientDetailsCampaignesGrid').store.load();
                                            }
                              
                                        }
                                }]
            });
            
            this.callParent(arguments);
        },
        
                populateFields:function(entryID){


                var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsWindow'), Lang.Loading);
                waitBox.show();
                if (entryID === -1) {
                    waitBox.hide();
                }
                else {

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Klijent.php',
                        params: { action: 'KlijentLoad', entryID: entryID },
                        success: function (response, request) {
                            var data = Ext.decode(response.responseText).data;

                            var form = Ext.getCmp('clientsDetailsForm').getForm();
                            form.setValues(data);
                            
                            Ext.getCmp('clientAdminisrtationContractType').store.load({ callback: function (r, options, success) {
                                        Ext.getCmp('clientAdminisrtationContractType').setValue(data.tipUgovoraID);
                                    }
                             });
                             //Ext.getCmp('cblClientDialogClientActivity').selectItems(data.delatnostList);

                             Ext.getCmp('clientAdminisrtationActivityType').store.load({ callback: function (r, options, success) {
                                        Ext.getCmp('clientAdminisrtationActivityType').setValue(data.delatnostID);
                                    }
                             });

                            Ext.getCmp('cblClientAdministrationDialogClientList').selectItems(data.agencijaList);

                            waitBox.hide();
                        },
                        failure: function (response, request) {
                            waitBox.hide();
                        }
                    });
                }
        }
});
