<?php

/**
 * Class ElementArchiveGeteacodeProcessor
 *
 * For ElementArchive File View.
 */
 
class ElementArchiveGeteacodeProcessor extends modProcessor
{
    public $eas = array();
    public $dir = "";
    public $fn = "";
    public $node = "";
	public $fname = "";
    
    public function initialize() {
		
		//$this->dir = $this->modx->getOption('assets_path').'components/elementarchive/archive/';
		$this->dir = $this->modx->getOption('elementarchive_path',null,$this->modx->getOption('assets_path').'components/elementarchive/archive/');
		$this->fn = $this->getProperty('folder');
		$this->node = $this->getProperty('node');
		$this->fname = $this->getProperty('fnm');
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
		$w = explode("~",$this->node);
		$this->node = $w[1].DIRECTORY_SEPARATOR;
		$path = $this->dir.$this->fn.$this->node.$this->fname;

		if (is_file($path)) {
			$w = @file_get_contents($path);
			$this->eas[] = $w;
		}
		else {
			$this->eas[] = $path;
		}
		return $this->outputArray($this->eas,count($this->eas));
    }
}
return 'ElementArchiveGeteacodeProcessor';