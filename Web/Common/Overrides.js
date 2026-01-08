Ext.override(Ext.data.Store, {

    // Handle prefetch when all the data is there and add purging
    prefetchPage: function (page, options, forceLoad) {

        var me = this,
            pageSize = me.pageSize || 25,
            start = (page - 1) * me.pageSize,
            end = start + pageSize;

        // A good time to remove records greater than cache
        me.purgeRecords();

        // No more data to prefetch
        if (me.getCount() === me.getTotalCount() && !forceLoad) {
            return;
        }

        // Currently not requesting this page and range isn't already satisified
        if (Ext.Array.indexOf(me.pagesRequested, page) === -1 && !me.rangeSatisfied(start, end)) {
            me.pagesRequested.push(page);

            // Copy options into a new object so as not to mutate passed in objects
            options = Ext.apply({
                page: page,
                start: start,
                limit: pageSize,
                callback: me.onWaitForGuarantee,
                scope: me
            }, options);
            me.prefetch(options);
        }
    },

    // Fixes too big guaranteedEnd and forces load even if all data is there
    doSort: function () {
        var me = this;
        if (me.buffered) {
            count = me.getCount();
            me.prefetchData.clear();
            me.prefetchPage(1, {
                callback: function (records, operation, success) {
                    if (success) {
                        me.guaranteedStart = 0;
                        me.guaranteedEnd = 99; // should be more dynamic
                        me.loadRecords(Ext.Array.slice(records, 0, count));
                        me.unmask();
                    }
                }
            }, true);
            me.mask();
        }
    }
});

Ext.override(Ext.ux.grid.FiltersFeature, {

    onBeforeLoad: Ext.emptyFn,

    // Appends the filter params, fixes too big guaranteedEnd and forces load even if all data is there
    reload: function () {
        var me = this,
            grid = me.getGridPanel(),
            filters = grid.filters.getFilterData(),
            store = me.view.getStore(),
            proxy = store.getProxy(),
            count = store.getCount();

        store.prefetchData.clear();
        proxy.extraParams = this.buildQuery(filters);
        store.prefetchPage(1, {
            callback: function (records, operation, success) {
                if (success) {
                    store.guaranteedStart = 0;
                    store.guaranteedEnd = 99; // should be more dynamic
                    store.loadRecords(Ext.Array.slice(records, 0, count));
                    store.unmask();
                }
            }
        }, true);
        store.mask();
    }
});

