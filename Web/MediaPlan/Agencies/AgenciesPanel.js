Ext.define('Mediaplan.mediaPlan.agencies.Panel', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.mediaplanagenciespanel',
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
            this.id = 'agenciesPanel';
            this.items = [{
                    xtype:'mediaplanagenciesgrid',
                    region:'center',
                    flex:1
            },{
                    xtype:'mediaplanagenciesfilter',
                    region:'east',
                    collapsible:true,
                    titleCollapse:true,
                    collapsed:true,
                    width:290
            }]    
            this.callParent(arguments);
        } //eo intitcomponent
        

});







