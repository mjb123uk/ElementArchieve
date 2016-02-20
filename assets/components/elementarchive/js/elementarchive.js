var ModElementArchive = function(config) {
    config = config || {};
    ModElementArchive.superclass.constructor.call(this,config);
};
Ext.extend(ModElementArchive,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('mod-elementarchive',ModElementArchive);
var ModElementArchive = new ModElementArchive();