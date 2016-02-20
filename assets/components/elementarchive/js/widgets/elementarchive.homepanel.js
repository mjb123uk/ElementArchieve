ModElementArchive.HomePanel = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('elementarchive')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
			layout: 'form'
			,cls: 'shadowbox'
            ,border: true
            ,labelWidth: 250
            ,width: '100%'
            ,autoHeight: true
            ,buttonAlign: 'center'
            ,items: [{
				items: [{
                    html: '<p>'+_('elementarchive_desc')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'panel'
                    ,cls: 'main-wrapper'
                    ,layout: 'form'
                    ,labelAlign: 'left'
                    ,labelWidth: 160
                    ,items: [{
						xtype: 'textfield'
						,fieldLabel: _('elementarchive_element')+'<span class="required">*</span>'
						,name: 'elementarchive_element'
						,id: 'elementarchive_element'
						,labelSeparator: ''
						,anchor: '100%'
						,value: ''
						,description: _('elementarchive_element_help')
					}]
                },{
                    xtype: 'mod-elementarchive-homegrid'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
            }]
        }]
    });
	ModElementArchive.HomePanel.superclass.constructor.call(this,config);
};
Ext.extend(ModElementArchive.HomePanel,MODx.Panel);
Ext.reg('mod-elementarchive-homepanel',ModElementArchive.HomePanel);