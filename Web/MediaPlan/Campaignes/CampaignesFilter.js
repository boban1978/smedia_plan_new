Ext.define('Mediaplan.mediaPlan.campaignes.Filter', {
	extend: 'Ext.form.Panel',
	alias: 'widget.mediaplancampaignesfilter',
        border:true,
        frame:true,
        //layout: 'fit',
        autoScroll:true,
        title:Lang.MediaPlan_campaignes_filter_Title,
        iconCls:'magnifier',
        padding:10,
	width:'100%',
	initComponent: function ()
	{
            this.id = 'campaignesFilter';
            this.items = [{
                    xtype:'fieldset',
                    border:true,
                    padding:'10 20 20 30',
                    collapsible:true,
                    title:Lang.MediaPlan_campaignes_filter_fldSet_GeneralData,
                    items:[{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_campaignes_filter_Name,
                        width:190,
                        name:'kampanjaNazivFilter'
                    },{
						xtype:'combobox',
						fieldLabel:Lang.MediaPlan_campaignes_filter_Station,
						labelAlign:'top',
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
						width: 190 
					},{
                        xtype:'datefield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_campaignes_filter_StartDate,
                        width:150,
                        format:'Y-m-d',
                        name:'datumPocetkaFilter'
                    },{
                        xtype:'datefield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_campaignes_filter_EndDate,
                        width:150,
                        format:'Y-m-d',
                        name:'datumKrajaFilter'
                    },{
                        xtype: 'combobox',
                        fieldLabel:Lang.MediaPlan_campaignes_filter_Status,
                        labelAlign:'top',
                        name:'statusFilterID',
                        store: new Ext.data.Store({
                                fields: ['EntryID', 'EntryName'],
                                proxy: {
                                        type: 'ajax',
                                        url: '../App/Controllers/StatusKampanja.php',
                                        actionMethods: {
                                                read: 'POST'
                                        },
                                        extraParams: {
                                                action: 'StatusKampanjaForComboBox'
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
                        width: 190
                },{
                        xtype: 'combobox',
                        fieldLabel:Lang.MediaPlan_campaignes_filter_Payment,
                        labelAlign:'top',
                        name:'nacinPlacanjaFilterID',
                        store: new Ext.data.Store({
                                fields: ['EntryID', 'EntryName'],
                                proxy: {
                                        type: 'ajax',
                                        url: '../App/Controllers/KampanjaNacinPlacanja.php',
                                        actionMethods: {
                                                read: 'POST'
                                        },
                                        extraParams: {
                                                action: 'KampanjaNacinPlacanjaForComboBox'
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
                        width: 190
                }]
            },{
                xtype:'fieldset',
                labelAlign:'top',
                border:true,
                padding:'10 20 20 30',
                collapsible:true,
                title:Lang.MediaPlan_campaignes_filter_fldSet_Clients,
                items:[{
                        xtype: 'checkboxlist',
                        id:'klijentListFilter',
                        storeUrl: '../App/Controllers/Klijent.php',
                        storeAction: 'KlijentGetForComboBox',
                        storeAutoLoad: true,
                        textHeader: Lang.MediaPlan_campaignes_filter_Client,
                        groupingEnabled: false,
                        listHeight: 150,
                        width: 200   
                }]
            },{
                xtype:'fieldset',
                labelAlign:'top',
                border:true,
                padding:'10 20 20 30',
                collapsible:true,
                title:Lang.MediaPlan_campaignes_filter_fldSet_Agencies,
                items:[{
                        xtype: 'checkboxlist',
                        id:'agencijaListFilter',
                        storeUrl: '../App/Controllers/Agencija.php',
                        storeAction: 'AgencijaGetForComboBox',
                        storeAutoLoad: true,
                        textHeader: Lang.MediaPlan_campaignes_filter_Agency,
                        groupingEnabled: false,
                        listHeight: 150,
                        width: 200   
                }]
            }],
            this.buttons = [{
                text:Lang.Filter_Search,
                iconCls: 'magnifier',
                handler:function(){
                    this.ownerCt.ownerCt.loadDataToGrid();
                } 
            },{
                text:Lang.Filter_Clear,
                iconCls:'refresh',
                handler:function(){
                    this.ownerCt.ownerCt.clearFilter();
                }
            }]
            this.callParent(arguments);
        }, //eo intitcomponent
        
        loadDataToGrid:function(){
            
                var form = this.getForm();
                if (form.isValid()) {
                    var filterValues = form.getValues();
                    filterValues.klijentListFilter = Ext.getCmp('klijentListFilter').getSelectedValues();
                    filterValues.agencijaListFilter = Ext.getCmp('agencijaListFilter').getSelectedValues();       
                    Ext.getCmp('campaignesGrid').getStore().load(
                    {
                        params: { filterValues: Ext.encode(filterValues) }
                    });
                }
        },
        
        getFilterValues: function(){
            return this.getForm().getValues();
        },
        
        getJsonFilterValues: function () {
            var filterValues = this.getForm().getValues();
            filterValues.klijentListFilter = Ext.getCmp('klijentListFilter').getSelectedValues();
            filterValues.agencijaListFilter = Ext.getCmp('agencijaListFilter').getSelectedValues(); 
            return Ext.encode(filterValues);
        },
        
        clearFilter: function(){
            this.getForm().reset();
            Ext.getCmp('klijentListFilter').deselectAllItems();
            Ext.getCmp('agencijaListFilter').deselectAllItems();
            this.reloadGrid();
        },
        
        reloadGrid: function(){
            Ext.getCmp('campaignesGrid').getStore().load();
        }
        
});




