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
    
    public function addServer(?string $ip, ?int $port) : bool {
        $so = memcache\ServerOpt::obj();
        
        if (strlen($ip) !== 0) {
            try {
                $so->ip = $ip;
            } catch (\UnexpectedValueException $e) {
                unset($so);
                throw new \UnexpectedValueException('Invalid Server IP.');
            }
        } else {
            $so->ip = \common\Config::obj()->system['defaultIP'];
        }
        
        if (strlen($port) !== 0) {
            try {
                $so->port = $port;
            } catch (\UnexpectedValueException $e) {
                unset($so);
                throw new \UnexpectedValueException('Invalid Server Port.');
            }
        } else {
            $so->port = \common\Config::obj()->system['defaultPort'];
        }
        
        return $this->memcache->addServer($ip, $port);
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
