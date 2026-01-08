Ext.define('Ext.ux.grid.CheckBoxList', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.checkboxlist',
        //custom properties
        storeUrl: '',
        storeAction: '',
        storeParams: [],
        storeAutoLoad: false,
        groupingEnabled: false,
        selectAll: false,
        selectedItems: [],
        textHeader: 'EntryName',
        textWidth: 100,
        groupHeader: 'Group',
        groupWidth: 100,
        listHeight: 200,
        listStyle: '',
        showCheckAll: true,
        checkColumnHeader: '',

        //selectItems function support
        valuesToBeSelectedNo: -1,
        selectingItemsFinishedCallbackFn: null,

        //base class properties
        id: 'commonCheckboxList',
        autoScroll: true,
	initComponent: function ()
	{
                this.style = this.listStyle;
                this.height = this.listHeight;

                this.addEvents('itemSelected');
                this.addEvents('itemDeselected');
                this.addEvents('selectingItemsFinished');
                
                
                var s = Ext.create('Ext.data.Store',{
                           fields: ['EntryName', 'EntryID', 'Group'],
                            proxy: {
                                    type: 'ajax',
                                    url: this.storeUrl,
                                    actionMethods: {
                                            read: 'POST'
                                    },
                                    extraParams: {
                                            action: this.storeAction
                                    },
                                    reader: {
                                            type: 'json',
                                            root: 'rows'
                                    }
                            }
                });
                /*var s = new Ext.data.GroupingStore({
                    autoDestroy: true,
                    url: this.storeUrl,
                    baseParams: { action: this.storeAction },
                    id: 'EntryID',
                    //groupField: 'Group',
                    reader: new Ext.data.JsonReader({
                        root: 'rows',
                        fields: ['EntryName', 'EntryID', 'Group']
                    }),
                    listeners: {
                        load: function (store, records, options) {
                            if (options.params.groupingEnabled) { store.groupBy('Group'); }
                            else { store.clearGrouping(); }

                            //                    if (this.parent.selectAll) {
                            //                        Common.Delay(500);
                            //                        this.parent.selectAllItems(this.parent);
                            //                    }
                        }
                    }
                });*/
                this.store = s;
                this.store.parent = this;

                var selModelConfig = {
                    checkOnly: true
                };

                if (!Ext.isEmpty(this.checkColumnHeader)) {
                    this.showCheckAll = false;
                    selModelConfig.header = this.checkColumnHeader;
                }

                var selectionModel = new Ext.selection.CheckboxModel(selModelConfig);
                selectionModel.on('select',
                    function (sModel, rowIndex, record) {
                        this.grid.fireEvent('itemSelected', record);

                        if (this.grid.valuesToBeSelectedNo > 0) {
                            this.grid.valuesToBeSelectedNo--;
                        }

                        if (this.grid.valuesToBeSelectedNo === 0) {
                            this.grid.valuesToBeSelectedNo = -1;

                            this.grid.fireEvent('selectingItemsFinished');

                            if (!Ext.isEmpty(this.grid.selectingItemsFinishedCallbackFn)) {
                                this.grid.selectingItemsFinishedCallbackFn();
                            }
                        }
                    }
                );
                selectionModel.on('deselect',
                    function (sModel, rowIndex, record) {
                        this.grid.fireEvent('itemDeselected', record);
                    }
                );
                this.sm = selectionModel;

                var colArray = [
                    selectionModel,
                    {
                        dataIndex: 'EntryName',
                        header: this.textHeader,
                        width: this.textWidth,
                        sortable: true
                    },
                    {
                        dataIndex: 'Group',
                        header: this.groupHeader,
                        width: this.groupWidth,
                        sortable: true
                    }
                ];
                if (!this.groupingEnabled) { colArray.pop(); }
                this.columns = colArray;

                /*var v = new Ext.grid.GroupingView({
                    forceFit: true,
                    groupTextTpl: '{group} ({[values.rs.length]})',
                    hideGroupedColumn: true
                });
                this.view = v;*/

                //Common.CheckboxList.superclass.initComponent.call(this);

                if (this.storeAutoLoad) {
                    //            if (this.selectAll) {
                    this.loadStore(this.selectItemsAfterLoad, this);
                    //            } else {
                    //                this.loadStore();
                    //            }
                } 
                
                this.callParent(arguments);   
        },
        /*enableGrouping: function () {
            this.groupingEnabled = true;
        },

        disableGrouping: function () {
            this.groupingEnabled = false;
        },*/

        setStoreParams: function (storeParams) {
            this.storeParams = storeParams;
        },

        loadStore: function (callbackFn, callbackArgs) {
            //this.getStore().proxy.extraParams = this.getStore().proxy.extraParams || {};
            this.store.proxy.extraParams = this.store.proxy.extraParams || {};
            var p = { groupingEnabled: this.groupingEnabled };
            if (this.storeParams.length > 0) {
                for (var i = 0; i < this.storeParams.length; i++) {
                    var f = this.storeParams[i];
                    if (!Ext.isEmpty(f.name)) {
                        if (!Ext.isEmpty(f.paramValue)) {
                            p[f.name] = f.paramValue;
                        }
                        else if (!Ext.isEmpty(f.field)) {
                            p[f.name] = f.field.getValue();
                        }
                        else { p[f.name] = ''; }
                    }
                }
            }

            this.getStore().load({
                params: p,
                callback: function (records, options, success) {
                    if (success) {
                        //fire the callback function after the store has been loaded
                        if (!Ext.isEmpty(callbackFn)) { callbackFn(callbackArgs); }
                    }
                }
            });
        },

        getStoreBaseParams: function () {
            return this.store.proxy.extraParams;
        },

        getSelectedRecords: function () {
            return this.getSelectionModel().getSelections();
        },

        getSelectedValues: function () {
            var records = this.getSelectionModel().getSelections();

            var selectedValues = [];
            Ext.each(
                records,
                function (item, index, allItems) { selectedValues.push(item.data.EntryID); },
                this
            );
            return selectedValues;
        },

        selectItems: function (valuesArray, callbackFn) {
            if (!Ext.isEmpty(callbackFn)) {
                this.selectingItemsFinishedCallbackFn = callbackFn;
            }

            this.valuesToBeSelectedNo = valuesArray.length;

            if (!Ext.isEmpty(valuesArray)) {
                var records = this.getStore().data;

                var recArray = [];
                Ext.each(
                    records.items,
                    function (item, index, allItems) {
                        if (valuesArray.contains(parseInt(item.data.EntryID))) { recArray.push(item); }
                    },
                    this
                );
                this.getSelectionModel().selectRecords(recArray);
            }
        },

        selectItemsAfterLoad: function (target) {
            if (target.selectAll) {
                target.selectAllItems(target);
            }
            else {
                target.selectSpecifiedItems(target, target.selectedItems);
            }
        },


        selectSpecifiedItems: function (target, valuesArray) {
            if (valuesArray == null) return;

            var records = target.getStore().data;

            var recArray = [];
            Ext.each(
                    records.items,
                    function (item, index, allItems) {
                        if (valuesArray.contains(item.data.EntryID)) { recArray.push(item); }
                    },
                    target
                );
            target.getSelectionModel().selectRecords(recArray);
        },

        selectAllItems: function (target) {

            var records = target.getStore().data;

            var recArray = [];
            Ext.each(
                    records.items,
                    function (item, index, allItems) {
                        { recArray.push(item); }
                    },
                    target
                );
            target.getSelectionModel().selectRecords(recArray);
        },

        deselectAllItems: function (target) {
            target.getSelectionModel().selectRecords([]);
        },


        deselectItem: function (index) {
            this.getSelectionModel().deselectRow(index);
        },

        anyItemSelected: function (fieldName, fieldValue) {
            var selectedRecords = this.getSelectionModel().getSelections();
            var selected = false;
            for (var i = 0; i < selectedRecords.length; i++) {
                selected = (
                    eval('selectedRecords[i].data.' + fieldName).toLowerCase() === fieldValue.toLowerCase()
                );
                if (selected) break;
            }
            return selected;
        }
})
