<?php
namespace local\classes\memcache;

use \cli\traits\utility as utility;

class Stats
{
    use utility\Output;
    
    protected $memcache;
    protected $programOutput = '';
    
    public function __construct($clearCache = FALSE)
    {
        $this->memcache = new \Memcached();
    }
    
    public function addServer(string $host, int $port) : bool {
        return $this->memcache->addServer('127.0.0.1', 11211);
    }

    public function flushCache() : bool {
        return $this->memcache->flush();
    }
    
    public function getVersion() {
        return $this->memcache->getVersion();
    }
    
    public function getServerList() {
        return $this->memcache->getServerList();
    }
    
    public function getStats()
    {
        return $this->memcache->getStats();
    }
    
    public function addData() {
        $this->memcache->setMulti(array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3'
        ), time() + 300);
    }
    
    public function getVariables(array $variables) : array {
        $this->memcache->getDelayed($variables, true);
        $result = $this->memcache->fetchAll();
        if ($result === FALSE) {
            $result = array($this->memcache->getResultMessage());
        }
        return $result;
    }

    public function __destruct()
    {
        $this->memcache->quit();
        $this->memcache->resetServerList();
    }
}
