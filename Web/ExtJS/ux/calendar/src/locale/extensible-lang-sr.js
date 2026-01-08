/*!
 * Extensible 1.5.1
 * Copyright(c) 2010-2011 Extensible, LLC
 * licensing@ext.ensible.com
 * http://ext.ensible.com
 */
/*
 * Default Serbian (sr_SR) locale
 * By Branko Radjen
 */

Ext.onReady(function() {
    var exists = Ext.Function.bind(Ext.ClassManager.get, Ext.ClassManager);
    
    Extensible.Date.use24HourTime = true;
    
    if (exists('Extensible.calendar.view.AbstractCalendar')) {
        Ext.apply(Extensible.calendar.view.AbstractCalendar.prototype, {
            startDay: 1,
            todayText: 'Danas',
            defaultEventTitleText: '(Bez naziva)',
            ddCreateEventText: 'Novi dogadaj za {0}',
            ddMoveEventText: 'Premesti dogadaj za {0}',
            ddResizeEventText: 'Promeni vreme dogadaja na {0}'
        });
    }
    
    if (exists('Extensible.calendar.view.Month')) {
        Ext.apply(Extensible.calendar.view.Month.prototype, {
            moreText: '+{0} više...',
            getMoreText: function(numEvents){
                return '+{0} više...';
            },
            detailsTitleDateFormat: 'F d'
        });
    }
    
    if (exists('Extensible.calendar.CalendarPanel')) {
        Ext.apply(Extensible.calendar.CalendarPanel.prototype, {
            todayText: 'Danas',
            dayText: 'Dan',
            weekText: 'Nedelja',
            monthText: 'Mesec',
            jumpToText: 'Pokaži datum:',
            goText: 'Pokaži',
            multiDayText: '{0} dana',
            multiWeekText: '{0} nedelje',
            getMultiDayText: function(numDays){
                return '{0} dana';
            },
            getMultiWeekText: function(numWeeks){
                return '{0} nedelja';
            }
        });
    }
    
    if (exists('Extensible.calendar.form.EventWindow')) {
        Ext.apply(Extensible.calendar.form.EventWindow.prototype, {
            width: 660,
            labelWidth: 60,
            titleTextAdd: 'Novi unos',
            titleTextEdit: 'Izmeni unos',
            savingMessage: 'Snimanje promene...',
            deletingMessage: 'Brišem unos...',
            detailsLinkText: 'Izmena detalja unosa...',
            saveButtonText: 'Snimi',
            deleteButtonText: 'Obriši',
            cancelButtonText: 'Odustani',
            titleLabelText: 'Naziv',
            datesLabelText: 'Vreme',
            calendarLabelText: 'Kalendar'
        });
    }
    
    if (exists('Extensible.calendar.form.EventDetails')) {
        Ext.apply(Extensible.calendar.form.EventDetails.prototype, {
            labelWidth: 65,
            labelWidthRightCol: 90,
            title: 'Obrada unosa',
            titleTextAdd: 'Novi unos',
            titleTextEdit: 'Izmena unosa',
            saveButtonText: 'Snimi',
            deleteButtonText: 'Obriši',
            cancelButtonText: 'Odustani',
            titleLabelText: 'Naziv',
            datesLabelText: 'Vreme',
            reminderLabelText: 'Podsetnik',
            notesLabelText: 'Belške',
            locationLabelText: 'Lokacija',
            webLinkLabelText: 'Internet adresa',
            calendarLabelText: 'Kalendar',
            repeatsLabelText: 'Ponavljanje'
        });
    }
    
    if (exists('Extensible.form.field.DateRange')) {
        Ext.apply(Extensible.form.field.DateRange.prototype, {
            toText: 'do',
            allDayText: 'Celodnevni događaj'
        });
    }
    
    if (exists('Extensible.calendar.form.field.CalendarCombo')) {
        Ext.apply(Extensible.calendar.form.field.CalendarCombo.prototype, {
            fieldLabel: 'Kalendar'
        });
    }
    
    if (exists('Extensible.calendar.gadget.CalendarListPanel')) {
        Ext.apply(Extensible.calendar.gadget.CalendarListPanel.prototype, {
            title: 'Kalendari'
        });
    }
    
    if (exists('Extensible.calendar.gadget.CalendarListMenu')) {
        Ext.apply(Extensible.calendar.gadget.CalendarListMenu.prototype, {
            displayOnlyThisCalendarText: 'Prikaži samo ovaj kalendar'
        });
    }
    
    if (exists('Extensible.form.recurrence.Combo')) {
        Ext.apply(Extensible.form.recurrence.Combo.prototype, {
            fieldLabel: 'Ponavljanje',
            recurrenceText: {
                none: 'Ne ponavlja se',
                daily: 'Dnevno',
                weekly: 'Nedeljno',
                monthly: 'Mesečno',
                yearly: 'Godišnje'
            }
        });
    }
    
    if (exists('Extensible.calendar.form.field.ReminderCombo')) {
        Ext.apply(Extensible.calendar.form.field.ReminderCombo.prototype, {
            fieldLabel: 'Podsetnik',
            noneText: 'Nema',
            atStartTimeText: 'Na početku',
            getMinutesText: function(numMinutes){
                return 'minuta';
            },
            getHoursText: function(numHours){
                return numHours === 1 ? 'sat' : 'sati';
            },
            getDaysText: function(numDays){
                return numDays === 1 ? 'dan' : 'dana';
            },
            getWeeksText: function(numWeeks){
                return numWeeks === 1 ? 'nedelja' : 'nedelje';
            },
            reminderValueFormat: '{0} {1} prije početka' // e.g. "2 hours before start"
        });
    }
    
    if (exists('Extensible.form.field.DateRange')) {
        Ext.apply(Extensible.form.field.DateRange.prototype, {
            dateFormat: 'd.m.Y.'
        });
    }
    
    if (exists('Extensible.calendar.menu.Event')) {
        Ext.apply(Extensible.calendar.menu.Event.prototype, {
            editDetailsText: 'Izmena unosa',
            deleteText: 'Briši',
            moveToText: 'Premesti...'
        });
    }
    
    if (exists('Extensible.calendar.dd.DropZone')) {
        Ext.apply(Extensible.calendar.dd.DropZone.prototype, {
            dateRangeFormat: '{0}-{1}',
            dateFormat: 'd.m.'
        });
    }
    
    if (exists('Extensible.calendar.dd.DayDropZone')) {
        Ext.apply(Extensible.calendar.dd.DayDropZone.prototype, {
            dateRangeFormat: '{0}-{1}',
            dateFormat : 'd.m.'
        });
    }
    
    if (exists('Extensible.calendar.template.BoxLayout')) {
        Ext.apply(Extensible.calendar.template.BoxLayout.prototype, {
            firstWeekDateFormat: 'D m',
            otherWeeksDateFormat: 'm',
            singleDayDateFormat: 'l, m. F, Y',
            multiDayFirstDayFormat: 'd. M, Y',
            multiDayMonthStartFormat: 'd. M'
        });
    }
    
    if (exists('Extensible.calendar.template.Month')) {
        Ext.apply(Extensible.calendar.template.Month.prototype, {
            dayHeaderFormat: 'D',
            dayHeaderTitleFormat: 'l, m. F, Y'
        });
    }
});