/*
var currentNode = {};

var eatreemenu = new Ext.menu.Menu({
	items : [{
		text: "View File"
		,handler: function(e) {
			showselectedfile(currentNode);
		}
	}]
});
*/

ModElementArchive.Viewtree = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		xtype: 'panel'
		,id: 'elementarchive_viewtree_panel'
		,items: [{
			xtype: 'treepanel',
			useArrows: true,
			autoScroll: true,
			animate: true,
			enableDD: false,
			containerScroll: true,
			border: true,
			// auto create TreeLoader
			dataUrl: ModElementArchive.config.connector_url,
			requestMethod: 'GET',
			root: {
				nodeType: 'async',
				text: eaFname,
				draggable: false,
				expanded: true,
				cls: 'elementarchive-viewtree-root',
				id: 0
			},
			rootVisible: true,
			listeners: {
				beforeload: function(treeLoader) {
					this.loader.baseParams.action = 'geteafiles';
					this.loader.baseParams.folder = eaFname;
				},
				contextmenu:function(n, eventObj){
					eventObj.stopEvent();
					var ni = n.attributes.id;
					/*
					if (typeof(ni) == 'number') {
						currentNode = n;
						eatreemenu.showAt(eventObj.getXY());
					}
					*/
				},
				click: { fn: this.showselectedfile ,scope: this }
			}
		}]
	});	
	ModElementArchive.Viewtree.superclass.constructor.call(this,config);
};
Ext.extend(ModElementArchive.Viewtree,MODx.Panel,{

	eacode : {},
	eajsontree : {},
	root : {},
	lastText : null,
	
	eajsonviewercheck: function() {
		this.eacode = Ext.getCmp('modx-file-content');
		this.eajsontree = Ext.getCmp('elementarchive_jsontree');
		this.root = this.eajsontree.getRootNode();
		var json;
		// remove any empty rows:
		var text = this.eacode.getValue().replace(/\n/g,' ').replace(/\r/g,' ');
		try {
			json = Ext.util.JSON.decode(text);
		} catch (e) {
			Ext.MessageBox.show({
				title: 'JSON error',
				msg: 'Invalid JSON variable',
				icon: Ext.MessageBox.ERROR,
				buttons: Ext.MessageBox.OK,
				closable: false
			});
			return false;
		}
		if (this.lastText === text) return;
		this.lastText = text;
		this.root.removeAll(true);
		this.root.appendChild(this.eajson2leaf(json));
		this.root.setIconCls(Ext.isArray(json) ? 'icon icon-bars' : 'icon icon-code');
		// the delay is necessary because cannot be sure that rendering has completed
		this.root.expand.defer(50, this.root, [false, false]);
	},

	eajson2leaf: function (json) {
		var wjson;
		var ret = [];
		for (var i in json) {
			if (json.hasOwnProperty(i)) {
				if (json[i] === null) {
					ret.push({text: i + ' : null', leaf: true, cls: 'file', iconCls: 'icon icon-square icon-red'});
				} else if (typeof json[i] === 'string') {
					wjson = null;
					if (json[i].substr(0,2) == '[{') {
						try {
							wjson = Ext.util.JSON.decode(json[i]);
						} catch (e) {
							wjson = null;
						}
					}
					if (wjson) ret.push({text: i, children: this.eajson2leaf(wjson), cls: 'folder', iconCls: Ext.isArray(wjson) ? 'icon icon-bars' : 'icon icon-code'});
					else ret.push({text: i + ' : "' + json[i] + '"', leaf: true, cls: 'file', iconCls: 'icon icon-square icon-blue'});
				} else if (typeof json[i] === 'number') {
					ret.push({text: i + ' : ' + json[i], leaf: true, cls: 'file', iconCls: 'icon icon-square icon-green'});
				} else if (typeof json[i] === 'boolean') {
					ret.push({text: i + ' : ' + (json[i] ? 'true' : 'false'), leaf: true, cls: 'file', iconCls: 'icon icon-square icon-yellow'});
				} else if (typeof json[i] === 'object') {
					ret.push({text: i, children: this.eajson2leaf(json[i]), cls: 'folder', iconCls: Ext.isArray(json[i]) ? 'icon icon-bars' : 'icon icon-code'});
				} else if (typeof json[i] === 'function') {
					ret.push({text: i + ' : function', leaf: true, cls: 'file', iconCls: 'icon icon-file-square icon-red'});
				}
			}
		}
		return ret;
	},
	
	showselectedfile: function(n) {
		var ni = n.attributes.id;					
		if (typeof(ni) != "number") return;
		var nfn = n.attributes.text;
		Ext.getCmp('elementarchive_viewer_filename').setValue(nfn);	
		MODx.Ajax.request({
			url: ModElementArchive.config.connector_url,
			params: {
				action: 'geteacode',
				folder: eaFname,
				node: n.parentNode.id,
				fnm: nfn
			},
			listeners: {
				success: {fn: function(response) {
					var eaEl = Ext.getCmp('modx-file-content');
					eaEl.setValue(response.results);
					var wnfn = Ext.getCmp('elementarchive_viewer_filename').getValue();
					var dp = wnfn.lastIndexOf(".");
					var wft = wnfn.substr(dp+1);
					var eaEm = "application/x-php";
					//if (wft == "html") eaEm = "text/html";
					if (wft == "json") {
						this.togglejson(true);
						return this.eajsonviewercheck();
					}
					else {
						if (eaUseAce) {
							// check initialised
							if (!eaEditor) {
								eaEl.height = 388;
								MODx.ux.Ace.replaceComponent('modx-file-content', eaEm, false);
								var elm = Ext.query('.ace_editor');
								if (elm.length == 1) eaEditor = elm[0];
							}
							// set editor to new content
							if (eaEditor) {
								//eaEditor.env.editor.session.setMode(wft); 
								eaEditor.env.editor.setValue(response.results[0],1);
								eaEditor.env.editor.setReadOnly(true);					
							}
						}
						this.togglejson(false);
						if (eaUseCM) {
							if (!eaEditor) {
								var elm = Ext.query('.CodeMirror');
								if (elm.length == 1) eaEditor = elm[0];
							}
							// set editor to new content
							if (eaEditor) {
								eaEditor.CodeMirror.focus();	
								eaEditor.CodeMirror.setValue(response.results[0]);				
							}				
						}
					}											
				}, scope: this},
				failure: {
					fn: function(r){}, scope: this
				}
			}
		});
	},
	
	togglejson: function(yn) {
		var eacd = Ext.getCmp('modx-file-content');
		var eajt = Ext.getCmp('elementarchive_jsontree');
		if (yn) {
			eacd.hide();
			eajt.show();
		}
		else {
			eajt.hide();
			eacd.show();
		}	
	}	
	
});
Ext.reg('mod-elementarchive-viewtree',ModElementArchive.Viewtree);
