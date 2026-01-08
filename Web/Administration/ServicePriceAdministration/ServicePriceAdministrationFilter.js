Ext.define('Mediaplan.administration.servicePriceAdministration.Filter', {
	extend: 'Ext.form.Panel',
	alias: 'widget.servicepriceadministrationfilter',
    border:false,
    frame:false,
	width:'100%',
	initComponent: function ()
	{
            this.id = 'servicePriceAdministrationFilter';
            this.items = [{
                            xtype:'fieldset',
                            title:Lang.Administration_servicePriceAdministration_filter_Title,
                            border:true,
                            collapsible: true,
                            labelWidth:150,
                            items:[{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                    		labelAlign: 'left',
                    		items: [{
                                        xtype:'textfield',
                                        fieldLabel:Lang.Administration_servicePriceAdministration_filter_Name,
                                        name:'naziv',
                                        width:300
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

                    Ext.getCmp('servicePriceAdministrationGrid').getStore().load(
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
            Ext.getCmp('servicePriceAdministrationGrid').getStore().load();
        }
        
});






