Ext.define('Mediaplan.administration.clientAdministration.Filter', {
	extend: 'Ext.form.Panel',
	alias: 'widget.clientadministrationfilter',
        border:false,
        frame:false,
	width:'100%',
        autoRef:'clientadminfilter',
	initComponent: function ()
	{
            this.id = 'clientAdministrationFilter';
                        this.items = [{
                            xtype:'fieldset',
                            title:Lang.Administration_clientAdministration_filter_Title,
                            border:true,
                            collapsible: true,
                            labelWidth:150,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'textfield',
                                        fieldLabel:Lang.Administration_clientAdministration_filter_Name,
                                        width:300,
                                        name:'naziv'
                                },{
                                        xtype: 'combobox',
                    			fieldLabel:Lang.Administration_clientAdministration_filter_Agency,
                                        labelWidth:200,
                                        name:'agencijaID',
                                        labelAlign:'right',
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
                    			width: 400
                                }]     
                            },{
                                    xtype:'radiogroup',
                                    fieldLabel:Lang.Administration_userAdministration_filter_Activ,
                                    columns: 2,
                                    width:250,
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

                    Ext.getCmp('clientAdministrationGrid').getStore().load(
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
            Ext.getCmp('clientAdministrationGrid').getStore().load();
        }
        
});





