<?php
namespace local\classes\mongodb;

use \local\classes as local;
use \cli\traits\utility as utility;

class Stats extends local\Stats
{
    use utility\Output;
    
    public const DBTYPE = 'redis';
    
    protected $redis;
    
    public function __construct() {
        $this->redis = new Redis();
    }
    
    public function addServer(?string $host, ?string $port) : bool {
    	try {
            $so = $this->getHostPort($host, $port);
        } catch(\UnexpectedValueException $oe) {
            throw $oe;
        }
        
    	return $this->redis->pconnect($so->host, $so->port);
    }
    
    public function flushCache() : bool {
        
    }
    
    public function getVersion() : array  {
    }
    
    public function getServerList() : array {
    }
    
    public function getStats() : array  {
    }
    
    public function getVariables(array $variables) : array {
        
    }
}