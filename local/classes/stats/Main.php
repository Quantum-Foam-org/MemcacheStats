<?php

class RunStats extends  Main {
    private $memcache = null;
    
    public function __construct($clearCache = FALSE) {
	$this->memcache = new MemcacheStats();
	if ($clearCache == TRUE) {
	    $this->memcache->flush();
	}
    }
    
    public function run($prog) {
	
	exec($prog, $output);
	
	$this->programOutput = implode("\n", $output);
    }
}

abstract class Main {
    public $programOutput = '';
    public $selfOutput = '';
    
    abstract public function run(StatsTest $prog);

    public function output() {
	echo $this->selfOutput."\n\n";
	echo "Program Output: \n";
	echo $this->programOutput."\n";
    }
}