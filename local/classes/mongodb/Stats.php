<?php
namespace local\classes\memcache;

use \cli\traits\utility as utility;

class Stats
{
    use utility\Output;
    
    protected $mongodb;
    
    public function __construct()
    {
        
    }
    
    public function addServer(string $host, int $port) : bool {
        try {
            $so = $this->getHostPort($host, $post);
        } catch(\UnexpectedValueException $oe) {
            throw $oe;
        }
        $this->mongodb =  new MongoDB\Driver\Manager("mongodb://".$so->ip.":".$so->port);
    }
    
    public function flushCache() : bool {
        
    }
    
    public function getVersion() {
        
    }
    
    public function getServerList() {
        $command = new MongoDB\Driver\Command(['ping' => 1]);
        $this->mongodb->executeCommand('db', $command);
        
        return $this->mongodb->getServers();
    }
    
    public function getStats() {
        
    }
    
    public function getVariables(array $variables) : array {
        
    }
}