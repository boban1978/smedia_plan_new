Ext.define('Mediaplan.administration.stationProgram.Filter', {
    extend: 'Ext.form.Panel',
    alias: 'widget.stationprogramfilter',
    border: false,
    frame: false,
    width: '100%',
    initComponent: function()
    {
        this.id = 'stationProgramFilter';
        this.items = [{
                xtype: 'fieldset',
                title: Lang.Administration_stationProgram_filter_Title,
                border: true,
                collapsible: true,
                labelWidth: 150,
                items: [{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelAlign: 'left',
                        items: [{
                                xtype: 'combobox',
                                fieldLabel: Lang.Administration_stationProgram_filter_Station,
                                store: Ext.create('Ext.data.Store', {
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
                                name: 'radioStanicaID',
                                queryMode: 'remote',
                                typeAhead: true,
                                queryParam: 'filter',
                                emptyText: Lang.Common_combobox_emptyText,
                                valueField: 'EntryID',
                                displayField: 'EntryName',
                                width: 350
                            }]
                    }, {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [{
                                xtype: 'button',
                                text: Lang.Filter_Search,
                                iconCls: 'magnifier',
                                handler: function() {
                                    this.ownerCt.ownerCt.ownerCt.loadDataToGrid();
                                }
                            }, {
                                xtype: 'splitter',
                                width: 5
                            }, {
                                xtype: 'button',
                                text: Lang.Filter_Clear,
                                iconCls: 'refresh',
                                handler: function() {
                                    this.ownerCt.ownerCt.ownerCt.clearFilter();
                                }
                            }]
                    }]
            }]

        this.callParent(arguments);
    }, //eo intitcomponent

    loadDataToGrid: function() {

        var form = this.getForm();
        if (form.isValid()) {
            var filterValues = form.getValues();

            Ext.getCmp('stationProgramGrid').getStore().load(
                    {
                        params: {filterValues: Ext.encode(filterValues)}
                    });
        }
    },
    getFilterValues: function() {
        return this.getForm().getValues();
    },
    getJsonFilterValues: function() {
        var filterValues = this.getForm().getValues();
        return Ext.encode(filterValues);
    },
    clearFilter: function() {
        this.getForm().reset();
        this.reloadGrid();
    },
    reloadGrid: function() {
        Ext.getCmp('stationProgramGrid').getStore().load();
    }

});






