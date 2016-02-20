ModElementArchive.Viewer = function(config) {
    config = config || {};
	
	this.ident = config.ident || Ext.id();

	Ext.applyIf(config, {
        cls: 'ea-viewer-ct container'
        ,layout: 'border'
        ,width: '98%'
        ,height: '95%'
        ,items: [{
            region: 'west'
			//,collapseMode: 'mini'
			,split: true
			//,useSplitTips: true
			,monitorResize: true
			,width: '30%'
			,minSize: 200
			,maxSize: 400
            ,items: [{
				xtype: 'mod-elementarchive-viewtree'
			}]
            ,id: 'test-tree'
            ,cls: 'test-tree shadowbox'
            ,autoScroll: true

        },{
            region: 'center'
            //,layout: 'fit'
            ,items: [{
				xtype: 'mod-elementarchive-viewerpanel'
			}]
            ,id: 'test-view'
            ,cls: 'test-view shadowbox'
            ,autoScroll: true
            ,border: false
        }]
    });

    ModElementArchive.Viewer.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(ModElementArchive.Viewer,Ext.Container);
Ext.reg('mod-elementarchive-viewer',ModElementArchive.Viewer);