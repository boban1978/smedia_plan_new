Ext.define('Mediaplan.mediaPlan.clients.Filter', {
	extend: 'Ext.form.Panel',
	alias: 'widget.mediaplanclientsfilter',
        border:true,
        frame:true,
        //layout: 'fit',
        autoScroll:true,
        title:Lang.MediaPlan_clients_filter_Title,
        iconCls:'magnifier',
        padding:10,
	width:'100%',
	initComponent: function ()
	{





            this.id = 'clientsFilter';
            this.items = [{
                    xtype:'fieldset',
                    border:true,
                    padding:'10 20 20 30',
                    collapsible:true,
                    title:Lang.MediaPlan_clients_filter_fldSet_GeneralData,
                    items:[{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_clients_filter_Name,
                        width:200,
                        name:'naziv'
                    },{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_clients_filter_Address,
                        width:200,
                        name:'adresa'
                    },{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_clients_filter_Country,
                        width:200,
                        name:'drzava'
                    },{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:'Pib',
                        width:200,
                        name:'pib'
                    },{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:'Maticni broj',
                        width:200,
                        name:'maticniBroj'
                    }]
            },{
                xtype:'fieldset',
                labelAlign:'top',
                border:true,
                padding:'10 20 20 30',
                collapsible:true,
                title:Lang.MediaPlan_clients_filter_fldSet_Contacts,
                items:[{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_clients_filter_NameSurname,
                        width:200,
                        name:'kontaktIme' 
                },{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_clients_filter_Email,
                        width:200,
                        name:'kontaktEmail'
                }]
            },{
                xtype:'fieldset',
                labelAlign:'top',
                border:true,
                padding:'10 20 20 30',
                collapsible:true,
                title:Lang.MediaPlan_clients_filter_fldSet_OtherData,
                items:[{
                        xtype: 'combobox',
                        fieldLabel:Lang.MediaPlan_clients_filter_ContractType,
                        labelAlign:'top',
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
                        width: 200
                },{
                        xtype: 'combobox',
                        fieldLabel:Lang.MediaPlan_clients_filter_ActiityType,
                        labelAlign:'top',
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
                        width: 200
                },{
                        xtype: 'checkboxlist',
                        id:'cblClientsAgencyList',
                        padding:'20 0 0 0',
                        fieldLabel:'Agencije',
                        storeUrl: '../App/Controllers/Agencija.php',
                        storeAction: 'AgencijaGetForComboBox',
                        storeAutoLoad: true,
                        textHeader: Lang.MediaPlan_clients_filter_Agency,
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
                    filterValues.Agencije = Ext.getCmp('cblClientsAgencyList').getSelectedValues();



                    //alert(filterValues.Agencije);



                    Ext.getCmp('clientsGrid').getStore().load(
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
            filterValues.Agencije = Ext.getCmp('cblClientsAgencyList').getSelectedValues(); 
            return Ext.encode(filterValues);
        },
        
        clearFilter: function(){
            this.getForm().reset();
            Ext.getCmp('cblClientsAgencyList').deselectAllItems();
            this.reloadGrid();
        },
        
        reloadGrid: function(){
            Ext.getCmp('clientsGrid').getStore().load();
        }
        
});



