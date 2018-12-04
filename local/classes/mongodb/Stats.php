<?php
namespace local\classes\mongodb;

use \local\classes as local;
use \cli\traits\utility as utility;

class Stats extends local\Stats
{
    use utility\Output;
    
    protected const DBTYPE = 'mongodb';
    
    protected $mongodb;
    
    public function __construct() {
        $this->mongodb =  new \MongoDB\Driver\Manager();
    }
    
    public function addServer(?string $host, ?string $port) : bool {
        try {
            $so = $this->getHostPort($host, $port);
        } catch(\UnexpectedValueException $oe) {
            throw $oe;
        }
        $uri = sprintf('mongodb://%s:%d', $so->ip, $so->port);
        
        $this->mongodb =  new \MongoDB\Driver\Manager($uri);
        
        return !empty($this->getServerList());
    }
    
    public function flushCache() : bool {
        
    }
    
    public function getVersion() : array  {
        $cursor = $this->mongodb->executeCommand('db', new \MongoDB\Driver\Command(['buildInfo' => 1]));
        
        return ($cursor instanceof \MongoDB\Driver\Cursor ? $cursor->toArray() : array());
    }
    
    public function getServerList() : array {
        $cursor = $this->mongodb->executeCommand('db', new \MongoDB\Driver\Command(['ping' => 1]));
        $serverList = [];
        foreach ($this->mongodb->getServers() as $server) {
            $serverList[] = $server->getInfo();
        }
        return $serverList;
    }
    
    public function getStats() : array  {
        $cursor = $this->mongodb->executeCommand('db', new \MongoDB\Driver\Command(['dbStats' => 1]));
        
        return ($cursor instanceof \MongoDB\Driver\Cursor ? $cursor->toArray() : array());
    }
    
    public function getVariables(array $variables) : array {
        
    }
}