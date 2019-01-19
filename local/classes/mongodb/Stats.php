<?php
namespace local\classes\mongodb;

use \local\classes as local;
use \cli\traits\utility as utility;

class Stats extends local\Stats
{
    use utility\Output;
    
    public const DBTYPE = 'mongodb';
    
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
        
        try {
            $this->mongodb =  new \MongoDB\Driver\Manager($uri);
        } catch (\MongoDB\Driver\Exception\InvalidArgumentException  | \MongoDB\Driver\Exception\RuntimeException $e) {
            \common\logging\Logger::obj()->writeException($e);
        }
        
        return !empty($this->getServerList());
    }
    
    public function addDB(?string $db) : bool {
        $so = ServerOpt::obj();
        try {
            $so->db = $db;
        } catch(\UnexpectedValueException $e) {
            unset($so);
            throw new \UnexpectedValueException('Error unable to add database');
        }
        
        return !empty($so->db);
    }
    
    public function addCollection(?string $collection) : bool {
        $so = ServerOpt::obj();
        try {
            $so->collection = $collection;
        } catch(\UnexpectedValueException $e) {
            unset($so);
            throw new \UnexpectedValueException('Error unable to add collection');
        }
        
        return !empty($so->collection);
    }
    
    public function flushCache() : bool {
        
    }
    
    public function getVersion() : array  {
        $cursor = $this->command(['buildInfo' => 1]);
        
        return ($cursor instanceof \MongoDB\Driver\Cursor ? $cursor->toArray() : array());
    }
    
    public function getServerList() : array {
        $cursor = $this->command(['ping' => 1]);
        
        $serverList = [];
        foreach ($this->mongodb->getServers() as $server) {
            $serverList[] = $server->getInfo();
        }
        return $serverList;
    }
    
    public function getStats() : array  {
        $cursor = $this->command(['dbStats' => 1]);
        
        return ($cursor instanceof \MongoDB\Driver\Cursor ? $cursor->toArray() : array());
    }
    
    protected function command(array $command, array $options = array()) : \MongoDB\Driver\Cursor {
        $cursor = FALSE;
        $so = ServerOpt::obj();
        
        try {
            $cursor = $this->mongodb->executeCommand($so->db, new \MongoDB\Driver\Command($command), $options);
        } catch(\MongoDB\Driver\Exception\ExecutionTimeoutException $e) {
            \common\logging\Logger::obj()->writeException($e);
        }
        
        return $cursor;
    }
    
    public function getVariables(array $variables) : array {
        $so = ServerOpt::obj();
        
        return $this->mongodb->executeQuery($so->db.'.'.$so->collection, new MongoDB\Driver\Query([], ['projection' => [$variables => 1]]));
    }
}