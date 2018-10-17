<?php
namespace local\classes;

use \local\classes as local;
use \cli\traits\utility as utility;

abstract class Stats
{
    use utility\Output;
    
    abstract public function addServer(?string $host, ?int $port) : bool;
    
    abstract public function flushCache() : bool;
    
    abstract public function getVersion();
    
    abstract public function getServerList();
    
    abstract public function getStats();
    
    abstract public function getVariables(array $variables) : array;
    
    protected function getHostPort(string $ip, int $port, local\ServerOpt $so) : void {
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
    }
}
