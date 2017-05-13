<?php

namespace \local\classes\memcache;

//$memcache = new MemcacheStats();
//$memcache->menu();

class Stats extends Memcache {
    
    public function __construct() {
	$this->connect('cache01', 11211);
    }
    
    public function menu() {
	echo "1.) Statistics - 1\n";
	echo "2.) Variables - 2\n";
	
	$selection = readline('Selection: ');
	
	if ($selection == 1) {
	    $this->printStats();
	} else if ($selection == 2) {
	    $this->printVariablesReport();
	} else {
	    $this->menu();
	}
    }
    
    public function printStats() {
	echo 'Version: '.$this->getVersion()."\n\n";
	echo 'Status: '.$this->getServerStatus('cache01', 11211)."\n\n";
	printArray($this->getExtendedStats(), 'General Stats');
	$this->endSection();
	printArray($this->getSlabs(), 'Slabs');
	$this->endSection();
	$slabKeys = $this->getSlabs(TRUE);
	foreach ($slabKeys as $slab) {
	    if (is_int($slab)) {
		printArray($this->getCacheDump($slab), 'Cache Dump Slab: '.$slab);
	    }
	}
	$this->endSection();
	printArray($this->getItems(), 'Items');
    }
    
    private function endSection() {
	readline('Enter to Continue.');
    }
    
    public function getSlabs($keys = FALSE) {
	return ($keys ? array_keys($this->getStats('slabs')) : $this->getStats('slabs'));
    }
    
    public function getItems() {
	return $this->getStats('items');
    }
    
    public function getCacheDump($slab) {
	if (!is_array($cacheDump = $this->getStats('cachedump', $slab))) {
	    $cacheDump = array();
	}
	
	return $cacheDump;
    }
    
    public function printVariablesReport() {
	$slabs = $this->getSlabs(TRUE);
	foreach ($slabs as $slab) {
	    if (is_int($slab)) {
		foreach($this->getCacheDump($slab) as $key => $item) {
		    var_dump($this->printVariable($key));
		}
	    }
	}
    }
    
    public function printVariable($varId) {
	return $this->get($varId);
    }
    
    public function __destruct() {
	$this->close();
    }
}


$mrs = new MemcacheRunStats();
$mrs->run('./user_agent_curl.php');
$mrs->output();
