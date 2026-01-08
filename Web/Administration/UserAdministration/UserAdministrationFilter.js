Ext.define('Mediaplan.administration.userAdministration.Filter', {
	extend: 'Ext.form.Panel',
	alias: 'widget.useradministrationfilter',
        border:false,
        frame:false,
	width:'100%',
        autoRef:'useradminfilter',
	initComponent: function ()
	{
            this.id = 'userAdministrationFilter';
            this.items = [{
                            xtype:'fieldset',
                            title:Lang.Administration_userAdministration_filter_Title,
                            border:true,
                            collapsible: true,
                            labelWidth:150,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'textfield',
                                        fieldLabel:Lang.Administration_userAdministration_filter_Username,
                                        width:300,
                                        name:'korisnickoImeFilter'
                                },{
                                        xtype:'textfield',
                                        labelWidth:200,
                                        labelAlign:'right',
                                        fieldLabel:Lang.Administration_userAdministration_filter_Name,
                                        width:500,
                                        name:'imePrezimeFilter'
                                }]     
                            },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                    xtype:'radiogroup',
                                    fieldLabel:Lang.Administration_userAdministration_filter_Activ,
                                    columns: 2,
                                    width:300,
                                    vertical: true,
                                    items:[{
                                            boxLabel: Lang.Common_Yes,
                                            name: 'aktivanFilter',
                                            inputValue: 'true'
                                    },{
                                            boxLabel: Lang.Common_No,
                                            name: 'aktivanFilter',
                                            inputValue: 'false'                                            
                                    }]
                                },{
                                        xtype: 'combobox',
                    			fieldLabel:Lang.Administration_userAdministration_filter_Client,
                                        labelWidth:200,
                                        name:'klijentFilterID',
                                        labelAlign:'right',
                    			store: new Ext.data.Store({
                    				fields: ['EntryID', 'EntryName'],
                    				proxy: {
                    					type: 'ajax',
                    					url: 'AccountingHandler.ashx',
                    					actionMethods: {
                    						read: 'POST'
                    					},
                    					extraParams: {
                    						action: 'get-klijent-combo'
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

                    Ext.getCmp('userAdministrationGrid').getStore().load(
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
            Ext.getCmp('userAdministrationGrid').getStore().load();
        }
        
});

