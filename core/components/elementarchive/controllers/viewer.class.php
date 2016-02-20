<?php

class ElementArchiveViewerManagerController extends modExtraManagerController {

	public $eafn;
	
    function __construct(modX &$modx, $config = array()) {
        parent::__construct($modx, $config);
        $this->config['namespace_assets_path'] = $modx->call('modNamespace','translatePath',array(&$modx, $this->config['namespace_assets_path']));
        $this->config['assets_url'] = $modx->getOption('elementarchive.assets_url', null, $modx->getOption('assets_url').'components/elementarchive/');
        $this->config['connector_url'] = $this->config['assets_url'].'connector.php';
    }
	
    function process(array $scriptProperties = array()) {
		#add css
		$this->addCss($this->config['assets_url'].'css/mgr.css');
        #add js
		$this->addJavascript($this->config['assets_url'].'js/widgets/elementarchive.viewertree.js');
        $this->addJavascript($this->config['assets_url'].'js/widgets/elementarchive.viewerpanel.js');
        $this->addJavascript($this->config['assets_url'].'js/sections/viewer.js');
		$this->eafn = $scriptProperties["fn"];
    }

    public function getLanguageTopics() {
        return array('elementarchive:default');
    }

    public function checkPermissions() { return true;}

    function initialize() {
        $this->addHtml('<script type="text/javascript">
        ModElementArchive.config.connector_url = "'.$this->config['connector_url'].'";
        </script>');
		$this->addJavascript($this->config['assets_url'].'js/elementarchive.js');
		$this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
			MODx.add("mod-elementarchive-viewer");
        });
        </script>');
    }
	
	/**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
		$fn = $this->eafn;
		$useAce = ($this->modx->getOption('which_element_editor') == 'Ace' ) ? 1 : 0;
		$useCM = ($this->modx->getOption('which_element_editor') == 'CodeMirror' ) ? 1 : 0;
		// If using CodeMirror then setup
		if ($useCM) {
			/** @var CodeMirror $codeMirror */
			$codeMirror = $this->modx->getService('codemirror','CodeMirror',$this->modx->getOption('codemirror.core_path',null,$this->modx->getOption('core_path').'components/codemirror/').'model/codemirror/');
			if (!($codeMirror instanceof CodeMirror)) {
				$useCM = 0;
			}
			else {
			
				$options = array(
					'modx_path' => $codeMirror->config['assetsUrl'],
					'theme' => $this->modx->getOption('theme',$scriptProperties,'default'),

					'indentUnit' => (int)$this->modx->getOption('indentUnit',$scriptProperties,$this->modx->getOption('indent_unit',$scriptProperties,2)),
					'smartIndent' => (boolean)$this->modx->getOption('smartIndent',$scriptProperties,false),
					'tabSize' => (int)$this->modx->getOption('tabSize',$scriptProperties,4),
					'indentWithTabs' => (boolean)$this->modx->getOption('indentWithTabs',$scriptProperties,true),
					'electricChars' => (boolean)$this->modx->getOption('electricChars',$scriptProperties,true),
					'autoClearEmptyLines' => (boolean)$this->modx->getOption('electricChars',$scriptProperties,false),

					'lineWrapping' => (boolean)$this->modx->getOption('lineWrapping',$scriptProperties,true),
					'lineNumbers' => (boolean)$this->modx->getOption('lineNumbers',$scriptProperties,$this->modx->getOption('line_numbers',$scriptProperties,true)),
					'firstLineNumber' => (int)$this->modx->getOption('firstLineNumber',$scriptProperties,1),
					'highlightLine' => (boolean)$this->modx->getOption('highlightLine',$scriptProperties,true),
					'matchBrackets' => (boolean)$this->modx->getOption('matchBrackets',$scriptProperties,true),
					//'showSearchForm' => (boolean)$this->modx->getOption('showSearchForm',$scriptProperties,true),
					'showSearchForm' => (boolean) false,	
					'undoDepth' => $this->modx->getOption('undoDepth',$scriptProperties,40),
					'readOnly' => (boolean) true,
					'onChange' => null
				);
				        
				$options['modx_loader'] = 'onFile';
				$options['mode'] = 'php';
					
				$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">MODx.codem = '.$this->modx->toJSON($options).';</script>');
				$this->modx->regClientCSS($codeMirror->config['assetsUrl'].'css/codemirror-compressed.css');
				$this->modx->regClientCSS($codeMirror->config['assetsUrl'].'css/cm.css');
				if ($options['theme'] != 'default') {
					$this->modx->regClientCSS($codeMirror->config['assetsUrl'].'cm/theme/'.$options['theme'].'.css');
				}
				$this->modx->regClientStartupScript($codeMirror->config['assetsUrl'].'js/codemirror-compressed.js');
				$this->modx->regClientStartupScript($codeMirror->config['assetsUrl'].'js/cm.js');			
			}
		}
		
        $this->addHtml(
<<<HTML
<script type="text/javascript">
// <![CDATA[
	var eaFname = "$fn";
	var eaUseAce = $useAce;
	var eaUseCM = $useCM;
	var eaEditor = null;
    Ext.onReady(function() {
        Ext.getCmp('modx-layout').hideLeftbar(true, false);
    });
// ]]>
</script>
HTML
);	
    }
	
	function getTemplate($tpl) {
        return $this->config['namespace_path']."templates/default/{$tpl}";
    }
	
/*	
    function getTemplateFile() {
        return $this->getTemplate('viewer.tpl');
    }
*/
	
}
?>
