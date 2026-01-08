Ext.define('Mediaplan.mediaPlan.reports.communicationReport.Panel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.reportcommunicationpanel',
        border:false,
        frame:false,
	width:'100%',
	initComponent: function ()
	{
            this.id = 'reportsCommunicationReportPanel';
            this.items = [{
                            xtype:'fieldset',
                            title:Lang.MediaPlan_reports_communicationReport_Title,
                            border:true,
                            labelWidth:150,
                            padding:'20',
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_communicationReport_Clients,
                                        name:'klijentID',
                                        store: new Ext.data.Store({
                                                fields: ['EntryID', 'EntryName'],
                                                proxy: {
                                                        type: 'ajax',
                                                        url: '../App/Controllers/Klijent.php',
                                                        actionMethods: {
                                                                read: 'POST'
                                                        },
                                                        extraParams: {
                                                                action: 'KlijentGetForComboBox'
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
                                        width: 400
                                },{
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_communicationReport_Type,
                                        labelWidth:200,
                                        labelAlign: 'right',
                                        name:'tipKomunikacijaID',
                                        store: new Ext.data.Store({
                                                fields: ['EntryID', 'EntryName'],
                                                proxy: {
                                                        type: 'ajax',
                                                        url: '../App/Controllers/TipKomunikacija.php',
                                                        actionMethods: {
                                                                read: 'POST'
                                                        },
                                                        extraParams: {
                                                                action: 'TipKomunikacijaGetForComboBox'
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
                                        width: 500
                                }]     
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_communicationReport_ContractType,
                                        name:'tipUgovoraID',
                                        store: new Ext.data.Store({
                                                fields: ['EntryID', 'EntryName'],
                                                proxy: {
                                                        type: 'ajax',
                                                        url: '../App/Controllers/TipUgovora.php',
                                                        actionMethods: {
                                                                read: 'POST'
                                                        },
                                                        extraParams: {
                                                                action: 'TipUgovoraGetForComboBox'
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
                                        width: 400
                                },{
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_communicationReport_ActivityType,
                                        labelAlign:'right',
                                        labelWidth:200,
                                        name:'delatnostID',
                                        store: new Ext.data.Store({
                                                fields: ['EntryID', 'EntryName'],
                                                proxy: {
                                                        type: 'ajax',
                                                        url: '../App/Controllers/Delatnost.php',
                                                        actionMethods: {
                                                                read: 'POST'
                                                        },
                                                        extraParams: {
                                                                action: 'DelatnostGetForComboBox'
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
                                        width: 500
                                }]     
                            },{
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_communicationReport_Sales,
                                        name:'komercijalistaID',
                                        store: new Ext.data.Store({
                                                fields: ['KorisnikID', 'ImePrezime'],
                                                proxy: {
                                                        type: 'ajax',
                                                        url: '../App/Controllers/Korisnik.php',
                                                        actionMethods: {
                                                                read: 'POST'
                                                        },
                                                        extraParams: {
                                                                action: 'KomercijalistaGetList'
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
                                        valueField: 'KorisnikID',
                                        displayField: 'ImePrezime',
                                        width: 400
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'datefield',
                                        fieldLabel:Lang.MediaPlan_reports_communicationReport_DateFrom,
                                        name:'datumOD',
                                        format:'Y-m-d',
                                        width:300
                                },{
                                        xtype:'datefield',
                                        labelAlign:'right',
                                        labelWidth:300,
                                        fieldLabel:Lang.MediaPlan_reports_communicationReport_DateTo,
                                        name:'datumDO',
                                        format:'Y-m-d',
                                        width:500                                        
                                }]
                            },{
                                html:'<br><br>',
                                border:false
                            }]
            },{
                    xtype: 'box',
                    id:'reportsCommunicationsIframe',
                    width:'100%',
                    height:Ext.getCmp('reportsPanelCenterPanel').getHeight() - 300,
                    border:false,
                    autoEl: {
                        tag: 'iframe'
                    }
            }];
            this.buttons = [{
                xtype:'button',
                text:Lang.MediaPlan_reports_btn_CreateReportHTML,
                iconCls: 'magnifier',
                handler:function(){
                    this.ownerCt.ownerCt.createReportHTML();
                }
            },{
                xtype:'button',
                text:Lang.MediaPlan_reports_btn_CreateReportPDF,
                iconCls: 'magnifier',
                handler:function(){
                    this.ownerCt.ownerCt.createReportPDF();
                }
            },{
                xtype:'button',
                text:Lang.MediaPlan_reports_btn_ResetFields,
                iconCls:'refresh',
                handler:function(){
                    this.ownerCt.ownerCt.clearFilter();
                }
            }];
            this.callParent(arguments);
        }, //eo intitcomponent
        
        createReportPDF:function(){
            
                var form = this.getForm();
                var formAction = 'report-communication';
                var fieldValues = form.getValues();
                var fieldValuesEncoded = Ext.encode(fieldValues);
                window.open("../App/Controllers/IzvestajiPdf.php?action="+formAction+"&fieldValues="+fieldValuesEncoded, "_newwindow", "width=400,height=200,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
        },
        
        createReportHTML:function(){
            
                var form = this.getForm();

                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('reportsPanel').getEl(), Lang.Loading);
                    waitBox.show();
                    var formAction = 'report-communication';

                    var fieldValues = form.getValues();

                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/IzvestajiHtml.php',
                        params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                        success: function (response, request) {

                            if (Common.IsAjaxResponseSuccessfull(response)) {
                                waitBox.hide();
                                var data = Ext.decode(response.responseText).data;
                                Ext.get('reportsCommunicationsIframe').dom.contentWindow.document.body.innerHTML = data[0].html;
                            }
                            else {
                                waitBox.hide();
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
        },
        
        getFilterValues: function(){
            return this.getForm().getValues();
        },
        
        getJsonFilterValues: function () {
            var filterValues = this.getForm().getValues();
            return Ext.encode(filterValues);
        },
        
        clearFilter: function(){
            this.getForm().reset();
            this.reloadGrid();
        }
        
});












