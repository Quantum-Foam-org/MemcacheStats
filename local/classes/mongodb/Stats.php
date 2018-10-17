<?php
namespace local\classes\mongodb;

use \local\classes as local;
use \cli\traits\utility as utility;

class Stats extends local\Stats
{
    use utility\Output;
    
    protected $mongodb;
    
    public function __construct() {
        
    }
    
    public function addServer(string $host, int $port) : bool {
        try {
            $so = mongodb\ServerOpt();
            $this->getHostPort($host, $port, $so);
        } catch(\UnexpectedValueException $oe) {
            throw $oe;
        }
        $this->mongodb =  new \MongoDB\Driver\Manager("mongodb://".$so->ip.":".$so->port);
    }
    
    public function flushCache() : bool {
        
    }
    
    public function getVersion() {
        
    }
    
    public function getServerList() {
        $command = new \MongoDB\Driver\Command(['ping' => 1]);
        $this->mongodb->executeCommand('db', $command);
        
        return $this->mongodb->getServers();
    }
    
    public function getStats() {
        $this->mongodb->executeCommand("", new MongoDB\Driver\Command());
    }
    
    public function getVariables(array $variables) : array {
        
    }
}