
Ext.ns('SchView');

SchView.Scheduler = null;
SchView.Config = null;

SchView.CheckConfig = function(config) {
	if (Ext.isEmpty(config)) {
		alert('Scheduler configuration file is not defined!');
		return false;
	}

	if (Ext.isEmpty(config.startDate) || Ext.isEmpty(config.endDate)) {
		alert('Scheduler start and/or end date is not defined!');
		return false;
	}


	if (Ext.isEmpty(config.startHour) || Ext.isEmpty(config.endHour)) {
		alert('Scheduler start and/or end hour is not defined!');
		return false;
	}


	if (Ext.isEmpty(config.dates)) {
		alert('Medija plan je prazan !');//Dates are not defined
		return false;
	}
    //alert("config.dates:"+config.dates);

	if (Ext.isEmpty(config.commercials)) {
		alert('Scheduler commercials are not defined!');
		return false;
	}


    //alert("config.commercials:"+config.commercials);

	return true;
};

SchView.GetCommercialBlockId = function(eventStartDate, eventEndDate) {
	var Y = eventStartDate.getFullYear();
	var M = eventStartDate.getMonth();
	var D = eventStartDate.getDate();
	var h = eventStartDate.getHours();
	var m = eventStartDate.getMinutes();
	var s = eventStartDate.getSeconds();

	var blockStartDate = null, blockEndDate = null;
	
	//first block
	blockStartDate = new Date(Y, M, D, h, 15, 0);
	blockEndDate = new Date(Y, M, D, h, 17, 30);
	if (Ext.Date.between(eventStartDate, blockStartDate, blockEndDate) && Ext.Date.between(eventEndDate, blockStartDate, blockEndDate)) 
		return '1';

	//second block
	blockStartDate = new Date(Y, M, D, h, 30, 0);
	blockEndDate = new Date(Y, M, D, h, 30, 30);
	if (Ext.Date.between(eventStartDate, blockStartDate, blockEndDate) && Ext.Date.between(eventEndDate, blockStartDate, blockEndDate)) 
		return '2';

	//third block
	blockStartDate = new Date(Y, M, D, h, 45, 0);
	blockEndDate = new Date(Y, M, D, h, 47, 30);
	if (Ext.Date.between(eventStartDate, blockStartDate, blockEndDate) && Ext.Date.between(eventEndDate, blockStartDate, blockEndDate)) 
		return '3';

	//fourth block
	blockStartDate = new Date(Y, M, D, h, 59, 30);
	
	if (h == 23) {
		blockEndDate = new Date(Y, M, D + 1, 0);
	}
	else { blockEndDate = new Date(Y, M, D, h + 1); }
	
	if (Ext.Date.between(eventStartDate, blockStartDate, blockEndDate) && Ext.Date.between(eventEndDate, blockStartDate, blockEndDate)) 
		return '4';

	return '-1';
};

SchView.Render = function(config, schContainer,rowHeight) {
	if (!SchView.CheckConfig(config)) return;
	SchView.Config = config;

	//resources (Clients)

	//Sch.model.Resource has two fields ('Id' and 'Name') by default
	//For now, there is no need to subclass it

	var clientStore = Ext.create('Ext.data.JsonStore', {
		storeId: 'clientStore',
		model: 'Sch.model.Resource',
		data: config.dates
	});

	//events (Commercials)
	//Sch.model.Event has five predefined fields ('Id', 'Name', 'StartDate', 'EndDate', 'ResourceId')

	Ext.define('Commercial', {
        extend: 'Sch.model.Event',
        fields: [
			//these are additional fields (other than five predefined ones)
			{ name: 'Duration', type: 'number' },
			{ name: 'Frequency', type: 'number' },
			{ name: 'OtherClient', type: 'boolean' }
        ]
    });

	var commercialStore = Ext.create('Ext.data.JsonStore', {
        model: 'Commercial',
        storeId: 'commercialStore',
		data: config.commercials
    });

	//custom time axis
	Ext.define('CustomTimeAxis', {
		extend: "Sch.data.TimeAxis", 
		continuous: false,

		generateTicks: function(start, end, unit, increment) {
			//generate commercial slots
			var ticks = [];

			while (start <= end) {
				var Y = start.getFullYear();
				var M = start.getMonth();
				var D = start.getDate();

				//time axis is from 6am
				for (var hour = config.startHour; hour <= config.endHour; hour++) {
					//first commercial slot
					var tickStart = new Date(Y, M, D, hour, 15);
					var tickEnd = new Date(Y, M, D, hour, 17, 30);
					ticks.push({ start: tickStart, end: tickEnd });

					//second commercial slot
					tickStart = new Date(Y, M, D, hour, 30);
					tickEnd = new Date(Y, M, D, hour, 30, 30);
					ticks.push({ start: tickStart, end: tickEnd });

					//third commercial slot
					tickStart = new Date(Y, M, D, hour, 45);
					tickEnd = new Date(Y, M, D, hour, 47, 30);
					ticks.push({ start: tickStart, end: tickEnd });

					//fourth commercial slot
					tickStart = new Date(Y, M, D, hour, 59, 30);
					tickEnd = new Date(Y, M, D, hour + 1);
					ticks.push({ start: tickStart, end: tickEnd });
				}

				start = Ext.Date.add(start, Ext.Date.DAY, 1);
			}

			return ticks;
		}
	});

	//define custom preset
	Sch.preset.Manager.registerPreset("customPreset", {
        displayDateFormat: 'G:i:s', //used for event tooltips
		shiftUnit: "DAY",
        shiftIncrement: 1,
        timeColumnWidth: 30,
        timeResolution: {
            unit: "MINUTE",
            increment: 0.5
        },
        headerConfig : {
//			top : {
//                unit: "DAY",
//				increment: 1,
//                dateFormat: 'D d.m'
//            },
            middle: {
                unit: "HOUR",
				increment: 1,
                dateFormat: 'G',
				renderer: function(startDate, endDate, headerConfig, cellIdx) {
					var hour = startDate.getHours();
					return hour + 'h';
				}
            },
			bottom: {
                unit: "MINUTE",
				increment: 0.5,
                dateFormat: 'G:i',
				renderer: function(startDate, endDate, headerConfig, cellIdx) {
					//var dayName = Util.Date.GetDayName(startDate.getDay());
					//var D = startDate.getDate();
					//var M = startDate.getMonth();
					//var Y = startDate.getFullYear();
					//var hour = startDate.getHours();
					var min = startDate.getMinutes();
					//var sec = startDate.getSeconds();
					
					var title = '';
					//if (min === 15) title = 'Prvi reklamni blok [' + hour + ':15:00 - ' + hour + ':17:30' + ']';
					//if (min === 30) title = 'Drugi reklamni blok [' + hour + ':30:00 - ' + hour + ':30:30' + ']';
					//if (min === 45) title = 'Treći reklamni blok [' + hour + ':45:00 - ' + hour + ':47:30' + ']';
					//if (min === 59) title = 'Četvrti reklamni blok [' + hour + ':59:30 - ' + (hour + 1) + ':00:00' + ']';
					if (min === 15) title = 'B1';
					if (min === 30) title = 'B2';
					if (min === 45) title = 'B3';
					if (min === 59) title = 'B4';

					//return '(' + dayName + ', ' + D + '.' + (M + 1) + '.' + Y + ') ' + title;
					return title;
				}
            }
        }
    });

	//init Ext tooltips
	//Ext.QuickTips.init();


	//support for event drag-drop
	var dndEventStartDate = null, dndEventEndDate = null;
    var dndEventData = null;
    var dndProxyDate = null;

	//render scheduler using the provided config
	SchView.Scheduler = Ext.create('Sch.panel.SchedulerGrid', {
		//readOnly: true,

		height: 485,
		width: 1000,
		rowHeight: rowHeight,

		renderTo: schContainer,
		
		resourceStore: clientStore,
		
		eventStore: commercialStore,
		allowOverlap: false,

        invalidateScrollerOnRefresh: true,

		refr: function(){


            alert_obj_boban(this);

            //this.scheduler.rowHeight=100;

            this.scheduler.refresh();
        },


		//eventBodyTemplate:
		eventRenderer : function(ev, res, tplData) {


            //alert_obj_boban(ev.data);

            //var title = ev.data.Title;
			//var startHour = ev.data.StartDate.getHours();
			//var startMin = ev.data.StartDate.getMinutes();
			//var startSec = ev.data.StartDate.getSeconds();
			//var endHour = ev.data.EndDate.getHours();
			//var endMin = ev.data.EndDate.getMinutes();
			//var endSec = ev.data.EndDate.getSeconds();
			//var duration = ev.data.Duration;
			
			tplData.internalcls = 'commercialBlockColor'+ev.data.Color;

			if (ev.data.OtherClient) {
				tplData.internalcls = 'commercialSlotOther';
			};
					

			//return title + ' (' + startHour + ':' + startMin + ':' + startSec + ' - ' + endHour + ':' + endMin + ':' + endSec + ')';
			return ev.data.CommercialBlockOrderID;
        },

		//event tooltip config and template

		tipCfg : {
            cls: 'sch-tip',
            showDelay: 0,
            autoHide: true,
            anchor: 'b'
        },

		tooltipTpl: new Ext.Template(
            '<dl class="tip">',
                '<dt class="textBold">Naziv</dt>',
                '<dd class="verticalSpacer10">{Title}</dd>',
                '<dt class="textBold">Spot</dt>',
                '<dd class="verticalSpacer10">{SpotName}</dd>',
                '<dt class="textBold">Trajanje</dt>',
                '<dd class="verticalSpacer10">{Duration} s</dd>',
            '</dl>'
        ),

		listeners: {

			beforeeventdrag: function(scheduler, event, eventObject, eventOptions) {
                return false;

                //alert("beforeeventdrag"); //RADIII POCETAK DRAGGGGGGGGGGGGGGGGGGGGGGGGGGGG !!!!!!!!!!!!!!!!!!!!!!!!!!!11

				//dndEventStartDate = event.get('StartDate');
				//dndEventEndDate = event.get('EndDate');


                //dndEventData=event.data;
                //alert_obj_boban(dndEventStartDate);

			},


            aftereventdrop: function(scheduler, eventOptions) {
                return false;

                //alert("aftereventdrop"); //RADIII POCETAK DRAGGGGGGGGGGGGGGGGGGGGGGGGGGGG !!!!!!!!!!!!!!!!!!!!!!!!!!!11
                //alert("aftereventdrop"); //RADIII POCETAK DRAGGGGGGGGGGGGGGGGGGGGGGGGGGGG !!!!!!!!!!!!!!!!!!!!!!!!!!!11

                //alert(dndProxyDate);

                //alert_obj_boban(event);
                //alert_obj_boban(config);

            },

            afterdragcreate: function( scheduler, el, eventOptions ){
                return false;
                //alert('afterdragcreate');
            },




            //eventdrop - Fires after a succesful drag and drop operation

            eventdrop: function(scheduler, eventRecords, isCopy, eventOptions) {
                return false;
                //alert("eventdrop");
                //alert_obj_boban(eventRecords);


                /*
                if (Ext.isDefined(config.eventDrop)) {
                    return config.eventDrop(scheduler, eventRecords);
                }*/
            },

            //dragcreateend - Fires after a successful drag-create operation


            dragcreateend: function(sView, newEventRecord, resource, eventObject, eOpts) {
                return false;
                //alert("dragcreateend");
/*
                if (Ext.isDefined(config.dragCreateEnd)) {
                    return config.dragCreateEnd(sView, newEventRecord, resource);
                }*/
            },






			//scheduler context menu
			eventcontextmenu: function (scheduler, eventRecord, eventObject) {



				eventObject.stopEvent();



				//prevent deleting other clients' commercials

                /*
				if (eventRecord.data.OtherClient)
					return false;*/

                                if(config.showContextmenu) {

                                    //alert(eventRecord.data.BlokId);





                                    delete scheduler.ctx;


                                    if (!scheduler.ctx) {


                                            scheduler.ctx = new Ext.menu.Menu({
                                                    items: [{
                                                            text:'Dodavanje emitovanja (parametri)',
                                                            id:'schedulerContextMenuBtnAdd',
                                                            iconCls: 'scheduler-add',
                                                            handler:function(){
                                                                Ext.create('Mediaplan.mediaPlan.scheduler.ShortDialog',{
                                                                    blockID:eventRecord.data.BlokId,
                                                                    blockDate:eventRecord.data.DatumBloka,
																	campaigneID:eventRecord.data.CampaigneID
                                                                })
                                                            }
													},/*{
                                                            text:Lang.MediaPlan_scheduler_btn_Move,
                                                            id:'schedulerContextMenuBtnMove',
                                                            iconCls: 'scheduler-move',
                                                            handler:function(){
                                                                Ext.create('Mediaplan.mediaPlan.scheduler.Dialog',{
                                                                    blockID:eventRecord.data.BlokId,
                                                                    blockDate:eventRecord.data.DatumBloka,
																	campaigneID:eventRecord.data.CampaigneID,
																	spotID:eventRecord.data.SpotID
                                                                })
                                                            }
                                                    },*/{
                                                            text: Lang.MediaPlan_scheduler_btn_Delete,
                                                            id:'schedulerContextMenuBtnDelete',
                                                            iconCls: 'scheduler-delete',
                                                            handler : function() {

                                                                //alert_obj_boban(eventRecord.data);
                                                                //return;




                                                                var PreviewWindow = Ext.getCmp('clientDetailsCampaignePreviewWindow');


                                                                var waitBox = Common.loadingBox(PreviewWindow.getEl(), Lang.DataProcessing);
                                                                waitBox.show();


                                                                //alert_obj_boban(eventRecord.data);


                                                                    /*if (Ext.isDefined(config.beforeEventDelete)) {*/
/*
                                                                        alert_obj_boban(scheduler);
                                                                        alert_obj_boban(scheduler.panel.verticalScroller.scroller);

                                                                        alert_obj_boban(scheduler.panel.verticalScroller.scrollEl.dom);
                                                                        alert_obj_boban(scheduler.panel.el.dom.scrollTop);

                                                                        alert_obj_boban(scheduler.el.scroll());
                                                                        alert_obj_boban(scheduler.el.dom.scrollTop);
*/
                                                                        //alert(scheduler.getView());

                                                                        //alert(scheduler.getSchedulingView().getEl().getScroll());

                                                                        //offerNo=Ext.getCmp('clientDetailsCampaignePreviewWindow').offerNo;

                                                                        //var data = config.beforeEventDelete(eventRecord.data.BlokId,eventRecord.data.DatumBloka,eventRecord.data.SpotID,eventRecord.data.CampaigneID,config.actionType,spotID_add,pozicija_add);




                                                                        blokID=eventRecord.data.BlokId;
                                                                        datumBloka=eventRecord.data.DatumBloka;
                                                                        spotID =eventRecord.data.SpotID;
                                                                        campaigneID=eventRecord.data.CampaigneID;
                                                                        actionType=config.actionType;

                                                                        spotID_add=PreviewWindow.spotID_add;
                                                                        pozicija_add=PreviewWindow.pozicija_add;


                                                                        var offersCount = PreviewWindow.offersCount;
                                                                        var offerNo = PreviewWindow.offerNo;

                                                                        //alert(offerNo);

                                                                        if(campaigneID==undefined){
                                                                            campaigneID="";
                                                                        }


                                                                        Ext.Ajax.request({
                                                                            timeout: Common.Timeout,
                                                                            url: '../App/Controllers/Kampanja.php',
                                                                            params: {action: 'BlokObrisi', blokID:blokID, datumBloka:datumBloka, spotID:spotID, campaigneID:campaigneID, offersCount:offersCount, offerNo:offerNo},
                                                                            success: function (response, request) {

                                                                                if (Common.IsAjaxResponseSuccessfull(response)) {

                                                                                    var data = Ext.decode(response.responseText).data;



                                                                                    //var PreviewWindow = Ext.getCmp('clientDetailsCampaignePreviewWindow');
                                                                                    if(offerNo==2) {
                                                                                        PreviewWindow.price2 = data.capmaignePrice2;
                                                                                        //PreviewWindow.popust = data.popust;
                                                                                        PreviewWindow.campaigneSetPrice();
                                                                                        //scheduler.eventStore.loadData(data.schedulerCommercial2);
                                                                                        //scheduler.resourceStore.loadData(data.schedulerDates2);
                                                                                    }else{
                                                                                        PreviewWindow.price=data.capmaignePrice;
                                                                                        //PreviewWindow.popust=data.popust;
                                                                                        PreviewWindow.campaigneSetPrice();
                                                                                        //scheduler.eventStore.loadData(data.schedulerCommercial);
                                                                                        //scheduler.resourceStore.loadData(data.schedulerDates);
                                                                                    }
                                                                                    //scheduler.refresh();



/*


*/

                                                                                    eventRecord.beginEdit();
/*
                                                                                    //delete eventRecord.data.BlokId;
                                                                                    delete eventRecord.data.Title;
                                                                                    eventRecord.data.Duration=0;
                                                                                    eventRecord.data.OtherClient=false;
                                                                                    delete eventRecord.data.CommercialBlockOrderID;
                                                                                    eventRecord.data.Color=0;
                                                                                    delete eventRecord.data.SpotName;
                                                                                    delete eventRecord.data.SpotID;
*/

                                                                                    //delete eventRecord.data.BlokId;
                                                                                    delete eventRecord.data.Title;
                                                                                    eventRecord.set('Duration', 0);
                                                                                    eventRecord.set('OtherClient', false);
                                                                                    delete eventRecord.data.CommercialBlockOrderID;
                                                                                    eventRecord.set('Color', 0);
                                                                                    delete eventRecord.data.SpotName;
                                                                                    delete eventRecord.data.SpotID;


                                                                                    eventRecord.endEdit();





/*
                                                                                    alert_obj_boban(eventRecord);
                                                                                    scheduler.refresh();
                                                                                    alert_obj_boban(eventRecord);*/
                                                                                    waitBox.hide();




























                                                                                    /*
                                                                                     var PreviewWindow = Ext.getCmp('clientDetailsCampaignePreviewWindow');
                                                                                     PreviewWindow.price=data.capmaignePrice;
                                                                                     PreviewWindow.campaigneSetPrice(PreviewWindow.price,0);//popust=0;
                                                                                     */

                                                                                    /*************OVDE DODAJ FUNKCIJU KOJKA CRT DIJAGRAM***********************/


                                                                                    /*
                                                                                     alert_obj_boban(PreviewWindow.config.commercials);
                                                                                     alert_obj_boban(data.schedulerCommercial);
                                                                                     */




                                                                                    /*
                                                                                     PreviewWindow.config.commercials=data.schedulerCommercial;

                                                                                     PreviewWindow.capmaigneShow(PreviewWindow.config);
                                                                                     */


                                                                                    //result = data;


                                                                                    /*
                                                                                     if(offersCount!=2) {
                                                                                     var schedulerConfig = Common.schConfig;
                                                                                     schedulerConfig.showContextmenu = true;
                                                                                     schedulerConfig.commercials = data.schedulerCommercial;
                                                                                     schedulerConfig.dates = data.schedulerDates;
                                                                                     var campaignePrice = data.capmaignePrice;
                                                                                     var campaigneId = data.campaigneID;

                                                                                     var sablonId = data.sablonId;

                                                                                     Ext.getCmp('clientDetailsCampaignePreviewWindow').close();
                                                                                     var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindow', {
                                                                                     tbar: Ext.create('Ext.toolbar.Toolbar', {
                                                                                     items: [{
                                                                                     text: Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                                                                     iconCls: 'scheduler-add',
                                                                                     handler: function () {
                                                                                     Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                                                                     }
                                                                                     }]
                                                                                     }),
                                                                                     height: 575,
                                                                                     showTbar: true,
                                                                                     config: schedulerConfig,
                                                                                     campaigneID: campaigneId,
                                                                                     price: campaignePrice,
                                                                                     sablonId: sablonId,


                                                                                     spotID_add:spotID_add,
                                                                                     pozicija_add:pozicija_add


                                                                                     });
                                                                                     }else {//dva predloga


                                                                                     var schedulerConfig = Common.schConfig;
                                                                                     schedulerConfig.showContextmenu = true;
                                                                                     schedulerConfig.commercials = data.schedulerCommercial;
                                                                                     schedulerConfig.dates = data.schedulerDates;
                                                                                     schedulerConfig.actionType = 'campaigne';


                                                                                     var schedulerConfig2 = Common.schConfig2;
                                                                                     schedulerConfig2.showContextmenu = true;
                                                                                     schedulerConfig2.commercials = data.schedulerCommercial2;
                                                                                     schedulerConfig2.dates = data.schedulerDates2;
                                                                                     schedulerConfig2.actionType = 'campaigne';


                                                                                     var campaignePrice = data.capmaignePrice;
                                                                                     var campaignePrice2 = data.capmaignePrice2;
                                                                                     var campaigneId = data.campaigneID;
                                                                                     Ext.getCmp('clientDetailsCampaignePreviewWindow').close();
                                                                                     var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindowtwooffers', {
                                                                                     //tbar: Ext.create('Ext.toolbar.Toolbar',{
                                                                                     // items:[{
                                                                                     //text:Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                                                                     // iconCls:'scheduler-add',
                                                                                     // handler:function(){
                                                                                     // Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                                                                     // }
                                                                                     // }]
                                                                                     // }),
                                                                                     showTbar: true,
                                                                                     height: 575,
                                                                                     config: schedulerConfig,
                                                                                     config2: schedulerConfig2,
                                                                                     campaigneID: campaigneId,
                                                                                     price: campaignePrice,
                                                                                     price2: campaignePrice2,
                                                                                     offerNo:offerNo,


                                                                                     spotID_add:spotID_add,
                                                                                     pozicija_add:pozicija_add

                                                                                     });
                                                                                     //Ext.getCmp('tabsss').setActiveTab( (offerNo-1) );
                                                                                     }

                                                                                     if (data.campaigneID != '') {
                                                                                     Ext.getCmp('campaignePreviewBtnNo').hide();
                                                                                     Ext.getCmp('campaignePreviewBtnYes').hide();
                                                                                     };
                                                                                     */

                                                                                }
                                                                                else {

                                                                                    Ext.Msg.show({
                                                                                        title: Lang.Message_Title,
                                                                                        msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                                                                        buttons: Ext.Msg.OK,
                                                                                        icon: Ext.MessageBox.INFO
                                                                                    });
                                                                                    waitBox.hide();
                                                                                }
                                                                            },
                                                                            failure: function (response, request) {

                                                                                Ext.Msg.show({
                                                                                    title: Lang.Message_Title,
                                                                                    msg: 'Došlo je do greške u komunukaciji sa serverom',
                                                                                    buttons: Ext.Msg.OK,
                                                                                    icon: Ext.MessageBox.ERROR
                                                                                });
                                                                                waitBox.hide();
                                                                            }
                                                                        });






























































                                                                        //alert_obj_boban(eventRecord.data);
/*
                                                                            if (data) {

                                                                                var PreviewWindow = Ext.getCmp('clientDetailsCampaignePreviewWindow');

                                                                                if(offerNo==2) {
                                                                                    PreviewWindow.price2 = data.capmaignePrice2;
                                                                                    //PreviewWindow.popust = data.popust;
                                                                                    PreviewWindow.campaigneSetPrice();
                                                                                    scheduler.eventStore.loadData(data.schedulerCommercial2);
                                                                                    scheduler.resourceStore.loadData(data.schedulerDates2);
                                                                                }else{
                                                                                    PreviewWindow.price=data.capmaignePrice;
                                                                                    //PreviewWindow.popust=data.popust;
                                                                                    PreviewWindow.campaigneSetPrice();

                                                                                    scheduler.eventStore.loadData(data.schedulerCommercial);
                                                                                    scheduler.resourceStore.loadData(data.schedulerDates);

                                                                                }



                                                                                //scheduler.rowHeight=100;
                                                                                scheduler.refresh();
*/


                                                                                //scheduler.refr;


/*
                                                                                var els = document.getElementsByClassName("x-grid-cell");

                                                                                Array.prototype.forEach.call(els, function(el) {
                                                                                    // Do stuff here
                                                                                    alert(el.style.height);
                                                                                });
*/


                                                                                //Ext.get('schedulerContainer').setStyle('width', '500px');



                                                                                //scheduler.eventStore.remove(scheduler.ctx.rec);


                                                                                //alert_obj_boban(eventRecord.data);
                                                                                //eventRecord.data.Color=2;

                                                                                //alert_obj_boban(proceedWithDelete.schedulerCommercial[0]);
                                                                                //alert_obj_boban(scheduler.eventStore.data.items[0].data);


                                                                                //var myStore=scheduler.eventStore;


                                                                                //scheduler.eventStore.data.items=proceedWithDelete.schedulerCommercial;





                                                                                /*
                                                                                alert_obj_boban(myStore.data.items);

                                                                                alert_obj_boban(myStore.data.items[5].data);
*/
/*
                                                                                delete eventRecord.data.BlokId;
                                                                                delete eventRecord.data.Title;
                                                                                delete eventRecord.data.Duration;
                                                                                eventRecord.data.OtherClient=false;
                                                                                delete eventRecord.data.CommercialBlockOrderID;
                                                                                eventRecord.data.Color=0;
                                                                                delete eventRecord.data.SpotName;
                                                                                delete eventRecord.data.SpotID;
*/




                                                                                //data.capmaignePrice




/*
                       #$row2['BlokId'] = $row['BlokID'];
                                                                                $row2['DatumBloka'] = $dan;
                       #$row2['Title'] =  $row['Title'];//'Blok zauzet drugim reklamama';
                                                                                $row2['StartDate'] = $row['VremeStart'];
                                                                                $row2['EndDate'] = $row['VremeEnd'];
                                                                                $row2['ResourceId'] = array_search($dan, $schedulerDatesArray);
                       #$row2['Duration'] = $row['Trajanje'];
              false   $row2['OtherClient'] = $row['Flag'];
                       #$row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
             0       $row2['Color'] = $k;
                       #$row2['SpotName'] = $tempSpot['spotName'];
                       #$row2['SpotID'] = $tempSpot['spotID'];
*/




                                                                           /* }*/





                                                                    /*}
                                                                    else {
                                                                            //scheduler.eventStore.remove(scheduler.ctx.rec);
                                                                        alert("error 6139!");

                                                                    }*/


                                                            }
                                                    },{
                                                        text:'Lista spotova u bloku',
                                                        id:'schedulerContextMenuBtnSpots',
                                                        iconCls: 'scheduler-add',
                                                        handler:function(){

                                                            Ext.create('Mediaplan.mediaPlan.scheduler.BlockDetailsWindow',{
                                                                blockID:eventRecord.data.BlokId,
                                                                blockDate:eventRecord.data.DatumBloka,
                                                                //campaigneID:1//eventRecord.data.RadioStanicaID,
                                                                campaigneID:eventRecord.data.RadioStanicaID
                                                            });
                                                        }
                                                    }



                                                    ]
                                            });
                                    }
                                

                                    scheduler.ctx.rec = eventRecord;
                                    scheduler.ctx.showAt(eventObject.getXY());


                                    eventObject.stopEvent();

                                }
			},
			//eventclick - Fires when click on cell
			eventclick: function( scheduler, eventRecord, e, eOpts ) {

                //alert("eventclick");
                //alert_obj_boban(eventRecord.data);



                var PreviewWindow = Ext.getCmp('clientDetailsCampaignePreviewWindow');


                if(PreviewWindow.spotID_add==0){
                    //alert('Prethodno morate setovati(desni klik misa -> Dodavanje emitovanja (parametri))');


                    Ext.Msg.show({
                        title: Lang.Message_Title,
                        msg: 'Prethodno morate setovati(desni klik misa -> Dodavanje emitovanja (parametri))',
                        buttons: Ext.Msg.OK,
                        icon: Ext.MessageBox.ERROR
                    });



                    return;
                }



                //alert_obj_boban(eventRecord.data);



/*
                Ext.getCmp('clientDetailsCampaignePreviewWindow').blokID_add=fieldValues.blokID;
                Ext.getCmp('clientDetailsCampaignePreviewWindow').datumEmitovanja_add=fieldValues.datumEmitovanja;
                Ext.getCmp('clientDetailsCampaignePreviewWindow').campaigneID_add=fieldValues.campaigneID;

                Ext.getCmp('clientDetailsCampaignePreviewWindow').spotID_add=fieldValues.spotID;
                Ext.getCmp('clientDetailsCampaignePreviewWindow').pozicija_add=fieldValues.pozicija;
*/



                    var waitBox = Common.loadingBox(PreviewWindow.getEl(), Lang.DataProcessing);
                    waitBox.show();







                blokID=eventRecord.data.BlokId;
                datumBloka=eventRecord.data.DatumBloka;
                spotID =eventRecord.data.SpotID;
                campaigneID=eventRecord.data.CampaigneID;
                actionType=config.actionType;

                spotID_add=PreviewWindow.spotID_add;
                pozicija_add=PreviewWindow.pozicija_add;


                var offersCount = PreviewWindow.offersCount;
                var offerNo = PreviewWindow.offerNo;

                //alert(offerNo);

                if(campaigneID==undefined){

                    /*
                    Ext.Msg.show({
                        title: Lang.Message_Title,
                        msg: 'Prethodno morate setovati(desni klik misa -> Dodavanje emitovanja (parametri))',
                        buttons: Ext.Msg.OK,
                        icon: Ext.MessageBox.ERROR
                    });



                    return;*/

                    campaigneID="";
                }

                    var fieldValues = {blokID:blokID,datumEmitovanja:datumBloka,campaigneID:campaigneID,spotID:spotID_add,pozicija:pozicija_add };

                    fieldValues.offersCount=offersCount;
                    fieldValues.offerNo=offerNo;

                    //alert_obj_boban(fieldValues);



                    Ext.Ajax.request({
                        timeout: Common.Timeout,
                        url: '../App/Controllers/Kampanja.php',
                        params: {action: 'DodajBlok', fieldValues: Ext.encode(fieldValues)},
                        success: function(response, request) {


                            //alert_obj_boban(response);


                            waitBox.hide();

                            if (Common.IsAjaxResponseSuccessfull(response)) {

/*
                                alert_obj_boban(response);
                                return;*/


                                var data = Ext.decode(response.responseText).data;


                                //alert_obj_boban(data);
/*
                                 alert_obj_boban(data.spot);
                                 return;*/







                                if(offerNo==2) {
                                    PreviewWindow.price2 = data.capmaignePrice;
                                    //PreviewWindow.popust = data.popust;
                                    PreviewWindow.campaigneSetPrice();
                                    //scheduler.eventStore.loadData(data.schedulerCommercial2);
                                    //scheduler.resourceStore.loadData(data.schedulerDates2);
                                }else{
                                    PreviewWindow.price=data.capmaignePrice;
                                    //PreviewWindow.popust=data.popust;
                                    PreviewWindow.campaigneSetPrice();
                                    //scheduler.eventStore.loadData(data.schedulerCommercial);
                                    //scheduler.resourceStore.loadData(data.schedulerDates);
                                }

                                //scheduler.refresh();



                                eventRecord.beginEdit();

/*
                                //delete eventRecord.data.BlokId;
                                delete eventRecord.data.Title;
                                eventRecord.set('Duration', 0);
                                eventRecord.set('OtherClient', false);
                                delete eventRecord.data.CommercialBlockOrderID;
                                eventRecord.set('Color', 0);
                                delete eventRecord.data.SpotName;
                                delete eventRecord.data.SpotID;
*/


                                //delete eventRecord.data.BlokId;
                                eventRecord.set('Title', data.capmaigneName);
                                eventRecord.set('Duration', data.spot.SpotTrajanje);
                                //eventRecord.set('OtherClient', false);
                                eventRecord.set('CommercialBlockOrderID', data.spot.pozicija);
                                eventRecord.set('Color', data.spot.rb);
                                eventRecord.set('SpotName', data.spot.SpotName);
                                eventRecord.set('SpotID', data.spot.SpotID);

                                eventRecord.endEdit();














                                /*
                                alert_obj_boban(eventRecord.data);
                                alert_obj_boban(data.schedulerCommercial[504]);
                                alert_obj_boban(scheduler.eventStore.data.items[504].data);
*/
                                /*
                                eventRecord.data.BlockId=
                                delete eventRecord.data.Title;
                                delete eventRecord.data.Duration;
                                eventRecord.data.OtherClient=false;
                                delete eventRecord.data.CommercialBlockOrderID;
                                eventRecord.data.Color=0;
                                delete eventRecord.data.SpotName;
                                delete eventRecord.data.SpotID;

                                scheduler.refresh();


                                alert_obj_boban(scheduler.eventStore.data.items[0]);*/
        /*
                                delete eventRecord.data.BlockId;
                                delete eventRecord.data.Title;
                                delete eventRecord.data.Duration;
                                eventRecord.data.OtherClient=false;
                                delete eventRecord.data.CommercialBlockOrderID;
                                eventRecord.data.Color=0;
                                delete eventRecord.data.SpotName;
                                delete eventRecord.data.SpotID;

                                scheduler.refresh();
                                */

                                /*
                                 alert(eventRecord.data.BlokId);
                                 alert(eventRecord.data.DatumBloka);
                                 alert(eventRecord.data.CampaigneID);
                                 */


                                //scheduler.eventStore.remove(eventRecord);
                                //alert_obj_boban(scheduler.eventStore.data.items[0]);

/*
                                alert_obj_boban(eventRecord.data);


                                alert(eventRecord.data.Colore);
                                eventRecord.data.Color=2;

                                var myStore=scheduler.eventStore;
                                alert_obj_boban(myStore.data.items[5].data);
*/


                                //return;













                                //alert(scheduler.eventStore.data);


/*
                                if(offersCount!=2) {


                                    var schedulerConfig = Common.schConfig;
                                    schedulerConfig.showContextmenu = true;
                                    schedulerConfig.commercials = data.schedulerCommercial;
                                    schedulerConfig.dates = data.schedulerDates;
                                    var campaignePrice = data.capmaignePrice;
                                    var campaigneId = data.campaigneID;

                                    var sablonId = data.sablonId;

                                    Ext.getCmp('clientDetailsCampaignePreviewWindow').close();
                                    var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindow', {
                                        tbar: Ext.create('Ext.toolbar.Toolbar', {
                                            items: [{
                                                text: Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                                iconCls: 'scheduler-add',
                                                handler: function () {
                                                    Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                                }
                                            }]
                                        }),
                                        height: 575,
                                        showTbar: true,
                                        config: schedulerConfig,
                                        campaigneID: campaigneId,
                                        price: campaignePrice,
                                        sablonId: sablonId,



                                        spotID_add:fieldValues.spotID,
                                        pozicija_add:fieldValues.pozicija



                                    });
                                }else {//dva predloga


                                    var schedulerConfig = Common.schConfig;
                                    schedulerConfig.showContextmenu = true;
                                    schedulerConfig.commercials = data.schedulerCommercial;
                                    schedulerConfig.dates = data.schedulerDates;
                                    schedulerConfig.actionType = 'campaigne';


                                    var schedulerConfig2 = Common.schConfig2;
                                    schedulerConfig2.showContextmenu = true;
                                    schedulerConfig2.commercials = data.schedulerCommercial2;
                                    schedulerConfig2.dates = data.schedulerDates2;
                                    schedulerConfig2.actionType = 'campaigne';


                                    var campaignePrice = data.capmaignePrice;
                                    var campaignePrice2 = data.capmaignePrice2;
                                    var campaigneId = data.campaigneID;
                                    Ext.getCmp('clientDetailsCampaignePreviewWindow').close();
                                    var shedulerWindow = Ext.widget('clientdetailscampaignepreviewwindowtwooffers', {
                                        //tbar: Ext.create('Ext.toolbar.Toolbar',{
                                        // items:[{
                                        //text:Lang.MediaPlan_clients_details_campaignesPreview_dialog_tbar_btn_Add,
                                        // iconCls:'scheduler-add',
                                        // handler:function(){
                                        // Ext.create('Mediaplan.mediaPlan.scheduler.Dialog')
                                        // }
                                        // }]
                                        // }),
                                        showTbar: true,
                                        height: 575,
                                        config: schedulerConfig,
                                        config2: schedulerConfig2,
                                        campaigneID: campaigneId,
                                        price: campaignePrice,
                                        price2: campaignePrice2,
                                        offerNo:offerNo,


                                        spotID_add:fieldValues.spotID,
                                        pozicija_add:fieldValues.pozicija



                                    });

                                    //Ext.getCmp('tabsss').setActiveTab( (offerNo-1) );




                                }
*/


/*
                                if (data.campaigneID != '') {
                                    Ext.getCmp('campaignePreviewBtnNo').hide();
                                    Ext.getCmp('campaignePreviewBtnYes').hide();
                                }
*/


                            }
                            else {
                                Ext.Msg.show({
                                    title: Lang.Message_Title,
                                    msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.MessageBox.ERROR
                                });
                            }
                        }
                    });

























			}



		},


		//event (commercial) creating

		createValidatorFn: function(resourceRecord, startDate, endDate, event) {

			if (Ext.isDefined(config.createValidatorFn)) {
				return config.createValidatorFn(startDate, endDate);
			}
		},

		//event (commercial) resizing (changing commercial duration)
		/*resizeValidatorFn: function() { return false; },
*/
		//drag-drop validator function
		dndValidatorFn : function(dragEventRecords, targetRowRecord, proxyDate, duration, eventMouseMove){

            return false;
            //dndProxyDate=proxyDate;
            //alert(proxyDate);
/*
            //prevent moving other clients' commercials
			if (dragEventRecords[0].get('OtherClient'))
				return false;

			//only allow drops on the same row
            if (dragEventRecords[0].get('ResourceId') !== targetRowRecord.get('Id'))
				return false;
*/






//alert(SchView.GetCommercialBlockId(proxyDate, proxyDate));


            //alert(SchView.GetCommercialBlockId(proxyDate, Ext.Date.add(proxyDate, Ext.Date.MILLI, duration)));


/*
			if (Ext.isDefined(config.dndValidatorFn)) {

                //return config.dndValidatorFn(SchView.GetCommercialBlockId(dndEventStartDate, dndEventEndDate), dndEventStartDate, dndEventEndDate, SchView.GetCommercialBlockId(proxyDate, Ext.Date.add(proxyDate, Ext.Date.MILLI, duration)), proxyDate, Ext.Date.add(proxyDate, Ext.Date.MILLI, duration));


                return config.dndValidatorFn(proxyDate, 0, 0, 0, 0, 0);
			}*/
        },
		
		//loadMask: true,

		//custom time axis
		timeAxis: new CustomTimeAxis(),

		startDate: config.startDate,
		endDate: config.endDate,
		
		viewPreset: 'customPreset',
		
		columns : [
            { header: 'Datum', dataIndex: 'Name' },
            { header: 'Broj emitovanja', dataIndex: 'Frequency'}
        ],
		
		plugins: []
	});
};















/*
myGridPanel.on('render', function() {
    myGridPanel.dropZone = new Ext.dd.DropZone(myGridPanel.getView().scroller, {

        // If the mouse is over a grid row, return that node. This is
        // provided as the "target" parameter in all "onNodeXXXX" node event handling functions
        getTargetFromEvent: function(e) {
            return e.getTarget(myGridPanel.getView().rowSelector);
        },

        // On entry into a target node, highlight that node.
        onNodeEnter : function(target, dd, e, data){
            Ext.fly(target).addCls('my-row-highlight-class');
        },

        // On exit from a target node, unhighlight that node.
        onNodeOut : function(target, dd, e, data){
            Ext.fly(target).removeCls('my-row-highlight-class');
        },

        // While over a target node, return the default drop allowed class which
        // places a "tick" icon into the drag proxy.
        onNodeOver : function(target, dd, e, data){
            return Ext.dd.DropZone.prototype.dropAllowed;
        },

        // On node drop we can interrogate the target to find the underlying
        // application object that is the real target of the dragged data.
        // In this case, it is a Record in the GridPanel's Store.
        // We can use the data set up by the DragZone's getDragData method to read
        // any data we decided to attach in the DragZone's getDragData method.
        onNodeDrop : function(target, dd, e, data){
            var rowIndex = myGridPanel.getView().findRowIndex(target);
            var r = myGridPanel.getStore().getAt(rowIndex);
            Ext.Msg.alert('Drop gesture', 'Dropped Record id ' + data.draggedRecord.id +
            ' on Record id ' + r.id);
            return true;
        }
    });
}*/









