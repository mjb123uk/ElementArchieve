ModElementArchive.Viewpanel = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		xtype: 'panel'
		,cls: 'elementarchive-viewfile-panel'
		,items: [{
			formpanel: 'mod-elementarchive-viewer-exit'
				,cls: 'elementarchive-viewexit'
				,buttons: [{
					text: _('elementarchive_exit')
					,id: 'elementarchive-exit'
					,cls:'primary-button'
					,handler: function() {
						//Ext.getCmp('modx-layout').showLeftbar(true);
						MODx.loadPage('home', 'namespace=elementarchive');
					}
				}]
		}, {
			html: '<h2>'+_('elementarchive_viewpanel_title')+'</h2>'
			,cls: 'elementarchive-viewfile'
			,border: false
			,autoHeight: true
		}, {			
			xtype: 'panel'
			,width: '96%'
			,items: [{
				layout: 'form'
				,labelAlign: 'left'
				,labelWidth: 160
				,cls: 'elementarchive-viewfile-form'
				,items: [{
					xtype: 'textfield'
					,width: '100%'
					,fieldLabel: _('elementarchive_viewfile')
					,name: 'elementarchive_viewer_filename'
					,id: 'elementarchive_viewer_filename'
					,labelSeparator: ''
					,value: ''
					,readOnly:true
				}, {
					xtype: 'textarea'
					,hideLabel: true
					,width: '100%'
					//,height: 400
					,grow: true
					,growMin: 400
					,name: 'modx-file-content'
					,id: 'modx-file-content'	
					,readOnly:true
				}]								
			}, {
				xtype: 'treepanel'
				,useArrows: true
				,autoScroll: true
				,animate: true
				,enableDD: false
				,containerScroll: true
				,border: true
				,name: 'elementarchive_jsontree'
				,id: 'elementarchive_jsontree'
				,cls: 'elementarchive-jsontreepanel x-form-textarea'
				,width: '98%'
				,height: 500
				,loader: new Ext.tree.TreeLoader()
				,root: new Ext.tree.TreeNode({text: 'JSON'})
				,trackMouseOver: false
			}]
		}]
		,listeners: {
			delay: 1
			,afterrender: function() {
				var wext = Ext.getCmp('elementarchive_jsontree');
				if (wext) wext.hide();
			}
		}
    });	
	ModElementArchive.Viewpanel.superclass.constructor.call(this,config);
};
Ext.extend(ModElementArchive.Viewpanel,MODx.Panel);
Ext.reg('mod-elementarchive-viewerpanel',ModElementArchive.Viewpanel);
