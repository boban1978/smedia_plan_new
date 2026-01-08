Ext.define('Mediaplan.administration.weekTemplatesAdministration.CampaignesWindow', {
	extend: 'Ext.window.Window',
        alias: 'widget.weekstemplatewindow',
        
        title: Lang.Administration_weekTemplate_dialog_Title,
        id:'weekTemplatesCampaigneWindow',
	layout: 'fit',
	autoShow: true,
	iconCls: 'table',
	modal: true,
	width: 750,
	//height:550,
        initComponent: function ()
	{
            
            var window = this;
            
            
            this.items = [{
                    xtype:'form',
                    bodyPadding: 10,
                    id: 'weekTemplatesCampaigneForm',
                    border: false,
                    frame: false,
                    labelWidth: 120,
                    fileUpload: true,
                    items:[{
                            xtype:'fieldset',
                            border:true,
                            title:Lang.Administration_weekTemplate_dialog_fldSet_CampaigneData,
                            //collapsible: true,
                            items:[{
                                    xtype:'textfield',
                                    fieldLabel:Lang.Administration_weekTemplate__dialog_Name,
                                    name:'naziv',
                                    allowBlank:false,
                                    width:550
                            },{
									xtype:'combobox',
									fieldLabel:Lang.Administration_weekTemplate_dialog_Station,
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
                                    xtype: 'fieldcontainer',
                                    layout: 'hbox',
                                    labelAlign: 'left',
                                    items: [{
                                            xtype:'datefield',
                                            format:'Y-m-d',
                                            fieldLabel:Lang.Administration_weekTemplate_dialog_DateStart,
                                            name:'datumPocetak',
                                            allowBlank:false,
                                            width:250
                                    },{
                                            xtype:'datefield',
                                            labelAlign:'right',
                                            labelWidth: 150,
                                            format:'Y-m-d',
                                            fieldLabel:Lang.Administration_weekTemplate_dialog_DateEnd,
                                            name:'datumZavrsetak',
                                            allowBlank:false,
                                            width:300
                                    }]
                            },{
                                            xtype:'numberfield',
                                            //labelWidth: 150,
                                            fieldLabel:Lang.Administration_weekTemplate_dialog_Discount,
                                            name:'popust',
                                            allowBlank:false,
                                            value: 1,
                                            minValue: 1,
                                            maxValue: 100,
                                            width:250                                          
                            },{
                                    xtype:'textfield',
                                    inputType:'file',
                                    hidden:true,
                                    fieldLabel:Lang.Administration_weekManual_dialog_dialog_Spot,
                                    name:'spot',
                                    width:450                                
                            },{
                                    xtype:'fieldset',
                                    border:true,
                                    items:[{
                                        xtype: 'checkboxgroup',
                                        fieldLabel: Lang.Administration_weekManual_dialog_dialog_WeekDays,
                                        columns: 2,
                                        vertical: true,
                                        width:500,
                                        items: [
                                            { boxLabel: 'Ponedeljak', name: 'dan1', inputValue: '1', checked: true },
                                            { boxLabel: 'Utorak', name: 'dan2', inputValue: '2', checked: true },
                                            { boxLabel: 'Sreda', name: 'dan3', inputValue: '3', checked: true },
                                            { boxLabel: 'Četvrtak', name: 'dan4', inputValue: '4', checked: true },
                                            { boxLabel: 'Petak', name: 'dan5', inputValue: '5', checked: true },
                                            { boxLabel: 'Subota', name: 'dan6', inputValue: '6', checked: true },
                                            { boxLabel: 'Nedelja', name: 'dan7', inputValue: '7', checked: true }
                                        ]
                                    }]
                   },{
                                xtype: 'fieldcontainer',
                    		layout: 'hbox',
                                items:[{
                                        xtype:'tbfill'
                                },{
                                    xtype:'button',
                                    text:Lang.Administration_weekTemplate_dialog_btn_Create,
                                    iconCls: 'table',
                                    handler:function(){
                                        this.ownerCt.ownerCt.ownerCt.ownerCt.createCampaigne();
                                    }
                                },{
                                    xtype:'splitter',
                                    width:5
                                },{
                                    xtype:'button',
                                    text:Lang.Administration_weekTemplate_dialog_btn_Cancel,
                                    icon:Icons.Cancel16,
                                    handler:function(){
                                        this.ownerCt.ownerCt.ownerCt.ownerCt.clearData();
										
                                    }
                                }]
                            }]
                    }]
            }],
            
                        
            this.callParent(arguments);
        },
        
        clearData: function(){
            Ext.getCmp('weekTemplatesCampaigneForm').getForm().reset();
			Ext.getCmp('weekTemplatesCampaigneWindow').close();
        },
        
        createCampaigne: function(){

                var form = Ext.getCmp('weekTemplatesCampaigneForm').getForm();
                               
                if (form.isValid()) {
                    var waitBox = Common.loadingBox(Ext.getCmp('weekTemplatesCampaigneWindow').getEl(), Lang.CampaigneCreate);
                    waitBox.show();
                  
                    
                    form.submit({
                        timeout: 600000,
                        url: '../App/Controllers/Sablon.php?action=NedeljniSablonInsertUpdate',
                        success: function (form, response) {
                            waitBox.hide();
							Ext.getCmp('weekTemplatesCampaigneWindow').close();
							Ext.getCmp('weekTemplatesAdministrationGrid').getStore().load();

                        },
                        failure: function (fp, o) {
                            waitBox.hide();
                            alert('Greška u obradi zahteva');
                        }

                    });
                }
            
        }
        
        
});






