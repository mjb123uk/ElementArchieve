ModElementArchive.homegrid = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'mod-elementarchive-homegrid'
		,url: ModElementArchive.config.connector_url
        ,baseParams: {
           action: 'geteadata'
        }
        ,fields: ['title', 'date']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('elementarchive_col1')
            ,dataIndex: 'title'
	    ,width: 300
	    ,tooltip: _('elementarchive_col1_qtip')
        },{
            header: _('elementarchive_col2')
            ,dataIndex: 'date'
            ,width: 150
        }]
    });
    ModElementArchive.homegrid.superclass.constructor.call(this,config);
};
Ext.extend(ModElementArchive.homegrid,MODx.grid.Grid,{
	windows: {}
    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('elementarchive_view')
            ,handler: this.viewItem
        });
        this.addContextMenuItem(m);
    }
    ,viewItem: function(btn,e) {
        if (!this.menu.record) return false;
		var eafn = this.menu.record.title;
		MODx.loadPage('viewer', 'namespace=elementarchive&fn='+eafn);

    }
    ,build: function(btn,e) {
		var fn = Ext.getCmp('elementarchive_element').getValue();
		if (Ext.isEmpty(fn)) {
			MODx.msg.alert(_('elementarchive_err_title'),_('elementarchive_err_no_folder'));
			return;
		}
        var _params = {
                action: 'build'
                 ,download: false
                ,limit: 0
				,fn: fn
                ,HTTP_MODAUTH: MODx.siteId
            },_link = ModElementArchive.config.connector_url+'?'+Ext.urlEncode(_params);
		var wthis = this;
		Ext.MessageBox.show({
			msg: _('elementarchive_create_msg'),
			progressText: _('elementarchive_progress_text'),
			width:300,
			wait:true,
			waitConfig: {interval:200}
		});
		Ext.Ajax.request({
			url: _link,
			success: function(response){
				// test for fail
				Ext.MessageBox.hide();
				wthis.refresh();
			},
			failure : function() {
				Ext.MessageBox.hide();
			}
			
		});
        return;
    }
});
Ext.reg('mod-elementarchive-homegrid',ModElementArchive.homegrid);