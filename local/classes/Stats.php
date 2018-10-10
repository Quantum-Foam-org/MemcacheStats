<?php
namespace local\classes;

use \cli\traits\utility as utility;

interface Stats
{
    use utility\Output;
    
    public function addServer(string $host, int $port) : bool;
    
    public function flushCache() : bool;
    
    public function getVersion();
    
    public function getServerList();
    
    public function getStats();
    
    public function getVariables(array $variables) : array;
    
    protected function getHostPort($ip, $port) : memcache\ServerOpt {
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
        
        return $so;
    }
}
