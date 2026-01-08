Ext.define('Mediaplan.mediaPlan.clients.details.OffersWindow', {
	extend: 'Ext.panel.Panel',
        alias: 'widget.clientdetailsofferwindow',
        id:'clientDetailsOfferWindow',
        border:false,
        frame:false,
        padding:0,
	width: '100%',
	height:'100%',
        initComponent: function ()
	{
            
            var window = this;
            
            
            this.items =  new Ext.create('Ext.tab.Panel', {
                    deferredRender: false,
                    activeTab: 0,
                    id:'clientDetailsOffersTabPanel',
                    items: [{
                            title:Lang.MediaPlan_clients_details_offers_window_tab_OfferData,
                            id:'clientDetailsOfferDataTab',
                            layout:'fit',
                            items:[{
                                    xtype:'clientdetailsofferform',
                                    height:'237'
                            }]
                    },{
                            title:Lang.MediaPlan_clients_details_offers_window_tab_OfferDocuments,
                            id:'clientDetailsOfferDocumentsTab',
                            autoScroll: true,
                            layout:'fit',
                            items:[{
                                    xtype:'clientdetailsoffersdocumentsgrid',
                                    height:237
                            }],
                            listeners: {
                                activate:function(){
                                     var ponudaID = Ext.getCmp('clientDetailsOfferID').getValue();
                                     Ext.getCmp('clientDetailsOffersDocumentsGrid').store.proxy.extraParams.ponudaID = ponudaID;
                                     Ext.getCmp('clientDetailsOffersDocumentsGrid').store.load();
                                }

                            }
                    },/*{
                            title:Lang.MediaPlan_clients_details_offers_window_tab_OfferNotes,
                            id:'clientDetailsOfferNotesTab',
                            autoScroll: true,
                            layout:'fit',
                            items:[{
                                    xtype:'clientdetailsoffersnotesgrid',
                                    height:237
                            }],
                            listeners: {
                                activate:function(){
                                     var ponudaID = Ext.getCmp('clientDetailsOfferID').getValue();
                                     Ext.getCmp('clientDetailsOffersNotesGrid').store.proxy.extraParams.ponudaID = ponudaID;
                                     Ext.getCmp('clientDetailsOffersNotesGrid').store.load();
                                }

                            }
                    },*/{
                            title:Lang.MediaPlan_clients_details_offers_window_tab_OfferHistory,
                            id:'clientDetailsOfferHistoryTab',
                            autoScroll: true,
                            layout:'fit',
                            items:[{
                                    xtype:'clientdetailsoffershistorygrid',
                                    height:237
                            }],
                            listeners: {
                                activate:function(){
                                     var ponudaID = Ext.getCmp('clientDetailsOfferID').getValue();
                                     Ext.getCmp('clientDetailsOffersHistoryGrid').store.proxy.extraParams.ponudaID = ponudaID;
                                     Ext.getCmp('clientDetailsOffersHistoryGrid').store.load();
                                }

                            }
                    }/*,



                        {
                            title:"Fakture",
                            id:'clientDetailsOfferFaktureTab',
                            autoScroll: true,
                            layout:'fit',
                            items:[{
                                xtype:'clientdetailsoffersfakturegrid',
                                height:237
                            }],
                            listeners: {
                                activate:function(){
                                    var ponudaID = Ext.getCmp('clientDetailsOfferID').getValue();
                                    Ext.getCmp('clientDetailsOffersHistoryGrid').store.proxy.extraParams.ponudaID = ponudaID;
                                    Ext.getCmp('clientDetailsOffersHistoryGrid').store.load();
                                }

                            }
                        }*/








                    /*,{
                            title:Lang.MediaPlan_clients_details_offers_window_tab_OfferCampaignes,
                            id:'clientDetailsOfferCampaigneTab',
                            autoScroll: true,
                            layout:'fit',
                            items:[{
                                    xtype:'clientdetailsofferscampaignegrid',
                                    height:237
                            }],
                            listeners: {
                                activate:function(){
                                     var ponudaID = Ext.getCmp('clientDetailsOfferID').getValue();
                                     Ext.getCmp('clientDetailsOffersCampaigneGrid').store.proxy.extraParams.ponudaID = ponudaID;
                                     Ext.getCmp('clientDetailsOffersCampaigneGrid').store.load();
                                }

                            }
                    }*/]
            });
            
            
            this.callParent(arguments);
        }
        
        
});




Ext.define('Mediaplan.mediaPlan.clients.details.OffersForm', {
	extend: 'Ext.form.Panel',
	alias: 'widget.clientdetailsofferform',
        border:false,
        frame:false,
        padding:10,
	//width:'100%',
        //height:'100%',
	initComponent: function ()
	{
            this.id = 'clientsDetailsOfferForm';
            this.items = [{
                    xtype:'fieldcontainer',
                    layout: 'hbox',
                    items:[{
                            xtype:'hidden',
                            id:'clientDetailsOfferID'
                    },{
                        xtype:'textfield',
                        id:'clientDetailsOfferDetailsDate',
                        fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Date,
                        width:250
                    },{
                        xtype:'textfield',
                        id:'clientDetailsOfferDetailsUser',
                        labelAlign:'right',
                        labelWidth:160,
                        fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_User,
                        width:300
                    }]
            },{
                    xtype:'fieldcontainer',
                    layout: 'hbox',
                    items:[{
                        xtype:'textfield',
                        id:'clientDetailsOfferDetailsStatus',
                        fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Status,
                        width:250                            
                    },{
                        xtype:'textfield',
                        id:'clientDetailsOfferDetailsValue',
                        labelWidth:160,
                        labelAlign:'right',
                        fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Value,
                        width:250
                    }]                    
            },{
                    xtype:'textfield',
                    id:'clientDetailsOfferDetailsStatus',
                    fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Status,
                    width:250
            },{
                    xtype:'htmleditor',
                    id:'clientDetailsOfferDetailsOffer',
                    fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Offer,
                    width:800,
                    height:150
            }]    
            this.callParent(arguments);
        } //eo intitcomponent
        

});



Ext.define('Mediaplan.mediaPlan.clients.details.NewOfferWindow', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientdetailsnewofferwindow',

	title: Lang.MediaPlan_clients_details_offers_newoffer_dialog_Title,
        id:'clientsDetailsNewOfferWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'calendar',
	modal: true,
	width: 750,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){

                    var clientID = Ext.getCmp('hdnClientDetailsID').getValue();
                    Ext.getCmp('hdnOfferClientID').setValue(clientID);
;                }
                
            };

            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'clientsDetailsNewOfferForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype: 'hidden',
                            id: 'hdnOfferID',
                            name: 'ponudaID',
                            value: '-1'
                    },{
                            xtype: 'hidden',
                            id: 'hdnOfferClientID',
                            name: 'klijentID'
                    },{
                            xtype: 'fieldcontainer',
                            layout: 'hbox',
                            labelAlign: 'left',
                            items: [{
                                xtype:'datefield',
                                format:'Y-m-d',
                                fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Date,
                                name:'vremePostavke',
                                allowBlank:false,
                                width:250                                   
                            },{
                                xtype:'textfield',
                                fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Value,
                                labelAlign:'right',
                                labelWidth: 160,
                                name:'vrednost',
                                width:250
                            }]
                    },{
                            xtype: 'combobox',
                            fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Status,
                            name:'statusPonudaID',
                            store: new Ext.data.Store({
                                    fields: ['EntryID', 'EntryName'],
                                    proxy: {
                                            type: 'ajax',
                                            url: '../App/Controllers/StatusPonuda.php',
                                            actionMethods: {
                                                    read: 'POST'
                                            },
                                            extraParams: {
                                                    action: 'StatusPonudaGetForComboBox'
                                            },
                                            reader: {
                                                    type: 'json',
                                                    root: 'rows'
                                            }
                                    }
                            }),
                            queryMode: 'remote',
                            typeAhead: true,
                            queryParam: 'filter',
                            emptyText: Lang.Common_combobox_emptyText,
                            valueField: 'EntryID',
                            displayField: 'EntryName',
                            width: 300
                    },{
                            xtype:'htmleditor',
                            name:'sadrzaj',
                            fieldLabel:Lang.MediaPlan_clients_details_offers_newoffer_dialog_Offer,
                            width:650,
                            height:150
                    }]
            }];
        
            this.buttons = [
            {
            	text: 'Save',
            	icon: Icons.Save16,
            	handler: function ()
            	{
                    this.ownerCt.ownerCt.saveData();
            	}
            },
            {
            	text: 'Cancel',
            	scope: this,
            	icon: Icons.Cancel16,
            	handler: this.close
            }
        ];
            this.callParent(arguments);
        },
        
        saveData:function(){
                var form = Ext.getCmp('clientsDetailsNewOfferForm').getForm();


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientsDetailsNewOfferWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = 'PonudaInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Ponuda.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                Ext.getCmp('clientsDetailsNewOfferWindow').close();

                                //rebind grid
                                var grid = Ext.getCmp('clientDetailsOffersGrid');
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
});


Ext.define('Mediaplan.mediaPlan.clients.details.OfferNoteWindow', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientdetailsoffernotewindow',

	title: Lang.MediaPlan_clients_details_offers_offernote_dialog_Title,
        id:'clientsDetailsOfferNoteWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'grid-rowaction-note',
	modal: true,
	width: 550,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){
                    Ext.getCmp('hdnOfferNoteOfferID').setValue(this.entryID);
;                }
                
            };

            
            this.items = [{                   
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'clientsDetailsOfferNoteForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    items:[{
                            xtype:'hidden',
                            name:'ponudaID',
                            id:'hdnOfferNoteOfferID'
                    },{
                            xtype:'hidden',
                            name:'ponudaNapomenaID',
                            value: '-1'
                    },{
                        xtype: 'combobox',
                        fieldLabel:Lang.MediaPlan_clients_details_offers_offernote_dialog_Status,
                        name:'statusPonudaID',
                        store: new Ext.data.Store({
                                fields: ['EntryID', 'EntryName'],
                                proxy: {
                                        type: 'ajax',
                                        url: '../App/Controllers/StatusPonuda.php',
                                        actionMethods: {
                                                read: 'POST'
                                        },
                                        extraParams: {
                                                action: 'StatusPonudaGetForComboBox'
                                        },
                                        reader: {
                                                type: 'json',
                                                root: 'rows'
                                        }
                                }
                        }),
                        queryMode: 'remote',
                        typeAhead: true,
                        queryParam: 'filter',
                        emptyText: Lang.Common_combobox_emptyText,
                        valueField: 'EntryID',
                        displayField: 'EntryName',
                        width: 300                        
                    },{
                        xtype:'textarea',
                        fieldLabel:Lang.MediaPlan_clients_details_offers_offernote_dialog_Note,
                        name:'napomena',
                        width:450,
                        height:150
                    }]
            }];
        
            this.buttons = [
            {
            	text: 'Save',
            	icon: Icons.Save16,
            	handler: function ()
            	{
                    this.ownerCt.ownerCt.saveData();
            	}
            },
            {
            	text: 'Cancel',
            	scope: this,
            	icon: Icons.Cancel16,
            	handler: this.close
            }
        ];
            this.callParent(arguments);
        },
        
        saveData:function(){
                var form = Ext.getCmp('clientsDetailsOfferNoteForm').getForm();


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientsDetailsOfferNoteWindow').getEl(), Lang.Saving);
                    waitBox.show();

                    var formAction = 'PonudaNapomenaInsertUpdate';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/PonudaNapomena.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {
                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                var grid = Ext.getCmp('clientDetailsOffersGrid')
                                grid.reloadGrid(grid);
                                Ext.getCmp('clientsDetailsOfferForm').getForm().reset();
                                Ext.getCmp('clientsDetailsOfferNoteWindow').close();

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
});



Ext.define('Mediaplan.mediaPlan.clients.details.OfferDocumentWindow', {
	extend: 'Ext.window.Window',
	alias: 'widget.clientdetailsofferdocumentwindow',

	title: Lang.MediaPlan_clients_details_offers_offerdocument_dialog_Title,
        id:'clientsDetailsOfferDocumentWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'grid-rowaction-document',
	modal: true,
	width: 550,
	autoHeight: true,
        initComponent: function ()
	{
            this.listeners = {
                show: function(){

                    Ext.getCmp('hdnOfferDocumentOfferID').setValue(this.entryID);
;                }
                
            };

            
            this.items = [{                   
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'clientsDetailsOfferDocumentForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    fileUpload: true,
                    items:[{
                            xtype:'hidden',
                            name:'ponudaID',
                            id:'hdnOfferDocumentOfferID'
                    },{
                            xtype:'hidden',
                            name:'ponudaDokumentID',
                            value: '-1'
                    },{
                            xtype:'textfield',
                            inputType:'file',
                            fieldLabel:Lang.MediaPlan_clients_details_offers_offerdocument_dialog_Attach,
                            name:'prilog',
                            width:450
                    }]
            }];
        
            this.buttons = [
            {
            	text: 'Save',
            	icon: Icons.Save16,
            	handler: function ()
            	{
                    this.ownerCt.ownerCt.saveData();
            	}
            },
            {
            	text: 'Cancel',
            	scope: this,
            	icon: Icons.Cancel16,
            	handler: this.close
            }
        ];
            this.callParent(arguments);
        },
        
        saveData:function(){
                var form = Ext.getCmp('clientsDetailsOfferDocumentForm').getForm();


                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('clientsDetailsOfferDocumentWindow').getEl(), Lang.Saving);
                    waitBox.show();
                    form.submit({
                        url: '../App/Controllers/PonudaDokument.php?action=PonudaDokumentInsertUpdate',
                        success: function (fp,o) {
                            waitBox.hide();
                            Ext.getCmp('clientsDetailsOfferDocumentWindow').close();
                            
                            var grid = Ext.getCmp('clientDetailsOffersDocumentsGrid')
                            grid.reloadGrid(grid);

                        },
                        failure: function (fp, o) {
                            waitBox.hide();
                            alert('Greška u obradi zahteva');
                        }

                    });

                }
        }
});










