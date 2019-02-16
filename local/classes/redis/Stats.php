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
        return $this->redis->flushDb();
    }
    
    public function getVersion() : string  {
        $serverInfo = $this->redis->info('server');
        
        return ($serverInfo['redis_version'] ?: '');
    }
    
    public function getServerList() : array {
        $serverInfo = array();
        try {
            $serverInfo = $this->redis->info('replication');
            if ($serverInfo['role'] === 'replica' && isset($serverInfo['master_host'])) {
                $serverInfo = $serverInfo['master_host'];
            } else {
                $serverInfo = array('role' => $serverInfo['role']);
                \common\logging\Logger::obj()->write('Role must be replica to print ip address of Redis server');
            }
        } catch(\RedisException $e) {
            \common\logging\Logger::obj()->writeException($e);
        }
        
        return $serverInfo;
    }
    
    public function getStats() : array  {
        return $this->redis->info();
    }
    
    public function getVariables(array $variables) : array {
        $so = ServerOpt::obj();
        try {
            $so->exchangeArray(['keys' => $variables]);
        } catch(\UnexpectedValueException $e) {
            unset($so);
            throw new \UnexpectedValueException('Error unable to add keys');
        }
        $result = array();
        
        foreach ($so->keys as $key) {
            $result[] = $this->redis->get($key);
        }
        
        return $result;
    }
    
    public function ping() : string {
        return $this->redis->ping();
    }
}