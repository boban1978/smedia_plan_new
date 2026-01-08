var MediaPlan = {};


MediaPlan.calendarsStore =  Ext.create('Extensible.calendar.data.MemoryCalendarStore', {
            // defined in ../data/Calendars.js
            data: Ext.create('Extensible.example.calendar.data.Calendars')
});


MediaPlan.eventStore = Ext.create('Extensible.calendar.data.EventStore', {
    //autoLoad: true,
    proxy: {
    type: 'ajax',
    url: '../App/Controllers/MediaPlan.php',
    actionMethods: {
            read: 'POST'
    },
    extraParams: {
            action: 'MediaPlanLoad',
			radioStanicaID:''
    },
    reader: new Ext.data.JsonReader({
        totalProperty: 'total',
        successProperty: 'success',
        type: 'json',
        root: 'evts'
    })

    },
	listeners:{
	  beforeload : {
		fn: function(store,records,successful,eOpts){
			var station = Ext.getCmp('calendarRadioStation').getValue();
			if(station !== null) {
				store.proxy.extraParams.radioStanicaID = station;
			} else {
				Ext.Msg.show({title : Lang.Message_Title,msg : 'Morate izabrati radio stanicu',buttons : Ext.Msg.OK,icon : Ext.MessageBox.INFO});
			}
		}
	  } 
	}
});



MediaPlan.updateTitle = function(startDt, endDt){
    var p = Ext.getCmp('app-center'),
        fmt = Ext.Date.format;

    if(Ext.Date.clearTime(startDt).getTime() == Ext.Date.clearTime(endDt).getTime()){
        p.setTitle(fmt(startDt, 'F j, Y'));
    }
    else if(startDt.getFullYear() == endDt.getFullYear()){
        if(startDt.getMonth() == endDt.getMonth()){
            p.setTitle(fmt(startDt, 'F j') + ' - ' + fmt(endDt, 'j, Y'));
        }
        else{
            p.setTitle(fmt(startDt, 'F j') + ' - ' + fmt(endDt, 'F j, Y'));
        }
    }
    else{
        p.setTitle(fmt(startDt, 'F j, Y') + ' - ' + fmt(endDt, 'F j, Y'));
    }
};

Ext.define('Extensible.calendar.view.DayHeader', {
    extend: 'Extensible.calendar.view.Month',
    alias: 'widget.extensible.dayheaderview',
    
    requires: [
        'Extensible.calendar.template.DayHeader'
    ],
    
    // private configs
    weekCount: 1,
    dayCount: 1,
    allDayOnly: true,
    monitorResize: false,
    isHeaderView: true,
    
    // The event is declared in MonthView but we're just overriding the docs:
    /**
     * @event dayclick
     * Fires after the user clicks within the view container and not on an event element. This is a cancelable event, so 
     * returning false from a handler will cancel the click without displaying the event editor view. This could be useful 
     * for validating that a user can only create events on certain days.
     * @param {Extensible.calendar.view.DayHeader} this
     * @param {Date} dt The date/time that was clicked on
     * @param {Boolean} allday True if the day clicked on represents an all-day box, else false. Clicks within the 
     * DayHeaderView always return true for this param.
     * @param {Ext.Element} el The Element that was clicked on
     */
    
    // private
    afterRender : function(){
        if(!this.tpl){
            this.tpl = Ext.create('Extensible.calendar.template.DayHeader', {
                id: this.id,
                showTodayText: this.showTodayText,
                todayText: this.todayText,
                showTime: this.showTime
            });
        }
        this.tpl.compile();
        this.addCls('ext-cal-day-header');
        
        this.callParent(arguments);
    },
    
    // private
    forceSize: Ext.emptyFn,
    
    // private
    refresh : function(reloadData){
        Extensible.log('refresh (DayHeaderView)');
        this.callParent(arguments);
        this.recalcHeaderBox();
    },
    
    // private
    recalcHeaderBox : function(){ //Branko promenio
        /*var tbl = this.el.down('.ext-cal-evt-tbl'),
            h = tbl.getHeight();
        
        this.el.setHeight(h+7);
        
        // These should be auto-height, but since that does not work reliably
        // across browser / doc type, we have to size them manually
        this.el.down('.ext-cal-hd-ad-inner').setHeight(h+5);
        this.el.down('.ext-cal-bg-tbl').setHeight(h+5);*/
    },
    
    // private
    moveNext : function(){
        return this.moveDays(this.dayCount);
    },

    // private
    movePrev : function(){
        return this.moveDays(-this.dayCount);
    }
    

});



Ext.ns('Extensible.calendar.data');

Extensible.calendar.data.EventMappings = {
    EventId: {
        name:    'EventId',
        mapping: 'id',
        type:    'int'
    },
    CalendarId: {
        name:    'CalendarId',
        mapping: 'cid',
        type:    'int'
    },
    Title: {
        name:    'Title',
        mapping: 'title',
        type:    'string'
    },
    StartDate: {
        name:       'StartDate',
        mapping:    'start',
        type:       'date',
        dateFormat: 'c'
    },
    EndDate: {
        name:       'EndDate',
        mapping:    'end',
        type:       'date',
        dateFormat: 'c'
    },
    BlockId: { // not currently used
        name:    'BlockId', 
        mapping: 'blokID', 
        type:    'int' 
    },
    RRule: { // not currently used
        name:    'RecurRule', 
        mapping: 'rrule', 
        type:    'string' 
    },
    Location: {
        name:    'Location',
        mapping: 'loc',
        type:    'string'
    },
    Notes: {
        name:    'Notes',
        mapping: 'notes',
        type:    'string'
    },
    Url: {
        name:    'Url',
        mapping: 'url',
        type:    'string'
    },
    IsAllDay: {
        name:    'IsAllDay',
        mapping: 'ad',
        type:    'boolean'
    },
    Reminder: {
        name:    'Reminder',
        mapping: 'rem',
        type:    'string'
    }
};

Ext.define('Extensible.calendar.form.EventWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.extensible.eventeditwindow',
    
    requires: [
        'Ext.form.Panel',
        'Extensible.calendar.data.EventModel',
        'Extensible.calendar.data.EventMappings'
    ],
    
    // Locale configs
    titleTextAdd: 'Add Event',
    titleTextEdit: 'Edit Event',
    width: 100,
    labelWidth: 65,
    detailsLinkText: 'Edit Details...',
    savingMessage: 'Saving changes...',
    deletingMessage: 'Deleting event...',
    saveButtonText: 'Save',
    deleteButtonText: 'Delete',
    cancelButtonText: 'Cancel',
    titleLabelText: 'Title',
    datesLabelText: 'When',
    calendarLabelText: 'Calendar',
    
    // General configs
    closeAction: 'hide',
    modal: false,
    resizable: false,
    constrain: true,
    buttonAlign: 'left',
    editDetailsLinkClass: 'edit-dtl-link',
    enableEditDetails: true,
    layout: 'fit',
    
    formPanelConfig: {
        border: false
    },
    
    // private
    initComponent: function(){
        
        
        this.callParent(arguments);
    },
    
    show:function(o,a) {
        
    }
});
