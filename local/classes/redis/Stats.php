<?php
namespace local\classes\redis;

use \local\classes as local;
use \cli\traits\utility as utility;

class Stats extends local\Stats
{
    use utility\Output;
    
    public const DBTYPE = 'redis';
    
    protected $redis;
    
    public function __construct() {
        $this->redis = new \Redis();
        var_dump($this->redis);
    }
    
    public function addServer(?string $host, ?string $port) : bool {
    	try {
            $so = $this->getHostPort($host, $port);
        } catch(\UnexpectedValueException $oe) {
            throw $oe;
        }
        
    	return $this->redis->connect($so->ip, $so->port);
    }
    
    public function flushCache() : bool {
        return true;
    }
    
    public function getVersion() : array  {
        return array();
    }
    
    public function getServerList() : array {
        $so = ServerOpt::obj();
        return array($so->ip);
    }
    
    public function getStats() : array  {
        return $this->redis->info();
    }
    
    public function getVariables(array $variables) : array {
        return array();
    }
}