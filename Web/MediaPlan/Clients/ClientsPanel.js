Ext.define('Mediaplan.mediaPlan.clients.Panel', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.mediaplanclientspanel',
        border:false,
        frame:false,
        padding:0,
	width:'100%',
        height:'100%',
        layout: {
            //type: 'hbox',
            type:'border',
            align:'strech'
        },
	initComponent: function ()
	{
        this.id = 'clientsPanel';
        this.items = [{
                xtype:'mediaplanclientsgrid',
                region:'center',
                flex:1
        },{
                xtype:'mediaplanclientsfilter',
                region:'east',
                collapsible:true,
                titleCollapse:true,
                collapsed:true,
                width:290
        }]
        this.callParent(arguments);
    } //eo intitcomponent
        

});





