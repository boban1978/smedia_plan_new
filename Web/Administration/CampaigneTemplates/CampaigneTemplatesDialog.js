Ext.define('Mediaplan.administration.campaigneTemplatesAdministration.CampaignesWindow', {
	extend: 'Ext.window.Window',
        alias: 'widget.campaignestemplatewindow',
        
        title: Lang.Administration_campaigneTemplate_dialog_Title,
        id:'templatesCampaigneWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
	modal: true,
	width: 750,
	//height:550,
        initComponent: function ()
	{
            
            var window = this;


            this.listeners = {
                show: function(){

                    if(typeof this.entryID != 'undefined'){
                        Ext.getCmp('hdnSablonID').setValue(this.entryID);
                        this.populateFields(this.entryID);
                    }

                }
            };








            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'templatesCampaigneForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    fileUpload: true,
                    items:[

                        {
                            xtype: 'hidden',
                            id: 'hdnSablonID',
                            name: 'sablonID',
                            value: '-1'
                        },




                        {
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_campaigneTemplate_dialog_fldSet_CampaigneData,
                            //collapsible: true,
                            items:[{
                                    xtype:'textfield',
                                    fieldLabel:Lang.Administration_campaigneTemplate__dialog_Name,
                                    name:'naziv',
                                    allowBlank:false,
                                    width:550
                            },{
									xtype:'combobox',
									fieldLabel:Lang.Administration_campaigneTemplate_dialog_Station,
									store: Ext.create('Ext.data.Store',{
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
									name:'radioStanicaID',
									queryMode: 'remote',
									typeAhead: true,
									queryParam: 'filter',
									emptyText: Lang.Common_combobox_emptyText,
									valueField: 'EntryID',
									displayField: 'EntryName',
									allowBlank:false,
									width: 550   							
							},{
                                xtype:'numberfield',
                                //labelWidth: 150,
                                fieldLabel:"Broj dana",
                                name:'trajanje',
                                allowBlank:false,
                                value: 1,
                                minValue: 1,
                                maxValue: 100,
                                width:250
                            },{
                                xtype:'numberfield',
                                //labelWidth: 150,
                                fieldLabel:"Popust",
                                name:'popust',
                                allowBlank:false,
                                value: 1,
                                minValue: 1,
                                maxValue: 100,
                                width:250
                            },




                                {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                labelAlign: 'left',
                                items: [{
                                    xtype: 'fieldset',
                                    title: 'Dani emitovanja spota',
                                    border: true,
                                    items: [{
                                        xtype: 'checkboxgroup',
                                        fieldLabel: 'Dani u nedelji kada se emituje spot',
                                        columns: 2,
                                        vertical: true,
                                        width: 300,
                                        height: 133,
                                        items: [
                                            {boxLabel: 'Ponedeljak', name: 'dan1', inputValue: '1', checked: true},
                                            {boxLabel: 'Utorak', name: 'dan2', inputValue: '2', checked: true},
                                            {boxLabel: 'Sreda', name: 'dan3', inputValue: '3', checked: true},
                                            {boxLabel: 'Četvrtak', name: 'dan4', inputValue: '4', checked: true},
                                            {boxLabel: 'Petak', name: 'dan5', inputValue: '5', checked: true},
                                            {boxLabel: 'Subota', name: 'dan6', inputValue: '6', checked: true},
                                            {boxLabel: 'Nedelja', name: 'dan7', inputValue: '7', checked: true}
                                        ]
                                    }]
                                }, {
                                    xtype: 'tbspacer',
                                    width: 15
                                }, {
                                    xtype: 'fieldset',
                                    border: true,
                                    title: 'Učestalost emitovanja spota',
                                    width: 300,
                                    height: 156,
                                    items: [{
                                        xtype: 'textfield',
                                        fieldLabel: 'Od  6h do 8h',
                                        labelWidth: 95,
                                        name: 'Ucestalost1',
                                        width: 200
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Od  8h do 12h',
                                        labelWidth: 95,
                                        name: 'Ucestalost2',
                                        width: 200
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Od 12h do 17h',
                                        labelWidth: 95,
                                        name: 'Ucestalost3',
                                        width: 200
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Od 17h do 20h',
                                        labelWidth: 95,
                                        name: 'Ucestalost4',
                                        width: 200
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Od 20h do 24h',
                                        labelWidth: 95,
                                        name: 'Ucestalost5',
                                        width: 200
                                    }]
                                }]
                            }]
                    }]


            }],



                this.buttons = [{
                    text: "Kreiraj Šablon",
                    iconCls: 'refresh',
                    handler: function() {
                        window.createCampaigne();
                    }
                }, '-', {
                    text: Lang.MediaPlan_clients_details_campaignes_dialog_btn_Cancel,
                    icon: Icons.Cancel16,
                    scope: this,
                    handler: this.close
                }];


            
                        
            this.callParent(arguments);
        },
        
        clearData: function(){
            Ext.getCmp('templatesCampaigneForm').getForm().reset();
			Ext.getCmp('weekTemplatesCampaigneWindow').close();
        },
        
        createCampaigne: function(){

                var form = Ext.getCmp('templatesCampaigneForm').getForm();
                               
                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('templatesCampaigneWindow').getEl(), Lang.CampaigneCreate);
                    waitBox.show();
                
                    
                    form.submit({
                        timeout: 600000,
                        url: '../App/Controllers/Sablon.php?action=SablonInsertUpdate',
                        success: function (form, response) {
                            waitBox.hide();
							Ext.getCmp('templatesCampaigneWindow').close();
							Ext.getCmp('campaigneTemplatesAdministrationGrid').getStore().load();

                        },
                        failure: function (fp, o) {
                            waitBox.hide();
                            alert('Greška u obradi zahteva');
                        }

                    });
                }
            
        },

        populateFields:function(entryID){
            var waitBox = Common.loadingBox(Ext.getCmp('clientDetailsContactsDialogWindow'), Lang.Loading);
            waitBox.show();
            if (entryID === -1) {
                waitBox.hide();
            }
            else {

                Ext.Ajax.request({
                    timeout: Common.Timeout,
                    url: '../App/Controllers/Kontakt.php',
                    params: { action: 'KontaktLoad', entryID: entryID },
                    success: function (response, request) {
                        var data = Ext.decode(response.responseText).data;

                        var form = Ext.getCmp('clientDetailsContactsDialogForm').getForm();
                        form.setValues(data);



                        waitBox.hide();
                    },
                    failure: function (response, request) {
                        waitBox.hide();
                    }
                });
            }
        }
        
        
});




