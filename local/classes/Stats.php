<?php
namespace local\classes;

use \local\classes as local;
use \cli\traits\utility as utility;

abstract class Stats
{
    use utility\Output;
    
    public const DBTYPE = null;
    
    abstract public function addServer(?string $host, ?string $port) : bool;
    
    abstract public function flushCache() : bool;
    
    abstract public function getVersion();
    
    abstract public function getServerList();
    
    abstract public function getStats();
    
    abstract public function getVariables(array $variables) : array;
    
    protected function getHostPort(?string $ip, ?string $port) : ServerOpt {
        $dbtype = get_called_class()::DBTYPE;
        $so = \call_user_func(array(__NAMESPACE__.'\\'.$dbtype.'\ServerOpt', 'obj'));
        if (strlen($ip) !== 0) {
            try {
                $so->ip = $ip;
            } catch (\UnexpectedValueException $e) {
                unset($so);
                throw new \UnexpectedValueException('Invalid Server IP.');
            }
        } else {
            $so->ip = \common\Config::obj()->system['defaultIP'][$dbtype];
        }
        
        if (strlen($port) !== 0) {
            try {
                $so->port = $port;
            } catch (\UnexpectedValueException $e) {
                unset($so);
                throw new \UnexpectedValueException('Invalid Server Port.');
            }
        } else {
            $so->port = \common\Config::obj()->system['defaultPort'][$dbtype];
        }
        
        return $so;
    }
}
