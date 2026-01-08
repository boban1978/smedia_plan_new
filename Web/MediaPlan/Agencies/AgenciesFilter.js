Ext.define('Mediaplan.mediaPlan.agencies.Filter', {
	extend: 'Ext.form.Panel',
	alias: 'widget.mediaplanagenciesfilter',
        border:true,
        frame:true,
        //layout: 'fit',
        autoScroll:true,
        title:Lang.MediaPlan_agencies_filter_Title,
        iconCls:'magnifier',
        padding:10,
	width:'100%',
	initComponent: function ()
	{
            this.id = 'agenciesFilter';
            this.items = [{
                    xtype:'fieldset',
                    border:true,
                    padding:'10 20 20 30',
                    collapsible:true,
                    title:Lang.MediaPlan_agencies_filter_fldSet_GeneralData,
                    items:[{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_agencies_filter_Name,
                        width:200,
                        name:'naziv'
                    },{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_agencies_filter_Address,
                        width:200,
                        name:'adresa'
                    },{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_agencies_filter_Country,
                        width:200,
                        name:'drzava'
                    }]
            },{
                xtype:'fieldset',
                labelAlign:'top',
                border:true,
                padding:'10 20 20 30',
                collapsible:true,
                title:Lang.MediaPlan_agencies_filter_fldSet_OtherData,
                items:[{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_agencies_filter_Contact,
                        width:200,
                        name:'kontakt'   
                },{
                        xtype:'textfield',
                        labelAlign:'top',
                        fieldLabel:Lang.MediaPlan_agencies_filter_Email,
                        width:200,
                        name:'email' 
                },{
                        xtype: 'combobox',
                        fieldLabel:Lang.MediaPlan_agencies_filter_Clients,
                        labelAlign:'top',
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
                    Ext.getCmp('agenciesGrid').getStore().load(
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
            return Ext.encode(filterValues);
        },
        
        clearFilter: function(){
            this.getForm().reset();
            this.reloadGrid();
        },
        
        reloadGrid: function(){
            Ext.getCmp('agenciesGrid').getStore().load();
        }
        
});





