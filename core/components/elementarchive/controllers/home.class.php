<?php

class ElementArchiveHomeManagerController extends modExtraManagerController {
    
    function __construct(modX &$modx, $config = array()) {
        parent::__construct($modx, $config);
        $this->config['namespace_assets_path'] = $modx->call('modNamespace','translatePath',array(&$modx, $this->config['namespace_assets_path']));
        $this->config['assets_url'] = $modx->getOption('elementarchive.assets_url', null, $modx->getOption('assets_url').'components/elementarchive/');
        $this->config['connector_url'] = $this->config['assets_url'].'connector.php';
    }
	
	function process(array $scriptProperties = array()) {
		$dir = $this->modx->getOption('elementarchive_path',null,$this->modx->getOption('assets_path').'components/elementarchive/archive/');
		if (!is_dir($dir)) {
			$w = 'readme.txt';
			$ft = $this->modx->lexicon('elementarchive_readme_text');
			if (strpos($dir,'assets') > 0) {
				$w = 'index.php';
				$cr = "\n";
				$ft = '<?'.'php'.$cr.'$host = $_SERVER["HTTP_HOST"];'.$cr.'header("Location: http://$host");'.$cr.'exit;'.$cr.'?>';
			}
			mkdir($dir.DIRECTORY_SEPARATOR, 0777, TRUE);
			file_put_contents($dir.$w, $ft);
		}
		
        #add js
		$this->addJavascript($this->config['assets_url'].'js/widgets/elementarchive.homegrid.js');
        $this->addJavascript($this->config['assets_url'].'js/widgets/elementarchive.homepanel.js');
        $this->addJavascript($this->config['assets_url'].'js/sections/home.js');
    }

    public function getLanguageTopics() {
        return array('elementarchive:default');
    }

    public function checkPermissions() { return true;}

    function initialize(){
        $this->addHtml('<script type="text/javascript">
        ModElementArchive.config.connector_url = "'.$this->config['connector_url'].'";
        </script>');
		$this->addJavascript($this->config['assets_url'].'js/elementarchive.js');
    }
    
    function getTemplate($tpl) {
        return $this->config['namespace_path']."templates/default/{$tpl}";
    }
	
	function getTemplateFile() {
        return $this->getTemplate('home.tpl');
    }

}
?>
