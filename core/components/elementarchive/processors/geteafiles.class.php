<?php

/**
 * Class ElementArchiveGeteafilesProcessor
 *
 * For ElementArchive TreeView.
 */
 
class ElementArchiveGeteafilesProcessor extends modProcessor
{
    public $eas = array();
    public $archiveds = array();
    public $archivefs = array();
    public $dir = "";
    public $fn = "";
    public $node = "";
    
    public function initialize() {
		//$this->dir = $this->modx->getOption('assets_path').'components/elementarchive/archive/';
		$this->dir = $this->modx->getOption('elementarchive_path',null,$this->modx->getOption('assets_path').'components/elementarchive/archive/');
		$this->fn = $this->getProperty('folder');
		$this->node = $this->getProperty('node');
		$this->fn .= DIRECTORY_SEPARATOR;

        return parent::initialize();
    }
    
    /**
     * TODO - check has permission
     */
    public function checkPermissions() { return true; }

    /**
     * Find the files within the seleced Archive folder
     *
     * @return mixed
     */
    public function process()
    {
    	$pos = 1;
		if (substr($this->node,0,5) == "xnode") {
			$this->node = "";
			$efolders = array("Templates","TemplateVars","Chunks","Snippets","Plugins");
		}
		else {
			$w = explode("~",$this->node);
			$pos = $w[0] * 1000;
			$pos++;
			$this->node = $w[1].DIRECTORY_SEPARATOR;
		}
		$this->dir .= $this->fn.$this->node;
		
		if (is_dir($this->dir)) {
			if ($dh = opendir($this->dir)) {
				while (($file = readdir($dh)))  {$files[] = $file;}
				natcasesort($files);
				foreach($files as $file) {			
					$filename = htmlentities($file,ENT_QUOTES);
					if ( ($filename == ".") || ($filename == "..") ) continue;
					if (is_dir($this->dir.$file)) {
						$w = array(
							'text' => $filename,
							'id' => $pos.'~'.$this->node.$filename,
							'leaf' => false,
							'cls' => 'folder',
							'iconCls' => 'icon icon-folder'
						);
						if ($this->node == "") $this->archiveds[$filename] = $w;
						else $this->archiveds[] = $w;
					}
					else {
						$this->archivefs[] = array(
							'text' => $filename,
							'id' => $pos,
							'leaf' => true,
							'cls' => 'file',
							'iconCls' => 'icon icon-file icon-html',
							'qtip' => $this->modx->lexicon('elementarchive_viewtree_qtip')
						);
					}
					$pos++;
				}
				closedir($dh);
				if ($this->node == "") {
					$w = array();
					foreach ($efolders as $f) {
						if ( (isset($this->archiveds[$f])) && (count($this->archiveds[$f]) > 0) ) $w[] = $this->archiveds[$f];
					}
					$this->eas = $w;		
				}
				else {
					$this->eas = array_merge($this->archiveds,$this->archivefs);
				}
			}
			else {
				return $this->modx->error->failure($this->modx->lexicon('elementarchive_err_dir_nf1').' '.$this->dir.' '.$this->modx->lexicon('elementarchive_err_dir_nf2') );
			}
		}
		else {
			return $this->modx->error->failure($this->modx->lexicon('elementarchive_err_invalid_folder').' - '.$this->dir);
		}
		return $this->toJSON($this->eas);
    }
}
return 'ElementArchiveGeteafilesProcessor';