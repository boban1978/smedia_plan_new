Ext.define('Mediaplan.administration.campaigneTemplatesAdministration.Filter', {
	extend: 'Ext.form.Panel',
	alias: 'widget.campaignetemplatesfilter',
        border:false,
        frame:false,
	width:'100%',
	initComponent: function ()
	{
            this.id = 'campaigneTemplatesAdministrationFilter';
            this.items = [{
                            xtype:'fieldset',
                            title:Lang.Administration_campaigneTemplatesAdministration_filter_Title,
                            border:true,
                            collapsible: true,
                            labelWidth:150,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'textfield',
                                        fieldLabel:Lang.Administration_campaigneTemplatesAdministration_filter_Name,
                                        name:'naziv',
                                        width:300
                                },{
										xtype:'combobox',
										fieldLabel:Lang.MediaPlan_clients_details_campaignes_dialog_Station,
										labelAlign:'right',
										labelWidth: 100,
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
										width: 350 
								},{
                                        xtype:'radiogroup',
                                        fieldLabel:Lang.Administration_campaigneTemplatesAdministration_filter_Active,
                                        labelAlign:'right',
                                        columns: 2,
                                        width:200,
                                        vertical: true,
                                        items:[{
                                                boxLabel: Lang.Common_Yes,
                                                name: 'aktivan',
                                                inputValue: 'true'
                                        },{
                                                boxLabel: Lang.Common_No,
                                                name: 'aktivan',
                                                inputValue: 'false'                                            
                                        }]
                                }]     
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                                items:[{
                                    xtype:'button',
                                    text:Lang.Filter_Search,
                                    iconCls: 'magnifier',
                                    handler:function(){
                                        this.ownerCt.ownerCt.ownerCt.loadDataToGrid();
                                    }
                                },{
                                    xtype:'splitter',
                                    width:5
                                },{
                                    xtype:'button',
                                    text:Lang.Filter_Clear,
                                    iconCls:'refresh',
                                    handler:function(){
                                        this.ownerCt.ownerCt.ownerCt.clearFilter();
                                    }
                                }]
                            }]
            }] 
   
            this.callParent(arguments);
        }, //eo intitcomponent
        
        loadDataToGrid:function(){
            
                var form = this.getForm();
                if (form.isValid()) {
                    var filterValues = form.getValues();

                    Ext.getCmp('campaigneTemplatesAdministrationGrid').getStore().load(
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
            Ext.getCmp('campaigneTemplatesAdministrationGrid').getStore().load();
        }
        
});








