<?php

/**
 * Class ElementArchiveBuildProcessor
 *
 * For ElementArchive 'Archive Files' button.
 */
 
class ElementArchiveBuildProcessor extends modProcessor
{
    public $dir = "";
    public $start = 0;
	public $limit = 999;
	public $fn = "";
	public $cats = array();
    
    public function initialize() {
		
		//$this->dir = $this->modx->getOption('assets_path').'components/elementarchive/archive/';
		$this->dir = $this->modx->getOption('elementarchive_path',null,$this->modx->getOption('assets_path').'components/elementarchive/archive/');
		$w = $this->getProperty('start');
		if (!empty($w)) $this->start = $w;
		$w = $this->getProperty('limit');
		if (!empty($w)) $this->limit = $w;
		$this->fn = $this->getProperty('fn');

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
		$categories = $this->getdata("modCategory");
		foreach($categories as $r) {
			$id = $r["id"];
			$this->cats[$id] = $r;
		}

		$data1 = $this->getdata("modTemplate");
		$data2 = $this->getdata("modTemplateVar");
		$data3 = $this->getdata("modChunk");
		$data4 = $this->getdata("modSnippet");
		$data5 = $this->getdata("modPlugin");

		// response - not used
		$list = array($this->modx->lexicon('elementarchive_result')." :-<br><br>".$data1."<br>".$data2."<br>".$data3."<br>".$data4."<br>".$data5);

		return $this->outputArray($list,count($list));
    }
	
	private function getdata( $qn )
	{
		/* build query */
		$c = $this->modx->newQuery($qn);
		$where = array();

		// todo - add options if any ?
		if(count($where) > 0) {
			$c->where($where);
		}
		unset($where);
		$count = $this->modx->getCount($qn, $c);
		$c->limit($this->limit,$this->start);
		$elements = $this->modx->getIterator($qn, $c);
		 
		/* iterate */
		$list = array();
		if ($qn == "modCategory") $list[] = array("id" => 0, "name" => $qn, "parent" => NULL);
		foreach($elements as $element) {		
			$list[] = $this->build($qn,$element);
		}
		if ($qn == "modCategory") return $list;
		$et = str_replace("mod","",$qn)."s";
		switch ($et) {
			case "Templates":
			case "Chunks":
				$ext = ".html";
				$code = "";
				break;
			case "TemplateVars":
				$ext = ".json";
				$code = "";
				break;
			case "Snippets":
			case "Plugins":
				$ext = ".php";
				$code = "<?"."php\n";
				break;
		}
		$lc = 0;
		foreach($list as $r){
			$path = $this->get_parent_nodes($r["category"]);
			if ($path != "") $path = $path . DIRECTORY_SEPARATOR;
			$fp = $this->dir . DIRECTORY_SEPARATOR . $this->fn .DIRECTORY_SEPARATOR . $et . DIRECTORY_SEPARATOR . $path . $r["name"] . $ext;
			$this->file_force_contents($fp,$code.$r["content"]);
			$lc++;
		}
		return $et." - ".$lc;
	}
	
	private function build( $qn, $element )
	{
		$fp = array();
	
		switch ($qn) {
			case "modCategory":
				$fp = array("id" => $element->get("id"), "name" => $element->get("category"), "parent" => $element->get("parent"), "rank" => $element->get("rank") );
				break;
			case "modTemplate":
				$fp = array("id" => $element->get("id"), "name" => $element->get("templatename"), "desc" => $element->get("description"), "category" => $element->get("category"), "content" => $element->getContent() );
				break;
			case "modTemplateVar":
				$fields = $element->toArray();
				$w = $this->modx->toJSON($fields);
				$fp = array("id" => $element->get("id"), "name" => $element->get("name"), "desc" => $element->get("description"), "category" => $element->get("category"), "content" => $w );
				break;			
			case "modChunk":
			case "modSnippet":
			case "modPlugin":
				$fp = array("id" => $element->get("id"), "name" => $element->get("name"), "desc" => $element->get("description"), "category" => $element->get("category"), "content" => $element->getContent() );
				break;
		}
		
		return $fp;
	}
	
	private function get_parent_nodes($id)
	{
		$current = $this->cats[$id];
		$parent = $current["parent"];
		$parents = array();
		while (isset($this->cats[$parent])) {
			$parents[] = $current["name"];
			$current = $this->cats[$parent];
			$parent = $current["parent"];
		}
		return implode(DIRECTORY_SEPARATOR, array_reverse($parents));	
	}
	
	private function file_force_contents($filename, $data, $flags = 0)
	{
		if(!is_dir(dirname($filename)))
			mkdir(dirname($filename). DIRECTORY_SEPARATOR, 0777, TRUE);
		return file_put_contents($filename, $data, $flags);
	}
}
return 'ElementArchiveBuildProcessor';