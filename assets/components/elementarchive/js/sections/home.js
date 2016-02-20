Ext.onReady(function() {
    MODx.load({ xtype: 'mod-elementarchive-page-home'});
});

ModElementArchive.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		formpanel: 'mod-elementarchive-homepanel'
    	,buttons: [{
            text: _('elementarchive_archivefiles')
			,id: 'elementarchive-archivefiles'
            ,cls:'primary-button'
			,handler: function() {
				Ext.getCmp('mod-elementarchive-homegrid').build();
			}
        },{
            text: _('elementarchive_close')
            ,id: 'elementarchive-close'
        }]
        ,components: [{
            xtype: 'mod-elementarchive-homepanel'
            ,renderTo: 'mod-extra-elementarchive'
        }]
    });
    ModElementArchive.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(ModElementArchive.page.Home,MODx.Component);
Ext.reg('mod-elementarchive-page-home',ModElementArchive.page.Home);