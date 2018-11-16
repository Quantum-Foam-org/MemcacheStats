<?php
namespace local\classes;

use \local\classes as local;
use \cli\traits\utility as utility;

abstract class Stats
{
    use utility\Output;
    
    protected const DBTYPE = null;
    
    abstract public function addServer(?string $host, ?string $port) : bool;
    
    abstract public function flushCache() : bool;
    
    abstract public function getVersion();
    
    abstract public function getServerList();
    
    abstract public function getStats();
    
    abstract public function getVariables(array $variables) : array;
    
    protected function getHostPort(?string $ip, ?string $port) : ServerOpt {
        
        $so = \call_user_func(array(__NAMESPACE__.'\\'.get_called_class()::DBTYPE.'\ServerOpt', 'obj'));
        
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
