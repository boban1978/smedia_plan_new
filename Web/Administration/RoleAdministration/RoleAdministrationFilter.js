Ext.define('Mediaplan.administration.roleAdministration.Filter', {
	extend: 'Ext.form.Panel',
	alias: 'widget.roleadministrationfilter',
        border:false,
        frame:false,
	width:'100%',
        autoRef:'roleadminfilter',
	initComponent: function ()
	{
            this.id = 'roleAdministrationFilter';
            this.items = [{
                            xtype:'fieldset',
                            title:Lang.Administration_roleAdministration_filter_Title,
                            border:true,
                            collapsible: true,
                            labelWidth:150,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'textfield',
                                        fieldLabel:Lang.Administration_roleAdministration_filter_Name,
                                        width:300,
                                        name:'rolaNazivFilter'
                                },{
                                        xtype: 'combobox',
                    			fieldLabel:Lang.Administration_roleAdministration_filter_Permission,
                                        labelWidth:200,
                                        name:'rolaPrivilegijaFilterID',
                                        labelAlign:'right',
                    			store: new Ext.data.Store({
                    				fields: ['EntryID', 'EntryName'],
                    				proxy: {
                    					type: 'ajax',
                    					url: '../App/Controllers/Rola.php',
                    					actionMethods: {
                    						read: 'POST'
                    					},
                    					extraParams: {
                    						action: 'PermisijaGetForComboBox'
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

                    Ext.getCmp('roleAdministrationGrid').getStore().load(
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
            Ext.getCmp('roleAdministrationGrid').getStore().load();
        }
        
});



