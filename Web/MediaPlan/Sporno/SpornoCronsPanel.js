Ext.define('Mediaplan.mediaPlan.sporno.spornoCrons.Panel', {
    extend: 'Ext.form.Panel',
	alias: 'widget.spornoCronsPanel',
    border:true,
    frame:true,
    title:"Kronovi",
	width:'100%',

	initComponent: function ()
	{

        var window=this;

            this.id = 'spornoCronsPanel';

            this.items = [

                     {
                    xtype:'button',
                    text:"Kron XML",
                    iconCls:'refresh',
                    id:"cron_xml",
                    top:10,
                    left:10,
                    width:150,
                    height:50,
                    margin:10,
                    handler:function(){
                        window.cronXML();}

                },
                {
                    xtype:'button',
                    text:"Kron Reklame",
                    iconCls:'refresh',
                    id:"cron_reklame_all",
                    top:10,
                    left:210,
                    width:150,
                    height:50,
                    margin:10,
                    handler:function(){
                        window.cronReklame();}

                },
                {
                    xtype:'button',
                    text:"Kron Reklame (provera)",
                    iconCls:'refresh',
                    id:"cron_reklame_check",
                    top:10,
                    left:410,
                    width:150,
                    height:50,
                    margin:10,
                    handler:function(){
                        window.cronReklameCheck();}
                }, {
                    xtype: 'datefield',
                    top:10,
                    left:610,
                    width:150,
                    height:50,
                    margin:10,
                    id:"cron_reklame_datum",
                    format: 'Y-m-d',
                    name: 'cron_reklame_datum',
                    allowBlank: false
                },{
                    xtype: 'box',
                    id:'cronsIframe',
                    width:'100%',
                    style: "background-image:none;background-color:#fff;",
                    height:Ext.getCmp('spornoPanelCenterPanel').getHeight() - 260,
                    border:false,
                    autoEl: {
                        tag: 'iframe'
                    }
                }







            ];
            this.callParent(arguments);
        }, //eo intitcomponent

        cronXML:function(){

            var waitBox = Common.loadingBox(Ext.getCmp('cron_xml').getEl(), "processing...");
            waitBox.show();

            Ext.get('cronsIframe').dom.contentWindow.document.body.innerHTML = "";


            Ext.Ajax.request({
                url: '../App/Controllers/Cron.php',
                method: 'POST',
                params: {
                    action: 'cron_xml'
                },
                success: function(response){

                    //alert_obj_boban(response);


                    Ext.get('cronsIframe').dom.contentWindow.document.body.innerHTML = response.responseText;

                    waitBox.hide();
                }
            });



        },
        cronReklame:function(){
            var waitBox = Common.loadingBox(Ext.getCmp('cron_reklame_all').getEl(), "processing...");
            waitBox.show();

            Ext.get('cronsIframe').dom.contentWindow.document.body.innerHTML = "";




            Ext.Ajax.request({
                url: '../App/Controllers/Cron.php',
                method: 'POST',
                params: {
                    action: 'cron_reklame_all'
                },
                success: function(response){

                    //alert_obj_boban(response);


                    Ext.get('cronsIframe').dom.contentWindow.document.body.innerHTML = response.responseText;

                    waitBox.hide();
                }
            });
        },
        cronReklameCheck:function(){
            var waitBox = Common.loadingBox(Ext.getCmp('cron_reklame_check').getEl(), "processing...");
            waitBox.show();

            Ext.get('cronsIframe').dom.contentWindow.document.body.innerHTML = "";


            var datum =Ext.getCmp('cron_reklame_datum').getValue();
            if(datum!=null){
                var datum = new Date(datum);
                datum= datum.getFullYear() + '-' + (datum.getMonth() + 1) + '-' + datum.getDate();
                //alert(datum);
            }


            Ext.Ajax.request({
                url: '../App/Controllers/Cron.php',
                method: 'POST',
                params: {
                    action: 'cron_reklame_check',
                    datum: datum
                },
                success: function(response){

                    //alert_obj_boban(response);

                    Ext.get('cronsIframe').dom.contentWindow.document.body.innerHTML = response.responseText;

                    waitBox.hide();
                }
            });
        }
        
});


















