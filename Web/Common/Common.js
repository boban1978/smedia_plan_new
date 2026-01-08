var Common = {};


Common.PageSize = 25;




Common.tipKorisnik=0;

//Funkcija za prikazivanje imena i prezimena ulogovanog korisnika
Common.LoggedUserNameSurname = function() {
    Ext.Ajax.request({
        timeout: Common.Timeout,
        url: '../App/Controllers/Korisnik.php',
        params: { action: 'KorisnikImePrezimeGet' },
        success: function (response, request) {

            var data = Ext.decode(response.responseText).data;

            Common.tipKorisnik=parseInt(data.tipKorisnik);

            var user = data.ime + ' ' + data.prezime ;
            Ext.getCmp('centralPageUserNameSurname').setText(user);
            //Ext.getCmp('centralPageUserNameSurname').render();

        },
        failure: function (response, request) {

        }
    });
};


Common.allUserPermisions = [];

//Funkcija koja dohvata privilefije koje korisnik ima
Common.getUserPermissions = function (callback) {
    
    Ext.Ajax.request({
        timeout: Common.Timeout,
        url: '../App/Controllers/Rola.php',
        params: { action: 'PermisijaGetForUser' },
        success: function (response, request) {

                if (Common.IsAjaxResponseSuccessfull(response)) {
                    var p = Ext.decode(response.responseText).data;
                    var privilegies = p.permissions;
                    callback(Ext.decode(privilegies)); //funkcijom Ext.decode() se od json stringa kreira niz
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
};










//Funkcije vezane za scheduler kompoentu
function ValidateDND(startBlockId, dragStartDate, dragEndDate, endBlockId, dropStartDate, dropEndDate) {


            //alert('ValidateDND');

        /*
                -- Funkcija za validaciju reklama prilikom premestanja (drag & drop) --

                Po defaultu, ova f-ja sprecava premestanje reklama drugih klijenata i
                dozvoljava premestanje reklama samo u okviru jednog dana.

                startBlockId = '-1' i/ili endBlockId = '-1' ako je dropStartDate van
                cetiri definisana bloka
                blockId = -1 | 1 | 2 | 3 | 4;

                Ovde treba proveriti recimo da li reklama moze da se premesti u
                izabrani reklamni blok, da li je njeno trajanje dovoljno da moze
                da stane u reklamni blok, itd.

                Funkcija treba da vrati TRUE da bi premestanje bilo uspesno,
                ili FALSE da bi premestanje bilo neuspesno (i u tom slucaju se reklama
                u scheduleru automatski vraca na pocetno mesto)
        */
         return true;
};

function ValidateCREATE(startDate, endDate) {
        /*
                -- Funkcija za validaciju prilikom kreiranja novih reklama u okviru reklamnog bloka --

                'startDate' i 'endDate' su pocetni i krajnji datum i vreme (Date object)
                kreirane reklame.

                Funkcija treba da vrati TRUE da bi kreiranje bilo uspesno ili FALSE u suprotnom.
    
    
        */
         return false;
};

function OnBeforeEventDelete(blokID, datumBloka, spotID, campaigneID, actionType, spotID_add, pozicija_add) {




        /*
                -- Okida se pre nego sto se reklama izbrise biranjem stavke iz kontekstnog menija  --

                'startDate' je datum i vreme pocetka reklame koja se brise
                'endDate' je datum i vreme zavrsetka reklame koja se brise
                'commercialBlockOrderID' je redni broj reklame koja se brise u okviru reklamnog bloka

                Funkcija treba da vrati FALSE da bi se brisanje otkazalo ili
                TRUE da se s brisanjem reklame nastavi.
        */
             //alert(campaigneID)
             var result;
    /*
             var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsCampaignePreviewWindow').getEl(), Lang.DataProcessing);
             waitBox.show();*/


            var offersCount = Ext.getCmp('clientDetailsCampaignePreviewWindow').offersCount;
            var offerNo = Ext.getCmp('clientDetailsCampaignePreviewWindow').offerNo;

            //alert(offerNo);

            if(campaigneID==undefined){
                campaigneID="";
            }


             Ext.Ajax.request({
                timeout: Common.Timeout,
                url: '../App/Controllers/Kampanja.php',
                 async: false,
                params: {action: 'BlokObrisi', blokID:blokID, datumBloka:datumBloka, spotID:spotID, campaigneID:campaigneID, offersCount:offersCount, offerNo:offerNo},
                success: function (response, request) {

                    //waitBox.hide();

                    if (Common.IsAjaxResponseSuccessfull(response)) {

                        var data = Ext.decode(response.responseText).data;

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


                        result = data;


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
                        result = false;
                        Ext.Msg.show({
                            title: Lang.Message_Title,
                            msg: (!Ext.isEmpty(response.responseText)) ? (Ext.decode(response.responseText)).msg : response.statusText,
                            buttons: Ext.Msg.OK,
                            icon: Ext.MessageBox.INFO
                        });
                    }
                },
                failure: function (response, request) {
                        result = false;
                        Ext.Msg.show({
                            title: Lang.Message_Title,
                            msg: 'Došlo je do greške u komunukaciji sa serverom',
                            buttons: Ext.Msg.OK,
                            icon: Ext.MessageBox.ERROR
                        });                    
                }
            });



    return result;

};


function OnEventDrop(sView, eventRecords) {


        alert("OnEventDrop");


        /*
                -- Izvrsava se nakon uspesnog premestanja reklame --

                'sView' je schedulerView
                'eventRecords' je niz uspesno premestenih reklama
        */
        return true;
};

function OnDragCreateEnd(sView, newEventRecord, resource) {

    alert("OnDragCreateEnd");

        /*
                -- Izvrsava se nakon uspesnog kreiranja reklame --

                'sView' je schedulerView
                'newEventRecord' je novokreirana reklama
                'resource' je resurs
        */
       
         return true;
};
Common.schConfig = {
        //posto se koriste samo sati, datum je ovde nebitan
        //ne zaboraviti da niz meseci u JS pocinje od nule (Januar = 0, Februar = 1, itd.)
        startDate: new Date(2012, 0, 1),
        endDate: new Date(2012, 0, 1),

        //pocetni i krajni sat (0 - 23) koji ce biti prikazan
        startHour: 6,
        endHour: 23,
        //showContextmenu:true,

        //datumi trajanja kampanje
        //'Id' mora biti jedinstven
        //dates: [ 
                //{ Id: '1', Name: '24.4.2012' },
                //{ Id: '2', Name: '25.4.2012' },
                //{ Id: '3', Name: '26.4.2012' } 
        //],

        //niz reklama (uredjen po rastucem redosledu po StartDate)
        //'Id' mora da bude jedinstven
        //'Title' je naziv reklame koji se prikazuje u tooltip-u
        //'StartDate' je datum i vreme pocetka reklame
        //'EndDate' je datum i vreme zavrsetka reklame
        //'ResourceId' je Id iz niza 'dates' (tj. datum kampanje na koji se reklama odnosi)
        //'Duration' je trajanje reklame u sekundama (prikazuje se u tooltipu i NE MORA da odgovara stvarnom trajanju reklame)
        //'OtherClient' je fleg koji oznacava da li reklama pripada klijentu za koga
        //se pravi kampanja (OtherClient = false) ili nekom drugom klijentu (tj. termin u reklamnom
        //bloku je zauzet; OtherClient = true)
        /*commercials: [
                { Id: '1', Title: 'Commercial1', StartDate: new Date(2012, 0, 1, 6, 15), EndDate: new Date(2012, 0, 1, 6, 17, 30), ResourceId: '1', Duration: 150, OtherClient: false },
                { Id: '2', Title: 'Commercial2', StartDate: new Date(2012, 0, 1, 6, 30), EndDate: new Date(2012, 0, 1, 6, 30, 30), ResourceId: '2', Duration: 15, OtherClient: false },
                { Id: '3', Title: 'Commercial3', StartDate: new Date(2012, 0, 1, 6, 45), EndDate: new Date(2012, 0, 1, 6, 47, 30), ResourceId: '2', Duration: 120, OtherClient: false },
                { Id: '4', Title: 'Commercial4', StartDate: new Date(2012, 0, 1, 6, 59, 30), EndDate: new Date(2012, 0, 1, 6, 59, 59), ResourceId: '3', Duration: 20, OtherClient: true }
        ],*/

        //ovo za sada uvek treba da bude true
        useDefaultCommercialSlots: true,

        //funkcija za validaciju prilikom kreiranja nove reklame u okviru reklamnog bloka
        createValidatorFn: ValidateCREATE,

        //funkcija za validaciju prilikom premestanja (drag & drop reklama)
        dndValidatorFn: ValidateDND,

        //funkcija koja se poziva pre nego sto se reklama obrise biranjem stavke iz kontekstnog menija
        beforeEventDelete: OnBeforeEventDelete,
        
        //eventdrop - Fires after a succesful drag and drop operation
        eventDrop:OnEventDrop,
                      
        //dragcreateend - Fires after a successful drag-create operation
        dragCreateEnd:OnDragCreateEnd
};
Common.schConfig2 = {
    //posto se koriste samo sati, datum je ovde nebitan
    //ne zaboraviti da niz meseci u JS pocinje od nule (Januar = 0, Februar = 1, itd.)
    startDate: new Date(2012, 0, 1),
    endDate: new Date(2012, 0, 1),

    //pocetni i krajni sat (0 - 23) koji ce biti prikazan
    startHour: 6,
    endHour: 23,
    //showContextmenu:true,

    //datumi trajanja kampanje
    //'Id' mora biti jedinstven
    //dates: [
    //{ Id: '1', Name: '24.4.2012' },
    //{ Id: '2', Name: '25.4.2012' },
    //{ Id: '3', Name: '26.4.2012' }
    //],

    //niz reklama (uredjen po rastucem redosledu po StartDate)
    //'Id' mora da bude jedinstven
    //'Title' je naziv reklame koji se prikazuje u tooltip-u
    //'StartDate' je datum i vreme pocetka reklame
    //'EndDate' je datum i vreme zavrsetka reklame
    //'ResourceId' je Id iz niza 'dates' (tj. datum kampanje na koji se reklama odnosi)
    //'Duration' je trajanje reklame u sekundama (prikazuje se u tooltipu i NE MORA da odgovara stvarnom trajanju reklame)
    //'OtherClient' je fleg koji oznacava da li reklama pripada klijentu za koga
    //se pravi kampanja (OtherClient = false) ili nekom drugom klijentu (tj. termin u reklamnom
    //bloku je zauzet; OtherClient = true)
    /*commercials: [
     { Id: '1', Title: 'Commercial1', StartDate: new Date(2012, 0, 1, 6, 15), EndDate: new Date(2012, 0, 1, 6, 17, 30), ResourceId: '1', Duration: 150, OtherClient: false },
     { Id: '2', Title: 'Commercial2', StartDate: new Date(2012, 0, 1, 6, 30), EndDate: new Date(2012, 0, 1, 6, 30, 30), ResourceId: '2', Duration: 15, OtherClient: false },
     { Id: '3', Title: 'Commercial3', StartDate: new Date(2012, 0, 1, 6, 45), EndDate: new Date(2012, 0, 1, 6, 47, 30), ResourceId: '2', Duration: 120, OtherClient: false },
     { Id: '4', Title: 'Commercial4', StartDate: new Date(2012, 0, 1, 6, 59, 30), EndDate: new Date(2012, 0, 1, 6, 59, 59), ResourceId: '3', Duration: 20, OtherClient: true }
     ],*/

    //ovo za sada uvek treba da bude true
    useDefaultCommercialSlots: true,

    //funkcija za validaciju prilikom kreiranja nove reklame u okviru reklamnog bloka
    createValidatorFn: ValidateCREATE,

    //funkcija za validaciju prilikom premestanja (drag & drop reklama)
    dndValidatorFn: ValidateDND,


    //funkcija koja se poziva pre nego sto se reklama obrise biranjem stavke iz kontekstnog menija
    beforeEventDelete: OnBeforeEventDelete,

    //eventdrop - Fires after a succesful drag and drop operation
    eventDrop:OnEventDrop,

    //dragcreateend - Fires after a successful drag-create operation
    dragCreateEnd:OnDragCreateEnd
};


Common.HandleAjaxError = function (result, request) {
    var responseJSON = Ext.JSON.decode(result.responseText);
    if (responseJSON.logoff) {
        Ext.Msg.show({
            title: 'Greška',
            msg: 'Vaša sesija je istekla. Prijavite se ponovo.',
            buttons: Ext.Msg.OK,
            fn: function () {window.location = '../../logout.php';},
            icon: Ext.MessageBox.ERROR
        });
    }
    else {
        win.close();
        Ext.Msg.show({
            title: 'Greška',
            msg: responseJSON.message,
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.ERROR
        });
    }
};


Common.loadingBox = function (target, message, cssClass) {
    return new Ext.LoadMask(
        target, {msg: message, msgCls: cssClass}
    );
};


Common.IsAjaxResponseSuccessfull = function (response) {
    if (!Ext.isEmpty(response) && !Ext.isEmpty(response.responseText)) {
        return Ext.decode(response.responseText).success;
    }
    return false;
};



Common.campaigneHelpPanelText = function () {
    var window = Ext.create('Ext.window.Window',{    
            id: 'campaigneHelpPanelWindow',
            title:Lang.MediaPlan_clients_details_campaignesPreview_infoDialog_Title,
            iconCls: 'table',
            //border: true,
            //frame:true,
            width: 380,
            height:320,
            layout: 'fit',
            bodyStyle:'padding:10px; background-color:white;',
            items: [{
                    border:false,
                    html:'<table><tr><td width="40" height="80"><img src="../Web/Images/blue_field.png"/></td><td>Polja obojena plavom bojom predstavljaju blokove u kojima je predvidjeno emitovanje vaše reklame. Broj u okviru polja predstavlja redni broj veše reklame u okviru datog bloka.</td></tr><tr><td width="40"><img src="../Web/Images/red_field.png"/></td><td>Polja obojena crvenom bojom predstavljaju blokove koji su vec zauzeti.</td></tr><tr><td></td><td>Ukoliko želite da promenite raspored emitovanja ili obrišete, vašu reklamu (bilo koje plavo polje) možete desnim klikom na polje izabrati željenu akciju</td></tr></table>'
            }],
                buttons: [{
                text: 'OK',
                handler: function () {

                    Ext.getCmp('campaigneHelpPanelWindow').close();

                }
            }]
        
    });
    window.show();
};


