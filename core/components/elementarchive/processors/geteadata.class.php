<?php

/**
 * Class ElementArchiveGeteafilesProcessor
 *
 * For ElementArchive Home Grid.
 */
 
class ElementArchiveGeteadataProcessor extends modProcessor
{
    public $eas = array();
    public $dir = "";

    public function initialize() {
		//$this->dir = $this->modx->getOption('assets_path').'components/elementarchive/archive/';
		$this->dir = $this->modx->getOption('elementarchive_path',null,$this->modx->getOption('assets_path').'components/elementarchive/archive/');
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
		if (is_dir($this->dir)) {
			if ($dh = opendir($this->dir)) {
				while (($file = readdir($dh)))  {$files[] = $file;}
				//natcasesort($files);
				foreach($files as $file) {
					if (is_dir($this->dir.$file)) {
						$stat = stat($this->dir.$file);
						//$fdate =  date("M d, Y H:i:s", $stat[9]);	
						$fdate =  date($this->modx->getOption('manager_date_format') .' - '. $this->modx->getOption('manager_time_format'), $stat[9]);	
						$filename = htmlentities($file,ENT_QUOTES);
						if ( ($filename != ".") && ($filename != "..") ) $this->eas[] = array('title' => $filename,'date' => $fdate);
					}
				}
				closedir($dh);
			}
			else {
				return $this->modx->error->failure($this->modx->lexicon('elementarchive_err_dir_nf1').' '.$this->dir.' '.$this->modx->lexicon('elementarchive_err_dir_nf2') );
			}
		}
		else {
			return $this->modx->error->failure($this->modx->lexicon('elementarchive_err_invalid_folder').' - '.$this->dir);
		}
		return $this->outputArray($this->eas,count($this->eas));
	}
}
return 'ElementArchiveGeteadataProcessor';