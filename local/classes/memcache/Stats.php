<?php
namespace local\classes\memcache;

use \local\classes as local;
use \cli\traits\utility as utility;

class Stats extends local\Stats
{
    use utility\Output;
   
    public const DBTYPE = 'memcache';
    
    protected $memcache;
    protected $programOutput = '';
    
    public function __construct($clearCache = FALSE) {
        $this->memcache = new \Memcached();
    }
    
    public function addServer(?string $ip, ?string $port) : bool {
        try {
            $so = $this->getHostPort($ip, $port);
        } catch(\UnexpectedValueException $oe) {
            throw $oe;
        }
        
        return $this->memcache->addServer($so->ip, $so->port);
    }
    
    public function flushCache() : bool {
        return $this->memcache->flush();
    }
    
    public function getVersion() : array {
        return $this->memcache->getVersion();
    }
    
    public function getServerList() : array {
        return $this->memcache->getServerList();
    }
    
    public function getStats() : array
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
        $so = memcache\ServerOpt::obj();
        try {
            $so->exchangeArray(['keys' => $variables]);
        } catch(\UnexpectedValueException $e) {
            unset($so);
            throw new \UnexpectedValueException('Error unable to add keys');
        }
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
