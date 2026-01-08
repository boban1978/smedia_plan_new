Ext.define('Ext.ux.grid.CheckBoxList', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.checkboxlist',
        //custom properties
        storeUrl: '',
        storeAction: '',
        storeParams: [],
        storeAutoLoad: false,
        selectAll: false,
        selectedItems: [],
        textHeader: 'EntryName',
        textWidth: 100,
        groupWidth: 100,
        listHeight: 200,
        listStyle: '',
        showCheckAll: true,

        //selectItems function support
        valuesToBeSelectedNo: -1,
        selectingItemsFinishedCallbackFn: null,

        //base class properties
        id: 'checkboxList',
        autoScroll: true,
	initComponent: function ()
	{
               this.style = this.listStyle;
               this.height = this.listHeight;
               
               this.addEvents('itemSelected');
               this.addEvents('itemDeselected');
               this.addEvents('selectingItemsFinished');
               
               var s = Ext.create('Ext.data.Store',{
                   fields: ['EntryID', 'EntryName'],
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
                
                this.store = s;
                this.store.parent = this;
      
                
                var  sm = Ext.create('Ext.selection.CheckboxModel',{
                    checkOnly: true
                });
                
                this.selModel = sm;
                var grid = this;
                
                sm.on('select',
                    function (sModel, rowIndex, record) {
                        
                        grid.fireEvent('itemSelected', record);

                        if (grid.valuesToBeSelectedNo > 0) {
                            grid.valuesToBeSelectedNo--;
                        }

                        if (grid.valuesToBeSelectedNo === 0) {
                            grid.valuesToBeSelectedNo = -1;

                            grid.fireEvent('selectingItemsFinished');

                            if (!Ext.isEmpty(grid.selectingItemsFinishedCallbackFn)) {
                                grid.selectingItemsFinishedCallbackFn();
                            }
                        }
                    }
                );
                    
                sm.on('deselect',
                    function (sModel, rowIndex, record) {
                        grid.fireEvent('itemDeselected', record);
                    }
                );
                
                
                
                this.columns = [
                    {
                        dataIndex: 'EntryName',
                        header: this.textHeader,
                        flex:1,
                        //width: this.textWidth,
                        sortable: true
                    }
                ];
                
                if (this.storeAutoLoad) {
                    this.store.autoLoad = true;
                    if (this.selectAll) {
                        this.loadStore(this.selectItemsAfterLoad, this);
                    } else {
                        this.loadStore();
                    }
                }
                
                this.callParent(arguments);
        },
        
        setStoreParams: function (storeParams) {
            this.storeParams = storeParams;
        },
        
        loadStore: function (callbackFn, callbackArgs) {
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

            this.store.load({
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
            var records = this.getSelectionModel().getSelection();

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

                //var startArray = [1,2,3,4,23]; //Pocetni niz(ono sto stigne sa srednjeg sloja)
                var startArray = eval(valuesArray); //Pretvaranje string koji stize sa srednjeg sloja u niz
                var startLength = startArray.length;
                var storeCount = this.getStore().count()

                for (var i=0; i < storeCount; i++) {
                    var entryID = this.getStore().getAt(i).data.EntryID;
                    for (var j=0; j < startLength; j++){
                        var item = startArray[j];
                        if (item == entryID) {
                            var itemToSelect = this.store.indexOf(this.getStore().getAt(i))
                            this.getSelectionModel().select(parseInt(itemToSelect),true);
                        }
                    }
                    
                };
                
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
            target.getSelectionModel().select(recArray);
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
            target.getSelectionModel().select(recArray);
        },

        deselectAllItems: function () {
            this.getSelectionModel().select([]);
        },


        deselectItem: function (index) {
            this.getSelectionModel().deselect(index);
        },

        anyItemSelected: function (fieldName, fieldValue) {
            var selectedRecords = this.getSelectionModel().getSelection();
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


