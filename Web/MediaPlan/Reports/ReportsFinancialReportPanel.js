Ext.define('Mediaplan.mediaPlan.reports.financialReport.Panel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.reportfinancialpanel',
        border:false,
        frame:false,
	width:'100%',
	initComponent: function ()
	{
            this.id = 'reportsFinancialReportPanel';
            this.items = [{
                            xtype:'fieldset',
                            title:Lang.MediaPlan_reports_financialReport_Title,
                            border:true,
                            labelWidth:200,
                            padding:'20',
                            height:170,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [
                                {
                                    xtype:'combobox',
                                    fieldLabel:Lang.MediaPlan_campaignes_filter_Station,
                                    store: Ext.create('Ext.data.Store',{
                                        fields: ['EntryID', 'EntryName'],
                                        proxy: {
                                            type: 'ajax',
                                            url: '../App/Controllers/RadioStanica.php',
                                            actionMethods: {
                                                read: 'POST'
                                            },
                                            extraParams: {
                                                action: 'RadioStanicaGetForComboBox'
                                            },
                                            reader: {
                                                type: 'json',
                                                root: 'rows'
                                            }
                                        }
                                    }),
                                    name:'radioStanicaID',
                                    queryMode: 'remote',
                                    typeAhead: true,
                                    queryParam: 'filter',
                                    emptyText: Lang.Common_combobox_emptyText,
                                    valueField: 'EntryID',
                                    displayField: 'EntryName',
                                    width: 400
                                },
                                {
                                    xtype: 'combobox',
                                    fieldLabel:Lang.MediaPlan_reports_financialReport_Agencies,
                                    labelWidth:200,
                                    labelAlign: 'right',
                                    name:'agencijaID',
                                    store: new Ext.data.Store({
                                        fields: ['EntryID', 'EntryName'],
                                        proxy: {
                                            type: 'ajax',
                                            url: '../App/Controllers/Agencija.php',
                                            actionMethods: {
                                                read: 'POST'
                                            },
                                            extraParams: {
                                                action: 'AgencijaGetForComboBox'
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
                    		items: [



                                {
                                    xtype: 'combobox',
                                    fieldLabel:Lang.MediaPlan_reports_financialReport_Clients,
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
                                },


                                /*{
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_ContractType,
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
                                },*/{
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_ActivityType,
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
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                labelAlign: 'left',
                                items: [



                                    {
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_offersReport_Sales,
                                        name:'komercijalistaID',
                                        store: new Ext.data.Store({
                                            //fields: ['EntryID', 'EntryName'],
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
                                    }]
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'datefield',
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_DateFrom,
                                        name:'datumOD',
                                        format:'Y-m-d',
                                        width:300
                                },{
                                        xtype:'datefield',
                                        labelAlign:'right',
                                        labelWidth:300,
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_DateTo,
                                        name:'datumDO',
                                        format:'Y-m-d',
                                        width:500                                        
                                }/*,{
                                        xtype:'checkboxfield',
                                        labelWidth:100,
                                        fieldLabel:' ',
                                        name:'komparativnaAnaliza',
                                        labelSeparator:'',
                                        inputValue:1,
                                        boxLabel:Lang.MediaPlan_reports_financialReport_ShowComparativAnalysis,
                                        listeners:{
                                            change:function(){




                                                if(this.getValue()) {
                                                    Ext.getCmp('reportsFinancialReportFormFldSet2').show();
                                                    Ext.getCmp('reportsFinancialIframe').setHeight(Ext.getCmp('reportsPanelCenterPanel').getHeight() - 460)
                                                } else {
                                                    Ext.getCmp('reportsFinancialReportFormFldSet2').hide(); 
                                                    Ext.getCmp('reportsFinancialIframe').setHeight(Ext.getCmp('reportsPanelCenterPanel').getHeight() - 260)
                                                }
                                            }
                                        }
                                }*/]
                            },{
                                html:'<br><br>',
                                border:false
                            }]
            },/*{
                            xtype:'fieldset',
                            title:Lang.MediaPlan_reports_financialReport_ComparativAnalysisTitle,
                            id:'reportsFinancialReportFormFldSet2',
                            hidden:true,
                            border:true,
                            labelWidth:200,
                            padding:'20',
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype: 'combobox',
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_Clients,
                                        name:'klijentIDUporedno',
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
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_Agencies,
                                        labelWidth:200,
                                        labelAlign: 'right',
                                        name:'agencijaIDUporedno',
                                        store: new Ext.data.Store({
                                                fields: ['EntryID', 'EntryName'],
                                                proxy: {
                                                        type: 'ajax',
                                                        url: '../App/Controllers/Agencija.php',
                                                        actionMethods: {
                                                                read: 'POST'
                                                        },
                                                        extraParams: {
                                                                action: 'AgencijaGetForComboBox'
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
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_ContractType,
                                        name:'tipUgovoraIDUporedno',
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
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_ActivityType,
                                        labelAlign:'right',
                                        labelWidth:200,
                                        name:'delatnostIDUporedno',
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
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'datefield',
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_DateFrom,
                                        name:'datumODUporedno',
                                        format:'Y-m-d',
                                        width:300
                                },{
                                        xtype:'datefield',
                                        labelAlign:'right',
                                        labelWidth:300,
                                        fieldLabel:Lang.MediaPlan_reports_financialReport_DateTo,
                                        name:'datumDOUporedno',
                                        format:'Y-m-d',
                                        width:500                                        
                                }]
                            },{
                                html:'<br><br>',
                                border:false
                            }]
            },*/{
                    xtype: 'box',
                    id:'reportsFinancialIframe',
                    width:'100%',
                    height:Ext.getCmp('reportsPanelCenterPanel').getHeight() - 260,
                    border:false,
                    autoEl: {
                        tag: 'iframe'
                    }
            }],
            this.buttons = [{
                xtype:'button',
                text:Lang.MediaPlan_reports_btn_CreateReportHTML,
                iconCls: 'magnifier',
                handler:function(){
                    this.ownerCt.ownerCt.createReportHTML();
                }
            },{
                xtype:'button',
                text:'Excel',
                iconCls: 'magnifier',
                handler:function(){
                    this.ownerCt.ownerCt.createReportExcel();
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


        createReportHTML:function(){

            var form = this.getForm();

            if (form.isValid()) {
                var waitBox = Common.loadingBox(Ext.getCmp('reportsPanel').getEl(), Lang.Loading);
                waitBox.show();


                var formAction = 'report-financial';

                var fieldValues = form.getValues();

                Ext.Ajax.request({
                    timeout: Common.Timeout,
                    url: '../App/Controllers/IzvestajiHtml.php',
                    params: { action: formAction, fieldValues: Ext.encode(fieldValues) },
                    success: function (response, request) {

                        if (Common.IsAjaxResponseSuccessfull(response)) {
                            waitBox.hide();
                            var data = Ext.decode(response.responseText).data;
                            Ext.get('reportsFinancialIframe').dom.contentWindow.document.body.innerHTML = data[0].html;
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

        createReportExcel:function(){

            var form = this.getForm();

            if (form.isValid()) {
                var waitBox = Common.loadingBox(Ext.getCmp('reportsPanel').getEl(), Lang.Loading);
                waitBox.show();

                var formAction = 'report-financial';
                var fieldValues = form.getValues();

                var download_token = Math.floor((Math.random() * 1000) + 1);
                var url = home_address+'App/Controllers/IzvestajiExcel.php?action='+ formAction+'&fieldValues='+ Ext.encode(fieldValues)+'&download_token='+download_token;
                var iframe = document.createElement('iframe');
                iframe.src = url;
                document.body.appendChild(iframe);

                var download_token_cookieValue=0;
                var xxx =setInterval(function(){
                    download_token_cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)download_token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                    if(download_token_cookieValue==download_token){
                        clearInterval(xxx);
                        waitBox.hide();
                    }
                }, 1000);

            }
        },


        createReportPDF:function(){

            var form = this.getForm();

            if (form.isValid()) {

                var waitBox = Common.loadingBox(Ext.getCmp('reportsPanel').getEl(), Lang.Loading);
                waitBox.show();

                var formAction = 'report-financial';
                var fieldValues = form.getValues();

                var download_token = Math.floor((Math.random() * 1000) + 1);
                var url = home_address + 'App/Controllers/IzvestajiPdf.php?action=' + formAction + '&fieldValues=' + Ext.encode(fieldValues)+'&download_token='+download_token;

                var iframe = document.createElement('iframe');
                iframe.src = url;
                document.body.appendChild(iframe);

                var download_token_cookieValue=0;
                var xxx =setInterval(function(){
                    download_token_cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)download_token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                    if(download_token_cookieValue==download_token){
                        clearInterval(xxx);
                        waitBox.hide();
                    }
                }, 1000);

            }

            /*
                var fieldValuesEncoded = Ext.encode(fieldValues);
                window.open("../App/Controllers/IzvestajiPdf.php?action="+formAction+"&fieldValues="+fieldValuesEncoded, "_newwindow", "width=400,height=200,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
        */
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
        }
        
});










