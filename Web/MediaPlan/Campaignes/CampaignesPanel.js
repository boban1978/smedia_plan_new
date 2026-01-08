Ext.define('Mediaplan.mediaPlan.campaignes.Panel', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.mediaplancampaignespanel',
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
            this.id = 'campaignesPanel';
            this.items = [{
                    xtype:'mediaplancampaignesgrid',
                    region:'center',
                    flex:1
            },{
                    xtype:'mediaplancampaignesfilter',
                    region:'east',
                    collapsible:true,
                    titleCollapse:true,
                    collapsed:true,
                    width:290
            }]    
            this.callParent(arguments);
        } //eo intitcomponent
        

});







