Ext.define('Mediaplan.mediaPlan.blockDetails.Window', {
    extend: 'Ext.window.Window',
    alias: 'widget.mediaplanblockdetailswindow',

    width: 850,
    height:400,
    labelWidth: 65,
    //title: this.title,
    id:'mediaPlanBlockDetailsWindow',
    layout: 'fit',
    autoShow: true,
    iconCls: 'table',
    modal:true,


    initComponent: function(){
        
        var window = this;
        
        this.listeners = {
                show: function(rec,animateTarget){
                    M = Extensible.calendar.data.EventMappings;
                        
                    //alert(rec.data[M.StartDate.name]);
                    var datum = Ext.getCmp('app-calendar').datum;
                    var blok = Ext.getCmp('app-calendar').blok;
                    var naslov = Ext.getCmp('app-calendar').naslov;
                    this.setTitle(naslov);
                    
                    Ext.getCmp('mediaPlanBlockDetailsGrid').store.load({
                        params: {datum: datum, blok: blok}
                    });
                }
                
        };
        

        this.items = [{
                xtype:'mediaplanblockdetailsgrid'
        }];
        
        this.callParent(arguments);
    }
   
});

